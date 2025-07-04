@extends('users.includes.master')
@section('content')
@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp
<div class="content-page">
    <div class="content">
        @if($status === 'active')
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pagamentos</li>
                        </ol>
                    </nav>
                    <div class="row">
                        <div class="col-md-3 col-xl-2 d-flex align-items-center">
                            <span style="margin-left: 20px;">Pix Cópia e Cola</span>
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
                            <form method="post" action="{{ route('payment.pix.store') }}" class="text-center" onsubmit="disableSubmitButton(this)">
                                @csrf
                                <div class="card-body">
                                    <h3 class="card-title text-center">
                                        @if($pixPreview->payment->amount==0)
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">R$</div>
                                            </div>
                                            <input type="text" name="amount" required class="form-control price form-control-lg" placeholder="Digite o valor" data-toggle="tooltip" title="Digite o valor">
                                        </div>
                                        @else
                                        R$ {{ number_format($pixPreview->payment->amount / 100, 2, ',', '.') }}
                                        @endif
                                    </h3>
                                    <h4 class="text-center">{{ $pixPreview->payment->name }}</h4>
                                    @if(isset($pixPreview->payment->description))
                                    <p class="text-muted text-center">{{ $pixPreview->payment->description }}</p>
                                    @endif
                                    <p class="text-center">CPF/CNPJ: {{ $pixPreview->payment->taxId }}</p>
                                    <hr>
                                    <h5 class="mt-4">Sobre a Transação</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">Código do banco: {{ $pixPreview->payment->bankCode }}</li>
                                        @if($pixPreview->payment->amount>0)
                                        <li class="list-group-item">Desconto: R$ {{ number_format($pixPreview->payment->discountAmount / 100, 2, ',', '.') }}</li>
                                        <li class="list-group-item">Juros: R$ {{ number_format($pixPreview->payment->interestAmount / 100, 2, ',', '.') }}</li>
                                        <li class="list-group-item">Valor: R$ {{ number_format($pixPreview->payment->amount / 100, 2, ',', '.') }}</li>
                                        @endif
                                    </ul>
                                    <h5 class="mt-4">Código</h5>
                                    <p class="text-monospace">{{ $pixPreview->id }}</p>
                                </div>
                                @if($pixPreview->payment->status=='created')
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="codigo_pix" required class="form-control" value="{{ $pixPreview->id}}">
                                </div>
                                <button type="submit" class="btn btn-primary" id="submit-button"> Fazer Transferência</button>
                                @else
                                <div class="alert alert-danger" role="alert">
                                    @if($pixPreview->payment->status=='paid')
                                    <span>Este pagamento já foi efetuado.</span>
                                    @elseif($pixPreview->payment->status=='expired')
                                    <span>Este pagamento já expirou.</span>
                                    @else
                                    <span>Este pagamento já venceu</span>
                                    @endif
                                </div>
                                @endif
                            </form>
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
                trigger: 'focus' // O tooltip será mostrado quando o input estiver em foco
            });
        });
    </script>
    <script type="text/javascript">
        //Mascaras
        $(document).ready(function() {

            $('.price').mask("#.##0,00", {
                reverse: true
            });
        });

        function disableSubmitButton(form) {
            const submitButton = form.querySelector("#submit-button");
            submitButton.disabled = true;
            submitButton.textContent = 'Processando...';
        }
    </script>
    @endsection
