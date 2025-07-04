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

                    <h4 class="mb-1 mt-0 align-self-center">Investimentos</h4>
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
                                        <input type="text" class="form-control" id="dash-daterange" style="min-width: 190px;" />
                                    </div>
                                    <div class="btn-group">
                                        <!-- Adicione seus botões aqui, se necessário -->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="row justify-content-left">
                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <div class="media-body">
                                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Valor Investido R$</span>
                                                <h2 class="mb-0">
                                                    <h2 class="mb-0">{{ number_format($totalInvested, 2, ',', '.') }}</h2>
                                                </h2>
                                            </div>
                                            <div class="align-self-center">
                                                <div id="today-revenue-chart" class="apex-charts"></div>
                                                <span class="text-success font-weight-bold font-size-13"><i class='uil uil-arrow-up'></i> 10.21%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6><strong>Investimentos</strong></h6>
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                            <th>Investimento</th>
                                            <th scope="col">Data Inicial</th>
                                            <th scope="col">Data de Vencimento</th>
                                            <th scope="col">Valor aplicado</th>
                                            <th scope="col">Retorno</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ivs as $transaction)
                                        <tr>
                                            <td>{{ $transaction->investment->title }}</td>
                                            <td><i class="far fa-calendar" style="color: #00b81f;"></i> {{ date('d/m/Y', strtotime($transaction->start_date)) }}</td>
                                            <td><i class="far fa-clock" style="color: #00b81f;"></i> {{ date('d/m/Y', strtotime($transaction->end_date))  }}</td>
                                            <td>
                                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                            </td> <!-- Formatando o valor -->
                                            <td>
                                                @if(empty($transaction->redemption_date) && empty($transaction->calculated_return))
                                                <?php
                                                $retorno = calculateDailyReturn($transaction->amount, $transaction->start_date, $transaction->investment->performance, $transaction->investment->tax);
                                                ?>
                                                R$ {{ number_format($retorno['valor_atual'], 2, ',', '.') }}
                                                @else
                                                R$ {{ number_format($transaction->calculated_return, 2, ',', '.') }}
                                                @endif
                                            </td> <!-- Formatando o valor -->
                                            <td>
                                                @if(empty($transaction->redemption_date))
                                                     <span class="badge badge-success">Ativo</span>
                                                @else
                                                <span class="badge badge-danger">Resgatado {{ date('d/m/Y', strtotime($transaction->redemption_date)) }}</span>  
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-lg-4">
                    <h4><b>Investimentos Disponíveis</b></h4>
                </div>
                <!-- Cards de Investimento -->
                <div class="row">
                    @foreach($show as $item)
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title font-size-16">Título: {{$item->title}}</h5>
                                <h5 class="card-title font-size-16">Resgate: {{$item->term}}</h5>
                                <h5 class="card-title font-size-16">Taxa Administração:{{$item->tax}}%</h5>
                                <h5 class="card-title font-size-16">Retorno: {{$item->performance}}%</h5>
                                <p class="card-text text-muted">Descrição{!!$item->description!!}</p>
                                <h6>Investimento Mínimo: R${{ number_format($item->amount, 2, ',', '.') }}</h6>
                                <!--Block quando o investimento estiver ok realbilitar
                                <a href="{{ route('userinvestment.create') }}" class="btn btn-primary">Quero Investir</a>
                                 !-->
                            </div>
                        </div>
                    </div><!-- end col -->
                    @endforeach
                </div><!-- end row -->
            </div>
        </div>
    </div>
</div>
</div> <!-- end .row -->
@endif
</div> <!-- end .content-page -->
@endsection