@extends('admin.includes.master')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script src="https://cdn.tiny.cloud/1/800zai2qbtsvy9kjn4fff6h4ihrv5i0qgpsn461vdazmbxtn/tinymce/6/tinymce.min.js"
referrerpolicy="origin"></script>
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
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('service.index') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Serviços</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Cadastro de Documentos</h4>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                                <form method="POST" action="{{ route('docs.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="client_id">Usuário</label>
                                        <select class="form-control custom-select" name="client_id" id="client_id">
                                            @foreach($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Descrição (Opcional)</label>
                                        <textarea rows="5" class="form-control" id="description" name="description" aria-describedby="emailHelp"
                                            placeholder="Digite Corretamente"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Upload de Arquivo (PDF, PNG, JPG, JPEG, GIF)</label>
                                        <input type="file" class="form-control-file" id="image" name="file">
                                        <img id="showImage" src="#" alt="Preview" style="max-width: 200px; margin-top: 10px; display: none;" accept=".pdf,.png,.jpg,.jpeg,.gif">
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Enviar Documento</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <!-- Seu código JavaScript -->
    <script type="text/javascript">
       $(document).ready(function() {
    // Seu código existente para máscaras

    // Mostrar pré-visualização da imagem selecionada ou do PDF
    $('#image').change(function(e) {
        var file = e.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var extension = file.name.split('.').pop().toLowerCase();

            if (extension === 'pdf') {
                // Renderizar a primeira página do PDF usando PDF.js
                PDFJS.getDocument(e.target.result).promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        var viewport = page.getViewport({ scale: 0.5 });
                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };

                        page.render(renderContext).promise.then(function() {
                            var dataURL = canvas.toDataURL();
                            $('#showImage').attr('src', dataURL).show();
                        });
                    });
                });
            } else {
                // Se não for um PDF, exibir como imagem
                $('#showImage').attr('src', e.target.result).show();
            }
        };

        reader.readAsDataURL(file);
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
