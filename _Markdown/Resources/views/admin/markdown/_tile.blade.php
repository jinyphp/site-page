@foreach ($rows as $item)
    <x-col-3>
        <x-card class="h-100">
            aaa
        </x-card>
    </x-col-3>
@endforeach

@if(empty($rows))
<div>
    목록이 없습니다.
</div>
@endif


