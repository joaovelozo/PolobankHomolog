@extends('agency.includes.master')
@section('content')

@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp

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
    <script src="https://cdn.tiny.cloud/1/800zai2qbtsvy9kjn4fff6h4ihrv5i0qgpsn461vdazmbxtn/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

<div class="content-page">
  <div class="content">
    @if($status === 'active')
      <!-- Start Content-->
      <div class="container-fluid">
          <div class="row page-title">
              <div class="col-md-12">
                  <nav aria-label="breadcrumb" class="float-right mt-1">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('admin.admin.dashboard')}}">Polocal Bank</a></li>
                          <li class="breadcrumb-item"><a href="{{route('message.index')}}">Voltar</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Mensagens</li>
                      </ol>
                  </nav>
                  <h4 class="mb-1 mt-0">Cadastro de Mensagens</h4>
              </div>
          </div>
      </div>
  </div>


          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{route('agencyticket.store')}}">
                          @csrf

                            <div class="form-group">
                                <label for="exampleInputEmail1">Título</label>
                                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Descrição</label>
                              <textarea rows="5" class="form-control" id="description" name="description" aria-describedby="emailHelp" placeholder="Digite Corretamente"></textarea>

                          </div>



                          <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                            <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Abrir Chamado</button>
                      </div>

                        </form>

                    </div>
                    @else
                    @endif<!-- end card-body-->
                </div> <!-- end card-->
            </div>
            <!-- end col -->
<script type="text/javascript">
  $(document).ready(function(){
      // Máscara para o campo de telefone
      $('#phone').mask('(00) 00000-0000');

      // Máscara para o campo de CPF
      $('#cpfCnpj').mask('000.000.000-00');

      // Seu código existente para a pré-visualização da imagem
      $('#image').change(function(e){
          var reader = new FileReader();
          reader.onload = function(e){
              $('#showImage').attr('src',e.target.result);
          }
          reader.readAsDataURL(e.target.files['0']);
      });
  });
</script>

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>


@endsection
