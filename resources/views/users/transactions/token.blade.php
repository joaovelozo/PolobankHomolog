<!--Pagina de validação de Token !-->
@extends('users.includes.master')
@section('content')
    @php
        $id = Auth::user()->id;
        $clientId = App\Models\User::find($id);
        $status = $clientId->status;
    @endphp
    <div class="content-page">
        <div class="content">
            @if ($status === 'active')
                <!-- Start Content-->
                <div class="container-fluid">
                    <div class="row page-title">
                        <div class="col-md-12">
                            <nav aria-label="breadcrumb" class="float-right mt-1">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Polocal Bank</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Token</li>
                                </ol>
                            </nav>
                            <div class="row">
                                <div class="col-md-12 col-xl-12 align-self-center">
                                    <h4 class="mb-1 mt-0">Validação de Token</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h6><strong>Valide Seu Token de Transação</strong></h6>
                                    <h5><b>Seu Token</b></h1>
                                        <p>
                                            <span class="badge bg-danger rounded-pill text-white"
                                                style="font-size: 1.5rem;">
                                                {{ $token }}
                                            </span>
                                        </p>
                                    </h5>
                                        <form action="{{ route('token.validate') }}" method="POST">
                                            @csrf
                                            <label for="user_token">Digite o Token:</label>
                                            <input type="text" id="user_token" name="user_token" required>
                                            <button type="submit">Validar</button>
                                        </form>

                                        @if ($errors->any())
                                            <div>
                                                <strong>Erro:</strong>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <p>Seu status não está ativo.</p>
                    </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.querySelector('button[type="button"]').addEventListener('click', function() {
            const tokenInput = document.getElementById('user_token').value;

            if (!tokenInput) {
                alert('Por favor, insira o token.');
                return;
            }

            fetch('{{ route('token.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_token: tokenInput
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = "{{ route('dashboard') }}";
                    } else {
                        alert('Token inválido. Por favor, tente novamente.');
                    }
                })
                .catch(error => console.error('Erro:', error));
        });
    </script>
@endsection
