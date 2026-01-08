
<table class="table table-based ">
    <tr>
        <th>الرقم</th>
        <th>وتس</th>
        <th>اتصال</th>
    </tr>
@foreach($entry->company_phones as $phone)
    <tr>
        <td>
            {{$phone->number}}
        </td>
        <td>
            {{$phone->is_whatsapp? 'نعم':'لا'}}
        </td>
        <td>
            {{$phone->is_call? 'نعم':'لا'}}
        </td>
    </tr>
@endforeach
</table>
