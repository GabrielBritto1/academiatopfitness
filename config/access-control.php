<?php

return [
   'permissions' => [
      'admin' => 'Acesso administrativo total',
      'users.manage' => 'Gerenciar usuários',
      'roles.manage' => 'Gerenciar Acessos e permissões',
      'students.manage' => 'Gerenciar alunos',
      'professors.manage' => 'Gerenciar professores',
      'plans.manage' => 'Gerenciar planos',
      'profile.manage' => 'Gerenciar perfil',
      'avaliacao.manage' => 'Gerenciar avaliações',
      'unidades.manage' => 'Gerenciar unidades',
      'financeiro.manage' => 'Gerenciar financeiro',
      'trainings.manage' => 'Gerenciar treinos',
      'whatsapp.manage' => 'Gerenciar instâncias de WhatsApp',
   ],

   'groups' => [
      'Administrativo' => [
         'admin',
         'users.manage',
         'roles.manage',
         'financeiro.manage',
         'whatsapp.manage',
      ],
      'Cadastros' => [
         'students.manage',
         'professors.manage',
         'plans.manage',
         'avaliacao.manage',
         'unidades.manage',
      ],
   ],

   'default_roles' => [
      'admin' => [
         'admin',
         'users.manage',
         'roles.manage',
         'students.manage',
         'professors.manage',
         'plans.manage',
         'profile.manage',
         'avaliacao.manage',
         'unidades.manage',
         'financeiro.manage',
         'trainings.manage',
         'whatsapp.manage',
      ],
      'aluno' => [],
      'professor' => [
         'profile.manage',
         'avaliacao.manage',
         'trainings.manage',
      ],
   ],
];
