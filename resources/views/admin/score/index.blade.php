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
      </style>
    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <img src="{{asset('assets/backend/images/logo.png')}}" class="img-fluid" alt="Logo" width="100px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Score Interno</h4>
                    </div>
                </div>
                
            </div>
            <div class="w-full lg:w-2/3 px-4">
                @if(session()->has('message'))
                <div class="alert-badge" id="notification-badge">
                    {{ session('message') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mt-0 mb-1">Pontuação</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                     
                                        <th>Cliente</th>
                                        <th>CPF</th>
                                        <th>Risco</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scores as $score)
                                    <tr class="{{ $score->status === 'Alto Risco' ? 'table-danger' : 'table-success' }}">
                                        <td>{{ $score->transaction->user->name }}</td>
                                        <td>{{ $score->transaction->user->cpfCnpj }}</td>
                                        <td>{{ $score->status }}</td>
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
<!-- end content-page -->
@endsection
