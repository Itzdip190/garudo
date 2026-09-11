@extends('layouts.admin')

@section('title', 'Notifications Center')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Marketplace Activity & Notifications</h1>
        <p class="page-header-subtitle mb-0">Real-time alerts for customer purchase orders, vendor updates and catalog events.</p>
    </div>
    @if($unreadCount > 0)
        <form action="{{ route('admin.notifications.mark_all_read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-check2-all me-1"></i> Mark All as Read
            </button>
        </form>
    @endif
</div>

<div class="garudo-card p-0 overflow-hidden">
    @if($notifications && count($notifications) > 0)
        <div class="list-group list-group-flush">
            @foreach($notifications as $notif)
                <div class="list-group-item p-3 d-flex align-items-start justify-content-between gap-3 {{ is_null($notif->read_at) ? 'bg-light bg-opacity-50' : '' }}">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 bg-white border shadow-sm" style="width: 42px; height: 42px; font-size: 1.25rem;">
                            <i class="bi {{ $notif->icon ?? 'bi-bell-fill text-primary' }}"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold text-dark mb-0">{{ $notif->title }}</h6>
                                @if(is_null($notif->read_at))
                                    <span class="badge bg-primary rounded-pill" style="font-size: 0.65rem;">NEW</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-1 mt-1">{{ $notif->message }}</p>
                            <span class="text-muted" style="font-size: 0.72rem;">
                                <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        @if($notif->link && $notif->link !== '#')
                            <a href="{{ $notif->link }}" class="btn btn-garudo-primary btn-sm rounded-pill px-3">
                                Inspect <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @endif

                        @if(is_null($notif->read_at) && !str_starts_with($notif->id, 'ord_') && !str_starts_with($notif->id, 'ven_') && !str_starts_with($notif->id, 'prd_'))
                            <form action="{{ route('admin.notifications.mark_read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-light btn-sm rounded-pill" title="Mark as Read">
                                    <i class="bi bi-check2"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash fs-1 mb-2 d-block"></i>
            <h5>No Notifications Right Now</h5>
            <p class="small">New order purchases and vendor updates will appear here in real-time.</p>
        </div>
    @endif
</div>
@endsection
