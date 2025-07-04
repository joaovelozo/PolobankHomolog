@extends('users.includes.master')
@section('content')

<style>

  .cartao-mockup {
        background-image: url('{{ asset("assets/backend/card.png") }}');
        background-size: 540px 344px; /* Define as dimensões da imagem */
        background-position: center; /* Para centralizar a imagem */
        padding: 20px; /* Adicione espaço interno para os dados do cartão */
        width: 540px; /* Largura da div igual à largura da imagem */
        height: 360px; /* Altura da div igual à altura da imagem */
    }
    .dados-cartao p {
        margin-left: 40px !important; /* Adiciona uma margem esquerda de 20 pixels */
    }
    .dados-cartao p strong {
  width: 100% !important;
  display: inline-block;
}
.dados-cartao h1 {
    margin-left: 40px
}


</style>

@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp
<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        @if($status === 'active')
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Meus Cartões</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="cartao-mockup">

        <div class="dados-cartao">
            <br>
            <br>
            <br>
            <br>
            <br>

            <br>
            <br>



            <div class="dados-cartao">
                <h1><strong>{{ $card->number }}</strong></h1>
                <p>Titular: <b>{{ $card->user->name }}</b></p>
                <p>Validade: <b>{{ $card->validate }}</b> </p>
                <p>Tipo: <b>{{$card->type}}</b>           CVV: {{ $card->cvv }}</p>
                <!-- Adicione mais campos conforme necessário -->
            </div>
            <!-- Adicione mais campos conforme necessário -->
        </div>
    </div>
        @else
@endif
    </div>
</div>
@endsection
