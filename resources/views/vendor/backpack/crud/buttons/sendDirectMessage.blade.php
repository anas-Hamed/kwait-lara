@php
    $modalId = 'sendMsgModal-' . $entry->id;
@endphp

<a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-toggle="modal"
   data-bs-target="#{{ $modalId }}" data-target="#{{ $modalId }}">
    <i class="la la-paper-plane"></i> {{ __('crud.send_direct_message') }}
</a>

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
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
                    @if($entry->user)
                        <strong>{{ $entry->user->name }}</strong>
                    @endif
                </p>
                <div class="mb-3">
                    <label for="title-{{ $entry->id }}" class="form-label">{{ __('crud.title') }}</label>
                    <input type="text" id="title-{{ $entry->id }}" name="title" class="form-control" maxlength="120" required>
                </div>
                <div class="mb-3">
                    <label for="body-{{ $entry->id }}" class="form-label">{{ __('crud.notification_text') }}</label>
                    <textarea id="body-{{ $entry->id }}" name="body" class="form-control" rows="4" maxlength="2000" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">{{ __('crud.cancel') }}</button>
                <button type="submit" class="btn btn-info">
                    <i class="la la-paper-plane"></i> {{ __('crud.send') }}
                </button>
            </div>
        </form>
    </div>
</div>
