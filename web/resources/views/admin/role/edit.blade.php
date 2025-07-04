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
                  <h4 class="mb-1 mt-0">Edição de Permissões de Acesso</h4>
              </div>
          </div>
          <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{route('permission.update',$permission->id)}}">
                          @csrf
                        @method('PUT')

                            <div class="form-group">
                                <label for="exampleInputEmail1">Nome da Permissão</label>
                                <input type="text" class="form-control" id="name" name="name" value={{$permission->name}} placeholder="Digite Corretamente">

                            </div>
                            <div class="col-xl-6">
                                <div class="form-group mt-3 mt-xl-0">
                                    <label>Selecione o Grupo de Permissão</label>
                                    <select name="group_name"class="form-control wide" >
                                        <option value="manager"{{$permission->group_name == 'manager' ? 'selected' : ''}}>Gerentes</option>
                                        <option value="agency" {{$permission->group_name == 'agency' ? 'selected' : ''}}>Agência</option>
                                        <option value="loan" {{$permission->group_name == 'loan' ? 'selected' : ''}}>Empréstimo</option>
                                        <option value="service" {{$permission->group_name == 'service' ? 'selected' : ''}}>Produtos e Serviços</option>
                                        <option value="STA" {{$permission->group_name == 'sta' ? 'selected' : ''}}>STA</option>
                                        <option value="message" {{$permission->group_name == 'message' ? 'selected' : ''}}>Mensagens</option>
                                        <option value="role" {{$permission->group_name == 'role' ? 'selected' : ''}}>Regras e Permissões</option>
                                        <option value="users" {{$permission->group_name == 'users' ? 'selected' : ''}}>Usuários</option>
                                    </select>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">Criar Permissão</button>
                        </form>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>
            <!-- end col -->

@endsection
