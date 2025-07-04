@extends('users.includes.master')

@section('content')
    @php
        $id = Auth::user()->id;
        $clientId = App\Models\User::find($id);
        $status = $clientId->status;
    @endphp

    @section('styles')
<style>
  #toast-container > .toast {
    width: 600px !important;
    font-size: 20px !important;
    padding: 15px 20px !important;
  }
</style>
@endsection

    <div class="content-page">
        <div class="content">
            @if ($status === 'active')
                <div class="container-fluid">
                    <div class="row page-title">
                        <div class="col-md-12">
                            <nav aria-label="breadcrumb" class="float-right mt-1">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pix</li>
                                </ol>
                            </nav>
                            <div class="row">
                                <div class="col-md-3 col-xl-2 d-flex align-items-center">
                                    <span style="margin-left: 10px;"><b>Transfêrencia Pix</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Transferência com Chave Pix</h5>

                                    <form method="POST" action="{{ route('transfer.pix.preview') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label>Selecione o Tipo de Chave:</label>
                                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                                <label class="btn btn-outline-primary flex-fill">
                                                    <input type="radio" name="tipo_chave" value="2"
                                                        autocomplete="off" class="tipo-chave" data-tipo="telefone"> Telefone
                                                </label>
                                                <label class="btn btn-outline-primary flex-fill">
                                                    <input type="radio" name="tipo_chave" value="1"
                                                        autocomplete="off" class="tipo-chave" data-tipo="cpf_cnpj"> CPF/CNPJ
                                                </label>
                                                <label class="btn btn-outline-primary flex-fill">
                                                    <input type="radio" name="tipo_chave" value="3"
                                                        autocomplete="off" class="tipo-chave" data-tipo="email"> E-mail
                                                </label>
                                                <label class="btn btn-outline-primary flex-fill">
                                                    <input type="radio" name="tipo_chave" value="4"
                                                        autocomplete="off" class="tipo-chave" data-tipo="aleatoria">
                                                    Aleatória
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label id="label-chave-pix">Digite a chave PIX</label>
                                            <input type="text" name="chave_pix" class="form-control"
                                                placeholder="Digite a chave PIX" required data-toggle="tooltip"
                                                title="Digite Aqui A Chave Pix">
                                        </div>

                                        <div class="form-group">
                                            <label>Valor da Transferência:</label>
                                            <input type="text" name="amount" required class="form-control price"
                                                placeholder="Valor" data-toggle="tooltip"
                                                title="Digite o valor da transferência">
                                        </div>

                                        <div class="form-group">
                                            <label>Nome do Favorecido:</label>
                                            <input type="text" name="nome" required class="form-control"
                                                placeholder="Nome completo do favorecido" data-toggle="tooltip"
                                                title="Nome completo de quem vai receber">
                                        </div>

                                        <div class="form-group">
                                            <label>CPF ou CNPJ do Favorecido:</label>
                                            <input type="text" name="cpfcnpj" required class="form-control"
                                                placeholder="Digite o CPF ou CNPJ" data-toggle="tooltip"
                                                title="CPF ou CNPJ de quem vai receber">
                                        </div>

                                        <input type="hidden" name="tipo_chave_selecionada" id="tipo_chave_selecionada"
                                            value="">

                                        <button type="submit" class="btn btn-primary">Verificar Transferência</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <p>Sua conta esta inativa, <b>"Procure seu Gerente".</p>
                    </div>
            @endif
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.price').mask("#.##0,00", {
                reverse: true
            });

            $('.tipo-chave').on('change', function() {
                const tipo = $(this).val();
                const labelMap = {
                    '2': 'Digite o número de telefone com DDD',
                    '1': 'Digite o CPF ou CNPJ',
                    '3': 'Digite o e-mail',
                    '4': 'Digite a chave aleatória'
                };

                $('#label-chave-pix').text(labelMap[tipo]);
                $('#tipo_chave_selecionada').val(tipo);
            });

            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'focus'
            });
        });
    </script>
    <script>
        $(function() {
            @if (session('message'))
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 11000
                };
                toastr["{{ session('alert-type', 'info') }}"]({!! json_encode(session('message')) !!});
            @endif
        });
    </script>
@endsection
