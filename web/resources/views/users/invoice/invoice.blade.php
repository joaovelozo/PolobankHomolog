<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Transação</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; background-color: #f4f4f9; }
        .header { text-align: center; padding: 20px; background-color: #2c3e50; color: #fff; }
        .header img { max-width: 150px; }
        .content { margin: 20px auto; padding: 20px; width: 80%; max-width: 800px; background-color: #fff; border-radius: 8px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1); }
        .content h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .content p { font-size: 14px; margin: 5px 0; }
        .section-title { font-size: 16px; color: #2c3e50; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .details { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .details th, .details td { text-align: left; padding: 10px; font-size: 14px; }
        .details th { background-color: #f8f9fa; width: 30%; }
        .details td { background-color: #fefefe; }
        .highlight { font-weight: bold; color: #27ae60; }
        hr { border: 0; height: 1px; background: #ddd; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('/assets/backend/logo.png') }}" alt="Logo">
        <h1>Comprovante de Transação</h1>
    </div>

    <div class="content">
        <h2>Resumo da Transação</h2>
        <p><strong>Nome do Usuário:</strong> {{ $user->name }}</p>
        <p><strong>Data da transação:</strong> {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
        <p><strong>Valor:</strong> <span class="highlight">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</span></p>

        <div class="section-title">Detalhes da Operação</div>
        <table class="details">
            <tr>
                <th>ID da Transação:</th>
                <td>{{ $transaction->id }}</td>
            </tr>
            <tr>
                <th>Operação:</th>
                <td>{{ $transaction->getOperacaoDescription() }}</td>
            </tr>
            <tr>
                <th>Tipo:</th>
                <td>{{ $transaction->getTypeDescription() }}</td>
            </tr>
            <tr>
                <th>Método:</th>
                <td>{{ $transaction->getMetodoDescription() }}</td>
            </tr>
        </table>

        <div class="section-title">Informações de {{ $transaction->getOperacaoDescription() == 'Entrada' ? 'quem pagou' : 'quem recebeu' }}</div>
        <table class="details">
            <tr>
                <th>Nome:</th>
                <td>{{ $transaction->nome }}</td>
            </tr>
            <tr>
                <th>CPF/CNPJ:</th>
                <td>{{ $transaction->cpfcnpj }}</td>
            </tr>

        </table>

        <hr>
        <p style="text-align: center; font-size: 12px; color: #aaa;">Este é um comprovante eletrônico de transação. Não é necessário assinatura.</p>
    </div>
</body>
</html>
