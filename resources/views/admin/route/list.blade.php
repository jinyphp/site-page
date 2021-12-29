<x-datatable>
    <x-data-table-thead>
        <th width='50'>Id</th>

        <th > {!! xWireLink('Route', "orderBy('route')") !!}</th>
        <th width='200'>type</th>
        <th width='200'>page</th>

        <th width='200'>생성일자</th>
    </x-data-table-thead>

    @if(!empty($rows))
    <tbody>
        @foreach ($rows as $item)
        <x-data-table-tr :item="$item" :selected="$selected">
            <td width='50'>{{$item->id}}</td>
            <td >
                {!! $popupEdit($item, $item->route) !!}
            </td>
            <td width='200'>{{$item->type}}</td>
            <td width='200'>{{$item->page}}</td>

            <td width='200'>{{$item->created_at}}</td>
        </x-data-table-tr>
        @endforeach

    </tbody>
    @endif
</x-datatable>


@if(empty($rows))
<div>
    목록이 없습니다.
</div>
@endif


