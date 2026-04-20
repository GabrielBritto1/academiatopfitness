@extends('adminlte::page')

@section('title', 'Editar Plano do Aluno')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-edit"></i> Editar Plano do Aluno</h1>
@stop

@section('content')
@if($errors->any())
<div class="alert alert-danger">
   <strong>Não foi possível salvar o plano.</strong>
   <ul class="mb-0 mt-2 pl-3">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif

<div class="card shadow-sm">
   <div class="card-body">
      <div class="mb-4">
         <h5 class="mb-1">{{ $user->name }}</h5>
         <small class="text-muted">
            Plano atual: {{ $contract->plano?->name ?? 'Plano removido' }}
            |
            Vencimento atual: {{ $contract->data_vencimento?->format('d/m/Y') ?? '—' }}
         </small>
      </div>

      <form action="{{ route('aluno.planos.update', [$user->id, $contract->id]) }}" method="POST">
         @csrf
         @method('PUT')

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="academia_unidade_id">Unidade</label>
                  <select class="form-control @error('academia_unidade_id') is-invalid @enderror" id="academia_unidade_id" name="academia_unidade_id" required>
                     <option value="" disabled>Selecione uma unidade</option>
                     @foreach($unidades as $unidade)
                     <option value="{{ $unidade->id }}" {{ (string) old('academia_unidade_id', $contract->academia_unidade_id) === (string) $unidade->id ? 'selected' : '' }}>
                        {{ $unidade->nome }}
                     </option>
                     @endforeach
                  </select>
                  @error('academia_unidade_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <div class="col-md-6">
               <div class="form-group">
                  <label for="plano_id">Plano</label>
                  <select class="form-control @error('plano_id') is-invalid @enderror" id="plano_id" name="plano_id" required>
                     <option value="{{ $contract->plano_id }}" selected>
                        {{ $contract->plano?->name ?? 'Plano atual' }}
                     </option>
                  </select>
                  @error('plano_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="valor_inicial">Valor</label>
                  <input type="number" step="0.01" min="0" class="form-control @error('valor_inicial') is-invalid @enderror" id="valor_inicial" name="valor_inicial" value="{{ old('valor_inicial', $contract->valor_inicial) }}" required>
                  @error('valor_inicial')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="valor_desconto">Desconto (%)</label>
                  <input type="number" step="0.01" min="0" max="100" class="form-control @error('valor_desconto') is-invalid @enderror" id="valor_desconto" name="valor_desconto" value="{{ old('valor_desconto', $contract->valor_desconto) }}">
                  @error('valor_desconto')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label>Valor final</label>
                  <input type="text" class="form-control" id="valor_total_preview" value="R$ 0,00" readonly>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="periodicidade">Periodicidade</label>
                  <select class="form-control @error('periodicidade') is-invalid @enderror" id="periodicidade" name="periodicidade" required>
                     <option value="mensal" {{ old('periodicidade', $contract->periodicidade) === 'mensal' ? 'selected' : '' }}>Mensal</option>
                     <option value="semestral" {{ old('periodicidade', $contract->periodicidade) === 'semestral' ? 'selected' : '' }}>Semestral</option>
                     <option value="anual" {{ old('periodicidade', $contract->periodicidade) === 'anual' ? 'selected' : '' }}>Anual</option>
                     <option value="diario" {{ old('periodicidade', $contract->periodicidade) === 'diario' ? 'selected' : '' }}>Diario</option>
                  </select>
                  @error('periodicidade')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="data_vencimento">Vencimento</label>
                  <input type="date" class="form-control @error('data_vencimento') is-invalid @enderror" id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento', $contract->data_vencimento?->format('Y-m-d')) }}" required>
                  @error('data_vencimento')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="forma_pagamento">Forma de pagamento</label>
                  <select class="form-control @error('forma_pagamento') is-invalid @enderror" id="forma_pagamento" name="forma_pagamento" required>
                     <option value="dinheiro" {{ old('forma_pagamento', $contract->forma_pagamento) === 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                     <option value="cartao" {{ old('forma_pagamento', $contract->forma_pagamento) === 'cartao' ? 'selected' : '' }}>Cartão</option>
                     <option value="pix" {{ old('forma_pagamento', $contract->forma_pagamento) === 'pix' ? 'selected' : '' }}>Pix</option>
                     <option value="boleto" {{ old('forma_pagamento', $contract->forma_pagamento) === 'boleto' ? 'selected' : '' }}>Boleto</option>
                  </select>
                  @error('forma_pagamento')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
         </div>

         <div class="d-flex flex-wrap">
            <button type="submit" class="btn btn-primary mr-2 mb-2">Salvar Alterações</button>
            <a href="{{ route('aluno.show', $user->id) }}#planos" class="btn btn-secondary mb-2">Voltar</a>
         </div>
      </form>
   </div>
</div>
@stop

@section('js')
<script>
   document.addEventListener('DOMContentLoaded', function() {
      const unidades = @json($unidades);
      const unidadeSelect = document.getElementById('academia_unidade_id');
      const planoSelect = document.getElementById('plano_id');
      const valorInput = document.getElementById('valor_inicial');
      const descontoInput = document.getElementById('valor_desconto');
      const valorTotalPreview = document.getElementById('valor_total_preview');
      const selectedPlanId = @json((string) old('plano_id', $contract->plano_id));

      function formatCurrency(value) {
         return 'R$ ' + value.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
         });
      }

      function updateTotalPreview() {
         const valor = parseFloat(valorInput.value) || 0;
         const desconto = parseFloat(descontoInput.value) || 0;
         const total = valor - (valor * (desconto / 100));

         valorTotalPreview.value = formatCurrency(Math.max(total, 0));
      }

      function populatePlans(selectedUnitId, preferredPlanId) {
         const unidade = unidades.find(item => String(item.id) === String(selectedUnitId));
         const plans = unidade && Array.isArray(unidade.planos) ? unidade.planos : [];

         planoSelect.innerHTML = '<option value="" disabled>Selecione um plano</option>';

         if (!plans.length) {
            planoSelect.innerHTML = '<option value="" disabled selected>Nenhum plano disponível para a unidade</option>';
            planoSelect.disabled = true;
            return;
         }

         plans.forEach(plan => {
            const option = document.createElement('option');
            option.value = plan.id;
            option.textContent = plan.name;
            option.dataset.preco = plan.preco;

            if (String(preferredPlanId) === String(plan.id)) {
               option.selected = true;
            }

            planoSelect.appendChild(option);
         });

         planoSelect.disabled = false;

         if (!planoSelect.value) {
            planoSelect.selectedIndex = 1;
         }
      }

      unidadeSelect.addEventListener('change', function() {
         populatePlans(this.value, null);
      });

      planoSelect.addEventListener('change', function() {
         const selectedOption = this.options[this.selectedIndex];
         const preco = parseFloat(selectedOption?.dataset?.preco || 0);

         if (preco > 0) {
            valorInput.value = preco.toFixed(2);
         }

         updateTotalPreview();
      });

      valorInput.addEventListener('input', updateTotalPreview);
      descontoInput.addEventListener('input', updateTotalPreview);

      populatePlans(unidadeSelect.value, selectedPlanId);
      updateTotalPreview();
   });
</script>
@stop
