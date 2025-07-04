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
                        <h4 class="mb-1 mt-0">Formulário de Contato Via Site</h4>
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mt-0 mb-1">Contato Via Site</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cont as $item)
                                    <tr>
                                        <td><strong>{{$item->name}}</strong></td>
                                        <td><strong>{{$item->email}}</strong></td>
                                        <td><strong>{{$item->phone}}</strong></td>



                                            <td>
                                                <a class="btn btn-info btn-sm" href="{{ route('contact.show',$item->id) }}"><i data-feather="eye"></i></a>
                                            </td>
                                            <td>
                                            <form action="{{ route('contact.destroy',$item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i data-feather="trash"></i></button>
                                            </form>
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
