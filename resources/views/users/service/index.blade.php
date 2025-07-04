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
                <div class="col-md-3 col-xl-2">

                    <h4 class="mb-1 mt-0 align-self-center">Parceiros</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    @if($status === 'active')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3 mt-0 header-title">Serviços</h4>
                    <div class="row bg-light p-3">
                        @foreach ($servs as $item)
                        <div class="col-lg-6 col-xl-3">
                            <!-- Simple card -->
                            <div class="card mb-4 mb-xl-0">
                                <img class="card-img-top img-fluid" src="{{ asset('images/' . $item->image) }}" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title font-size-16">{{$item->title}}</h5>
                                    <p class="card-text" style="text-align: justify !important;">{!!$item->description!!}</p>
                                    <a href="{{$item->url}}" class="btn btn-primary" target="_blank">Ver Mais</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
@endif
    </div><!-- end row -->

</div><!-- end content-page -->

@endsection
