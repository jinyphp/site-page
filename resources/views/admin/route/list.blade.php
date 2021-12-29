<x-datatable>
    <x-data-table-thead>
        <th width='50'>Id</th>
        <th width='100'>type</th>
        <th > {!! xWireLink('Route', "orderBy('route')") !!}</th>
        <th width='100'>조회수</th>
        <th width='180'>생성일자</th>
    </x-data-table-thead>

    @if(!empty($rows))
    <tbody>
        @foreach ($rows as $item)
        <x-data-table-tr :item="$item" :selected="$selected">
            <td width='50'>{{$item->id}}</td>
            <td width='100'>{{parserValue($item->type)}}</td>
            <td >
                {!! $popupEdit($item, $item->route) !!}

                <a href="{{$item->route}}" class="px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>

                {{$item->title}}
            </td>
            <td width='180'>{{$item->cnt}}</td>
            <td width='180'>{{$item->created_at}}</td>
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


