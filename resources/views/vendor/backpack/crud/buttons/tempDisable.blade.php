@php
    $modalId = 'tempDisableModal-' . $entry->id;
    $until = optional($entry->disabled_until)->format('Y-m-d\TH:i');
    $minDate = now()->addMinutes(5)->format('Y-m-d\TH:i');
    $defaultDate = now()->addDays(7)->format('Y-m-d\TH:i');
@endphp

<a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-toggle="modal"
   data-bs-target="#{{ $modalId }}" data-target="#{{ $modalId }}">
    <i class="la la-clock"></i> {{ __('crud.temporary_disable') }}
</a>

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url($crud->route . '/' . $entry->id . '/temporary-disable') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('crud.temporary_disable') }} — {{ $entry->ar_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">{{ __('crud.temporary_disable_help') }}</p>
                <label for="until-{{ $entry->id }}" class="form-label">{{ __('crud.disabled_until') }}</label>
                <input type="datetime-local"
                       id="until-{{ $entry->id }}"
                       name="until"
                       class="form-control"
                       min="{{ $minDate }}"
                       value="{{ $until ?: $defaultDate }}"
                       required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">{{ __('crud.cancel') }}</button>
                <button type="submit" class="btn btn-warning">
                    <i class="la la-clock"></i> {{ __('crud.confirm') }}
                </button>
            </div>
        </form>
    </div>
</div>
