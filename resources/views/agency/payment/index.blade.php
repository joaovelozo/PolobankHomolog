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
                            <h4 class="mb-1 mt-0">Pagamentos</h4>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col text-left">
                                    <h5 class="card-title">Pix</h5>
                                    <a href="{{route('payment.pix')}}">
                                        <i class="fa-brands fa-pix"></i> <!-- Ícone de transferir -->
                                        <span>Pix Copia e Cola</span> <!-- Texto abaixo do ícone -->
                                    </a>

                                </div>
                                <div class="col text-left">
                                    <h5 class="card-title">Boleto</h5>
                                    <a href="{{route('payment.boleto')}}">
                                        <i class="fa-solid fa-qrcode"></i> <!-- Ícone de copiar -->
                                        <span>Código de Barras</span> <!-- Texto abaixo do ícone -->
                                    </a>
                                </div>
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
