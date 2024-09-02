<div class="p-4 bg-white">
    <form action="/api/pages/ui/Widget" method="POST">
        @csrf
        @method('post')
        <input type="hidden" name="_id" value="{{$id}}">

        @include('jiny-site-page::ui.widget.markdown')

        <div class="flex justify-between pt-4">
            <div></div>
            <div class="text-right">
                <x-button class="btn-modal-close" secondary wire:click="sectionClose">닫기</x-button>
                <x-button type="submit" primary wire:click="sectionUpdate">수정</x-button>
            </div>
        </div>
    </form>
</div>
