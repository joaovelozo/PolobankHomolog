@extends('admin.includes.master')
@section('content')


<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h6 class="mb-1 mt-0">Detalhes do Pagamento: <b>{{$pay->title}}</b></h6>
                </div>

                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('adpayment.index') }}" class="btn btn-secondary">
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
                    <p>Usuário:</p>
                    <h5><a href="#" class="text-dark">{{$pay->recebedor->name }}</a></h5>
                    <p>Valor:</p>
                    <h5><a href="#" class="text-dark">R$ {{ number_format($pay->amount, 2, ',', '.') }}</a></h5>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection