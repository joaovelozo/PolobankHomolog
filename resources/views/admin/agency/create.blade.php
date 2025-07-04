@extends('admin.includes.master')
@section('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Importe a biblioteca de máscara -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('agency.index') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Agências</li>
                            </ol>
                        </nav>

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
                                            <h4 class="mb-1 mt-0">Agências</h4>
                                        </div>
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

            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>
                    <form method="POST" action="{{ route('agency.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-y-4">
                            <p class="text-xs text-gray-500 mb-2">(*) Campos obrigatórios</p>
                            <div class="relative rounded-lg bg-white shadow-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="relative rounded-lg bg-white shadow-md">
                                            <label for="user_id" class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Selecione um Gerente</label>
                                            <select id="user_id" name="user_id" class="custom-select custom-select-lg mb-2">
                                                <option selected>Selecione Um Gerente</option>
                                                @foreach($managers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="relative rounded-lg bg-white shadow-md">
                                            <label for="name" class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Nome da Agência *</label>
                                            <input id="name" class="form-control w-full py-3 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500" type="text" placeholder="" name="name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="relative rounded-lg bg-white shadow-md">
                                            <label for="phone" class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Telefone *</label>
                                            <input id="phone" class="form-control w-full py-3 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500" type="tel" placeholder="" name="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="relative rounded-lg bg-white shadow-md">
                                            <label for="email" class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Email *</label>
                                            <input id="email" class="form-control w-full py-3 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500" type="text" placeholder="" name="email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="relative rounded-lg bg-white shadow-md">
                                            <label for="address" class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Endereço *</label>
                                            <input id="address" class="form-control w-full py-3 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500" type="text" placeholder="" name="address" required>
                                        </div>
                                    </div>
                                    <!-- Coluna vazia para manter a estrutura -->
                                    <div class="col-md-6"></div>
                                </div>
                                <div class="row">
                                    <!-- Coluna vazia para manter a estrutura -->
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Cadastrar Agência</button>
                                        </div>
                                    </div>
                                </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <script type="text/javascript">
        //Mascaras
        $(document).ready(function() {
            $('#phone').mask('(00) 00000-0000');
            // Use o seletor correto para o campo de telefone, que parece ser '#phone'
        });
    </script>
@endsection
