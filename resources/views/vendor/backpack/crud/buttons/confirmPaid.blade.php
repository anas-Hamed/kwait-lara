@if(!$entry->has_paid)
    <a href="{{url($crud->route . '/' . $entry->id . '/confirm-paid')}}" class="btn btn-sm btn-success"> تأكيد الدفع</a>
@endif

