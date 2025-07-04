<div class="sidebar-content">
    <!--- Sidemenu -->
    <div id="sidebar-menu" class="slimscroll-menu">
        <ul class="metismenu" id="menu-bar">
            <li class="menu-title">Administração</li>

            <li>
                <a href="{{url('agency/dashboard')}}">
                    <i data-feather="home"  style="color: #00b81f;"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            <li class="menu-title">Gestão</li>
            <li>
                <a href="{{url('agency/clients')}}">
                    <i class="fa-solid fa-users"  style="color: #00b81f;"></i>
                    <span> Clientes </span>
                </a>
            </li>
            <li>
                <a href="{{route('agency.users.transactions')}}">
                    <i class="fa-solid fa-file-invoice"  style="color: #00b81f;"></i>
                    <span> Movimentações </span>
                </a>
            </li>
            <li>
                <a href="javascript: void(0);">
                    <i data-feather="dollar-sign"  style="color: #00b81f;"></i>
                    <span> Pagamentos </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="nav-second-level" aria-expanded="false">
                     <li>
                        <a href="{{route('mkey.index')}}">Minha Chave Pix</a>
                    </li>
                    <li>
                        <a href="{{route('agency.pix.index')}}">Área Pix</a>
                    </li>
                    <li>
                        <a href="{{route('agency.debit')}}">Débitar contas</a>
                    </li>
                    <li>
                        <a href="{{route('agency.transfer')}}">Tranferência</a>
                    </li>
                </ul>
            </li>
            <!--

            <li>
                <a href="{{route('agencylending.index')}}">
                    <i data-feather="file-plus"  style="color: #00b81f;"></i>
                    <span> Empréstimos </span>
                </a>
            </li>
            <li>
                <a href="{{route('agencycard.index')}}">
                    <i data-feather="credit-card"  style="color: #00b81f;"></i>
                    <span> Gerar Cartão </span>
                </a>
            </li>
            <li>
                <a href="{{route('score.index')}}">
                    <i data-feather="activity"  style="color: #00b81f;"></i>
                    <span> Score Interno </span>
                </a>
            </li>
-->
            <li>
                <a href="{{route('agency/link')}}">
                    <i class="fa-solid fa-link"  style="color: #00b81f;"></i>
                    <span> Abertura de Contas </span>
                </a>
            </li>
            </li>

            </li>

            <li class="menu-title">Comunicação</li>
            <li>
                <a href="{{route('agencycomunication.index')}}">
                    <i data-feather="message-square"  style="color: #00b81f;"></i>
                    <span> Mensagens </span>
                </a>
            </li>
            <li class="menu-title">Ajuda e Suporte</li>
            <li>
                <a href="{{route('agencyreply.index')}}">
                    <span class="badge badge-danger float-right"  ></span>
                    <i data-feather="help-circle"  style="color: #00b81f;"></i>
                    <span> Clientes </span>
                </a>
            </li>
            <li>
                <a href="{{route('agencyticket.index')}}">
                    <span class="badge badge-danger float-right"></span>
                    <i class="fa-solid fa-ticket"  style="color: #00b81f;"></i>
                    <span> Abrir Ticket </span>
                </a>
            </li>

    </div>
    </li>

</div>
<!-- End Sidebar -->

<div class="clearfix"></div>
</div>
<!-- Sidebar -left -->

</div>
