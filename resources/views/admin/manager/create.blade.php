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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a>
                                </li>
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                                <form method="POST" action="{{ route('manager.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Nome</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ old('name') }}" placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Nome da Mãe</label>
                                                <input type="text" class="form-control" id="name" name="nameMother"
                                                    value="{{ old('name') }}" placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Faturamento Mensal</label>
                                                <input type="text" class="form-control" id="name" name="rent"
                                                    value="{{ old('name') }}" placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Como Gostaria de Ser Chamado?</label>
                                                <input type="text" class="form-control" id="name" name="username"
                                                    value="{{ old('username') }}" placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender">Gênero</label>
                                                <select class="form-control" id="gender" name="gender" required>
                                                    <option value="">Selecione...</option>
                                                    <option value="MASCULINO" {{ old('gender') == 'MASCULINO' ? 'selected' : '' }}>
                                                        Masculino</option>
                                                    <option value="FEMININO"
                                                        {{ old('gender') == 'FEMININO' ? 'selected' : '' }}>Feminino</option>
                                                    <option value="OUTROS" {{ old('gender') == 'OUTROS' ? 'selected' : '' }}>
                                                        Outro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idMaritalStatus">Estado Civil</label>
                                                <select class="form-control" id="idMaritalStatus" name="idMaritalStatus"
                                                    required>
                                                    <option value="">Selecione...</option>
                                                    <option value="single"
                                                        {{ old('idMaritalStatus') == 'single' ? 'selected' : '' }}>
                                                        Solteiro(a)</option>
                                                    <option value="married"
                                                        {{ old('idMaritalStatus') == 'married' ? 'selected' : '' }}>
                                                        Casado(a)</option>
                                                    <option value="separate"
                                                        {{ old('idMaritalStatus') == 'separate' ? 'selected' : '' }}>
                                                        Separado(a)</option>
                                                    <option value="widower"
                                                        {{ old('idMaritalStatus') == 'widower' ? 'selected' : '' }}>
                                                        Viúvo(a)</option>
                                                </select>
                                            </div>
                                        </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender">Pessoa Politicamente Exposta</label>
                                                <select name="political" id="political" class="form-control" required>
                                            <option value="0" {{ old('political') == '0' ? 'selected' : '' }}>Não</option>
                                            <option value="1" {{ old('political') == '1' ? 'selected' : '' }}>Sim</option>
                                        </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cpfCnpj">CPF</label>
                                                <input type="text" class="form-control" id="cpfCnpj"
                                                    name="documentNumber" value="{{ old('documentNumber') }}"
                                                    placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cpfCnpj">Número da Identidade</label>
                                                <input type="text" class="form-control" id="cpfCnpj"
                                                    name="identityDocument" value="{{ old('identityDocument') }}"
                                                    placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="birthdate">Data de Emissão</label>
                                                <input type="date" class="form-control" id="birthdate" name="birthdate"
                                                    value="{{ old('birthdate') }}" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cpfCnpj">Órgão Emissor</label>
                                                <input type="text" class="form-control" id="cpfCnpj"
                                                    name="issuingAgency" value="{{ old('issuingAgency') }}"
                                                    placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stateSelect">Estado Emissor</label>
                                                <select class="form-control" id="state" name="issuingState" required>
                                                    <option value="" disabled selected>Selecione o Estado</option>
                                                    @foreach (['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $state)
                                                        <option value="{{ $state }}"
                                                            {{ old('state') === $state ? 'selected' : '' }}>
                                                            {{ $state }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="birthdate">Data de Nascimento</label>
                                                <input type="date" class="form-control" id="birthdate"
                                                    name="issueDate" value="{{ old('issueDate') }}"
                                                    placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="zipcode">CEP</label>
                                                <input type="text" class="form-control" id="cep" name="zipCode"
                                                    value="{{ old('zipcode') }}"  required onblur="buscarEndereco()" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Endereço</label>
                                                <input type="text" class="form-control" id="endereco" name="address"
                                                    value="{{ old('address') }}" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addressNumber">Número</label>
                                                <input type="text" class="form-control" id="addressNumber" name="addressNumber"
                                                    value="{{ old('addressNumber') }}" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="neighborhood">Bairro</label>
                                                <input type="text" class="form-control" id="bairro"
                                                    name="neighborhood" value="{{ old('neighborhood') }}"
                                                    placeholder="Digite Corretamente" required  readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city">Cidade</label>
                                                <input type="text" class="form-control" id="cidade" name="city"
                                                    value="{{ old('city') }}" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="state">Estado</label>
                                                <input type="text" class="form-control" id="estado" name="state"
                                                    value="{{ old('state') }}" placeholder="Digite Corretamente"
                                                    readonly required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Telefone de Contato</label>
                                                <input type="tel" class="form-control" id="phone" name="phoneNumber"
                                                    value="{{ old('phone') }}" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">WhatsApp</label>
                                                <input type="tel" class="form-control" id="phone" name="cellPhone"
                                                    value="{{ old('phone') }}" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email de Acesso</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email') }}" placeholder="Digite Corretamente"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Senha de Acesso</label>
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Password" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirme a Senha</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" placeholder="Password" required>
                                            </div>
                                        </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="selfie">Selfie</label>
                                                <input type="file" class="form-control" id="selfie" name="imageself"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="document_front">Frente do Documento</label>
                                                <input type="file" class="form-control" id="document_front"
                                                    name="imagedoc" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="document_back">Verso do Documento</label>
                                                <input type="file" class="form-control" id="document_back"
                                                    name="imagedoc_verso" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="selfie">Comprovante de Endereço</label>
                                                <input type="file" class="form-control" id="selfie" name="imagecomprovante"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary"
                                            style="background-color: #00b81f; color: white;">Cadastrar Gerente</button>
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

    <script>
        function buscarEndereco() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            if (cep !== '') {
                const validacep = /^[0-9]{8}$/;
                if (validacep.test(cep)) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById('endereco').value = data.logradouro;
                                document.getElementById('cidade').value = data.localidade;
                                document.getElementById('bairro').value = data.bairro;
                                document.getElementById('estado').value = data.uf;
                            } else {
                                alert('CEP não encontrado.');
                            }
                        })
                        .catch(error => {
                            alert('Erro ao buscar o CEP.');
                            console.error(error);
                        });
                } else {
                    alert('Formato de CEP inválido.');
                }
            }
        }
    </script>

@endsection
