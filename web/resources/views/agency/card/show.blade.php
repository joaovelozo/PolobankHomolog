@extends('admin.includes.master')
@section('content')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <p> <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid" alt="Logo" width="200px"></p>
                    <p> Dados do Gerente</p>
                </div>

                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('manager.index') }}" class="btn btn-secondary">
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
                        <p> <img src="{{asset('assets/backend/images/logo.png')}}" class="img-fluid" alt="Logo" width="30px"></p>
                        <div class="card-body">
                            <div class="badge badge-success float-right" style="font-size: 20px;">{{$manager->name}}</div>
                            <p class="text-success text-uppercase font-size-12 mb-2">Nome do Gerente</p>
                            <p>Telefone:</p><h5><a href="#" class="text-dark"><td>
                                @if(strlen($manager->mobilePhone) === 11)
                                    ({!! substr($manager->mobilePhone, 0, 2) !!}) {!! substr($manager->mobilePhone, 2, 5) !!}-{!! substr($manager->mobilePhone, 7) !!}
                                @elseif(strlen($manager->mobilePhone) === 10)
                                    ({!! substr($manager->mobilePhone, 0, 2) !!}) {!! substr($manager->mobilePhone, 2, 4) !!}-{!! substr($manager->mobilePhone, 6) !!}
                                @else
                                    {{ $manager->mobilePhone }}
                                @endif
                            </td></a></h5>
                            <p class="text-muted mb-4"><p>CPF:</p><strong>{{$manager->cpfCnpj}}</strong></p>
                            <p class="text-muted mb-4"><p>Email:</p><strong>{{$manager->email}}</strong></p>
                            <p class="text-muted mb-4"><p>Endereço:</p><strong>{{$manager->address}}</strong></p>
                            <p class="text-muted mb-4"><p>Número:</p><strong>{{$manager->addressNumber}}</strong></p>
                            <p class="text-muted mb-4"><p>Cidade:</p><strong>{{$manager->province}}</strong></p>
                            <p class="text-muted mb-4"><p>CEP:</p><strong>{{$manager->postalCode}}</strong></p>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
