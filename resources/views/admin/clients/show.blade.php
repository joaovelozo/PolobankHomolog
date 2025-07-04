@extends('admin.includes.master')
@section('content')


<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h6 class="mb-1 mt-0">Detalhes do Cliente: <b>{{$imt->name}}</b></h6>
                </div>

                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="badge badge-success float-right" style="font-size: 20px;">{{$imt->name}}</div>
                            <p class="text-success text-uppercase font-size-12 mb-2">Nome do Correntista Solicitante:</p>
                            <p>CPF:</p><h5><a href="#" class="text-dark">{{$imt->document}}</a></h5>
                            <p>Email:</p><h5><a href="#" class="text-dark">{{$imt->email}}</a></h5>
                            <p>Telefone:</p><h5><a href="#" class="text-dark">{{$imt->phone}}</a></h5>
                            <p>Número do Processo:</p><h5><a href="#" class="text-dark">{{$imt->process}}</a></h5>
                            <p>Nome do Advogado:</p><h5><a href="#" class="text-dark">{{$imt->attorney}}</a></h5>
                            <p>OAB do Advogado:</p><h5><a href="#" class="text-dark">{{$imt->number}}</a></h5>
                            <p>Telefone do Advogado:</p><h5><a href="#" class="text-dark">{{$imt->contact}}</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
