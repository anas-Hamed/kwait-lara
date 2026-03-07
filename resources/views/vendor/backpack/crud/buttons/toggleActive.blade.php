@if($entry->is_active)
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-active')}}" class="btn btn-sm btn-toggle-disable">
        <i class="la la-ban"></i> {{ __('crud.disable') }}
    </a>
@else
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-active')}}" class="btn btn-sm btn-toggle-enable">
        <i class="la la-check-circle"></i> {{ __('crud.enable') }}
    </a>
@endif
