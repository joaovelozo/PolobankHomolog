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
                  <h4 class="mb-1 mt-0">Cadastro de Permissões e Regra de Acesso</h4>
              </div>
          </div>
          <div class="w-full lg:w-2/3 px-4">
            @if(session()->has('message'))
            <div class="alert-badge" id="notification-badge">
                {{ session('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">


                        <form method="POST" action="{{route('role.permission.store')}}">
                          @csrf

                          <div class="col-xl-6">
                            <div class="form-group mt-3 mt-xl-0">
                                <label>Selecione o Perfil</label>
                                <select name="role_id"class="form-control wide" >
                                    @foreach($roles as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                      <hr>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultAll">
                        <label class="form-check-label" for="flexCheckDefault"><strong>Selecionar Todas as Permissões</strong></label>
                    </div>
                    <br>
                      <label>Selecione as Permissões</label>
                      @foreach($permission_groups as $item)
                      <div class="row">
                        <div class="col-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck2" >
                                <label class="custom-control-label" for="customCheck2">{{$item->group_name}}</label>
                            </div>
                        </div>
                        <div class="col-9">

                            <!--- Get User Permissions Group !--->

                            @php
                            $permissions = App\Models\User::getpermissionByGroupName($item->group_name);
                            @endphp


                               @foreach($permissions as $item)
                            <div class="custom-control custom-checkbox">
                                <input class="form-check-input" name="permission[]" type="checkbox" value="{{$item->id}}" id="flexCheckDefault{{$item->id}}">
                                <label class="form-check-label" for="flexCheckDefault{{$item->id}}">{{ $item->name }}</label>
                        </div>
                        @endforeach
                      </div>
                      </div>
                      @endforeach
                      <br>
                      <br>


                      <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                        <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Relacionar Perfil e Permissão</button>
                  </div>
                        </form>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>
            <!-- end col -->

            <script type="text/javascript">

            $('#flexCheckDefaultAll').click(function(){
                if($(this).is(':checked')){
               $('input[type = checkbox]').prop('checked', true);
                }else{
                    $('input[type = checkbox]').prop('checked', false);
                }
            });
            </script>


<script>
    document.addEventListener('DOMContentLoaded', (event) => {
      if (document.getElementById('notification-badge')) {
        setTimeout(() => {
          document.getElementById('notification-badge').style.display = 'none';
        }, 5000);
      }
    });
  </script>

@endsection
