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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script src="https://cdn.tiny.cloud/1/800zai2qbtsvy9kjn4fff6h4ihrv5i0qgpsn461vdazmbxtn/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.admin.dashboard')}}">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('loan.index')}}">Voltar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Empréstimos</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Edição de Empréstimo</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
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
                            <form method="POST" action="{{ route('admininvestment.update', $ivs->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="type_id">Tipo:</label>
                                    <select class="form-control" id="type_id" name="type_id">
                                        <option value="">Selecione O Tipo</option>
                                        @foreach($typs as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $ivs->type_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="exampleInputEmail1">Título</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{$ivs->title}}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Conteúdo</label>
                                    <textarea rows="5" class="form-control" id="description" name="description" aria-describedby="emailHelp" placeholder="Digite Corretamente">{{$ivs->description}}</textarea>

                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><b>Retorno % (mês)</b> </label>
                                    <input type="text" class="form-control" id="performance" name="performance" value="{{$ivs->performance}}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><b>Taxa de Administração %</b> </label>
                                    <input type="text" class="form-control" id="tax" name="tax" value="{{$ivs->tax}}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><b>Prazo Mínimo</b> </label>
                                    <input type="text" class="form-control" id="term" name="term" value="{{$ivs->term}}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><b>Investimento Mínimo</b> </label>
                                    <input type="text" class="form-control price" id="amount" name="amount" value="{{$ivs->amount}}" aria-describedby="emailHelp" placeholder="Digite Corretamente">

                                </div>

                                <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                                    <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Atualizar Investimento</button>
                                </div>

                            </form>

                        </div>

                    </div> <!-- end card-->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- end col -->
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


@endsection
