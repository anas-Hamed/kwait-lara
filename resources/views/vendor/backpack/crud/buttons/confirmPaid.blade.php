@if(!$entry->has_paid)
    <a href="{{url($crud->route . '/' . $entry->id . '/confirm-paid')}}" class="btn btn-sm btn-toggle-enable">
        <i class="la la-check-circle"></i> {{ __('crud.confirm_paid') }}
    </a>
@endif
