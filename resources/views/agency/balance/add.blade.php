@extends('agency.layouts.master')
@section('content')

<section class="py-8">
    <div class="container px-4 mx-auto">
      <div class="max-w-3xl mx-auto">
        <div class="pb-8 mb-8 border-b border-gray-400">

        </div>
        <div class="flex flex-wrap justify-between -mx-4 mb-10">
          <div class="w-full md:w-1/2 px-4 mb-10 md:mb-0">
            <div class="max-w-xs">
              <h4 class="text-gray-50 leading-6 font-bold">Adicionar Saldo para Usuários</h4>
              <p class="text-xs text-gray-300 leading-normal font-medium mb-4">Selecione um usuário e insira os valores sem pontuação e clique em enviar saldo.</p>

            </div>
          </div>
          <div class="w-full md:w-1/2 px-4">
            <form method="POST" action="/agency/add-balance">
                @csrf
              <div class="relative w-full h-14 py-4 px-3 mb-8 border border-gray-400 hover:border-white focus-within:border-green-500 rounded-lg">
                <span class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Selecione um Usuário</span>
                <select name="user_id" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
              </div>
              <div class="relative w-full h-14 py-4 px-3 mb-8 border border-gray-400 hover:border-white focus-within:border-green-500 rounded-lg">
                <span class="absolute bottom-full left-0 ml-3 -mb-1 transform translate-y-0.5 text-xs font-semibold text-gray-300 px-1 bg-gray-600">Valor</span>
                <input class="block w-full outline-none bg-transparent text-sm text-gray-100 font-medium money" id="money" type="text" placeholder="" name="amount">
              </div>

              <button class="block w-full py-3 px-6 text-center text-blue-50 leading-6 font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg transition duration-200">Inserir Saldo</button>
            </form>
          </div>
        </div>


      </div>
    </div>
  </section>
</div>

@endsection