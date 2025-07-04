
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" />
        <title>Polocal Bank</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/fav/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/fav/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/fav/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('assets/fav/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('assets/fav/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

        <!-- plugins -->
        <link href="{{asset('assets/backend/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- Summernote css -->
        <link href="{{asset('assets/backend/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" />

        <!-- App css -->
        <link href="{{asset('assets/backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/backend/css/app.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('assets/backend/libs/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/backend/libs/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/backend/libs/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/backend/libs/datatables/select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="{{asset('assets/backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @trixassets



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
        <!-- Begin page -->
        <div id="wrapper">

          @include('admin.includes.top')
            <!-- end Topbar -->

           @include('admin.includes.profile')
                @include('admin.includes.nav')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            @yield('content')


                <!-- Footer Start -->
          @include('admin.includes.footer')
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div class="rightbar-title">
                <a href="javascript:void(0);" class="right-bar-toggle float-right">
                    <i data-feather="x-circle"></i>
                </a>
                <h5 class="m-0">Customization</h5>
            </div>

            <div class="slimscroll-menu">

                <h5 class="font-size-16 pl-3 mt-4">Choose Variation</h5>
                <div class="p-3">
                    <h6>Default</h6>
                    <a href="index.html"><img src="assets/images/layouts/vertical.jpg" alt="vertical" class="img-thumbnail demo-img" /></a>
                </div>
                <div class="px-3 py-1">
                    <h6>Top Nav</h6>
                    <a href="layouts-horizontal.html"><img src="assets/images/layouts/horizontal.jpg" alt="horizontal" class="img-thumbnail demo-img" /></a>
                </div>
                <div class="px-3 py-1">
                    <h6>Dark Side Nav</h6>
                    <a href="layouts-dark-sidebar.html"><img src="assets/images/layouts/vertical-dark-sidebar.jpg" alt="dark sidenav" class="img-thumbnail demo-img" /></a>
                </div>
                <div class="px-3 py-1">
                    <h6>Condensed Side Nav</h6>
                    <a href="layouts-dark-sidebar.html"><img src="assets/images/layouts/vertical-condensed.jpg" alt="condensed" class="img-thumbnail demo-img" /></a>
                </div>
                <div class="px-3 py-1">
                    <h6>Fixed Width (Boxed)</h6>
                    <a href="layouts-boxed.html"><img src="assets/images/layouts/boxed.jpg" alt="boxed"
                            class="img-thumbnail demo-img" /></a>
                </div>
            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="{{asset('assets/backend/js/vendor.min.js')}}"></script>

        <!-- optional plugins -->
        <script src="{{asset('assets/backend/libs/moment/moment.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/apexcharts/apexcharts.min.js')}}"></script>
        <script src="{{asset('assets/backend/libs/flatpickr/flatpickr.min.js')}}"></script>

        <!-- page js -->
        <script src="{{asset('assets/backend/js/pages/dashboard.init.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('assets/backend/js/app.min.js')}}"></script>

          <!-- datatable js -->
          <script src="{{asset('assets/backend/libs/datatables/jquery.dataTables.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/dataTables.bootstrap4.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/dataTables.responsive.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/responsive.bootstrap4.min.js')}}"></script>

          <script src="{{asset('assets/backend/libs/datatables/dataTables.buttons.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/buttons.bootstrap4.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/buttons.html5.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/buttons.flash.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/buttons.print.min.js')}}"></script>

          <script src="{{asset('assets/backend/libs/datatables/dataTables.keyTable.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/datatables/dataTables.select.min.js')}}"></script>

          <!-- Datatables init -->
          <script src="{{asset('assets/backend/js/pages/datatables.init.js')}}"></script>

          <script src="{{asset('assets/backend/libs/select2/select2.min.js')}}"></script>
          <script src="{{asset('assets/backend/libs/multiselect/jquery.multi-select.js')}}"></script>

          <script src="{{asset('assets/backend/js/pages/form-advanced.init.js')}}"></script>
          <script src="{{asset('assets/backend/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
        <script src="{{asset('assets/backend/js/jquery.mask.min.js')}}"></script>

          <!--Summernote js-->
          <script src="{{asset('assets/backend/libs/summernote/summernote-bs4.min.js')}}"></script>

          <!-- Init js -->
          <script src="{{asset('assets/backend/js/pages/form-editor.init.js')}}"></script>

          <script>
            // Definindo o tempo de inatividade (1 minuto = 60000 milissegundos)
            let idleTime = 0;

            // Função para fazer logout automaticamente
            function autoLogout() {
                fetch("{{ route('logout') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.href = "{{ route('login') }}"; // Redireciona para a página de login após o logout
                    }
                });
            }

            // Incrementa o tempo de inatividade a cada minuto
            function timerIncrement() {
                idleTime++;
                if (idleTime > 0) { // 1 minuto
                    autoLogout();
                }
            }

            // Reseta o tempo de inatividade sempre que houver interação do usuário
            function resetTimer() {
                idleTime = 0;
            }

            // Eventos que detectam interação do usuário (movimentos, cliques e teclas)
            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onclick = resetTimer;
            document.onscroll = resetTimer;

            // Verifica inatividade a cada 1 minuto (60000 milissegundos)
            setInterval(timerIncrement, 300000);
        </script>
          @yield('scripts')


        </body>
</html>
