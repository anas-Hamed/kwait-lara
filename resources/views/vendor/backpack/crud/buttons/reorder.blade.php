@if ($crud->get('reorder.enabled') && $crud->hasAccess('reorder'))
    <button class="btn btn-outline-primary reorder-btn" data-style="zoom-in"><span class="ladda-label"><i
                class="la la-arrows"></i> {{ trans('backpack::crud.reorder') }} {{ $crud->entity_name_plural }}</span>
    </button>
    <script>
        document.querySelector('.reorder-btn').addEventListener('click', function () {
            const url = "{!! app('request')->url()!!}";
            const queryString = location.search;
            let queryObject = Object.fromEntries((new URLSearchParams(queryString)).entries()) ;
            if (url.match('company') && !queryObject.category_id ) alert('يجب تحديد التصنيف')
            else location.href = `${url}/reorder${queryString}`;
        })
    </script>
@endif
