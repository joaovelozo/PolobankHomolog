@extends('users.includes.master')
@section('content')





<style>
    .alert-badge {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px;
        background-color: green;
        color: white;
        border-radius: 5px;
        z-index: 1000;
    }
    .btn-custom {
    background-color: #00b81f; /* Cor personalizada */
    color: white; /* Cor do texto */
    /* Outros estilos se necessário */
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
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Documentos</h4>
                    </div>

        </div>

    </div>

    </div>
</div>
<div class="w-full lg:w-2/3 px-4">
    @if(session()->has('message'))
    <div class="alert-badge" id="notification-badge">
        {{ session('message') }}
    </div>
@endif
    <hr>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0 mb-1">Visualização do Documento: {{$docs->id}}</h4>

                </div>
            </div>
        </div>
    </div>
    @if($status === 'active')

    <h4><strong>Descrição</strong></h4>
        <div class="card-body">
            <p class="card-text" style="text-align: justify !important;">{!! $docs->description !!}</p>
        </div>

        <h4><strong>Arquivo</strong></h4>
    <div class="card mb-4 mb-xl-0">
        @if(pathinfo($docs->file, PATHINFO_EXTENSION) === 'pdf')
            <embed src="{{ asset( $docs->file) }}" type="application/pdf" width="25%" height="50%">
            <div class="card-body">
                <a href="{{ asset( $docs->file) }}" class="btn btn-primary" target="_blank">Visualizar</a>
                <a href="{{ asset( $docs->file) }}" class="btn btn-success" download>Baixar</a>
            </div>
        @else
            <img src="{{ asset( $docs->file) }}" alt="Card image cap" width="25%" height="50%">
            <div class="card-body">
                <a href="{{ asset( $docs->file) }}" class="btn btn-primary" target="_blank">Visualizar</a>
                <a href="{{ asset($docs->file) }}" class="btn btn-success" download>Baixar</a>
            </div>
        @endif


    </div>

    </div>
</div>

    @else
    @endif
    <!-- end container-fluid -->
</div>
<!-- end content -->
</div>
        </div>
    </div>

    @endsection
