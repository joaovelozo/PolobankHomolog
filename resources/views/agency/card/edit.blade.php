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
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{ route('manager.update', $manager->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="exampleInputEmail1">Primeiro Nome</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$manager->name}}" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sobrenome</label>
                                <input type="text" class="form-control" id="name" name="fullname" value="{{$manager->fullname}}" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">CPF</label>
                                <input type="text" class="form-control" id="cpfCnpj" name="cpfCnpj" value="{{$manager->cpfCnpj}}" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Data de Nascimento</label>
                                <input type="date" class="form-control" id="name" name="birthDate" value="{{$manager->birthDate}}" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Endereço</label>
                                <input type="text" class="form-control" id="name" name="address" value="{{$manager->address}}" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Número</label>
                                <input type="text" class="form-control" id="name" name="addressNumber" value="{{$manager->addressNumber}}" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">CEP</label>
                                <input type="text" class="form-control" id="zipCode" name="postalCode"  value="{{$manager->postalCode}}" aria-describedby="emailHelp" placeholder="Digite Corretamente" required>

                            </div>
                            <div class="form-group">
                                <label for="stateSelect">Estado</label>
                                <select class="form-control" id="stateSelect" name="province" required value="{{$manager->province}}">
                                    <option value="" disabled selected>Selecione o Estado</option>
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
                            <div class="form-group">
                                <label for="exampleInputEmail1">Telefone de Contato</label>
                                <input type="tel" class="form-control" id="mobilePhone" name="mobilePhone" value="{{$manager->mobilePhone}}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Email de Acesso</label>
                              <input type="email" class="form-control" id="email" name="email" value="{{$manager->email}}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                          </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Senha de Acesso</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Confirme a Senha</label>
                                <input type="password" class="form-control" id="password" name="password_confirmation" placeholder="Password">
                            </div>


                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Atualizar Gerente</button>
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
                    $('$zipCode').mask('00.000-000')
                });
            </script>

@endsection
