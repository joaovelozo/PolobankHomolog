@extends('users.includes.master')
@section('content')
    @php
        $id = Auth::user()->id;
        $clientId = App\Models\User::find($id);
        $status = $clientId->status;
    @endphp
    <div class="content-page">
        @if ($status === 'active')
            <!-- Start Content -->
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="row">
                        <div class="col-md-12 col-xl-12 d-flex align-items-center">
                            <span style="margin-left: 10px;"><b>Área Pix</b></span>
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
                                    <a href="{{ route('transfer.pix') }}" data-toggle="tooltip"
                                        title="Pagar usando Chave Pix">
                                        <i class="fas fa-key"></i>
                                        <span>Transferir Chave Pix</span>
                                    </a>
                                </div>
                                <div class="col text-center">
                                    <a href="{{ route('payment.pix.extract') }}" data-toggle="tooltip"
                                        title="Ver Extrato de Pagamentos">
                                        <i class="fa-solid fa-receipt"></i> <!-- Ícone de copiar -->
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
                                    <a href="{{ route('pix.qrcode') }}" data-toggle="tooltip"
                                        title="Criar um QR Code para receber pagamentos">
                                        <i class="fas fa-qrcode"></i> <!-- Ícone de QR CODE -->
                                        <span>Criar QR Code</span> <!-- Texto abaixo do ícone -->
                                    </a>
                                </div>
                                <div class="col text-left">
                                    <!-- <a href="{{ route('key.index') }}">
                                            <i class="fa-solid fa-key"></i>
                                            <span>Minhas Chaves</span>
                                    </a> -->
                                </div>
                                <div class="col text-center">
                                    <a href="{{ route('pix.extract') }}" data-toggle="tooltip"
                                        title="Ver Extrato de Recebimentos">
                                        <i class="fa-solid fa-receipt"></i> <!-- Ícone de copiar -->
                                        <span>Extrato</span> <!-- Texto abaixo do ícone -->
                                    </a>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger: 'click' // Aciona o tooltip com um clique/tap
                    });
                });
            </script>
        @else
        @endif
    </div>
@endsection
