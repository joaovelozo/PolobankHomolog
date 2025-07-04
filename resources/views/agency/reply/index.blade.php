@extends('agency.includes.master')
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
                        <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Chamados</h4>
                    </div>
                </div>
                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">

                    </div>
                </div>
            </div>
            <div class="w-full lg:w-2/3 px-4">
                @if(session()->has('message'))
                <div class="alert-badge" id="notification-badge">
                    {{ session('message') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title mt-0 mb-1">Gestão de Chamados</h4>



                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Perfil</th>
                                        <th>Título</th>
                                        <th>Protocolo</th>
                                        <th>Status</th>
                                        <th>Ações</th>

                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($tks as $item)
                                    <tr>
                                        <td><strong>{{$item->user->name}}</strong></td>
                                        <td><strong>{{ $item->user->role === 'user' ? 'Cliente' : ($item->user->role === 'manager' ? 'Gerente' : '') }}</strong></td>
                                        <td>{!! substr($item->description, 0, 50) !!}{!! strlen($item->description) > 50 ? "..." : "" !!}</td>
                                        <td>{{ $item->protocol }}</td>
                                        <td>
                                            <h5 class="mt-0">
                                                @if($item->status === 'open')
                                                    <span class="badge badge-success">Aberto</span>
                                                @elseif($item->status === 'pending')
                                                    <span class="badge badge-warning">Pendente</span>
                                                @elseif($item->status === 'closed')
                                                    <span class="badge badge-danger">Fechado</span>
                                                @else
                                                    {{ $item->status }}
                                                @endif
                                            </h5>
                                        </td>
                                        <td> <!-- Início da célula para os botões -->
                                            <a class="btn btn-primary btn-md" href="{{ route('agencyreply.show',$item->id) }}"><i class="fa-solid fa-eye"></i></a>
                                            <a class="btn btn-primary btn-md" href="{{ route('agencyreply.create',$item->id) }}"><i class="fa-regular fa-comment-dots"></i></a>
                                        </td> <!-- Fim da célula para os botões -->
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
<!-- end content-page -->
@endsection
