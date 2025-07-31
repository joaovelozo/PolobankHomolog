@extends('agency.includes.master')
@section('content')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pix</li>
                        </ol>
                    </nav>
                    <div class="row">

                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Revisão Transação Pix</h4>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
             <!-- Mensagens de Erro -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="pl-2 mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- Mensagem de Sucesso -->
                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <!-- Mensagem de Erro -->
                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <div class="card-body">
                                        <h3 class="card-title text-center">
                                            R$ {{ number_format($amount / 100, 2, ',', '.') }}
                                        </h3>

                                        <h4 class="text-center">{{ $pix['bankData']['name'] ?? '' }}</h4>
                                        <p class="text-center">CPF/CNPJ: {{ $pix['bankData']['document'] ?? '' }}</p>
                                        <p class="text-center"><b>Instituição: {{ $pix['bankData']['bankName'] ?? '' }}</b>
                                        </p>
                                        <hr>

                                        <h5 class="mt-4">Sobre a Transação</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Chave PIX: {{ $pix['key'] ?? '' }}</li>

                                        </ul>

                                        <h5 class="mt-4">Dados da Conta</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Conta: {{ $pix['bankData']['account'] ?? '' }}</li>
                                            <li class="list-group-item">Agência: {{ $pix['bankData']['branch'] ?? '' }}
                                            </li>
                                        </ul>

                                        <h5 class="mt-4">Dados do Banco</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Número Banco: {{ $pix['bankData']['bank'] ?? '' }}
                                            </li>
                                            <li class="list-group-item">Código Banco:
                                                {{ $pix['bankData']['bankCode'] ?? '' }}</li>

                                        </ul>

                                    </div>

                                    <form method="post" action="{{ route('agency.transfer.pix') }}" class="text-center"
                                        onsubmit="disableSubmitButton(this)">
                                        @csrf
                                        <input type="hidden" name="idTipoChavePIX"
                                            value="{{ $params['idTypeKeyPix'] ?? '' }}">
                                        <input type="hidden" name="chave_pix" value="{{ $pix['key'] ?? '' }}">
                                        <input type="hidden" name="nome" value="{{ $pix['bankData']['name'] ?? '' }}">
                                        <input type="hidden" name="cpfcnpj"
                                            value="{{ $pix['bankData']['document'] ?? '' }}">
                                        <input type="hidden" name="amount" value="{{ $amount }}">
                                        <button type="submit" class="btn btn-primary" id="submit-button">
                                            Fazer Transferência
                                        </button>
                                    </form>

                                    @if (isset($pix['status']) && $pix['status'] != 'registered')
                                        <p class="text-center text-warning">Atenção: Chave PIX não está ativa.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <p>Seu status não está ativo.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'focus'
            });
        });

        function disableSubmitButton(form) {
            const submitButton = form.querySelector("#submit-button");
            submitButton.disabled = true;
            submitButton.textContent = 'Processando...';
        }
    </script>

@endsection
