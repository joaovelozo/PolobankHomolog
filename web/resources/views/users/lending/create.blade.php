@extends('users.includes.master')
@section('content')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Importe a biblioteca de máscara -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>


@php
$id = Auth::user()->id;
$clientId = App\Models\User::find($id);
$status = $clientId->status;
@endphp

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
                            <li class="breadcrumb-item active" aria-current="page">Empréstimos</li>
                        </ol>
                    </nav>
                    <div class="row">
                        <div class="col-md-3 col-xl-2 d-flex align-items-center">
                            <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid d-none d-md-block" alt="Logo" style="max-width: 100px;">
                            <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid d-md-none" alt="Logo" style="max-width: 50px;">
                        </div>
                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Empréstimos</h4>
                        </div>
                    </div>
            </div>
            </div>
        </div>
        @if($status === 'active')
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Formulário de Solicitação</h4>

                            <!-- Seu formulário -->
                            <form method="POST" action="{{ route('lending.store') }}">
                                @csrf

                                <input type="hidden" name="loan_id" value="{{$loan->id}}">

                                <div class="form-group">

                                   <p>Você Está Solicitando o Empréstimo: <h6><b>"{{$loan->title}}"</b></h6></p>
                                </div>


                              <div class="form-group">
                                    <label for="cpf">Seu Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Informe o Nome" required>
                                </div>

                                <div class="form-group">
                                    <label for="cpf">Seu Telefone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Informe o Telefone" required>
                                </div>
                                <div class="form-group">
                                    <label for="cpf">CPF ou CNPJ</label>
                                    <input type="text" class="form-control" id="cpfCnpj" name="cpf" placeholder="Informe o CPF" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Informe o e-mail" required>
                                </div>

                                <div class="form-group">
                                    <label for="amount">Valor do Empréstimo</label>
                                    <input type="text"  class="form-control"  id="amount" name="amount" placeholder="Informe o valor" required>
                                </div>

                                <div class="form-group">
                                    <label for="installments">Número de Parcelas</label>
                                    <select class="form-control" id="installments" name="installments">
                                        @for ($i = 12; $i <= 100; $i++)
                                            <option value="{{ $i }}">{{ $i }}x</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="text-right"> <!-- Adicione esta classe para alinhar à direita -->
                                    <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Solicitar Empréstimo</button>
                              </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                @endif
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/charts-demo.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#phone').mask('(00) 00000-0000');
        $('#whatsapp').mask('(00) 00000-0000');
        $('#cep').mask('00.000-000');
        $('#amount').mask('R$ 000.000.000,00', {reverse: true});

        $('#cpfCnpj').focusout(function() {
          var value = $(this).val().replace(/\D/g, '');

          if (value.length === 11) {
            $(this).mask('000.000.000-00');
          } else if (value.length === 14) {
            $(this).mask('00.000.000/0000-00');
          } else {
            $(this).val('');
          }
        });
        $('form').submit(function(event) {
            event.preventDefault(); // Previne o envio do formulÃ¡rio para a aÃ§Ã£o padrÃ£o

            var amountField = $('#amount');
            var unmaskedValue = amountField.cleanVal();
            var floatValue = parseFloat(unmaskedValue) / 100; // Converta para valor decimal

            // Defina o valor do campo como o valor decimal correto
            amountField.val(floatValue);

            // Envie o formulÃ¡rio
            $(this).unbind('submit').submit();
        });
    });
</script>



@endsection
