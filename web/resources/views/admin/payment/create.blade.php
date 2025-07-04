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

<script src="https://cdn.tiny.cloud/1/800zai2qbtsvy9kjn4fff6h4ihrv5i0qgpsn461vdazmbxtn/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>


<!-- Adicionar CSS e JS do Tagify -->
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adpayment.index') }}">Voltar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Split de Pagamento</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Cadastro de Pagamentos</h4>
                </div>
            </div>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body">
                                <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>
                                <form method="POST" action="{{ route('adpayment.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">Título</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Digite Corretamente" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Valor da Transferência</label>
                                        <input type="text" class="form-control price" id="amount" name="amount" placeholder="Digite Corretamente" value="{{ old('amount') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_id">Selecione o Usuário</label>
                                        <select class="form-control" id="user_id" name="user_id" required>
                                            <option value="">Selecione um usuário</option>
                                        </select>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Criar Pagamento</button>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#phone').mask('(00) 00000-0000');
        $('#whatsapp').mask('(00) 00000-0000');
        $('#cep').mask('00.000-000');

        $('.price').mask("#.##0,00", {
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
    });
</script>

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.querySelector('#advantages'); // Seleciona o campo de vantagens
        new Tagify(input); // Inicializa o Tagify
    });
</script>
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            ajax: {
                url: '{{ route("users.search") }}', // O endpoint para buscar usuários
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
            placeholder: 'Selecione um usuário',
            minimumInputLength: 1 // Começa a buscar a partir de um caractere
        });
    });
</script>
@endsection
