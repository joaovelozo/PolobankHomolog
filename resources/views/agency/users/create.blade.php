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
                            <li class="breadcrumb-item active" aria-current="page">Clientes</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Cadastro de Clientes</h4>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                            <form method="POST" action="{{ route('clientusers.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nome</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cpfCnpj">CPF</label>
                                            <input type="text" class="form-control" id="cpfCnpj" name="document" value="{{ old('document') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="birthdate">Data de Nascimento</label>
                                            <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="zipcode">CEP</label>
                                            <input type="text" class="form-control" id="zipCode" name="zipcode" value="{{ old('zipcode') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Endereço</label>
                                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="number">Número</label>
                                            <input type="text" class="form-control" id="number" name="number" value="{{ old('number') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="neighborhood">Bairro</label>
                                            <input type="text" class="form-control" id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">Cidade</label>
                                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stateSelect">Estado</label>
                                            <select class="form-control" id="state" name="state" required>
                                                <option value="" disabled selected>Selecione o Estado</option>
                                                @foreach (["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"] as $state)
                                                <option value="{{ $state }}" {{ old('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Telefone de Contato</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email de Acesso</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Digite Corretamente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Senha de Acesso</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirme a Senha</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme sua senha" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="document_front">Frente do Documento</label>
                                            <input type="file" class="form-control" id="document_front" name="document_front" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="document_back">Verso do Documento</label>
                                            <input type="file" class="form-control" id="document_back" name="document_back" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selfie">Selfie</label>
                                            <input type="file" class="form-control" id="selfie" name="selfie" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Cadastrar cliente</button>
                                </div>
                            </form>

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
        $('#cpfCnpj').mask('000.000.000-00');
        $('#zipCode').mask('00.000-000');
    });
</script>
@endsection