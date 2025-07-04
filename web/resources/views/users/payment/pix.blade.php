@extends('users.includes.master')
@section('content')
    @php
        $id = Auth::user()->id;
        $clientId = App\Models\User::find($id);
        $status = $clientId->status;
    @endphp
    <div class="content-page">
        <div class="content">
            @if ($status === 'active')
                <!-- Start Content-->
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
                                    <span style="margin-left: 10px;"><b>Pix Cópia e Cola</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Cole o código PIX</h5>
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
                                    <form method="post" action="{{ route('payment.pix.preview') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" name="codigo_pix" required class="form-control"
                                                placeholder="Cole o código PIX" data-toggle="tooltip"
                                                title="Insira aqui o código PIX que você copiou" />
                                        </div>
                                        <button type="submit" class="btn btn-primary">Pagar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <p>Seu status não está ativo.</p>
                    </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        //Mascaras
        $(document).ready(function() {
            $("#cpfCnpj").keydown(function() {
                try {
                    $("#cpfCnpj").unmask();
                } catch (e) {}

                var tamanho = $("#cpfCnpj").val().length;

                if (tamanho < 11) {
                    $("#cpfCnpj").mask("999.999.999-99");
                } else {
                    $("#cpfCnpj").mask("99.999.999/9999-99");
                }

                // ajustando foco
                var elem = this;
                setTimeout(function() {
                    // mudo a posição do seletor
                    elem.selectionStart = elem.selectionEnd = 10000;
                }, 0);
                // reaplico o valor para mudar o foco
                var currentValue = $(this).val();
                $(this).val('');
                $(this).val(currentValue);
            });
        });

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'focus' // O tooltip será mostrado quando o input estiver em foco
            });
        });
    </script>
@endsection
