@extends('users.includes.master')
@section('content')
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
                            <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pagamentos</li>
                        </ol>
                    </nav>
                    <div class="row">
                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Pagamentos de Boletos</h4>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Digite o Código de Barras</h5>
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

                            <form method="post" action="{{ route('payment.boleto.store') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="codigo_barra" required class="form-control" placeholder="Código de Barras">
                                </div>
                                <button type="submit" class="btn btn-primary">Efetuar Pagamento</button>
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
