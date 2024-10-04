<div>
    <x-loading-indicator/>

    @foreach ($widgets as $i => $widget)
        <section id="widget" data-pos="{{ $i }}">

            <div wire:key="widget-{{ $i }}">
                @livewire(
                    $widget['element'],
                    [
                        'widget' => $widget,
                        'widget_id' => $i
                    ],
                    key($i)
                )
            </div>

        </section>
    @endforeach

</div>
