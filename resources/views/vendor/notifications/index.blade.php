@extends('layouts.vendor')

@section('title', 'Notifications Center')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Notifications Center</h4>
        <p class="text-muted mb-0 small">Real-time alerts regarding wholesale purchases, bulk orders, product approvals, stock status, and account updates.</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('vendor.notifications.mark_all_read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-success btn-sm fw-semibold">
                <i class="bi bi-check2-all me-1"></i> Mark All as Read
            </button>
        </form>
    </div>
</div>

<div class="card-custom p-0 overflow-hidden shadow-sm" style="overflow-x: hidden !important;">
    <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bell-fill text-success fs-5"></i>
            <span class="fw-bold text-dark" style="font-size: 0.9rem;">Recent Activity Feed</span>
        </div>
        @if($unreadCount > 0)
            <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold px-2 py-1" style="border-radius: 9999px;">{{ $unreadCount }} Unread</span>
        @else
            <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1" style="border-radius: 9999px;">All Caught Up</span>
        @endif
    </div>

    <div class="list-group list-group-flush" style="overflow-x: hidden !important;">
        @forelse($notifications as $notif)
            <div class="list-group-item p-3 d-flex align-items-start gap-3 {{ $notif->read_at ? 'bg-white' : 'bg-light bg-opacity-75' }}" style="overflow-x: hidden !important; max-width: 100%; min-width: 0;">
                <div class="p-2 rounded-circle bg-white border shadow-sm fs-5 text-success flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi {{ $notif->icon ?? 'bi-bell-fill' }}"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <div class="d-flex align-items-center gap-2 text-truncate">
                            <h6 class="fw-bold text-dark small mb-0 text-truncate">{{ $notif->title }}</h6>
                            @if(!$notif->read_at)
                                <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 0.65rem;">NEW</span>
                            @endif
                        </div>
                        <small class="text-muted flex-shrink-0" style="font-size: 0.72rem;">
                            {{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Recently' }}
                        </small>
                    </div>
                    <p class="text-muted small mb-2" style="line-height: 1.45; word-break: break-word;">{{ $notif->message }}</p>
                    <div class="d-flex align-items-center gap-3">
                        @if($notif->link && $notif->link !== '#')
                            <a href="{{ $notif->link }}" class="btn btn-sm btn-outline-success py-1 px-3 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                <span>View Details</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                        @if(!$notif->read_at)
                            <form action="{{ route('vendor.notifications.mark_read', $notif->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm p-0 text-muted text-decoration-none fw-medium" style="font-size: 0.75rem;">
                                    <i class="bi bi-check2"></i> Mark as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted p-4">
                <i class="bi bi-bell-slash fs-1 d-block mb-2 text-muted opacity-50"></i>
                <p class="small mb-0">No notifications at this time.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

