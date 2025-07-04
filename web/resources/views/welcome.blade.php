
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Polocal Bank</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/general-sans?styles=135312,135310,135313,135303">
    <link rel="stylesheet" href="{{asset ('assets/frontend/css/bootstrap/bootstrap.min.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="shuffle-for-bootstrap.png">
</head>
<body>
    <div class="">

      <section>
        <nav class="navbar navbar-expand-lg navbar-dark bg-black">
          <div class="container-fluid">
      <a class="navbar-brand" href="#"><img class="img-fluid" src="images/logo.svg" alt=""></a>
            <div class="collapse navbar-collapse position-absolute top-50 start-50 translate-middle">
              <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#pf">Conta PF</a></li>
                <li class="nav-item"><a class="nav-link" href="#pj">Conta PJ</a></li>
                <li class="nav-item"><a class="nav-link" href="#help">Ajuda</a></li>
                <li class="nav-item">
              </li></ul>
            </div>
            <div class="d-none d-lg-block"><a class="btn btn-outline-secondary-light" href="{{ route('login') }}">Entrar</a></div>
            <div class="d-lg-none">
              <button class="btn navbar-burger p-0">
                <svg class="text-primary" width="51" height="51" viewbox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect width="56" height="56" rx="28" fill="currentColor"></rect>
                  <path d="M37 32H19M37 24H19" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
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
                <li class="nav-item mb-8"><a class="nav-link text-dark" href="#pj">Abrir Conta PF</a></li>
                <li class="nav-item mb-8"><a class="nav-link text-dark" href="#pf">Abrir Conta PJ</a></li>
                <li class="nav-item mb-8"><a class="nav-link text-dark" href="#help">Ajuda</a></li>

              </ul>
            </div>
            <div>
      <a class="btn w-100 fw-medium" href="{{ route('login') }}">Login</a>
      <a class="btn w-100 btn-primary" href="{{ url('become/1') }}">Abrir Conta PF</a>
      </div>
          </nav>
        </div>
      </section>

      <div> <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="{{asset('assets/frontend/banner/banner1.png')}}" class="d-block w-100" alt="Banner 1">
        </div>
        <div class="carousel-item">
          <img src="{{asset('assets/frontend/banner/banner2.png')}}" class="d-block w-100" alt="Banner 2">
        </div>
        <div class="carousel-item">
          <img src="{{asset('assets/frontend/banner/banner3.png')}}" class="d-block w-100" alt="Banner 3">
        </div>
        <div class="carousel-item">
          <img src="{{asset('assets/frontend/banner/banner4.png')}}" class="d-block w-100" alt="Banner 4">
        </div>
        <div class="carousel-item">
          <img src="{{asset('assets/frontend/banner/banner5.png')}}" class="d-block w-100" alt="Banner 5">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

      <section class="py-12 py-sm-24 pb-md-32">
        <div class="container">
          <div class="mw-xs mw-sm-2xl mx-auto mw-lg-none">
            <h3 class="mw-md mw-lg-lg mb-24">Veja como é simples.</h3>
            <div class="row">
              <div class="col-12 col-sm-6 col-lg-3 mb-12 mb-lg-0 position-relative">
                <div class="d-none d-sm-block position-absolute top-0 start-0 ms-12 w-100 bg-light-dark bg-opacity-50 mt-6" style="height: 1px;"></div>
                <div class="mw-xs pe-10 overflow-hidden position-relative">
                  <div class="d-inline-flex px-4 bg-white rounded-pill">
                    <div class="d-flex mb-7 align-items-center justify-content-center fs-5 fw-bold bg-primary-light rounded-pill" style="width: 54px; height: 54px;"><span>1</span></div>
                  </div>
                  <p class="fs-6 fw-semibold">Crie sua conta digital.</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-3 mb-12 mb-lg-0 position-relative">
                <div class="d-none d-lg-block position-absolute top-0 start-0 ms-12 w-100 bg-light-dark bg-opacity-50 mt-6" style="height: 1px;"></div>
                <div class="mw-xs pe-10 overflow-hidden position-relative">
                  <!-- <div class="d-block d-sm-none d-lg-block position-absolute top-0 start-0 ms-20 w-100 bg-light-dark bg-opacity-50 mt-6" style="height: 1px;"></div>-->
                  <div class="d-inline-flex px-4 bg-white rounded-pill">
                    <div class="position-relative d-flex mb-7 align-items-center justify-content-center fs-5 text-white fw-bold bg-primary rounded-pill" style="width: 54px; height: 54px;">
      <img class="position-absolute top-0 start-0" src="{{asset ('assets/frontend/flaro-assets/images/how-it-works/gradient.svg') }}" alt=""><span class="position-relative">2</span>
      </div>
                  </div>
                  <p class="fs-6 fw-semibold">Acesse  produtos e serviços.</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-3 mb-12 mb-sm-0 position-relative">
                <div class="d-none d-sm-block position-absolute top-0 start-0 ms-12 w-100 bg-light-dark bg-opacity-50 mt-6" style="height: 1px;"></div>
                <div class="mw-xs pe-10 overflow-hidden position-relative">
                  <div class="d-inline-flex px-4 bg-white rounded-pill">
                    <div class="d-flex mb-7 align-items-center justify-content-center fs-5 fw-bold bg-primary-light rounded-pill" style="width: 54px; height: 54px;"><span>3</span></div>
                  </div>
                  <p class="fs-6 fw-semibold">Seja um credenciado.</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="mw-xs pe-10 overflow-hidden position-relative">
                  <div class="d-inline-flex px-4 bg-white rounded-pill">
                    <div class="d-flex mb-7 align-items-center justify-content-center fs-5 fw-bold bg-primary-light rounded-pill" style="width: 54px; height: 54px;"><span>4</span></div>
                  </div>
                  <p class="fs-6 fw-semibold">Tenha vantagens exclusivas.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="pt-24 pb-lg-24 pt-md-40 bg-black position-relative"  id="pf">
        <img class="position-absolute bottom-0 end-0" src="{{ asset('assets/frontend/flaro-assets/images/cta/gradient2.svg') }}" alt="">
        <img class="d-none d-lg-block position-absolute bottom-0 end-0 me-xl-64" src="{{ asset('assets/frontend/flaro-assets/images/cta/man.png') }}" alt="">
        <div class="container position-relative">
          <div class="mw-md mx-auto mb-16 mb-lg-0 mw-lg-none">
            <div class="row align-items-end">
              <div class="col-12 col-lg-6 col-xl-8 mb-16 mb-lg-0 " >
                <div class="mw-lg">
                  <h1 class="h3 text-white mb-6">Conta Pessoa Física</h1>
                  <p class="text-secondary-light mb-8">Abra agora mesmo sua conta e aproveite as vantagens que só o Polocal Bank pode te proporcionar</p>
                  <div class="d-flex flex-column flex-md-row">
      <a class="btn mb-4 mb-md-0 me-md-4 btn-light fw-medium" href="{{ url('become/1') }}">Abrir Conta PF</a>
      </div>
                </div>
              </div>
            </div>
          </div>
      <img class="d-lg-none d-block w-100 mw-sm ms-auto" src="{{ asset('assets/frontend/flaro-assets/images/cta/man.png') }}" alt="">
        </div>
      </section>

      <section class="py-12 py-sm-24 py-md-32" id="pj">
        <div class="container">
          <div class="mw-7xl mx-auto">
            <div class="row">
              <div class="col-12 col-lg-4 mb-8 mb-lg-0">
                <div class="d-flex mw-md mx-auto pb-10 px-10 bg-primary rounded-4" style="height: 533px;">
                  <div class="mt-auto">
                    <h4 class="text-white mb-4">Conta PJ</h4>
                    <p class="text-info-light mb-8">Sua empresa merece produtos e serviços diferenciados como empréstimos, pagamentos e muito mais.</p>
      <a class="btn btn-light" href="{{ url('business/1') }}">Abrir  Conta PJ</a>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-4 mb-8 mb-lg-0">
                <div class="d-flex mw-md mx-auto pb-10 px-10 bg-primary rounded-4 position-relative overflow-hidden" style="height: 533px;">
      <img class="position-absolute top-0 start-0 h-100 w-100" src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?crop=entropy&amp;cs=srgb&amp;fm=jpg&amp;ixid=M3wzMzIzMzB8MHwxfHNlYXJjaHwxfHxidXNzaW5lc3xlbnwwfHx8fDE3NDc5NDAyMjh8MA&amp;ixlib=rb-4.1.0&amp;q=85&amp;w=1920" alt="" style="object-fit: cover;"><img class="position-absolute bottom-0 start-0 w-100" src="flaro-assets/images/team/white-gradient-bottom.png" alt="">
                  <div class="position-relative mt-auto">


                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="d-flex mw-md mx-auto pb-10 px-10 bg-primary rounded-4 position-relative overflow-hidden" style="height: 533px;">
      <img class="position-absolute top-0 start-0 h-100 w-100" src="https://images.unsplash.com/photo-1553877522-43269d4ea984?crop=entropy&amp;cs=srgb&amp;fm=jpg&amp;ixid=M3wzMzIzMzB8MHwxfHNlYXJjaHw0Nnx8YnVzc2luZXN8ZW58MHx8fHwxNzQ3OTQwMjYyfDA&amp;ixlib=rb-4.1.0&amp;q=85&amp;w=1920" alt="" style="object-fit: cover;"><img class="position-absolute bottom-0 start-0 w-100" src="flaro-assets/images/team/white-gradient-bottom.png" alt="">
                  <div class="position-relative mt-auto">


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-12 py-sm-24 py-md-32 bg-info-light position-relative overflow-hidden" id="help">
        <div class="container position-relative">
          <div class="mw-6xl mx-auto">
            <div class="row align-items-center">
              <div class="col-12 col-lg-6 mb-16">
                <div class="mw-md mx-auto">
      <span class="fs-9 text-primary text-uppercase">AJUDA</span>
                  <h2 class="mt-6 mb-24">Precisa de ajuda?</h2>
                  <div class="mb-14">
      <span class="d-block mb-4 fs-9 text-secondary-light text-uppercase">Email</span>
                    <div><a class="btn btn-link fs-6 text-dark fw-semibold p-0" href="mailto:#">suporte@polocalbank.com.br</a></div>

                  </div>
                  <div>
      <span class="d-block mb-4 fs-9 text-secondary-light text-uppercase">Telefone</span>
      <span class="d-block fs-6 fw-semibold">(61) 4063-7218</span>
      </div>
                </div>
              </div>
              <div class="col-12 col-lg-6 position-relative">
      <img class="position-absolute top-0 start-0 w-100" src="{{asset ('assets/frontend/flaro-assets/images/contact/gradient2.svg') }}" alt="">
                <div class="mw-md mx-auto mw-lg-lg me-lg-0 pt-8 pb-12 px-6 px-md-10 bg-white bg-opacity-75 rounded-4 shadow-lg position-relative">
                  <form action="">
                    <label class="form-label fw-light">Seu Nome</label>
                    <div class="input-group mb-4">
      <span class="input-group-text bg-transparent">
                        <svg width="14" height="18" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.3327 4.83358C10.3327 6.67453 8.8403 8.16691 6.99935 8.16691C5.1584 8.16691 3.66602 6.67453 3.66602 4.83358C3.66602 2.99263 5.1584 1.50024 6.99935 1.50024C8.8403 1.50024 10.3327 2.99263 10.3327 4.83358Z" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M6.99935 10.6669C3.77769 10.6669 1.16602 13.2786 1.16602 16.5002H12.8327C12.8327 13.2786 10.221 10.6669 6.99935 10.6669Z" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span>
                      <input class="form-control" type="text" placeholder="First &amp; last name">
                    </div>
                    <label class="form-label fw-light">Whatsapp</label>
                    <div class="input-group mb-4">
      <span class="input-group-text bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="14px" viewbox="0 0 24 24" fill="none" class=""><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M21 10H14.6C14.0399 10 13.7599 10 13.546 9.89101C13.3578 9.79513 13.2049 9.64215 13.109 9.45399C13 9.24008 13 8.96005 13 8.4V5M10 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.07989 21 8.2V17.8C21 18.9201 21 19.4802 20.782 19.908C20.5903 20.2843 20.2843 20.5903 19.908 20.782C19.4802 21 18.9201 21 17.8 21H6.2C5.07989 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V8.2C3 7.07989 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.49359 5.01338 5.01165 5.00082 6 5.00005M10 5V4.6C10 4.03995 10 3.75992 9.89101 3.54601C9.79513 3.35785 9.64215 3.20487 9.45399 3.10899C9.24008 3 8.96005 3 8.4 3H7.6C7.03995 3 6.75992 3 6.54601 3.10899C6.35785 3.20487 6.20487 3.35785 6.10899 3.54601C6 3.75992 6 4.03995 6 4.6V5.00005M10 5V15.4C10 15.9601 10 16.2401 9.89101 16.454C9.79513 16.6422 9.64215 16.7951 9.45399 16.891C9.24008 17 8.96005 17 8.4 17H7.6C7.03995 17 6.75992 17 6.54601 16.891C6.35785 16.7951 6.20487 16.6422 6.10899 16.454C6 16.2401 6 15.9601 6 15.4V5.00005M14 14H14.01V13.99H14V14ZM14 17H14.01V17.01H14V17ZM17 17H17.01V17.01H17V17ZM17 14H17.01V14.01H17V14Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></span>
                      <input class="form-control" type="text" placeholder="Email address">
                    </div>
                    <label class="form-label fw-light">Como podemos te ajudar</label>
                    <div class="position-relative"><span class="position-absolute top-0 start-0 mt-2 ms-4">
                        <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M15.8898 2.11044L16.4201 1.58011L16.4201 1.58011L15.8898 2.11044ZM4.41667 16.5298V17.2798C4.61558 17.2798 4.80634 17.2008 4.947 17.0601L4.41667 16.5298ZM1.5 16.5298H0.75C0.75 16.944 1.08579 17.2798 1.5 17.2798L1.5 16.5298ZM1.5 13.5539L0.96967 13.0236C0.829018 13.1642 0.75 13.355 0.75 13.5539H1.5ZM13.4738 2.64077C13.9945 2.12007 14.8387 2.12007 15.3594 2.64077L16.4201 1.58011C15.3136 0.473623 13.5196 0.473623 12.4132 1.58011L13.4738 2.64077ZM15.3594 2.64077C15.8801 3.16147 15.8801 4.00569 15.3594 4.52639L16.4201 5.58705C17.5266 4.48056 17.5266 2.68659 16.4201 1.58011L15.3594 2.64077ZM15.3594 4.52639L3.88634 15.9995L4.947 17.0601L16.4201 5.58705L15.3594 4.52639ZM4.41667 15.7798H1.5V17.2798H4.41667V15.7798ZM12.4132 1.58011L0.96967 13.0236L2.03033 14.0843L13.4738 2.64077L12.4132 1.58011ZM0.75 13.5539V16.5298H2.25V13.5539H0.75ZM11.1632 3.89077L14.1094 6.83705L15.1701 5.77639L12.2238 2.83011L11.1632 3.89077Z" fill="#9CA3AF"></path>
                        </svg></span></div>
                    <textarea class="ps-12 mb-6 form-control" style="height: 187px; resize: none;" placeholder="Write message"></textarea><a class="btn btn-primary shadow" href="#">Enviar Mensagem</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="pt-24 pb-8 bg-black position-relative overflow-hidden"><img class="position-absolute bottom-0 start-50 translate-middle-x h-100" src="flaro-assets/images/footers/gradient.svg" alt="">
        <div class="container position-relative">
          <div class="row pb-20 mb-6 border-bottom border-secondary-dark">
            <div class="col-12 col-lg-5 mb-16 mb-lg-0">
      <a class="d-inline-block mb-8" href="#"><img src="images/logo.svg" alt=""></a>


            </div>
            <div class="col-12 col-lg-7">
              <div class="row">
                <div class="col-6 col-md-4 mb-16 mb-md-0">
                  <h6 class="fs-9 mb-6 text-uppercase text-secondary">CONTAS</h6>
                  <ul class="list-unstyled">
                    <li class="mb-4"><a class="btn btn-link p-0 text-white" href="#pf">Conta PF</a></li>
                    <li class="mb-4"><a class="btn btn-link p-0 text-white" href="#pj">Conta PJ</a></li>
                    <li class="mb-4">
                    </li><li>
                  </li></ul>
                </div>
                <div class="col-6 col-md-4 mb-16 mb-md-0">
                  <h6 class="fs-9 mb-6 text-uppercase text-secondary">ACESSO</h6>
                  <ul class="list-unstyled">
                    <li class="mb-4"><a class="btn btn-link p-0 text-white" href="{{ route('login') }}">Já tenho conta</a></li>

                    <li class="mb-4">
                    </li><li class="mb-4">
                    </li><li>
                  </li></ul>
                </div>
                <div class="col-6 col-md-4">
                  <h6 class="fs-9 mb-6 text-uppercase text-secondary">AJUDA</h6>
                  <ul class="list-unstyled">
                    <li class="mb-4"><a class="btn btn-link p-0 text-white" href="#help">Preciso de Ajuda</a></li>
                    <li class="mb-4">
                    </li><li class="mb-4">
                    </li><li>
                  </li></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <p class="fs-9 text-secondary-light mb-0">Copyright © {{ date('Y') }} PolocalBank. Desenvolvido Por<a href="https://virtualbrain.com.br/" target="blank"> VirtualBrain</a></p>
          </div>
        </div>
      </section>
    </div>
    <script src="{{asset ('assets/frontend/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/main.js') }}"></script>
</body>
</html>
