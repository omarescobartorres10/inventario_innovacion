<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso | Inventario Innovacionnnn</title>
    <link rel="icon" type="image/png" href="{{ asset('logoA.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logoA.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoA.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --bg: #eef3f1;
            --panel: rgba(255, 255, 255, 0.96);
            --ink: #14212b;
            --muted: #64748b;
            --line: rgba(20, 33, 43, 0.12);
            --dark: #0f172a;
            --accent: #0f766e;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 32px;
            --shadow-lg: 0 10px 25px rgba(15, 23, 42, 0.07), 0 4px 10px rgba(15, 23, 42, 0.04);
            --shadow-xl: 0 20px 50px rgba(15, 23, 42, 0.12), 0 8px 20px rgba(15, 23, 42, 0.06);
            --transition-base: 0.2s ease;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse at 20% 0%, rgba(15, 118, 110, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(29, 78, 216, 0.05) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 100%, rgba(184, 134, 11, 0.03) 0%, transparent 50%),
                linear-gradient(180deg, #f8fbfa 0%, var(--bg) 100%);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .login-shell {
            width: min(100%, 960px);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            border-radius: var(--radius-xl);
            overflow: hidden;
            background: var(--panel);
            box-shadow: var(--shadow-xl);
            animation: loginIn 0.4s ease;
        }

        @keyframes loginIn {
            from { opacity: 0; transform: translateY(16px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-brand {
            padding: 48px 42px;
            background: linear-gradient(160deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .login-brand img {
            width: 150px;
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
        }

        .login-brand h1 {
            margin: 28px 0 12px;
            font-size: 36px;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-brand p {
            margin: 0;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.7;
            font-size: 15px;
        }

        .login-form {
            padding: 48px 42px;
            display: grid;
            align-content: center;
            gap: 20px;
        }

        .login-form h2 {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--dark);
        }

        .login-form p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        input, button {
            width: 100%;
            font: inherit;
        }

        input {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            border: 1.5px solid var(--line);
            background: rgba(255, 255, 255, 0.95);
            outline: none;
            transition: all var(--transition-base);
            color: var(--ink);
        }

        input:hover {
            border-color: rgba(20, 33, 43, 0.18);
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.1);
            background: #fff;
        }

        button {
            border: 0;
            border-radius: var(--radius-md);
            padding: 15px 20px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            background: linear-gradient(180deg, #0d9488 0%, #0f766e 100%);
            box-shadow: 0 8px 20px rgba(15, 118, 110, 0.2), inset 0 1px 0 rgba(255,255,255,0.1);
            transition: all var(--transition-base);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover {
            background: linear-gradient(180deg, #14b8a6 0%, #0d9488 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(15, 118, 110, 0.25), inset 0 1px 0 rgba(255,255,255,0.12);
        }

        button:active {
            transform: translateY(0) scale(0.98);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: var(--muted);
        }

        .remember input {
            width: auto;
        }

        .message {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            border: 1px solid #fda4af;
            color: #9f1239;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .credentials {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%);
            border: 1px solid #67e8f9;
            color: #155e75;
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-brand {
                padding: 36px 28px;
            }

            .login-brand h1 {
                font-size: 28px;
                margin-top: 20px;
            }

            .login-form {
                padding: 36px 28px;
            }

            .login-form h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <section class="login-brand">
            <img src="{{ asset('logoA.png') }}" alt="Logo institucional">
            <h1>Secretaría de Innovación</h1>
            <p>La Paz Este.</p>
        </section>

        <section class="login-form">
            <div>
                <h2>Iniciar sesion</h2>
                <p>Ingresa con tu usuario y contrasenia para continuar.</p>
            </div>

            @if ($errors->any())
                <div class="message">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST">
                @csrf
                <div>
                    <label for="username">Usuario</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username">
                </div>

                <div>
                    <label for="password">Contrasena</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password">
                </div>

                <label class="remember" for="remember">
                    <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                    Recordar sesion
                </label>

                <button type="submit"><i class="fa-solid fa-right-to-bracket"></i> Entrar al sistema</button>
            </form>
        </section>
    </div>
</body>
</html>
