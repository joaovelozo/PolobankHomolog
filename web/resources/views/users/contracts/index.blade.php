@extends('users.includes.master')
@section('content')


@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp

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

                    @if ($status === 'active')
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Contratos</h4>
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



                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Conteúdo</th>

                                        <th>Ações</th>

                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($ctr as $item)
                                    <tr>
                                        <td><strong>{{$item->title}}</strong></td>
                                        <td>{!! substr(strip_tags($item->content), 0, 50) !!}...</td>


                                        <td> <!-- Início da célula para os botões -->
                                            <a class="btn btn-info btn-sm mr-1" href="{{ route('usercontract.show', $item->id) }}"><i data-feather="eye"></i></a>

                                        </td> <!-- Fim da célula para os botões -->
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div> <!-- end card body-->
                    </div>
                    @else
                    @endif<!-- end card -->
                </div><!-- end col-->
            </div>
<!-- end content-page -->
@endsection
