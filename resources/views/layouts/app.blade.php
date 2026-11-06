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
            font-family: Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.14), transparent 24%),
                radial-gradient(circle at right center, rgba(29, 78, 216, 0.10), transparent 22%),
                linear-gradient(180deg, #f8fbfa 0%, var(--bg) 100%);
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

        .brand-block {
            display: grid;
            justify-items: start;
            gap: 12px;
        }

        .brand-logo {
            display: block;
            width: 134px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.22em;
            color: #7dd3fc;
        }

        .nav {
            margin-top: 28px;
            display: grid;
            gap: 10px;
        }

        .nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid rgba(255, 255, 255, 0.04);
            transition: 180ms ease;
        }

        .nav a:hover {
            transform: translateX(2px);
            background: rgba(255, 255, 255, 0.06);
        }

        .nav a.active {
            background: rgba(125, 211, 252, 0.14);
            border-color: rgba(125, 211, 252, 0.20);
        }

        .nav strong {
            display: block;
            font-size: 15px;
        }

        .nav i {
            width: 18px;
            text-align: center;
            color: #7dd3fc;
            font-size: 15px;
        }

        .sidebar-stats {
            margin-top: 28px;
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-user {
            margin-top: 28px;
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-user strong {
            display: block;
            font-size: 16px;
        }

        .sidebar-user span {
            display: block;
            margin-top: 6px;
            color: rgba(255, 255, 255, 0.68);
            font-size: 13px;
        }

        .sidebar-stats h3 {
            margin: 0 0 14px;
            font-size: 14px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
        }

        .mini-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .mini-card {
            padding: 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.06);
        }

        .mini-card small {
            display: block;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.66);
        }

        .mini-card strong {
            display: block;
            margin-top: 6px;
            font-size: 24px;
        }

        .stat-head {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-head i {
            color: rgba(255, 255, 255, 0.76);
            font-size: 13px;
        }

        .content {
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .topbar-user {
            text-align: right;
        }

        .topbar-user strong {
            display: block;
            font-size: 14px;
        }

        .topbar-user span {
            display: block;
            margin-top: 3px;
            font-size: 13px;
            color: var(--muted);
        }

        .hero-note {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(11, 93, 86, 0.10);
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: end;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 42px;
            line-height: 1.05;
        }

        .page-header p {
            margin: 10px 0 0;
            color: var(--muted);
            line-height: 1.7;
            max-width: 760px;
        }

        .summary-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card, .panel {
            background: var(--panel);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            box-shadow: 0 18px 48px rgba(20, 33, 43, 0.08);
        }

        .summary-card {
            padding: 22px;
        }

        .summary-card small {
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--muted);
        }

        .summary-card strong {
            display: block;
            margin-top: 8px;
            font-size: 34px;
        }

        .panel {
            padding: 24px;
        }

        .panel.soft {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 243, 231, 0.88));
        }

        .toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .toolbar form {
            display: block;
            gap: 0;
        }

        .button-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: auto;
            padding: 12px 16px;
            border-radius: 16px;
            border: 0;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        .button-link:hover {
            transform: translateY(-1px);
        }

        .button-link.dark {
            color: #fff;
            background: linear-gradient(180deg, #18283d 0%, #0f172a 100%);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.18);
        }

        .button-link.green {
            color: #fff;
            background: linear-gradient(180deg, #11998e 0%, #0f766e 100%);
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
        }

        .stack {
            display: grid;
            gap: 24px;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 18px;
        }

        .section-head h2 {
            margin: 0;
            font-size: 30px;
        }

        .section-head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .pill {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .pill.dark { background: var(--dark); color: #fff; }
        .pill.green { background: var(--green-soft); color: #115e59; }
        .pill.amber { background: var(--amber-soft); color: #92400e; }
        .pill.blue { background: var(--blue-soft); color: #1d4ed8; }
        .pill.rose { background: var(--rose-soft); color: var(--rose-text); }

        .message {
            margin-bottom: 24px;
            padding: 14px 16px;
            border-radius: 18px;
            font-size: 14px;
        }

        .message.ok {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .message.error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }

        .errors {
            margin: 0;
            padding-left: 18px;
        }

        .datatable-wrap {
            overflow-x: auto;
        }

        .filters-bar {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 12px;
            margin-bottom: 18px;
            align-items: end;
        }

        .filters-bar.compact {
            grid-template-columns: 1.8fr auto auto;
        }

        .filters-panel {
            margin-bottom: 18px;
            padding: 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 248, 247, 0.92));
            border: 1px solid rgba(20, 33, 43, 0.08);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr;
            gap: 12px;
            align-items: end;
        }

        .filters-grid.secondary {
            grid-template-columns: 1fr 1fr auto;
            margin-top: 12px;
        }

        .filters-grid.single-row {
            grid-template-columns: minmax(240px, 1.8fr) minmax(150px, 1fr) minmax(150px, 1fr) minmax(150px, 0.9fr) minmax(150px, 0.9fr) auto;
        }

        .filters-grid.single-row + .filters-grid.secondary {
            display: none;
        }

        .filters-actions {
            display: flex;
            gap: 10px;
            align-items: end;
            flex-wrap: wrap;
        }

        .act-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .rows-panel {
            display: grid;
            gap: 12px;
        }

        .row-item {
            display: grid;
            grid-template-columns: 160px minmax(0, 1fr);
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.72);
        }

        .subtle-note {
            font-size: 13px;
            color: var(--muted);
        }

        .permission-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .permission-grid.is-disabled {
            opacity: 0.72;
        }

        .permission-option {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.72);
            cursor: pointer;
            font-weight: 600;
        }

        .permission-option input {
            width: 18px;
            height: 18px;
            margin: 0;
            flex: 0 0 auto;
        }

        .stock-lookup-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .stock-card {
            padding: 18px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.82);
            box-shadow: 0 14px 34px rgba(20, 33, 43, 0.08);
        }

        .stock-card-link {
            display: block;
            transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease;
        }

        .stock-card-link:hover {
            transform: translateY(-2px);
            border-color: rgba(15, 118, 110, 0.22);
            box-shadow: 0 18px 38px rgba(20, 33, 43, 0.12);
        }

        .stock-card-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }

        .stock-card h3 {
            margin: 0;
            font-size: 21px;
        }

        .stock-card p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .stock-card-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .stock-card-stats div {
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(238, 243, 241, 0.92);
        }

        .stock-card-stats small {
            display: block;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .stock-card-stats strong {
            display: block;
            margin-top: 6px;
            font-size: 22px;
        }

        .search-select {
            position: relative;
        }

        .search-select-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            max-height: 260px;
            overflow: auto;
            padding: 8px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.14);
            z-index: 15;
        }

        .search-select-menu[hidden] {
            display: none;
        }

        .search-select-option {
            width: 100%;
            padding: 12px 14px;
            border: 0;
            border-radius: 14px;
            background: transparent;
            color: var(--ink);
            text-align: left;
            box-shadow: none;
            cursor: pointer;
        }

        .search-select-option:hover,
        .search-select-option.is-active {
            background: rgba(15, 118, 110, 0.10);
            transform: none;
        }

        .search-select-empty {
            padding: 12px 14px;
            color: var(--muted);
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
        }

        th {
            color: var(--muted);
            font-weight: 700;
        }

        .text-right { text-align: right; }
        .muted { color: var(--muted); }
        .strong { font-weight: 700; }

        .movement-list {
            display: grid;
            gap: 12px;
        }

        .movement-item {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            padding: 16px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.76);
        }

        .movement-item p {
            margin: 6px 0 0;
            font-size: 14px;
            color: var(--muted);
        }

        .list {
            display: grid;
            gap: 12px;
        }

        .list-card {
            padding: 16px;
            border-radius: 20px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.72);
        }

        form {
            display: grid;
            gap: 16px;
        }

        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #243744;
        }

        input, select, textarea, button {
            width: 100%;
            font: inherit;
        }

        input, select, textarea {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.96);
            outline: none;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
            transition: border-color 160ms ease, box-shadow 160ms ease, transform 160ms ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: rgba(11, 93, 86, 0.35);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.10);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        button {
            border: 0;
            border-radius: 18px;
            padding: 14px 16px;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(180deg, #18283d 0%, #0f172a 100%);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.18);
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(15, 23, 42, 0.22);
        }

        button.green {
            background: linear-gradient(180deg, #11998e 0%, #0f766e 100%);
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .pagination .links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span.current {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--line);
            font-size: 14px;
        }

        .pagination span.current {
            background: var(--dark);
            color: #fff;
            border-color: var(--dark);
        }

        .table-actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .permission-group {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.82);
        }

        .permission-group-title {
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 800;
            color: #243744;
        }

        .permission-group-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .empty {
            padding: 26px;
            border-radius: 24px;
            border: 1px dashed var(--line);
            color: var(--muted);
            text-align: center;
        }

        .modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(15, 23, 42, 0.46);
            backdrop-filter: blur(6px);
            z-index: 50;
        }

        .modal.is-visible {
            display: flex;
        }

        .modal-card {
            width: min(100%, 720px);
            max-height: calc(100vh - 48px);
            overflow: auto;
            padding: 24px;
            border-radius: 28px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 243, 231, 0.92));
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.24);
        }

        .modal-head {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .modal-head h2 {
            margin: 8px 0 0;
            font-size: 28px;
        }

        .icon-button {
            width: 42px;
            height: 42px;
            padding: 0;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.06);
            color: var(--dark);
            box-shadow: none;
        }

        @media (max-width: 1100px) {
            .app-shell,
            .grid-2,
            .summary-row,
            .field-grid,
            .mini-grid,
            .filters-bar,
            .filters-bar.compact,
            .filters-grid,
            .filters-grid.secondary,
            .act-form-grid,
            .row-item {
                grid-template-columns: 1fr;
            }

                    /* --- MEJORAS RESPONSIVE PARA MÓVILES --- */
        
        /* 1. Contenedor para hacer las tablas deslizables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        /* 2. Estilos base del botón hamburguesa (oculto en PC) */
        .sidebar-toggle { display: none; }
        .sidebar-toggle-label { display: none; }

        /* 3. Nuevo breakpoint para celulares y tablets pequeñas */
        @media (max-width: 768px) {
            /* Mostrar el botón hamburguesa fijo arriba a la izquierda */
            .sidebar-toggle-label {
                display: flex;
                align-items: center;
                justify-content: center;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1000;
                background: var(--dark);
                color: white;
                width: 45px;
                height: 45px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                cursor: pointer;
                font-size: 20px;
            }

            /* El layout principal se vuelve un bloque */
            .app-shell {
                display: block;
            }

            /* Ocultar el menú lateral por defecto */
            .sidebar {
                display: none;
                width: 100%;
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 999;
                overflow-y: auto;
            }

            /* Cuando el checkbox está marcado, mostrar el menú */
            .sidebar-toggle:checked ~ .app-shell .sidebar {
                display: block;
            }

            /* Ajustar los títulos y cabeceras para que no se aplasten */
            .page-header {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                margin-top: 50px; /* Espacio para el botón hamburguesa */
            }

            .page-header h1 {
                font-size: 28px;
            }

            .content {
                padding: 15px 10px;
            }

            /* Los botones de acción se ponen uno debajo del otro */
            .header-actions {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 8px;
            }
            
            .header-actions a, .header-actions button {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            /* Reducir un poco el tamaño de las letras en tablas */
            table { font-size: 12px; }
            th, td { padding: 8px; }
        }

            .sidebar {
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }

            .content {
                padding: 22px 16px 28px;
            }

            .page-header h1 {
                font-size: 34px;
            }

            .movement-item {
                flex-direction: column;
            }

            .stock-lookup-grid {
                grid-template-columns: 1fr;
            }

            .stock-card-stats {
                grid-template-columns: 1fr;
            }
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
                    <a href="{{ route('inventory.items.index') }}" class="{{ request()->routeIs('inventory.items.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i>
                        <strong>Insumos</strong>
                    </a>
                    <a href="{{ route('inventory.stock-lookup.index') }}" class="{{ request()->routeIs('inventory.stock-lookup.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-magnifying-glass-chart" aria-hidden="true"></i>
                        <strong>Consulta stock</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_DELIVERY_ACTS))
                    <a href="{{ route('inventory.delivery-act.form') }}" class="{{ request()->routeIs('inventory.delivery-act.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-signature" aria-hidden="true"></i>
                        <strong>Actas</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_DASHBOARD))
                    <a href="{{ route('inventory.dashboard') }}" class="{{ request()->routeIs('inventory.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                        <strong>Dashboard</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_MOVEMENTS))
                    <a href="{{ route('inventory.movements.index') }}" class="{{ request()->routeIs('inventory.movements.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-right-left" aria-hidden="true"></i>
                        <strong>Movimientos</strong>
                    </a>
                @endif
                @if (auth()->user()->hasPermission(\App\Models\User::PERMISSION_EQUIPMENTS))
                    <a href="{{ route('equipments.index') }}" class="{{ request()->routeIs('equipments.*') ? 'active' : '' }}">
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
                    <a href="{{ route('catalogs.equipment-categories.index') }}" class="{{ request()->routeIs('catalogs.*') ? 'active' : '' }}">
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
                    <button type="submit" class="button-link dark"><i class="fa-solid fa-right-from-bracket"></i> Salir</button>
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
