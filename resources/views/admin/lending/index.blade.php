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
      </style>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Solicitações</h4>
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
                            <h4 class="header-title mt-0 mb-1">Solicitações</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>

                                        <th>Email</th>


                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lds as $item)
                                    <tr>
                                        <td><strong>{{ $item->loan->title ?? 'Sem título' }}</strong></td>
                                        <td><strong>{{$item->name}}</strong></td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->cpf }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>R${{ number_format($item->amount, 2, ',', '.') }}</td>
                                        <td>{{ $item->installments }}</td>

                                                                              <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('loan.show_lending',$item->id) }}"><i data-feather="eye"></i></a>
                                            <a class="btn btn-success btn-sm" href="{{ route('show.documents',$item->id) }}"><i data-feather="file-text"></i></a>
                                            <form action="{{ route('lending.delete', ['id' => $item->id]) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este empréstimo?')"><i data-feather="trash-2"></i></button>
                                            </form>
                                            @php
                                            $ldsCounter = 0;
                                            if(isset($lendingId)) {
                                                $ldsCounter = DB::table('lendings')->where('response', $lendingId)->count();
                                            }
                                        @endphp
                                               <span class="badge badge-success float-right">{{$ldsCounter}}</span>
                                            <a class="btn btn-warning btn-sm" href="{{ route('lending.user.response',$item->id) }}"><i data-feather="message-circle"></i></a>

                                        </td>


                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end content -->
</div>
<!-- end content-page -->
@endsection
