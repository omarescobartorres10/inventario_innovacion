<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipal_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180);
            $table->string('slug', 180)->unique();
            $table->string('responsible_name')->nullable();
            $table->string('institutional_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('equipments', function (Blueprint $table) {
            $table->foreignId('assigned_area_id')->nullable()->after('assigned_user_id')->constrained('municipal_areas')->nullOnDelete();
        });

        $now = now();
        $areas = [
            ['name' => 'Alcaldesa', 'responsible_name' => 'Marcela Balbina Pineda Erazo', 'institutional_email' => 'mp.alcaldesa@lapazeste.gob.sv'],
            ['name' => 'Sindica Municipal', 'responsible_name' => 'Marcela del Carmen Valencia Lemus', 'institutional_email' => 'sindicatura@lapazeste.gob.sv'],
            ['name' => 'Secretario Municipal', 'responsible_name' => 'Guillermo Antonio Villacorta Ramos', 'institutional_email' => 'secretaria@lapazeste.gob.sv'],
            ['name' => 'Gestion de Archivo', 'responsible_name' => 'Digna Isabel Hernandez Vda. de Huezo', 'institutional_email' => 'ugda@lapazeste.gob.sv'],
            ['name' => 'Auditor Interno', 'responsible_name' => 'Hugo Alfredo Santillana', 'institutional_email' => 'auditoriainterna@lapazeste.gob.sv'],
            ['name' => 'Despacho Municipal', 'responsible_name' => 'Kenia Sarai Miranda', 'institutional_email' => 'despacho@lapazeste.gob.sv'],
            ['name' => 'Oficial de Cumplimiento', 'responsible_name' => 'Hemerson Stanley Serrano Lemus', 'institutional_email' => 'oficial.cumplimiento@lapazeste.gob.sv'],
            ['name' => 'Direccion Distrital Zacatecoluca', 'responsible_name' => null, 'institutional_email' => null],
            ['name' => 'Sub-Direccion Distrital Zacatecoluca', 'responsible_name' => null, 'institutional_email' => null],
            ['name' => 'Direccion Distrital SJN', 'responsible_name' => 'Carlos Humberto Martinez Barahona', 'institutional_email' => 'direcciondistrital.sjn@lapazeste.gob.sv'],
            ['name' => 'Sub-Direccion Distrital SJN', 'responsible_name' => 'Carmen Beatriz Lopez Martinez', 'institutional_email' => 'subdireccion.sjn@lapazeste.gob.sv'],
            ['name' => 'Proyectos de Agua', 'responsible_name' => 'David Antonio Iraheta Martinez', 'institutional_email' => null],
            ['name' => 'Direccion Distrital San Rafael Obrajuelo', 'responsible_name' => 'Juan Ramon Mejia Mejia', 'institutional_email' => 'direcciondistrital.sro@lapazeste.gob.sv'],
            ['name' => 'Sub-Direccion Distrital San Rafael Obrajuelo', 'responsible_name' => 'Fernando de Jesus de la Paz Rodriguez', 'institutional_email' => 'subdireccion.sro@lapazeste.gob.sv'],
            ['name' => 'Directora Talento Humano', 'responsible_name' => 'Nerys Azucena Beltran', 'institutional_email' => 'talentohumano@lapazeste.gob.sv'],
            ['name' => 'Secretaria de Innovacion', 'responsible_name' => 'Jerson Ernesto Zelaya Villalta', 'institutional_email' => 'innovacion@lapazeste.gob.sv'],
            ['name' => 'Secretaria de Comunicaciones Institucional', 'responsible_name' => 'Adela Fernanda Sibrian Bermudez', 'institutional_email' => 'comunicaciones@lapazeste.gob.sv'],
            ['name' => 'Instituto de la Juventud', 'responsible_name' => 'Luis Eliseo Rodriguez Palacios', 'institutional_email' => 'imj@lapazeste.gob.sv'],
            ['name' => 'Comision de Proteccion Civil', 'responsible_name' => null, 'institutional_email' => 'comisioncivil@lapazeste.gob.sv'],
            ['name' => 'Integral de Riesgos', 'responsible_name' => 'Jose Israel Hernandez Ayala', 'institutional_email' => 'depto.iiriesgos@lapazeste.gob.sv'],
            ['name' => 'Registro de la Carrera Administrativa', 'responsible_name' => 'Elmer Alexander Alfaro Alfaro', 'institutional_email' => 'lacam@lapazeste.gob.sv'],
            ['name' => 'CAM', 'responsible_name' => 'Ronan Baltazar Trinidad Melgar', 'institutional_email' => 'cam@lapazeste.gob.sv'],
            ['name' => 'Acceso a la Informacion Publica', 'responsible_name' => null, 'institutional_email' => 'aip@lapazeste.gob.sv'],
            ['name' => 'Unidad de Compras Publicas', 'responsible_name' => 'Fatima Dolores Rivera de Torres', 'institutional_email' => 'ucp@lapazeste.gob.sv'],
            ['name' => 'Gerente Legal', 'responsible_name' => 'Jorge Enrique Rodriguez', 'institutional_email' => 'gerencia.legal@lapazeste.gob.sv'],
            ['name' => 'Sub-Gerente Legal', 'responsible_name' => 'Ana Guadalupe Menendez de Muñoz', 'institutional_email' => 'subgerencialegal@lapazeste.gob.sv'],
            ['name' => 'Registro Familiar', 'responsible_name' => 'Luisa Jerardina Minero Moscote', 'institutional_email' => 'ref@lapazeste.gob.sv'],
            ['name' => 'Contravencional', 'responsible_name' => 'Pamela Cecilia Diaz', 'institutional_email' => 'contravencional@lapazeste.gob.sv'],
            ['name' => 'Gerencia General', 'responsible_name' => 'Sarahi Alejandra Cortez Alfaro', 'institutional_email' => 'gerenciageneral.admin@lapazeste.gob.sv'],
            ['name' => 'Sub-Gerencia General', 'responsible_name' => null, 'institutional_email' => 'subgerenciageneral@lapazeste.gob.sv'],
            ['name' => 'Sub-Gerencia Administrativa', 'responsible_name' => 'Sonia Guadalupe Pineda', 'institutional_email' => 'subgerenciaadm@lapazeste.gob.sv'],
            ['name' => 'Almacen', 'responsible_name' => 'Luis Vladimir Nochez Moreno', 'institutional_email' => 'depto.almacen@lapazeste.gob.sv'],
            ['name' => 'Gerente Financiero', 'responsible_name' => 'Juan Jose Calero Cruz', 'institutional_email' => 'gerencia.financiera@lapazeste.gob.sv'],
            ['name' => 'Depto Financiero', 'responsible_name' => 'Israel Antonio Salgado Ramos', 'institutional_email' => 'coordinadorfinanciero@lapazeste.gob.sv'],
            ['name' => 'Depto Tesoreria', 'responsible_name' => 'Fatima Brendaly Tamayo', 'institutional_email' => 'tesoreria@lapazeste.gob.sv'],
            ['name' => 'Depto Contabilidad', 'responsible_name' => 'Xiomara Estela Suarez Quinteros', 'institutional_email' => 'contabilidad@lapazeste.gob.sv'],
            ['name' => 'Depto Presupuesto', 'responsible_name' => 'David Salvador Mancia', 'institutional_email' => 'presupuesto@lapazeste.gob.sv'],
            ['name' => 'Depto Activo Fijo', 'responsible_name' => 'Carlos Edilberto Cañas Aguirre', 'institutional_email' => 'activofijo@lapazeste.gob.sv'],
            ['name' => 'Gerente Tributario', 'responsible_name' => 'Wilver Gerardo Parada Garcia', 'institutional_email' => 'tributaria@lapazeste.gob.sv'],
            ['name' => 'Cuentas Corrientes', 'responsible_name' => 'Morena Guadalupe Mejia Mejia', 'institutional_email' => 'delegado.cuentascorriente@lapazeste.gob.sv'],
            ['name' => 'Catastro', 'responsible_name' => 'Ronal Alexander Lopez Preza', 'institutional_email' => 'delegadosubgerenciatributaria@lapazeste.gob.sv'],
            ['name' => 'Recuperacion de Mora', 'responsible_name' => 'Yessica Alicia Diaz Rodriguez', 'institutional_email' => 'atencioncontribuyente@lapazeste.gob.sv'],
            ['name' => 'Planificacion, Ordenamiento y Desarrollo Territorial', 'responsible_name' => 'Alexis Ernesto Paniagua Vasquez', 'institutional_email' => 'secretaria.spodt@lapazeste.gob.sv'],
            ['name' => 'Gerencia de Obras Municipales y Proyectos', 'responsible_name' => 'Walter Alexander Torres Tobar', 'institutional_email' => 'domp@lapazeste.gob.sv'],
            ['name' => 'Depto Conectividad de Vias Rurales', 'responsible_name' => 'Andres Alfonso Campos Garcia', 'institutional_email' => 'conectividadviasrurales@lapazeste.gob.sv'],
            ['name' => 'Renovadores', 'responsible_name' => 'Oscar Enrique Guzman', 'institutional_email' => 'renovadores@lapazeste.gob.sv'],
            ['name' => 'Gerencia de Proyectos Institucionales y de Cooperacion Internacional', 'responsible_name' => 'Henry Josue Servellon Santos', 'institutional_email' => 'cooperacion@lapazeste.gob.sv'],
            ['name' => 'Escuela de Formacion y Capacitacion', 'responsible_name' => null, 'institutional_email' => null],
            ['name' => 'Gerencia de Servicios Generales y Publicos', 'responsible_name' => 'Francisco Israel Acevedo Martinez', 'institutional_email' => 'serviciosgenerales@lapazeste.gob.sv'],
            ['name' => 'Mercados', 'responsible_name' => 'Carla Nere Ortiz Hernandez', 'institutional_email' => 'mercados@lapazeste.gob.sv'],
            ['name' => 'Cementerios', 'responsible_name' => 'Porfirio Armando Rodriguez Hernandez', 'institutional_email' => 'cementerios@lapazeste.gob.sv'],
            ['name' => 'Alumbrado Publico', 'responsible_name' => 'Jose Ricardo Arias Marciaño', 'institutional_email' => 'alumbrado.publico@lapazeste.gob.sv'],
            ['name' => 'Limpieza Publica e Institucional', 'responsible_name' => 'Marco Antonio Somoza Salomon', 'institutional_email' => 'deptolimpieza@lapazeste.gob.sv'],
            ['name' => 'Mantenimiento General y Operativo', 'responsible_name' => null, 'institutional_email' => 'operativosgenerales@lapazeste.gob.sv'],
            ['name' => 'Estructuras Metalicas', 'responsible_name' => 'Mariano Salvador Rodriguez', 'institutional_email' => 'dem@lapazeste.gob.sv'],
            ['name' => 'Gerencia Logistica y Transporte', 'responsible_name' => 'Gerson Eduardo Henriquez Rivas', 'institutional_email' => 'logisticaytransporte@lapazeste.gob.sv'],
            ['name' => 'Depto Livianos, Pesados y Maquinaria', 'responsible_name' => 'Wilfredo Balmore Acevedo Rivas', 'institutional_email' => null],
            ['name' => 'Gerencia Desarrollo Social', 'responsible_name' => 'Blanca Marisol Villacorta Vides', 'institutional_email' => 'desarrollo.social@lapazeste.gob.sv'],
            ['name' => 'Primera Infancia', 'responsible_name' => 'Mallumy Faridy Guillen Sanchez', 'institutional_email' => 'depina@lapazeste.gob.sv'],
            ['name' => 'Depto Mujer', 'responsible_name' => 'Mallumy Faridy Guillen Sanchez', 'institutional_email' => 'mujer@lapazeste.gob.sv'],
            ['name' => 'Depto Organizacion Social', 'responsible_name' => 'Blanca Marisol Villacorta Vides', 'institutional_email' => 'organizacion.social@lapazeste.gob.sv'],
            ['name' => 'Deportes', 'responsible_name' => 'Raul Alberto Lovato Osorio', 'institutional_email' => 'deportes@lapazeste.gob.sv'],
            ['name' => 'Agricultura y Ganaderia', 'responsible_name' => 'Roberto Antonio Muñoz Melendez', 'institutional_email' => 'agriculturayganaderia@lapazeste.gob.sv'],
            ['name' => 'Tiangue San Rafael Obrajuelo', 'responsible_name' => 'Jose Israel Hernandez Ayala', 'institutional_email' => 'tiangue.sro@lapazeste.gob.sv'],
            ['name' => 'Planta de Compostaje San Rafael Obrajuelo', 'responsible_name' => 'Jose Daniel Solorzano de Leon', 'institutional_email' => null],
            ['name' => 'Gerencia de Inversion', 'responsible_name' => 'Otsmaro Ladislao Aguilar Jovel', 'institutional_email' => 'inversion@lapazeste.gob.sv'],
            ['name' => 'Depto Empleabilidad', 'responsible_name' => 'Claudia Mirta Rivera Abarca', 'institutional_email' => 'emprende@lapazeste.gob.sv'],
            ['name' => 'Medio Ambiente', 'responsible_name' => 'Fatima del Rosario Rodriguez Bachez', 'institutional_email' => 'departamentoambiental@lapazeste.gob.sv'],
            ['name' => 'Turismo y Cultura', 'responsible_name' => 'Cristian Mauricio Santos Molina', 'institutional_email' => 'turismo@lapazeste.gob.sv'],
            ['name' => 'Polideportivo SRO', 'responsible_name' => 'Levi Sail Melendes Santos', 'institutional_email' => null],
        ];

        foreach ($areas as $area) {
            DB::table('municipal_areas')->insert([
                'name' => $area['name'],
                'slug' => Str::slug($area['name']),
                'responsible_name' => $area['responsible_name'],
                'institutional_email' => $area['institutional_email'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_area_id');
        });

        Schema::dropIfExists('municipal_areas');
    }
};
