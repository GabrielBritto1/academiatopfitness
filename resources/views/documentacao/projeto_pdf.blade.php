<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <title>Documentação do Projeto Academia Top Fitness</title>
   <style>
      @page {
         margin: 28px 30px 36px;
      }

      * {
         box-sizing: border-box;
      }

      body {
         font-family: DejaVu Sans, sans-serif;
         color: #1d2430;
         font-size: 10.5px;
         line-height: 1.5;
         margin: 0;
      }

      h1,
      h2,
      h3,
      h4,
      p {
         margin: 0;
      }

      .page-break {
         page-break-before: always;
      }

      .cover {
         background: #102033;
         color: #ffffff;
         padding: 34px 34px 28px;
         border-radius: 18px;
      }

      .cover-top {
         width: 92px;
         height: 8px;
         background: #f7b538;
         border-radius: 8px;
         margin-bottom: 20px;
      }

      .cover h1 {
         font-size: 28px;
         line-height: 1.2;
         font-weight: bold;
         margin-bottom: 12px;
      }

      .cover h2 {
         font-size: 13px;
         color: #d6deeb;
         font-weight: normal;
         margin-bottom: 24px;
      }

      .cover-grid {
         width: 100%;
         border-collapse: separate;
         border-spacing: 12px 0;
         margin: 18px -12px 0;
      }

      .cover-card {
         width: 50%;
         vertical-align: top;
         background: #172c44;
         border: 1px solid #2e4967;
         border-radius: 12px;
         padding: 14px 16px;
      }

      .cover-card-title {
         display: block;
         font-size: 9px;
         color: #f7b538;
         text-transform: uppercase;
         letter-spacing: 0.7px;
         margin-bottom: 6px;
      }

      .cover-card strong {
         font-size: 12px;
      }

      .logo-box {
         margin-top: 22px;
         text-align: right;
      }

      .logo-box img {
         width: 180px;
      }

      .intro {
         margin-top: 18px;
         background: #f5f7fb;
         border: 1px solid #d8e1ee;
         border-radius: 14px;
         padding: 18px 20px;
      }

      .intro h3 {
         font-size: 15px;
         color: #102033;
         margin-bottom: 10px;
      }

      .pill {
         display: inline-block;
         padding: 5px 9px;
         border-radius: 999px;
         font-size: 9px;
         font-weight: bold;
         margin: 0 6px 6px 0;
      }

      .pill.dark {
         background: #102033;
         color: #ffffff;
      }

      .pill.gold {
         background: #f7b538;
         color: #102033;
      }

      .pill.soft {
         background: #e8eef7;
         color: #29415e;
      }

      .section {
         margin-top: 22px;
      }

      .section-title {
         background: #102033;
         color: #ffffff;
         border-radius: 12px;
         padding: 12px 16px;
         font-size: 16px;
         font-weight: bold;
         margin-bottom: 14px;
      }

      .summary-table,
      .detail-table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 10px;
      }

      .summary-table th,
      .summary-table td,
      .detail-table th,
      .detail-table td {
         border: 1px solid #d9e2ef;
         padding: 8px 9px;
         vertical-align: top;
      }

      .summary-table th,
      .detail-table th {
         background: #edf3fb;
         color: #17304d;
         font-size: 9.5px;
         text-transform: uppercase;
         letter-spacing: 0.4px;
      }

      .summary-table td strong,
      .detail-table td strong {
         color: #102033;
      }

      .note,
      .example,
      .highlight {
         border-radius: 12px;
         padding: 12px 14px;
         margin-top: 12px;
      }

      .note {
         background: #f4f6f9;
         border-left: 5px solid #637387;
      }

      .example {
         background: #fff4de;
         border-left: 5px solid #f7b538;
      }

      .highlight {
         background: #e8f7ef;
         border-left: 5px solid #23a26d;
      }

      .two-col {
         width: 100%;
         border-collapse: separate;
         border-spacing: 10px 0;
         margin: 10px -10px 0;
      }

      .two-col td {
         width: 50%;
         vertical-align: top;
         background: #ffffff;
         border: 1px solid #dde6f2;
         border-radius: 12px;
         padding: 12px 14px;
      }

      .box-title {
         font-size: 11px;
         font-weight: bold;
         color: #102033;
         margin-bottom: 7px;
      }

      ul {
         margin: 8px 0 0 18px;
         padding: 0;
      }

      li {
         margin-bottom: 5px;
      }

      .module {
         border: 1px solid #d9e2ef;
         border-radius: 14px;
         margin-bottom: 14px;
         overflow: hidden;
      }

      .module-header {
         background: #1b3553;
         color: #ffffff;
         padding: 11px 14px;
      }

      .module-header h3 {
         font-size: 14px;
         margin-bottom: 3px;
      }

      .module-header p {
         color: #d8e2f1;
         font-size: 9.5px;
      }

      .module-body {
         padding: 14px;
         background: #ffffff;
      }

      .footer {
         position: fixed;
         bottom: -16px;
         left: 0;
         right: 0;
         text-align: center;
         font-size: 9px;
         color: #6f7d8c;
      }
   </style>
</head>

<body>
   @php
      $logoPath = public_path('img/logo.png');
   @endphp

   <div class="footer">
      Documento funcional do projeto Academia Top Fitness
   </div>

   <div class="cover">
      <div class="cover-top"></div>
      <h1>Documentação Completa do Projeto Academia Top Fitness</h1>
      <h2>Material de apresentação e entrega com visão detalhada das áreas do sistema, suas finalidades e a experiência esperada para cada perfil de uso.</h2>

      <span class="pill gold">Visão do Cliente</span>
      <span class="pill soft">Operação da Academia</span>
      <span class="pill soft">Gestão Administrativa</span>
      <span class="pill dark">Acompanhamento do Aluno</span>

      <table class="cover-grid">
         <tr>
            <td class="cover-card">
               <span class="cover-card-title">Objetivo do Documento</span>
               <strong>Explicar o funcionamento do sistema de forma clara e comercial</strong>
               <p style="margin-top: 8px;">Este documento foi preparado para apoiar apresentação, aprovação, treinamento e entrega formal do projeto ao cliente.</p>
            </td>
            <td class="cover-card">
               <span class="cover-card-title">Versão Gerada</span>
               <strong>{{ $generatedAt }}</strong>
               <p style="margin-top: 8px;">Conteúdo organizado por módulos, com exemplos práticos e explicações sobre o que cada área entrega no dia a dia da academia.</p>
            </td>
         </tr>
      </table>

      @if(file_exists($logoPath))
      <div class="logo-box">
         <img src="{{ $logoPath }}" alt="Academia Top Fitness">
      </div>
      @endif
   </div>

   <div class="intro">
      <h3>Apresentação geral do sistema</h3>
      <p>O sistema Academia Top Fitness foi concebido para centralizar a operação da academia em um único ambiente. Ele apoia desde o primeiro contato com o aluno, passando por cadastro, contratação de plano, controle financeiro, avaliação física, criação de treinos e geração de documentos de acompanhamento. Na prática, isso significa mais organização para a equipe, mais controle para a gestão e uma experiência mais profissional para o aluno.</p>
      <table class="summary-table">
         <thead>
            <tr>
               <th>Área</th>
               <th>Objetivo principal</th>
               <th>Valor percebido pelo cliente</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td><strong>Dashboard</strong></td>
               <td>Apresentar a situação geral da academia em poucos segundos.</td>
               <td>Mais agilidade para acompanhar números e atividades recentes.</td>
            </tr>
            <tr>
               <td><strong>Cadastros</strong></td>
               <td>Organizar pessoas, unidades, planos e modalidades.</td>
               <td>Base operacional confiável e centralizada.</td>
            </tr>
            <tr>
               <td><strong>Acompanhamento do aluno</strong></td>
               <td>Registrar avaliação física e fichas de treino.</td>
               <td>Atendimento mais personalizado e histórico completo.</td>
            </tr>
            <tr>
               <td><strong>Financeiro</strong></td>
               <td>Controlar entradas, saídas, cobranças e despesas.</td>
               <td>Maior previsibilidade, organização e visão do caixa.</td>
            </tr>
            <tr>
               <td><strong>Relatórios em PDF</strong></td>
               <td>Emitir documentos de apoio à operação e à gestão.</td>
               <td>Facilidade para apresentar resultados e formalizar informações.</td>
            </tr>
         </tbody>
      </table>
   </div>

   <div class="section">
      <div class="section-title">Perfis de uso e visão operacional</div>
      <table class="two-col">
         <tr>
            <td>
               <div class="box-title">Perfis presentes no sistema</div>
               <ul>
                  <li><strong>Administrador:</strong> responsável pela gestão geral do sistema, cadastros e controle administrativo.</li>
                  <li><strong>Professor:</strong> atua no acompanhamento técnico dos alunos, avaliações e montagem de treinos.</li>
                  <li><strong>Aluno:</strong> representa o cliente atendido e concentra histórico, planos, avaliações e treinos.</li>
               </ul>
            </td>
            <td>
               <div class="box-title">Como o sistema apoia a operação</div>
               <ul>
                  <li>Permite que a recepção organize cadastros e cobranças com rapidez.</li>
                  <li>Ajuda a equipe técnica a acompanhar a evolução física do aluno.</li>
                  <li>Dá à gestão uma visão centralizada de alunos, planos, unidades e financeiro.</li>
                  <li>Reduz controles paralelos e dispersão de informações.</li>
               </ul>
            </td>
         </tr>
      </table>

      <div class="highlight">
         <strong>Diferencial funcional:</strong> o sistema conecta o lado comercial, técnico e financeiro da academia, evitando que o aluno seja tratado em partes separadas. O histórico permanece reunido em um mesmo ambiente.
      </div>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Mapa das abas e visão resumida dos módulos</div>
      <table class="summary-table">
         <thead>
            <tr>
               <th>Menu / módulo</th>
               <th>O que o usuário encontra</th>
               <th>Principal utilidade</th>
               <th>Exemplo de uso</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td><strong>Dashboard</strong></td>
               <td>Painel com indicadores, atalhos e atividades recentes.</td>
               <td>Ter visão rápida da operação.</td>
               <td>Conferir total de alunos, professores e últimas avaliações.</td>
            </tr>
            <tr>
               <td><strong>Usuários</strong></td>
               <td>Lista de contas internas e respectivos perfis.</td>
               <td>Gerenciar quem acessa o sistema.</td>
               <td>Criar um novo usuário administrativo.</td>
            </tr>
            <tr>
               <td><strong>Professores</strong></td>
               <td>Cadastro e listagem dos professores do sistema.</td>
               <td>Controlar a equipe técnica.</td>
               <td>Adicionar um novo professor avaliador.</td>
            </tr>
            <tr>
               <td><strong>Alunos</strong></td>
               <td>Base principal de clientes da academia.</td>
               <td>Consultar e atualizar o cadastro do aluno.</td>
               <td>Registrar um aluno novo e vinculá-lo a uma unidade.</td>
            </tr>
            <tr>
               <td><strong>Avaliação</strong></td>
               <td>Histórico de avaliações físicas e comparativos.</td>
               <td>Acompanhar evolução corporal.</td>
               <td>Gerar o PDF da avaliação de um aluno.</td>
            </tr>
            <tr>
               <td><strong>Unidades</strong></td>
               <td>Cadastro das filiais da academia.</td>
               <td>Organizar estrutura física da operação.</td>
               <td>Inserir uma nova unidade com nome, endereço e logo.</td>
            </tr>
            <tr>
               <td><strong>Planos</strong></td>
               <td>Catálogo comercial com preços e benefícios.</td>
               <td>Estruturar ofertas da academia.</td>
               <td>Criar um plano premium com benefícios diferenciados.</td>
            </tr>
            <tr>
               <td><strong>Planilha de Treino</strong></td>
               <td>Treinos padrão e fichas personalizadas.</td>
               <td>Prescrever e acompanhar treinos.</td>
               <td>Aplicar uma planilha padrão para um aluno novo.</td>
            </tr>
            <tr>
               <td><strong>Financeiro</strong></td>
               <td>Caixa, contas a receber, contas a pagar e categorias.</td>
               <td>Controlar a saúde financeira da academia.</td>
               <td>Registrar uma mensalidade e uma despesa operacional.</td>
            </tr>
            <tr>
               <td><strong>Relatórios</strong></td>
               <td>Geração de documentos em PDF.</td>
               <td>Consolidar informações para gestão.</td>
               <td>Emitir relatório financeiro filtrado por período.</td>
            </tr>
         </tbody>
      </table>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Descrição detalhada de cada aba</div>

      <div class="module">
         <div class="module-header">
            <h3>1. Dashboard</h3>
            <p>Painel inicial com visão rápida e estratégica da operação.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> ao entrar no sistema, o usuário encontra um resumo visual com os principais números da academia. A ideia da tela é economizar tempo e facilitar a leitura do cenário geral logo no início do uso.</p>
            <ul>
               <li>Exibe quantidade de alunos cadastrados.</li>
               <li>Exibe quantidade de professores cadastrados.</li>
               <li>Exibe total de planos disponíveis.</li>
               <li>Exibe quantidade de unidades cadastradas.</li>
               <li>Mostra o volume de avaliações e planilhas registradas.</li>
               <li>Apresenta atalhos rápidos para avaliações e planilhas de treino.</li>
               <li>Lista atividades recentes, como avaliações e fichas criadas.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> antes de abrir o atendimento do dia, a gestão pode entrar no Dashboard para verificar o total de alunos ativos, conferir quantas avaliações foram feitas recentemente e acessar rapidamente as últimas planilhas criadas.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>2. Aba Usuários</h3>
            <p>Área destinada à administração das contas que acessam o sistema.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> permite controlar as contas internas da operação, separando quem pode acessar o sistema e com qual perfil de atuação.</p>
            <ul>
               <li>Lista nome, e-mail e perfil de cada usuário.</li>
               <li>Permite criar novos usuários com perfil definido.</li>
               <li>Permite pesquisar usuários por nome.</li>
               <li>Permite visualizar, editar e excluir registros.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> quando a academia contrata uma nova recepcionista ou um novo gestor, o administrador pode criar a conta correspondente e definir o tipo de acesso adequado.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>3. Aba Professores</h3>
            <p>Onde se vê e se organiza a equipe técnica da academia.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> reúne os professores cadastrados e oferece uma visão rápida sobre quem está ativo, com quais vínculos aparece relacionado e quais ações podem ser feitas no cadastro.</p>
            <ul>
               <li>Exibe nome, e-mail, cargo, unidade relacionada, plano associado e status.</li>
               <li>Permite cadastrar professor de forma rápida.</li>
               <li>Permite ativar ou inativar o cadastro.</li>
               <li>Permite filtrar por nome e status.</li>
               <li>Permite abrir o cadastro para visualização.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> se um professor inicia atividades na unidade principal, a gestão pode incluí-lo no sistema, deixá-lo ativo e usá-lo posteriormente nas avaliações físicas e planilhas de treino.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>4. Aba Alunos</h3>
            <p>Cadastro central dos clientes da academia.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> é a porta de entrada para a gestão do aluno. A partir daqui, o atendimento consegue registrar dados pessoais, vincular unidade, consultar status e abrir o histórico completo do cliente.</p>
            <ul>
               <li>Exibe nome, e-mail, unidade e status do aluno.</li>
               <li>Permite cadastrar novos alunos com dados completos.</li>
               <li>Permite informar CPF, telefone, idade, sexo, observações e foto.</li>
               <li>Permite editar o cadastro.</li>
               <li>Permite ativar ou inativar o aluno.</li>
               <li>Permite abrir a página completa do aluno para acompanhamento detalhado.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> ao realizar uma matrícula, a recepção registra o aluno com seus dados principais e já o deixa preparado para contratação de plano, avaliação física e criação de treino.
            </div>
         </div>
      </div>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Página do aluno e abas internas de acompanhamento</div>

      <div class="module">
         <div class="module-header">
            <h3>5. Tela completa do aluno</h3>
            <p>Painel individual do cliente com visão consolidada.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta tela faz:</strong> concentra em um único local as informações mais importantes do aluno. É uma página estratégica, porque conecta o cadastro com o acompanhamento técnico e comercial.</p>
            <p style="margin-top: 8px;"><strong>Resumo superior:</strong> foto, CPF, e-mail, data de cadastro, unidade, status, última atualização e total de avaliações físicas.</p>
            <table class="detail-table">
               <thead>
                  <tr>
                     <th>Aba interna</th>
                     <th>O que o usuário consegue fazer</th>
                     <th>Informações apresentadas</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td><strong>Ficha Técnica</strong></td>
                     <td>Consultar os dados cadastrais e observações relevantes.</td>
                     <td>Idade, telefone, sexo e observações do aluno.</td>
                  </tr>
                  <tr>
                     <td><strong>Avaliações Físicas</strong></td>
                     <td>Ver o histórico resumido de avaliações.</td>
                     <td>Lista de avaliações com dados principais, como IMC e gordura.</td>
                  </tr>
                  <tr>
                     <td><strong>Planos</strong></td>
                     <td>Consultar os planos ligados ao aluno.</td>
                     <td>Relação dos planos contratados.</td>
                  </tr>
                  <tr>
                     <td><strong>Ficha de Treino</strong></td>
                     <td>Criar, revisar e exportar planilhas de treino.</td>
                     <td>Planilhas, treinos, exercícios e botão de PDF completo.</td>
                  </tr>
               </tbody>
            </table>
            <div class="example">
               <strong>Exemplo:</strong> durante um atendimento de renovação, a equipe pode abrir a página do aluno, confirmar os dados, verificar o histórico de avaliações e revisar a ficha de treino sem precisar navegar por várias telas separadas.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>6. Aba Unidades</h3>
            <p>Cadastro das filiais ou locais de atendimento da academia.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> organiza a estrutura física da operação e permite que outras áreas do sistema trabalhem com a lógica de unidade, como planos, financeiro e atendimento do aluno.</p>
            <ul>
               <li>Apresenta as unidades em formato visual, com destaque para nome, endereço e logo.</li>
               <li>Permite cadastrar uma nova unidade.</li>
               <li>Permite editar dados e atualizar a identidade visual da unidade.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> se a academia inaugura uma nova filial, a gestão pode cadastrá-la nesta aba e depois utilizá-la nas matrículas, nos planos e no controle financeiro.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>7. Módulo Modalidades</h3>
            <p>Catálogo de serviços e atividades ofertadas pela academia.</p>
         </div>
         <div class="module-body">
            <p><strong>O que este módulo faz:</strong> registra as modalidades oferecidas pela academia, permitindo organizar melhor o portfólio de atendimento, o posicionamento comercial e a gestão das ofertas.</p>
            <ul>
               <li>Permite cadastrar nome, descrição, duração e nível de dificuldade.</li>
               <li>Permite ativar ou inativar modalidades.</li>
               <li>Permite editar e visualizar cada item.</li>
               <li>Ajuda a estruturar atividades como musculação, funcional, spinning e outras.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> caso a academia passe a oferecer uma nova atividade coletiva, a modalidade pode ser registrada para compor a organização do portfólio da empresa.
            </div>
         </div>
      </div>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Planos, contratação e visão comercial</div>

      <div class="module">
         <div class="module-header">
            <h3>8. Aba Planos</h3>
            <p>Catálogo comercial dos serviços contratáveis da academia.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> apresenta os planos comercializados pela academia de forma visual e organizada por unidade, destacando preço, benefícios e identidade de cada oferta.</p>
            <ul>
               <li>Permite cadastrar nome, valor e benefícios do plano.</li>
               <li>Permite definir uma cor para facilitar identificação visual.</li>
               <li>Organiza os planos por unidade.</li>
               <li>Permite editar as condições comerciais.</li>
               <li>Serve como base para a contratação feita no carrinho de planos.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> a academia pode cadastrar os planos Básico, Plus e Premium com preços e benefícios distintos, facilitando a apresentação comercial ao aluno durante a matrícula.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>9. Carrinho de Planos</h3>
            <p>Fluxo de contratação comercial vinculado ao aluno.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta tela faz:</strong> transforma a escolha do plano em uma operação prática e organizada. Nela, o usuário escolhe o aluno, seleciona unidade e plano, informa desconto se necessário e registra a forma de pagamento.</p>
            <ul>
               <li>Permite selecionar o aluno que receberá o plano.</li>
               <li>Permite adicionar uma ou mais combinações de unidade e plano.</li>
               <li>Calcula o valor total com desconto de forma visual.</li>
               <li>Registra a forma de pagamento.</li>
               <li>Conecta a contratação ao controle financeiro da academia.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> ao fechar a matrícula de um novo aluno, a recepção seleciona o plano escolhido, aplica um desconto promocional e finaliza o cadastro comercial em poucos passos.
            </div>
         </div>
      </div>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Planilhas de treino, treinos e exercícios</div>

      <div class="module">
         <div class="module-header">
            <h3>10. Aba Planilha de Treino</h3>
            <p>Área dedicada à prescrição e organização dos treinos.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> organiza tanto modelos padronizados de treino quanto fichas personalizadas criadas para alunos específicos. Isso permite ganhar produtividade sem perder personalização.</p>
            <ul>
               <li>Separa as planilhas em dois grupos: padrão e personalizadas.</li>
               <li>Permite criar modelos reutilizáveis para diferentes perfis.</li>
               <li>Permite visualizar a relação entre aluno, professor, unidade e plano.</li>
               <li>Permite exportar a ficha de treino em PDF.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> o coordenador técnico pode manter modelos como “Iniciante”, “Hipertrofia” ou “Condicionamento”, e depois adaptá-los rapidamente para novos alunos.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>11. Criação da planilha</h3>
            <p>Fluxo flexível para montar treino do zero ou a partir de um modelo existente.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta tela faz:</strong> oferece ao professor duas formas de trabalho. A primeira é usar uma planilha padrão já pronta. A segunda é criar uma planilha nova, personalizada para o aluno.</p>
            <ul>
               <li>Permite aplicar um modelo padrão a um aluno.</li>
               <li>Permite criar planilha personalizada com professor, unidade, plano e observações.</li>
               <li>Permite criar planilhas padrão que poderão ser reutilizadas futuramente.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> um professor pode aplicar a planilha padrão “Hipertrofia Iniciante” para um aluno recém-chegado e depois personalizar treinos e observações conforme necessidade.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>12. Detalhes da planilha</h3>
            <p>Ambiente de consulta e manutenção da ficha completa.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta tela faz:</strong> centraliza tudo o que compõe a planilha de treino. É onde o usuário revisa a estrutura da ficha, adiciona treinos e acompanha os exercícios vinculados.</p>
            <ul>
               <li>Mostra contexto da planilha: aluno, professor, unidade e plano.</li>
               <li>Permite editar e excluir a planilha.</li>
               <li>Permite adicionar novos treinos.</li>
               <li>Lista os exercícios com ordem, séries, repetições, carga, descanso e observações.</li>
               <li>Permite gerar o PDF da ficha completa.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> em uma reavaliação, o professor pode abrir a planilha atual do aluno, ajustar a divisão do treino e emitir uma nova versão em PDF para uso em sala.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>13. Submódulos Treino e Exercícios</h3>
            <p>Detalhamento fino da prescrição técnica.</p>
         </div>
         <div class="module-body">
            <p><strong>O que estes submódulos fazem:</strong> permitem construir a ficha no nível operacional, separando os blocos de treino e os exercícios que compõem cada sessão.</p>
            <ul>
               <li><strong>Treino:</strong> organiza sigla, nome, dias da semana e observações.</li>
               <li><strong>Exercícios:</strong> organiza nome, séries, repetições, carga, descanso, observações e ordem de execução.</li>
               <li>Facilitam a prescrição estruturada e clara para o aluno.</li>
            </ul>
         </div>
      </div>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Avaliação física e evolução do aluno</div>

      <div class="module">
         <div class="module-header">
            <h3>14. Aba Avaliação</h3>
            <p>Ponto de entrada do acompanhamento físico dos alunos.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> organiza a lista de alunos que podem ser avaliados e oferece acesso direto para criar uma nova avaliação ou consultar o histórico já existente.</p>
            <ul>
               <li>Permite iniciar uma nova avaliação física.</li>
               <li>Permite abrir o histórico de avaliações do aluno.</li>
               <li>Facilita a rotina de acompanhamento técnico.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> no dia de retorno do aluno, o professor pode localizar rapidamente o nome na aba de avaliação e iniciar uma nova coleta de medidas.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>15. Formulário de avaliação</h3>
            <p>Coleta detalhada das medidas corporais do aluno.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta tela faz:</strong> permite registrar os dados físicos do aluno em uma avaliação estruturada, com cálculos automáticos que ajudam na análise do professor.</p>
            <ul>
               <li>Coleta peso e altura.</li>
               <li>Registra perimetrias como cintura, tórax, abdômen, quadril, braços, coxa e panturrilha.</li>
               <li>Permite registrar dobras cutâneas conforme o protocolo selecionado.</li>
               <li>Calcula automaticamente IMC, percentual de gordura e massa muscular.</li>
               <li>Permite salvar observações complementares.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> em uma avaliação inicial, o professor registra as medidas do aluno e entrega uma leitura mais profissional da condição física já no primeiro atendimento.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>16. Histórico de avaliações</h3>
            <p>Consulta cronológica das avaliações físicas já realizadas.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta tela faz:</strong> organiza as avaliações por data e professor, servindo como base para consulta, comparação e emissão de documentos.</p>
            <ul>
               <li>Lista as avaliações já registradas do aluno.</li>
               <li>Permite abrir o PDF individual de cada avaliação.</li>
               <li>Permite gerar documento consolidado com filtro por data.</li>
               <li>Permite comparar avaliações.</li>
               <li>Permite visualizar gráficos de evolução.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> após alguns meses de treino, o professor pode abrir o histórico do aluno para mostrar a evolução entre a primeira avaliação e as mais recentes.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>17. Comparação e gráficos</h3>
            <p>Ferramentas de apresentação da evolução física.</p>
         </div>
         <div class="module-body">
            <p><strong>O que estas telas fazem:</strong> permitem transformar os dados das avaliações em leitura comparativa e visual, o que agrega valor ao acompanhamento do aluno.</p>
            <ul>
               <li>A comparação em PDF mostra diferenças entre duas ou mais avaliações.</li>
               <li>Os gráficos ajudam a visualizar tendências ao longo do tempo.</li>
               <li>Esses recursos apoiam a percepção de resultado e retenção do aluno.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> o professor pode mostrar ao aluno a evolução do peso, da gordura corporal e da massa muscular em formato gráfico, tornando o progresso mais claro e motivador.
            </div>
         </div>
      </div>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Financeiro e relatórios de gestão</div>

      <div class="module">
         <div class="module-header">
            <h3>18. Menu Financeiro</h3>
            <p>Centro de controle das movimentações financeiras da academia.</p>
         </div>
         <div class="module-body">
            <table class="detail-table">
               <thead>
                  <tr>
                     <th>Subárea</th>
                     <th>Finalidade</th>
                     <th>Como ajuda a operação</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td><strong>Caixa / Fluxo de Caixa</strong></td>
                     <td>Consolidar entradas e saídas da academia.</td>
                     <td>Ajuda a gestão a visualizar saldo, receitas e despesas.</td>
                  </tr>
                  <tr>
                     <td><strong>Contas a Receber</strong></td>
                     <td>Controlar cobranças pendentes e recebidas.</td>
                     <td>Facilita o acompanhamento de mensalidades e outras receitas.</td>
                  </tr>
                  <tr>
                     <td><strong>Contas a Pagar</strong></td>
                     <td>Controlar compromissos financeiros da academia.</td>
                     <td>Ajuda a organizar despesas e vencimentos.</td>
                  </tr>
                  <tr>
                     <td><strong>Categorias</strong></td>
                     <td>Classificar receitas e despesas.</td>
                     <td>Melhora a análise gerencial e a organização financeira.</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>19. Caixa / Fluxo de Caixa</h3>
            <p>Visão consolidada do movimento financeiro.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta tela faz:</strong> apresenta de forma direta o comportamento financeiro da academia, permitindo leitura rápida dos valores que entram, dos valores que saem e do saldo resultante.</p>
            <ul>
               <li>Mostra total de receitas.</li>
               <li>Mostra total de despesas.</li>
               <li>Mostra saldo consolidado.</li>
               <li>Permite filtrar por tipo, status, unidade e período.</li>
               <li>Permite abrir detalhes das transações.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> no fechamento semanal, a gestão pode consultar o fluxo de caixa para verificar se o saldo acompanhou o esperado e quais transações impactaram o resultado.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>20. Contas a Receber</h3>
            <p>Controle das receitas previstas e recebidas.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> organiza os valores que a academia tem a receber, como mensalidades e outras cobranças relacionadas aos alunos.</p>
            <ul>
               <li>Permite filtrar por status e unidade.</li>
               <li>Permite marcar um valor como recebido.</li>
               <li>Permite consultar vencimentos.</li>
               <li>Permite abrir detalhes e editar lançamentos.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> a recepção pode abrir as contas a receber do dia, identificar o que ainda está pendente e registrar rapidamente os pagamentos efetuados.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>21. Contas a Pagar</h3>
            <p>Controle das despesas e obrigações financeiras.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> registra as saídas financeiras da academia, facilitando o acompanhamento de despesas, vencimentos e pagamentos realizados.</p>
            <ul>
               <li>Permite filtrar por status e unidade.</li>
               <li>Permite marcar uma despesa como paga.</li>
               <li>Permite consultar vencimentos e detalhes.</li>
               <li>Permite editar lançamentos existentes.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> a gestão pode acompanhar despesas como aluguel, manutenção e fornecedores, mantendo controle mais claro sobre os compromissos da operação.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>22. Categorias Financeiras</h3>
            <p>Organização das classificações financeiras da academia.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> permite criar a estrutura que identifica e separa receitas e despesas por tipo, melhorando a leitura gerencial.</p>
            <ul>
               <li>Permite cadastrar categoria de receita.</li>
               <li>Permite cadastrar categoria de despesa.</li>
               <li>Permite ativar e inativar categorias.</li>
               <li>Permite editar e excluir quando necessário.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> a academia pode trabalhar com categorias como Mensalidade, Avaliação, Loja, Manutenção, Limpeza e Fornecedores para tornar o financeiro mais organizado.
            </div>
         </div>
      </div>

      <div class="module">
         <div class="module-header">
            <h3>23. Aba Relatórios</h3>
            <p>Área voltada à emissão de documentos gerenciais em PDF.</p>
         </div>
         <div class="module-body">
            <p><strong>O que esta aba faz:</strong> reúne pontos de saída documental do sistema. O destaque atual está no relatório financeiro, que permite gerar um PDF filtrado com visão consolidada do movimento financeiro.</p>
            <ul>
               <li>Permite filtrar por período.</li>
               <li>Permite filtrar por unidade, tipo, status, categoria e forma de pagamento.</li>
               <li>Gera documento em PDF para análise e apresentação.</li>
               <li>Ajuda no fechamento gerencial e na conferência da operação.</li>
            </ul>
            <div class="example">
               <strong>Exemplo:</strong> no fechamento mensal, o gestor pode gerar o relatório financeiro da unidade desejada e levar o documento pronto para reunião ou prestação de contas.
            </div>
         </div>
      </div>
   </div>

   <div class="page-break"></div>

   <div class="section">
      <div class="section-title">Documentos em PDF entregues pelo sistema</div>
      <table class="summary-table">
         <thead>
            <tr>
               <th>Documento</th>
               <th>Finalidade</th>
               <th>Benefício para a academia</th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td><strong>Avaliação individual</strong></td>
               <td>Apresentar os dados de uma avaliação específica do aluno.</td>
               <td>Facilita acompanhamento técnico e entrega profissional do resultado.</td>
            </tr>
            <tr>
               <td><strong>Consolidado de avaliações</strong></td>
               <td>Reunir avaliações de um período em um único documento.</td>
               <td>Ajuda no histórico e no acompanhamento longitudinal.</td>
            </tr>
            <tr>
               <td><strong>Comparação de avaliações</strong></td>
               <td>Comparar duas ou mais avaliações em um mesmo PDF.</td>
               <td>Valoriza a percepção de evolução do aluno.</td>
            </tr>
            <tr>
               <td><strong>Planilha de treino completa</strong></td>
               <td>Gerar a ficha completa de treino do aluno.</td>
               <td>Facilita impressão, consulta e entrega do treino.</td>
            </tr>
            <tr>
               <td><strong>Relatório financeiro</strong></td>
               <td>Apresentar movimentações e totais financeiros filtrados.</td>
               <td>Ajuda a gestão na análise e no fechamento da operação.</td>
            </tr>
         </tbody>
      </table>

      <div class="note">
         <strong>Importância prática:</strong> a geração de PDFs transforma o sistema em uma ferramenta não apenas de registro, mas também de apresentação profissional, padronização de processos e apoio à tomada de decisão.
      </div>
   </div>

   <div class="section">
      <div class="section-title">Fluxos recomendados de uso no dia a dia</div>
      <table class="two-col">
         <tr>
            <td>
               <div class="box-title">Fluxo 1. Nova matrícula</div>
               <ul>
                  <li>Cadastrar o aluno na aba <strong>Alunos</strong>.</li>
                  <li>Contratar o plano na área de <strong>Planos / Carrinho de Planos</strong>.</li>
                  <li>Confirmar o lançamento em <strong>Contas a Receber</strong>.</li>
                  <li>Registrar a primeira <strong>Avaliação Física</strong>.</li>
                  <li>Criar ou aplicar uma <strong>Planilha de Treino</strong>.</li>
               </ul>
            </td>
            <td>
               <div class="box-title">Fluxo 2. Acompanhamento contínuo</div>
               <ul>
                  <li>Consultar a página completa do aluno.</li>
                  <li>Revisar pagamentos e situação financeira.</li>
                  <li>Registrar nova avaliação e comparar evolução.</li>
                  <li>Atualizar treino e gerar nova ficha em PDF.</li>
               </ul>
            </td>
         </tr>
      </table>

      <div class="highlight">
         <strong>Conclusão:</strong> o sistema Academia Top Fitness foi estruturado para oferecer controle, agilidade e profissionalismo em toda a jornada do aluno. Ele atende ao cadastro, ao acompanhamento técnico, à gestão comercial e ao controle financeiro, entregando uma solução unificada para o dia a dia da academia.
      </div>
   </div>

</body>

</html>
