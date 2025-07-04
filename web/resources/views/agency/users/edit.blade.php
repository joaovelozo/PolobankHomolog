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
      <!-- Start Content-->
      <div class="container-fluid">
          <div class="row page-title">
              <div class="col-md-12">
                  <nav aria-label="breadcrumb" class="float-right mt-1">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('admin.admin.dashboard')}}">Polocal Bank</a></li>
                          <li class="breadcrumb-item"><a href="{{route('manager.index')}}">Voltar</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Gerentes</li>
                      </ol>
                  </nav>
                  <h4 class="mb-1 mt-0">Edição de Gerentes</h4>
              </div>
          </div>
          <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{ route('manager.update', $manager->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite Corretamente" required value="{{$manager->name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpfCnpj">CPF</label>
                                        <input type="text" class="form-control" id="cpfCnpj" name="document" placeholder="Digite Corretamente" required value="{{$manager->document}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birthdate">Data de Nascimento</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Digite Corretamente" required value="{{$manager->birthdate}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Endereço</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Digite Corretamente" required value="{{$manager->address}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="number">Número</label>
                                        <input type="text" class="form-control" id="number" name="number" placeholder="Digite Corretamente" required value="{{$manager->number}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zipcode">CEP</label>
                                        <input type="text" class="form-control" id="zipCode" name="zipcode" placeholder="Digite Corretamente" required value="{{$manager->zipcode}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="neighborhood">Bairro</label>
                                        <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Digite Corretamente" required value="{{$manager->neighborhood}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">Cidade</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="Digite Corretamente" required value="{{$manager->city}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stateSelect">Estado</label>
                                        <select class="form-control" id="state" name="state" required>
                                            elect class="form-control" id="stateSelect" name="state">
                                            <option value="" disabled selected>Selecione o Estado</option>
                                            <option value="AC" {{ $manager->state === 'AC' ? 'selected' : '' }}>Acre</option>
                                            <option value="AL" {{ $manager->state === 'AL' ? 'selected' : '' }}>Alagoas</option>
                                            <option value="AP" {{ $manager->state === 'AP' ? 'selected' : '' }}>Amapá</option>
                                            <option value="AM" {{ $manager->state === 'AM' ? 'selected' : '' }}>Amazonas</option>
                                            <option value="BA" {{ $manager->state === 'BA' ? 'selected' : '' }}>Bahia</option>
                                            <option value="CE" {{ $manager->state === 'CE' ? 'selected' : '' }}>Ceará</option>
                                            <option value="DF" {{ $manager->state === 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                            <option value="ES" {{ $manager->state === 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                            <option value="GO" {{ $manager->state === 'GO' ? 'selected' : '' }}>Goiás</option>
                                            <option value="MA" {{ $manager->state === 'MA' ? 'selected' : '' }}>Maranhão</option>
                                            <option value="MT" {{ $manager->state === 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                            <option value="MS" {{ $manager->state === 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                            <option value="MG" {{ $manager->state === 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                            <option value="PA" {{ $manager->state === 'PA' ? 'selected' : '' }}>Pará</option>
                                            <option value="PB" {{ $manager->state === 'PB' ? 'selected' : '' }}>Paraíba</option>
                                            <option value="PR" {{ $manager->state === 'PR' ? 'selected' : '' }}>Paraná</option>
                                            <option value="PE" {{ $manager->state === 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                            <option value="PI" {{ $manager->state === 'PI' ? 'selected' : '' }}>Piauí</option>
                                            <option value="RJ" {{ $manager->state === 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                            <option value="RN" {{ $manager->state === 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                            <option value="RS" {{ $manager->state === 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                            <option value="RO" {{ $manager->state === 'RO' ? 'selected' : '' }}>Rondônia</option>
                                            <option value="RR" {{ $manager->state === 'RR' ? 'selected' : '' }}>Roraima</option>
                                            <option value="SC" {{ $manager->state === 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                            <option value="SP" {{ $manager->state === 'SP' ? 'selected' : '' }}>São Paulo</option>
                                            <option value="SE" {{ $manager->state === 'SE' ? 'selected' : '' }}>Sergipe</option>
                                            <option value="TO" {{ $manager->state === 'TO' ? 'selected' : '' }}>Tocantins</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Telefone de Contato</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Digite Corretamente" value="{{$manager->phone}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email de Acesso</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite Corretamente" value="{{$manager->email}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Senha de Acesso</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirme a Senha</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password">
                                    </div>
                                </div>

                                </div>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Alterar Gerente</button>
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
