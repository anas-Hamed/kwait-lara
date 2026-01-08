@php
 $keys_name = [
    'ar_name' => 'الاسم العربي',
    'en_name' => 'الاسم الانكليزي',
    'whatsapp' => 'الواتساب',
    'phone' => 'الهاتف'
];

@endphp
<table>
    <thead>
    <tr>
        <th>الاسم</th>
        <th>القيمة القديمة</th>
        <th>القيمة الجديدة</th>
    </tr>
    </thead>
    @foreach($entry['old_values'] as $key=> $value)
        <tr>
            <td>{{$keys_name[$key]}}</td>
            <td ><span style="background-color:#f6939b ">{{$value}}</span> </td>
            <td ><span style="background-color:#bbf17d ">{{((array)$entry['new_values'])[$key]}}</span> </td>
        </tr>
    @endforeach
</table>

