<x-www-app>
    <x-www-layout>
        <x-www-main>

            {{-- 페이지 삽입된 widgets 을 출력합니다. --}}
            @foreach (Action()->widgets() as $i => $widget)
                <section>
                    @livewire('site-widget-add', ['pos' => $i])
                </section>

                <section id="widget" data-pos="{{ $i }}" class="mb-4">
                    <div wire:key="widget-{{ $i }}">
                        @livewire(
                            $widget['element'],
                            [
                                'widget' => $widget,
                                'widget_id' => $i,
                            ],
                            key($i)
                        )
                    </div>
                </section>
            @endforeach

            <section>
                @if (isset($i))
                    @livewire('site-widget-add', ['pos' => ++$i])
                @else
                    @livewire('site-widget-add', ['pos' => 0])
                @endif
            </section>


            {{-- 위젯 popup 추가 --}}
            @livewire('site-widget-create')

            {{-- 페이지 수정 팝업 --}}
            @livewire('site-page-edit')

            {{-- @includeIf('jiny-site-page::pages.script_dragpos') --}}
            {{-- ajax drag move --}}
            @livewire('site-widget-move')

            {{-- @livewire('site-widget-loop') --}}

            @if (isDesign() && isAdmin())
                {{-- @livewire('site-widget-loop') --}}

                {{-- 드레그 하여 element 요소를 삽입합니다. --}}
                @includeIf('jiny-site-page::design.dropzone')
            @else
            @endif

        </x-www-main>
    </x-www-layout>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('page-realod', (event) => {
                console.log("page-realod");
                location.reload();
            });
        });
    </script>
</x-www-app>
