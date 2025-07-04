<!DOCTYPE html>
<html lang="en">

<head>
  <title>Polocal Bank</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/general-sans?styles=135312,135310,135313,135303">
  <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap/bootstrap.min.css')}}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/fav/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/fav/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/fav/favicon-16x16.png')}}">
  <link rel="manifest" href="{{asset('assets/fav/site.webmanifest')}}">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <!--Mask!-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6WF9P5M8E5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-6WF9P5M8E5');
    </script>


</head>

<body>
  <div class="">

    <section class="pt-16 pb-32">
      <div class="container">
        <div class="mw-md mx-auto text-center">
          <a class="d-inline-block mb-32" href="#"><img src="{{asset('assets/frontend/images/logo-png-1723144474190.webp')}}" alt=""></a>
          <h3 class="mb-4">Crie Sua Conta</h3>
          <p class="fs-8 text-secondary mb-12">Seja Bem Vindo a Comunidade Ral Bank</p>
          <div class="container bg-red-500 text-white">
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>

          <form method="POST" action="{{ route('validacao-facial') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="agency_id" value="{{ $agency }}">
            <h5 class="mb-5">Dados Pessoais</h5>

            <div class="mb-5">
              <input class="form-control" type="text" name="name" placeholder="Digite Seu Nome" required>
            </div>
            <div class="mb-5">
              <input class="form-control" type="email" name="email" placeholder="Digite Seu Email" required>
            </div>
            <div class="mb-5">
              <input class="form-control" type="tel" id="phone" name="phone" placeholder="Telefone" required>
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="cpfCnpj" name="document" placeholder="CPF ou CNPJ" required>
            </div>
            <div class="mb-5">
              <input class="form-control" type="date" id="birthdate" name="birthdate" required placeholder="Data de Nascimento">
            </div>
            <div class="mb-5">
              <select class="form-control" name="gender" required>
                <option value="" disabled selected>Selecione o Sexo</option>
                <option value="male">Masculino</option>
                <option value="female">Feminino</option>
                <option value="other">Outro</option>
              </select>
            </div>
            <h5>Dados Financeiros</h5>
            <div class="mb-5">
              <input class="form-control" type="text" placeholder="Profissão" name="profession">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" placeholder="Renda Mensal" name="icome">
            </div>
            <h5>Dados Residenciais</h5>

            <div class="mb-5">
              <input class="form-control" type="text" id="cep" name="zipcode" required placeholder="CEP" required onblur="buscarEndereco()">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="endereco" name="address" required placeholder="Endereço" readonly>
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" name="number" placeholder="Número">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="bairro" name="neighborhood" required placeholder="Bairro" readonly>
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="cidade" name="city" placeholder="Cidade" readonly>
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="estado" name="state" placeholder="Estado" readonly>
            </div>

            <div class="mb-5">
              <input class="form-control" type="text" name="complement" placeholder="Complemento">
            </div>

            <h5>Dados de Acesso</h5>
            <div class="mb-5">
              <input class="form-control" type="password" name="password" placeholder="Senha">
            </div>

            <div class="mb-5">
              <input class="form-control" type="password" name="password_confirmation" placeholder="Repita a Senha">
            </div>

            <h5 class="mb-5">Validação de Documentos</h5>
            <p> Faça o Upload ou Use sua WebCam</p>

            <label>Frente do Documento</label>

            <div class="mb-5">
              <input class="form-control" type="file" id="fileFront" name="document_front" placeholder="Documento Frente">
              <p> Selecione do Dispositivo</p>
              <p>Ou</p>
              <div class="mt-3">
                <button type="button" class="btn btn-primary" onclick="startWebcam('document_front')">Capturar com WebCam</button>
              </div>
            </div>

            <div id="frontCapture" class="mb-5" style="display:none;">
              <video id="frontVideo" width="320" height="240" autoplay></video>
              <button type="button" class="btn btn-success mt-2" onclick="captureImage('document_front')">Capturar</button>
              <canvas id="frontCanvas" style="display:none;"></canvas>
            </div>

            <label>Verso do Documento</label>
            <div class="mb-5">
              <input class="form-control" type="file" id="fileBack" name="document_back" placeholder="Documento Verso">
              <p> Selecione do Dispositivo</p>
              <p>Ou</p>
              <div class="mt-3">
                <button type="button" class="btn btn-primary" onclick="startWebcam('document_back')">Capturar com WebCam</button>
              </div>
            </div>

            <div id="backCapture" class="mb-5" style="display:none;">
              <video id="backVideo" width="320" height="240" autoplay></video>
              <button type="button" class="btn btn-success mt-2" onclick="captureImage('document_back')">Capturar</button>
              <canvas id="backCanvas" style="display:none;"></canvas>
            </div>

            <label>Selfie com Documento</label>
            <div class="mb-5">
              <input class="form-control" type="file" id="fileSelfie" name="selfie" placeholder="Selfie com Documento">
              <p> Selecione do Dispositivo</p>
              <p>Ou</p>
              <div class="mt-3">
                <button type="button" class="btn btn-primary" onclick="startWebcam('selfie')">Capturar Selfie</button>
              </div>
            </div>
            <div id="selfieCapture" class="mb-5" style="display:none;">
              <video id="selfieVideo" width="320" height="240" autoplay></video>
              <button type="button" class="btn btn-success mt-2" onclick="captureImage('selfie')">Capturar</button>
              <canvas id="selfieCanvas" style="display:none;"></canvas>
            </div>
            <br>
            <div class="form-check text-start mt-4">
              <input class="form-check-input" type="checkbox" id="agreeTerms" onchange="toggleSubmitButton()">
              <label class="form-check-label text-light small" for="agreeTerms">
                <a href="">Concordo Com os Termos de Uso</a>
              </label>
            </div>

            <br>
            <button id="submitButton" class="btn w-100 mb-8 btn-primary shadow" disabled>Criar Conta</button>
            <p class="d-flex flex-wrap align-items-center justify-content-center">

              <span class="me-1">Possui Conta?</span><a class="btn px-0 btn-link fw-bold" href="{{route('login')}}">Login</a>
            </p>
          </form>
        </div>
        <p class="d-flex flex-wrap align-items-center justify-content-center">
          <span class="me-1"> Dados Auditados<span class="me-1">

        </p>
        <p class="d-flex flex-wrap align-items-center justify-content-center">
          <a href="https://loja.serpro.gov.br/datavalid" target="_blank">
            <img src="{{asset('assets/frontend/serpro.png')}}" alt="" width="90px">
          </a>
        </p>
      </div>
    </section>
  </div>
  <script src="{{asset('assets/frontend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/frontend/js/main.js')}}"></script>

  <script>
    function buscarEndereco() {
      const cep = document.getElementById('cep').value.replace(/\D/g, '');
      if (cep !== '') {
        const validacep = /^[0-9]{8}$/;
        if (validacep.test(cep)) {
          fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
              if (!data.erro) {
                document.getElementById('endereco').value = data.logradouro;
                document.getElementById('cidade').value = data.localidade;
                document.getElementById('bairro').value = data.bairro;
                document.getElementById('estado').value = data.uf;
              } else {
                alert('CEP não encontrado.');
              }
            })
            .catch(error => {
              alert('Erro ao buscar o CEP.');
              console.error(error);
            });
        } else {
          alert('Formato de CEP inválido.');
        }
      }
    }
  </script>
  <!--Button Verify !-->
  <script>
    function toggleSubmitButton() {
      const checkbox = document.getElementById('agreeTerms');
      const submitButton = document.getElementById('submitButton');
      // Habilita ou desabilita o botão de envio com base na seleção do checkbox
      submitButton.disabled = !checkbox.checked;
    }
  </script>
  <!--Start WebCam!-->
  <script>
    // Função para habilitar a webcam
    function startWebcam(type) {
      const videoElement = document.getElementById(`${type}Video`);
      const captureDiv = document.getElementById(`${type}Capture`);
      navigator.mediaDevices.getUserMedia({
          video: true
        })
        .then((stream) => {
          videoElement.srcObject = stream;
          captureDiv.style.display = 'block';
        })
        .catch((err) => {
          console.error("Erro ao acessar a webcam:", err);
          alert("Erro ao acessar a webcam.");
        });
    }

    // Função para capturar a imagem
    function captureImage(type) {
      const videoElement = document.getElementById(`${type}Video`);
      const canvasElement = document.getElementById(`${type}Canvas`);
      const context = canvasElement.getContext('2d');
      // Definir tamanho do canvas igual ao vídeo
      canvasElement.width = videoElement.videoWidth;
      canvasElement.height = videoElement.videoHeight;
      // Capturar o quadro do vídeo
      context.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
      canvasElement.style.display = 'block';
      // Encerrar a transmissão da webcam
      const stream = videoElement.srcObject;
      const tracks = stream.getTracks();
      tracks.forEach(track => track.stop());
      videoElement.srcObject = null;
    }
  </script>
  <!-- Mask!-->
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

  <!--Date Invert!-->
  <script>
    $(function() {
      $("#birthdate").datepicker({
        dateFormat: 'yy-mm-dd', // Define o formato da data
        changeMonth: true, // Permite a seleção do mês
        changeYear: true // Permite a seleção do ano
      });
    });
  </script>


</body>

</html>
