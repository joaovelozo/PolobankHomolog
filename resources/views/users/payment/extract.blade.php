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
                            <li class="breadcrumb-item"><a href="#">Ral Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pagamentos</li>
                        </ol>
                    </nav>
                    <div class="col-md-3 col-xl-2 d-flex align-items-center">
                        <span style="margin-left: 10px;"><b>Suas Movimentações</b></span>
                    </div>

            <hr>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6><strong>Movimentações pagamento PIX</strong></h6>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Hora</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Valor</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td><i class="far fa-calendar" style="color: #00b81f;"></i> {{ $transaction->created_at->format('d/m/Y')}}</td>
                                        <td><i class="far fa-clock" style="color: #00b81f;"></i> {{ $transaction->created_at->format('H:i:s')}}</td>
                                        <td>{{ $transaction->nome}}</td>
                                        <td>R$ {{ formatToBrazilianCurrency($transaction->amount)}}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        });
    </script>
    @endsection
