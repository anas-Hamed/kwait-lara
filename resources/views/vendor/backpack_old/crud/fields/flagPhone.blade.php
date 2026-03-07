@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
<div class="px-2" id="{{$field['name']}}">
    <vue-tel-input :input-options="{name: '{!! $field["name"] !!}'}"   dir="ltr" value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"></vue-tel-input>
</div>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

@push('crud_fields_styles')
    <!-- no styles -->

@endpush
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
    <script src="https://unpkg.com/vue-tel-input"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-tel-input/dist/vue-tel-input.css" />
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
@endif
@push('crud_fields_scripts')



    <script>
        Vue.use(window['vue-tel-input'], {
            dynamicPlaceholder: true,
            defaultCountry:'{!! env('DEFAULT_COUNTRY','KW') !!}',
            onlyCountries:['KW'],
            mode: 'international'
        })
         new Vue({
            el: '#{!! $field["name"] !!}',

        });
    </script>

@endpush

