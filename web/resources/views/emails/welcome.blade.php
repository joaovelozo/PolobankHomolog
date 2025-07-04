<!DOCTYPE html>
<html>

<head>
    <title>Bem-vindo ao Polocal Bank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td align="center" style="padding: 20px; background-color: #000000; border: 2px solid #000000;">
                            <img src="https://polocalbank.com.br/assets/logo.svg" alt="Polocal Bank" style="max-width: 150px; width: 100%; height: auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px; text-align: center;">
                            <h1 style="color: #007bff; font-size: 24px;">Parabéns, sua conta Polocal Bank foi ativada!</h1>
                            <p style="font-size: 16px; color: #333333;">
                                Olá <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="font-size: 16px; color: #333333;">
                                Seja bem-vindo ao Polocal Bank! Sua conta já está ativa e você pode acessar normalmente nossa plataforma.
                            </p>
                            <p style="font-size: 16px; color: #333333;">
                                Aproveite nossos serviços de pagamentos, transferências e muito mais.
                            </p>

                            <a href="https://polocalbank.com.br/login" style="display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 4px; margin-top: 20px; font-size: 16px;">
                                Acessar Minha Conta
                            </a>

                            <p style="font-size: 14px; color: #999999; margin-top: 40px;">
                                Se você não solicitou essa conta, pode ignorar este e-mail.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f4f4f4; text-align: center; padding: 20px;">
                            <p style="font-size: 12px; color: #777777;">
                                © 2025 Polocal Bank. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
