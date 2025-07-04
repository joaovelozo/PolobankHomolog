@extends('admin.includes.master')
@section('content')

<style>
  /* Estilos CSS */
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

  .input-field {
    margin-bottom: 1.5rem;
  }

  .input-field input,
  .input-field select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    outline: none;
  }

  .input-field label {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: bold;
  }

  .input-field select {
    width: 100%;
  }

  .input-field input[type="file"] {
    padding: 0.5rem 0;
  }

  .bg-danger {
    background-color: #dc3545;
  }
</style>

<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h4 class="mb-1 mt-0">Edição de Perfil</h4>
                </div>
                
            </div>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap -mx-4 mb-8">
      <div class="w-full lg:w-1/3 px-4 mb-8 lg:mb-0">
        <div class="mt-8">
          <h3 class="text-2xl font-bold tracking-wide text-white mb-1">Informações Pessoais</h3>
          <p class="text-xs text-gray-300">Edite Seus Dados Pessoais</p>
        </div>
      </div>

      <div class="w-full lg:w-2/3 px-4">
        @if(session()->has('message'))
        <div class="alert-badge" id="notification-badge">
          {{ session('message') }}
        </div>
        @endif
        <div class="px-8 md:px-16 pt-16 pb-8 bg-gray-500 rounded-xl">
          <div class="max-w-xl mx-auto">
            <form method="POST" action="{{route('admin.profile.store')}}" enctype="multipart/form-data">
              @csrf
              <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/2 px-4">
                  <div class="input-field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" type="text" value="{{$adminData->name}}" required>
                  </div>
                </div>
                <div class="w-full md:w-1/2 px-4">
                  <div class="input-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{$adminData->email}}" required>
                  </div>
                </div>
                <div class="w-full md:w-1/2 px-4">
                  <div class="input-field">
                    <label for="phone">Telefone</label>
                    <input id="phone" type="tel" name="phone" value="{{$adminData->phone}}">
                  </div>
                </div>
                <div class="w-full md:w-1/2 px-4">
                  <div class="input-field">
                    <label for="cpfCnpj">CPF</label>
                    <input id="cpfCnpj" name="cpfCnpj" type="text" value="{{$adminData->cpfCnpj}}">
                  </div>
                </div>
                <div class="w-full px-4">
                  <div class="input-field">
                    <label for="avatar">Selecione Uma Imagem</label>
                    <input type="file" name="avatar" id="image">
                  </div>
                </div>
                <div class="w-full px-4">
                  <div class="input-field">
                    <h6>Pré-visualização da Imagem</h6>
                    <img id="showImage" src="{{ (!empty($adminData->avatar)) ? url('uploads/admin_images/'.$adminData->avatar) : url('uploads/noimage.jpg') }}" alt="Admin" style="width:100px; height: 100px;">
                  </div>
                </div>
                <!-- Outros campos e elementos podem ser adicionados aqui -->
              </div>

              <div class="mt-8 text-right">
                <div class="flex flex-row gap-4">
                  <button class="inline-block py-2 px-4 text-xs font-semibold leading-6 text-blue-50 bg-blue-500 hover:bg-blue-600 rounded-lg transition duration-200" type="submit">Atualizar Perfil</button>
                  <a href="{{route('admin.change.password')}}" class="inline-block py-2 px-4 text-xs font-semibold leading-6 text-white bg-danger hover:bg-blue-600 rounded-lg transition duration-200" type="submit">Alterar Senha</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
      // Máscaras
      $('#phone').mask('(00) 00000-0000');
      $('#cpfCnpj').mask('000.000.000-00');
  
      // Pré-visualização da imagem
      $('#image').change(function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#showImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files['0']);
      });
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
