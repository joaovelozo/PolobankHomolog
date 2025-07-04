<?php

function convertAmountToInt($amount) {
    $normalized = str_replace(['.', ','], ['', '.'], $amount);
    return (int) round(floatval($normalized) * 100);
}

function converterAmountToCents(int $cents): string
{
    return number_format($cents / 100, 2, ',', '.');
}

function converterBalanceToInt($balance)
{
    return str_replace('.', '', $balance);
}


function formatToBrazilianCurrency(float $amount): string
{
    // Formata o valor com separador de milhar (.) e decimal (,)
    return number_format($amount, 2, ',', '.');
}


// No modelo User (User.php)
function isValidCPF(string $cpf): bool
{
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11 || preg_match('/^(\d)\1+$/', $cpf)) {
        return false;
    }

    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
        $sum += $cpf[$i] * (10 - $i);
    }
    $remainder = $sum % 11;
    $firstDigit = $remainder < 2 ? 0 : 11 - $remainder;

    if ($cpf[9] != $firstDigit) {
        return false;
    }

    $sum = 0;
    for ($i = 0; $i < 10; $i++) {
        $sum += $cpf[$i] * (11 - $i);
    }
    $remainder = $sum % 11;
    $secondDigit = $remainder < 2 ? 0 : 11 - $remainder;

    return $cpf[10] == $secondDigit;
}

function isValidCEP(string $cep): bool
{
    $cep = preg_replace('/[^0-9]/', '', $cep);

    return strlen($cep) == 8;
}

function calculateDailyReturn($amount, $startDate, $performanceMonthly, $adminTaxAnnual)
{
    $performanceMonthly = $performanceMonthly/100;
    $adminTaxAnnual = $adminTaxAnnual/100;
    // Data de início e data atual
    $startDate = new \DateTime($startDate);
    $currentDate = new \DateTime();

    // Calcula o número de dias ativos
    $daysActive = $startDate->diff($currentDate)->days;

    // Definir o número médio de dias no mês como 30 (ou pegue dinamicamente)
    $daysInMonth = 30;

    // Calcula a taxa de performance diária sem juros compostos
    // Taxa diária é a taxa mensal dividida pelos dias do mês
    $performanceDaily = $performanceMonthly / $daysInMonth;

    // Calcula a taxa de administração diária (não composta)
    $adminTaxDaily = $adminTaxAnnual / 365;

    // Calcula o retorno bruto com base nos dias ativos, sem juros compostos
    // Multiplica a taxa diária pelos dias ativos para calcular o retorno
    $grossReturn = $amount * ($performanceDaily * $daysActive);

    // Subtrair a taxa de administração do montante inicial
    $adminFee = $amount * ($adminTaxDaily * $daysActive);

    // Retorno final: Valor inicial + Retorno bruto - Taxa de administração
    $currentValue = $amount + $grossReturn - $adminFee;

    // Arredondar os valores para 2 casas decimais (centavos)
    $grossReturn = round($grossReturn, 2);
    $adminFee = round($adminFee, 2);
    $currentValue = round($currentValue, 2);
    $amount = round($amount, 2);

    // Retorno do array com os cálculos corretos
    return [
        'investimento_inicial' => $amount,                // Valor inicial investido
        'retorno_bruto' => $grossReturn,                  // Retorno gerado pela taxa de performance
        'admin_fee' => $adminFee,                         // Taxa de administração acumulada
        'valor_atual' => $currentValue,                   // Valor final (investimento inicial + retorno bruto - taxa de administração)
        'dias_ativos' => $daysActive,                     // Número de dias que o investimento está ativo
    ];
}

// Função para validar CPF
function checkIsCPF($cpf) {
    // Valida formato e dígitos do CPF
    $invalidCpfs = [
        '00000000000', '11111111111', '22222222222', '33333333333', '44444444444',
        '55555555555', '66666666666', '77777777777', '88888888888', '99999999999'
    ];
    if (strlen($cpf) != 11 || in_array($cpf, $invalidCpfs)) {
        return false;
    }
    // Cálculo de validação de dígitos do CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}
