@extends('agency.includes.master')
@section('content')
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
                            <li class="breadcrumb-item active" aria-current="page">Pix</li>
                        </ol>
                    </nav>
                    <div class="row">

                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Transferência Pix</h4>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Digite a chave PIX</h5>
                            <!-- Mensagens de Erro -->
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="pl-2 mb-0">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Mensagem de Sucesso -->
                            @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif

                            <!-- Mensagem de Erro -->
                            @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif
                            <form method="post" action="{{ route('agency.transfer.pix') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Selecione o Tipo de Chave:</label>
                                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                        <label class="btn btn-outline-primary flex-fill">
                                            <input type="radio" name="tipo_chave" value="telefone" autocomplete="off" class="tipo-chave" data-tipo="telefone"> Telefone
                                        </label>
                                        <label class="btn btn-outline-primary flex-fill">
                                            <input type="radio" name="tipo_chave" value="cpf_cnpj" autocomplete="off" class="tipo-chave" data-tipo="cpf_cnpj"> CPF/CNPJ
                                        </label>
                                        <label class="btn btn-outline-primary flex-fill">
                                            <input type="radio" name="tipo_chave" value="email" autocomplete="off" class="tipo-chave" data-tipo="email"> E-mail
                                        </label>
                                        <label class="btn btn-outline-primary flex-fill">
                                            <input type="radio" name="tipo_chave" value="aleatoria" autocomplete="off" class="tipo-chave" data-tipo="aleatoria"> Aleatória
                                        </label>
                                    </div>
                                </div>

                                <!-- Campo de Entrada da Chave Pix -->
                                <div id="campo-chave-pix" style="display: none;">
                                    <div class="form-group">
                                        <label id="label-chave-pix">Digite a chave PIX</label>
                                        <input type="text" name="chave_pix" class="form-control" placeholder="Digite a chave PIX" data-toggle="tooltip" title="Digite Aqui A Chave Pix">
                                    </div>
                                    <div class="form-group">
                                        <label>Valor da Transferência:</label>
                                        <input type="text" name="amount" required class="form-control price" placeholder="Valor" data-toggle="tooltip" title="Digite O Valor Que Deseja Transferir">
                                    </div>
                                </div>
                                <!-- Campo Oculto para Tipo de Chave -->
                                <input type="hidden" name="tipo_chave_selecionada" id="tipo_chave_selecionada">
                                <button type="submit" class="btn btn-primary">Transferir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endsection
    @section('scripts')
    <script type="text/javascript">
        //Mascaras
        $(document).ready(function() {

            $('.price').mask("#.##0,00", {
                reverse: true
            });



            // Evento para seleção de tipo de chave Pix
            $('.tipo-chave').on('change', function() {
                const tipo = $(this).data('tipo');
                const labelMap = {
                    telefone: 'Digite o número de telefone com DDD',
                    cpf_cnpj: 'Digite o CPF ou CNPJ',
                    email: 'Digite o e-mail',
                    aleatoria: 'Digite a chave aleatória'
                };

                // Atualizar o campo com as informações corretas
                $('#campo-chave-pix').show();
                $('#label-chave-pix').text(labelMap[tipo]);
                $('#tipo_chave_selecionada').val(tipo);
            });
        });
    </script>
    @endsection
