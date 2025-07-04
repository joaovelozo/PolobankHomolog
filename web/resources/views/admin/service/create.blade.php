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
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('service.index') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Serviços</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Cadastro de Serviços</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                                <form method="POST" action="{{ route('service.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Título</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            aria-describedby="emailHelp" placeholder="Digite Corretamente">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Descrição</label>
                                        <textarea rows="5" class="form-control" id="description" name="description" aria-describedby="emailHelp"
                                            placeholder="Digite Corretamente"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Url</label>
                                        <input type="text" class="form-control" id="url" name="url"
                                            aria-describedby="emailHelp" placeholder="Digite Corretamente">
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Imagem</label>
                                        <input type="file" class="form-control-file" id="image" name="image">
                                        <img id="showImage" src="#" alt="Preview"
                                            style="max-width: 200px; margin-top: 10px; display: none;">
                                    </div>
                                    <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                                        <button type="submit" class="btn btn-primary"
                                            style="background-color: #00b81f; color: white;">Cadastrar Serviço</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ... Seu código posterior ... -->

                <script type="text/javascript">
                    $(document).ready(function() {
                        // Seu código existente para máscaras

                        // Mostrar pré-visualização da imagem selecionada
                        $('#image').change(function(e) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('#showImage').attr('src', e.target.result).show();
                            }
                            reader.readAsDataURL(e.target.files[0]);
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
