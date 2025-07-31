<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abertura de Conta Pessoa Física</title>
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/general-sans?styles=135312,135310,135313,135303">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap/bootstrap.min.css') }}">
    <style>
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 10px;
            background: #f9f9f9;
        }

        .message.user {
            text-align: right;
            color: blue;
            margin: 5px 0;
        }

        .message.bot {
            text-align: left;
            color: green;
            margin: 5px 0;
        }

        .h3 {
            color: white;
        }
    </style>
</head>

<body>

    <!-- Modal de Boas-vindas -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="welcomeModalLabel">Bem-vindo ao Polocal Bank IA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                  <p><strong>Antes de começar, aqui vão algumas dicas:</strong></p>
                    <ul>
                         <li>Documento de Identificação <strong>não podem ter mais de 5 Anos</strong></li>
                                       <li>Comprovantes de residência <strong>não podem ter mais de 90 dias</strong></li>
                         <li>Não precisa digitar pontos ou traços nos campos: <strong>CPF, CNPJ, CEP, Valores
                                Monetários</strong>. Digite apenas números.</li>
                        <li>As imagens dos documentos devem ser formato <strong>Imagem (PNG,JPG)</strong> e não podem ter mais de <strong>3 MB</strong> de tamanho.
                        </li>
                        <li>Em caso de problemas ou dúvidas, entre em contato com o <strong><a href="https://virtualbrain.com.br/" target="blank">suporte</a></strong>.</li>
                    </ul>
                    <p class="mt-3">Abra agora mesmo sua conta e aproveite as vantagens!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Começar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm mt-4">
                    <br>
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Polocal Bank IA - Conta PF</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="chat-messages" id="chatMessages"></div>
                    </div>
                    <div class="card-footer">
                        <form class="chat-form" id="chatForm">
                            <div class="mb-3">
                                <label class="form-label visually-hidden" for="chatInput">Digite Corretamente</label>
                                <div class="input-group">
                                    <input class="form-control chat-input" id="chatInput" type="text" name="message"
                                        placeholder="Digite sua mensagem..." autocomplete="off" />
                                    <button class="btn btn-primary chat-send-btn" type="submit"
                                        id="btnSend">Enviar</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="inputImage" class="form-label">Enviar imagem:</label>
                                <input class="form-control" type="file" id="inputImage" accept="image/*" />
                            </div>
                            <button type="button" class="btn btn-danger" onclick="resetChat()">Limpar Conversa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('assets/frontend/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        const onboardingUrl = "{{ route('chat.onboarding', ['agency_id' => $agency]) }}";
        const startUrl = "{{ route('chat.start', ['agency_id' => $agency]) }}";
        const resetUrl = "{{ route('chat.reset') }}";

        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const inputImage = document.getElementById('inputImage');
        const btnSend = document.getElementById('btnSend');

        function addMessage(text, fromUser = false) {
            const div = document.createElement('div');
            div.className = 'message ' + (fromUser ? 'user' : 'bot');
            div.innerHTML = text;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        async function sendText() {
            const message = chatInput.value.trim();
            if (!message) return;
            addMessage(message, true);
            chatInput.value = '';
            chatInput.disabled = true;
            btnSend.disabled = true;

            try {
                const formData = new FormData();
                formData.append('message', message);

                const response = await fetch(onboardingUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                addMessage(data.reply, false);
            } catch (err) {
                console.error('Erro:', err);
                addMessage('Erro na comunicação com o servidor.', false);
            }

            chatInput.disabled = false;
            btnSend.disabled = false;
            chatInput.focus();
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendText();
        });

        inputImage.addEventListener('change', () => {
            const file = inputImage.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = async function(event) {
                const base64Completo = event.target.result;

                // Remove o prefixo "data:image/...;base64,"
                const base64Limpo = base64Completo.replace(/^data:image\/[a-zA-Z]+;base64,/, '');

                addMessage('<i>Enviando imagem...</i>', true);
                inputImage.disabled = true;

                try {
                    const formData = new FormData();
                    formData.append('image', base64Limpo);

                    const response = await fetch(onboardingUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    addMessage(data.reply, false);
                } catch (err) {
                    console.error('Erro:', err);
                    addMessage('Erro ao enviar a imagem.', false);
                }

                inputImage.value = '';
                inputImage.disabled = false;
                chatInput.focus();
            };
            reader.readAsDataURL(file);
        });

        async function resetChat() {
            const response = await fetch(resetUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            alert(data.reply);
            window.location.reload();
        }

        window.addEventListener('load', async () => {
            const res = await fetch(startUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            addMessage(data.reply, false);
        });
    </script>

    <script>
        window.addEventListener('load', () => {
            // Exibe o modal de boas-vindas ao carregar a página
            const welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
            welcomeModal.show();

            // Chamada normal do chat
            fetch(startUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    addMessage(data.reply, false);
                })
                .catch(err => console.error('Erro ao iniciar chat:', err));
        });
    </script>

</body>

</html>
