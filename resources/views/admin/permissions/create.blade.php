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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                          <li class="breadcrumb-item"><a href="{{route('permission.index')}}">Voltar</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Permissões de Acesso</li>
                      </ol>
                  </nav>
                  <h4 class="mb-1 mt-0">Cadastro de Permissões de Acesso</h4>
              </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{route('permission.store')}}">
                          @csrf

                            <div class="form-group">
                                <label for="exampleInputEmail1">Nome da Permissão</label>
                                <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                            </div>
                            <div class="col-xl-6">
                                <div class="form-group mt-3 mt-xl-0">
                                    <label>Selecione o Grupo de Permissão</label>
                                    <select name="group_name"class="form-control wide" >
                                        <option value="Manager">Gerentes</option>
                                        <option value="Agency">Agência</option>
                                        <option value="loan">Empréstimo</option>
                                        <option value="service">Produtos e Serviços</option>
                                        <option value="STA">STA</option>
                                        <option value="Message">Mensagens</option>
                                        <option value="role">Regras e Permissões</option>
                                        <option value="users">Usuários</option>
                                    </select>
                                </div>
                            </div>



                            <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                                <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Cadastrar Permissão</button>
                          </div>
                        </form>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>
            <!-- end col -->

@endsection
