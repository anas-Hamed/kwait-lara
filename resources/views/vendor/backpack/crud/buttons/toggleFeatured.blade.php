@if($entry->is_featured)
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-featured')}}" class="btn btn-sm btn-danger"> إلغاء التثبيت</a>
@else
    <a href="{{url($crud->route . '/' . $entry->id . '/toggle-featured')}}" class="btn btn-sm btn-success"> تثبيت</a>
@endif
