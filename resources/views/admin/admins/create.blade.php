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
                                <li class="breadcrumb-item"><a href="{{ route('all.admin') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Administradores</li>
                            </ol>
                        </nav>
                        <div class="row">
                            <div class="col-md-3 col-xl-2">
                                <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo" width="200px">
                            </div>

                        </div>

                    </div>
                    <br>
                    <br>
                    <br>
                    <h4 class="mb-1 mt-0">Cadastro de Administradores</h4>
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

                <!--end breadcrumb-->
                <div class="container">
                    <div class="main-body">
                        <div class="row">

                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">

                                        <form method="post" action="{{ route('admin.user.store') }}" enctype="multipart/form-data">
                                            @csrf

                                            @csrf <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">Nome</label>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cpfCnpj">CPF</label>
                                                        <input type="text" class="form-control" id="cpfCnpj"
                                                            name="document" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="birthdate">Data de Nascimento</label>
                                                        <input type="date" class="form-control" id="birthdate"
                                                            name="birthdate" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="address">Endereço</label>
                                                        <input type="text" class="form-control" id="address"
                                                            name="address" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="number">Número</label>
                                                        <input type="text" class="form-control" id="number"
                                                            name="number" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="zipcode">CEP</label>
                                                        <input type="text" class="form-control" id="zipCode"
                                                            name="zipcode" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="neighborhood">Bairro</label>
                                                        <input type="text" class="form-control" id="neighborhood"
                                                            name="neighborhood" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="city">Cidade</label>
                                                        <input type="text" class="form-control" id="city"
                                                            name="city" placeholder="Digite Corretamente" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="stateSelect">Estado</label>
                                                        <select class="form-control" id="state" name="state" required>
                                                            <option value="" disabled selected>Selecione o Estado
                                                            </option>
                                                            <option value="AC">Acre</option>
                                                            <option value="AL">Alagoas</option>
                                                            <option value="AP">Amapá</option>
                                                            <option value="AM">Amazonas</option>
                                                            <option value="BA">Bahia</option>
                                                            <option value="CE">Ceará</option>
                                                            <option value="DF">Distrito Federal</option>
                                                            <option value="ES">Espírito Santo</option>
                                                            <option value="GO">Goiás</option>
                                                            <option value="MA">Maranhão</option>
                                                            <option value="MT">Mato Grosso</option>
                                                            <option value="MS">Mato Grosso do Sul</option>
                                                            <option value="MG">Minas Gerais</option>
                                                            <option value="PA">Pará</option>
                                                            <option value="PB">Paraíba</option>
                                                            <option value="PR">Paraná</option>
                                                            <option value="PE">Pernambuco</option>
                                                            <option value="PI">Piauí</option>
                                                            <option value="RJ">Rio de Janeiro</option>
                                                            <option value="RN">Rio Grande do Norte</option>
                                                            <option value="RS">Rio Grande do Sul</option>
                                                            <option value="RO">Rondônia</option>
                                                            <option value="RR">Roraima</option>
                                                            <option value="SC">Santa Catarina</option>
                                                            <option value="SP">São Paulo</option>
                                                            <option value="SE">Sergipe</option>
                                                            <option value="TO">Tocantins</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phone">Telefone de Contato</label>
                                                        <input type="tel" class="form-control" id="phone"
                                                            name="phone" placeholder="Digite Corretamente">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Email de Acesso</label>
                                                        <input type="email" class="form-control" id="email"
                                                            name="email" placeholder="Digite Corretamente">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password">Senha de Acesso</label>
                                                        <input type="password" class="form-control" id="password"
                                                            name="password" placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password_confirmation">Confirme a Senha</label>
                                                        <input type="password" class="form-control"
                                                            id="password_confirmation" name="password_confirmation"
                                                            placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="document_front">Frente do Documento</label>
                                                        <input type="file" class="form-control" id="document_front"
                                                            name="document_front" placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="document_back">Verso do Documento</label>
                                                        <input type="file" class="form-control" id="document_back"
                                                            name="document_back" placeholder="Password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="selfie">Selfie</label>
                                                        <input type="file" class="form-control" id="selfie"
                                                            name="selfie" placeholder="Password">
                                                    </div>
                                                </div>

                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary"
                                        style="background-color: #00b81f; color: white;">Cadastrar Administrador</button>
                                </div>
                                </form>

                            </div> <!-- end card-body-->

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
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
            $('#zipCode').mask('00.000-000')
        });
    </script>

@endsection
