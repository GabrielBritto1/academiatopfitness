@extends('adminlte::page')

@section('title', 'Mudar Senha')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
   <h1 class="text-bold"><i class="fas fa-key"></i> Mudar Senha</h1>
   <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
      <i class="fas fa-user"></i> Voltar ao perfil
   </a>
</div>
@stop

@section('content')
@if(session('success'))
<div class="alert alert-success">
   {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
   <strong>Não foi possível atualizar a senha.</strong>
   <ul class="mb-0 mt-2 pl-3">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif

<div class="row justify-content-center">
   <div class="col-lg-8">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Atualizar senha de acesso</h3>
         </div>

         <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            @method('PUT')

            <div class="card-body">
               <div class="form-group">
                  <label for="current_password">Senha atual</label>
                  <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                  @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="form-group">
                  <label for="password">Nova senha</label>
                  <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                  @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="form-group">
                  <label for="password_confirmation">Confirmar nova senha</label>
                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
               </div>
            </div>

            <div class="card-footer">
               <button type="submit" class="btn btn-warning text-bold">Salvar nova senha</button>
            </div>
         </form>
      </div>
   </div>
</div>
@stop
