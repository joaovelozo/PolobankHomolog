@extends('agency.includes.master')
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
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Comunicados</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    @if($status === 'active')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mt-0 mb-1">Gerenciamento de Comunicados</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Comunicado</th>

                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($com as $item)
                                    <tr>
                                        <td><strong>{{$item->title}}</strong></td>
                                        <td>{!! substr($item->description, 0, 50) !!}{!! strlen($item->description) > 50 ? "..." : "" !!}</td>


                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('agencycomunication.show',$item->id) }}"><i data-feather="eye"></i></a>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
            @endif
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end content -->
</div>
<!-- end content-page -->


@endsection
