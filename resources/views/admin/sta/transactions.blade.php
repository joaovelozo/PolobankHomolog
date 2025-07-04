<!DOCTYPE html>
<html>

<head>
    <title>Sua Página</title>
    <!-- Adicione os links para os arquivos CSS e JavaScript do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Cabeçalho com Bootstrap e Logo -->
    <header class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- Insira a sua logo aqui -->
            <a class="navbar-brand" href="#"><img src="{{ public_path('assets/logo.png') }}" alt="Logo"></a>
        </div>
    </header>

    <!-- Conteúdo da Página -->
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th style="font-size: 12px;">Saldo</th>
                    <th style="font-size: 12px;">CPF</th>
                    <th style="font-size: 12px;">Remetente</th>
                    <th style="font-size: 12px;">Destinatário</th>
                    <th style="font-size: 12px;">Valor</th>
                    <th style="font-size: 12px;">Data</th>
                    <th style="font-size: 12px;">Tipo</th>
                    <th style="font-size: 12px;">Banco</th>
                    <th style="font-size: 12px;">Agência</th>
                    <th style="font-size: 12px;">Conta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td style="font-size: 8px;">R$ {{ number_format($transaction->sender->balance, 2, ',', '.') }}
                        </td>
                        <td style="font-size: 8px;">{{ $transaction->sender->document }}</td>
                        <td style="font-size: 8px;">
                            {{ $transaction->sender ? $transaction->sender->name : 'Desconhecido' }}</td>
                        <td style="font-size: 8px;">
                            <span class="text-sm font-medium text-gray-100">
                                @if ($transaction->receiver)
                                    Recebido por:
                                    @if ($transaction->type->name != 'PIX' && $transaction->type->name != 'TED' && $transaction->receiver->agency)
                                        {{ $transaction->receiver->agency->name }}
                                    @elseif ($transaction->type->name != 'PIX' && $transaction->type->name != 'TED')
                                        Destinatário Desconhecido
                                    @else
                                        {{ $transaction->receiver->name }}
                                    @endif
                                @else
                                    Destinatário Desconhecido
                                @endif
                            </span>
                        <td style="font-size: 8px;">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                        <td style="font-size: 8px;">{{ $transaction->created_at }}</td>
                        <td style="font-size: 8px;">{{ $transaction->type->name }}</td>
                        <td style="font-size: 8px;">Polocal Bank</td>
                        <td style="font-size: 8px;">
                            {{ $transaction->sender->account ? $transaction->sender->account : 'N/A' }}
                        </td>
                        <td style="font-size: 8px;">
                            {{ $transaction->sender->account ? $transaction->sender->account : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Adicione o link para o JavaScript do Bootstrap (se necessário) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
