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
                        <div class="col-md-3 col-xl-2 d-flex align-items-center">
                            <span style="margin-left: 20px;">Pix Cópia e Col</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-center">PIX</h5>
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
                            <div class="card-body text-center">
                                <h3 class="card-title text-center">
                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </h3>
                                <h4 class="text-center">{{ $transaction->name }}</h4>
                                <p class="text-center">Data: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                                <small>Status</small><br/>
                                <span class="badge badge-secondary ">{{ $transaction->getStatusDescription() }}</span>
                                <br/>
                                <small>Operação</small><br/>
                                <span class="badge badge-info ">{{ $transaction->operacao }}</span>
                                <p class="mt-4">Sobre a Transação</p>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><small>#TRANSACTION ID: {{ $transaction->id }}</small></li>
                                    <li class="list-group-item"><small>#ID: {{ $transaction->token }}</small></li>
                                </ul>
                            </div>
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
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'focus' // O tooltip será mostrado quando o input estiver em foco
            });
        });
    </script>
    @endsection
