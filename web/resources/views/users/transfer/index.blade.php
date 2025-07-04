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

        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">

                    <div class="col-md-12 align-self-center">
                        <h4 class="mb-1 mt-0">Transferências Entre Contas</h4>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Transferência entre contas</h5>
                        <p>Envie pagamentos para contas Polocal Bank</p>
                        <div class="row">
                            <div class="col text-left">
                                <a href="{{route('transfer.accounts')}}">
                                    <i class="fa-brands fa-pix"></i> <!-- Ícone de transferir -->
                                    <span>Transferência interna entre contas</span> <!-- Texto abaixo do ícone -->
                                </a>
                            </div>
                            <div class="col text-center">
                                <a href="{{ route('transfer.extract') }}">
                                    <i class="fa-solid fa-receipt"></i></i> <!-- Ícone de copiar -->
                                    <span>Extrato</span> <!-- Texto abaixo do ícone -->
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        @endif
    </div>
</div>
@endsection
