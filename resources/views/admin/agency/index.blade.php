@extends('admin.includes.master')
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
            background-color: #00b81f;
            /* Cor personalizada */
            color: white;
            /* Cor do texto */
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
                            <img src="{{ asset('assets/backend/icon.png') }}" class="responsive" alt="Logo"
                                width="200px">
                        </div>
                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Agências</h4>
                        </div>
                    </div>
                    <div class="col-md-9 col-xl-6 text-md-right">
                        <div class="mt-4 mt-md-0">
                            <a href="{{ route('agency.create') }}" class="btn btn-custom btn-sm mr-4 mb-3 mb-sm-0">
                                <i class="uil-plus mr-1"></i>Nova Agência
                            </a>
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

                                    <h4 class="header-title mt-0 mb-1">Gestão de Agências</h4>



                                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Número</th>
                                                <th>Email</th>
                                                <th>Gerente Responsável</th>


                                                <th>Ações</th>

                                            </tr>
                                        </thead>


                                        <tbody>
                                            @foreach ($agencies as $item)
                                                @php
                                                    $agency = \App\Models\Agency::findOrFail($item->id);
                                                    $clientsCount = \App\Models\User::where(
                                                        'agency_id',
                                                        $agency->id,
                                                    )->count();
                                                @endphp
                                                <tr>
                                                    <td><strong>{{ $item->name }}</strong></td>
                                                    <td>{{ $item->number }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->user->username ?? 'Não Atribuído' }}</td>

                                                    <td>
                                                        <div style="display: flex; align-items: flex-start;">
                                                            <form action="{{ route('agency.destroy', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm mr-1"><i data-feather="trash"></i></button>
                                                            </form>
                                                            <a class="btn btn-info btn-sm mr-1" href="{{ route('agency.show', $item->id) }}"><i data-feather="eye"></i></a>
                                                            <a class="btn btn-primary btn-sm mr-1" href="{{ route('agency.edit', $item->id) }}"><i data-feather="edit"></i></a>
                                                            <a class="btn btn-success btn-sm" href="{{ route('admin.agency.clients', $item->id) }}"><i data-feather="users"></i></a>
                                                            <span class="badge badge-warning badge-top">{{ $clientsCount }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- end content-page -->

                    <script>
                        document.addEventListener('DOMContentLoaded', (event) => {
                          if (document.getElementById('notification-badge')) {
                            setTimeout(() => {
                              document.getElementById('notification-badge').style.display = 'none';
                            }, 5000);
                          }
                        });
                      </script>
                @endsection
