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
                                <li class="breadcrumb-item active" aria-current="page">Administradores</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Inserir Saldo</h4>
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
                                <div class="pb-8 mb-8 border-b border-gray-400">

                                </div>
                                <div class="flex flex-wrap justify-between -mx-4 mb-10">
                                    <div class="w-full md:w-1/2 px-4 mb-10 md:mb-0">
                                        <div class="max-w-xs">
                                            <h4 class="text-gray-50 leading-6 font-bold">Adicionar Saldo para Usuários</h4>
                                            <p class="text-xs text-gray-300 leading-normal font-medium mb-4">Selecione um
                                                usuário e insira os valores sem pontuação e clique em enviar saldo.</p>

                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 px-4">

                                        <div class="row justify-content-center">
                                            <form method="POST" action="/admin/add-balance">
                                                @csrf
                                                <div class="grid grid-cols-1 gap-y-4">

                                                    <div class="relative rounded-lg bg-white shadow-md">
                                                        <label for="user_id"
                                                            class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Selecione
                                                            o cliente</label>
                                                        <select id="user_id" name="user_id"
                                                            class="custom-select custom-select-lg mb-2">
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>



                                                    </div>
                                                    <div class="relative rounded-lg bg-white shadow-md">
                                                        <label for="name"
                                                            class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Valor</label>
                                                        <input id="name"
                                                            class="form-control w-full py-3 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                                                            type="text" placeholder="" name="amount" required>
                                                    </div>

                                                    <br>
                                                    <br>

                                                    <div class="text-right">
                                                        <!-- Adicione esta classe para alinhar à direita -->
                                                        <button type="submit" class="btn btn-primary"
                                                            style="background-color: #00b81f; color: white;">Inserir
                                                            Saldo</button>
                                                    </div>
                                                    <br>
                                                    <br>
                                                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/charts-demo.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#phone').mask('(00) 00000-0000');
            $('#whatsapp').mask('(00) 00000-0000');
            $('#cep').mask('00.000-000');
            $('input[name="amount"]').mask('#.##0,00', {
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
                event.preventDefault(); // Previne o envio do formulário para a ação padrão

                var unformattedValue = $('input[name="amount"]').val().replace(/\D/g,
                ''); // Remove todos os caracteres não numéricos
                var floatValue = parseFloat(unformattedValue) / 100; // Converte para valor decimal

                // Formata o valor para conter apenas duas casas decimais
                floatValue = floatValue.toFixed(2);

                // Defina o valor formatado no campo de entrada 'amount'
                $('input[name="amount"]').val(floatValue);

                // Envie o formulário
                $(this).unbind('submit').submit();
            });
        });
    </script>
@endsection
