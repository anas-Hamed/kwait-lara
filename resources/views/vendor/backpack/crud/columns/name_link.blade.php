<?php
$name = null;
if (isset($entry[$column['name']]) && $entry[$column['name']] != null) {
    if (isset($column['entity']) && $entry[$column['entity']] != null) {
        $name = $entry[$column['entity']]->{$column['attribute']};
    } elseif (isset($column['model'])) {
        $name = $column['model']::find($entry[$column['name']])[$column['attribute']];
    }

    $link = "/admin/" . $column['prefix'] . "/" . $entry[$column['name']] . "/show";
}
?>
<span>
  @if( $name == null )
        -
    @else
        <a href="{{$link}}">{{$name}}</a>
    @endif
</span>
