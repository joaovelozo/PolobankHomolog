@extends('agency.includes.master')
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
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="content-page">
        <div class="content">
            <!-- Start Content -->
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="row">
                        <div class="col-md-12 col-xl-12 align-self-center">
                            <h4 class="mb-1 mt-0">Últimas Transações</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-2/3 px-4">
            @if (session()->has('message'))
                <div class="alert-badge" id="notification-badge">
                    {{ session('message') }}
                </div>
            @endif
            <hr>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mt-0 mb-1">Gestão de Clientes</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Cliente</th>
                                        <th>Descrição</th>
                                        <th>Tipo</th>
                                        <th>Transação</th>
                                        <th>Operação</th>
                                        <th>Data</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <p> {{ $transaction->id }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $transaction->user->name }}</p>
                                            </td>
                                            <td>
                                                <p>{{($transaction->getOperacaoDescription()=='Entrada'?' Recebido de ':' Enviado para ')}} {{ $transaction->name }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $transaction->getOperacaoDescription() }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $transaction->getMetodoDescription() }}</p>
                                            </td>
                                            <td>
                                                <p>{{($transaction->getOperacaoDescription()=='Entrada'?' Recebido de ':' Enviado para ')}} {{ $transaction->name }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $transaction->created_at }}</p>
                                            </td>

                                            <td>
                                                <p>R$ {{ number_format($transaction->amount, 2, ',', '.') }}</p>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end content -->
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
