@extends('admin.includes.master')
@section('content')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Importe a biblioteca de máscara -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.admin.dashboard') }}">Polocal Bank</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('all.admin') }}">Voltar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Administradores</li>
                        </ol>
                    </nav>
					<div class="row">
						<div class="col-md-3 col-xl-2">
                            <img src="{{ asset('assets/backend/icon.png') }}" class="img-fluid" alt="Logo" width="200px">
						</div>

					</div>
					<br>
					<br>
                    <h4 class="mb-1 mt-0">Atualização de Administradores</h4>
                </div>
            </div>


				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">

<div class="col-lg-8">
	<div class="card">
		<div class="card-body">

		<form method="post" action="{{ route('admin.user.update',$user->id) }}" >
			@csrf

			<div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">Nome do Administrador</h6>
				</div>
				<div class="col-sm-9 text-secondary">
					<input type="text" name="name" class="form-control" placeholder="Digite Corretamente" value="{{$user->name}}"/>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">CPF</h6>
				</div>
				<div class="col-sm-9 text-secondary">
					<input type="text"  id="cpfCnpj" name="document" class="form-control" value="{{$user->document}}" />
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">Email</h6>
				</div>
				<div class="col-sm-9 text-secondary">
					<input type="email" name="email" class="form-control" value="{{$user->email}}" />
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">Telefone </h6>
				</div>
				<div class="col-sm-9 text-secondary">
					<input type="text" id="phone" name="phone" class="form-control" value="{{$user->phone}}" />
				</div>
			</div>










			<div class="row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 text-secondary">
					<input type="submit" class="btn btn-primary px-4" value="Atualizar Administrador" />
				</div>
			</div>
		</div>

		</form>



	</div>




							</div>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				//Mascaras
				$(document).ready(function() {
					$('#phone').mask('(00) 00000-0000');
					$('#cpfCnpj').mask('000.000.000-00');
					$('#zipCode').mask('00.000-000')
				});
			</script>


@endsection
