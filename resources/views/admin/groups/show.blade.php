@extends('admin.includes.master')
@section('content')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h6 class="mb-1 mt-0">Detalhes da Agência: <b>{{$agency->name}}</b></h6>
                </div>

                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('agency.index') }}" class="btn btn-secondary">
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
                            <div class="badge badge-success float-right" style="font-size: 20px;">{{$agency->name}}</div>
                            <p class="text-success text-uppercase font-size-12 mb-2">Nome da Agência:</p>
                            <p>Número:</p><h5><a href="#" class="text-dark">{{$agency->number}}</a></h5>
                            <p>Email:</p><h5><a href="#" class="text-dark">{{$agency->email}}</a></h5>
                            <p>Endereço:</p><h5><a href="#" class="text-dark">{{$agency->address}}</a></h5>
                            <p>Telefone:</p><h5><a href="#" class="text-dark">{{$agency->phone}}</a></h5>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
