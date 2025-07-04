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
                        <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Planos</h4>
                    </div>
                </div>
                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{route('plans.create')}}" class="btn btn-custom btn-sm mr-4 mb-3 mb-sm-0">
                            <i class="uil-plus mr-1"></i>Cadastrar Plano
                        </a>
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
                            <h4 class="header-title mt-0 mb-1">Gestão de Planos</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Descrição</th>
                                        <th>Período</th>
                                        <th>Valor</th>

                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pls as $item)
                                    <tr>
                                        <td><strong>{{$item->title}}</strong></td>
                                        <td>{!! substr($item->description, 0, 50) !!}{!! strlen($item->description) > 50 ? "..." : "" !!}</td>
                                        <td><strong>{{$item->period}}</strong></td>
                                        <td>R${{ number_format($item->amount, 2, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('plans.destroy',$item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i data-feather="trash"></i></button>
                                            </form>
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('plans.show',$item->id) }}"><i data-feather="eye"></i></a>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{ route('plans.edit',$item->id) }}"><i data-feather="edit"></i></a>
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
