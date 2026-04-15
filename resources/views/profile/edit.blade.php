@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
   <h1 class="text-bold"><i class="fas fa-user"></i> Perfil</h1>
   <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-warning">
      <i class="fas fa-key"></i> Mudar senha
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
   <strong>Não foi possível atualizar o perfil.</strong>
   <ul class="mb-0 mt-2 pl-3">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif

<div class="row">
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body box-profile">
            <div class="text-center">
               <div class="img-circle elevation-2 bg-warning d-inline-flex align-items-center justify-content-center text-bold text-white" style="width: 90px; height: 90px; font-size: 2rem;">
                  {{ strtoupper(mb_substr($user->name, 0, 1)) }}
               </div>
            </div>

            <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
            <p class="text-muted text-center mb-3">{{ $user->email }}</p>

            <ul class="list-group list-group-unbordered mb-3">
               <li class="list-group-item">
                  <b>Status</b>
                  <span class="float-right">{{ $user->status ? 'Ativo' : 'Inativo' }}</span>
               </li>
               <li class="list-group-item">
                  <b>Perfis</b>
                  <span class="float-right text-right">{{ $user->roles->pluck('formatted_name')->implode(', ') ?: 'Sem perfil' }}</span>
               </li>
            </ul>
         </div>
      </div>
   </div>

   <div class="col-lg-8">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Dados da conta</h3>
         </div>
         <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="card-body">
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                  @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="form-group">
                  <label for="email">E-mail</label>
                  <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                  @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <div class="card-footer">
               <button type="submit" class="btn btn-warning text-bold">Salvar perfil</button>
            </div>
         </form>
      </div>
   </div>
</div>
@stop
