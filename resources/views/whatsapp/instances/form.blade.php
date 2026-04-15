@csrf

<div class="card-body">
   <div class="form-group">
      <label for="name">Nome interno</label>
      <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $instance->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
   </div>

   <div class="form-group">
      <label for="base_url">URL base da Evolution API</label>
      <input type="url" id="base_url" name="base_url" class="form-control @error('base_url') is-invalid @enderror" value="{{ old('base_url', $instance->base_url ?? '') }}" placeholder="https://seu-servidor-evolution.com" required>
      @error('base_url')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
   </div>

   <div class="form-group">
      <label for="instance_name">Nome da instância</label>
      <input type="text" id="instance_name" name="instance_name" class="form-control @error('instance_name') is-invalid @enderror" value="{{ old('instance_name', $instance->instance_name ?? '') }}" required>
      @error('instance_name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
   </div>

   <div class="form-group">
      <label for="api_key">API Key</label>
      <textarea id="api_key" name="api_key" class="form-control @error('api_key') is-invalid @enderror" rows="3" required>{{ old('api_key', $instance->api_key ?? '') }}</textarea>
      @error('api_key')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      @isset($instance)
      <small class="text-muted">Atual: {{ $instance->masked_api_key }}</small>
      @endisset
   </div>

   <div class="form-group">
      <label for="description">Descrição</label>
      <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $instance->description ?? '') }}</textarea>
      @error('description')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
   </div>

   <div class="form-check mb-2">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" {{ old('is_active', isset($instance) ? $instance->is_active : true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Ativa</label>
   </div>

   <div class="form-check">
      <input type="hidden" name="is_default" value="0">
      <input type="checkbox" id="is_default" name="is_default" value="1" class="form-check-input" {{ old('is_default', isset($instance) ? $instance->is_default : false) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_default">Definir como padrão</label>
   </div>
</div>
