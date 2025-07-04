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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('useradmininvestment.index') }}">Voltar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Investimentos</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Associar Investimento a Usuário</h4>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">


                            <div class="card-body">
                                <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>
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
                                <form method="POST" action="{{route('useradmininvestment.store')}}">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="user_id">Selecione o Cliente</label>
                                                <select class="form-control" id="user_id" name="user_id" required>
                                                    <option value="">Selecione O Cliente</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="investment_id">Investimento:</label>
                                                <select class="form-control" id="investment_id" name="investment_id">
                                                    <option value="">Selecione Investimento</option>
                                                    @foreach($investments as $investment)
                                                    <option value="{{ $investment->id }}">{{ $investment->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="birthdate">Data de Início</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="birthdate">Data do Vencimento</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" placeholder="Digite Corretamente" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Valor Investido</label>
                                                <input type="text" class="form-control price" id="amount" name="amount" aria-describedby="emailHelp" placeholder="Digite Corretamente">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Inserir Investimento</button>
                                    </div>
                                </form>

                            </div> <!-- end card-body-->

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript">
    //Mascaras
    $(document).ready(function() {
        $('#phone').mask('(00) 00000-0000');
        $('#cpfCnpj').mask('000.000.000-00');
        $('#zipCode').mask('00.000-000');

        $('.price').mask("#.##0,00", {
            reverse: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            ajax: {
                url: '{{ route("useradmininvestment.search") }}', // O endpoint para buscar usuários
                dataType: 'json',
                delay: 250, // Espera antes de enviar a requisição
                data: function (params) {
                    return {
                        q: params.term // O termo de pesquisa inserido pelo usuário
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function(user) {
                            return {
                                id: user.id,
                                text: user.name // O texto que será exibido no select
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Selecione um cliente',
            minimumInputLength: 1 // Começa a buscar a partir de um caractere
        });
    });
</script>
@endsection
