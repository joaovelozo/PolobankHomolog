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
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Transferências</h4>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-center">Revisão</h5>
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
                            <div class="card-body">
                                <h3 class="card-title text-center">
                                    R$ {{ number_format($amount / 100, 2, ',', '.') }}
                                </h3>
                                <h6 class="text-center">Conta: {{ $receiver->account }}</h6>
                                <h6 class="text-center">Nome: {{ $receiver->name }}</h6>
                            </div>
                            <form method="post" action="{{ route('transfer.accounts.store') }}" class="text-center">
                                @csrf
                                 <input type="hidden" name="account" required value="{{ $receiver->account }}" >
                                 <input type="hidden" name="amount" required value="{{ number_format($amount / 100, 2, ',', '.') }}">
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
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'focus' // O tooltip será mostrado quando o input estiver em foco
            });
        });
    </script>
    @endsection