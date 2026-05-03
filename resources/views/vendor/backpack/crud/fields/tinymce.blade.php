{{-- Tiny MCE (overridden to enforce RTL by default) --}}
@php
$tinymceIdentifier = uniqid('tinymce_');

$rtlDefaults = [
    'directionality' => 'rtl',
    'language'       => 'ar',
    'language_url'   => asset('storage/basset/tinymce-6.3.2/tinymce-dist-6.3.2/langs/ar.js'),
    'content_style'  => 'body { direction: rtl; text-align: right; font-family: Tahoma, "Segoe UI", Arial, sans-serif; font-size: 14px; line-height: 1.7; }',
];

$defaultOptions = array_merge([
    'file_picker_callback' => 'elFinderBrowser',
    'selector'             => 'textarea.'.$tinymceIdentifier,
    'plugins'              => 'image,link,media,anchor,lists',
    'toolbar'              => 'undo redo | blocks | bold italic underline | numlist bullist | alignleft aligncenter alignright alignjustify | ltr rtl | link image media | removeformat',
    //these two options allow tinymce to save the path of images "/upload/image.jpg" instead of the relative server path "../../../uploads/image.jpg"
    'relative_urls'        => false,
    'remove_script_host'   => true,
], $rtlDefaults);

$field['options'] = array_merge($defaultOptions, $field['options'] ?? []);
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    <textarea
        name="{{ $field['name'] }}"
        data-init-function="bpFieldInitTinyMceElement"
        data-options='{!! trim(json_encode($field['options'])) !!}'
        bp-field-main-input
        @include('crud::fields.inc.attributes', ['default_class' =>  'form-control tinymce '.$tinymceIdentifier])
        >{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    {{-- include tinymce js --}}
    @bassetArchive('https://github.com/tinymce/tinymce-dist/archive/refs/tags/6.3.2.zip', 'tinymce-6.3.2')
    @basset('tinymce-6.3.2/tinymce-dist-6.3.2/tinymce.min.js')

    @bassetBlock('backpack/pro/fields/tinymce-field.js')
    <script type="text/javascript">
    function bpFieldInitTinyMceElement(element) {
        // grab the configuration defined in PHP
        var configuration = element.data('options');

        // the target should be the element the function has been called on
        configuration['target'] = element;
        configuration['file_picker_callback'] = eval(configuration['file_picker_callback']);

        // automatically update the textarea value on editor change
        configuration['setup'] = function (editor) {
             editor.on('change', function(e) {
                let hasOriginalEvent = typeof e.originalEvent !== 'undefined';
                if(hasOriginalEvent && e.originalEvent.type !== 'savecontent') {
                    editor.save();
                    element.trigger('change');
                }
                if(!hasOriginalEvent && typeof e.level.content !== 'undefined') {
                    editor.save();
                    element.trigger('change');
                }
            });

            editor.on('input', function(e) {
                if(e.inputType === 'insertText') {
                    editor.save();
                    element.trigger('change');
                }
            });

            editor.on('Undo Redo', function(e) {
                editor.save();
                element.trigger('change');
            });
        };

        // initialize the TinyMCE editor
        tinymce.init(configuration);

        element.on('CrudField:disable', function(e) {
            tinymce.activeEditor.mode.set('readonly');
        });

        element.on('CrudField:enable', function(e) {
            tinymce.activeEditor.mode.set('design');
        });
    }

    function elFinderBrowser (callback, value, meta) {
        tinymce.activeEditor.windowManager.openUrl({
            title: 'elFinder 2.0',
            url: '{{ backpack_url('elfinder/tinymce5') }}',
            width: 900,
            height: 460,
            onMessage: function (dialogApi, details) {
                if (details.mceAction === 'fileSelected') {
                    const file = details.data.file;
                    const info = file.name;

                    if (meta.filetype === 'file') {
                        callback(file.url, {text: info, title: info});
                    }
                    if (meta.filetype === 'image') {
                        callback(file.url, {alt: info});
                    }
                    if (meta.filetype === 'media') {
                        callback(file.url);
                    }

                    dialogApi.close();
                }
            }
        });
    }
    </script>
    @endBassetBlock
@endpush
