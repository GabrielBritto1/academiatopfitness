@extends('adminlte::page')
@section('title', 'Avaliações')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-clipboard-list"></i> Fazer avaliação do aluno: </h1>
@stop

@section('content')

<div class="card">
   <div class="card-body">

      <form action="{{ route('avaliacao.store') }}" method="POST">
         @csrf

         <input type="hidden" name="aluno_id" value="{{ request()->get('aluno_id') }}">
         <input type="hidden" name="professor_id" value="{{ request()->get('professor_id') }}">

         {{-- ============================ MEDIDAS BÁSICAS ============================ --}}

         <h4 class="text-md text-bold text-uppercase">Medidas Iniciais*</h4>
         <div class="row">
            <div class="col">
               <div class="form-group mt-3">
                  <label for="peso">Peso (Kg)</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="peso" id="peso">
               </div>
            </div>

            <div class="col">
               <div class="form-group mt-3">
                  <label for="altura">Altura (cm)</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="altura" id="altura">
               </div>
            </div>
         </div>

         <hr>

         <h4 class="text-md text-bold text-uppercase">Perimetrias*</h4>
         <div class="row">
            {{-- TÓRAX --}}
            <div class="col-md-3">
               <div class="form-group mt-3">
                  <label for="torax">Tórax (cm)</label>
                  <input type="text" name="torax" id="torax" class="form-control form-control-lg">
               </div>
            </div>

            {{-- CINTURA --}}
            <div class="col-md-3">
               <div class="form-group mt-3">
                  <label for="cintura">Cintura (cm)</label>
                  <input type="text" name="cintura" id="cintura" class="form-control form-control-lg">
               </div>
            </div>

            {{-- ABDÔMEN --}}
            <div class="col-md-3">
               <div class="form-group mt-3">
                  <label for="abdomen">Abdômen (cm)</label>
                  <input type="text" name="abdomen_medida" id="abdomen_medida" class="form-control form-control-lg">
               </div>
            </div>

            {{-- QUADRIL --}}
            <div class="col-md-3">
               <div class="form-group mt-3">
                  <label for="quadril">Quadril (cm)</label>
                  <input type="text" name="quadril" id="quadril" class="form-control form-control-lg">
               </div>
            </div>
         </div>
         <div class="row">
            {{-- BRAÇO RELAXADO --}}
            <div class="col-md-3">
               <label>Braço Relaxado Esquerdo</label>
               <input name="braco_relaxado_esquerdo" class="form-control form-control-lg">
            </div>

            <div class="col-md-3">
               <label>Braço Relaxado Direito</label>
               <input name="braco_relaxado_direito" class="form-control form-control-lg">
            </div>

            {{-- BRAÇO CONTRAÍDO --}}
            <div class="col-md-3">
               <label>Braço Contraído Esquerdo</label>
               <input name="braco_contraido_esquerdo" class="form-control form-control-lg">
            </div>

            <div class="col-md-3">
               <label>Braço Contraído Direito</label>
               <input name="braco_contraido_direito" class="form-control form-control-lg">
            </div>
         </div>
         <div class="row mt-3">
            {{-- COXA MEDIAL --}}
            <div class="col-md-3">
               <label>Coxa Medial (cm)</label>
               <input name="coxa_medial" class="form-control form-control-lg">
            </div>

            {{-- PANTURRILHA --}}
            <div class="col-md-3">
               <label>Panturrilha (cm)</label>
               <input name="panturrilha" class="form-control form-control-lg">
            </div>

            {{-- ABDÔMEN (dobra já existente) --}}
            <div class="col-md-3">
               <label>Dobra Abdominal (mm)</label>
               <input name="abdomen_dobra" class="form-control form-control-lg">
            </div>
         </div>


         {{-- ============================ DOBRAS CUTÂNEAS ============================ --}}

         <hr>
         <h4 class="mt-4">Dobras Cutâneas (Pollock)</h4>

         <div class="row">

            {{-- Protocolo --}}
            <div class="col-md-4">
               <div class="form-group mt-3">
                  <label for="protocolo">Protocolo</label>
                  <select name="protocolo" id="protocolo" class="form-control form-control-lg">
                     <option value="" disabled selected>Selecione</option>
                     <option value="pollock3">Pollock – 3 Dobras</option>
                     <option value="pollock7">Pollock – 7 Dobras</option>
                  </select>
               </div>
            </div>

            {{-- Sexo --}}
            <div class="col-md-4">
               <div class="form-group mt-3">
                  <label for="sexo_avaliacao">Sexo do aluno</label>
                  <select name="sexo_avaliacao" id="sexo_avaliacao" class="form-control form-control-lg">
                     <option value="" disabled selected>Selecione</option>
                     <option value="masculino">Masculino</option>
                     <option value="feminino">Feminino</option>
                  </select>
               </div>
            </div>

         </div>

         {{-- Container onde as dobras vão aparecer --}}
         <div id="dobras-container" class="mt-3"></div>
         <div class="row mt-3">
            <div class="col">
               <div class="form-group">
                  <label for="imc">IMC</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="imc" id="imc" readonly>
               </div>
            </div>
            <div class="col">
               <div class="form-group">
                  <label for="gordura">Percentual de Gordura (%)</label>
                  <input type="text" class="form-control form-control-lg" name="gordura" id="gordura" readonly>
               </div>
            </div>
            <div class="col">
               <div class="form-group">
                  <label for="massa_muscular">Massa Muscular</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="massa_muscular" id="massa_muscular" readonly>
               </div>
            </div>
         </div>


         {{-- ============================ OBSERVAÇÕES ============================ --}}
         <div class="row">
            <div class="col">
               <div class="form-group mt-3">
                  <label for="observacoes">Observações</label>
                  <textarea class="form-control form-control-lg" name="observacoes" id="observacoes"></textarea>
               </div>
            </div>
         </div>

         <button type="submit" class="btn btn-warning mt-3 text-bold">Salvar Avaliação</button>

      </form>
   </div>
</div>
@stop



@section('js')
<script>
   $(function() {

      // ==========================================
      // IMC Automático
      // ==========================================
      function calcularIMC() {
         const peso = parseFloat($('#peso').val()) || 0;
         const altura = parseFloat($('#altura').val()) || 0;
         let imc = '';

         if (peso > 0 && altura > 0) {
            imc = (peso / (altura * altura)).toFixed(2);
         }

         $('#imc').val(imc);
      }
      $('#peso, #altura').on('input', calcularIMC);

      // ==========================================
      // MASSA MUSCULAR AUTOMÁTICA
      // ==========================================
      function calcularMassaMuscular() {
         const peso = parseFloat($('#peso').val()) || 0;
         const gordura = parseFloat($('#gordura').val()) || 0;

         if (peso > 0 && gordura >= 0) {
            const massa = peso * (1 - (gordura / 100));
            $('#massa_muscular').val(massa.toFixed(2));
         }
      }

      $('#peso').on('input', function() {
         calcularIMC();
         calcularMassaMuscular();
      });

      $('#gordura').on('input', function() {
         calcularMassaMuscular();
      });

      // ==========================================
      // Gera HTML de inputs
      // ==========================================
      function gerarInput(nome, label) {
         return `
            <div class="col-md-3">
               <div class="form-group">
                  <label for="${nome}">${label}</label>
                  <input type="number" class="form-control form-control-lg dobra"
                         step="0.1" name="${nome}" id="${nome}">
               </div>
            </div>`;
      }



      // ==========================================
      // Renderizar as dobras automaticamente
      // ==========================================
      function renderDobras() {
         const protocolo = $('#protocolo').val();
         const sexo = $('#sexo_avaliacao').val();
         let html = '<div class="row">';

         if (!protocolo) {
            $('#dobras-container').html('');
            return;
         }

         // ============ POLLOCK 3 =============
         if (protocolo === 'pollock3') {

            if (!sexo) {
               $('#dobras-container').html('<p class="text-muted">Selecione o sexo.</p>');
               return;
            }

            if (sexo === 'masculino') {
               html += gerarInput('peito', 'Peito (mm)');
               html += gerarInput('abdomen_dobra', 'Abdômen (mm)');
               html += gerarInput('coxa_dobra', 'Coxa (mm)');
            }

            if (sexo === 'feminino') {
               html += gerarInput('triceps', 'Tríceps (mm)');
               html += gerarInput('coxa_dobra', 'Coxa (mm)');
               html += gerarInput('supra_iliaca', 'Supra-ilíaca (mm)');
            }
         }

         // ============ POLLOCK 7 =============
         if (protocolo === 'pollock7') {
            html += gerarInput('peito', 'Peito (mm)');
            html += gerarInput('triceps', 'Tríceps (mm)');
            html += gerarInput('subescapular', 'Subescapular (mm)');
            html += gerarInput('axilar_media', 'Axilar Média (mm)');
            html += gerarInput('supra_iliaca', 'Supra-ilíaca (mm)');
            html += gerarInput('abdomen_dobra', 'Abdômen (mm)');
            html += gerarInput('coxa_dobra', 'Coxa (mm)');
         }

         html += '</div>';
         $('#dobras-container').html(html);

         $('.dobra').on('input', calcularGordura);
      }

      $('#protocolo, #sexo_avaliacao').on('change', renderDobras);



      // ==========================================
      // Cálculo automático de Gordura (%)
      // ==========================================
      function calcularGordura() {

         const protocolo = $('#protocolo').val();
         const sexo = $('#sexo_avaliacao').val();
         const idade = 25; // você pode puxar depois do aluno

         let campos = [];
         let soma = 0;

         if (protocolo === 'pollock3') {
            if (sexo === 'masculino') campos = ['peito', 'abdomen_dobra', 'coxa_dobra'];
            if (sexo === 'feminino') campos = ['triceps', 'coxa_dobra', 'supra_iliaca'];
         }

         if (protocolo === 'pollock7') {
            campos = [
               'peito', 'triceps', 'subescapular',
               'axilar_media', 'supra_iliaca',
               'abdomen_dobra', 'coxa_dobra'
            ];
         }

         campos.forEach(id => soma += parseFloat($('#' + id).val()) || 0);

         if (soma === 0) return;

         let densidade = 0;

         // FORMULA POLLOCK 3
         if (protocolo === 'pollock3') {
            if (sexo === 'masculino')
               densidade = 1.10938 - 0.0008267 * soma + 0.0000016 * (soma ** 2) - 0.0002574 * idade;

            if (sexo === 'feminino')
               densidade = 1.0994921 - 0.0009929 * soma + 0.0000023 * (soma ** 2) - 0.0001392 * idade;
         }

         // FORMULA POLLOCK 7
         if (protocolo === 'pollock7') {
            densidade = 1.112 - 0.00043499 * soma + 0.00000055 * (soma ** 2) - 0.00028826 * idade;
         }

         const gordura = ((495 / densidade) - 450).toFixed(2);

         $('#gordura').val(gordura);
         calcularMassaMuscular();
      }

   });
</script>
@stop