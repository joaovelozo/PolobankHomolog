@extends('agency.includes.master')
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
    </style>
    
    <div class="content-page">
        <div class="content">
            <!-- Start Content -->
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="col-md-3 col-xl-6">
                        <h4 class="mb-1 mt-0">Edição de Perfil</h4>
                    </div>
                </div>
                <section class="py-8">
                    <div class="container px-4 mx-auto">
                        <div class="flex flex-wrap -mx-4 mb-8">
                            <div class="w-full lg:w-1/3 px-4 mb-8 lg:mb-0">
                                <div class="mt-8">
                                    <h3 class="text-2xl font-bold tracking-wide text-white mb-1">Informações Pessoais</h3>
                                    <p class="text-xs text-gray-300">Edição de Senha</p>
                                </div>
                            </div>
    
                            <div class="w-full lg:w-2/3 px-4">
                                @if(session()->has('message'))
                                <div class="alert-badge" id="notification-badge">
                                    {{ session('message') }}
                                </div>
                                @endif
                                <div class="px-8 md:px-16 pt-16 pb-8 bg-gray-500 rounded-xl">
                                    <div class="max-w-xl mx-auto">
                                        <form method="POST" action="{{route('manager.update.password')}}" >
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="current_password">Digite Sua Senha Antiga</label>
                                                    <input id="current_password" name="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Digite Corretamente">
                                                    @error('old_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="new_password">Nova Senha</label>
                                                    <input id="new_password" type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror"  placeholder="Digite Corretamente">
                                                    @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                  <label for="new_password">Confirme A Nova Senha</label>
                                                  <input id="new_password" type="password" name="new_password_confirmation" class="form-control @error('new_password') is-invalid @enderror"  placeholder="Digite Corretamente">
                                                  @error('new_password')
                                                  <span class="text-danger">{{ $message }}</span>
                                                  @enderror
                                              </div>
                                            </div>
                                            <div class="mt-8 text-right">
                                                <button class="btn btn-primary" type="submit">Atualizar Senha</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            if(document.getElementById('notification-badge')) {
                setTimeout(() => {
                    document.getElementById('notification-badge').style.display = 'none';
                }, 5000); // O badge desaparecerá após 5000 milissegundos, ou seja, 5 segundos
            }
        });
    </script>
    


@endsection