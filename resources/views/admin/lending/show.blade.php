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
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Empréstimos</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Responder Solicitação</h4>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        @if (session()->has('message'))
                            <div class="alert-badge" id="notification-badge">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form method="post" action="{{ route('loan.respond_to_lending', ['id' => $lending->id]) }}">
                            @csrf

                            <div class="form-group">
                                <label for="message">Mensagem para o usuário:</label>
                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" name="status" required>
                                    <option value="em analise">Em Análise</option>
                                    <option value="assinar contrato">Assinar Contrato</option>
                                    <option value="enviar documentos">Enviar Documentos</option>
                                    <option value="aprovado">Aprovado</option>
                                    <option value="negado">Rejeitado</option>
                                </select>
                            </div>

                            <div class="relative rounded-lg bg-white shadow-md">
                                <label for="address"
                                    class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Link
                                    de Assinatura do Contrato</label>
                                <input id="address"
                                    class="form-control w-full py-3 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                                    type="text" placeholder="" name="url" required>
                            </div>
                            <br>
                            <br>

                            <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #00b81f; color: white;">Responder Solicitação</button>
                            </div>
                        </form>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end container-fluid -->
        </div> <!-- end content -->
    </div> <!-- end content-page -->
@endsection
