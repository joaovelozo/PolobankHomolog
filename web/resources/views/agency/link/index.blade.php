@extends('agency.includes.master')
@section('content')
<div class="content-page">
    <div class="content">
        <!-- Start Content -->
        <div class="container-fluid">
            <div class="row page-title align-items-center">
                <div class="row">
                    <div class="col-md-3 col-xl-2">
                        <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid" alt="Logo" width="200px">
                    </div>
                    <div class="col-md-9 col-xl-4 align-self-center">
                        <h4 class="mb-1 mt-0">Link</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container px-4 mx-auto">
        <section>
            <!-- Conteúdo da Página -->
            <div class="container" style="padding-top: 80px;">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">

                        <button id="generateLinkButton" class="py-2 px-4 mb-3 leading-6 text-white font-semibold btn btn-warning width-lg rounded-lg transition duration-200">Clique Aqui Para Gerar o Link de Criação de Conta</button>
                        <div class="input-group mb-3">
                            <input type="text" id="generatedLink" class="form-control" readonly style="height: 50px;">
                            <br>
                            <br>
                            <div class="input-group-append">
                                <button class="py-2 px-4 leading-6 text-black font-semibold bg-green-500 hover:bg-green-600 rounded-lg transition duration-200" type="button" id="copyButton">Copiar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    document.getElementById('generateLinkButton').addEventListener('click', function() {
        var agencyId = "{{ $agencyId }}"; // Usando o agency_id passado para a view
        var link = "{{ url('/become') }}/" + agencyId;
        document.getElementById('generatedLink').value = link;

        var copyText = document.getElementById("generatedLink");
        copyText.select();
        document.execCommand("copy");
        alert("Link copiado: " + copyText.value);
    });
</script>

@endsection
