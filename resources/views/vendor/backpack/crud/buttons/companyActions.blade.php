@php
    $tempId = 'tempDisableModal-' . $entry->id;
    $sendId = 'sendMsgModal-' . $entry->id;
    $delId = 'adminDelModal-' . $entry->id;
    $until = optional($entry->disabled_until)->format('Y-m-d\TH:i');
    $minDate = now()->addMinutes(5)->format('Y-m-d\TH:i');
    $defaultDate = now()->addDays(7)->format('Y-m-d\TH:i');
@endphp

<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
            data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false"
            title="{{ __('crud.more_actions') }}">
        <i class="la la-ellipsis-h"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-toggle="modal"
               data-bs-target="#{{ $sendId }}" data-target="#{{ $sendId }}">
                <i class="la la-paper-plane text-info me-1"></i> {{ __('crud.send_direct_message') }}
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-toggle="modal"
               data-bs-target="#{{ $tempId }}" data-target="#{{ $tempId }}">
                <i class="la la-clock text-warning me-1"></i> {{ __('crud.temporary_disable') }}
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-toggle="modal"
               data-bs-target="#{{ $delId }}" data-target="#{{ $delId }}">
                <i class="la la-trash me-1"></i> {{ __('crud.delete') }}
            </a>
        </li>
    </ul>
</div>

{{-- Send Direct Message Modal --}}
<div class="modal fade" id="{{ $sendId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url($crud->route . '/' . $entry->id . '/send-direct-message') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('crud.send_direct_message') }} — {{ $entry->ar_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">
                    {{ __('crud.send_direct_message_help') }}
                    @if($entry->user)<strong>{{ $entry->user->name }}</strong>@endif
                </p>
                <div class="mb-3">
                    <label for="t-{{ $entry->id }}" class="form-label">{{ __('crud.title') }}</label>
                    <input type="text" id="t-{{ $entry->id }}" name="title" class="form-control" maxlength="120" required>
                </div>
                <div class="mb-3">
                    <label for="b-{{ $entry->id }}" class="form-label">{{ __('crud.notification_text') }}</label>
                    <textarea id="b-{{ $entry->id }}" name="body" class="form-control" rows="4" maxlength="2000" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">{{ __('crud.cancel') }}</button>
                <button type="submit" class="btn btn-info"><i class="la la-paper-plane"></i> {{ __('crud.send') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Temporary Disable Modal --}}
<div class="modal fade" id="{{ $tempId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url($crud->route . '/' . $entry->id . '/temporary-disable') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('crud.temporary_disable') }} — {{ $entry->ar_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">{{ __('crud.temporary_disable_help') }}</p>
                <label for="u-{{ $entry->id }}" class="form-label">{{ __('crud.disabled_until') }}</label>
                <input type="datetime-local" id="u-{{ $entry->id }}" name="until"
                       class="form-control" min="{{ $minDate }}" value="{{ $until ?: $defaultDate }}" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">{{ __('crud.cancel') }}</button>
                <button type="submit" class="btn btn-warning"><i class="la la-clock"></i> {{ __('crud.confirm') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Admin Delete Modal --}}
<div class="modal fade" id="{{ $delId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url($crud->route . '/' . $entry->id . '/admin-delete') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('crud.delete') }} — {{ $entry->ar_name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('crud.admin_delete_company_warning') }}</p>
                <p class="text-muted small">{{ __('crud.admin_delete_company_recoverable') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">{{ __('crud.cancel') }}</button>
                <button type="submit" class="btn btn-danger"><i class="la la-trash"></i> {{ __('crud.confirm_delete') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    if (window.__kwCompanyModalsInit) return;
    window.__kwCompanyModalsInit = true;
    var attach = function () {
        document.addEventListener('show.bs.modal', function (e) {
            var modal = e.target;
            if (!modal || !modal.classList || !modal.classList.contains('modal')) return;
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
        }, true);
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attach);
    } else {
        attach();
    }
})();
</script>
