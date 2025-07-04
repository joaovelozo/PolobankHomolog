    @extends('users.includes.master')
    @section('content')
        <style>
            .alert-badge {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 10px;
                background-color: green;
                color: white;
                border-radius: 5px;
                z-index: 1000;
            }

            .card {
                border: 1px solid #ccc;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 15px;
                margin: 20px;
            }

            .response {
                color: #fa270b;
            }
        </style>


        @php
            $id = Auth::user()->id;
            $clientId = App\Models\User::find($id);
            $status = $clientId->status;
        @endphp



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <div class="content-page">
            <div class="content">
                <!-- Start Content -->
                <div class="container-fluid">
                    <div class="row page-title align-items-center">
                        <div class="row">
                            <div class="col-md-3 col-xl-2">
                                <img src="{{ asset('assets/backend/images/logo.png') }}" class="img-fluid" alt="Logo"
                                    width="200px">
                            </div>
                            <div class="col-md-9 col-xl-4 align-self-center">
                                <h4 class="mb-1 mt-0">Empréstimos</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-2/3 px-4">
                @if (session()->has('message'))
                    <div class="alert-badge" id="notification-badge">
                        {{ session('message') }}
                    </div>
                @endif
                <hr>
                @if ($status === 'active')
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <ul class="nav nav-pills navtab-bg">
                                    <li class="nav-item">
                                        <a href="#comments" data-toggle="tab" aria-expanded="true" class="nav-link active">

                                            Acompanhe o Status
                                        </a>
                                    </li>
                                    <li class="nav-item">

                                        <a href="#attac-file" data-toggle="tab" aria-expanded="false" class="nav-link">
                                            <span class="badge badge-success float-right">{{ $responsesCount }}</span>
                                            Mensagens
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content text-muted">
                                    <div class="tab-pane show active" id="comments">

                                        <div class="media mb-4 font-size-14">
                                            <div class="mr-3">
                                                @if ($lending && $lending->created_at instanceof \Carbon\Carbon)
                                                    <span
                                                        class="font-size-16 avatar-title text-primary font-weight-semibold">
                                                        {{ $lending->created_at->format('d') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <h5 class="mt-0 font-size-15">Resposta Polocal Bank</b></h5>
                                                <p class="text-muted mb-1">
                                                    <a href=""
                                                        class="text-danger">{{ $lending ? $lending->message : '' }}</a>

                                                </p>
                                                <h5 class="mt-0 font-size-15">Status</b></h5>
                                                <p class="text-muted mb-1">
                                                    <a href=""
                                                        style="color: #00b81f;"><strong>{{ $lending ? $lending->status : '' }}</strong></a>

                                                </p>
                                                @if ($lending && $lending->status === 'enviar documentos')
                                                    <a href="{{ route('lending.edit', $lending->id) }}"
                                                        class="btn btn-primary">Enviar Documentos</a>
                                                @endif
                                                @if ($lending && $lending->status === 'assinar contrato')
                                                    <a href="{{ $lending->url }}" class="btn btn-success"
                                                        target="blank">Assinar
                                                        Contrato</a>
                                                @endif
                                            </div>
                                        </div>


                                    </div>

                                    <!--CHAT ONDE ESTA DUPLICANDO ABAIXO !-->

                                    <div class="tab-pane" id="attac-file">

                                        <div class="tab-pane" id="attac-file">
                                            @php
                                                $lendings = DB::table('lendings')->get();
                                            @endphp
                                            <div class="card-body">
                                                <h4 style="display: inline-block;">Chat do Empréstimo</h4>
                                                <!-- ... (outro código anterior) ... -->
                                                <div class="media">
                                                    <div class="media-body">
                                                        <h6 style="color: orange"><strong>Minhas Solicitações:</strong></h6>
                                                        @foreach ($lendings as $lending)
                                                            <div class="mb-3">

                                                                @php
                                                                $userId = Auth::user()->id;
                                                                $userLendings = App\Models\Lending::whereHas('responses.user', function($query) use ($userId) {
                                                                    $query->where('id', $userId);
                                                                })->get();
                                                            @endphp

                                                                @if ($responses->isNotEmpty())
                                                                    <h6 style="color: #00b81f"><strong>Mensagens Enviadas e
                                                                            Recebidas:</strong></h6>
                                                                    <div class="mb-3">
                                                                        @foreach ($responses as $single_response)
                                                                            <img src="{{ asset('assets/backend/images/logo.png') }}"
                                                                                alt="Avatar do usuário" width="20px">
                                                                            <br>
                                                                            <p style="color:#fa0000"><strong>Enviado
                                                                                    por:</strong>
                                                                                <b>{{ $single_response->user->name }}</b>
                                                                            </p>
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <p><strong>Mensagem:</strong></p>
                                                                                    <div class="response">
                                                                                        <h5><b>"{{ $single_response->response }}"</b>
                                                                                        </h5>
                                                                                        <p><strong>Data de Envio:</strong>
                                                                                            {{ $single_response->created_at->format('d M Y H:i') }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                        <div class="media-body">
                                                            <form method="POST" action="{{ route('user.question') }}">
                                                                <!-- Substitua 'submit.question' pela rota adequada -->
                                                                @csrf
                                                                <input type="hidden" name="lending_id"
                                                                    value="{{ $lendingId }}">
                                                                <!-- Seu campo ID de empréstimo -->
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input type="text" class="form-control input-sm"
                                                                            name="response" id="response"
                                                                            placeholder="Digite sua pergunta">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button type="submit" class="btn btn-custom"
                                                                            style="background-color: #00b81f; color: #fff;">Enviar</button>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @else
                @endif
            </div>

        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                if (document.getElementById('notification-badge')) {
                    setTimeout(() => {
                        document.getElementById('notification-badge').style.display = 'none';
                    }, 5000);
                }
            });
        </script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const form = document.querySelector('form'); // Seleciona o formulário
        if (form) {
            const enviarBtn = form.querySelector('button[type="submit"]'); // Seleciona o botão de envio dentro do formulário
            form.addEventListener('submit', (e) => {
                e.preventDefault(); // Impede o envio padrão do formulário

                if (enviarBtn) {
                    enviarBtn.disabled = true; // Desabilita o botão após o clique
                }

                // Simula o envio do formulário (você precisará substituir isso pelo seu método real de envio, por exemplo, usando AJAX)
                setTimeout(() => {
                    // Aqui você pode enviar o formulário usando AJAX ou qualquer método necessário
                    // Simulação de envio bem-sucedido
                    alert('Mensagem enviada com sucesso!');
                }, 1000); // Simulando um envio após 1 segundo (você pode ajustar esse tempo conforme necessário)

                // Este é apenas um exemplo de simulação de envio bem-sucedido, substitua pelo seu código real de envio
            });
        }
    });
</script>

    @endsection
