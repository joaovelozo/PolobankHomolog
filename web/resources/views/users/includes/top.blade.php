  <!-- Topbar Start -->
  <div class="navbar navbar-expand flex-column flex-md-row navbar-custom" style="background-color: black !important;">
    <div class="container-fluid">
        <!-- LOGO -->
        <a href="{{route('dashboard')}}" class="navbar-brand mr-0 mr-md-2 logo">
            <span class="logo-lg">
                <img src="{{asset('assets/backend/logo.png')}}" alt="" height="90" />
                          </span>
                          <span class="d-inline h5 ml-1 text-logo"> </span>
            <span class="logo-sm">
                <img src="{{asset('assets/backend/logo.png')}}" alt="" height="90">
            </span>
        </a>

        <ul class="navbar-nav bd-navbar-nav flex-row list-unstyled menu-left mb-0">
            <li class="">
                <button class="button-menu-mobile open-left disable-btn">
                    <i data-feather="menu" class="menu-icon"></i>
                    <i data-feather="x" class="close-icon"></i>
                </button>
            </li>
        </ul>

        <ul class="navbar-nav flex-row ml-auto d-flex list-unstyled topnav-menu float-right mb-0">
            <li class="d-none d-sm-block">
                <div class="app-search">
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Localizar...">
                            <span data-feather="search"></span>
                        </div>
                    </form>
                </div>
            </li>


            @php
            $notifications = \App\Models\Lending::where('user_id', auth()->id())->get();
            $counter = $notifications->count();
            @endphp

            <li class="dropdown notification-list" data-toggle="tooltip" data-placement="left"
                title="{{$counter}} mensagens não lidas">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                    aria-expanded="false">
                    <i data-feather="bell"></i>
                    <span class="noti-icon-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-lg">


                    <!-- item-->
                    <div class="dropdown-item noti-title border-bottom">
                        <h5 class="m-0 font-size-16">
                            <span class="float-right">
                                <a href="" class="text-dark">
                                    <small>Limpar Tudo</small>
                                </a>
                            </span>Mensagens
                        </h5>
                    </div>

                    <div class="slimscroll noti-scroll">

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item border-bottom">
                            <div class="notify-icon bg-primary"><i class="uil uil-user-plus"></i></div>
                            <p class="notify-details">New user registered.<small class="text-muted">5 hours ago</small>
                            </p>
                        </a>

                        <!-- item-->
                        @foreach($notifications as $item)
                        <a href="javascript:void(0);" class="dropdown-item notify-item border-bottom">

                            <p class="notify-details">{{$item->username}}</p>
                            <p class="text-muted mb-0 user-msg">
                                <small>{{$item->response}}</small>
                            </p>
                            <p class="notify-details">New user registered.<small class="text-muted">5 hours ago</small>
                        </a>
                        @endforeach



                    <!-- All-->
                    <a href="{{route('lending.index')}}"
                        class="dropdown-item text-center text-primary notify-item notify-all border-top">
                        Ver Respostas
                        <i class="fi-arrow-right"></i>
                    </a>

                </div>
            </li>



            <li class="dropdown notification-list align-self-center profile-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <div class="media user-profile ">
                        <img src="assets/images/users/avatar-7.jpg" alt="user-image" class="rounded-circle align-self-center" />
                        <div class="media-body text-left">
                            <h6 class="pro-user-name ml-2 my-0">
                                <span>Shreyu N</span>
                                <span class="pro-user-desc text-muted d-block mt-1">Administrator </span>
                            </h6>
                        </div>
                        <span data-feather="chevron-down" class="ml-2 align-self-center"></span>
                    </div>
                </a>
                <div class="dropdown-menu profile-dropdown-items dropdown-menu-right">
                    <a href="pages-profile.html" class="dropdown-item notify-item">
                        <i data-feather="user" class="icon-dual icon-xs mr-2"></i>
                        <span>My Account</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-feather="settings" class="icon-dual icon-xs mr-2"></i>
                        <span>Settings</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-feather="help-circle" class="icon-dual icon-xs mr-2"></i>
                        <span>Support</span>
                    </a>

                    <a href="pages-lock-screen.html" class="dropdown-item notify-item">
                        <i data-feather="lock" class="icon-dual icon-xs mr-2"></i>
                        <span>Lock Screen</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-feather="log-out" class="icon-dual icon-xs mr-2"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>

</div>
