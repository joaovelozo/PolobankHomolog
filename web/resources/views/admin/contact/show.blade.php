@extends('admin.includes.master')
@section('content')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h4 class="mb-1 mt-0">Formulário de Contato Via Site</h4>
                </div>

                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('contact.index') }}" class="btn btn-secondary">
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
                            <div class="badge badge-success float-right">{{$cnt->name}}</div>
                            <p class="text-success text-uppercase font-size-12 mb-2">Nome</p>
                            <p>Email:</p><h5><a href="#" class="text-dark">{{$cnt->email}}</a></h5>
                            <p>Telefone:</p><h5><a href="#" class="text-dark">{{$cnt->phone}}</a></h5>
                            <p>Mensagem:</p><h5><a href="#" class="text-dark">{{$cnt->content}}</a></h5>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
