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
                        <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo"
                            width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Movimentações</h4>
                    </div>
                </div>

            </div>
            <div class="w-full lg:w-2/3 px-4">
                @if (session()->has('message'))
                <div class="alert-badge" id="notification-badge">
                    {{ session('message') }}
                </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="header-title mt-0 mb-1">Movimentações Bancárias</h4>

                                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Data</th>
                                            <th>Operação</th>
                                            <th>Status</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($transactions->count() > 0)
                                        @foreach ($transactions as $transaction)
                                        <tr>
                                            <td class="p-0">
                                                <div class="pb-2 pt-2 pl-2">
                                                    <span class="text-sm text-gray-100 font-medium">{{ $transaction->id }}</span>
                                                </div>
                                            </td>
                                            <td class="p-0">
                                                <div class="h-16 p-6 pb-2 pt-2 ">
                                                    <span class="text-sm font-medium text-gray-100">
                                                        {{$transaction->user->name}}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <p>{{($transaction->getOperacaoDescription()=='Entrada'?' Recebido de ':' Enviado para ')}} {{ $transaction->name }}</p>
                                            </td>
                                            <td class="p-0">
                                                <div class="h-16 p-6 pb-2 pt-2">
                                                    <span class="text-sm text-gray-100 font-medium">
                                                        R$ {{ formatToBrazilianCurrency($transaction->amount) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="p-0">
                                                <div class="h-16 p-6 pb-2 pt-2">
                                                    <span class="text-sm text-gray-100 font-medium">
                                                        {{ $transaction->created_at->format('d/m/Y H:i:s') }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="p-0">
                                                <div class="h-16 p-6 pb-2 pt-2">
                                                    <span class="text-sm text-gray-100 font-medium">
                                                        {{ $transaction->getOperacaoDescription() }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="p-0">
                                                <div class="h-16 p-6 pb-2 pt-2">
                                                    <span class="text-sm text-gray-100 font-medium">
                                                        {{ $transaction->getStatusDescription() }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="p-0">
                                                <div class="h-16 p-6 pb-2 pt-2">
                                                    <span class="text-sm text-gray-100 font-medium">
                                                        {{ $transaction->getTypeDescription() }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">Nenhuma Transação Recebida Encontrada</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end content-page -->

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