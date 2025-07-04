


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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

  <style>
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked+.slider {
      background-color: #2196F3;
    }

    input:focus+.slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
  </style>
</head>

<body>
  <div class="">

    <section class="pt-16 pb-32">
      <div class="container">
        <div class="mw-md mx-auto text-center">
          <a class="d-inline-block mb-32" href="#"><img src="{{asset('assets/frontend/images/logo-png-1723144474190.webp')}}" alt=""></a>
          <h3 class="mb-4">Atualização de Conta</h3>
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

          <form method="POST" action="{{ route('update.user', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="agency_id" value="{{ $agency }}">
            <h5 class="mb-5">Dados Pessoais</h5>

            <div class="mb-5">
              <input class="form-control" type="text" name="name" placeholder="Digite Seu Nome" required value="{{ $user->name }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="email" name="email" placeholder="Digite Seu Email" required value="{{ $user->email }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="tel" id="phone" name="phone" placeholder="Telefone" required value="{{ $user->phone }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="cpfCnpj" name="document" placeholder="CPF ou CNPJ" required value="{{ $user->document }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="date" id="birthdate" name="birthdate" required placeholder="Data de Nascimento" value="{{ $user->birthdate }}">
            </div>
            <div class="mb-5">
              <select class="form-control" name="gender" required>
                <option value="" disabled>Selecione o Sexo</option>
                <option value="male" {{ ($user->gender=='male'?'selected':null) }}>Masculino</option>
                <option value="female" {{ ($user->gender=='female'?'selected':null) }}>Feminino</option>
                <option value="other" {{ ($user->gender=='other'?'selected':null) }}>Outro</option>
              </select>
            </div>
            <h5>Dados Financeiros</h5>
            <div class="mb-5">
              <input class="form-control" type="text" placeholder="Profissão" name="profession" value="{{ $user->profession }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" placeholder="Renda Mensal" name="icome" value="{{ $user->icome }}">
            </div>
            <h5>Dados Residenciais</h5>

            <div class="mb-5">
              <input class="form-control" type="text" id="cep" name="zipcode" required placeholder="CEP" required onblur="buscarEndereco()" value="{{ $user->zipcode }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="endereco" name="address" required placeholder="Endereço" readonly value="{{ $user->address }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" name="number" placeholder="Número" value="{{ $user->number }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="bairro" name="neighborhood" required placeholder="Bairro" readonly value="{{ $user->neighborhood }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="cidade" name="city" placeholder="Cidade" readonly value="{{ $user->city }}">
            </div>
            <div class="mb-5">
              <input class="form-control" type="text" id="estado" name="state" placeholder="Estado" readonly value="{{ $user->state }}">
            </div>

            <div class="mb-5">
              <input class="form-control" type="text" name="complement" placeholder="Complemento" value="{{ $user->complement }}">
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
            <br>
            <button id="submitButton" class="btn w-100 mb-8 btn-primary shadow" disabled>Atualizar Conta</button>
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
