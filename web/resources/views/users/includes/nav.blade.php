@php
$id = Auth::user()->id;
$documentCount = App\Models\Document::where('user_id', $id)->count();
$ticketCount = App\Models\Ticket::where('user_id', $id)->count();

$contracts = App\Models\Contract::all();
$contractCount = count($contracts);

$messages = App\Models\Message::all();
$messageCount = count($messages);
$ip = request()->ip();

$userService = App\Models\UserServices::where('user_id', $id)
->whereHas('payment', function ($query) {
$query->where('title', 'like', '%Telemedicina%');
})->first();
@endphp


@if ($status === 'active')
<div class="sidebar-content">
    <!--- Sidemenu -->
    <div id="sidebar-menu" class="slimscroll-menu">
        <ul class="metismenu" id="menu-bar">
            <li class="menu-title">Administração</li>

            <li>
                <a href="{{ route('dashboard') }}">
                    <i data-feather="home"></i>

                    <span> Dashboard </span>
                </a>
            </li>

            <li class="menu-title">Pagamentos</li>
             <li>
                <a href="{{ route('managerkey.index') }}">
                   <i class="fa-solid fa-key"></i>
                    <span> Gerenciar Chave Pix </span>
                </a>
            </li>
            <li>
                <a href="{{ route('pix.index') }}">
                    <i class="fa-brands fa-pix"></i>
                    <span> Pix </span>
                </a>
            </li>

            <li>
                <a href="{{ route('payment.index') }}">
                    <i class="fa-solid fa-barcode"></i>
                    <span> Pagamentos </span>
                </a>
            </li>
            <li>
                <a href="{{ route('transfer') }}" style="display: flex; align-items: center;">
                    <i class="fa-solid fa-users"></i>
                    <span style="word-break: break-word;"> Transferência</span>
                </a>
            </li>

            <li>
                <a href="{{ url('users/comunication') }}">
                    <span class="badge badge-warning float-right">{{ $messageCount }}</span>
                    <i class="fa-solid fa-message"></i>
                    <span>Comunicados </span>
                </a>
            </li>

            <li>

                @php

                @endphp
                <a href="{{ route('document.index') }}">
                    <span class="badge badge-success float-right">{{ $documentCount }}</span>
                    <i class="fa-solid fa-file"></i>
                    <span>Documentos </span>
                </a>
            </li>

            </li>

            <li class="menu-title">Ajuda e Suporte</li>

            <li>
                <a href="{{ route('userticket.index') }}">
                    <span class="badge badge-danger float-right">{{ $ticketCount }}</span>
                    <i data-feather="help-circle"></i>

                    <span> Chamados </span>
                </a>
            </li>
            <li>
                <a href="javascript: void(0);">

                    <i data-feather="file-minus"></i>
                    <span> Contratos </span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="nav-second-level" aria-expanded="false">

                    <li>
                        <a href="{{ route('opencontract.index') }}">Abertura de Conta</a>
                    </li>
                    <li>
                        <span class="badge badge-info float-right">{{ $contractCount }}</span>
                        <a href="{{ route('usercontract.index') }}">Outros Contratos</a>
                    </li>


                </ul>
            </li>


    </div>





    <div class="clearfix"></div>
</div>



<!-- Sidebar -left -->
@else
@endif
</div>
