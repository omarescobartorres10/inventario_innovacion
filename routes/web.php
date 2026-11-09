<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'no.cache'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware(['auth', 'active', 'no.cache'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        $user = request()->user();

        if ($user->hasPermission(\App\Models\User::PERMISSION_DASHBOARD)) {
            return app(InventoryController::class)->dashboard();
        }

        return redirect()->route($user->defaultLandingRoute());
    })->name('inventory.dashboard');

    Route::middleware('permission:items.view')->group(function () {
        Route::get('/insumos', [InventoryController::class, 'items'])->name('inventory.items.index');
        Route::get('/consulta-stock', [InventoryController::class, 'stockLookup'])->name('inventory.stock-lookup.index');
    });
    Route::post('/insumos', [InventoryController::class, 'storeItem'])->middleware('permission:items.create')->name('inventory.items.store');
    Route::put('/insumos/{item}', [InventoryController::class, 'updateItem'])->middleware('permission:items.edit')->name('inventory.items.update');
    Route::delete('/insumos/{item}', [InventoryController::class, 'destroyItem'])->middleware('permission:items.delete')->name('inventory.items.destroy');

    Route::middleware('permission:delivery_acts.view')->group(function () {
        Route::get('/actas-entrega', [InventoryController::class, 'deliveryActForm'])->name('inventory.delivery-act.form');
        Route::get('/actas-entrega/historial', [InventoryController::class, 'deliveryActHistory'])->name('inventory.delivery-act.history');
        Route::get('/actas-entrega/{record}/editar', [InventoryController::class, 'editDeliveryRecord'])->middleware('permission:delivery_acts.edit')->name('inventory.delivery-act.edit');
        Route::get('/actas-entrega/equipos', [InventoryController::class, 'equipmentDeliveryActForm'])->name('inventory.delivery-act.equipments.form');
    });
    Route::post('/actas-entrega/pdf', [InventoryController::class, 'generateDeliveryActPdf'])->middleware('permission:delivery_acts.create')->name('inventory.delivery-act.pdf');
    Route::post('/actas-entrega/equipos/pdf', [InventoryController::class, 'generateEquipmentDeliveryActPdf'])->middleware('permission:delivery_acts.create')->name('inventory.delivery-act.equipments.pdf');
    Route::put('/actas-entrega/{record}', [InventoryController::class, 'updateDeliveryRecord'])->middleware('permission:delivery_acts.edit')->name('inventory.delivery-act.update');
    Route::post('/actas-entrega/{record}/anular', [InventoryController::class, 'annulDeliveryRecord'])->middleware('permission:delivery_acts.edit')->name('inventory.delivery-act.annul');

    Route::middleware('permission:dashboard.view')->group(function () {
        Route::get('/exportar/resumen/pdf', [InventoryController::class, 'exportSummaryPdf'])->name('inventory.exports.summary.pdf');
        Route::get('/exportar/resumen/excel', [InventoryController::class, 'exportSummaryExcel'])->name('inventory.exports.summary.excel');
    });

    Route::middleware('permission:items.view,exports.view')->group(function () {
        Route::get('/exportar/insumos/resumen/pdf', [InventoryController::class, 'exportItemsSummaryPdf'])->name('inventory.exports.items-summary.pdf');
        Route::get('/exportar/insumos/resumen/excel', [InventoryController::class, 'exportItemsSummaryExcel'])->name('inventory.exports.items-summary.excel');
    });

    Route::middleware('permission:movements.view')->group(function () {
        Route::get('/movimientos', [InventoryController::class, 'movements'])->name('inventory.movements.index');
    });
    Route::post('/movimientos', [InventoryController::class, 'storeMovement'])->middleware('permission:movements.create')->name('inventory.movements.store');

    Route::middleware('permission:equipments.view')->group(function () {
        Route::get('/equipos', [EquipmentController::class, 'index'])->name('equipments.index');
        Route::get('/equipos/asignaciones', [EquipmentController::class, 'assignments'])->name('equipments.assignments');
        Route::get('/equipos/movimientos', [EquipmentController::class, 'movements'])->name('equipments.movements');
        Route::get('/equipos/movimientos/pdf', [EquipmentController::class, 'exportMovementsPdf'])->name('equipments.movements.pdf');
    });
    Route::post('/equipos', [EquipmentController::class, 'store'])->middleware('permission:equipments.create')->name('equipments.store');
    Route::put('/equipos/{equipment}', [EquipmentController::class, 'update'])->middleware('permission:equipments.edit')->name('equipments.update');
    Route::delete('/equipos/{equipment}', [EquipmentController::class, 'destroy'])->middleware('permission:equipments.delete')->name('equipments.destroy');
    Route::put('/equipos/asignaciones/{equipment}', [EquipmentController::class, 'updateAssignment'])->middleware('permission:equipments.edit')->name('equipments.assignments.update');
    Route::post('/equipos/movimientos', [EquipmentController::class, 'storeMovement'])->middleware('permission:equipments.create')->name('equipments.movements.store');

    Route::middleware('permission:catalogs.view')->group(function () {
        Route::get('/catalogos/categorias-equipos', [CatalogController::class, 'equipmentCategories'])->name('catalogs.equipment-categories.index');
        Route::get('/catalogos/areas', [CatalogController::class, 'municipalAreas'])->name('catalogs.municipal-areas.index');
    });
    Route::post('/catalogos/categorias-equipos', [CatalogController::class, 'storeEquipmentCategory'])->middleware('permission:catalogs.create')->name('catalogs.equipment-categories.store');
    Route::put('/catalogos/categorias-equipos/{equipmentCategory}', [CatalogController::class, 'updateEquipmentCategory'])->middleware('permission:catalogs.edit')->name('catalogs.equipment-categories.update');
    Route::delete('/catalogos/categorias-equipos/{equipmentCategory}', [CatalogController::class, 'destroyEquipmentCategory'])->middleware('permission:catalogs.delete')->name('catalogs.equipment-categories.destroy');
    Route::post('/catalogos/areas', [CatalogController::class, 'storeMunicipalArea'])->middleware('permission:catalogs.create')->name('catalogs.municipal-areas.store');
    Route::put('/catalogos/areas/{municipalArea}', [CatalogController::class, 'updateMunicipalArea'])->middleware('permission:catalogs.edit')->name('catalogs.municipal-areas.update');
    Route::delete('/catalogos/areas/{municipalArea}', [CatalogController::class, 'destroyMunicipalArea'])->middleware('permission:catalogs.delete')->name('catalogs.municipal-areas.destroy');

    Route::middleware('permission:users.view')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    });
    Route::post('/usuarios', [UserController::class, 'store'])->middleware('permission:users.create')->name('users.store');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->middleware('permission:users.edit')->name('users.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('users.destroy');
});
