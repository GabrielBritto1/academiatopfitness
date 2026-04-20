@extends('adminlte::page')

@section('title', 'Editar Aluno')

@section('content_header')
<h1 class="text-bold">Editar Aluno</h1>
@stop

@section('content')

@if($errors->any())
<div class="alert alert-danger">
   <strong>Não foi possível salvar o aluno.</strong>
   <ul class="mb-0 mt-2 pl-3">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif

<form method="POST" action="{{ route('aluno.update', $aluno->id) }}" enctype="multipart/form-data">
   @csrf
   @method('PUT')

   <div class="card shadow-sm">

      <div class="card-body">

         {{-- NOME --}}
         <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $aluno->name) }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- CPF --}}
         <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf', $aluno->aluno?->cpf) }}" placeholder="000.000.000-00">
            @error('cpf')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- EMAIL --}}
         <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $aluno->email) }}" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         <div class="row">
            {{-- TELEFONE --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="telefone">Telefone</label>
                  <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone', $aluno->aluno?->telefone) }}">
                  @error('telefone')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            {{-- DATA DE NASCIMENTO --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="data_nascimento">Data de Nascimento</label>
                  <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" value="{{ old('data_nascimento', $aluno->aluno?->data_nascimento?->format('Y-m-d')) }}">
                  @error('data_nascimento')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            {{-- SEXO --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="sexo">Sexo</label>
                  <select name="sexo" class="form-control @error('sexo') is-invalid @enderror">
                     <option value="">Selecione</option>
                     <option value="Masculino" {{ old('sexo', $aluno->aluno?->sexo) === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                     <option value="Feminino" {{ old('sexo', $aluno->aluno?->sexo) === 'Feminino' ? 'selected' : '' }}>Feminino</option>
                  </select>
                  @error('sexo')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
         </div>

         {{-- OBSERVAÇÕES --}}
         <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea name="observacoes" class="form-control @error('observacoes') is-invalid @enderror" rows="3">{{ old('observacoes', $aluno->aluno?->observacoes) }}</textarea>
            @error('observacoes')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- FOTO DO ALUNO --}}
         <div class="form-group">
            <label for="edit-foto">Foto do Aluno</label>
            <div class="d-flex flex-wrap mb-2">
               <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2" id="open-edit-student-camera">
                  <i class="fas fa-camera mr-1"></i> Tirar foto agora
               </button>
               <button type="button" class="btn btn-outline-success btn-sm mr-2 mb-2 d-none" id="capture-edit-student-camera">
                  <i class="fas fa-camera-retro mr-1"></i> Capturar
               </button>
               <button type="button" class="btn btn-outline-secondary btn-sm mb-2 d-none" id="close-edit-student-camera">
                  <i class="fas fa-times mr-1"></i> Fechar câmera
               </button>
            </div>

            <input
               type="file"
               id="edit-foto"
               name="foto"
               class="form-control-file @error('foto') is-invalid @enderror"
               accept="image/*"
               capture="user">

            <small class="form-text text-muted">
               Você pode enviar um arquivo ou abrir a câmera para tirar a foto na hora.
            </small>
            @error('foto')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <div class="mt-3 d-none" id="edit-student-camera-wrapper">
               <div class="border rounded p-2 bg-dark">
                  <video
                     id="edit-student-camera-video"
                     class="w-100 rounded"
                     autoplay
                     playsinline
                     muted
                     style="max-height: 320px; object-fit: cover;"></video>
               </div>
            </div>

            <canvas id="edit-student-camera-canvas" class="d-none"></canvas>

            <div class="mt-3 {{ $aluno->aluno?->foto ? '' : 'd-none' }}" id="edit-student-photo-preview-wrapper">
               <label class="d-block">Pré-visualização</label>
               <img
                  id="edit-student-photo-preview"
                  src="{{ $aluno->aluno?->foto_url ?? '' }}"
                  alt="Pré-visualização da foto do aluno"
                  class="img-thumbnail rounded"
                  style="width: 160px; height:160px; object-fit: cover;">
            </div>
         </div>

      </div>

      <div class="card-footer">
         <button type="submit" class="btn btn-primary">Salvar Alterações</button>
         <a href="{{ route('aluno.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>

   </div>

</form>

@stop

@section('js')
<script src="/js/User/index.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      if (typeof window.initStudentPhotoCapture !== 'function') {
         return;
      }

      window.initStudentPhotoCapture({
         fileInputId: 'edit-foto',
         openCameraButtonId: 'open-edit-student-camera',
         captureCameraButtonId: 'capture-edit-student-camera',
         closeCameraButtonId: 'close-edit-student-camera',
         cameraWrapperId: 'edit-student-camera-wrapper',
         videoId: 'edit-student-camera-video',
         canvasId: 'edit-student-camera-canvas',
         previewWrapperId: 'edit-student-photo-preview-wrapper',
         previewImageId: 'edit-student-photo-preview'
      });
   });
</script>
@stop
