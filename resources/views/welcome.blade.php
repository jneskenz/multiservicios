<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Bienvenido a ERP Multilens</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #7367F0;
            --primary-dark: #5e50ee;
            --secondary: #A8AAAE;
            --dark: #4B4B4B;
        }

        html, body {
            height: 100%;
            font-family: 'Public Sans', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .btn-login {
            background: var(--primary);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(115, 103, 240, 0.4);
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 3rem 2rem;
        }

        .hero-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            max-width: 700px;
        }

        .btn-primary {
            background: white;
            color: var(--primary);
            padding: 1rem 3rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        footer {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            color: rgba(255, 255, 255, 0.8);
            padding: 2rem 0;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-text {
            font-size: 0.9rem;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: white;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .logo {
                font-size: 1.2rem;
            }

            .logo-icon {
                width: 35px;
                height: 35px;
                font-size: 1.2rem;
            }

            .btn-login {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .main-content {
                padding: 2rem 1rem;
            }

            .hero-icon {
                font-size: 3.5rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
                margin-bottom: 2rem;
            }

            .btn-primary {
                padding: 0.875rem 2rem;
                font-size: 1rem;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-text {
                font-size: 0.85rem;
            }

            .footer-links {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.75rem;
            }

            .hero-subtitle {
                font-size: 0.9rem;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="javascript:void(0);" class="logo">
                    <div class="logo-icon">👓</div>
                    <span>{{ config('app.name', 'ERP Multisoft') }}</span>
                </a>               
                    
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn-login nav-link"><span>→</span> Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login nav-link"><span>→</span> Iniciar Sesión</a>                            
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="hero-icon">👓</div>
        <h1 class="hero-title">
            @if (Route::has('login'))
                ¡Bienvenido de nuevo!
            @else
                Bienvenido a nuestra plataforma
            @endif
        </h1>
        <p class="hero-subtitle">Ver bien no es un lujo, es tu derecho. ¡Gestiona tu óptica con estilo y eficiencia!</p>
        
            
            @if (Route::has('login'))
                @auth
                <a href="{{ url('/home') }}" class="btn-primary">
                    <span>Regresar al Sistema ERP</span><span>→</span>
                </a>
                @else
                <a href="{{ route('login') }}" class="btn-primary">
                    <span>Comenzar Ahora</span><span>→</span>
                </a>
                @endauth
            @endif
        
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-text">
                    © {{ date('Y') }} <a class="footer-link" href="javascript:void(0);">ERP Multisoft</a>. Hecho con ❤️ para mejorar tu visión del negocio.
                </div>
                <div class="footer-links">
                    <a href="#" class="footer-link">Soporte</a>
                    <a href="#" class="footer-link">Documentación</a>
                    <a href="#" class="footer-link">Contacto</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>