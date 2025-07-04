@extends('users.includes.master')
@section('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Importe a biblioteca de máscara -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp

<div class="content-page">
    <div class="content">
        @if($status === 'active')
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('userinvestment.index')}}">Voltar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Investimentos</li>
                        </ol>
                    </nav>
                    <div class="row">
                        <div class="col-md-3 col-xl-2">
                            <h4 class="mb-1 mt-0 align-self-center">Investimentos</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
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
                            <form method="POST" action="{{ route('userinvestment.store') }}">
                                @csrf

                                <label for="investment_amount">Selecione Um Investimento:</label>
                                <div class="form-group">
                                    <select name="investment_id" class="form-control" required>
                                        @foreach($ivs as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="investment_amount">Valor Que Quer Investir:</label>
                                    <input type="text" class="form-control price" id="amount" name="amount" required placeholder="Digite Corretamente">
                                </div>

                                <button type="submit" class="btn btn-primary">Fazer Investimento</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body-->
            @else
            <!-- Mensagem de conta inativa -->
            @endif
        </div> <!-- end card -->
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
    @endsection
