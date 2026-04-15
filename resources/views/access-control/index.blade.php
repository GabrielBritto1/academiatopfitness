@extends('adminlte::page')

@section('title', 'Acessos e Permissões')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
   <h1 class="text-bold"><i class="fas fa-user-shield"></i> Acessos e Permissões</h1>
</div>
@stop

@section('content')
<div class="row">
   <div class="col-lg-4">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Novo Acesso</h3>
         </div>
         <div class="card-body">
            <form method="POST" action="{{ route('access-control.roles.store') }}">
               @csrf
               <div class="form-group">
                  <label for="role_name">Nome</label>
                  <input type="text" name="name" id="role_name" class="form-control" placeholder="ex: recepcao" required>
               </div>
               <button type="submit" class="btn btn-warning text-bold">Criar Acesso</button>
            </form>
         </div>
      </div>

      <!-- <div class="card">
         <div class="card-header">
            <h3 class="card-title">Nova Permissão</h3>
         </div>
         <div class="card-body">
            <form method="POST" action="{{ route('access-control.permissions.store') }}">
               @csrf
               <div class="form-group">
                  <label for="permission_name">Nome</label>
                  <input type="text" name="name" id="permission_name" class="form-control" placeholder="ex: users.manage" required>
               </div>
               <button type="submit" class="btn btn-warning text-bold">Criar Permissão</button>
            </form>
         </div>
      </div> -->
   </div>

   <div class="col-lg-8">
      @foreach($roles as $role)
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">{{ $role->formatted_name }}</h3>
         </div>
         <div class="card-body">
            <form method="POST" action="{{ route('access-control.roles.permissions.sync', $role->id) }}">
               @csrf
               @method('PUT')

               @foreach($permissionGroups as $groupName => $permissionNames)
               <div class="mb-3">
                  <h5 class="text-bold">{{ $groupName }}</h5>
                  <div class="row">
                     @foreach($permissionNames as $permissionName)
                     @php
                        $permission = $permissions->firstWhere('name', $permissionName);
                     @endphp
                     @if($permission)
                     <div class="col-md-6">
                        <div class="form-check mb-2">
                           <input
                              class="form-check-input"
                              type="checkbox"
                              name="permissions[]"
                              id="permission_{{ $role->id }}_{{ $permission->id }}"
                              value="{{ $permission->id }}"
                              {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                           >
                           <label class="form-check-label" for="permission_{{ $role->id }}_{{ $permission->id }}">
                              <strong>{{ $permission->formatted_name }}</strong>
                              <small class="d-block text-muted">{{ $permission->name }}</small>
                           </label>
                        </div>
                     </div>
                     @endif
                     @endforeach
                  </div>
               </div>
               @endforeach

               @php
                  $groupedPermissions = collect($permissionGroups)->flatten()->unique()->values();
                  $customPermissions = $permissions->filter(fn ($permission) => ! $groupedPermissions->contains($permission->name));
               @endphp

               @if($customPermissions->isNotEmpty())
               <div class="mb-3">
                  <h5 class="text-bold">Outras Permissões</h5>
                  <div class="row">
                     @foreach($customPermissions as $permission)
                     <div class="col-md-6">
                        <div class="form-check mb-2">
                           <input
                              class="form-check-input"
                              type="checkbox"
                              name="permissions[]"
                              id="permission_{{ $role->id }}_{{ $permission->id }}"
                              value="{{ $permission->id }}"
                              {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                           >
                           <label class="form-check-label" for="permission_{{ $role->id }}_{{ $permission->id }}">
                              <strong>{{ $permission->formatted_name }}</strong>
                              <small class="d-block text-muted">{{ $permission->name }}</small>
                           </label>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
               @endif

               <button type="submit" class="btn btn-warning text-bold">Salvar Permissões</button>
            </form>
         </div>
      </div>
      @endforeach
   </div>
</div>
@stop
