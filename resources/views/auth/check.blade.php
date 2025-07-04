<!DOCTYPE html>
<html lang="en">

<head>
  <title>Polocal Bank</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/general-sans?styles=135312,135310,135313,135303">
  <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap/bootstrap.min.css')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/frontend/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/frontend/favicon-16x16.png')}}">
  <link rel="manifest" href="{{asset('assets/frontend/site.webmanifest')}}">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>

<body>
  <div class="">
    <section class="pt-16 pb-32">
      <div class="container">
        <div class="mw-md mx-auto text-center">
          <a class="d-inline-block mb-32" href="#">
            <img src="{{asset('assets/icon.png')}}" width="90px" alt=""></a>
          <h3 class="mb-4">Atualização de Dados</h3>
          <p class="fs-8 text-secondary mb-12">Digite seu E-mail para verificação dos Dados</p>
          <form action="{{route('check.document')}}" method="POST">
            @csrf
            <div class="mb-5">
              <input class="form-control" type="text" placeholder="Digite o E-mail" name="email" id="email" value="{{session('document')}}" required>
            </div>
            <div class="mb-5 position-relative"></div>

            <!-- Substitua a tag <a> por um botão de tipo submit -->
            <button type="submit" class="btn w-100 mb-8 btn-primary shadow">Validar Meus Dados</button>

            <p class="d-flex flex-wrap align-items-center justify-content-center">
              <span class="me-1">Não Possuí Conta?</span><a class="btn px-0 btn-link fw-bold" href="{{route('register')}}/1">Criar Minha Conta</a>
            </p>
          </form>
        </div>
      </div>
    </section>
  </div>
  <script src="{{asset('assets/frontend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/frontend/js/main.js')}}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#phone').mask('(00) 00000-0000');
      $('#whatsapp').mask('(00) 00000-0000');
      $('#cep').mask('00.000-000');

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
    });
  </script>
</body>

</html>
