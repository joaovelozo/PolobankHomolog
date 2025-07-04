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
                        <h4 class="mb-1 mt-0">Clientes Por Agência</h4>
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
                                <h3>{{ $agency->name }} - Clientes ({{ $clientsCount }})</h3>
                                <table id="datatable" class="table table-striped dt-responsive nowrap" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Conta</th>
                                            <th>Status</th>
                                            <th>Documento</th>
                                            <th>Email</th>
                                            <th>Plano</th>
                                            <th>Telefone</th>
                                            <th>Saldo Disponível</th>
                                            <th>Perfil</th>
                                            <th>Criado Em</th>
                                            <th>Último Acesso</th>
                                            <th>Transação Ativação</th>
                                            <th>Status da Ativação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
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
                    url: "{{ route('admin.agency.getClients', $agency->id) }}",
                    type: 'POST'
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'account',
                        name: 'account'
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
                        data: 'plano',
                        name: 'plano'
                    },
                    {
                        data: 'telefone',
                        name: 'telefone'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'perfil',
                        name: 'perfil'
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