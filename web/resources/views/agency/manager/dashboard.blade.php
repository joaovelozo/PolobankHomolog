@extends('agency.includes.master')
@section('content')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Relatórios</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Dashboard</h4>
                </div>
                <h6>Seu Último Acesso foi em: {{ date('d/M/Y') }}<span class="text-success">Conta Ativa</span></h6>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="media p-3">
                                <div class="media-body">
                                    <span class="text-muted text-uppercase font-size-12 font-weight-bold">Seu
                                        Saldo</span>
                                    <h2 class="mb-0">R$ {{ number_format(auth()->user()->balance(), 2, ',', '.') }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <div id="today-revenue-chart" class="apex-charts"></div>
                                    <span class="text-success font-weight-bold font-size-13"><i
                                            class='uil uil-arrow-up'></i> 10.21%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="media p-3">
                                <div class="media-body">
                                    <span class="text-muted text-uppercase font-size-12 font-weight-bold">Dados
                                        Bancários</span>
                                    <div>
                                        <p><b>Banco</b>: <strong> Polocal Bank</strong></p>
                                        <p><b>Agência</b>: {{ auth()->user()->agency->number }}</p>
                                        <p><b>Conta:</b>: {{ auth()->user()->account }}</p>
                                        <p><b>CPF/CNPJ</b>: {{ auth()->user()->documentNumber }}</p>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h6><strong>Transações</strong></h6>
                <p class="sub-header">
                    Acompanhe suas últimas transações de pagamento, transferências e compras!
                </p>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-buttons" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Valor</th>
                                    <th>Data</th>
                                    <th>Operação</th>
                                    <th>Status</th>
                                    <th>Tipo</th>
                                    <th>Comprovante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($transactions->count() > 0)
                                @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="p-0">
                                        <div class="pb-2 pt-2 pl-2">
                                            <span class="text-sm text-gray-100 font-medium">{{ $transaction->id }}</span>
                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <div class="h-16 p-6 pb-2 pt-2">
                                            <span class="text-sm font-medium text-gray-100">
                                                {{$transaction->name}}
                                            </span>
                                        </div>
                                    <td class="p-0">
                                        <div class="h-16 p-6 pb-2 pt-2">
                                            <span class="text-sm text-gray-100 font-medium">
                                                R$ {{ formatToBrazilianCurrency($transaction->amount) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <div class="h-16 p-6 pb-2 pt-2">
                                            <span class="text-sm text-gray-100 font-medium">
                                                {{ $transaction->created_at->format('d/m/Y H:i:s') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <div class="h-16 p-6 pb-2 pt-2">
                                            <span class="text-sm text-gray-100 font-medium">
                                                {{ $transaction->getOperacaoDescription() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <div class="h-16 p-6 pb-2 pt-2">
                                            <span class="text-sm text-gray-100 font-medium">
                                                {{ $transaction->getStatusDescription() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <div class="h-16 p-6 pb-2 pt-2">
                                            <span class="text-sm text-gray-100 font-medium">
                                                {{ $transaction->getTypeDescription() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <div class="h-16 p-6 pb-2 pt-2">
                                            @if($transaction->status == 'SUCCESS')
                                            <a href="{{ route('agency.invoice.show', $transaction->id) }}" class="btn btn-sm btn-primary" title="Baixar Comprovante">
                                                <i class="fas fa-file-pdf"></i> COMPROVANTE
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">Nenhuma Transação Recebida Encontrada</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end card -->
@endsection
