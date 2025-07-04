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
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Área Pix</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-center">QR Code de pagamento</h5>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="pl-2 mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <!-- QR Code e Informações -->

                    <p style="word-wrap: break-word;line-break: normal;text-align:center"><strong>Copie e cola:</strong>
                        <br />
                        <span id="qr-code-id" style="word-break: break-word; white-space: normal; display: inline-block;">{{ $brcode->qrCodeString }}</span>
                    </p>
                    <div class="text-center mb-3">
                        <button id="copy-btn" class="btn btn-outline-primary btn-sm copy-btn" onclick="copyToClipboard('{{ $brcode->qrCodeString }}')" title="Copiar">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                    <div class="text-center mb-3">
                        <strong>Valor:</strong> R${{ converterAmountToCents($brcode->amount) }}
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
@endsection
@section('scripts')
<script>
    function copyToClipboard(codigo) {
            // Seleciona o texto
            var copyText = codigo;

            // Cria um elemento temporário para copiar o texto
            var tempInput = document.createElement('input');
            tempInput.value = copyText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            var copyBtn = document.getElementById('copy-btn');
            copyBtn.innerHTML = '<i class="fas fa-check"></i> Copiado';
            copyBtn.classList.add('btn-copied');

            setTimeout(function () {
                copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                copyBtn.classList.remove('btn-copied');
            }, 3000);
        }
</script>
@endsection
