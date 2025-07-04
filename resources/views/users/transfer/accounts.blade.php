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
                        <h4 class="mb-1 mt-0">Transferências</h4>
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
                    <h5 class="card-title">Para quem você deseja transferir?</h5>
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
                    <form method="post" action="{{ route('transfer.accounts.preview') }}">
                        @csrf
                        <div class="form-group">
                            <label>Número da Conta do Recebedor</label>
                            <input type="text" name="accountNumber" required class="form-control" placeholder="Digite Corretamente">
                        </div>
                         <div class="form-group">
                             <label>Número do CPF ou CNPJ do Recebedor</label>
                            <input type="text" name="documentNumber" required class="form-control" placeholder="Digite Corretamente">
                        </div>
                        <div class="form-group">
                                 <label>Valor Que Deseja Transferir</label>
                            <input type="text" name="amount" required class="form-control price" placeholder="Digite Corretamente">
                        </div>
                        <button type="submit" class="btn btn-primary">Conferir Transação</button>
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
@endsection
@section('scripts')
<script type="text/javascript">
    //Mascaras
    $(document).ready(function() {
        $("#cpfCnpj").keydown(function() {
            try {
                $("#cpfCnpj").unmask();
            } catch (e) {}

            var tamanho = $("#cpfCnpj").val().length;

            if (tamanho < 11) {
                $("#cpfCnpj").mask("999.999.999-99");
            } else {
                $("#cpfCnpj").mask("99.999.999/9999-99");
            }

            // ajustando foco
            var elem = this;
            setTimeout(function() {
                // mudo a posição do seletor
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            // reaplico o valor para mudar o foco
            var currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });

        $('.price').mask("#.##0,00", {
            reverse: true
        });
    });
</script>
@endsection
