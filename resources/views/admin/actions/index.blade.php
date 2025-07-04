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
                            <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo"
                                width="200px">
                        </div>
                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Ações dos Usuários</h4>
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

                                    <h4 class="header-title mt-0 mb-1">Acesso de Usuários</h4>



                                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>CPF/CNPJ</th>
                                                <th>Ações</th>
                                                <th>Latitude</th>
                                                <th>Logitude</th>
                                                <th>IP</th>
                                                <th>Ações</th>

                                            </tr>
                                        </thead>


                                        <tbody>
                                            @foreach ($act as $item)

                                                <tr>
                                                    <td><strong>{{$item->name}}</strong></td>
                                                    <td><strong>{{$item->document}}</strong></td>
                                                    <td>{{ $item->action }}</td>
                                                    <td>{{ $item->latitude }}</td>
                                                    <td>{{ $item->longitude }}</td>
                                                    <td>{{ $item->ip }}</td>
                                                    <td>
                                                        @if(strlen($item->phone) === 11)
                                                            ({!! substr($item->phone, 0, 2) !!}) {!! substr($item->phone, 2, 5) !!}-{!! substr($item->phone, 7) !!}
                                                        @elseif(strlen($item->phone) === 10)
                                                            ({!! substr($item->phone, 0, 2) !!}) {!! substr($item->phone, 2, 4) !!}-{!! substr($item->phone, 6) !!}
                                                        @else
                                                            {{ $item->phone }}
                                                        @endif

                                                    <td>
                                                        <div style="display: flex; align-items: flex-start;">
                                                            <form action="{{ route('manager.destroy', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm mr-1"><i data-feather="trash"></i></button>
                                                            </form>
                                                            <a class="btn btn-info btn-sm mr-1" href="{{ route('manager.show', $item->id) }}"><i data-feather="eye"></i></a>
                                                            <a class="btn btn-primary btn-sm mr-1" href="{{ route('manager.edit', $item->id) }}"><i data-feather="edit"></i></a>

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
