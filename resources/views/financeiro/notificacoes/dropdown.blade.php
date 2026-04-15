@if($notifications->isEmpty())
<div class="dropdown-item dropdown-header">Sem notificações</div>
<div class="dropdown-divider"></div>
<div class="dropdown-item text-center text-muted">
   Nenhum aviso disponível.
</div>
@else
<div class="dropdown-item dropdown-header">{{ $notifications->count() }} notificações</div>
<div class="dropdown-divider"></div>
@foreach($notifications as $notification)
<a href="{{ $notification['url'] }}" class="dropdown-item">
   <div class="d-flex align-items-start">
      <div class="mr-3">
         <i class="{{ $notification['icon'] }}"></i>
      </div>
      <div class="flex-grow-1" style="white-space: normal; line-height: 1.3;">
         <span class="text-sm font-weight-bold d-block {{ $notification['tone_class'] }}">{{ $notification['message'] }}</span>
         <small class="text-muted">{{ $notification['secondary_text'] }}</small>
      </div>
      <div class="ml-3 text-sm {{ $notification['tone_class'] }}">
         {{ $notification['date_label'] }}
      </div>
   </div>
</a>
<div class="dropdown-divider"></div>
@endforeach
@endif
