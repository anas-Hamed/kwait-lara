@php
    $controller = new $widget['controller'];
    $chart = $controller->chart;
    $path = $controller->getLibraryFilePath();
    $widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-md-4';
@endphp

@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
<div class="{{ $widget['class'] ?? 'card' }}">
    @if (isset($widget['content']['header']))
        <div class="card-header text-center">{!! $widget['content']['header'] !!}</div>
    @endif
    <div class="card-body">

        @if(isset($widget['content']['body']))
            @include($widget['content']['body'],['chart' => $chart,'options' => $widget['content']['options'] ?? []])
        @endif
        <div class="card-wrapper">
            {!! $chart->container() !!}
        </div>

    </div>
</div>
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')
@push('after_scripts')
    @if (is_array($path))
        @foreach ($path as $string)
            <script src="{{ $string }}" charset="utf-8"></script>
        @endforeach
    @elseif (is_string($path))
        <script src="{{ $path }}" charset="utf-8"></script>
    @endif


    @foreach ($chart->plugins as $plugin)
        @include($chart->pluginsViews[$plugin]);
    @endforeach

    <script {!! $chart->displayScriptAttributes() !!}>
        var ctvChart = document.getElementById('{{ $chart->id }}').getContext('2d');
        function {{ $chart->id }}_create(data) {
            {{ $chart->id }}_rendered = true;
            document.getElementById("{{ $chart->id }}_loader").style.display = 'none';
            document.getElementById("{{ $chart->id }}").style.display = 'block';
            window.{{ $chart->id }} = new Chart(document.getElementById("{{ $chart->id }}").getContext("2d"), {
                type: {!! $chart->type ? "'{$chart->type}'" : 'data[0].type' !!},
                data: {
                    labels: {!! $chart->formatLabels() !!},
                    datasets: data
                },
                options: {!! $chart->formatOptions(true) !!},
                plugins: {!! $chart->formatPlugins(true) !!}
            });
        }
        @if ($chart->api_url)
        let {{ $chart->id }}_refresh = function (url) {
            document.getElementById("{{ $chart->id }}").style.display = 'none';
            document.getElementById("{{ $chart->id }}_loader").style.display = 'flex';
            if (typeof url !== 'undefined') {
                {{ $chart->id }}_api_url = url;
            }
            fetch({{ $chart->id }}_api_url)
                .then(data => data.json())
                .then(data => {
                    document.getElementById("{{ $chart->id }}_loader").style.display = 'none';
                    document.getElementById("{{ $chart->id }}").style.display = 'block';
                    {{ $chart->id }}.data.datasets = data;
                    if(data[0] && 'labels' in data[0])
                        {{ $chart->id }}.data.labels = data[0].labels;
                    {{ $chart->id }}.update();
                });
        };
        @endif
        @include('charts::init')

    </script>

@endpush
