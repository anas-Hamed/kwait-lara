@php
    $modalId = 'adminDelModal-' . $entry->id;
@endphp

<a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-toggle="modal"
   data-bs-target="#{{ $modalId }}" data-target="#{{ $modalId }}">
    <i class="la la-trash"></i> {{ __('crud.delete') }}
</a>

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
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
                <button type="submit" class="btn btn-danger">
                    <i class="la la-trash"></i> {{ __('crud.confirm_delete') }}
                </button>
            </div>
        </form>
    </div>
</div>
