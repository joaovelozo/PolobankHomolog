@extends('admin.includes.master')
@section('content')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h4 class="mb-1 mt-0">Detalhes do Empréstimo</h4>
                </div>

                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('plans.index') }}" class="btn btn-secondary">
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
                            <div class="badge badge-success float-right">{{$pls->title}}</div>
                            <p class="text-success text-uppercase font-size-12 mb-2">Título</p>
                            <p>Descrição:</p><h5><a href="#" class="text-dark">{{$pls->description}}</a></h5>
                            <p>Periodo:</p><h5><a href="#" class="text-dark">{{$pls->period}}</a></h5>
                            <p>Valor:</p><h5><a href="#" class="text-dark">R${{ number_format($pls->amount, 2, ',', '.') }}</a></h5>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
