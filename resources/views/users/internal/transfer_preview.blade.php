@extends('users.includes.master')

@section('content')

    @php
        $id = Auth::user()->id;
        $clientId = App\Models\User::find($id);
        $status = $clientId->status;
    @endphp

    <div class="content-page">
        <div class="content">
            @if ($status === 'active')
                <!-- Start Content -->
                <div class="container-fluid">
                    <div class="row page-title">
                        <div class="col-md-12">
                            <nav aria-label="breadcrumb" class="float-right mt-1">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pagamentos</li>
                                </ol>
                            </nav>
                            <div class="row">
                                <div class="col-md-3 col-xl-2 d-flex align-items-center">
                                    <span style="margin-left: 20px;">Transferência Pix</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Revisão</h5>

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

                                        <h4 class="text-center">{{ $pix['nome'] }}</h4>
                                        <p class="text-center">CPF/CNPJ: {{ $pix['cpfcnpj'] }}</p>
                                        <hr>

                                        <h5 class="mt-4">Sobre a Transação</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Chave PIX: {{ $pix['chavePIX'] }}</li>
                                            <li class="list-group-item">Tipo de Chave: {{ $pix['idTipoChavePIX'] }}</li>
                                        </ul>
                                    </div>

                                    <form method="post" action="{{ route('transfer.pix.transfer') }}" class="text-center"
                                        onsubmit="disableSubmitButton(this)">
                                        @csrf
                                        <input type="hidden" name="idTipoChavePIX" required value="{{ $pix['idTipoChavePIX'] }}">
                                        <input type="hidden" name="chave_pix" required value="{{ $pix['chavePIX'] }}">
                                        <input type="hidden" name="nome" required value="{{ $pix['nome'] }}">
                                        <input type="hidden" name="cpfcnpj" required value="{{ $pix['cpfcnpj'] }}">
                                        <input type="hidden" name="amount" required value="{{  $pix['amount'] }}">
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
