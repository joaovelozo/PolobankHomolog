@extends('agency.includes.master')
@section('content')
<div class="content-page">
    <!-- Start Content -->
    <div class="container-fluid">
        <div class="row page-title align-items-center">
            <div class="row">

                <div class="col-md-12  align-self-center">
                    <h4 class="mb-1 mt-0">Área Pix</h4>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Pagar</h5>
                    <div class="row">
                        <div class="col text-left">
                            <a href="{{route('agency.payment.pix')}}">
                                <i class="fa-brands fa-pix"></i> <!-- Ícone de transferir -->
                                <span>Pix Copia e Cola</span> <!-- Texto abaixo do ícone -->
                            </a>
                        </div>
                        <div class="col text-left">
                            <a href="{{route('agency.transfer.pix')}}">
                                <i class="fas fa-key"></i>
                                <span>Chave Pix</span>
                            </a>
                        </div>
                        <div class="col text-center">
                            <a href="{{ route('agency.payment.pix.extract') }}">
                                <i class="fa-solid fa-receipt"></i></i> <!-- Ícone de copiar -->
                                <span>Extrato</span> <!-- Texto abaixo do ícone -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Receber</h5>
                    <div class="row">
                        <div class="col text-left">
                            <a href="{{route('agency.qrcode')}}">
                                <i class="fas fa-qrcode"></i> <!-- Ícone de QR CODE -->
                                <span>Criar QR Code</span> <!-- Texto abaixo do ícone -->
                            </a>
                        </div>
                        <div class="col text-left">
                       
                        </div>
                        <div class="col text-center">
                            <a href="{{ route('agency.qrcode.extract') }}">
                                <i class="fa-solid fa-receipt"></i></i> <!-- Ícone de copiar -->
                                <span>Extrato</span> <!-- Texto abaixo do ícone -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection