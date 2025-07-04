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
                            <li class="breadcrumb-item active" aria-current="page">Transferências</li>
                        </ol>
                    </nav>
                    <div class="row">
                        <div class="col-md-3 col-xl-2">
                            <img src="{{asset('assets/backend/images/logo.png')}}" class="img-fluid" alt="Logo" width="30px">
                        </div>
                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Transferências</h4>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6><strong>Movimentações de Transferências</strong></h6>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Hora</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Movimentação</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td><i class="far fa-calendar" style="color: #00b81f;"></i> {{ $transaction->created_at->format('d/m/Y')}}</td>
                                        <td><i class="far fa-clock" style="color: #00b81f;"></i> {{ $transaction->created_at->format('H:i:s')}}</td>
                                        <td>{{ $transaction->name }}</td>
                                        <td>R$ {{ formatToBrazilianCurrency($transaction->amount)}}</td>
                                        <td>{{ $transaction->operacao }}</td>
                                        <td>{{ $transaction->getStatusDescription()}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
