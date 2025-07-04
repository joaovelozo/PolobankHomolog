@extends('users.includes.master')
@section('content')


@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp

@if($status === 'active')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Emprétimos</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Solicitação de Empréstimo</h4>
                </div>
            </div>
            <div class="row">
                @foreach ($loans as $item)
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title font-size-16">{{$item->title}}</h5>
                                <p class="card-text" style="text-align: justify !important;">{!!$item->description!!}</p>
                                <a href="{{ route('lending.create.custom', ['loan_id' => $item->id]) }}" class="btn btn-primary">Solicitar Agora</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        @endif
    </div>
</div>
@endsection
