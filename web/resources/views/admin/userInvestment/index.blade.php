@extends('admin.includes.master')
@section('content')

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
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Investimentos</h4>
                    </div>
                </div>
                <div class="col-md-9 col-xl-6 text-md-right">
                    <div class="mt-4 mt-md-0">
                        <a href="{{route('useradmininvestment.create')}}" class="btn btn-custom btn-sm mr-4 mb-3 mb-sm-0">
                            <i class="uil-plus mr-1"></i>Associar Investimento a Usuário
                        </a>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-2/3 px-4">
                @if(session()->has('message'))
                <div class="alert-badge" id="notification-badge">
                    {{ session('message') }}
                </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mt-0 mb-1">Inserir Investimento Ao Usuário</h4>
                                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Tipo</th>
                                            <th>Investimento</th>
                                            <th>Valor</th>
                                            <th>Inicio</th>
                                            <th>Vencimento</th>
                                            <th>Retorno</th>
                                            <th>Resgatado</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ivs as $item)
                                        <tr>
                                            <td><strong>{{ $item->user->name }}</strong></td> <!-- Exibindo o nome do usuário -->
                                            <td>{{ $item->type->name }}</td>
                                            <td>{{ $item->investment->title }}</td>
                                            <td>R$ {{ number_format($item->amount, 2, ',', '.') }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->start_date)) }}</td> <!-- Formatando a data de início -->
                                            <td>{{ date('d/m/Y', strtotime($item->end_date)) }}</td> <!-- Formatando o vencimento -->
                                            <td>
                                                @if(empty($item->redemption_date) && empty($item->calculated_return))
                                                <?php
                                                $retorno = calculateDailyReturn($item->amount, $item->start_date, $item->investment->performance, $item->investment->tax);
                                                ?>
                                                R$ {{ number_format($retorno['valor_atual'], 2, ',', '.') }}
                                                @else
                                                R$ {{ number_format($item->calculated_return, 2, ',', '.') }}
                                                @endif
                                            </td> <!-- Formatando o valor -->
                                            <td>
                                                @if(empty($item->redemption_date))
                                                <a class="btn btn-info btn-sm" href="{{ route('useradmininvestment.redemption', $item->id) }}">Resgatar</a>
                                                @else
                                                {{ date('d/m/Y', strtotime($item->redemption_date)) }}
                                                @endif
                                            </td> <!-- Formatando o vencimento -->
                                            <td>
                                                <form action="{{ route('useradmininvestment.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i data-feather="trash"></i></button>
                                                </form>
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-sm" href="{{ route('useradmininvestment.show', $item->id) }}"><i data-feather="eye"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container-fluid -->
        </div>
        <!-- end content -->
    </div>
    <!-- end content-page -->

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('notification-badge')) {
                setTimeout(() => {
                    document.getElementById('notification-badge').style.display = 'none';
                }, 5000);
            }
        });
    </script>
    @endsection