@extends('admin.includes.master')
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Contratos</h4>
                    </div>
                </div>
                <div class="col-md-9 col-xl-6 text-md-right">
                        <div class="mt-4 mt-md-0">
                            <a href="{{ route('contracts.index') }}" class="btn btn-custom btn-sm mr-4 mb-3 mb-sm-0">
                                <i data-feather="arrow-left" mr-1></i>Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-2/3 px-4">
                    @if (session()->has('message'))
                        <div class="alert-badge" id="notification-badge">
                            {{ session('message') }}
                        </div>
                    @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mt-0 mb-1">Gestão de Contratos</h4>


                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mt-0 header-title">{{$ctr->title}}</h6>

                                            <div class="text-muted mt-3">
                                                <p>{!!$ctr->content!!}</p>


                                                <div class="tags">

                                                </div>


                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
                            </div>
                        </div>
                    </div>
                </div>
<!-- end content-page -->
@endsection
