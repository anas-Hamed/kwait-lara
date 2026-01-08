<div class="d-flex">
    @if($entry[$column['name']] != null)
    @foreach($entry[$column['name']] as $item)
        <div class="p-1 text-center">
            <div class="rounded bg-primary p-1">{{$item}}</div>
        </div>
    @endforeach
    @else
        -
    @endif
</div>

