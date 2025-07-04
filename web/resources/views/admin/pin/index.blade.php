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
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 10px; /* Espaçamento entre os botões */
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <div class="content-page">
        <div class="content">
            <!-- Start Content -->
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="row">
                        <div class="text-center">
                            <h4 class="mr-1">Pin De Acesso</h4>
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
                                    <h4 class="header-title mt-0 mb-1">Cadastre sua Senha de Transação</h4>
                                    <br><br>

                                    <div class="btn-group">
                                        <a href="{{ route('pinadmin.create') }}" class="btn btn-primary">
                                            <i class="uil-lock mr-1"></i>Cadastrar Meu Pin
                                        </a>
                                        <a href="{{ route('pinadmin.edit',$pinadminId) }}" class="btn btn-warning">
                                            <i class="uil-lock mr-1"></i>Atualizar Meu Pin
                                        </a>
                                    </div>

                                    <br><br>
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
