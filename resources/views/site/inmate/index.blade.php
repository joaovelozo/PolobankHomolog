
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Polocal Bank Of Investment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/general-sans?styles=135312,135310,135313,135303">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap/bootstrap.min.css')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/frontend/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/frontend/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/frontend/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('assets/frontend/site.webmanifest')}}">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6WF9P5M8E5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-6WF9P5M8E5');
    </script>

</head>
<body>
    <div class="">

        <section>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <a class="navbar-brand me-6" href="{{ url('/') }}"><img class="img-fluid"
                            src="{{ asset('assets/frontend/images/logo-png-1722000001641.webp') }}" alt=""></a>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav">
                            <li class="nav-item text-white"><a class="nav-link" href="{{ url('/') }}"  style="color: white;">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('site.ralbank') }}"  style="color: white;">Ral Bank Of
                                    Investment</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('site.liberty') }}"  style="color: white;">Liberty Bank</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('site.contact') }}"  style="color: white;">Contato</a></li>
                        </ul>
                    </div>
                    <div class="d-none d-lg-block">
                        <a class="btn fw-medium me-4" href="#plan"  style="color: white;">Abrir Conta</a>
                        <a class="btn btn-primary" href="{{ route('login') }}">Acesso</a>

                    </div>
                    <div class="d-lg-none">
                        <button class="btn navbar-burger p-0">
                            <svg class="text-primary" width="51" height="51" viewbox="0 0 56 56" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="56" height="56" rx="28" fill="currentColor"></rect>
                                <path d="M37 32H19M37 24H19" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>
        <div class="d-none navbar-menu position-fixed top-0 start-0 bottom-0 w-75 mw-xs" style="z-index: 9999;">
          <div class="navbar-close navbar-backdrop position-fixed top-0 start-0 end-0 bottom-0 bg-dark" style="opacity: 75%;"></div>
          <nav class="position-relative h-100 w-100 d-flex flex-column justify-content-between py-8 px-8 bg-white overflow-auto">
            <div class="d-flex align-items-center">
      <a class="me-auto h4 mb-0 text-decoration-none" href="#"><img class="img-fluid" src="flaro-assets/logos/flaro-logo-black.svg" alt=""></a><a class="navbar-close" href="#">
                <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6 18L18 6M6 6L18 18" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg></a>
      </div>
            <div class="py-16">
              <ul class="nav flex-column">
                <li class="nav-item mb-8"><a class="nav-link text-dark" href="{{url('/')}}">Home</a></li>
                <li class="nav-item mb-8"><a class="nav-link text-dark" href="{{route('site.ralbank')}}">Polocal Bank</a></li>
                <li class="nav-item mb-8"><a class="nav-link text-dark" href="{{route('site.liberty')}}">Liberty Bank</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="{{route('site.contact')}}">Contato</a></li>
              </ul>
            </div>
            <div>
      <a class="btn w-100 fw-medium" href="#">Criar Conta</a>
      <a class="btn w-100 btn-primary" href="{{route('login')}}">Acessar</a>
      </div>
          </nav>
        </div>
      </section>

      <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Mensagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('message') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
      <section class="pt-24 bg-info-light">
        <div class="container">
          <div class="mw-xl mx-auto pb-52 text-center">
      <span class="fs-9 fw-semibold text-primary text-uppercase">Para Ter Direito ao Benefício</span>
            <h3 class="mt-6 mb-0">Preencha Corretamente Os Dados Abaixo.</h3>
          </div>
        </div>
        <div class="position-relative">
      <img class="d-block img-fluid start-0 w-100" src="{{asset('assets/frontend/flaro-assets/images/contact/reclusion.png')}}" alt="" style="height: 502px;">
          <div class="position-absolute top-0 start-0 w-100">
            <div class="container">
              <div class="position-relative mt-n32 mw-md mx-auto mw-lg-lg pt-8 pb-12 px-6 px-md-10 bg-white bg-opacity-75 rounded-4 shadow-lg position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 h-100 w-100" style="backdrop-filter: blur(13.5px);"></div>
                <div class="position-relative">
                  <h6 class="mw-xs mx-auto text-center fs-7 mb-8">Após Análise Sua Conta Será Ativada, Caso Todos os Dados Fornecidos Estejam Corretos!</h6>
                  <form method="POST" action="{{route('inmateform.store')}}">
                    @csrf
                    <input class="form-control mb-4" type="text" placeholder="Qual Seu Nome?" name="name" id="name">
                    <input class="form-control mb-4" type="text" placeholder="Qual Seu CPF?" name="document" id="document">
                    <input class="form-control mb-4" type="text" placeholder="Qual Seu Telefone?" name="phone" id="phone">
                    <input class="form-control mb-4" type="text" placeholder="Qual Seu Email?"  name="email" id="email">
                    <input class="form-control mb-4" type="text" placeholder="Qual O Número do Seu Processo?" name="process" id="process">
                    <input class="form-control mb-4" type="text" placeholder="Qual O Nome do Seu Advogado?" name="attorney" id="attorney">
                    <input class="form-control mb-4" type="text" placeholder="Qual O Número Da OAB  do Seu Advogado?" name="number" id="number">
                    <input class="form-control mb-4" type="text" placeholder="Qual O Telefone do Seu Advogado?" name="contact" id="contact">
                    <button class="btn w-100 btn-primary shadow">Solicitar Liberação</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>


    <script src="{{asset('assets/frontend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/main.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session()->has('message'))
                var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
                messageModal.show();
            @endif
        });
    </script>


  <!--WhatsApp !-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <a href="https://wa.me/5561920035973?text=Fale%com%20agente" style="position:fixed;width:60px;height:60px;bottom:40px;right:40px;background-color:#25d366;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 1px 1px 2px #888;
    z-index:1000;" target="_blank">
  <i style="margin-top:16px" class="fa fa-whatsapp"></i>
  </a>
</body>
</html>
