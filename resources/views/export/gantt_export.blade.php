<table>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>{{ $project->name }}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td
            style="background: #0952a4; color: #ffffff; font-size: 12px; font-weight: bold;" rowspan="2"
        >{{ __('item') }}</td>
        <td
            style="background: #0952a4; color: #ffffff; font-size: 12px; font-weight: bold;" rowspan="2"
        >{{ __('category') }}</td>
        <td
            style="background: #0952a4; color: #ffffff; font-size: 12px; font-weight: bold;" rowspan="2"
        >{{ __('start') }}</td>
        <td
            style="background: #0952a4; color: #ffffff; font-size: 12px; font-weight: bold;" rowspan="2"
        >{{ __('end') }}</td>
        <td
            style="background: #0952a4; color: #ffffff; font-size: 12px; font-weight: bold;" rowspan="2"
        >{{ __('days') }}</td>
        <td rowspan="2" ></td>
        @foreach($months as $month => $colSpanMonth )
            <td colspan="{{ $colSpanMonth }}">{{ $month }}</td>
        @endforeach
        <td></td>
    </tr>
    <tr>
        @foreach($interval as $day)
            <td>{{ $dateTimeToExcel($day) }}</td>
        @endforeach
        <td></td>
    </tr>
    @php($i = 5)
    @foreach($groups as $group)
        @php($i++)
        <tr>
            <td
                style="font-size: 14pt; font-weight: bold;"
                colspan="5"
            >{{ $group->content }}</td>
        </tr>
        @foreach($items->where('group', $group->id) as $item)
            @php($i++)
            <tr>
                <td>{{ $item->title }}</td><!-- A -->
                <td>{{ $item->type }}</td><!-- B -->
                <td>{{ $dateTimeToExcel($item->start) }}</td><!-- C -->
                <td>{{ $dateTimeToExcel($item->end) }}</td><!-- D -->

                <td>=DATEDIF(C{{ $i }}, D{{ $i }}, "d")</td>
                <td></td>
                @foreach($interval as $col => $day)
                    <td>=IF(AND($C{{ $i }}<={{ $num2Alpha($col + 6) }}$5,$D{{ $i }}>={{ $num2Alpha($col + 6) }}$5),1,"")</td>
                @endforeach
                <td></td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        <td></td>
    </tr>

</table>

