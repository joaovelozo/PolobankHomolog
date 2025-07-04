@extends('admin.includes.master')
@section('content')

<div class="content-page">
    <div class="content">
        
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h4 class="mb-1 mt-0">Detalhes do Chamado</h4>
                </div>
               
                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('loan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $statusColor = '';
                                $statusLabel = '';
                                switch ($ticket->status) {
                                    case 'open':
                                        $statusColor = 'badge-success';
                                        $statusLabel = 'Aberto';
                                        break;
                                    case 'pending':
                                        $statusColor = 'badge-warning';
                                        $statusLabel = 'Em Análise';
                                        break;
                                    case 'closed':
                                        $statusColor = 'badge-danger';
                                        $statusLabel = 'Fechado';
                                        break;
                                    default:
                                        $statusColor = 'badge-secondary';
                                        $statusLabel = 'Desconhecido';
                                }
                            @endphp
                        
                            <div class="badge {{ $statusColor }} float-right">
                                {{ $statusLabel }}
                            </div>
                        
                            <p class="text-success text-uppercase font-size-12 mb-2">Status</p>
                            <p>Título:</p><h5><a href="#" class="text-dark">{{$ticket->title}}</a></h5>
                            <p>Descrição:</p><h5><a href="#" class="text-dark">{!!$ticket->description!!}</a></h5>
                            <p>Protocolo:</p><h5><a href="#" class="text-dark">{!!$ticket->protocol!!}</a></h5>
                            
                            <!-- Botão de edição dentro do card-body -->
                            
                        </div>

                        <a href="{{route('ticketsadmin.create', $ticket->id)}}" class="btn btn-primary">Responder Chamado</a>
            </div>
        </div>
    </div>

@endsection