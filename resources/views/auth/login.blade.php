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
            --muted: #5b6770;
            --line: rgba(20, 33, 43, 0.12);
            --dark: #0f172a;
            --accent: #0f766e;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.14), transparent 26%),
                radial-gradient(circle at right center, rgba(29, 78, 216, 0.10), transparent 20%),
                linear-gradient(180deg, #f8fbfa 0%, var(--bg) 100%);
        }

        .login-shell {
            width: min(100%, 960px);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            border-radius: 32px;
            overflow: hidden;
            background: var(--panel);
            box-shadow: 0 28px 70px rgba(15, 23, 42, 0.18);
        }

        .login-brand {
            padding: 42px;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.98) 0%, rgba(8, 15, 28, 0.98) 100%);
            color: #fff;
        }

        .login-brand img {
            width: 160px;
            max-width: 100%;
            height: auto;
        }

        .login-brand h1 {
            margin: 24px 0 10px;
            font-size: 40px;
            line-height: 1.05;
        }

        .login-brand p {
            margin: 0;
            color: rgba(255, 255, 255, 0.72);
            line-height: 1.7;
        }

        .login-form {
            padding: 42px;
            display: grid;
            align-content: center;
            gap: 18px;
        }

        .login-form h2 {
            margin: 0;
            font-size: 30px;
        }

        .login-form p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
        }

        input, button {
            width: 100%;
            font: inherit;
        }

        input {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.96);
        }

        button {
            border: 0;
            border-radius: 18px;
            padding: 14px 16px;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(180deg, #11998e 0%, #0f766e 100%);
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
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
            padding: 14px 16px;
            border-radius: 18px;
            font-size: 14px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }

        .credentials {
            padding: 14px 16px;
            border-radius: 18px;
            background: #ecfeff;
            border: 1px solid #a5f3fc;
            color: #155e75;
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
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
