@extends('admin.includes.master')
@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Documentação</li>
                            </ol>
                        </nav>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center mt-3">
                                            <div class="col-auto">
                                                <!-- Exibe o avatar do usuário associado ao empréstimo ou a imagem padrão -->
                                                @if($lending->user)
                                                    <img src="{{ $lending->user->avatar ? asset('uploads/user_images/' . $lending->user->avatar) : asset('uploads/noimage.png') }}" class="avatar-lg rounded-circle" alt="Avatar do usuário">
                                                @else
                                                    <img src="{{ asset('uploads/noimage.png') }}" class="avatar-lg rounded-circle" alt="Imagem padrão">
                                                @endif
                                            </div>
                                            <h5 class="mt-2 mb-0">{{$lending->user->name}}</h5>
                                            <h6 class="text-muted font-weight-normal mt-2 mb-0"><span class="badge badge-danger">{{$lending->status}}</span></h6>
                                            <h6 class="text-muted font-weight-normal mt-1 mb-4"><span class="badge badge-success">{{$lending->created_at}}</span></h6>


                                        </div>

                                        <!-- profile  -->



                                    </div>
                                </div>
                                <!-- end card -->

                            </div>

                            <div class="col-lg-9">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-pills navtab-bg nav-justified" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-files-tab" data-toggle="pill" href="#pills-files" role="tab"
                                                    aria-controls="pills-files" aria-selected="false">
                                                    <b>Informações do Solicitantes</b>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="card mt-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Informações do Solicitante</h5>
                                                <p class="card-text"><strong>Email:</strong> {{ $lending->email }}</p>
                                                <p class="card-text"><strong>CPF:</strong> {{ $lending->cpf }}</p>
                                                <p class="card-text"><strong>Telefone:</strong> {{ $lending->phone }}</p>
                                                <p class="card-text"><strong>Valor Solicitado:</strong>   R${{ number_format($lending->amount, 2, ',', '.') }}</p>
                                                <p class="card-text"><strong>Parcelas:</strong> {{ $lending->installments }}</p>

                                            </div>
                                        </div>
                                    </div>




                                                <div class="card mb-2 shadow-none border">
                                                    <div class="p-1 px-2">
                                                        <div class="row align-items-center">

                            <div class="col-lg-9">
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="nav nav-pills navtab-bg nav-justified" id="pills-tab" role="tablist">

                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-files-tab" data-toggle="pill"
                                                    href="#pills-files" role="tab" aria-controls="pills-files"
                                                    aria-selected="false">
                                                    Imagens
                                                </a>
                                            </li>
                                        </ul>



                                                <div class="card mb-2 shadow-none border">
                                                    <div class="p-1 px-2">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <!-- Exibe a miniatura -->
                                                                @if (pathinfo($lending->document, PATHINFO_EXTENSION) === 'pdf')
                                                                <i class="fas fa-file-pdf fa-3x text-danger"></i> <!-- Ícone para PDF -->
                                                            @else
                                                                <img src="{{ asset('documents/' . $lending->document) }}" class="avatar-sm rounded" alt="file-image">
                                                            @endif
                                                            </div>
                                                            <div class="col pl-0">
                                                                <a href="javascript:void(0);"
                                                                    class="text-muted font-weight-bold">Documento 1</a>

                                                            </div>
                                                            <div class="col-auto">
                                                                <!-- Botão de Download -->
                                                                <a href="{{ asset('documents/' . $lending->document) }}" download="{{ $lending->document }}" class="btn btn-link text-muted btn-lg p-0">
                                                                    <i class='uil uil-cloud-download font-size-14'></i> Baixar
                                                                </a>

                                                                <!-- Botão de Visualização -->
                                                                <a href="javascript:void(0);" onclick="handlePreview('{{ asset('documents/' . $lending->document) }}')" class="btn btn-link text-muted btn-lg p-0">
                                                                    <i class='uil uil-image font-size-14'></i> Visualizar
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card mb-2 shadow-none border">
                                                    <div class="p-1 px-2">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <!-- Exibe a miniatura ou ícone do arquivo -->
                                                                @if (pathinfo($lending->proof, PATHINFO_EXTENSION) === 'pdf')
                                                                    <i class="fas fa-file-pdf fa-3x text-danger"></i> <!-- Ícone para PDF -->
                                                                @else
                                                                    <img src="{{ asset('proofs/' . $lending->proof) }}" class="avatar-sm rounded" alt="file-image">
                                                                @endif
                                                            </div>
                                                            <div class="col pl-0">
                                                                <a href="javascript:void(0);"
                                                                    class="text-muted font-weight-bold">Documento 2</a>

                                                            </div>
                                                            <div class="col-auto">
                                                                   <!-- Botão de Download -->
                                                                   <a href="{{ asset('proofs/' . $lending->proof) }}" download="{{ $lending->proof }}" class="btn btn-link text-muted btn-lg p-0">
                                                                    <i class='uil uil-cloud-download font-size-14'></i> Baixar
                                                                </a>

                                                                <!-- Botão de Visualização -->
                                                                <a href="javascript:void(0);" onclick="handlePreview('{{ asset('proofs/' . $lending->proof) }}')" class="btn btn-link text-muted btn-lg p-0">
                                                                    <i class='uil uil-image font-size-14'></i> Visualizar
                                                                </a>



                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card mb-2 shadow-none border">
                                                        <div class="p-1 px-2">
                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <!-- Exibe a miniatura ou ícone do arquivo -->
                                                                    @if (pathinfo($lending->invoice, PATHINFO_EXTENSION) === 'pdf')
                                                                        <i class="fas fa-file-pdf fa-3x text-danger"></i> <!-- Ícone para PDF -->
                                                                    @else
                                                                        <img src="{{ asset('invoices/' . $lending->invoice) }}" class="avatar-sm rounded" alt="file-image">
                                                                    @endif
                                                                </div>
                                                                <div class="col pl-0">
                                                                    <a href="javascript:void(0);"
                                                                        class="text-muted font-weight-bold">Documento 3</a>

                                                                </div>
                                                                <div class="col-auto">
                                                                      <!-- Botão de Download -->
                                                                      <a href="{{ asset('invoices/' . $lending->invoice) }}" download="{{ $lending->invoice }}" class="btn btn-link text-muted btn-lg p-0">
                                                                        <i class='uil uil-cloud-download font-size-14'></i> Baixar
                                                                    </a>

                                                                    <!-- Botão de Visualização -->
                                                                    <a href="javascript:void(0);" onclick="handlePreview('{{ asset('invoices/' . $lending->invoice) }}')" class="btn btn-link text-muted btn-lg p-0">
                                                                        <i class='uil uil-image font-size-14'></i> Visualizar
                                                                    </a>


                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <!-- end attachments -->
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- end row -->
                    </div> <!-- container-fluid -->

                </div> <!-- content -->
                <script>
                    function handleDownload(event) {
                        event.preventDefault(); // Impede o comportamento padrão do link de download
                        var downloadLink = event.currentTarget.href;
                        var fileName = event.currentTarget.download;
                        var link = document.createElement('a');
                        link.href = downloadLink;
                        link.download = fileName;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }

                    function handlePreview(imageURL) {
        window.open(imageURL, '_blank');
    }
                </script>



            @endsection
