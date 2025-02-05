{{-- 디자인 모드에서 위젯를 선택하는 팝업창 입니다. --}}
<x-wire-dialog-modal wire:model="popupForm" :maxWidth="$popupWindowWidth">
    <x-slot name="title">
        <x-flex-between>
            <div>
                {{ __('위젯을 추가합니다.') }}
            </div>
            <div>
                <span class="text-muted" wire:click="cancel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                      </svg>
                </span>
            </div>
        </x-flex-between>
    </x-slot>

    <x-slot name="content">
        <style>
            /* 그리드를 담는 메인 컨테이너 */
            .grid-container {
                display: grid;

                gap: 10px;
                /* 그리드 아이템 사이의 간격 */
                overflow-y: auto;
                /* 수직 스크롤 활성화 */
                overflow-x: hidden;
                /* 수평 스크롤 비활성화 */
                padding: 10px;
            }

            .grid-column-3 {
                grid-template-columns: repeat(3, 1fr);
            }

            .grid-column-4 {
                grid-template-columns: repeat(4, 1fr);
            }


        </style>

        <x-ui-divider>페이지 위젯</x-ui-divider>
        {{-- widgets/raws.json 에 정의된 위젯 목록을 출력합니다. --}}
        <div class="grid-container grid-column-4"
            style="grid-template-columns: repeat({{ count($widgetList) }}, 1fr); @media (max-width: 768px) { grid-template-columns: 1fr; }">
            @foreach ($widgetList as $i => $item)
                <div class="card">
                    <div class="card-body">
                        {{-- @if (isset($item['image']) && $item['image'])
                            <img src="{{ $item['image'] }}" alt="" class="card-img-top">
                        @else
                        @endif

                        <div>
                            <div class="flex justify-between items-top mb-2">
                                <div>
                                    <h5 class="card-title">{{ $item['title'] }}</h5>

                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary" wire:click="store('{{ $item['code'] }}')">
                                        선택
                                    </button>
                                </div>
                            </div>
                            @if (isset($item['description']))
                                <p class="card-text">
                                    {{ $item['description'] }}
                                </p>
                            @endif
                        </div> --}}
                        <div>
                            <button class="btn btn-sm btn-primary w-100" wire:click="store('{{ $item['code'] }}')">
                                {{ $item['title'] }}
                            </button>
                        </div>
                        @if (isset($item['description']))
                            <p class="card-text pt-2">
                                {{ $item['description'] }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <x-ui-divider>
            템플릿 위젯
        </x-ui-divider>

        <div style="padding: 0 10px;">
            <div class="mb-3">
                {!! xSelect()
                ->table('site_widget_type', 'type')
                ->setWire('model.live', 'template_type')
                ->setWidth('medium') !!}
            </div>

            {{-- {{ $template_type }} --}}
        </div>

        <div class="mb-3" style="max-height: 350px; overflow-y: auto;">
            <div class="grid-container grid-column-3">
                @foreach (widgetTemplates($template_type) as $i => $item)
                    <div class="card">
                        @if (isset($item['image']) && $item['image'])
                            <img src="{{ $item['image'] }}" alt="" class="card-img-top">
                        @else
                        @endif

                        <div class="card-body d-flex flex-column" style="height:100%">
                            <div class="flex-grow-1">
                                <h5 class="card-title">{{ $item['name'] }}</h5>
                                @if (isset($item['description']))
                                    <p class="card-text">
                                        {{ $item['description'] }}
                                    </p>
                                @endif
                            </div>

                            <div class="text-end mt-3">
                                <button class="btn btn-sm btn-primary" wire:click="store('{{ $item['name'] }}')">
                                    선택
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach

                <div class="card shadow-sm position-relative">
                    <div class="card-body d-flex flex-column" style="height:100%">
                        <p>관리자 페이지에서 위젯을 추가할 수 있습니다.</p>
                        <a href="http://127.0.0.1:8000/admin/site/widget" target="_blank" class="btn btn-primary btn-sm">
                            위젯 추가
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </x-slot>

    {{-- <x-slot name="footer">
        <div class="flex justify-between">
            <div>

            </div>
            <div class="text-right">
                <button type="button" class="btn btn-secondary" wire:click="cancel">취소</button>
            </div>
        </div>
    </x-slot> --}}
</x-wire-dialog-modal>
