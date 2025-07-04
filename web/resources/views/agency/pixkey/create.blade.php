@extends('agency.includes.master')
@section('content')
    @php
        $id = Auth::user()->id;
        $clientId = App\Models\User::find($id);
        $status = $clientId->status;
    @endphp
    <div class="content-page">
        <div class="content">
            <!-- Start Content -->
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="row">

                        <div class="col-md-9 col-xl-4 align-self-center">
                            <h4 class="mb-1 mt-0">Chaves</h4>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-2/3 px-4">
                    @if (session()->has('message'))
                        <div class="alert-badge" id="notification-badge">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </div>

            <hr>
            @if ($status === 'active')
                <form action="{{ route('mkey.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="type">Tipo da Chave</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Selecione o Tipo</option>
                            <option value="EVP">EVP – Chave aleatória</option>
                            <option value="DOCUMENT">DOCUMENT – CPF ou CNPJ</option>
                            <option value="PHONE">PHONE – Número de telefone</option>
                            <option value="EMAIL">EMAIL – Endereço de e-mail</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="input_key" class="mb-3">
                        <label for="key">Valor da Chave</label>
                        <input id="key" name="key" type="text" class="form-control">
                        @error('key')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Criar Chave</button>
                    <a href="{{ route('managerkey.index') }}">Voltar</a>
                </form>

                <script>
                    const typeSelect = document.getElementById('type');
                    const keyInput = document.getElementById('key');
                    const inputKeyDiv = document.getElementById('input_key');

                    typeSelect.addEventListener('change', (e) => {
                        const selectedType = e.target.value;

                        // Esconde o campo se for EVP (chave aleatória)
                        if (selectedType === 'EVP') {
                            inputKeyDiv.style.display = 'none';
                            keyInput.value = ''; // limpa o valor
                        } else {
                            inputKeyDiv.style.display = '';
                            keyInput.value = ''; // limpa o valor ao trocar o tipo
                        }

                        // Se for PHONE, pré-preenche com +55
                        if (selectedType === 'PHONE') {
                            keyInput.value = '+55';
                        }
                    });

                    // Opcional: força o +55 no início mesmo se o usuário tentar apagar
                    keyInput.addEventListener('input', () => {
                        if (typeSelect.value === 'PHONE') {
                            if (!keyInput.value.startsWith('+55')) {
                                keyInput.value = '+55' + keyInput.value.replace(/^\+*/, '').replace(/^55*/, '');
                            }
                        }
                    });
                </script>
            @else
            @endif
        @endsection
