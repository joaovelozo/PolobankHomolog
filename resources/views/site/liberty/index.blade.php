
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
                            <li class="nav-item"><a class="nav-link" href="{{ route('site.ralbank') }}"  style="color: white;">RAL BANK</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('site.liberty') }}"  style="color: white;">LIBERTY BANK</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('site.contact') }}"  style="color: white;">Contato</a></li>

                            <li class="nav-item">
                                <a class="btn btn-success" href="{{ route('site.update') }}">Atualizar Dados</a>
                              </li>

                        </ul>
                    </div>
                    <div class="d-none d-lg-block">
                        <a class="btn btn-danger" href="#plan">Abrir Conta</a>

                       <a class="btn btn-primary" href="{{ route('login') }}">Acessar Conta</a>

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
          <li class="nav-item mb-8"><a class="nav-link text-dark" href="{{route('site.ralbank')}}">RAL BANK</a></li>
          <li class="nav-item mb-8"><a class="nav-link text-dark" href="{{route('site.liberty')}}">LIBERTY BANK</a></li>
          <li class="nav-item mb-8"><a class="nav-link text-dark" href="{{route('site.contact')}}">Contato</a></li>
          <li class="nav-item">
              <a class="btn btn-success" href="{{ route('site.update') }}">Atualizar Dados</a></li>
        </ul>
      </div>
      <div>
<a class="btn w-100 btn-primary fw-medium mb-3" href="{{route('login')}}">Acessar Conta</a>

<a class="btn w-100 btn-danger" href="/.#plan">Abra Sua Conta</a>
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

      <section class="pt-24 pb-lg-24 pt-md-40 bg-black position-relative">
        <img class="position-absolute bottom-0 end-0" src="{{asset('assets/frontend/flaro-assets/images/cta/gradient2.svg')}}" alt=""><img class="d-none d-lg-block position-absolute bottom-0 end-0 me-xl-64" src="{{asset('assets/frontend/flaro-assets/images/cta/man.png')}}" alt="">
        <div class="container position-relative">
          <div class="mw-md mx-auto mb-16 mb-lg-0 mw-lg-none">
            <div class="row align-items-end">
              <div class="col-12 col-lg-6 col-xl-8 mb-16 mb-lg-0">
                <div class="mw-lg">
                  <h1 class="h3 text-white mb-6">Sempre é bom ter um parceiro para te ajudar a ir mais longe.</h1>
                  <p class="text-secondary-light mb-8">O Polocal Bank é parceiro oficial LIBERTY BANK.</p>
                  <div class="d-flex flex-column flex-md-row">

      </div>
                </div>
              </div>
            </div>
          </div>
      <img class="d-lg-none d-block w-100 mw-sm ms-auto" src="{{asset('assets/frontend/flaro-assets/images/cta/man.png')}}" alt="">
        </div>
      </section>

      <section class="py-12 py-sm-24 bg-info-light">
        <div class="container">
          <div class="mw-xs px-md-0 mw-md-md mw-lg-2xl mx-auto mb-14">
            <div class="row align-items-center justify-content-center">
              <div class="col-4 col-lg-2"></div>
              <div class="col-4 col-lg-2"></div>
              <div class="col-4 col-lg-2"></div>
              <div class="col-4 col-lg-2"><img class="img-fluid d-block mx-auto" src="{{asset('assets/frontend/images/liberty.jpg')}}" alt=""></div>
              <div class="col-4 col-lg-2"></div>
              <div class="col-4 col-lg-2"></div>
            </div>
          </div>
          <div class="mw-2xl mx-auto text-center">
            <h2 class="mb-6">Conheça nosso parceiro e apoiador oficial.</h2>
            <p class="mw-lg mx-auto fs-7 text-secondary mb-0">O LIBERTY BANK Bank é nosso parceiro estratégico e de negócios, comprometido em trazer os melhores produtos e serviços financeiros para você. Acompanhe as novidades que nosso parceiro traz periodicamente e faça parte dessa parceria revolucionária.</p>
          </div>
        </div>
      </section>

      <section class="py-12 py-sm-24 pt-md-64 pb-md-16 position-relative overflow-hidden">
        <img class="position-absolute top-0 start-0 w-100 h-100" src="{{asset('assets/frontend/flaro-assets/images/newsletter/bg.jpeg')}}" alt="">
        <div class="container position-relative">
          <div class="mw-lg mw-lg-7xl mx-auto py-16 px-8 px-lg-20 rounded-4 bg-black bg-opacity-75 position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="backdrop-filter: blur(18.5px);"></div>
            <div class="row align-items-end position-relative">
              <div class="col-12 col-lg-6 mb-10 mb-lg-0">
                <div class="mw-sm mw-lg-none mx-auto">
                  <h3 class="mw-md text-white mb-14">Acompanhe as novidades dessa parceria de Sucesso.</h3>
                  <ul class="list-unstyled mb-0">
                    <li class="mb-4">
                      <svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="10" cy="10" r="10" fill="#4F46E5"></circle>
                        <path d="M5.91699 10.5833L8.25033 12.9166L14.0837 7.08331" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg><span class="ms-2 text-white fw-semibold">Receba Emails com Novidades</span>
                    </li>
                    <li class="mb-4">
                      <svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="10" cy="10" r="10" fill="#4F46E5"></circle>
                        <path d="M5.91699 10.5833L8.25033 12.9166L14.0837 7.08331" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg><span class="ms-2 text-white fw-semibold">Receba Novidades Financeiras</span>
                    </li>
                    <li class="mb-4">
                      <svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="10" cy="10" r="10" fill="#4F46E5"></circle>
                        <path d="M5.91699 10.5833L8.25033 12.9166L14.0837 7.08331" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg><span class="ms-2 text-white fw-semibold">Receba Novidades de Negócios</span>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="mw-sm mx-auto me-lg-0">
                  <h6 class="fs-6 text-white mb-6">Faça como ({{$new}}),  que acompanham a revolução</h6>
                  <form action="{{route('news.store')}}" method="POST">
                    @csrf
                    <input class="form-control mb-4" type="email" name="email" placeholder="Digite Seu Melhor Email">
                    <button class="btn w-100 btn-primary" type="submit">Receber Novidades</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-16 position-relative overflow-hidden bg-dark">

        <div class="container position-relative">
            <div class="d-none d-md-inline-flex flex-wrap position-absolute top-50 start-50 translate-middle justify-content-center"
                style="z-index: 10;">

                <div class="p-4"><a class="btn p-0 btn-link text-secondary"
                        href="{{ route('site.ralbank') }} " style="color: white;">RAL BANK</a></div>
                <div class="p-4"><a class="btn p-0 btn-link text-secondary"
                        href="{{ route('site.liberty') }}" style="color: white;">LIBERTY BANK</a></div>
                <div class="p-4"><a class="btn p-0 btn-link text-secondary" href="/.#plan" style="color: white;">Abra
                        Sua Conta</a></div>
                <div class="p-4"><a class="btn p-0 btn-link text-secondary"
                        href="{{ route('site.contact') }}" style="color: white;">Contato</a></div>
                <br>
                <hr>
                <p class="text-center text-white"><b>O Polocal Bank é um "Correspondente Bancário" STARK SeD S.A, que é
                        registrada no BACEN sob o número - <a
                            href="https://www.bcb.gov.br/content/estabilidadefinanceira/str1/ParticipantesSTR.pdf"
                            target="_blank"> 462.
                        </a></b></p>
            </div>

            <div class="position-relative row justify-content-between align-items-center">
                <div class="col-auto"><a class="d-inline-block" href="{{ url('/') }}">
                        <img src="{{ asset('assets/frontend/images/logo-png-1722002115167.webp') }}"
                            alt=""></a></div>
                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <a class="btn p-0 btn-outline-secondary-dark d-inline-flex align-items-center justify-content-center me-2 rounded-pill"
                            href="#" style="width: 35px; height: 35px;">
                            <svg width="8" height="14" viewbox="0 0 8 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.55736 5.2L5.55736 3.88C5.55736 3.308 5.69631 3 6.66894 3H7.87315V0.800003L6.02052 0.800003C3.70473 0.800003 2.77841 2.252 2.77841 3.88V5.2H0.925781L0.925781 7.4H2.77841L2.77841 14H5.55736L5.55736 7.4H7.59526L7.87315 5.2H5.55736Z"
                                    fill="#27272A"></path>
                            </svg></a><a
                            class="btn p-0 btn-outline-secondary-dark d-inline-flex align-items-center justify-content-center me-2 rounded-pill"
                            href="#" style="width: 35px; height: 35px;">
                            <svg width="14" height="11" viewbox="0 0 14 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.6655 1.39641C13.1901 1.60149 12.6728 1.74907 12.1399 1.80656C12.6931 1.47788 13.1074 0.958619 13.3051 0.346204C12.7859 0.655036 12.2172 0.871595 11.6241 0.986274C11.3762 0.721276 11.0764 0.510168 10.7434 0.366102C10.4104 0.222036 10.0512 0.1481 9.68836 0.148902C8.22024 0.148902 7.03953 1.33893 7.03953 2.79928C7.03953 3.00436 7.06439 3.20943 7.10478 3.40673C4.90649 3.29177 2.94589 2.24155 1.64246 0.633614C1.40495 1.03927 1.2805 1.50117 1.28203 1.97123C1.28203 2.89094 1.74965 3.70191 2.46274 4.17885C2.0425 4.1623 1.63211 4.0468 1.26494 3.84173V3.87435C1.26494 5.16226 2.17533 6.22956 3.38866 6.47502C3.16084 6.5342 2.92649 6.56447 2.69111 6.56513C2.51866 6.56513 2.35554 6.54804 2.19086 6.52474C2.52643 7.57495 3.50362 8.33775 4.66724 8.3626C3.75685 9.07569 2.61654 9.49515 1.37835 9.49515C1.15619 9.49515 0.951119 9.48738 0.738281 9.46253C1.91278 10.216 3.30632 10.651 4.80706 10.651C9.67904 10.651 12.345 6.61484 12.345 3.11155C12.345 2.99659 12.345 2.88162 12.3372 2.76666C12.853 2.38914 13.3051 1.92152 13.6655 1.39641Z"
                                    fill="#27272A"></path>
                            </svg></a><a
                            class="btn p-0 btn-outline-secondary-dark d-inline-flex align-items-center justify-content-center rounded-pill"
                            href="#" style="width: 35px; height: 35px;">
                            <svg width="16" height="15" viewbox="0 0 16 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.00094 0.360001C6.09046 0.360001 5.85022 0.368801 5.09958 0.402241C4.34894 0.437441 3.83766 0.555361 3.38974 0.729601C2.9199 0.906321 2.49433 1.18353 2.14278 1.54184C1.78468 1.89357 1.50751 2.31909 1.33054 2.7888C1.1563 3.23584 1.0375 3.748 1.00318 4.496C0.969738 5.2484 0.960937 5.48776 0.960937 7.40088C0.960937 9.31224 0.969738 9.5516 1.00318 10.3022C1.03838 11.052 1.1563 11.5633 1.33054 12.0112C1.51094 12.4741 1.75118 12.8666 2.14278 13.2582C2.5335 13.6498 2.92598 13.8909 3.38886 14.0704C3.83766 14.2446 4.34806 14.3634 5.09782 14.3978C5.84934 14.4312 6.0887 14.44 8.00094 14.44C9.91318 14.44 10.1517 14.4312 10.9032 14.3978C11.6521 14.3626 12.1651 14.2446 12.613 14.0704C13.0826 13.8936 13.5078 13.6164 13.8591 13.2582C14.2507 12.8666 14.4909 12.4741 14.6713 12.0112C14.8447 11.5633 14.9635 11.052 14.9987 10.3022C15.0321 9.5516 15.0409 9.31224 15.0409 7.4C15.0409 5.48776 15.0321 5.2484 14.9987 4.49688C14.9635 3.748 14.8447 3.23584 14.6713 2.7888C14.4944 2.31908 14.2172 1.89356 13.8591 1.54184C13.5077 1.1834 13.0821 0.906169 12.6121 0.729601C12.1633 0.555361 11.6512 0.436561 10.9023 0.402241C10.1508 0.368801 9.9123 0.360001 7.99918 0.360001H8.00182H8.00094ZM7.36998 1.62896H8.00182C9.8815 1.62896 10.1041 1.63512 10.846 1.66944C11.5324 1.70024 11.9055 1.81552 12.1537 1.91144C12.4819 2.03904 12.7169 2.19216 12.9633 2.43856C13.2097 2.68496 13.3619 2.91904 13.4895 3.24816C13.5863 3.49544 13.7007 3.86856 13.7315 4.55496C13.7658 5.2968 13.7729 5.51944 13.7729 7.39824C13.7729 9.27704 13.7658 9.50056 13.7315 10.2424C13.7007 10.9288 13.5854 11.301 13.4895 11.5492C13.3766 11.8549 13.1965 12.1313 12.9624 12.3579C12.716 12.6043 12.4819 12.7566 12.1528 12.8842C11.9064 12.981 11.5333 13.0954 10.846 13.127C10.1041 13.1605 9.8815 13.1684 8.00182 13.1684C6.12214 13.1684 5.89862 13.1605 5.15678 13.127C4.47038 13.0954 4.09814 12.981 3.84998 12.8842C3.54418 12.7715 3.26753 12.5916 3.04038 12.3579C2.80608 12.1309 2.62565 11.8543 2.51238 11.5483C2.41646 11.301 2.30118 10.9279 2.27038 10.2415C2.23694 9.49968 2.2299 9.27704 2.2299 7.39648C2.2299 5.5168 2.23694 5.29504 2.27038 4.5532C2.30206 3.8668 2.41646 3.49368 2.51326 3.24552C2.64086 2.91728 2.79398 2.68232 3.04038 2.43592C3.28678 2.18952 3.52086 2.03728 3.84998 1.90968C4.09814 1.81288 4.47038 1.69848 5.15678 1.6668C5.80622 1.63688 6.0579 1.62808 7.36998 1.6272V1.62896ZM11.7594 2.7976C11.6485 2.7976 11.5386 2.81945 11.4361 2.86191C11.3336 2.90436 11.2405 2.96659 11.1621 3.04504C11.0836 3.12348 11.0214 3.21661 10.9789 3.31911C10.9365 3.42161 10.9146 3.53146 10.9146 3.6424C10.9146 3.75334 10.9365 3.8632 10.9789 3.96569C11.0214 4.06819 11.0836 4.16132 11.1621 4.23976C11.2405 4.31821 11.3336 4.38044 11.4361 4.42289C11.5386 4.46535 11.6485 4.4872 11.7594 4.4872C11.9835 4.4872 12.1984 4.3982 12.3568 4.23976C12.5152 4.08133 12.6042 3.86646 12.6042 3.6424C12.6042 3.41835 12.5152 3.20347 12.3568 3.04504C12.1984 2.88661 11.9835 2.7976 11.7594 2.7976ZM8.00182 3.78496C7.52228 3.77748 7.04604 3.86547 6.60084 4.0438C6.15563 4.22214 5.75035 4.48726 5.40859 4.82373C5.06683 5.1602 4.79542 5.5613 4.61016 6.00367C4.4249 6.44604 4.32949 6.92084 4.32949 7.40044C4.32949 7.88004 4.4249 8.35484 4.61016 8.79721C4.79542 9.23958 5.06683 9.64068 5.40859 9.97715C5.75035 10.3136 6.15563 10.5787 6.60084 10.7571C7.04604 10.9354 7.52228 11.0234 8.00182 11.0159C8.95093 11.0011 9.85616 10.6137 10.5221 9.93726C11.1881 9.26084 11.5613 8.34967 11.5613 7.40044C11.5613 6.45121 11.1881 5.54004 10.5221 4.86362C9.85616 4.1872 8.95093 3.79977 8.00182 3.78496ZM8.00182 5.05304C8.62427 5.05304 9.22123 5.30031 9.66137 5.74045C10.1015 6.18059 10.3488 6.77755 10.3488 7.4C10.3488 8.02245 10.1015 8.61941 9.66137 9.05955C9.22123 9.49969 8.62427 9.74696 8.00182 9.74696C7.37937 9.74696 6.78241 9.49969 6.34227 9.05955C5.90213 8.61941 5.65486 8.02245 5.65486 7.4C5.65486 6.77755 5.90213 6.18059 6.34227 5.74045C6.78241 5.30031 7.37937 5.05304 8.00182 5.05304Z"
                                    fill="#27272A"></path>
                            </svg></a>
                    </div>
                </div>
                <div class="col-12 d-md-none mt-10">
                    <div class="d-flex flex-wrap mb-n4 justify-content-center">

                        <div class="p-4"><a class="btn p-0 btn-link text-secondary"
                                href="{{ route('site.ralbank') }}">RAL BANK</a></div>
                        <div class="p-4"><a class="btn p-0 btn-link text-secondary"
                                href="{{ route('site.liberty') }}">LIBERTY BANK</a></div>
                        <div class="p-4"><a class="btn p-0 btn-link text-secondary"
                                href="{{ route('site.contact') }}">Contato</a></div>
                        <div class="p-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    <script src="{{asset('assets/frontend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/main.js')}}"></script>
    <!--WhatsApp !-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <a href="https://wa.me/5561920035973?text=Fale%com%20agente" style="position:fixed;width:60px;height:60px;bottom:40px;right:40px;background-color:#25d366;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 1px 1px 2px #888;
      z-index:1000;" target="_blank">
    <i style="margin-top:16px" class="fa fa-whatsapp"></i>
    </a>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session()->has('message'))
                var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
                messageModal.show();
            @endif
        });
    </script>

</body>
</html>
