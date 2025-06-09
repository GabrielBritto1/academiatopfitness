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
      <!-- <div class="row">
         @forelse($planos as $plano)
         <div class="col-md-4">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title text-uppercase">{{ $plano->name }}</h3>
               </div>
               <div class="card-body">
                  @if (Carbon\Carbon::today()->day > $plano->dia_vencimento)
                  <h4 class="text-danger text-bold">VENCIMENTO: dia {{ Carbon\Carbon::today()->day }} de cada mês</h4>
                  <h4 class="text-danger text-bold">VALOR: R$ {{ $plano->preco_pos_vencimento }}/mês</h4>
                  @else
                  <h4 class="text-info text-bold">VENCIMENTO: {{ Carbon\Carbon::today()->day }} de cada mês</h4>
                  <h4 class="text-info text-bold">VALOR: R$ {{ $plano->preco_pre_vencimento }}/mês</h4>
                  @endif
               </div>
            </div>
         </div>
         @empty
         <div class="col-12">
            <div class="alert alert-info">
               <i class="fas fa-info-circle"></i> Nenhum plano encontrado no carrinho.
            </div>
         </div>
         @endforelse
      </div> -->

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
         <div class="row mt-3">
            <div class="col">
               <label for="plano">Plano</label>
               <select class="form-control" name="plano" id="plano">
                  <option value="" selected disabled>Selecione um Plano</option>
                  @foreach ($planos as $plano)
                  <option value="{{ $plano->id }}">{{ $plano->name }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div class="row mt-3">
            <div class="col">
               <label for="unidade">Unidades</label>
               <select class="form-control" name="unidade" id="unidade">
                  <option value="" selected disabled>Selecione uma Unidade</option>
                  @foreach ($unidades as $unidade)
                  <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div class="row mt-3">
            <div class="col">
               <label for="modalidade_id">Modalidade</label>
               <select class="form-control" name="modalidade_id" id="modalidade_id" disabled>
                  <option value="">Selecione a Modalidade</option>
                  @foreach ($unidades as $unidade)
                  <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                  @endforeach
               </select>
            </div>
         </div>
      </form>
   </div>
</div>
</div>
@stop

@section('js')
<script>
   // const planos = @json($planos);

   // // Crie um elemento para mostrar os dados
   // const infoDiv = document.createElement('div');
   // infoDiv.id = 'infoPlano';
   // document.querySelector('.row').appendChild(infoDiv);

   // // Pegue o dia atual do mês em JS
   // const diaHoje = (new Date()).getDate();

   // document.getElementById('plano').addEventListener('change', function() {
   //    const planoId = this.value;
   //    const plano = planos.find(p => p.id == planoId);

   //    if (plano) {
   //       let precoHtml = '';
   //       if (diaHoje > plano.dia_vencimento) {
   //          precoHtml = `<strong>Preço Pós Vencimento:</strong> R$ ${plano.preco_pos_vencimento}<br>`;
   //       } else {
   //          precoHtml = `<strong>Preço Pré Vencimento:</strong> R$ ${plano.preco_pre_vencimento}<br>`;
   //       }

   //       infoDiv.innerHTML = `
   //          <div class="mt-3">
   //             <strong>Nome:</strong> ${plano.name}<br>
   //             ${precoHtml}
   //             <strong>Dia Vencimento:</strong> ${plano.dia_vencimento}
   //          </div>
   //       `;
   //    } else {
   //       infoDiv.innerHTML = '';
   //    }
   // });

   const unidades = @json($unidades);
   document.getElementById('unidade').addEventListener('change', function() {
      const unidadeId = this.value;
      const modalidadeSelect = document.getElementById('modalidade_id');
      modalidadeSelect.innerHTML = '<option value="">Selecione a Modalidade</option>';

      if (!unidadeId) {
         modalidadeSelect.disabled = true;
         return;
      }
      // Busca a unidade selecionada
      const unidade = unidades.find(u => u.id == unidadeId);
      if (unidade && unidade.modalidades.length > 0) {
         unidade.modalidades.forEach(function(modalidade) {
            modalidadeSelect.innerHTML += `<option value="${modalidade.id}">${modalidade.name}</option>`;
         });
         modalidadeSelect.disabled = false;
      } else {
         modalidadeSelect.disabled = true;
      }
   });
</script>
@endsection