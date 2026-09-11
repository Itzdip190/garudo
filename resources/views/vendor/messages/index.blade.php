@extends('layouts.vendor')

@section('title', 'Buyer Messages & Admin Inquiries')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Messages & Buyer Inquiries</h4>
        <p class="text-muted mb-0 small">Direct communication channel with contractors, wholesale buyers, and Garudo Admin.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form action="{{ route('vendor.messages.mark_all_read') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-success btn-sm fw-semibold">
                <i class="bi bi-check2-all me-1"></i> Mark All Read
            </button>
        </form>
        <a href="{{ route('vendor.messages.index') }}" class="btn btn-sm {{ ($filter ?? 'all') === 'all' ? 'btn-success' : 'btn-outline-secondary' }}">All</a>
        <a href="{{ route('vendor.messages.index', ['filter' => 'customer']) }}" class="btn btn-sm {{ ($filter ?? '') === 'customer' ? 'btn-success' : 'btn-outline-secondary' }}">Buyer RFQs</a>
        <a href="{{ route('vendor.messages.index', ['filter' => 'admin']) }}" class="btn btn-sm {{ ($filter ?? '') === 'admin' ? 'btn-success' : 'btn-outline-secondary' }}">Admin Desk</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Messages List (Strictly No Horizontal Scroll) -->
    <div class="col-lg-5">
        <div class="card-custom p-0 overflow-hidden shadow-sm" style="overflow-x: hidden !important;">
            <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-chat-dots-fill text-success fs-5"></i>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">Active Conversations</span>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success small fw-semibold px-2 py-1" style="border-radius: 9999px;">{{ count($threads) }} Threads</span>
            </div>
            
            <div class="list-group list-group-flush" style="max-height: 580px; overflow-y: auto; overflow-x: hidden !important; scrollbar-width: thin;" id="threadsListGroup">
                @forelse($threads as $index => $thread)
                    <a href="javascript:void(0)" onclick="loadThread({{ json_encode($thread) }})" class="list-group-item list-group-item-action p-3 thread-item border-bottom d-flex align-items-start gap-2 text-decoration-none {{ (isset($targetThread) && $targetThread->id == $thread->id) || (!isset($targetThread) && $index === 0) ? 'bg-light border-start border-4 border-success' : 'bg-white' }}" id="thread-item-{{ $thread->id }}" style="overflow-x: hidden !important; max-width: 100%; min-width: 0;">
                        <div class="rounded-circle {{ $thread->sender_type === 'admin' ? 'bg-success bg-opacity-10 text-success' : 'bg-primary bg-opacity-10 text-primary' }} border d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 38px; height: 38px; font-size: 1.1rem;">
                            <i class="bi {{ $thread->sender_avatar ?? 'bi-person-circle' }}"></i>
                        </div>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                <span class="fw-bold text-dark text-truncate" style="font-size: 0.82rem;">
                                    {{ $thread->sender_name }}
                                </span>
                                <span class="text-muted flex-shrink-0" style="font-size: 0.68rem;">
                                    {{ is_string($thread->created_at) ? $thread->created_at : $thread->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            <div class="fw-semibold text-dark text-truncate mb-1" style="font-size: 0.76rem;">
                                {{ $thread->subject ?? 'Inquiry' }}
                            </div>
                            <div class="text-muted text-truncate" style="font-size: 0.73rem;">
                                {{ $thread->latest_message }}
                            </div>
                        </div>
                        @if($thread->unread)
                            <span class="p-1 bg-danger rounded-circle align-self-center flex-shrink-0" style="width: 7px; height: 7px;" title="Unread"></span>
                        @endif
                    </a>
                @empty
                    <div class="text-center py-5 text-muted p-3">
                        <i class="bi bi-chat-square-text fs-1 d-block mb-2 text-muted opacity-50"></i>
                        <p class="small mb-0">No active conversations found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Active Thread & Live Chat Workspace -->
    <div class="col-lg-7">
        <div class="card-custom p-0 overflow-hidden shadow-sm d-flex flex-column" style="min-height: 580px; overflow-x: hidden !important;">
            <!-- Thread Header -->
            <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                <div style="min-width: 0;">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="fw-bold mb-0 text-dark text-truncate" id="threadSenderName">Select a conversation</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary small" id="threadRoleBadge" style="font-size: 0.68rem;">Verified Buyer</span>
                    </div>
                    <small class="text-muted text-truncate d-block mt-1" id="threadTopic" style="font-size: 0.75rem;">Click any message on the left to read history and reply.</small>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success small flex-shrink-0 ms-2" id="threadStatusBadge">ACTIVE</span>
            </div>

            <!-- Thread Messages History -->
            <div class="p-3 p-md-4 flex-grow-1" id="threadMessagesContainer" style="max-height: 410px; overflow-y: auto; overflow-x: hidden !important; background-color: #F8FAFC; scrollbar-width: thin;">
                <div class="text-center py-5 text-muted" id="emptyThreadNotice">
                    <i class="bi bi-chat-left-quote fs-1 d-block mb-2 text-success opacity-50"></i>
                    <p class="small mb-0">Select a message thread from the left panel.</p>
                </div>
            </div>

            <!-- Reply Input Box -->
            <div class="p-3 border-top bg-white" id="replyBoxContainer">
                <form id="replyForm" onsubmit="sendReply(event)">
                    @csrf
                    <input type="hidden" id="replyThreadId" value="">
                    <div class="input-group">
                        <textarea id="replyMessageInput" class="form-control" rows="2" placeholder="Type your response to the buyer or admin..." required style="resize: none; font-size: 0.85rem; border-color: var(--garudo-border);"></textarea>
                        <button type="submit" class="btn btn-success fw-semibold px-4 d-flex align-items-center justify-content-center" style="background-color: var(--garudo-primary); border: none;" id="sendReplyBtn">
                            <i class="bi bi-send-fill me-1"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentThread = null;

    function formatChatDate(dateInput) {
        if (!dateInput) return 'Recently';
        if (typeof dateInput === 'string') {
            if (dateInput.includes('ago') || dateInput.includes('Just now') || dateInput.includes('Today')) {
                return dateInput;
            }
        }
        try {
            const d = new Date(dateInput);
            if (isNaN(d.getTime())) return dateInput;
            return d.toLocaleString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        } catch (e) {
            return 'Recently';
        }
    }

    function loadThread(thread) {
        currentThread = thread;
        document.querySelectorAll('.thread-item').forEach(el => {
            el.classList.remove('bg-light', 'border-start', 'border-4', 'border-success');
            el.classList.add('bg-white');
        });
        
        const activeEl = document.getElementById('thread-item-' + thread.id);
        if (activeEl) {
            activeEl.classList.remove('bg-white');
            activeEl.classList.add('bg-light', 'border-start', 'border-4', 'border-success');
            const unreadDot = activeEl.querySelector('.bg-danger');
            if (unreadDot) unreadDot.remove();
        }

        document.getElementById('threadSenderName').innerText = thread.sender_name;
        document.getElementById('threadRoleBadge').innerText = thread.sender_role || (thread.sender_type === 'admin' ? 'Staff Desk' : 'Verified Buyer');
        document.getElementById('threadTopic').innerText = (thread.subject || 'Inquiry') + ' | ' + (thread.product_name || 'General');
        document.getElementById('threadStatusBadge').innerText = (thread.status || 'Active').toUpperCase();
        document.getElementById('replyThreadId').value = thread.id;

        const container = document.getElementById('threadMessagesContainer');
        container.innerHTML = '';

        // Formatted date for initial message
        const formattedDate = formatChatDate(thread.created_at);
        const isAdmin = (thread.sender_type === 'admin');

        // Initial question/message bubble
        const initialBubble = `
            <div class="d-flex gap-2 mb-3 align-items-start">
                <div class="rounded-circle ${isAdmin ? 'bg-success bg-opacity-10 text-success' : 'bg-light border text-primary'} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                    <i class="bi ${thread.sender_avatar ?? (isAdmin ? 'bi-shield-check' : 'bi-person-fill')}"></i>
                </div>
                <div class="bg-white border rounded-3 p-3 shadow-sm" style="max-width: 82%; min-width: 220px;">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <span class="fw-bold small text-dark">${thread.sender_name}</span>
                        <span class="badge ${isAdmin ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary'}" style="font-size: 0.65rem;">${thread.sender_role || 'Buyer'}</span>
                    </div>
                    <div class="small text-dark" style="white-space: pre-wrap; line-height: 1.45;">${thread.latest_message || ''}</div>
                    <div class="text-muted mt-2 text-end" style="font-size: 0.68rem;">${formattedDate}</div>
                </div>
            </div>
        `;
        container.innerHTML += initialBubble;

        // Any threaded replies
        if (thread.replies && thread.replies.length > 0) {
            thread.replies.forEach(rep => {
                const isVendor = rep.sender_type === 'vendor';
                const replyTime = formatChatDate(rep.created_at);
                const replyBubble = `
                    <div class="d-flex gap-2 mb-3 ${isVendor ? 'justify-content-end' : 'justify-content-start'} align-items-start">
                        ${!isVendor ? '<div class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0 text-primary" style="width: 36px; height: 36px;"><i class="bi bi-person-fill"></i></div>' : ''}
                        <div class="${isVendor ? 'bg-success text-white' : 'bg-white border text-dark'} rounded-3 p-3 shadow-sm" style="max-width: 82%; min-width: 220px; ${isVendor ? 'background-color: var(--garudo-primary) !important;' : ''}">
                            <div class="fw-bold small mb-1 ${isVendor ? 'text-white' : 'text-dark'}">${isVendor ? 'You (Seller)' : (rep.sender_name || 'Buyer')}</div>
                            <div class="small" style="white-space: pre-wrap; line-height: 1.45;">${rep.message}</div>
                            <div class="${isVendor ? 'text-white-50' : 'text-muted'} mt-2 text-end" style="font-size: 0.68rem;">${replyTime}</div>
                        </div>
                    </div>
                `;
                container.innerHTML += replyBubble;
            });
        }

        container.scrollTop = container.scrollHeight;

        // Inform backend thread was opened/read
        fetch(`/vendor/messages/${thread.id}`, {
            headers: { 'Accept': 'application/json' }
        }).catch(err => {});
    }

    function sendReply(e) {
        e.preventDefault();
        const threadId = document.getElementById('replyThreadId').value;
        const msg = document.getElementById('replyMessageInput').value.trim();
        if (!msg || !threadId) return;

        const container = document.getElementById('threadMessagesContainer');
        const nowFormatted = new Date().toLocaleString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        const replyBubble = `
            <div class="d-flex gap-2 mb-3 justify-content-end align-items-start">
                <div class="bg-success text-white rounded-3 p-3 shadow-sm" style="max-width: 82%; min-width: 220px; background-color: var(--garudo-primary) !important;">
                    <div class="fw-bold small mb-1 text-white">You (Seller)</div>
                    <div class="small" style="white-space: pre-wrap; line-height: 1.45;">${msg}</div>
                    <div class="text-white-50 mt-2 text-end" style="font-size: 0.68rem;">${nowFormatted}</div>
                </div>
            </div>
        `;
        container.innerHTML += replyBubble;
        container.scrollTop = container.scrollHeight;
        document.getElementById('replyMessageInput').value = '';

        // AJAX submit to server
        fetch(`/vendor/messages/${threadId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: msg })
        }).then(r => r.json()).catch(err => {});
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($targetThread))
            loadThread({!! json_encode($targetThread) !!});
        @elseif(isset($threads) && count($threads) > 0)
            loadThread({!! json_encode($threads[0]) !!});
        @endif
    });
</script>
@endpush
@endsection
