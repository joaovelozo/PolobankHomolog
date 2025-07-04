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
                            <li class="breadcrumb-item"><a href="{{route('service.index')}}">Voltar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Serviços</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Edição de Serviços</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3 header-title mt-0">Atualize os Detalhes</h4>

                            <form method="POST" action="{{ route('service.update', $service->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <!-- Método PUT para atualização -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Título</label>
                                    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" placeholder="Digite Corretamente" value="{{ $service->title }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Descrição</label>
                                    <textarea rows="5" class="form-control" id="description" name="description" aria-describedby="emailHelp" placeholder="Digite Corretamente">{{ $service->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Url</label>
                                    <input type="text" class="form-control" id="url" name="url" aria-describedby="emailHelp" placeholder="Digite Corretamente" value="{{ $service->url }}">
                                </div>
                                <div class="form-group">
                                    <label for="image">Imagem</label>
                                    <input type="file" class="form-control-file" id="image" name="image">
                                    <img id="showImage" src="{{ asset('caminho/para/sua/pasta/de/imagens/' . $service->image) }}" alt="Preview" style="max-width: 200px; margin-top: 10px;">
                                </div>
                                <button type="submit" class="btn btn-primary">Atualizar Serviço</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seu código JavaScript -->

<script type="text/javascript">
    $(document).ready(function(){
        // Seu código existente para máscaras e pré-visualização de imagens

        // Mostrar pré-visualização da imagem selecionada
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    });
</script>

@endsection
