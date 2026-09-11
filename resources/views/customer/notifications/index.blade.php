@extends('layouts.customer')

@section('title', 'Notifications')

@section('content')
<div class="notifications-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Notifications</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Stay updated with delivery dispatches, vendor quote revisions, and exclusive trade discounts.</p>
        </div>
    </div>

    <!-- Notifications Card -->
    <div class="card-custom">
        @if($notifications->count() > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($notifications as $item)
            <div class="p-3 rounded-3 d-flex align-items-start gap-3 transition" style="background: {{ $item->read_at ? '#FFFFFF' : '#F8FAFC' }}; border: 1px solid {{ $item->read_at ? '#E2E8F0' : '#CBD5E1' }};">
                <div class="p-2 rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.25rem;">
                    <i class="bi bi-bell-fill text-success"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <h3 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">
                            {{ $item->data['title'] ?? 'Platform Update' }}
                            @if(!$item->read_at)
                            <span class="badge bg-danger rounded-circle p-1 ms-1" style="width: 8px; height: 8px; display: inline-block;"></span>
                            @endif
                        </h3>
                        <span class="text-muted" style="font-size: 0.72rem;">{{ $item->created_at?->diffForHumans() }}</span>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.82rem; line-height: 1.4;">
                        {{ $item->data['message'] ?? ($item->data['body'] ?? 'No message details available.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
                <i class="bi bi-bell-slash fs-3"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Notifications</h5>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">You are all caught up! Important order milestones and quote alerts will appear here.</p>
        </div>
        @endif
    </div>

</div>
@endsection
