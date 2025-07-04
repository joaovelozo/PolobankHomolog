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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Importe a biblioteca de máscara -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('manager.index') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Gerentes</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Cadastro de Gerentes</h4>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mt-0">Digite os Dados Corretamente</h4>

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                                        <form method="POST" action="{{route('agencycard.store')}}">
                                          @csrf

                                          <div class="form-group">
                                            <label for="clientSelect">Cliente</label>
                                            <select class="form-control" id="clientSelect" name="user_id" required>
                                                <option value="" disabled selected>Selecione o Cliente</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="stateSelect">Tipo</label>
                                            <select class="form-control" id="stateSelect" name="type" required>
                                                <option value="" disabled selected>Escolha Um Tipo</option>
                                                <option value="Crédito">Crédito</option>
                                                <option value="Débito">Débito</option>

                                            </select>
                                        </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Data de Vencimento</label>
                                                <input type="date" class="form-control" id="name" name="validate" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                                            </div>




                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Gerar Cartão</button>
                                            </div>

                </div>
                                        </form>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                    </div>
                </div>
            </div>


                            <script type="text/javascript">
                                //Mascaras
                                $(document).ready(function() {
                                    $('#mobilePhone').mask('(00) 00000-0000');
                                    $('#cpfCnpj').mask('000.000.000-00');
                                    $('#zipCode').mask('00.000-000')
                                });
                            </script>

                @endsection
