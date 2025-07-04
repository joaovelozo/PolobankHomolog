@extends('users.includes.master')
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


@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp


<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Documentos</h4>
                    </div>

        </div>

    </div>

    </div>
</div>
<div class="w-full lg:w-2/3 px-4">
    @if(session()->has('message'))
    <div class="alert-badge" id="notification-badge">
        {{ session('message') }}
    </div>
@endif
    <hr>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0 mb-1">Documentos</h4>

                </div>
            </div>
        </div>
    </div>
    @if($status === 'active')
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Descrição</th>

                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($docs as $item)
                                    <tr>
                                        <td>{{ $item->id}}</td>
                                        <td>{!! substr($item->description, 0, 50) !!}{!! strlen($item->description) > 50 ? "..." : "" !!}</td>

                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('document.show',$item->id) }}"><i data-feather="eye"></i></a>
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
        @else
        @endif
        <!-- end container-fluid -->
    </div>
    <!-- end content -->
</div>
            </div>
        </div>








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
