@extends('agency.includes.master')
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
                            <div class="pb-8 mb-8 border-b border-gray-400">

                            </div>
                            <div class="flex flex-wrap justify-between -mx-4 mb-10">
                                <div class="w-full md:w-1/2 px-4 mb-10 md:mb-0">
                                    <div class="max-w-xs">
                                        <h4 class="text-gray-50 leading-6 font-bold">Debitar Saldo do Usuário</h4>
                                        <p class="text-xs text-gray-300 leading-normal font-medium mb-4">Selecione um
                                            usuário e insira os valores sem pontuação e clique em enviar saldo.</p>

                                    </div>
                                </div>


                                <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 400px;"> <!-- Define uma altura mínima para o card -->
                                    <div class="card" style="width: 100%; max-width: 500px;"> <!-- Limita a largura do card -->
                                        <form method="POST" action="{{ route('agency.debit') }}">
                                            @csrf
                                            <div class="relative rounded-lg bg-white shadow-md">
                                                <label for="user_id"
                                                    class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Selecione
                                                    o cliente</label>
                                                <div class="form-group">
                                                    <label for="user_id">Selecione o Usuário</label>
                                                    <select class="form-control" id="user_id" name="user_id" required>
                                                        <option value="">Selecione um usuário</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="relative rounded-lg bg-white shadow-md">
                                                <label for="user_id"
                                                    class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Tipo de
                                                    Transação</label>
                                                <select id="type_id" name="type_id" class="custom-select custom-select-lg mb-2" required>
                                                    @foreach ($types as $type)
                                                    @if ($type->name !== 'Pix' && $type->name !== 'TED')
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Digite o Valor</label>
                                                <input type="text" class="form-control" id="amount"
                                                    name="amount" placeholder="Informe o valor" required>
                                            </div>
                                            <br>
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary"
                                                    style="background-color: #00b81f; color: white;">Debitar
                                                    Saldo</button>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        if (document.getElementById('notification-badge')) {
            setTimeout(() => {
                document.getElementById('notification-badge').style.display = 'none';
            }, 5000);
        }
    });
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="js/charts-demo.js"></script>
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

        $('.price').mask("#.##0,00", {
            reverse: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            ajax: {
                url: '{{ route("agency.users.search") }}', // O endpoint para buscar usuários
                dataType: 'json',
                delay: 250, // Espera antes de enviar a requisição
                data: function(params) {
                    return {
                        q: params.term // O termo de pesquisa inserido pelo usuário
                    };
                },
                processResults: function(data) {
                    var results = [{
                            id: 'all',
                            text: 'Debitar Todos os Clientes'
                        } // Adiciona a opção "Todos os Clientes"
                    ];

                    // Mapeia os outros usuários da pesquisa
                    results = results.concat($.map(data, function(user) {
                        return {
                            id: user.id,
                            text: user.name // O texto que será exibido no select
                        };
                    }));

                    return {
                        results: results
                    };
                },
                cache: true
            },
            placeholder: 'Selecione um usuário',
            minimumInputLength: 1 // Começa a buscar a partir de um caractere
        });
    });
</script>
@endsection
