<div class="markdown">
    @if ($admin)
        <div class="flex flex-row justify-end">
            <div class="pr-4">
                전체: {{$transTotal}} / 번역완료 {{$transCount}}
            </div>            
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" wire:model="transMode">
                <label class="form-check-label" for="flexSwitchCheckChecked">관리자 번역</label>
            </div>
        </div>
    @endif      

    {!! $markdown !!}

    {{-- 팝업 번역관리 --}}
    @if ($trans)
        @livewire('LiveTrans',['content'=>$content])
    @endif    
</div>