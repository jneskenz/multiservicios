<!doctype html>

<html lang="en" class=" layout-wide  customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light"
    data-assets-path="{{ asset('vuexy') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Demo: Login Basic - Pages | Vuexy - Bootstrap Dashboard PRO</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('vuexy/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('vuexy/vendor/fonts/iconify-icons.css') }}" />

    <script src="{{ asset('vuexy/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/node-waves/node-waves.css') }}" />

    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('vuexy/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/@form-validation/form-validation.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('vuexy/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('vuexy/vendor/js/template-customizer.js') }}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('vuexy/js/config.js') }}"></script>
</head>

<body>

    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        {{-- <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="javascript:void(0);" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('vuexy/img/logo/multisoft.png') }}" alt=""
                                        width="150">
                                </span>
                            </a>
                        </div> --}}
                        <!-- /Logo -->
                        <h4 class="mb-1 pt-2">¡Bienvenido a Optivas Multilens!</h4>
                        <p class="mb-4">Ingrese sus credenciales.</p>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible d-flex" role="alert">
                                    <span class="alert-icon rounded"><i class="ti tabler-x"></i></span>
                                    <div>
                                        {{-- <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">Error</h6> --}}
                                        <p class="mb-0">{{ session('error') }}</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Contraseña</label>

                                </div>
                                <div class="input-group input-group-merge">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">
                                    <span class="input-group-text cursor-pointer"><i
                                            class="ti tabler-eye-off"></i></span>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember-me"> Recordarme </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        <small>¿Olvidaste tu contraseña?</small>
                                    </a>
                                @endif
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-dark d-grid w-100" type="submit">Iniciar sesión</button>
                            </div>
                        </form>

                        <p class="text-center my-5">
                            <span>¿Nuevo en nuestra plataforma?</span>
                            <a href="javascript:void(0);">
                                <span>Contáctate con su administrador</span>
                            </a>
                        </p>

                        <div class="divider my-5">
                            <div class="divider-text">Visita nuestras redes</div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
                                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
                                <i class="tf-icons fa-brands fa-google fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                                <i class="tf-icons fa-brands fa-twitter fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Register -->
                <div class="card mt-2">
                    <div class="card-body py-2 px-4 d-flex justify-content-between">
                        <span class="text-left" id="fecha_Actual"></span>
                        <span class="text-right" id="reloj_Actual"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // hora actual
        function actualizarReloj() {
            var ahora = new Date();
            var horas = ahora.getHours();
            var minutos = ahora.getMinutes();
            var segundos = ahora.getSeconds();

            horas = horas < 10 ? '0' + horas : horas;
            minutos = minutos < 10 ? '0' + minutos : minutos;
            segundos = segundos < 10 ? '0' + segundos : segundos;

            var tiempoActual = horas + ':' + minutos + ':' + segundos;
            document.getElementById('reloj_Actual').textContent = tiempoActual;
        }

        setInterval(actualizarReloj, 1000);
        actualizarReloj(); // Para que muestre la hora de inmediato al cargar la página

        // fecha actual
        function obtenerFechaActual() {
            // Crear un objeto de fecha
            var fechaActual = new Date();

            // Días de la semana y meses en español
            var diasSemana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
            var meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre",
                "noviembre", "diciembre"
            ];

            // Obtener el día de la semana, el día del mes y el mes
            var diaSemana = diasSemana[fechaActual.getDay()];
            var diaMes = fechaActual.getDate();
            var mes = meses[fechaActual.getMonth()];
            var año = fechaActual.getFullYear();

            // Formatear la fecha
            var fechaFormateada = `${diaSemana}, ${diaMes} de ${mes} del ${año}`;
            console.log(fechaFormateada);
            // Mostrar la fecha en el elemento con id "fechaActual"
            document.getElementById("fecha_Actual").innerText = fechaFormateada;
        }

        // Llamar a la función al cargar la página
        obtenerFechaActual()
    </script>


    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->

    <script src="{{ asset('vuexy/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('vuexy/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('vuexy/vendor/libs/pickr/pickr.js') }}"></script>

    <script src="{{ asset('vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('vuexy/vendor/libs/hammer/hammer.js') }}"></script>

    <script src="{{ asset('vuexy/vendor/libs/i18n/i18n.js') }}"></script>

    <script src="{{ asset('vuexy/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('vuexy/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/@form-validation/auto-focus.js') }}"></script>

    <!-- Main JS -->

    <script src="{{ asset('vuexy/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('vuexy/js/pages-auth.js') }}"></script>
</body>

</html>
