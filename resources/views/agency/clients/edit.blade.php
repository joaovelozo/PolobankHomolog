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
                          <li class="breadcrumb-item"><a href="{{url('admin.agency')}}">Voltar</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Clientes</li>
                      </ol>
                  </nav>
                  <h4 class="mb-1 mt-0">Edição de Clientes</h4>
              </div>
          </div>
          <div class="w-full lg:w-2/3 px-4">
                @if(session()->has('message'))
                <div class="alert-badge" id="notification-badge">
                    {{ session('message') }}
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>



                        <form action="{{ route('clients.update', $client->id) }}" method="POST">

                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite Corretamente" required value="{{$client->name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpfCnpj">CPF</label>
                                        <input type="text" class="form-control" id="cpfCnpj" name="document" placeholder="Digite Corretamente" required value="{{$client->document}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birthdate">Data de Nascimento</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Digite Corretamente" required value="{{$client->birthdate}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Endereço</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Digite Corretamente" required value="{{$client->address}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="number">Número</label>
                                        <input type="text" class="form-control" id="number" name="number" placeholder="Digite Corretamente" required value="{{$client->number}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zipcode">CEP</label>
                                        <input type="text" class="form-control" id="zipCode" name="zipcode" placeholder="Digite Corretamente" required value="{{$client->zipcode}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="neighborhood">Bairro</label>
                                        <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Digite Corretamente" required value="{{$client->neighborhood}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">Cidade</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="Digite Corretamente" required value="{{$client->city}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stateSelect">Estado</label>
                                        <select class="form-control" id="state" name="state" required>
                                            elect class="form-control" id="stateSelect" name="state">
                                            <option value="" disabled selected>Selecione o Estado</option>
                                            <option value="AC" {{ $client->state === 'AC' ? 'selected' : '' }}>Acre</option>
                                            <option value="AL" {{ $client->state === 'AL' ? 'selected' : '' }}>Alagoas</option>
                                            <option value="AP" {{ $client->state === 'AP' ? 'selected' : '' }}>Amapá</option>
                                            <option value="AM" {{ $client->state === 'AM' ? 'selected' : '' }}>Amazonas</option>
                                            <option value="BA" {{ $client->state === 'BA' ? 'selected' : '' }}>Bahia</option>
                                            <option value="CE" {{ $client->state === 'CE' ? 'selected' : '' }}>Ceará</option>
                                            <option value="DF" {{ $client->state === 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                            <option value="ES" {{ $client->state === 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                            <option value="GO" {{ $client->state === 'GO' ? 'selected' : '' }}>Goiás</option>
                                            <option value="MA" {{ $client->state === 'MA' ? 'selected' : '' }}>Maranhão</option>
                                            <option value="MT" {{ $client->state === 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                            <option value="MS" {{ $client->state === 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                            <option value="MG" {{ $client->state === 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                            <option value="PA" {{ $client->state === 'PA' ? 'selected' : '' }}>Pará</option>
                                            <option value="PB" {{ $client->state === 'PB' ? 'selected' : '' }}>Paraíba</option>
                                            <option value="PR" {{ $client->state === 'PR' ? 'selected' : '' }}>Paraná</option>
                                            <option value="PE" {{ $client->state === 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                            <option value="PI" {{ $client->state === 'PI' ? 'selected' : '' }}>Piauí</option>
                                            <option value="RJ" {{ $client->state === 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                            <option value="RN" {{ $client->state === 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                            <option value="RS" {{ $client->state === 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                            <option value="RO" {{ $client->state === 'RO' ? 'selected' : '' }}>Rondônia</option>
                                            <option value="RR" {{ $client->state === 'RR' ? 'selected' : '' }}>Roraima</option>
                                            <option value="SC" {{ $client->state === 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                            <option value="SP" {{ $client->state === 'SP' ? 'selected' : '' }}>São Paulo</option>
                                            <option value="SE" {{ $client->state === 'SE' ? 'selected' : '' }}>Sergipe</option>
                                            <option value="TO" {{ $client->state === 'TO' ? 'selected' : '' }}>Tocantins</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Telefone de Contato</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Digite Corretamente" value="{{$client->phone}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email de Acesso</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite Corretamente" value="{{$client->email}}">
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
                                <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Alterar Cliente</button>
                            </div>
                        </form>

                        <br>
                        <hr>
                        <!-- Galeria de imagens do cliente -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4 class="mb-3 header-title mt-0">Galeria de Imagens do Cliente</h4>
                                <div class="image-gallery">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="image-container">
                                                <img src="{{ asset($client->document_front) }}" alt="Documento (Frente)" class="img-fluid">
                                                <div class="overlay">
                                                    <a href="{{ asset($client->document_front) }}" download><i class="fas fa-download"></i></a>
                                                    <a href="{{ asset($client->document_front) }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                </div>
                                            </div>
                                            <p>Documento (Frente)</p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="image-container">
                                                <img src="{{ asset($client->document_back) }}" alt="Documento (Verso)" class="img-fluid">
                                                <div class="overlay">
                                                    <a href="{{ asset($client->document_back) }}" download><i class="fas fa-download"></i></a>
                                                    <a href="{{ asset($client->document_back) }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                </div>
                                            </div>
                                            <p>Documento (Verso)</p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="image-container">
                                                <img src="{{ asset($client->selfie) }}" alt="Selfie" class="img-fluid">
                                                <div class="overlay">
                                                    <a href="{{ asset($client->selfie) }}" download><i class="fas fa-download"></i></a>
                                                    <a href="{{ asset($client->selfie) }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                </div>
                                            </div>
                                            <p>Selfie</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
      if (document.getElementById('notification-badge')) {
        setTimeout(() => {
          document.getElementById('notification-badge').style.display = 'none';
        }, 5000);
      }
    });
</script>


<script type="text/javascript">
//Mascaras
$(document).ready(function() {
$('#phone').mask('(00) 00000-0000');
$('#cpfCnpj').mask('000.000.000-00');
$('#zipCode').mask('00.000-000')
});
</script>

@endsection
