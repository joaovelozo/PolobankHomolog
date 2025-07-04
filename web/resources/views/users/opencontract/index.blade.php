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
            background-color: #00b81f;
            /* Cor personalizada */
            color: white;
            /* Cor do texto */
            /* Outros estilos se necessário */
        }

        .contract-content {
            text-align: justify;
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
                    <div class="col-md-9 col-xl-6 text-md-right">
                        <div class="mt-4 mt-md-0">
                            <a href="{{ route('usercontract.index') }}" class="btn btn-custom btn-sm mr-4 mb-3 mb-sm-0">
                                <i data-feather="arrow-left" mr-1></i>Voltar
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




                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h1 class="mt-0 header-title"><b>{{ $ctr->title }}</b></h1>

                                                    <div class="text-muted mt-3">
                                                        <p class="contract-content">{!! $ctr->content !!}</p>


                                                        <div class="tags">

                                                        </div>


                                                    </div> <!-- end card body-->
                                                </div> <!-- end card -->
                                            </div><!-- end col-->
                                        </div>
                                    @else
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end content-page -->
                    @endsection
