@extends('adminlte::page')

@section('title', 'Editar Unidade')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-building"></i> Editar Unidade</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="POST" action="{{ route('unidade.update', $unidade->id) }}" enctype="multipart/form-data">
         @csrf
         @method('PUT')

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="nome">Nome da Unidade *</label>
                  <input type="text" 
                         class="form-control @error('nome') is-invalid @enderror" 
                         id="nome" 
                         name="nome" 
                         value="{{ old('nome', $unidade->nome) }}" 
                         required>
                  @error('nome')
                  <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-6">
               <div class="form-group">
                  <label for="endereco">Endereço *</label>
                  <input type="text" 
                         class="form-control @error('endereco') is-invalid @enderror" 
                         name="endereco" 
                         id="endereco" 
                         value="{{ old('endereco', $unidade->endereco) }}" 
                         required>
                  @error('endereco')
                  <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
               </div>
            </div>
         </div>

         <div class="form-group">
            <label for="logo">Logo da Unidade</label>
            
            @if($unidade->logo)
            <div class="mb-3">
               <p class="text-muted">Logo atual:</p>
               <img src="{{ asset('storage/' . $unidade->logo) }}" 
                    alt="{{ $unidade->nome }}" 
                    class="img-thumbnail" 
                    style="max-height: 150px; max-width: 200px;">
            </div>
            @endif

            <div class="custom-file">
               <input type="file" 
                      class="custom-file-input @error('logo') is-invalid @enderror" 
                      id="logo" 
                      name="logo" 
                      accept="image/*">
               <label class="custom-file-label" for="logo">
                  {{ $unidade->logo ? 'Alterar logo...' : 'Escolher arquivo...' }}
               </label>
            </div>
            <small class="form-text text-muted">
               Formatos aceitos: JPG, PNG, GIF, SVG (máx. 2MB)
               @if($unidade->logo)
               <br>Deixe em branco para manter o logo atual.
               @endif
            </small>
            @error('logo')
            <span class="text-danger">{{ $message }}</span>
            @enderror

            <div id="logoPreview" class="mt-3" style="display: none;">
               <p class="text-muted">Preview do novo logo:</p>
               <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px; max-width: 200px;">
            </div>
         </div>

         <div class="form-group">
            <button type="submit" class="btn btn-warning text-bold">
               <i class="fas fa-save"></i> Salvar Alterações
            </button>
            <a href="{{ route('unidade.index') }}" class="btn btn-secondary">
               <i class="fas fa-arrow-left"></i> Voltar
            </a>
         </div>
      </form>
   </div>
</div>
@stop

@section('js')
<script>
   // Preview do logo ao selecionar arquivo
   $('#logo').on('change', function() {
      const file = this.files[0];
      if (file) {
         const reader = new FileReader();
         reader.onload = function(e) {
            $('#previewImg').attr('src', e.target.result);
            $('#logoPreview').show();
         };
         reader.readAsDataURL(file);
         $(this).next('.custom-file-label').html(file.name);
      } else {
         $('#logoPreview').hide();
         $(this).next('.custom-file-label').html('{{ $unidade->logo ? "Alterar logo..." : "Escolher arquivo..." }}');
      }
   });
</script>
@stop
