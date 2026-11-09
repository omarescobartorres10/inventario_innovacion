<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Inventario Innovacion') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logoA.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logoA.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoA.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

      <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --bg: #eef3f1;
            --panel: rgba(255, 255, 255, 0.92);
            --ink: #14212b;
            --muted: #5b6770;
            --line: rgba(20, 33, 43, 0.12);
            --dark: #0f172a;
            --accent: #0b5d56;
            --green: #0f766e;
            --green-soft: #ccfbf1;
            --gold: #b8860b;
            --cream: #f8f3e7;
            --amber: #d97706;
            --amber-soft: #fef3c7;
            --blue: #1d4ed8;
            --blue-soft: #dbeafe;
            --rose-soft: #ffe4e6;
            --rose-text: #9f1239;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.08), transparent 30%),
                radial-gradient(circle at right center, rgba(29, 78, 216, 0.05), transparent 25%),
                linear-gradient(180deg, #f8fbfa 0%, var(--bg) 100%);
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        .app-shell {
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            min-height: 100vh;
        }

        .sidebar {
            padding: 28px 22px;
            border-right: 1px solid var(--line);
            background:
                radial-gradient(circle at top, rgba(125, 211, 252, 0.12), transparent 28%),
                linear-gradient(180deg, rgba(15, 23, 42, 0.98) 0%, rgba(8, 15, 28, 0.98) 100%);
            color: #fff;
        }

        .brand-block { display: grid; justify-items: start; gap: 12px; }
        .brand-logo { display: block; width: 134px; max-width: 100%; height: auto; object-fit: contain; }
        .brand-title { font-size: 24px; font-weight: 700; line-height: 1.1; }
        .brand-subtitle { font-size: 12px; text-transform: uppercase; letter-spacing: 0.22em; color: #7dd3fc; }

        /* --- Sidebar Navigation --- */
        .nav { margin-top: 28px; display: grid; gap: 8px; }
        .nav a { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 14px 18px; 
            border-radius: 12px; 
            background: transparent; 
            border: 1px solid transparent; 
            transition: all 0.2s ease; 
        }
        .nav a:hover { 
            background: rgba(255, 255, 255, 0.05); 
            transform: translateX(4px);
        }
        .nav a.active { 
            background: linear-gradient(90deg, rgba(125, 211, 252, 0.15) 0%, rgba(125, 211, 252, 0) 100%);
            border-left: 4px solid #7dd3fc;
            border-radius: 0 12px 12px 0;
            padding-left: 14px;
        }
        .nav strong { display: block; font-size: 15px; font-weight: 500; }
        .nav i { width: 20px; text-align: center; color: #7dd3fc; font-size: 16px; }

        .sidebar-stats, .sidebar-user { margin-top: 28px; padding: 18px; border-radius: 16px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255,255,255,0.05); }
        .sidebar-user strong { display: block; font-size: 15px; font-weight: 600; }
        .sidebar-user span { display: block; margin-top: 4px; color: rgba(255, 255, 255, 0.6); font-size: 13px; }
        .sidebar-stats h3 { margin: 0 0 14px; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(255, 255, 255, 0.6); }

        .mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .mini-card { padding: 14px; border-radius: 12px; background: rgba(255, 255, 255, 0.05); }
        .mini-card small { display: block; font-size: 12px; color: rgba(255, 255, 255, 0.6); }
        .mini-card strong { display: block; margin-top: 6px; font-size: 22px; font-weight: 600; }
        .stat-head { display: flex; align-items: center; gap: 8px; }
        .stat-head i { color: rgba(255, 255, 255, 0.6); font-size: 13px; }

        /* --- Content & Topbar --- */
        .content { padding: 30px; }
        .topbar { display: flex; justify-content: flex-end; align-items: center; gap: 14px; margin-bottom: 20px; }
        .topbar-user { text-align: right; }
        .topbar-user strong { display: block; font-size: 14px; font-weight: 600; }
        .topbar-user span { display: block; margin-top: 2px; font-size: 12px; color: var(--muted); }
        .hero-note { display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 999px; background: rgba(11, 93, 86, 0.10); color: var(--accent); font-size: 12px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; }

        .page-header { display: flex; justify-content: space-between; gap: 18px; align-items: end; margin-bottom: 24px; }
        .page-header h1 { margin: 0; font-size: 36px; font-weight: 700; line-height: 1.1; letter-spacing: -0.5px; }
        .page-header p { margin: 8px 0 0; color: var(--muted); line-height: 1.6; max-width: 760px; font-size: 15px; }

        /* --- Cards & Panels --- */
        .summary-row { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }
        
        .summary-card, .panel { 
            background: var(--panel); 
            border: 1px solid rgba(20, 33, 43, 0.06); 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(20, 33, 43, 0.03), 0 1px 3px rgba(20, 33, 43, 0.02); 
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .summary-card:hover, .panel:hover {
            box-shadow: 0 10px 25px rgba(20, 33, 43, 0.06);
        }

        .summary-card { padding: 24px; }
        .summary-card small { display: block; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; font-size: 12px; color: var(--muted); }
        .summary-card strong { display: block; margin-top: 8px; font-size: 32px; font-weight: 700; color: var(--dark); }
        
        .panel { padding: 24px; }
        .panel.soft { background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 243, 231, 0.88)); }

        /* --- Buttons --- */
        .toolbar { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        .toolbar form { display: block; gap: 0; }
        
        .button-link, button { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
            width: auto; 
            padding: 10px 20px; 
            border-radius: 10px; 
            border: 0; 
            cursor: pointer; 
            font: inherit; 
            font-weight: 600; 
            font-size: 14px;
            text-decoration: none; 
            transition: all 0.2s ease; 
        }
        
        .button-link:active, button:active { transform: scale(0.96); }
        .button-link.dark, button { color: #fff; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15); }
        .button-link.dark:hover, button:hover { background: linear-gradient(180deg, #334155 0%, #1e293b 100%); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2); }
        .button-link.green, button.green { background: linear-gradient(180deg, #10b981 0%, #0f766e 100%); box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2); }

        /* --- Grids & Layout Helpers --- */
        .grid-2 { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 24px; }
        .stack { display: grid; gap: 24px; }
        .section-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; }
        .section-head h2 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.3px; }
        .section-head p { margin: 4px 0 0; color: var(--muted); font-size: 14px; }

        /* --- Pills (Badges) --- */
        .pill { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 13px; font-weight: 600; }
        .pill.dark { background: var(--dark); color: #fff; }
        .pill.green { background: var(--green-soft); color: #115e59; }
        .pill.amber { background: var(--amber-soft); color: #92400e; }
        .pill.blue { background: var(--blue-soft); color: #1d4ed8; }
        .pill.rose { background: var(--rose-soft); color: var(--rose-text); }

        /* --- Messages & Errors --- */
        .message { margin-bottom: 24px; padding: 16px; border-radius: 12px; font-size: 14px; font-weight: 500; border: 1px solid transparent; }
        .message.ok { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .message.error { background: #fff1f2; border-color: #fecdd3; color: #9f1239; }
        .errors { margin: 0; padding-left: 18px; }

        /* --- Tables --- */
        .datatable-wrap, .table-responsive { overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { 
            padding: 14px 12px; 
            text-align: left; 
            border-bottom: 2px solid var(--line); 
            text-transform: uppercase; 
            font-size: 11px; 
            font-weight: 700; 
            letter-spacing: 0.5px; 
            color: var(--muted); 
        }
        td { 
            padding: 16px 12px; 
            border-bottom: 1px solid var(--line); 
            text-align: left; 
            vertical-align: middle; 
        }
        tbody tr { transition: background-color 0.2s ease; }
        tbody tr:hover { background-color: rgba(238, 243, 241, 0.6); }
        .text-right { text-align: right; }
        .muted { color: var(--muted); }
        .strong { font-weight: 600; color: var(--dark); }

        /* --- Forms & Inputs --- */
        form { display: grid; gap: 16px; }
        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--ink); }
        input, select, textarea { 
            width: 100%; 
            font: inherit; 
            padding: 12px 16px; 
            border-radius: 10px; 
            border: 1px solid var(--line); 
            background: rgba(255, 255, 255, 0.9); 
            outline: none; 
            transition: all 0.2s ease; 
            color: var(--ink);
        }
        input:focus, select:focus, textarea:focus { 
            border-color: var(--accent); 
            box-shadow: 0 0 0 4px rgba(11, 93, 86, 0.12); 
            background: #fff; 
        }
        textarea { min-height: 120px; resize: vertical; }

        /* --- Filters & Actions --- */
        .filters-bar { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 12px; margin-bottom: 18px; align-items: end; }
        .filters-bar.compact { grid-template-columns: 1.8fr auto auto; }
        .filters-panel { margin-bottom: 18px; padding: 20px; border-radius: 16px; background: #fff; border: 1px solid var(--line); box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .filters-grid { display: grid; grid-template-columns: 1.8fr 1fr 1fr; gap: 12px; align-items: end; }
        .filters-grid.secondary { grid-template-columns: 1fr 1fr auto; margin-top: 12px; }
        .filters-grid.single-row { grid-template-columns: minmax(240px, 1.8fr) minmax(150px, 1fr) minmax(150px, 1fr) minmax(150px, 0.9fr) minmax(150px, 0.9fr) auto; }
        .filters-grid.single-row + .filters-grid.secondary { display: none; }
        .filters-actions { display: flex; gap: 10px; align-items: end; flex-wrap: wrap; }

        /* --- Modals --- */
        .modal { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; padding: 24px; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); z-index: 50; }
        .modal.is-visible { display: flex; }
        .modal-card { width: min(100%, 720px); max-height: calc(100vh - 48px); overflow: auto; padding: 28px; border-radius: 20px; background: #fff; border: 1px solid var(--line); box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); }
        .modal-head { display: flex; align-items: start; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
        .modal-head h2 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; }
        .icon-button { width: 36px; height: 36px; padding: 0; border-radius: 10px; background: rgba(15, 23, 42, 0.05); color: var(--dark); box-shadow: none; display: flex; align-items: center; justify-content: center; }
        .icon-button:hover { background: rgba(15, 23, 42, 0.1); }

        /* --- Otros componentes específicos --- */
        .movement-list { display: grid; gap: 12px; }
        .movement-item { display: flex; justify-content: space-between; gap: 18px; padding: 18px; border-radius: 12px; border: 1px solid var(--line); background: rgba(255, 255, 255, 0.7); }
        .movement-item p { margin: 4px 0 0; font-size: 14px; color: var(--muted); }
        .list { display: grid; gap: 12px; }
        .list-card { padding: 16px; border-radius: 12px; border: 1px solid var(--line); background: #fff; }
        .row-item { display: grid; grid-template-columns: 160px minmax(0, 1fr); gap: 12px; padding: 16px; border: 1px solid var(--line); border-radius: 12px; background: rgba(255, 255, 255, 0.72); }
        .act-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .stock-lookup-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-top: 18px; }
        .stock-card { padding: 20px; border-radius: 16px; border: 1px solid var(--line); background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .stock-card-link { display: block; transition: all 0.2s ease; }
        .stock-card-link:hover { transform: translateY(-2px); border-color: rgba(15, 118, 110, 0.3); box-shadow: 0 12px 25px rgba(20, 33, 43, 0.08); }
        .stock-card-head { display: flex; justify-content: space-between; gap: 12px; align-items: start; }
        .stock-card h3 { margin: 0; font-size: 20px; font-weight: 700; }
        .stock-card p { margin: 4px 0 0; color: var(--muted); font-size: 14px; }
        .stock-card-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-top: 16px; }
        .stock-card-stats div { padding: 12px 14px; border-radius: 12px; background: rgba(238, 243, 241, 0.92); }
        .stock-card-stats small { display: block; color: var(--muted); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .stock-card-stats strong { display: block; margin-top: 4px; font-size: 20px; font-weight: 700; }

        .permission-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .permission-option { display: flex; align-items: center; gap: 10px; margin: 0; padding: 12px 14px; border: 1px solid var(--line); border-radius: 10px; background: rgba(255, 255, 255, 0.72); cursor: pointer; font-weight: 500; }
        .permission-option input { width: 18px; height: 18px; margin: 0; flex: 0 0 auto; }
        .permission-group { padding: 16px; border-radius: 12px; border: 1px solid var(--line); background: #fff; }
        .permission-group-title { margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--dark); }
        .permission-group-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        
        .pagination { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-top: 24px; flex-wrap: wrap; }
        .pagination .links { display: flex; gap: 8px; flex-wrap: wrap; }
        .pagination a, .pagination span.current { display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; border-radius: 8px; background: #fff; border: 1px solid var(--line); font-size: 14px; font-weight: 500; transition: all 0.2s ease; }
        .pagination a:hover { background: var(--bg); }
        .pagination span.current { background: var(--dark); color: #fff; border-color: var(--dark); }
        .table-actions { display: inline-flex; align-items: center; justify-content: flex-end; gap: 8px; flex-wrap: wrap; }
        .empty { padding: 32px; border-radius: 16px; border: 1px dashed var(--line); color: var(--muted); text-align: center; font-size: 15px; }

        /* ========================================================= */
        /* ============== MENÚ HAMBURGUESA (OCULTO EN PC) ========== */
        /* ========================================================= */
        .sidebar-toggle, 
        .sidebar-toggle-label {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            position: absolute !important;
            width: 0 !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            z-index: -9999 !important;
            pointer-events: none !important;
        }

        /* ========================================================= */
        /* ============== MEDIA QUERIES (TABLETS Y MÓVILES) ======== */
        /* ========================================================= */
        @media (max-width: 1100px) {
            .app-shell, .grid-2, .summary-row, .field-grid, .mini-grid, .filters-bar, .filters-bar.compact, .filters-grid, .filters-grid.secondary, .act-form-grid, .row-item {
                grid-template-columns: 1fr;
            }
            .sidebar { border-right: 0; border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
            .content { padding: 22px 16px 28px; }
            .page-header h1 { font-size: 32px; }
            .movement-item { flex-direction: column; }
            .stock-lookup-grid, .stock-card-stats { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            /* Mostrar y posicionar el botón de hamburguesa */
            .sidebar-toggle-label {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: fixed !important;
                top: 15px !important;
                left: 15px !important;
                width: 45px !important;
                height: 45px !important;
                z-index: 1000 !important;
                background: var(--dark) !important;
                color: white !important;
                border-radius: 8px;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                cursor: pointer;
                margin: 0 !important;
                padding: 0 !important;
                pointer-events: auto !important;
            }

            .app-shell { display: block; }

            .sidebar {
                display: none;
                width: 100%;
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 999;
                overflow-y: auto;
                padding-top: 60px; /* Espacio para el botón */
            }

            .sidebar-toggle:checked ~ .app-shell .sidebar { display: block; }

            .page-header { display: flex; flex-direction: column; align-items: flex-start; gap: 10px; margin-top: 60px; }
            .page-header h1 { font-size: 28px; }
            .content { padding: 15px 10px; }
            .header-actions { display: flex; flex-direction: column; width: 100%; gap: 8px; }
            .header-actions a, .header-actions button { width: 100%; text-align: center; justify-content: center; }
            table { font-size: 13px; }
            th, td { padding: 10px 8px; }
        }
    </style>
        
</head>

<body>
    <!-- Botón de menú para móviles -->
    <input type="checkbox" id="sidebar-toggle" class="sidebar-toggle">
    <label for="sidebar-toggle" class="sidebar-toggle-label">
        <i class="fa-solid fa-bars"></i>
    </label>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand-block">
                <img src="{{ asset('logoA.png') }}" alt="Logo institucional" class="brand-logo">
                <div>
                    <div class="brand-title">SISTEMA DE INVENTARIO</div>
                    <div class="brand-subtitle">Secretaria de Innovacion y Digitalizacion</div>
                </div>
            </div>

            <nav class="nav">
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_ITEMS))
                    <a href="{{ route('inventory.items.index') }}"
                        class="{{ request()->routeIs('inventory.items.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i>
                        <strong>Insumos</strong>
                    </a>
                    <a href="{{ route('inventory.stock-lookup.index') }}"
                        class="{{ request()->routeIs('inventory.stock-lookup.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-magnifying-glass-chart" aria-hidden="true"></i>
                        <strong>Consulta stock</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_DELIVERY_ACTS))
                    <a href="{{ route('inventory.delivery-act.form') }}"
                        class="{{ request()->routeIs('inventory.delivery-act.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-signature" aria-hidden="true"></i>
                        <strong>Actas</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_DASHBOARD))
                    <a href="{{ route('inventory.dashboard') }}"
                        class="{{ request()->routeIs('inventory.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                        <strong>Dashboard</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_MOVEMENTS))
                    <a href="{{ route('inventory.movements.index') }}"
                        class="{{ request()->routeIs('inventory.movements.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-right-left" aria-hidden="true"></i>
                        <strong>Movimientos</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_EQUIPMENTS))
                    <a href="{{ route('equipments.index') }}"
                        class="{{ request()->routeIs('equipments.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-computer" aria-hidden="true"></i>
                        <strong>Equipos</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_USERS))
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users" aria-hidden="true"></i>
                        <strong>Usuarios</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_CATALOGS))
                    <a href="{{ route('catalogs.equipment-categories.index') }}"
                        class="{{ request()->routeIs('catalogs.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-folder-tree" aria-hidden="true"></i>
                        <strong>Catalogo</strong>
                    </a>
                @endif
            </nav>

            <div class="sidebar-user">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->position ?: 'Usuario del sistema' }}</span>
                <span>{{ auth()->user()->isAdmin() ? 'Administrador' : 'Acceso personalizado' }}</span>
                <span>{{ auth()->user()->username }}</span>
            </div>

            <div class="sidebar-stats">
                <h3>Resumen rapido</h3>
                <div class="mini-grid">
                    <div class="mini-card">
                        <div class="stat-head"><i class="fa-solid fa-cubes"></i><small>Stock</small></div>
                        <strong>{{ $summary['stock'] }}</strong>
                    </div>
                    <div class="mini-card">
                        <div class="stat-head"><i class="fa-solid fa-box-open"></i><small>Insumos</small></div>
                        <strong>{{ $summary['items'] }}</strong>
                    </div>
                    <div class="mini-card">
                        <div class="stat-head"><i class="fa-solid fa-arrow-down"></i><small>Entradas</small></div>
                        <strong>{{ $summary['entries'] }}</strong>
                    </div>
                    <div class="mini-card">
                        <div class="stat-head"><i class="fa-solid fa-arrow-up"></i><small>Salidas</small></div>
                        <strong>{{ $summary['exits'] }}</strong>
                    </div>
                </div>
            </div>
        </aside>

        <main class="content">
            <div class="topbar">
                <div class="topbar-user">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->username }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="button-link dark"><i class="fa-solid fa-right-from-bracket"></i>
                        Salir</button>
                </form>
            </div>

            @if (session('status'))
                <div class="message ok">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="message error">
                    <ul class="errors">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        (() => {
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    window.location.reload();
                }
            });
        })();

        (() => {
            const closeModal = (modal) => {
                if (!modal) return;
                modal.classList.remove('is-visible');
                document.body.style.overflow = '';
            };

            const openModal = (id) => {
                const modal = document.getElementById(id);
                if (!modal) return;
                modal.classList.add('is-visible');
                document.body.style.overflow = 'hidden';
            };

            window.inventoryOpenModal = openModal;

            document.querySelectorAll('[data-open-modal]').forEach((button) => {
                button.addEventListener('click', () => openModal(button.getAttribute('data-open-modal')));
            });

            document.querySelectorAll('[data-close-modal]').forEach((button) => {
                button.addEventListener('click', () => closeModal(button.closest('.modal')));
            });

            document.querySelectorAll('.modal').forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    document.querySelectorAll('.modal.is-visible').forEach(closeModal);
                }
            });
        })();
    </script>
</body>

</html>
