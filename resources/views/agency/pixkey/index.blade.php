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
            <div class="col text-end">
                <div class="mt-4 mt-md-0">
                    <a href="{{ route('mkey.create') }}" class="btn btn-success">
                        <i class="uil-plus mr-1"></i>Criar Chave Pix
                    </a>
                </div>
            </div>
        </div>
        <hr>
        @if ($status === 'active')
            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Chave</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($keys as $item)
                        <tr>
                            <td>{{ $item->type }}</td>
                            <td>
                                <span id="key-{{ $item->id }}">{{ $item->key }}</span>
                                <button onclick="copyToClipboard('key-{{ $item->id }}')"
                                    class="btn btn-sm btn-outline-success">
                                    Copiar Chave
                                </button>
                            </td>
                            <td>


                                <form action="{{ route('mkey.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mr-1"><i
                                            data-feather="trash"></i></button>
                                </form>

    </div>
    </td>
    </tr>
    </tr>
    @endforeach
    </tbody>
    </table>

    </div>
    </div>
    </div>
    </div>
    </div>
@else
    @endif
    <!-- end container-fluid -->
    </div>
    <!-- end content -->
    </div>
    </div>
    </div>

<script>
    function copyToClipboard(elementId) {
        var text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(function() {
            alert('Copiado com sucesso!');
        }, function(err) {
            alert('Erro ao copiar');
        });
    }
</script>

@endsection
