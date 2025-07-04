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
                    <div class="col-md-3 col-xl-2">
                        <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Débito em Conta</h4>
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <a href="{{route('agency.debit')}}" class="btn btn-custom btn-sm mr-4 mb-3 mb-sm-0">
                            <i class="uil-plus mr-1"></i>Novo Débito
                        </a>
                    </div>
                </div>
            </div>

            @if(session()->has('message'))
            <div class="alert-badge" id="notification-badge">
                {{ session('message') }}
            </div>
            @endif

            <hr>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mt-0 mb-1">Últimos Débitos Efetuados</h4>



                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Valor</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loop through successful transactions -->
                                    @foreach ($successfulTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->sender->name }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                        <td>Success</td>
                                    </tr>
                                    @endforeach

                                    <!-- Loop through transactions with insufficient balance -->
                                    @foreach ($insufficientBalanceTransactions as $transaction)
                                    <tr>
                                        <td>{{ optional($transaction->sender)->name ?? 'Usuário não disponível' }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                        <td>Saldo Insuficiente</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->
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