@extends('users.includes.master')
@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.viewer.css" rel="stylesheet">

@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp


@if($status === 'active')

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Empréstimos</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Solicitação de Empréstimo</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Envio de Documentação</h4>

                            <!-- Seu formulário -->
                            <form method="POST" action="{{ route('lending.update',$lending->id) }}"  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                              <div class="form-group">
                                    <label for="cpf">Seu Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$lending->name}}" required readonly>
                                </div>


                                <div class="form-group">
                                    <label for="cpf">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Informe o CPF" value="{{$lending->cpf}}" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="document">Documento</label>
                                    <input type="file" class="form-control" id="document" name="document" onChange="showThumbnail(this, 'mainThmbDocument')" required>
                                    <img src="" id="mainThmbDocument" />
                                </div>

                                <div class="form-group">
                                    <label for="proof">Comprovante</label>
                                    <input type="file" class="form-control" id="proof" name="proof" onChange="showThumbnail(this, 'mainThmbProof')" required>
                                    <img src="" id="mainThmbProof" />
                                </div>

                                <div class="form-group">
                                    <label for="invoice">Fatura</label>
                                    <input type="file" class="form-control" id="invoice" name="invoice" onChange="showThumbnail(this, 'mainThmbInvoice')" required>
                                    <img src="" id="mainThmbInvoice" />
                                </div>

                                <button type="submit" class="btn btn-primary">Enviar Documentação</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         @else
                @endif
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>

<script type="text/javascript">
   function showThumbnail(input, targetId) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var fileData = e.target.result;
            var fileType = file.type.toLowerCase();

            if (fileType.includes('image/') || fileType === 'application/pdf') {
                if (fileType === 'application/pdf') {
                    var fileReader = new FileReader();

                    fileReader.onload = function() {
                        var typedarray = new Uint8Array(this.result);
                        pdfjsLib.getDocument({ data: typedarray }).promise.then(function(pdf) {
                            pdf.getPage(1).then(function(page) {
                                var scale = 0.5;
                                var viewport = page.getViewport({ scale: scale });

                                var canvas = document.createElement('canvas');
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };

                                page.render(renderContext).promise.then(function() {
                                    var canvasData = canvas.toDataURL('image/png');
                                    $('#' + targetId).attr('src', canvasData);
                                });
                            });
                        });
                    };

                    fileReader.readAsArrayBuffer(file);
                } else {
                    $('#' + targetId).attr('src', fileData);
                }

                $('#' + targetId).show();
            } else {
                $('#' + targetId).hide();
            }
        };

        reader.readAsDataURL(file);
    }
}
</script>




@endsection
