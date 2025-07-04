@extends('agency.includes.master')
@section('content')
@php
$agencyId = Auth::user()->agency_id;
$users = App\Models\User::where('agency_id', $agencyId)->get();
$userCount = count($users);
$id = Auth::user()->id;
$adminData = App\Models\User::find($id);
@endphp
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
                        <h4 class="mb-1 mt-0">Clientes</h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0 mb-1">Gestão de Agências</h4>
                    <div class="row mb-3">
                        <div class="col-12 text-right">
                            <a href="{{ route('clientusers.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Cadastrar Cliente
                            </a>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="media p-3">
                                        <div class="media-body">
                                            <span class="text-muted text-uppercase font-size-12 font-weight-bold">Clientes</span>
                                            <h2 class="mb-0">{{ $userCount}}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <div id="today-revenue-chart" class="apex-charts"></div>
                                            <span class="text-success font-weight-bold font-size-13"><i class="fa-solid fa-users"></i> 10.21%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="datatable" class="table table-striped dt-responsive nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Status</th>
                                <th>CPF</th>
                                <th>Email</th>
                                <th>Número da Conta</th>
                                <th>Saldo Disponível</th>
                                <th>Criado Em</th>
                                <th>Último Acesso</th>
                                <th>Transação Ativação</th>
                                <th>Status da Ativação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end content-page -->
    @endsection
    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('notification-badge')) {
                setTimeout(() => {
                    document.getElementById('notification-badge').style.display = 'none';
                }, 5000);
            }
        });


        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('clients.getClients') }}",
                    type: 'POST'
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'cpfCnpj',
                        name: 'cpfCnpj'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'account',
                        name: 'account'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'last_login',
                        name: 'last_login'
                    },
                    {
                        data: 'data_ativacao',
                        name: 'data_ativacao'
                    },
                    {
                        data: 'status_ativacao',
                        name: 'status_ativacao'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                pageLength: 100,
                lengthChange: false,
                language: {
                    paginate: {
                        previous: "<i class='uil uil-angle-left'>",
                        next: "<i class='uil uil-angle-right'>"
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                }
            });
        });
    </script>
    @endsection
