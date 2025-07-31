<!DOCTYPE html>
<html lang="en">
<head>
    <title>Polocal Bank</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{asset('assets/login/css/tailwind/tailwind.min.css')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/fav/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/fav/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/fav/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('assets/fav/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('assets/fav/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script src="{{('assets/login/js/main.js')}}"></script>
</head>
<body class="antialiased bg-body text-body font-body">
    <div class="relative min-h-screen flex items-center justify-center bg-gray-900">
        <img class="hidden lg:block absolute top-0 left-0 h-full w-1/2 object-cover" src="{{asset('assets/login/images/ground.png')}}" alt="" class="img-fluid">
        <div class="container px-4 mx-auto">
            <div class="lg:w-1/2 ml-auto">
                <div class="relative max-w-xs lg:max-w-md mx-auto text-center">
                    <a class="inline-block mx-auto mb-10" href="#"></a>
                    <h2 class="text-2xl text-gray-100 font-semibold mb-2">Login</h2>
                    <p class="text-gray-300 font-medium mb-10">Seja Bem Vindo!</p>
                    <!-- Errors !-->
                    <div class="container bg-red-500 text-white">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <br>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="relative w-full h-14 py-4 px-3 mb-8 border border-gray-400 hover:border-white focus-within:border-green-500 rounded-lg">
                            <span class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Email</span>
                            <input class="block w-full outline-none bg-transparent text-sm text-gray-100 font-medium" id="signInInput3-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        </div>
                        <div class="relative w-full h-14 py-4 px-3 mb-6 border border-gray-400 hover:border-white focus-within:border-green-500 rounded-lg">
                            <span class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Senha</span>
                            <input class="block w-full outline-none bg-transparent text-sm text-gray-100 font-medium" id="signInInput3-2" type="password"
                            name="password"
                            required autocomplete="current-password">
                        </div>
                        <div class="flex flex-wrap items-center justify-between mb-6">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <input id="remember_me" type="checkbox"  name="remember">
                                <label class="ml-2 text-xs text-gray-300 font-semibold" for="" >Manter Conectado</label>
                            </div>
                            <div class="w-full sm:w-auto"><a class="inline-block text-xs font-semibold text-blue-500 hover:text-blue-600" href="{{route('password.request')}}">Esqueci a Senha</a></div>
                        </div>
                        <button class="block w-full py-4 mb-4 leading-6 text-white font-semibold bg-green-600 hover:bg-blue-600 rounded-lg transition duration-200">Entrar</button>

                        <!-- <div class="w-full sm:w-auto"><a class="inline-block text-xs font-semibold text-orange-500 hover:text-blue-600" href="/register/1"><h5>Criar Conta</h5></a></div>-->
                    </form>

                    <br><br><br>
                    <hr>
                </div>
                <!-- Link com Imagem centralizada -->
                <div class="text-center mt-8">
                    <a href="https://vpbm.com.br/" class="inline-block" target="_blank">
                        <img src="{{asset('assets/backend/vpbm.png')}}" alt="Conheça o VPBM" style="width: 100px; height: auto;" class="mx-auto">
                        <p class="text-white mt-2">Conheça o VPBM</p>
                    </a>
                </div>

                <div class="text-center mt-8">
                    <a href="https://clientes.security.vpbm.com.br/login" class="inline-block" target="_blank">

                        <p class="text-white mt-2">Sou Cliente VPBM</p>
                    </a>
                </div>

            </div>


        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{asset('assets/login/js/charts-demo.js')}}"></script>
</body>
</html>
