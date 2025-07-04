@php
    $lds = App\Models\Lending::all();
    $ldscount = count($lds);

    $tks = App\Models\Ticket::all();
    $tkscount = count($tks);
@endphp
<style>
    .custom-icon {
    color: #00b81f; /* Cor personalizada */
}

    </style>

<div class="sidebar-content">
  <!--- Sidemenu -->
  <div id="sidebar-menu" class="slimscroll-menu">
      <ul class="metismenu" id="menu-bar">
          <li class="menu-title">Administração</li>

          <li>
              <a href="{{route('admin.admin.dashboard')}}">
                <i  data-feather="home" style="color: #00b81f;"></i>

                  <span> Dashboard </span>
              </a>
          </li>
          <li class="menu-title">Gestão</li>

          <li>
              <a href="{{route('manager.index')}}">
                <i data-feather="briefcase" style="color: #00b81f;"></i>
                  <span> Gerentes </span>
              </a>
          </li>

          <li>
            <a href="{{route('agency.index')}}">
                <i data-feather="credit-card" style="color: #00b81f;"></i>
                <span> Agências </span>
            </a>
        </li>



        <li>
            <a href="{{route('docs.index')}}">
                <i data-feather="file" style="color: #00b81f;"></i>
                <span> Documentos </span>
            </a>
        </li>
        <li>
            <a href="{{route('adpayment.index')}}">
                <i data-feather="dollar-sign" style="color: #00b81f;"></i>
                <span>Pagamentos</span>
            </a>
        </li>

        <li>
            <a href="{{route('transactions.index')}}">
                <i data-feather="loader" style="color: #00b81f;"></i>
                <span> Movimentações</span>
            </a>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i data-feather="percent" style="color: #00b81f;"></i>
                <span> Débito em Conta </span>
                <span class="menu-arrow"></span>
            </a>

            <ul class="nav-second-level" aria-expanded="false">
                <li>

                    <a href="{{route('types.index')}}">Cadastrar Tipo</a>
                </li>
                <li>
                    <a href="{{route('admin.debit')}}">Debitar Saldo</a>
                </li>


            </ul>
            <li>


        <li>
            <a href="{{route('admin.add-balance')}}">
                <i data-feather="feather" style="color: #00b81f;"></i>
                <span> Inserir Saldo </span>
            </a>
        </li>

        <li>
            <a href="{{route('pinadmin.index')}}">
                <i data-feather="lock"style="color: #00b81f;"  ></i>
                <span> Senha de Transação </span>
            </a>
        </li>
          <li>
              <a href="javascript: void(0);">
                  <i data-feather="dollar-sign" style="color: #00b81f;"></i>
                  <span> Empréstimos </span>
                  <span class="menu-arrow"></span>
              </a>

              <ul class="nav-second-level" aria-expanded="false">
                  <li>
                    <span class="badge badge-success float-right">{{$ldscount}}</span>
                      <a href="{{route('admin.lending')}}">Solicitações</a>
                  </li>
                  <li>
                      <a href="{{route('loan.index')}}">Cadastrar</a>
                  </li>
                  <li>
                    <a href="{{route('adminscore.index')}}">Score Interno</a>
                </li>

              </ul>

          </li>

          </li>
        <li>
            <a href="{{route('service.index')}}">
                <i class="fa-solid fa-basket-shopping" style="color: #00b81f;"></i>
                <span> Produtos e Serviços </span>
            </a>
        </li>
          <li class="menu-title">Relatórios</li>
          <li>
              <a href="javascript: void(0);">
                  <i data-feather="file-text" style="color: #00b81f;"></i>
                  <span> STA </span>
                  <span class="menu-arrow"></span>
              </a>
              <ul class="nav-second-level" aria-expanded="false">
                  <li>
                      <a href="{{route('admin.generate-sta')}}">Gerar</a>
                  </li>
              </ul>
          </li>
          <li>
            <a href="{{route('actions.user')}}">
                <i class="fa-solid fa-person-walking" style="color: #00b81f;"></i>
                <span> Atividades Usuários </span>
            </a>
        </li>


          </li>

          <li class="menu-title">Contato Via Site</li>
          <li>
            <a href="{{route('contact.index')}}">
                <i data-feather="mail" style="color: #00b81f;"></i>
                <span>Formulário de Contato</span>
            </a>
        </li>
        <li>
            <a href="{{route('news.index')}}">
                <i data-feather="rss" style="color: #00b81f;"></i>
                <span>News Letter</span>
            </a>
        </li>

          </li>

          <li class="menu-title">Comunicação</li>
          <li>
            <a href="{{route('message.index')}}">
                <i data-feather="message-circle" style="color: #00b81f;"></i>
                <span>Para Clientes </span>
            </a>
        </li>
        <li>
            <a href="{{route('agencycom.index')}}">
                <i data-feather="message-square" style="color: #00b81f;" ></i>
                <span> Para Agências </span>
            </a>
        </li>

          </li>

          <li class="menu-title">Suporte</li>
          <li>
            <a href="{{route('ticketsadmin.index')}}">
                <span class="badge badge-success float-right">{{$tkscount}}</span>
                <i data-feather="help-circle" style="color: #00b81f;" ></i>
                <span>Chamados </span>
            </a>
        </li>

        <li class="menu-title">Juridico</li>
          <li>
            <a href="{{route('contracts.index')}}">

                <i class="fa-solid fa-gavel" style="color: #00b81f;"></i>

                <span>Contratos </span>
            </a>
        </li>


          </li>

          <li class="menu-title">Gestão de Acessos</li>
          <li>
            <a href="{{route('permission.index')}}">
                <i data-feather="shield" style="color: #00b81f;"></i>
                <span> Regras de Acessos</span>
            </a>
        </li>


        <li>
          <a href="{{route('role.index')}}">
              <i data-feather="unlock" style="color: #00b81f;"></i>
              <span> Perfil de Acesso </span>
          </a>
      </li>

      <li>
        <a href="{{route('role.permission.index')}}">
            <i data-feather="link-2" style="color: #00b81f;"></i>
            <span>Perfil e Permissões</span>
        </a>
    </li>

          </li>

          <li class="menu-title">Administradores</li>
          <li>
            <a href="{{route('all.admin')}}">
                <i data-feather="user-check" style="color: #00b81f;"></i>
                <span> Administradores</span>
            </a>
        </li>


  </div>
  <!-- End Sidebar -->

  <div class="clearfix"></div>
</div>
<!-- Sidebar -left -->

</div>
