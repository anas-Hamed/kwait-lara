@php
    $images = $entry[$column['entity']];
    $attribute = isset($column['attribute']) ? $column['attribute'] : 'image';
@endphp
<span>
  @if( empty($images) )
        لا يوجد صور
    @else
      <div class="position-relative" style="padding-top: {{$column['ratio'] ?? 50}}%">
           <div class="position-absolute" style="inset: 0;width: 100%;height: 100%">
                <div dir='ltr' style='width: 80%' id='{{$column['name']}}' class='carousel slide' data-ride='carousel'>
                  <ol class='carousel-indicators'>
                      @foreach($images as $key=>$image)
                          <li data-target='#{{$column['name']}}' data-slide-to='{{$key}}' class="{{$key == 0 ? 'active' : ''}}"></li>
                      @endforeach
                  </ol>
                  <div class='carousel-inner'>
                        @foreach($images as $key=>$image)

                          <div class="{{$key == 0 ? "carousel-item active" : "carousel-item" }}">
                             <img width='100%' src='{{url("/storage/". $image->$attribute)}}' />
                        </div>
                      @endforeach
                  </div>
                  <a class='carousel-control-prev' href='#{{$column['name']}}' role='button' data-slide='prev'>
                    <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                    <span class='sr-only'>Previous</span>
                  </a>
                  <a class='carousel-control-next' href='#{{$column['name']}}' role='button' data-slide='next'>
                    <span class='carousel-control-next-icon' aria-hidden='true'></span>
                    <span class='sr-only'>Next</span>
                  </a>
                </div>
           </div>
      </div>


      @push('after_scripts')
            <script>
                $('.carousel').carousel({
                    interval: 2000,
                })
            </script>
            <style>
                .carousel-control-prev,.carousel-control-next{
                    background-color: rgba(0,0,0,0.4);


                }
            </style>
      @endpush
    @endif
</span>
