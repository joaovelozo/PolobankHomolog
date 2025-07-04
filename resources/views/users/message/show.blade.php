@extends('users.includes.master')
@section('content')

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
                        <h4 class="mb-1 mt-0">Comunicados</h4>
                    </div>
                </div>

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h4 class="mb-1 mt-0">Detalhes do Comunicado</h4>
                </div>
                @if($status === 'active')
                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('comunication.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <p> <img src="{{asset('assets/backend/images/logo.png')}}" class="img-fluid" alt="Logo" width="30px"></p>
                            <div class="badge badge-success float-right">{{$msn->title}}</div>
                            <p class="text-success text-uppercase font-size-12 mb-2">Título</p>
                            <p>Descrição:</p><h5><a href="#" class="text-dark">{!!$msn->description!!}</a></h5>
                            @if ($msn->url)
                            <p>Link:</p>
                            <h5>
                                <a href="{{$msn->url}}" class="btn btn-primary" target="_blank">Abrir Link</a>
                            </h5>
                        @endif




                        </div>
                    </div>
                </div>
                @else
@endif
            </div>
        </div>
    </div>

@endsection
