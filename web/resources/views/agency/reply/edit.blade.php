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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.tiny.cloud/1/800zai2qbtsvy9kjn4fff6h4ihrv5i0qgpsn461vdazmbxtn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<div class="content-page">
  <div class="content">
      <!-- Start Content-->
      <div class="container-fluid">
          <div class="row page-title">
              <div class="col-md-12">
                  <nav aria-label="breadcrumb" class="float-right mt-1">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('admin.admin.dashboard')}}">Polocal Bank</a></li>
                          <li class="breadcrumb-item"><a href="{{route('loan.index')}}">Voltar</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Empréstimos</li>
                      </ol>
                  </nav>
                  <h4 class="mb-1 mt-0">Edição de Empréstimo</h4>
              </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Preencha Corretamente</h4>

                        <form method="POST" action="{{ route('ticketsadmin.update', $ticket->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="exampleInputEmail1">Resposta</label>
                                <textarea rows="5" class="form-control" id="response" name="response" aria-describedby="emailHelp" placeholder="Digite Corretamente"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select class="form-select" aria-label="Default select example" name="status">
                                    <option selected>Selecione Um Status</option>
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Aberto</option>
                                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Analise</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Fechado</option>
                                </select>
                            </div>



                            <div class="text-right">
                                <button type="submit" class="btn btn-primary" style="background-color: #00b81f; color: white;">Responder Ticket</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>

@endsection
