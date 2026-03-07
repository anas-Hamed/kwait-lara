<span>
    @if($entry[$column['name']])
        <div class="mt-3 map" id="map"></div>
    @else
        -
    @endif

</span>
@if($entry[$column['name']])

    @push('after_scripts')
        @if( isset($entry[$column['name']]) )
            <script>
                function initMap() {
                    // The location of Uluru
                    var uluru = {lat: <?php echo $entry[$column['name']]->lat ;?>, lng: <?php echo $entry[$column['name']]->lng ;?>};
                    // The map, centered at Uluru
                    var map = new google.maps.Map(
                        document.getElementById('map'), {zoom: 15, center: uluru});
                    // The marker, positioned at Uluru
                    var marker = new google.maps.Marker({position: uluru, map: map});
                }
            </script>
        @endif
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=<?php echo env('MAP_KEY') ?>&callback=initMap">
        </script>
    @endpush
@endif
@push('after_styles')
<style>
    .map {
        height: {!! (isset($column['height']) ?  $column['height'] : 200 ) . 'px'!!};
        width: 100%;
        z-index: 6000000;
    }
</style>
@endpush
