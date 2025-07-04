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



<div class="content-page">
  <div class="content">
      <!-- Start Content-->
      <div class="container-fluid">
          <div class="row page-title">
              <div class="col-md-12">
                  <nav aria-label="breadcrumb" class="float-right mt-1">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('admin.admin.dashboard')}}">Polocal Bank</a></li>
                          <li class="breadcrumb-item"><a href="{{route('pinadmin.index')}}">Voltar</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Pin</li>
                      </ol>
                  </nav>
                  <h4 class="mb-1 mt-0">Cadastro de Pin de Transação</h4>
              </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form action="{{ route('pins.update', $pin->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="pin">PIN (4 dígitos)</label>
                                <input type="password" class="form-control" id="pin" name="pin" required>

                            </div>



                          <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                            <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Atualizar Pin</button>
                      </div>
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

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>

@endsection
