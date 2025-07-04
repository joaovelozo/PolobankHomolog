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
                    <img src="{{asset('assets/icon.png')}}" width="90px" alt=""></a>
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

                    <form method="POST" id="documentForm" action="{{ route('update.user') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <input type="hidden" name="agency_id" value="{{ $agency->id }}">
                        <input type="hidden" id="document_front" name="document_front">
                        <input type="hidden" id="document_back" name="document_back">
                        <input type="hidden" id="selfie" name="selfie">
                        <h5 class="mb-5">Dados Pessoais</h5>

                        <div class="mb-5">
                            <input class="form-control" type="text" name="name" placeholder="Digite Seu Nome"
                                required value="{{ $user->name }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="email" name="email" placeholder="Digite Seu Email"
                                required value="{{ $user->email }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="tel" id="phone" name="phone"
                                placeholder="Telefone" required value="{{ $user->phone }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="cpfCnpj" name="document"
                                placeholder="CPF ou CNPJ" required value="{{ $user->document }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="date" id="birthdate" name="birthdate" required
                                placeholder="Data de Nascimento" value="{{ $user->birthdate }}">
                        </div>
                        <div class="mb-5">
                            <select class="form-control" name="gender" required>
                                <option value="" disabled>Selecione o Sexo</option>
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : null }}>Masculino
                                </option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : null }}>Feminino
                                </option>
                                <option value="other" {{ $user->gender == 'other' ? 'selected' : null }}>Outro
                                </option>
                            </select>
                        </div>
                        <h5>Dados Financeiros</h5>
                        <div class="mb-5">
                            <input class="form-control" type="text" placeholder="Profissão" name="profession"
                                value="{{ $user->profession }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" placeholder="Renda Mensal" name="icome"
                            id="income" value="{{ $user->icome }}">
                        </div>
                        <h5>Dados Residenciais</h5>

                        <div class="mb-5">
                            <input class="form-control" type="text" id="cep" name="zipcode" required
                                placeholder="CEP" required onblur="buscarEndereco()" value="{{ $user->zipcode }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="endereco" name="address" required
                                placeholder="Endereço" readonly value="{{ $user->address }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" name="number" placeholder="Número"
                                value="{{ $user->number }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="bairro" name="neighborhood" required
                                placeholder="Bairro" readonly value="{{ $user->neighborhood }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="cidade" name="city"
                                placeholder="Cidade" readonly value="{{ $user->city }}">
                        </div>
                        <div class="mb-5">
                            <input class="form-control" type="text" id="estado" name="state"
                                placeholder="Estado" readonly value="{{ $user->state }}">
                        </div>

                        <div class="mb-5">
                            <input class="form-control" type="text" name="complement" placeholder="Complemento"
                                value="{{ $user->complement }}">
                        </div>

                        <h5>Dados de Acesso</h5>
                        <div class="mb-5">
                            <input class="form-control" type="password" name="password" placeholder="Senha">
                        </div>

                        <div class="mb-5">
                            <input class="form-control" type="password" name="password_confirmation"
                                placeholder="Repita a Senha">
                        </div>


                        <h5 class="mb-5">Validação de Documentos</h5>
                        <p>Use a WebCam ou selecione uma imagem</p>

                        <!-- Frente do Documento -->
                        <div class="mb-5">
                            <label>Frente do Documento</label>
                            <div>
                                <!-- Botão para Desktop -->
                                <button type="button" class="btn btn-primary mb-2 desktop-button" onclick="startWebcam('document_front')">Tire uma Foto da Frente do Documento</button>
                                <video id="frontVideo" autoplay class="hidden"></video>
                                <canvas id="frontCanvas" class="hidden"></canvas>
                                <div id="frontImageContainerPreview" class="image-preview"></div>
                                <button type="button" class="btn btn-secondary mt-2 desktop-button" onclick="captureImage('document_front')" disabled>Salvar Frente do Documento</button>

                                <!-- Input de Arquivo para Móveis -->
                                <div class="file-input-container mobile-input">
                                    <label for="frontFile" class="file-input-label">Selecionar Frente do Documento</label>
                                    <input type="file" accept="image/*" capture="environment" id="frontFile" onchange="handleFileUpload('document_front', this)">
                                    <div id="frontImageContainer" class="image-preview"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Verso do Documento -->
                        <div class="mb-5">
                            <label>Documento - Verso</label>
                            <div>
                                <!-- Botão para Desktop -->
                                <button type="button" class="btn btn-primary mb-2 desktop-button" onclick="startWebcam('document_back')">Tire uma Foto do Verso do Documento</button>
                                <video id="backVideo" autoplay class="hidden"></video>
                                <canvas id="backCanvas" class="hidden"></canvas>
                                <div id="backImageContainerPreview" class="image-preview"></div>
                                <button type="button" class="btn btn-secondary mt-2 desktop-button" onclick="captureImage('document_back')" disabled>Salvar Verso do Documento</button>

                                <!-- Input de Arquivo para Móveis -->
                                <div class="file-input-container mobile-input">
                                    <label for="backFile" class="file-input-label">Selecionar Verso do Documento</label>
                                    <input type="file" accept="image/*" capture="environment" id="backFile" onchange="handleFileUpload('document_back', this)">
                                    <div id="backImageContainer" class="image-preview"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Selfie -->
                        <div class="mb-5">
                            <label>Selfie</label>
                            <div>
                                <!-- Botão para Desktop -->
                                <button type="button" class="btn btn-primary mb-2 desktop-button" onclick="startWebcam('selfie')">Tire uma Selfie com Documento</button>
                                <video id="selfieVideo" autoplay class="hidden"></video>
                                <canvas id="selfieCanvas" class="hidden"></canvas>
                                <div id="selfieImageContainerPreview" class="image-preview"></div>
                                <button type="button" class="btn btn-secondary mt-2 desktop-button" onclick="captureImage('selfie')" disabled>Salvar Selfie com Documento</button>

                                <!-- Input de Arquivo para Móveis -->
                                <div class="file-input-container mobile-input">
                                    <label for="selfieFile" class="file-input-label">Selecionar Selfie com Documento</label>
                                    <input type="file" accept="image/*" capture="user" id="selfieFile" onchange="handleFileUpload('selfie', this)">
                                    <div id="selfieImageContainer" class="image-preview"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Exibir imagens capturadas -->
                        <div id="capturedImages" class="mb-5">
                            <h5>Imagens Capturadas:</h5>
                            <div class="image-container row">
                                <div class="col-12 col-md-4" id="frontImageContainerPreview"></div>
                                <div class="col-12 col-md-4" id="backImageContainerPreview"></div>
                                <div class="col-12 col-md-4" id="selfieImageContainerPreview"></div>
                            </div>
                        </div>
                        <br>

                        <div class="form-check text-start mt-4">
                            <input class="form-check-input" type="checkbox" id="agreeTerms"
                                onchange="toggleSubmitButton()">
                            <label class="form-check-label text-light small" for="agreeTerms">
                                <a href="{{route('site.terms')}}" target="_blank">Concordo Com os Termos de Uso</a> <a href="{{route('site.privacy')}}" target="_blank">e Política de Privacidade</a>
                            </label>
                        </div>
                        <br>
                        <br>
                        <button id="submitButton" class="btn w-100 mb-8 btn-primary shadow" disabled>Atualizar
                            Conta</button>
                        <p class="d-flex flex-wrap align-items-center justify-content-center">
                            <span class="me-1">Possui Conta?</span><a class="btn px-0 btn-link fw-bold"
                                href="{{ route('login') }}">Login</a>
                        </p>
                    </form>
                </div>
                <p class="d-flex flex-wrap align-items-center justify-content-center">
                    <span class="me-1"> Dados Auditados<span class="me-1">

                </p>
                <p class="d-flex flex-wrap align-items-center justify-content-center">
                    <a href="https://loja.serpro.gov.br/datavalid" target="_blank">
                        <img src="{{ asset('assets/frontend/serpro.png') }}" alt="" width="90px">
                    </a>
                </p>
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

    <script src="{{ asset('assets/frontend/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

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
        // function toggleSubmitButton() {
        //   const checkbox = document.getElementById('agreeTerms');
        //    const submitButton = document.getElementById('submitButton');

        // Habilita ou desabilita o botão de envio com base na seleção do checkbox
        //   submitButton.disabled = !checkbox.checked;
        // }
    </script>
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
            // Parar qualquer stream existente
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            let videoElement, saveButton;
            if (side === 'document_front') {
                videoElement = document.getElementById('frontVideo');
                saveButton = videoElement.parentElement.querySelector('button.btn-secondary');
            } else if (side === 'document_back') {
                videoElement = document.getElementById('backVideo');
                saveButton = videoElement.parentElement.querySelector('button.btn-secondary');
            } else {
                videoElement = document.getElementById('selfieVideo');
                saveButton = videoElement.parentElement.querySelector('button.btn-secondary');
            }

            // Mostrar o vídeo e habilitar o botão de salvar
            videoElement.classList.remove('hidden');
            saveButton.disabled = false; // Habilitar o botão

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: side === 'selfie' ? 'user' : 'environment'
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

            let video, canvas, imageContainer, inputField, saveButton;
            if (side === 'document_front') {
                video = document.getElementById('frontVideo');
                canvas = document.getElementById('frontCanvas');
                imageContainer = document.getElementById('frontImageContainerPreview');
                inputField = document.getElementById('document_front');
                saveButton = video.parentElement.querySelector('button.btn-secondary');
            } else if (side === 'document_back') {
                video = document.getElementById('backVideo');
                canvas = document.getElementById('backCanvas');
                imageContainer = document.getElementById('backImageContainerPreview');
                inputField = document.getElementById('document_back');
                saveButton = video.parentElement.querySelector('button.btn-secondary');
            } else {
                video = document.getElementById('selfieVideo');
                canvas = document.getElementById('selfieCanvas');
                imageContainer = document.getElementById('selfieImageContainerPreview');
                inputField = document.getElementById('selfie');
                saveButton = video.parentElement.querySelector('button.btn-secondary');
            }

            // Ajustar a largura e altura do canvas para reduzir o tamanho da imagem
            const MAX_WIDTH = 640;
            const scale = MAX_WIDTH / video.videoWidth;
            const newWidth = MAX_WIDTH;
            const newHeight = video.videoHeight * scale;

            canvas.width = newWidth;
            canvas.height = newHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, newWidth, newHeight);

            // Reduzir a qualidade da imagem para melhorar a performance e evitar travamento
            const imageData = canvas.toDataURL('image/jpeg', 0.3); // Reduzindo para JPEG com 30% da qualidade

            // Criar o elemento de imagem para mostrar a prévia
            const img = document.createElement('img');
            img.src = imageData;
            imageContainer.innerHTML = ''; // Limpar imagens anteriores
            imageContainer.appendChild(img);

            // Salvar a imagem base64 no campo hidden
            inputField.value = imageData;

            // Parar a stream e esconder o vídeo
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
            video.classList.add('hidden');

            // Desabilitar o botão de salvar após a captura
            saveButton.disabled = true;

            // Feedback ao usuário
            alert("Imagem capturada e salva com sucesso!");

            // Opcional: Habilitar o botão de submit se todas as imagens estiverem capturadas
            verificarImagensCapturadas();
        }

        // Função para lidar com uploads de arquivos em dispositivos móveis
        function handleFileUpload(side, input) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const imageData = e.target.result;

                let imageContainer, inputField;
                if (side === 'document_front') {
                    imageContainer = document.getElementById('frontImageContainer');
                    inputField = document.getElementById('document_front');
                } else if (side === 'document_back') {
                    imageContainer = document.getElementById('backImageContainer');
                    inputField = document.getElementById('document_back');
                } else {
                    imageContainer = document.getElementById('selfieImageContainer');
                    inputField = document.getElementById('selfie');
                }

                // Exibir a imagem no preview
                const img = document.createElement('img');
                img.src = imageData;
                imageContainer.innerHTML = ''; // Limpar imagens anteriores
                imageContainer.appendChild(img);

                // Salvar a imagem base64 no campo hidden
                inputField.value = imageData;

                // Feedback ao usuário
                alert("Imagem selecionada e salva com sucesso!");

                // Opcional: Habilitar o botão de submit se todas as imagens estiverem capturadas
                verificarImagensCapturadas();
            };
            reader.readAsDataURL(file);
        }

        // Função para verificar se todas as imagens foram capturadas e habilitar o botão de submit
        function verificarImagensCapturadas() {
            const front = document.getElementById('document_front').value;
            const back = document.getElementById('document_back').value;
            const selfie = document.getElementById('selfie').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;

            const submitButton = document.getElementById('submitButton');

            if (front && back && selfie && agreeTerms) {
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
            const front = document.getElementById('document_front').value;
            const back = document.getElementById('document_back').value;
            const selfie = document.getElementById('selfie').value;

            if (!front || !back || !selfie) {
                e.preventDefault();
                alert("Por favor, capture todas as imagens antes de enviar.");
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

    <script>
        $(document).ready(function() {
            $('#cpfCnpj').on('input', function() {
                // Remove caracteres especiais para realizar a validação
                let value = $(this).val().replace(/[^\d]/g, '');

                // Verifica se é um CNPJ (14 dígitos)
                if (value.length === 14) {
                    $('input[name="telemedicina"]').prop('checked', false); // Desmarca o checkbox de telemedicina
                    $('input[name="telemedicina"]').closest('div').hide(); // Esconde o botão de telemedicina
                } else {
                    $('input[name="telemedicina"]').closest('div').show(); // Mostra o botão de telemedicina novamente
                }
            });
        });
    </script>

<script>
    $(document).ready(function() {
        $('#income').mask('000.000.000.000.000,00', {reverse: true});
    });
</script>
</body>

</html>
