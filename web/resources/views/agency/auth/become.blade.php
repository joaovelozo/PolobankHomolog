<html lang="en">
<head>
    <title>Polocal Bank</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/tailwind/tailwind.min.css')}}">
    <link rel="icon" type="{{asset('assets/frontend/image/png')}}" sizes="32x32" href="shuffle-for-tailwind.png">
    <script src="{{('assets/frontend/js/main.js')}}"></script>
</head>
<body class="antialiased bg-body text-body font-body">
    <div class="">

      <section class="relative pt-24 lg:py-44">
        <img class="hidden lg:block absolute top-0 left-0 h-full w-1/2" src="{{asset('assets/frontend/images/ground.png')}}" alt="">
        <div class="container px-4 mx-auto">
          <div class="lg:w-1/2 ml-auto">
            <div class="relative max-w-xs lg:max-w-md mx-auto text-center">
              <a class="inline-block mx-auto mb-10" href="#">

              </a>
              <h2 class="text-2xl text-gray-100 font-semibold mb-2">Login</h2>
              <p class="text-gray-300 font-medium mb-10">Seja Bem Vindo Gestor!</p>
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

                <button class="block w-full py-4 mb-4 leading-6 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg transition duration-200">Entrar</button>


              </form>
            </div>
          </div>
        </div>
        <img class="lg:hidden mt-24" src="trizzle-assets/images/placeholder-laptop-dark-light.png" alt="">
      </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{asset('assets/frontend/js/charts-demo.js')}}"></script>
</body>
</html>
