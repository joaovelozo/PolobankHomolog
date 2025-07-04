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
                    <div class="row">

                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Chamados</h4>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-2/3 px-4">
                    @if (session()->has('message'))
                        <div class="alert-badge" id="notification-badge">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </div>
            <hr>
            @if ($status === 'active')
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <h5 class="mb-5">Meus Chamados</h5>
                        <div>
                            <a href="{{ route('userticket.create') }}"
                                class="btn btn-primary btn-sm">Abrir Chamado</a>
                        </div>
                        <div class="left-timeline pl-4">
                            <ul class="list-unstyled events">
                                @if ($tks->isEmpty())
                                    <li class="event-list">
                                        <p>Você não tem nenhum ticket.</p>

                                    </li>
                                @else
                                    @foreach ($tks as $item)
                                        <div class="event-list mt-4">
                                            <div class="media">
                                                <div class="event-date text-center mr-4">
                                                    <div class="avatar-sm rounded-circle bg-soft-primary">
                                                        <span
                                                            class="font-size-16 avatar-title text-primary font-weight-semibold">
                                                            {{ date('d', strtotime($item->created_at)) }}
                                                            <!-- Exibe o dia -->
                                                        </span>
                                                    </div>
                                                    <p class="mt-2">{{ date('M', strtotime($item->created_at)) }}</p>
                                                    <!-- Exibe a abreviação do mês -->
                                                </div>
                                                <div class="media-body">
                                                    <div class="card d-inline-block">
                                                        <div class="card-body">
                                                            <h5 class="mt-0"><h6>Título: <b>{{ $item->title }}</b></h6></h5>
                                                            <h6 class="mt-0"><b>Protocolo: {{ $item->protocol }}</b></h6>
                                                            <h6>Descrição: <b>{!! $item->description !!}</b></h6></p>
                                                            <h5 class="mt-0">
                                                                Status:
                                                                @if ($item->status === 'open')
                                                                    <span class="badge badge-success">Aberto</span>
                                                                @elseif($item->status === 'pending')
                                                                    <span class="badge badge-warning">Pendente</span>
                                                                @elseif($item->status === 'closed')
                                                                    <span class="badge badge-danger">Fechado</span>
                                                                @else

                                                                    <b>{{ $item->status }}</b>
                                                                @endif
                                                            </h5>
                                                            <!-- Encontrar a resposta correspondente -->
                                                            @php
                                                                $reply = $rps->firstWhere('ticket_id', $item->id);
                                                            @endphp

                                                            <!-- Exibir a resposta, se existir -->
                                                            @if ($reply)
                                                                <p>Resposta: <b>{!!$reply->response !!}<b></p>
                                                            @endif

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <hr>
                            </ul>
                        </div>
                    </div>
                    <!-- end container-fluid -->
                </div>
                <!-- end content -->
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
