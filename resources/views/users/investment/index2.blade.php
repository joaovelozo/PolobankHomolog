@extends('users.includes.master')
@section('content')
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
                    <div class="col-md-3 col-xl-2 text-center"> <!-- Adicionado text-center aqui -->

                        <h4 class="mb-1 mt-0">Investimentos</h4>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        @if ($status === 'active')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid">
                                <div class="row page-title align-items-center">
                                    <div class="col-sm-4 col-xl-6">
                                        <h4 class="mb-1 mt-0">Dashboard</h4>
                                    </div>
                                    <div class="col-sm-8 col-xl-6">
                                        <form class="form-inline float-sm-right mt-3 mt-sm-0">
                                            <div class="form-group mb-sm-0 mr-2">
                                                <input type="text" class="form-control" id="dash-daterange"
                                                    style="min-width: 190px;" />
                                            </div>
                                            <div class="btn-group">
                                                <!-- Adicione seus botões aqui, se necessário -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Content -->
                                <div class="row justify-content-center"> <!-- Altere justify-content-left para justify-content-center -->
                                    <div class="col-md-6 col-xl-3">
                                        <div class="card">
                                            <div class="text-center">
                                                <img src="{{asset('assets/frontend/load.png')}}" width="300px" />
                                                <h3>"No momento esta área esta passando por atualizações, em breve vamos te avisar para aproveitar ainda mais sua conta!"</h1>
                                            </div>
                                        </div><!-- end row -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end .row -->
        @endif
    </div> <!-- end .content-page -->
@endsection
