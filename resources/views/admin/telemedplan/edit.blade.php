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
                                <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">Voltar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Planos</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Edição de Planos de Telemedicina</h4>
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

                                    <form method="POST" action="{{ route('ateledicine.update', $tele->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Título</label>
                                            <input type="text" class="form-control" id="title"
                                                name="title" aria-describedby="emailHelp"
                                                placeholder="Digite Corretamente" value="{{$tele->title}}">

                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Descrição</label>
                                            <textarea rows="5" class="form-control" id="description" name="description" aria-describedby="emailHelp"
                                                placeholder="Digite Corretamente">{{$tele->description}}</textarea>
                                        </div>



                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Período</b> </label>
                                            <input type="text" class="form-control" id="period"
                                                name="period" aria-describedby="emailHelp"
                                                placeholder="Digite Corretamente" value="{{$tele->period}}">



                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><b>Mensalidade</b> </label>
                                                <input type="text" class="form-control" id="amount"
                                                    name="amount" aria-describedby="emailHelp"
                                                    placeholder="Digite Corretamente" value="{{$tele->amount}}">

                                            </div>

                                            <div class="text-right">
                                                <!-- Adicione esta classe para alinhar à direita -->
                                                <button type="submit" class="btn btn-primary"
                                                    style="background-color: #00b81f; color: white;">Atualizar
                                                    Plano</button>
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

@endsection
