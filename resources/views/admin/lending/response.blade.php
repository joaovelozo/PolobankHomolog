@extends('admin.includes.master')
@section('content')

    <style>
        .alert-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: green;
            color: white;
            border-radius: 5px;
            z-index: 1000;
        }

        .btn-custom {
            background-color: #00b81f;
            /* Cor personalizada */
            color: white;
            /* Cor do texto */
            /* Outros estilos se necessário */
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="content-page">
        <div class="content">
            <!-- Start Content -->
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="row">
                        <div class="col-md-3 col-xl-2">
                            <img src="{{ asset('assets/backend/images/logo.png') }}" class="img-fluid" alt="Logo"
                                width="200px">
                        </div>
                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Histórico</h4>
                        </div>
                    </div>

                </div>
                <div class="w-full lg:w-2/3 px-4">
                    @if (session()->has('message'))
                        <div class="alert-badge" id="notification-badge">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="content-page">
                        <div class="content">
                            <div class="card-body">
                                <h4 style="display: inline-block;">Chat do Empréstimo</h4>
                                <div style="display: inline-block; vertical-align: middle;">
                                    <button onclick="location.reload();" class="btn btn-custom"
                                        style="background-color: #ff6600; color: #fff;">
                                        <i class="fas fa-sync-alt"></i> Atualizar
                                    </button>
                                </div>
                                <div class="container-fluid">
                                    <!-- Restante do seu código HTML -->
                                    <h6 style="color:orange;"><b>Chat do Usuário:</b></h6>
                                    <!-- Exibir a resposta do usuário -->
                                    <div class="mb-3">
                                        <p><strong>Nome do Usuário:</strong>
                                        <h6><b> {{ $lds->user->name }}</b></h6>
                                        </p>

                                    </div>
                                    <hr>

                                    <!-- Seção de respostas existentes -->
                                    @if ($responses->isNotEmpty())
                                        <h6 style="color: #00b81f"><strong>Minhas Respostas:</strong></h6>
                                        <div class="mb-3">
                                            @foreach ($responses as $response)
                                                <img src="{{ asset('assets/backend/images/logo.png') }}"
                                                    alt="Avatar do usuário" width="20px">
                                                <br>
                                                <p style="color:#fa0000"><strong>Enviado por:</strong>
                                                <h6 style="color:#00b81f"><b>{{ $response->user->name }}<b></h6>
                                                </p>
                                                <p><strong>Resposta:</strong>
                                                    <h6 style="color:#fa270b">
                                                        <h5> <b>"{{ $response->response }}"</b></h5>
                                                    </h6>
                                                </p>
                                                <p><strong>Data de Envio:</strong>
                                                   <h6> {{ $response->created_at->format('d M Y H:i') }}</h6></p>
                                                <hr>
                                            @endforeach

                                        </div>
                                    @endif

                                    <!-- Formulário para enviar uma resposta -->
                                    <div class="media-body">
                                        <form method="POST" action="{{ route('lending.user.store') }}">
                                            @csrf
                                            <input type="hidden" name="lending_id" value="{{ $lds->id }}">
                                            <div class="row">
                                                <div class="col">
                                                    <input type="text" class="form-control input-sm" name="response"
                                                        id="response" placeholder="Digite sua resposta">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-custom">Enviar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', (event) => {
                                if (document.getElementById('notification-badge')) {
                                    setTimeout(() => {
                                        document.getElementById('notification-badge').style.display = 'none';
                                    }, 5000);
                                }
                            });
                        </script>

                    @endsection
