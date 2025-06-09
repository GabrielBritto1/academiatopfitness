@extends('adminlte::page')
@section('title', 'Carrinho de Planos')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-file-invoice-dollar"></i> Carrinho de Planos</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <a href="{{ route('planos.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-fw fa-arrow-left"></i> Voltar aos Planos
         </a>
      </div>
   </div>
   <div class="card-body">
      <form action="#">
         <div class="row">
            <div class="col">
               <label for="aluno">Aluno</label>
               <select class="form-control" name="aluno" id="aluno">
                  <option value="" selected disabled>Selecione um Aluno</option>
                  @foreach ($alunos as $aluno)
                  <option value="{{ $aluno->id }}">{{ $aluno->name }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div id="unidades-planos-container">
            <div class="row mt-3 unidade-plano-group">
               <div class="col-12 col-sm-6 col-md-3">
                  <label for="unidade">Unidade</label>
                  <select class="form-control unidade-select" name="unidades[]" required>
                     <option value="" selected disabled>Selecione uma Unidade</option>
                     @foreach ($unidades as $unidade)
                     <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="col-12 col-sm-6 col-md-3">
                  <label for="plano">Plano</label>
                  <select class="form-control plano-select" name="planos[]" disabled required>
                     <option value="" selected disabled>Selecione um Plano</option>
                  </select>
               </div>
               <div class="col-12 col-sm-6 col-md-3">
                  <label for="valor">Valor</label>
                  <input type="text" class="form-control valor-input" name="valores[]" placeholder="Valor do Plano" required>
               </div>
               <div class="col-8 col-sm-3 col-md-2">
                  <label for="plano">Desconto</label>
                  <input type="text" class="form-control desconto-input" name="descontos[]" placeholder="(%)">
               </div>
               <div class="col-auto d-flex align-items-end mb-1">
                  <button type="button" class="btn btn-danger btn-sm text-center remove-unidade-plano"><i class="fas fa-trash"></i></button>
               </div>
            </div>
         </div>
         <button type="button" class="btn btn-success btn-sm mt-3" id="add-unidade-plano"><i class="fas fa-plus"></i></button>
         <div class="mt-3 float-right">
            <strong>Valor total:</strong> <span class="valor-com-desconto">R$ 0.00</span>
         </div>
         <div class="mt-3">
            <button type="submit" class="btn btn-warning text-bold">Finalizar Cadastro dos Planos</button>
         </div>
      </form>
   </div>
</div>
@stop

@section('js')
<script>
   const unidades = @json($unidades);
   const dataHoje = new Date();
   const diaHoje = dataHoje.getDate();
   console.log(diaHoje);

   function getPlanosOptions(unidadeId) {
      const unidade = unidades.find(u => u.id == unidadeId);
      if (!unidade || !unidade.planos) return '<option value="" selected disabled>Selecione um Plano</option>';
      let options = '<option value="" selected disabled>Selecione um Plano</option>';
      unidade.planos.forEach(plano => {
         if (plano.dia_vencimento >= diaHoje) {
            options += `<option value="${plano.id}" data-preco="${plano.preco_pre_vencimento}">${plano.name}</option>`;
         } else {
            options += `<option value="${plano.id}" data-preco="${plano.preco_pos_vencimento}">${plano.name}</option>`;
         }
      });
      return options;
   }

   $(function() {
      // Adicionar novo grupo unidade/plano
      $('#add-unidade-plano').click(function() {
         const group = $('.unidade-plano-group').first().clone();
         group.find('select').val('');
         group.find('input').val('');
         group.removeData('valor-com-desconto'); // Limpa dados antigos
         group.find('.plano-select').html('<option value="" selected disabled>Selecione um Plano</option>').prop('disabled', true);
         $('#unidades-planos-container').append(group);
      });

      // Remover grupo
      $(document).on('click', '.remove-unidade-plano', function() {
         if ($('.unidade-plano-group').length > 1) {
            $(this).closest('.unidade-plano-group').remove();
         }
      });

      $(document).on('change', '.plano-select', function() {
         const preco = $(this).find(':selected').data('preco') || 0;
         const group = $(this).closest('.unidade-plano-group');
         group.find('.valor-input').val(preco.toFixed(2));
         calcularDesconto(group);
      });

      // Popular planos ao selecionar unidade
      $(document).on('change', '.unidade-select', function() {
         const unidadeId = $(this).val();
         const planoSelect = $(this).closest('.unidade-plano-group').find('.plano-select');
         planoSelect.html(getPlanosOptions(unidadeId));
         planoSelect.prop('disabled', false);
      });
   });

   function calcularDesconto(group) {
      const valor = parseFloat(group.find('.valor-input').val()) || 0;
      const desconto = parseFloat(group.find('.desconto-input').val()) || 0;
      const valorComDesconto = valor - (valor * (desconto / 100));

      // Armazena o valor com desconto no grupo (usando jQuery .data)
      group.data('valor-com-desconto', valorComDesconto);

      // Atualiza o valor total final
      atualizarTotalComDesconto();
   }

   function atualizarTotalComDesconto() {
      let total = 0;
      $('.unidade-plano-group').each(function() {
         const valor = $(this).data('valor-com-desconto') || 0;
         total += valor;
      });
      $('.valor-com-desconto').text('R$ ' + total.toFixed(2));
   }

   // Quando usuário digitar valores ou descontos
   $(document).on('input', '.valor-input, .desconto-input', function() {
      const group = $(this).closest('.unidade-plano-group');
      calcularDesconto(group);
   });


   $(document).on('input', '.valor-input, .desconto-input', function() {
      const group = $(this).closest('.unidade-plano-group');
      calcularDesconto(group);
   });
</script>
@endsection