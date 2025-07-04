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
                          <li class="breadcrumb-item"><a href="{{route('loan.index')}}">Voltar</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Empréstimos</li>
                      </ol>
                  </nav>
                  <h4 class="mb-1 mt-0">Edição de Empréstimo</h4>
              </div>
          </div>
          <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{ route('message.update', $message->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="exampleInputEmail1">Título do Empréstimo</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $message->title }}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Descrição</label>
                              <input type="textarea" class="form-control" id="description" name="description" value="{{ $message->description }}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                          </div>


                            <button type="submit" class="btn btn-primary">Editar Empréstimo</button>
                        </form>

                    </div> <!-- end card-body-->
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

@endsection
