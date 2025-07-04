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
    <script src="https://cdn.tiny.cloud/1/800zai2qbtsvy9kjn4fff6h4ihrv5i0qgpsn461vdazmbxtn/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

<div class="content-page">
  <div class="content">

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
                  <div class="container-fluid">
                    <div class="row page-title align-items-center">
                        <div class="row">
                            <div class="col-md-3 col-xl-2">
                                <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo" width="200px">
                            </div>
                            <div class="col-md-9 col-xl-4 align-self-center">
                                <h4 class="mb-1 mt-0">Contratos</h4>
                            </div>
                        </div>

\
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{route('contracts.update',$ctr->id)}}">
                          @csrf
                          @method('PUT')


                            <div class="form-group">
                                <label for="exampleInputEmail1">Título</label>
                                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" placeholder="Digite Corretamente" value="{{$ctr->title}}">

                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Conteúdo</label>
                              <textarea rows="5" class="form-control" id="content" name="content" aria-describedby="emailHelp" placeholder="Digite Corretamente">{{old('content')}}</textarea>

                          </div>



                          <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                            <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Alterar Contrato</button>
                      </div>

                        </form>

                    </div>

                </div> <!-- end card-->
            </div>
          </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
            <!-- end col -->

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>


@endsection
