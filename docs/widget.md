# loop 위젯 호출
`site-widget-loop` 라이브 컴포넌트는 복수의 위젯 컴포넌트를 중첩 라이브와이어 형태로 호출할 수 있습니다. 위젯 데이터는 actions 설정에서 배열값을 읽어오게 됩니다.


```php
{{-- 위젯 반복 --}}
<div wire:key="widget-{{$i}}">
    @livewire(
        $widget['element'],
        [
            'widget' => $widget,
            'widget_id' => $i
        ],
        key($i)
    )
</div>
```
