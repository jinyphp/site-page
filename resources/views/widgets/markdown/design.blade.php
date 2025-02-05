<article>
    @if (!$design)
        {{-- 일반모드, 태그 제거 --}}
        {!! clean($content) !!}

    @else
        @if($editable)
        {{-- 수정모드 --}}

        {!! xTextarea()
            ->setWire('model.defer',"forms.markdown")
        !!}
        <x-flex-between class="gap-4 mt-2">
            <div class="d-flex gap-2">
                {!! xInputText()->setWire('model.defer', 'forms.filename')->setWidth('standard') !!}
                <button class="btn btn-danger btn-sm" wire:click="remove('{{$widget['key']}}')">삭제</button>

            </div>
            <div>
                <button class="btn btn-secondary btn-sm" wire:click="cencel">취소</button>
                <button class="btn btn-info btn-sm" wire:click="update">변경</button>
            </div>
        </x-flex-between>

        @else
        {{-- 편집 모드 --}}
        <div wire:click="modify" class="hover:bg-gray-100">
            {!! clean($content) !!}
        </div>
        @endif
    @endif




    <!-- 팝업 데이터 수정창 -->
    @if ($widgetPopupForm)
    <x-wire-dialog-modal wire:model="widgetPopupForm" maxWidth="3xl">
        <x-slot name="title">
            {{ __('위젯 정보 수정') }}
        </x-slot>

        <x-slot name="content">
            @if($setup)
                @includeIf("jiny-widgets::widgets.layout_form")
            @else
                @includeIf($viewForm)
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-flex-between>
                <div>
                </div>
                <div> {{-- right --}}
                    <button type="button" class="btn btn-secondary"
                        wire:click="widgetSettingClose">취소</button>
                    <button type="button" class="btn btn-info"
                        wire:click="widgetSettingUpdate">수정</button>
                </div>
            </x-flex-between>
        </x-slot>
    </x-wire-dialog-modal>
    @endif

</article>
