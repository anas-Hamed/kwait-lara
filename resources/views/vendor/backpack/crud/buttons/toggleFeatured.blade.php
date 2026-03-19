@if($entry->is_featured)
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-featured')}}" class="btn btn-sm btn-toggle-disable">
        <i class="la la-ban"></i> {{ __('crud.unpin') }}
    </a>
@else
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-featured')}}" class="btn btn-sm btn-toggle-enable">
        <i class="la la-thumbtack"></i> {{ __('crud.pin') }}
    </a>
@endif
