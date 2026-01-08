
<table class="table table-based ">

@foreach($entry->workTimes as $whrs)
    <tr>

        <td>
            {{ __('days.'.  $whrs->day ). ": "}}
        </td>
        <td>
            {{   $whrs->start_time }}
        </td>
        <td>
            {{    $whrs->end_time }}
        </td>
        <td>
            {{    $whrs->active ? 'عمل' : 'عطلة' }}
        </td>
    </tr>
@endforeach
</table>
