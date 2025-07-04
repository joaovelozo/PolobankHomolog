@extends('agency.includes.master')
@section('content')

<div class="content-page">
    <div class="content">

        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <img src="{{asset('assets/backend/images/logo.png')}}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Minhas Chaves</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-center">Minhas Chaves</h5>
                    <div class="row text-center">
                        <div class="col">
                            <a href="{{ route('agency.transfer') }}">
                                <i class="fas fa-exchange-alt"></i> <!-- Ícone de transferir -->
                                <span>Solicitar Migração</span> <!-- Texto abaixo do ícone -->
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('agency.key.index') }}">
                                <i class="fa-solid fa-key"></i> <!-- Ícone de copiar -->
                                <span>Criar Chave Pix</span> <!-- Texto abaixo do ícone -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
</div>

</div>
</div>
@endsection