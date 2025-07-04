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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Importe a biblioteca de máscara -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>


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
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Simulação de Débito</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Debitar Saldo</h4>
                    </div>
                </div>
                <div class="w-full lg:w-2/3 px-4">
                    @if (session()->has('message'))
                        <div class="alert-badge" id="notification-badge">
                            {{ session('message') }}
                        </div>
                    @endif


                    <section class="py-8">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                                <div class="flex flex-wrap justify-between -mx-4 mb-10">
                                    <div class="w-full md:w-1/2 px-4 mb-10 md:mb-0">
                                        <div class="max-w-xs">
                                            <h4 class="text-gray-50 leading-6 font-bold">Debitar Saldo do Usuário</h4>
                                            <p class="text-xs text-gray-300 leading-normal font-medium mb-4">Selecione um
                                                usuário e insira os valores sem pontuação e clique em enviar saldo.</p>

                                        </div>
                                    </div>


                                        <div class="row justify-content-center">
                                            <form method="POST" action="{{ route('admin.debit') }}">
                                                @csrf
                                                <div class="grid grid-cols-1 gap-y-4">
                                                    <div class="relative rounded-lg bg-white shadow-md">

                                                            <div class="grid grid-cols-1 gap-y-4">
                                                                <div class="relative rounded-lg bg-white shadow-md">
                                                                    <label for="user_id"
                                                                        class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Selecione
                                                                        o cliente</label>
                                                                        <select id="user_id" name="user_id" class="custom-select custom-select-lg mb-2">
                                                                            <option value="">Escolha Uma Opção</option>
                                                                            <option value="all"><b>Debitar Todos os Clientes</b></option>
                                                                            @foreach ($users as $user)
                                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                </div>
                                                    </div>

                                                    <div class="grid grid-cols-1 gap-y-4">
                                                        <div class="relative rounded-lg bg-white shadow-md">
                                                            <label for="user_id"
                                                                class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Tipo de
                                                                Transação</label>
                                                                <select id="type_id" name="type_id" class="custom-select custom-select-lg mb-2">
                                                                    @foreach ($types as $type)
                                                                        @if ($type->name !== 'Pix' && $type->name !== 'TED')
                                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                        </div>

                                                    <div class="form-group">
                                                        <label for="amount">Digite o Valor</label>
                                                        <input type="text" class="form-control" id="amount" name="amount"
                                                            placeholder="Informe o valor" required>
                                                    </div>

                                                    <br>
                                                    <div class="text-right mt-5">
                                                        <button type="submit" class="btn btn-primary"
                                                            style="background-color: #00b81f; color: white;">Debitar Saldo</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <br>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('notification-badge')) {
                setTimeout(() => {
                    document.getElementById('notification-badge').style.display = 'none';
                }, 5000);
            }
        });
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#phone').mask('(00) 00000-0000');
        $('#whatsapp').mask('(00) 00000-0000');
        $('#cep').mask('00.000-000');
        $('#amount').mask('#.##0,00', {
            reverse: true
        });

        $('#cpfCnpj').focusout(function() {
            var value = $(this).val().replace(/\D/g, '');

            if (value.length === 11) {
                $(this).mask('000.000.000-00');
            } else if (value.length === 14) {
                $(this).mask('00.000.000/0000-00');
            } else {
                $(this).val('');
            }
        });
        $('form').submit(function(event) {
            event.preventDefault(); // Previne o envio do formulÃ¡rio para a aÃ§Ã£o padrÃ£o

            var amountField = $('#amount');
            var unmaskedValue = amountField.cleanVal();
            var floatValue = parseFloat(unmaskedValue) / 100; // Converta para valor decimal

            // Defina o valor do campo como o valor decimal correto
            amountField.val(floatValue);

            // Envie o formulÃ¡rio
            $(this).unbind('submit').submit();
        });
    });
</script>
@endsection
