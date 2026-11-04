<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use App\Models\DeliveryRecord;
use App\Models\EquipmentMovement;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use App\Models\MunicipalArea;
use App\Models\User;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFlowTest extends TestCase
{
    use RefreshDatabase;

    private function authenticatedUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    private function authenticatedSuppliesOperator(): User
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPPLIES_OPERATOR,
            'permissions' => [
                User::PERMISSION_ITEMS,
                User::PERMISSION_DELIVERY_ACTS,
            ],
        ]);
        $this->actingAs($user);

        return $user;
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_is_rate_limited_after_repeated_failed_attempts(): void
    {
        User::factory()->create([
            'username' => 'blockeduser',
            'email' => 'blocked@example.com',
            'password' => 'secret123',
        ]);

        foreach (range(1, 5) as $attempt) {
            $response = $this->from('/login')->post('/login', [
                'username' => 'blockeduser',
                'password' => 'incorrecta',
            ]);

            $response->assertRedirect('/login');
        }

        $blockedResponse = $this->from('/login')->post('/login', [
            'username' => 'blockeduser',
            'password' => 'incorrecta',
        ]);

        $blockedResponse->assertRedirect('/login');
        $blockedResponse->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_database_seeder_does_not_create_default_admin_without_seed_variables(): void
    {
        config()->set('app.env', 'testing');
        putenv('SEED_ADMIN_EMAIL');
        putenv('SEED_ADMIN_PASSWORD');
        putenv('SEED_ADMIN_NAME');
        putenv('SEED_ADMIN_PHONE');
        putenv('SEED_ADMIN_POSITION');

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseMissing('users', [
            'email' => 'admin@inventarioinnovacion.local',
        ]);
    }

    public function test_inventory_page_loads(): void
    {
        $this->authenticatedUser();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Dashboard');
        $cacheControl = (string) $response->headers->get('cache-control');

        $this->assertStringContainsString('no-store', $cacheControl);
        $this->assertStringContainsString('no-cache', $cacheControl);
    }

    public function test_can_register_a_new_item(): void
    {
        $this->authenticatedUser();

        $response = $this->post('/insumos', [
            'name' => 'Mouse',
            'brand' => 'Logitech',
            'model' => 'M90',
            'unit' => 'unidad',
        ]);

        $response->assertRedirect('/insumos');

        $this->assertDatabaseHas('items', [
            'name' => 'Mouse',
            'brand' => 'Logitech',
            'model' => 'M90',
        ]);
    }

    public function test_user_username_validation_rejects_invalid_format(): void
    {
        $this->authenticatedUser();

        $response = $this->post('/usuarios', [
            'name' => 'Usuario Malo',
            'username' => 'usuario invalido',
            'email' => 'malo@example.com',
            'phone' => '7001-0001',
            'position' => 'Tecnica',
            'role' => User::ROLE_SUPPLIES_OPERATOR,
            'permissions' => [
                User::PERMISSION_ITEMS,
            ],
            'is_active' => '1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertDatabaseMissing('users', [
            'email' => 'malo@example.com',
        ]);
    }

    public function test_can_update_item_from_items_module(): void
    {
        $this->authenticatedUser();

        $item = Item::query()->create([
            'name' => 'Mouse',
            'brand' => 'Logitech',
            'model' => 'M90',
            'unit' => 'unidad',
        ]);

        $response = $this->put("/insumos/{$item->id}", [
            'name' => 'Mouse inalambrico',
            'brand' => 'Logitech',
            'model' => 'M170',
            'unit' => 'unidad',
        ]);

        $response->assertRedirect('/insumos');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Mouse inalambrico',
            'model' => 'M170',
        ]);
    }

    public function test_can_delete_item_without_movements(): void
    {
        $this->authenticatedUser();

        $item = Item::query()->create([
            'name' => 'Cable HDMI',
            'brand' => 'Genérico',
            'model' => '2M',
            'unit' => 'unidad',
        ]);

        $response = $this->delete("/insumos/{$item->id}");

        $response->assertRedirect('/insumos');

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);
    }

    public function test_item_with_movements_cannot_be_deleted(): void
    {
        $this->authenticatedUser();

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina']
        );

        $item = Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        InventoryMovement::query()->create([
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $office->id,
            'quantity' => 1,
            'performed_at' => now(),
        ]);

        $response = $this->delete("/insumos/{$item->id}");

        $response->assertRedirect('/insumos');
        $response->assertSessionHasErrors('item');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
        ]);
    }

    public function test_exit_cannot_exceed_available_stock(): void
    {
        $this->authenticatedUser();

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina']
        );

        $item = Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        InventoryMovement::query()->create([
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $office->id,
            'quantity' => 1,
            'performed_at' => now(),
        ]);

        $response = $this->from('/movimientos')->post('/movimientos', [
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_EXIT,
            'from_location_id' => $office->id,
            'quantity' => 2,
            'performed_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/movimientos');
        $response->assertSessionHasErrors('quantity');
    }

    public function test_summary_export_excel_returns_csv_download(): void
    {
        $this->authenticatedUser();

        $response = $this->get('/exportar/resumen/excel');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $response->assertHeader('content-disposition', 'attachment; filename="resumen_inventario_innovacion.csv"');
    }

    public function test_items_summary_export_excel_returns_csv_download(): void
    {
        $this->authenticatedUser();

        $response = $this->get('/exportar/insumos/resumen/excel');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $response->assertHeader('content-disposition', 'attachment; filename="resumen_total_por_insumo.csv"');
    }

    public function test_items_can_be_filtered_by_search(): void
    {
        $this->authenticatedUser();

        Item::query()->create([
            'name' => 'Teclado',
            'brand' => 'Logitech',
            'model' => 'MK120',
            'unit' => 'unidad',
        ]);

        Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        $response = $this->get('/insumos?search=Brother');

        $response->assertOk();
        $response->assertSee('Tinta');
        $response->assertDontSee('Teclado');
    }

    public function test_stock_lookup_page_loads(): void
    {
        $this->authenticatedUser();

        $response = $this->get('/consulta-stock');

        $response->assertOk();
        $response->assertSee('Consulta de stock');
    }

    public function test_movements_can_be_filtered_by_type(): void
    {
        $this->authenticatedUser();

        $bodega = Location::query()->firstOrCreate(
            ['code' => 'BODEGA'],
            ['name' => 'Bodega']
        );

        $item = Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        InventoryMovement::query()->create([
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $bodega->id,
            'quantity' => 2,
            'notes' => 'Ingreso de prueba',
            'performed_at' => now(),
        ]);

        InventoryMovement::query()->create([
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_EXIT,
            'from_location_id' => $bodega->id,
            'quantity' => 1,
            'notes' => 'Salida de prueba',
            'performed_at' => now(),
        ]);

        $response = $this->get('/movimientos?type=entry');

        $response->assertOk();
        $response->assertSee('Ingreso de prueba');
        $response->assertDontSee('Salida de prueba');
    }

    public function test_entry_ignores_origin_location_to_avoid_negative_stock(): void
    {
        $this->authenticatedUser();

        $bodega = Location::query()->firstOrCreate(
            ['code' => 'BODEGA'],
            ['name' => 'Bodega']
        );

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina']
        );

        $item = Item::query()->create([
            'name' => 'Mouse',
            'brand' => 'Logitech',
            'model' => 'M90',
            'unit' => 'unidad',
        ]);

        $response = $this->post('/movimientos', [
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'from_location_id' => $office->id,
            'to_location_id' => $bodega->id,
            'quantity' => 5,
            'performed_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/movimientos');

        $this->assertDatabaseHas('inventory_movements', [
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'from_location_id' => null,
            'to_location_id' => $bodega->id,
            'quantity' => 5,
        ]);
    }

    public function test_delivery_act_form_loads(): void
    {
        $this->authenticatedUser();

        $response = $this->get('/actas-entrega');

        $response->assertOk();
        $response->assertSee('Acta de entrega');
    }

    public function test_delivery_act_form_can_prefill_item_from_stock_lookup(): void
    {
        $this->authenticatedUser();

        $item = Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        $response = $this->get('/actas-entrega?item_id='.$item->id);

        $response->assertOk();
        $response->assertSee('Insumo precargado desde consulta de stock');
        $response->assertSee('Tinta - Brother BTD60BK');
    }

    public function test_delivery_act_history_loads(): void
    {
        $this->authenticatedUser();

        DeliveryRecord::query()->create([
            'type' => DeliveryRecord::TYPE_SUPPLIES,
            'delivery_date' => now()->toDateString(),
            'district' => 'Zacatecoluca',
            'area_name' => 'Informatica',
            'responsible_name' => 'Jerson Ernesto Zelaya Villalta',
            'receives' => 'Juan Perez',
            'from_location_name' => 'Bodega',
            'total_lines' => 1,
        ]);

        $response = $this->get('/actas-entrega/historial');

        $response->assertOk();
        $response->assertSee('Historial de actas');
        $response->assertSee('Informatica');
    }

    public function test_delivery_act_pdf_downloads(): void
    {
        $this->authenticatedUser();

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina']
        );

        $item = Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        InventoryMovement::query()->create([
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $office->id,
            'quantity' => 3,
            'performed_at' => now(),
        ]);

        $area = MunicipalArea::query()->create([
            'name' => 'Informatica',
            'slug' => 'informatica-insumos',
            'responsible_name' => 'Jerson Ernesto Zelaya Villalta',
            'institutional_email' => 'innovacion@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $response = $this->post('/actas-entrega/pdf', [
            'delivery_date' => now()->format('Y-m-d'),
            'district' => 'Zacatecoluca',
            'assigned_area_id' => $area->id,
            'receives' => 'Juan Perez',
            'from_location_id' => $office->id,
            'rows' => [
                ['quantity' => '1', 'item_id' => $item->id],
            ],
        ]);

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=acta_entrega_insumos.pdf');

        $this->assertDatabaseHas('inventory_movements', [
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_EXIT,
            'from_location_id' => $office->id,
            'quantity' => 1,
        ]);

        $record = DeliveryRecord::query()->where('type', DeliveryRecord::TYPE_SUPPLIES)->firstOrFail();

        $this->assertDatabaseHas('delivery_record_lines', [
            'delivery_record_id' => $record->id,
            'description' => 'Tinta Brother BTD60BK',
            'quantity' => 1,
        ]);
    }

    public function test_supplies_delivery_record_can_be_corrected(): void
    {
        $this->authenticatedUser();

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina']
        );

        $item = Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        $replacementItem = Item::query()->create([
            'name' => 'Teclado',
            'brand' => 'Logitech',
            'model' => 'MK120',
            'unit' => 'unidad',
        ]);

        InventoryMovement::query()->create([
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $office->id,
            'quantity' => 5,
            'performed_at' => now(),
        ]);

        InventoryMovement::query()->create([
            'item_id' => $replacementItem->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $office->id,
            'quantity' => 5,
            'performed_at' => now(),
        ]);

        $area = MunicipalArea::query()->create([
            'name' => 'Informatica',
            'slug' => 'informatica-correccion',
            'responsible_name' => 'Jerson Ernesto Zelaya Villalta',
            'institutional_email' => 'innovacion@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $this->post('/actas-entrega/pdf', [
            'delivery_date' => now()->format('Y-m-d'),
            'district' => 'Zacatecoluca',
            'assigned_area_id' => $area->id,
            'receives' => 'Juan Perez',
            'from_location_id' => $office->id,
            'rows' => [
                ['quantity' => '1', 'item_id' => $item->id],
            ],
        ])->assertOk();

        $record = DeliveryRecord::query()->where('type', DeliveryRecord::TYPE_SUPPLIES)->firstOrFail();

        $response = $this->put("/actas-entrega/{$record->id}", [
            'delivery_date' => now()->format('Y-m-d'),
            'district' => 'Zacatecoluca',
            'assigned_area_id' => $area->id,
            'receives' => 'Maria Perez',
            'from_location_id' => $office->id,
            'rows' => [
                ['quantity' => '2', 'item_id' => $replacementItem->id],
            ],
        ]);

        $response->assertRedirect('/actas-entrega/historial');

        $this->assertDatabaseHas('delivery_records', [
            'id' => $record->id,
            'receives' => 'Maria Perez',
            'from_location_id' => $office->id,
            'assigned_area_id' => $area->id,
        ]);

        $this->assertDatabaseHas('delivery_record_lines', [
            'delivery_record_id' => $record->id,
            'item_id' => $replacementItem->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('inventory_movements', [
            'delivery_record_id' => $record->id,
            'item_id' => $replacementItem->id,
            'quantity' => 2,
            'from_location_id' => $office->id,
        ]);

        $this->assertDatabaseMissing('delivery_record_lines', [
            'delivery_record_id' => $record->id,
            'item_id' => $item->id,
            'quantity' => 1,
        ]);
    }

    public function test_supplies_delivery_record_can_be_annulled_and_returns_stock(): void
    {
        $user = $this->authenticatedUser();

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina']
        );

        $item = Item::query()->create([
            'name' => 'Tinta',
            'brand' => 'Brother',
            'model' => 'BTD60BK',
            'unit' => 'unidad',
        ]);

        InventoryMovement::query()->create([
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $office->id,
            'quantity' => 5,
            'performed_at' => now(),
        ]);

        $area = MunicipalArea::query()->create([
            'name' => 'Informatica',
            'slug' => 'informatica-anulacion',
            'responsible_name' => 'Jerson Ernesto Zelaya Villalta',
            'institutional_email' => 'innovacion@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $this->post('/actas-entrega/pdf', [
            'delivery_date' => now()->format('Y-m-d'),
            'district' => 'Zacatecoluca',
            'assigned_area_id' => $area->id,
            'receives' => 'Juan Perez',
            'from_location_id' => $office->id,
            'rows' => [
                ['quantity' => '2', 'item_id' => $item->id],
            ],
        ])->assertOk();

        $record = DeliveryRecord::query()->where('type', DeliveryRecord::TYPE_SUPPLIES)->firstOrFail();

        $response = $this->post("/actas-entrega/{$record->id}/anular");

        $response->assertRedirect('/actas-entrega/historial');

        $this->assertDatabaseHas('delivery_records', [
            'id' => $record->id,
            'annulled_by' => $user->id,
        ]);

        $this->assertDatabaseHas('inventory_movements', [
            'delivery_record_id' => $record->id,
            'item_id' => $item->id,
            'type' => InventoryMovement::TYPE_ENTRY,
            'to_location_id' => $office->id,
            'quantity' => 2,
        ]);

        $stock = InventoryMovement::query()
            ->where('item_id', $item->id)
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN to_location_id = ? THEN quantity ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN from_location_id = ? THEN quantity ELSE 0 END), 0) AS stock',
                [$office->id, $office->id]
            )
            ->value('stock');

        $this->assertSame(5, (int) $stock);
    }

    public function test_equipment_delivery_act_pdf_downloads_and_assigns_equipment(): void
    {
        $this->authenticatedUser();

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina']
        );

        $area = MunicipalArea::query()->create([
            'name' => 'Informatica',
            'slug' => 'informatica',
            'responsible_name' => 'Jerson Ernesto Zelaya Villalta',
            'institutional_email' => 'innovacion@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $equipment = Equipment::query()->create([
            'category' => 'laptop',
            'name' => 'Laptop Dell Latitude 5420',
            'brand' => 'Dell',
            'model' => 'Latitude 5420',
            'serial_number' => 'EQ-001',
            'asset_code' => 'AF-900',
            'status' => 'available',
        ]);

        $response = $this->post('/actas-entrega/equipos/pdf', [
            'delivery_date' => now()->format('Y-m-d'),
            'district' => 'Zacatecoluca',
            'receives' => 'Juan Perez',
            'assigned_area_id' => $area->id,
            'location_id' => $office->id,
            'rows' => [
                ['equipment_id' => $equipment->id],
            ],
        ]);

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=acta_entrega_equipos.pdf');

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'status' => 'assigned',
            'assigned_area_id' => $area->id,
            'assigned_area' => 'Informatica',
            'location_id' => $office->id,
        ]);

        $record = DeliveryRecord::query()->where('type', DeliveryRecord::TYPE_EQUIPMENTS)->firstOrFail();

        $this->assertDatabaseHas('delivery_record_lines', [
            'delivery_record_id' => $record->id,
            'description' => 'Laptop Dell Latitude 5420',
            'quantity' => 1,
            'asset_code' => 'AF-900',
            'serial_number' => 'EQ-001',
        ]);
    }

    public function test_can_create_update_and_delete_users(): void
    {
        $admin = $this->authenticatedUser();

        $createResponse = $this->post('/usuarios', [
            'name' => 'Maria Lopez',
            'username' => 'maria.lopez',
            'email' => 'maria@example.com',
            'phone' => '7001-0001',
            'position' => 'Tecnica',
            'role' => User::ROLE_SUPPLIES_OPERATOR,
            'permissions' => [
                User::PERMISSION_ITEMS,
                User::PERMISSION_DELIVERY_ACTS,
                User::PERMISSION_EQUIPMENTS,
            ],
            'is_active' => '1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $createResponse->assertRedirect('/usuarios');
        $this->assertDatabaseHas('users', [
            'username' => 'maria.lopez',
            'email' => 'maria@example.com',
            'position' => 'Tecnica',
            'role' => User::ROLE_SUPPLIES_OPERATOR,
        ]);

        $this->assertSame(
            User::normalizePermissions([
                User::PERMISSION_ITEMS,
                User::PERMISSION_DELIVERY_ACTS,
                User::PERMISSION_EQUIPMENTS,
            ]),
            $userPermissions = User::query()->where('username', 'maria.lopez')->firstOrFail()->permissions
        );

        $user = User::query()->where('username', 'maria.lopez')->firstOrFail();

        $updateResponse = $this->put("/usuarios/{$user->id}", [
            'name' => 'Maria Lopez Actualizada',
            'username' => 'maria.lopez',
            'email' => 'maria@example.com',
            'phone' => '7001-0002',
            'position' => 'Coordinadora',
            'role' => User::ROLE_ADMIN,
            'permissions' => [
                User::PERMISSION_ITEMS,
            ],
            'is_active' => '0',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $updateResponse->assertRedirect('/usuarios');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Maria Lopez Actualizada',
            'position' => 'Coordinadora',
            'role' => User::ROLE_ADMIN,
            'is_active' => false,
        ]);

        $this->assertSame(User::defaultModulePermissions(), $user->fresh()->permissions);

        $deleteResponse = $this->delete("/usuarios/{$user->id}");

        $deleteResponse->assertRedirect('/usuarios');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $selfDeleteResponse = $this->delete("/usuarios/{$admin->id}");

        $selfDeleteResponse->assertRedirect('/usuarios');
        $selfDeleteResponse->assertSessionHasErrors('user');
    }

    public function test_can_create_update_and_delete_equipments(): void
    {
        $user = $this->authenticatedUser();
        $location = Location::query()->create([
            'name' => 'Bodega Equipos',
            'code' => 'BOD-EQ',
        ]);
        $area = MunicipalArea::query()->create([
            'name' => 'Informatica',
            'slug' => 'informatica',
            'responsible_name' => 'Jerson Ernesto Zelaya Villalta',
            'institutional_email' => 'innovacion@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $createResponse = $this->post('/equipos', [
            'category' => 'laptop',
            'brand' => 'Dell',
            'model' => 'Latitude 5420',
            'serial_number' => 'SER-001',
            'asset_code' => 'AF-001',
            'processor' => 'Intel Core i5',
            'ram' => '16 GB',
            'storage' => '512 GB SSD',
            'operating_system' => 'Windows 11',
            'status' => 'available',
            'notes' => 'Equipo en uso',
        ]);

        $createResponse->assertRedirect('/equipos');
        $this->assertDatabaseHas('equipments', [
            'serial_number' => 'SER-001',
            'asset_code' => 'AF-001',
        ]);

        $equipment = Equipment::query()->where('serial_number', 'SER-001')->firstOrFail();

        $assignmentResponse = $this->put("/equipos/asignaciones/{$equipment->id}", [
            'status' => 'assigned',
            'location_id' => $location->id,
            'assigned_user_id' => $user->id,
            'assigned_area_id' => $area->id,
            'assigned_at' => now()->format('Y-m-d'),
            'notes' => 'Equipo asignado',
        ]);

        $assignmentResponse->assertRedirect('/equipos/asignaciones');

        $updateResponse = $this->put("/equipos/{$equipment->id}", [
            'category' => 'laptop',
            'brand' => 'Dell',
            'model' => 'Latitude 5430',
            'serial_number' => 'SER-001',
            'asset_code' => 'AF-001',
            'processor' => 'Intel Core i7',
            'ram' => '16 GB',
            'storage' => '1 TB SSD',
            'operating_system' => 'Windows 11 Pro',
            'status' => 'maintenance',
            'notes' => 'Equipo en revision',
        ]);

        $updateResponse->assertRedirect('/equipos');
        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'name' => 'Laptop Dell Latitude 5430',
            'status' => 'maintenance',
            'assigned_area' => 'Informatica',
            'assigned_area_id' => $area->id,
        ]);

        $deleteResponse = $this->delete("/equipos/{$equipment->id}");

        $deleteResponse->assertRedirect('/equipos');
        $this->assertDatabaseMissing('equipments', [
            'id' => $equipment->id,
        ]);
    }

    public function test_can_register_equipment_movements_and_track_performer(): void
    {
        $user = $this->authenticatedUser();
        $technician = User::factory()->create([
            'name' => 'Tecnico Innovacion',
        ]);

        $originLocation = Location::query()->create([
            'name' => 'Oficina Central',
            'code' => 'OF-CEN',
        ]);

        $destinationLocation = Location::query()->create([
            'name' => 'Taller Innovacion',
            'code' => 'TALLER',
        ]);

        $originArea = MunicipalArea::query()->create([
            'name' => 'Tesoreria',
            'slug' => 'tesoreria-prueba',
            'responsible_name' => 'Ana Perez',
            'institutional_email' => 'tesoreria@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $destinationArea = MunicipalArea::query()->create([
            'name' => 'Informatica',
            'slug' => 'informatica-mov',
            'responsible_name' => 'Jerson Zelaya',
            'institutional_email' => 'innovacion@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $equipment = Equipment::query()->create([
            'category' => 'laptop',
            'name' => 'Laptop Dell Latitude 5420',
            'brand' => 'Dell',
            'model' => 'Latitude 5420',
            'serial_number' => 'EQ-MOV-001',
            'asset_code' => 'AF-MOV-001',
            'status' => 'assigned',
            'location_id' => $originLocation->id,
            'assigned_area_id' => $originArea->id,
            'assigned_area' => $originArea->name,
            'assigned_at' => now()->format('Y-m-d'),
        ]);

        $transferResponse = $this->post('/equipos/movimientos', [
            'equipment_id' => $equipment->id,
            'type' => EquipmentMovement::TYPE_TRANSFER,
            'to_location_id' => $destinationLocation->id,
            'to_area_id' => $destinationArea->id,
            'performed_at' => now()->format('Y-m-d H:i:s'),
            'notes' => 'Traslado por reorganizacion',
        ]);

        $transferResponse->assertRedirect('/equipos/movimientos');

        $this->assertDatabaseHas('equipment_movements', [
            'equipment_id' => $equipment->id,
            'type' => EquipmentMovement::TYPE_TRANSFER,
            'performed_by' => $user->id,
            'from_location_id' => $originLocation->id,
            'to_location_id' => $destinationLocation->id,
            'from_area_id' => $originArea->id,
            'to_area_id' => $destinationArea->id,
        ]);

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'location_id' => $destinationLocation->id,
            'assigned_area_id' => $destinationArea->id,
            'assigned_area' => 'Informatica',
            'status' => 'assigned',
        ]);

        $withdrawalResponse = $this->post('/equipos/movimientos', [
            'equipment_id' => $equipment->id,
            'type' => EquipmentMovement::TYPE_WITHDRAWAL,
            'performed_at' => now()->addMinute()->format('Y-m-d H:i:s'),
            'notes' => 'Retiro para revision tecnica',
        ]);

        $withdrawalResponse->assertRedirect('/equipos/movimientos');

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'status' => 'maintenance',
            'assigned_area_id' => null,
            'assigned_user_id' => null,
        ]);

        $retirementArea = MunicipalArea::query()->create([
            'name' => 'Activo Fijo',
            'slug' => 'activo-fijo-baja',
            'responsible_name' => 'Luis Ramirez',
            'institutional_email' => 'activofijo@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $equipment->update([
            'status' => 'maintenance',
            'assigned_area_id' => $retirementArea->id,
            'assigned_area' => $retirementArea->name,
        ]);

        $retirementResponse = $this->post('/equipos/movimientos', [
            'equipment_id' => $equipment->id,
            'type' => EquipmentMovement::TYPE_RETIREMENT,
            'performed_by' => $technician->id,
            'from_area_id' => $retirementArea->id,
            'performed_at' => now()->addMinutes(2)->format('Y-m-d H:i:s'),
            'notes' => 'Baja por obsolescencia',
        ]);

        $retirementResponse->assertRedirect('/equipos/movimientos');

        $this->assertDatabaseHas('equipment_movements', [
            'equipment_id' => $equipment->id,
            'type' => EquipmentMovement::TYPE_RETIREMENT,
            'performed_by' => $technician->id,
            'from_area_id' => $retirementArea->id,
        ]);

        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'status' => 'retired',
            'assigned_area_id' => null,
        ]);
    }

    public function test_assigned_equipment_can_be_linked_only_to_an_area_and_available_clears_assignment(): void
    {
        $this->authenticatedUser();
        $location = Location::query()->create([
            'name' => 'Centro de Gobierno',
            'code' => 'CG-01',
        ]);
        $area = MunicipalArea::query()->create([
            'name' => 'Contabilidad',
            'slug' => 'contabilidad',
            'responsible_name' => 'Xiomara Estela Suarez Quinteros',
            'institutional_email' => 'contabilidad@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $createResponse = $this->post('/equipos', [
            'category' => 'impresora',
            'brand' => 'HP',
            'model' => 'LaserJet Pro',
            'serial_number' => 'IMP-001',
            'asset_code' => 'ACT-IMP-001',
            'status' => 'available',
            'notes' => 'Asignada al area',
        ]);

        $createResponse->assertRedirect('/equipos');

        $equipment = Equipment::query()->where('serial_number', 'IMP-001')->firstOrFail();

        $assignResponse = $this->put("/equipos/asignaciones/{$equipment->id}", [
            'status' => 'assigned',
            'location_id' => $location->id,
            'assigned_user_id' => '',
            'assigned_area_id' => $area->id,
            'assigned_at' => now()->format('Y-m-d'),
            'notes' => 'Asignada al area',
        ]);

        $assignResponse->assertRedirect('/equipos/asignaciones');
        $this->assertDatabaseHas('equipments', [
            'serial_number' => 'IMP-001',
            'status' => 'assigned',
            'assigned_area' => 'Contabilidad',
            'assigned_area_id' => $area->id,
            'assigned_user_id' => null,
        ]);

        $updateResponse = $this->put("/equipos/asignaciones/{$equipment->id}", [
            'status' => 'available',
            'location_id' => $location->id,
            'assigned_user_id' => '',
            'assigned_area_id' => '',
            'assigned_at' => now()->format('Y-m-d'),
            'notes' => 'Disponible nuevamente',
        ]);

        $updateResponse->assertRedirect('/equipos/asignaciones');
        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'status' => 'available',
            'assigned_area' => null,
            'assigned_area_id' => null,
            'assigned_user_id' => null,
            'assigned_at' => null,
        ]);
    }

    public function test_can_create_update_and_delete_equipment_categories(): void
    {
        $this->authenticatedUser();

        $createResponse = $this->post('/catalogos/categorias-equipos', [
            'name' => 'Tablet',
            'slug' => 'tablet',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect('/catalogos/categorias-equipos');
        $this->assertDatabaseHas('equipment_categories', [
            'name' => 'Tablet',
            'slug' => 'tablet',
            'is_active' => true,
        ]);

        $category = EquipmentCategory::query()->where('slug', 'tablet')->firstOrFail();

        $updateResponse = $this->put("/catalogos/categorias-equipos/{$category->id}", [
            'name' => 'Tableta',
            'slug' => 'tableta',
            'is_active' => '0',
        ]);

        $updateResponse->assertRedirect('/catalogos/categorias-equipos');
        $this->assertDatabaseHas('equipment_categories', [
            'id' => $category->id,
            'name' => 'Tableta',
            'slug' => 'tableta',
            'is_active' => false,
        ]);

        $deleteResponse = $this->delete("/catalogos/categorias-equipos/{$category->id}");

        $deleteResponse->assertRedirect('/catalogos/categorias-equipos');
        $this->assertDatabaseMissing('equipment_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_equipment_categories_can_be_filtered_by_search(): void
    {
        $this->authenticatedUser();

        EquipmentCategory::query()->create([
            'name' => 'Tablet',
            'slug' => 'tablet',
            'is_active' => true,
        ]);

        EquipmentCategory::query()->create([
            'name' => 'Proyector',
            'slug' => 'proyector-prueba',
            'is_active' => true,
        ]);

        $response = $this->get('/catalogos/categorias-equipos?search=tabl');

        $response->assertOk();
        $response->assertSee('Tablet');
        $response->assertDontSee('Proyector');
    }

    public function test_can_create_update_and_delete_municipal_areas(): void
    {
        $this->authenticatedUser();

        $createResponse = $this->post('/catalogos/areas', [
            'name' => 'Tecnologias',
            'slug' => 'tecnologias',
            'responsible_name' => 'Ana Lopez',
            'institutional_email' => 'tecnologias@lapazeste.gob.sv',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect('/catalogos/areas');
        $this->assertDatabaseHas('municipal_areas', [
            'name' => 'Tecnologias',
            'slug' => 'tecnologias',
            'responsible_name' => 'Ana Lopez',
        ]);

        $area = MunicipalArea::query()->where('slug', 'tecnologias')->firstOrFail();

        $updateResponse = $this->put("/catalogos/areas/{$area->id}", [
            'name' => 'Tecnologia Institucional',
            'slug' => 'tecnologia-institucional',
            'responsible_name' => 'Ana Maria Lopez',
            'institutional_email' => 'tecnologia@lapazeste.gob.sv',
            'is_active' => '0',
        ]);

        $updateResponse->assertRedirect('/catalogos/areas');
        $this->assertDatabaseHas('municipal_areas', [
            'id' => $area->id,
            'name' => 'Tecnologia Institucional',
            'slug' => 'tecnologia-institucional',
            'is_active' => false,
        ]);

        $deleteResponse = $this->delete("/catalogos/areas/{$area->id}");

        $deleteResponse->assertRedirect('/catalogos/areas');
        $this->assertDatabaseMissing('municipal_areas', [
            'id' => $area->id,
        ]);
    }

    public function test_municipal_areas_can_be_filtered_by_search(): void
    {
        $this->authenticatedUser();

        MunicipalArea::query()->create([
            'name' => 'Informatica',
            'slug' => 'informatica',
            'responsible_name' => 'Jerson Zelaya',
            'institutional_email' => 'innovacion@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        MunicipalArea::query()->create([
            'name' => 'Tesoreria',
            'slug' => 'tesoreria',
            'responsible_name' => 'Ana Perez',
            'institutional_email' => 'tesoreria@lapazeste.gob.sv',
            'is_active' => true,
        ]);

        $response = $this->get('/catalogos/areas?search=innovacion');

        $response->assertOk();
        $response->assertSee('Informatica');
        $response->assertDontSee('Tesoreria');
    }

    public function test_supplies_operator_is_redirected_to_items_after_login(): void
    {
        $user = User::factory()->create([
            'username' => 'operador1',
            'email' => 'operador@example.com',
            'password' => 'secret123',
            'role' => User::ROLE_SUPPLIES_OPERATOR,
            'permissions' => [
                User::PERMISSION_ITEMS,
                User::PERMISSION_DELIVERY_ACTS,
            ],
        ]);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/insumos');
        $this->assertAuthenticatedAs($user);
    }

    public function test_custom_access_user_can_access_items_and_delivery_acts(): void
    {
        $this->authenticatedSuppliesOperator();

        $itemsResponse = $this->get('/insumos');
        $itemsResponse->assertOk();

        $actsResponse = $this->get('/actas-entrega');
        $actsResponse->assertOk();
    }

    public function test_custom_access_user_cannot_access_unassigned_modules(): void
    {
        $this->authenticatedSuppliesOperator();

        $dashboardResponse = $this->get('/');
        $dashboardResponse->assertRedirect('/insumos');

        $movementsResponse = $this->get('/movimientos');
        $movementsResponse->assertRedirect('/insumos');
        $movementsResponse->assertSessionHasErrors('access');

        $usersResponse = $this->get('/usuarios');
        $usersResponse->assertRedirect('/insumos');
        $usersResponse->assertSessionHasErrors('access');

        $equipmentsResponse = $this->get('/equipos');
        $equipmentsResponse->assertRedirect('/insumos');
        $equipmentsResponse->assertSessionHasErrors('access');
    }

    public function test_user_with_view_only_permission_cannot_create_or_delete_items(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_SUPPLIES_OPERATOR,
            'permissions' => [
                User::composePermission(User::PERMISSION_ITEMS, User::ACTION_VIEW),
            ],
        ]);

        $this->actingAs($user);

        $createResponse = $this->post('/insumos', [
            'name' => 'Adaptador',
            'brand' => 'UGREEN',
            'model' => 'USB-C',
            'unit' => 'unidad',
        ]);

        $createResponse->assertRedirect('/insumos');
        $createResponse->assertSessionHasErrors('access');

        $item = Item::query()->create([
            'name' => 'Cable VGA',
            'brand' => 'Generico',
            'model' => '1.5M',
            'unit' => 'unidad',
        ]);

        $deleteResponse = $this->delete("/insumos/{$item->id}");

        $deleteResponse->assertRedirect('/insumos');
        $deleteResponse->assertSessionHasErrors('access');
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
        ]);
    }
}
