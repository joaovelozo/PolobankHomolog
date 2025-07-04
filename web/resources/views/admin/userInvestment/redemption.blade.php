@extends('admin.includes.master')
@section('content')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="col-md-3 col-xl-6">
                    <h4 class="mb-1 mt-0">Detalhes do investimento</h4>
                </div>

                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{ route('useradmininvestment.index') }}" class="btn btn-secondary">
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title  mb-0">Detalhes do Investimento</h5>
                        <div class="badge badge-success">#{{$investment->id}}</div>
                    </div>
                    <p class="  mb-1">Investimento: {{ $investment->investment->title }}</p>
                    <p class="   mb-1">Cliente: {{ $investment->user->name }} </p>
                    <p class="  mb-1">Valor: R$ {{ number_format($investment->amount, 2, ',', '.') }}</p>
                    @if(empty($investment->redemption_date) && empty($investment->calculated_return))
                    <?php
                    $retorno = calculateDailyReturn($investment->amount, $investment->start_date, $investment->investment->performance, $investment->investment->tax);
                    ?>
                    <p class="   mb-1">Retorno em aberto: R$ {{ number_format($retorno['valor_atual'], 2, ',', '.') }}</p>
                    @else
                    <p class="   mb-1">Retorno: R$ {{ number_format($investment->calculated_return, 2, ',', '.') }}</p>
                    @endif
                    <p class="   mb-1">Data investimento: {{ date('d/m/Y', strtotime($investment->start_date)) }}</p>
                    <p class="   mb-1">Data vencimento: {{ date('d/m/Y', strtotime($investment->end_date)) }}</p>
                    @if(empty($investment->redemption_date))
                    <p class="  mb-1">Status: <span class="badge badge-success">Ativo</span></p>
                    @else
                    <p class="  mb-1">Status: <span class="badge badge-danger">Resgatado</span></p>
                    <p class="   mb-1">Data do resgate: {{ date('d/m/Y', strtotime($investment->redemption_date)) }}</p>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                    @endif
                    @if(empty($investment->redemption_date))
                    <form method="POST" action="{{ route('useradmininvestment.redemption',$investment->id) }}" class="mt-4">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="birthdate">Data do resgate</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" placeholder="Digite Corretamente" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Resgatar</button>
                        </div>
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection