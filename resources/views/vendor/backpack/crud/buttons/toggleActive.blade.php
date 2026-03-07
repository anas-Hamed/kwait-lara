@if($entry->is_active)
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-active')}}" class="btn btn-sm btn-danger"> تعطيل</a>
@else
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-active')}}" class="btn btn-sm btn-success"> تفعيل</a>
@endif
