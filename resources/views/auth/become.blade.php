<!DOCTYPE html>
<html lang="en">

<head>
    <title>Polocal Bank</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/general-sans?styles=135312,135310,135313,135303">

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap/bootstrap.min.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/fav/site.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!--Mask!-->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>



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

        .image-container {
            display: flex;
            gap: 10px;
            /* Espaço entre as imagens */
        }

        .image-container img {
            width: 70px;
            /* Largura fixa para as imagens */
            height: auto;
            /* Altura automática para manter a proporção */
        }

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .modal-body {
            padding: 20px;
        }

        form .form-group label {
            font-weight: bold;
        }

        form .form-control {
            border-radius: 5px;
            padding: 12px;
        }

        form button {
            border-radius: 5px;
            padding: 10px;
            font-weight: bold;
        }

        #phone-error-message,
        #code-error-message {
            font-size: 14px;
            color: #dc3545;
        }

        /* Estilo do Spinner */
        .spinner {
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.6);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Animação de rotação */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Ícone de sucesso */
        .success-icon {
            display: inline-block;
            color: #fff;
            font-size: 24px;
        }

        /* Ocultar o texto enquanto carrega */
        .loading-text {
            display: none;
        }
    </style>
</head>

<body>
    <div class="">
        <!-- Modal -->



        <section class="pt-16 pb-32">
            <div class="container">
                <div class="mw-md mx-auto text-center">
                    <img src="{{ asset('assets/icon.png') }}" width="90px" alt=""></a>
                    <h3 class="mb-4">Crie Sua Conta PF</h3>
                    <p class="fs-8 text-secondary mb-12">Seu Banco Completo</p>
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
                    <form method="POST" id="documentForm" action="{{ route('register') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ request()->get('plan_id') }}">
                        <input type="hidden" name="agency_id" value="{{ $agency }}">
                        <input type="hidden" name="imagedoc" id="imagedoc">
                        <input type="hidden" name="imagedoc_verso" id="imagedoc_verso">
                        <input type="hidden" name="imagecomprovante" id="imagecomprovante">
                        <input type="hidden" name="imageself" id="imageself">


                        <h5 class="mb-5">Dados Pessoais</h5>

                        <div class="mb-5">
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                placeholder="Digite Seu Nome" required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" name="nameMother"
                                value="{{ old('nameMother') }}" placeholder="Qual o nome da sua Mãe?" required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" name="username" value="{{ old('username') }}"
                                placeholder="Como Gostaria de Ser Chamado?" required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="cpfCnpj"
                                value="{{ old('documentNumber') }}" name="documentNumber"
                                placeholder="Digite o Número do Seu CPF" required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="identityDocument"
                                value="{{ old('identityDocument') }}" name="identityDocument"
                                placeholder="Digite O Número de Identidade" required>
                        </div>
                        <div class="mb-5">
                            <label style="display: block; text-align: left;"><b>Data de Emissão</b></label>
                            <input class="form-control" type="date" id="issueDate" value="{{ old('issueDate') }}"
                                name="issueDate" placeholder="Data de Emissão" required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="issuingAgency"
                                value="{{ old('issuingAgency') }}" name="issuingAgency" placeholder="Orgão Emissor"
                                required>
                        </div>
                        <div class="mb-5">
                            <div class="form-group">
                                <label for="stateSelect" style="display: block; text-align: left;">Estado
                                    Emissor</label>
                                <select class="form-control" id="state" name="issuingState" required>
                                    <option value="" disabled selected>Selecione o Estado</option>
                                    @foreach (['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $state)
                                        <option value="{{ $state }}"
                                            {{ old('state') === $state ? 'selected' : '' }}>
                                            {{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-5">
                            <select class="form-control" name="gender" required>
                                <option value="" disabled {{ old('gender') === null ? 'selected' : '' }}>
                                    Selecione o Sexo</option>
                                <option value="MASCULINO" {{ old('gender') === 'MASCULINO' ? 'selected' : '' }}>
                                    Masculino
                                </option>
                                <option value="FEMININO" {{ old('gender') === 'Feminino' ? 'selected' : '' }}>Feminino
                                </option>
                                <option value="OUTROS" {{ old('gender') === 'OUTROS' ? 'selected' : '' }}>Outro
                                </option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <div class="form-group">
                                <label for="idMaritalStatus" style="display: block; text-align: left;">Estado
                                    Civil</label>
                                <select class="form-control" id="idMaritalStatus" name="idMaritalStatus" required>
                                    <option value="">Selecione...</option>
                                    <option value="single" {{ old('idMaritalStatus') == 'single' ? 'selected' : '' }}>
                                        Solteiro(a)</option>
                                    <option value="married"
                                        {{ old('idMaritalStatus') == 'married' ? 'selected' : '' }}>
                                        Casado(a)</option>
                                    <option value="separate"
                                        {{ old('idMaritalStatus') == 'separate' ? 'selected' : '' }}>
                                        Separado(a)</option>
                                    <option value="widower"
                                        {{ old('idMaritalStatus') == 'widower' ? 'selected' : '' }}>
                                        Viúvo(a)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="political" style="display: block; text-align: left;"><b>Você é Pessoa
                                    Politicamente Exposta</b></label>
                            <select name="political" id="political" class="form-control" required>
                                <option value="0" {{ old('political') == '0' ? 'selected' : '' }}>Não</option>
                                <option value="1" {{ old('political') == '1' ? 'selected' : '' }}>Sim</option>
                            </select>

                        </div>

                        <div class="mb-5">
                            <input class="form-control" type="tel" id="phone" name="phoneNumber"
                                placeholder="Número de Telefone" value="{{ old('phoneNumber') }}" required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="tel" id="phone" name="cellPhone"
                                placeholder="Digite seu WhatsApp" value="{{ old('cellPhone') }}" required>
                        </div>

                        <div class="mb-5">
                            <label for="idMaritalStatus" style="display: block; text-align: left;"><b>Data de
                                    Nascimento</b></label>
                            <input class="form-control" type="date" id="birthdate"
                                value="{{ old('birthdate') }}" name="birthdate" required
                                placeholder="Data de Nascimento">
                        </div>


                        <h5>Dados Financeiros</h5>

                        <div class="mb-5">
                            <input class="form-control" type="text" placeholder="Renda Mensal"
                                value="{{ old('rent') }}" name="rent" id="income">
                        </div>

                        <h5>Dados Residenciais</h5>

                        <div class="mb-5">
                            <input class="form-control" type="text" id="cep" name="zipCode" required
                                placeholder="CEP" required onblur="buscarEndereco()" value="{{ old('zipcode') }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="endereco" name="address"
                                value="{{ old('address') }}" required placeholder="Endereço" readonly>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" name="addressNumber" placeholder="Número"
                                value="{{ old('addressNumber') }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="bairro" name="neighborhood"
                                value="{{ old('neighborhood') }}" required placeholder="Bairro" readonly>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="cidade" name="city"
                                placeholder="Cidade" value="{{ old('city') }}" readonly required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="estado" value="{{ old('state') }}"
                                name="state" placeholder="Estado" readonly required>
                        </div>

                        <div class="mb-5">
                            <input class="form-control" type="text" name="complement"
                                value="{{ old('complement') }}" placeholder="Complemento">
                        </div>

                        <h5>Dados de Acesso</h5>
                        <div class="mb-5">
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                placeholder="Digite Seu Email" required>
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="password" name="password" placeholder="Senha"
                                required>
                        </div>

                        <div class="mb-5">
                            <input class="form-control" type="password" name="password_confirmation"
                                placeholder="Repita a Senha" required>
                        </div>

                        <h5 class="mb-5">Validação de Documentos</h5>
                        <p>Selecione uma imagem</p>

                        <!-- Frente do Documento -->
                        <div class="mb-5">
                            <label><b>Frente do Documento de Identificação</b></label>
                            <input class="form-control" type="file" id="fileImagedoc" accept="image/*" required>
                        </div>

                        <!-- Verso do Documento -->
                        <div class="mb-5">
                            <label><b>Verso do Documento de Identificação</b></label>
                            <input class="form-control" type="file" id="fileImagedocVerso" accept="image/*"
                                required>
                        </div>

                        <!-- Comprovante -->
                        <div class="mb-5">
                            <label><b>Comprovante de Endereço</b></label>
                            <input class="form-control" type="file" id="fileComprovante" accept="image/*"
                                required>
                        </div>

                        <!-- Selfie -->
                        <div class="mb-5">
                            <label><b>Selfie com Documento</b></label>
                            <input class="form-control" type="file" id="fileSelfie" accept="image/*" required>
                        </div>





                        <div class="form-check text-start mt-4">
                            <input class="form-check-input" type="checkbox" id="agreeTerms"
                                onchange="toggleSubmitButton()">
                            <label class="form-check-label text-light small" for="agreeTerms">
                                <a href="{{ route('site.terms') }}" target="blank">Concordo Com os Termos de Uso</a>
                                <a href="{{ route('site.privacy') }}" target="blank">e Política de Privacidade</a>
                            </label>
                        </div>
                        <br>
                        <br>
                        <button id="submitButton" class="btn w-100 mb-8 btn-primary shadow" disabled>Criar
                            Conta</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <style>
        video,
        canvas {
            width: 100%;
            max-width: 320px;
            height: auto;
        }

        .image-preview img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }

        .hidden {
            display: none;
        }

        /* Estilos para inputs de arquivo */
        .file-input-container {
            margin-bottom: 1rem;
        }

        .file-input-container input[type="file"] {
            display: none;
        }

        .file-input-label {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #0d6efd;
            color: #fff;
            border-radius: 0.25rem;
            cursor: pointer;
        }

        .file-input-label:hover {
            background-color: #0b5ed7;
        }
    </style>
    <script>
        // Função para detectar dispositivo móvel
        function isMobileDevice() {
            return /Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // Mostrar ou esconder elementos com base no dispositivo
        document.addEventListener("DOMContentLoaded", function() {
            if (isMobileDevice()) {
                document.querySelectorAll('.desktop-button').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.mobile-input').forEach(el => el.style.display = 'block');
            } else {
                document.querySelectorAll('.desktop-button').forEach(el => el.style.display = 'inline-block');
                document.querySelectorAll('.mobile-input').forEach(el => el.style.display = 'none');
            }
        });

        let currentStream = null;
        let currentSide = null;

        function startWebcam(side) {
            currentSide = side;
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            let videoElement;
            if (side === 'imagedoc') {
                videoElement = document.getElementById('frontVideo');
            } else if (side === 'imagedoc_verso') {
                videoElement = document.getElementById('backVideo');
            } else if (side === 'imagecomprovante') {
                videoElement = document.getElementById('comprovanteVideo');
            } else {
                videoElement = document.getElementById('selfieVideo');
            }

            const saveButton = videoElement.parentElement.querySelector('button.btn-secondary');
            videoElement.classList.remove('hidden');
            saveButton.disabled = false;

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: side === 'imageself' ? 'user' : 'environment'
                    }
                })
                .then(stream => {
                    currentStream = stream;
                    videoElement.srcObject = stream;
                    videoElement.play();
                })
                .catch(err => {
                    console.error("Erro ao acessar a webcam: ", err);
                    alert("Não foi possível acessar a câmera. Por favor, verifique as permissões.");
                });
        }


        function captureImage(side) {
            if (!currentStream) {
                alert("A câmera não está ativa.");
                return;
            }

            let video, canvas, imageContainer, inputField;
            if (side === 'imagedoc') {
                video = document.getElementById('frontVideo');
                canvas = document.getElementById('frontCanvas');
                imageContainer = document.getElementById('frontImageContainerPreview');
                inputField = document.getElementById('imagedoc');
            } else if (side === 'imagedoc_verso') {
                video = document.getElementById('backVideo');
                canvas = document.getElementById('backCanvas');
                imageContainer = document.getElementById('backImageContainerPreview');
                inputField = document.getElementById('imagedoc_verso');
            } else if (side === 'imagecomprovante') {
                video = document.getElementById('comprovanteVideo');
                canvas = document.getElementById('comprovanteCanvas');
                imageContainer = document.getElementById('comprovanteImageContainerPreview');
                inputField = document.getElementById('imagecomprovante');
            } else {
                video = document.getElementById('selfieVideo');
                canvas = document.getElementById('selfieCanvas');
                imageContainer = document.getElementById('selfieImageContainerPreview');
                inputField = document.getElementById('imageself');
            }

            const context = canvas.getContext('2d');
            const MAX_WIDTH = 640;
            const scale = MAX_WIDTH / video.videoWidth;
            const newWidth = MAX_WIDTH;
            const newHeight = video.videoHeight * scale;

            canvas.width = newWidth;
            canvas.height = newHeight;
            context.drawImage(video, 0, 0, newWidth, newHeight);

            const imageDataURL = canvas.toDataURL('image/png');
            inputField.value = imageDataURL;

            imageContainer.innerHTML = `<img src="${imageDataURL}" alt="Pré-visualização da imagem">`;
            // Obter a imagem em base64
            const imageData = canvas.toDataURL('image/jpeg');

            // Mostrar a imagem capturada
            imageContainer.innerHTML = `<img src="${imageData}" alt="Imagem Capturada" />`;

            // Preencher o campo oculto com os dados da imagem
            inputField.value = imageData;

            // Parar a câmera
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }

            // Esconder o vídeo
            video.classList.add('hidden');
            canvas.classList.add('hidden');

        }



        // Função para verificar se todas as imagens foram capturadas e habilitar o botão de submit
        function verificarImagensCapturadas() {
            //  const front = document.getElementById('document_front').value;
            // const back = document.getElementById('document_back').value;
            // const selfie = document.getElementById('selfie').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;

            const submitButton = document.getElementById('submitButton');

            //if (front && back && selfie && agreeTerms) {
            if (agreeTerms) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        }
        // Função para habilitar/desabilitar o botão de submit com base no checkbox
        function toggleSubmitButton() {
            verificarImagensCapturadas();
        }
        // Validação antes do envio do formulário
        document.getElementById('documentForm').addEventListener('submit', function(e) {
            //const front = document.getElementById('document_front').value;
            //const back = document.getElementById('document_back').value;
            //const selfie = document.getElementById('selfie').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;

            // Verifica se todas as condições estão atendidas
            //if (!front || !back || !selfie || !agreeTerms) {
            if (!agreeTerms) {
                e.preventDefault(); // Impede o envio do formulário
                alert("Por favor, aceite os termos antes de enviar.");
            } else {
                e.target.submit(); // Envia o formulário
                console.log("Formulário validado e enviado com sucesso!");
            }
        });
    </script>
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

    <!--Button Verify !-->
    <script>
        //function toggleSubmitButton() {
        //   const checkbox = document.getElementById('agreeTerms');
        //
        // Habilita ou desabilita o botão de envio com base na seleção do checkbox
        //    submitButton.disabled = !checkbox.checked;
        //}
    </script>
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

    <script>
        function handleFileUpload(side, input) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const imageData = e.target.result;

                let imageContainer, inputField;
                if (side === 'imagedoc') {
                    imageContainer = document.getElementById('frontImageContainer');
                    inputField = document.getElementById('imagedoc');
                } else if (side === 'imagedoc_verso') {
                    imageContainer = document.getElementById('backImageContainer');
                    inputField = document.getElementById('imagedoc_verso');
                } else if (side === 'imagecomprovante') {
                    imageContainer = document.getElementById('comprovanteImageContainer');
                    inputField = document.getElementById('imagecomprovante');
                } else {
                    imageContainer = document.getElementById('selfieImageContainer');
                    inputField = document.getElementById('imageself');
                }

                imageContainer.innerHTML = `<img src="${imageData}" alt="Imagem Selecionada" />`;
                inputField.value = imageData;
            };
            reader.readAsDataURL(file);
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#cpfCnpj').on('input', function() {
                // Remove caracteres especiais para realizar a validação
                let value = $(this).val().replace(/[^\d]/g, '');

                // Verifica se é um CNPJ (14 dígitos)
                if (value.length === 14) {
                    $('input[name="telemedicina"]').prop('checked',
                        false); // Desmarca o checkbox de telemedicina
                    $('input[name="telemedicina"]').closest('div')
                        .hide(); // Esconde o botão de telemedicina
                } else {
                    $('input[name="telemedicina"]').closest('div')
                        .show(); // Mostra o botão de telemedicina novamente
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#income').mask('000.000.000.000.000,00', {
                reverse: true
            });
        });
    </script>

    <script>
        function convertToBase64(inputId, hiddenId) {
            const inputFile = document.getElementById(inputId);
            const hiddenField = document.getElementById(hiddenId);

            inputFile.addEventListener('change', () => {
                const file = inputFile.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onloadend = () => {
                    hiddenField.value = reader.result; // base64 com prefixo data:image/jpeg;base64,...
                };
                reader.readAsDataURL(file);
            });
        }

        // Convertendo todos os campos
        convertToBase64('fileImagedoc', 'imagedoc');
        convertToBase64('fileImagedocVerso', 'imagedoc_verso');
        convertToBase64('fileComprovante', 'imagecomprovante');
        convertToBase64('fileSelfie', 'imageself');

        // Impede o envio se qualquer campo estiver vazio
        document.querySelector('form').addEventListener('submit', function(e) {
            const fields = ['imagedoc', 'imagedoc_verso', 'imagecomprovante', 'imageself'];
            for (const field of fields) {
                if (!document.getElementById(field).value) {
                    alert('Você deve selecionar todas as imagens antes de enviar o formulário.');
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>



</body>

</html>
