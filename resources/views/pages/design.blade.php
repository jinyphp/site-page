<div>
    <x-loading-indicator />

    @foreach ($widgets as $i => $widget)
        {{-- 페이지 위젯 추가 --}}
        @if ($design)
            <x-ui-divider>
                <x-click wire:click="create({{ $i }})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                </x-click>
            </x-ui-divider>
        @endif


        {{-- 드레그 드롭을 위하여 패딩 추가 --}}
        @if ($design)
            <section id="widget" data-pos="{{ $i }}" draggable="true" class="p-1 bg-green-100 mb-2">
            @else
                <section id="widget" data-pos="{{ $i }}">
        @endif


        @livewire($widget['element'],[
                'widget' => $widget,
                'widget_id' => $i,
            ],key($i))

        {{-- actions 정보에서 widget 관리 --}}
        @if($design)
        <div class="">
            <x-click class="btn btn-primary btn-sm " wire:click="remove('{{ $widget['key'] }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-trash" viewBox="0 0 16 16">
                    <path
                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                    <path
                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                </svg>
                <span class="pl-2">위젯삭제</span>
            </x-click>

            <x-click class="btn btn-info btn-sm" wire:click="widgetSetLayout('{{ $widget['key'] }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-columns" viewBox="0 0 16 16">
                    <path
                        d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm8.5 0v8H15V2zm0 9v3H15v-3zm-1-9H1v3h6.5zM1 14h6.5V6H1z" />
                </svg>
                <span class="pl-2">위젯설정</span>
            </x-click>

            </div>
        @endif

        </section>
    @endforeach


    @php
        if (isset($i)) {
            $i++;
        } else {
            $i = 0;
        }
    @endphp

    @if ($design)
        <x-ui-divider>
            <x-click wire:click="create('{{ $i }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </x-click>
        </x-ui-divider>
    @endif



    <!-- 팝업창 -->
    @if ($popupForm)
        @includeIf('jiny-site-page::pages.popupWidgets')
    @endif



    {{-- ajax drag move --}}
    <form id="dragmove" style="display: none;">
        @csrf
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const widgets = document.querySelectorAll('#widget');
            console.log(widgets);

            const dragmove = document.getElementById('dragmove');
            if (dragmove) {
                let token = dragmove.querySelector('input[name=_token]').value;
                console.log(token);
            }


            let dragSource;
            let dragTarget;

            widgets.forEach(element => {

                element.addEventListener('dragstart', function(e) {
                    //e.preventDefault();
                    console.log("drag start...");

                    dragSource = e.target;
                    e.target.classList.add("bg-green-200");
                });

                element.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    console.log("drag Over...");

                    let target = e.target;
                    //console.log(target);
                    while (target && target.id !== "widget") {
                        target = target.parentElement;
                    }


                    if (!target.classList.contains("dragover")) {
                        target.classList.add("dragover");
                        target.classList.add("bg-gray-100");

                        dragTarget = target;
                    }
                });

                // element.addEventListener('dragenter', function(e) {
                //     e.preventDefault();
                // });

                element.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    console.log("drag Leave...");

                    let target = e.target;
                    //console.log(target);
                    while (target && target.id !== "widget") {
                        target = target.parentElement;
                    }

                    if (target.classList.contains("dragover")) {
                        target.classList.remove("dragover");
                        target.classList.remove("bg-gray-100");

                        dragTarget = null;
                    }
                });

                element.addEventListener('drop', function(e) {
                    e.preventDefault();
                    console.log('drop');


                    if (dragSource.dataset.pos > dragTarget.dataset.pos) {
                        dragTarget.parentElement.insertBefore(dragSource, dragTarget);

                        let next; // 하위요소를 하나씩 이동
                        next = dragSource.nextElementSibling;
                        while (next && next.id == "widget") {
                            console.log("source next")
                            console.log(next);
                            dragTarget.parentElement.insertBefore(next.nextElementSibling, next);
                            next = next.nextElementSibling;
                        }




                    } else {
                        // 요소이동
                        dragTarget.parentElement.insertBefore(dragSource, dragTarget
                            .nextElementSibling);

                        let prev; // 상위요소를 하나씩 이동
                        prev = dragSource.previousElementSibling;
                        while (prev && prev.id == "widget") {
                            console.log(prev);
                            dragTarget.parentElement.insertBefore(prev, prev
                                .previousElementSibling);
                            prev = prev.previousElementSibling;
                        }
                    }

                    // ajax 통신
                    updateDragPos();

                    //unselectWidget(dragSource);

                });

                element.addEventListener('dragend', function(e) {
                    e.preventDefault();
                    console.log('dragend');

                    e.target.classList.remove("bg-green-200");
                    //unselectWidget(e.target);

                    if (dragTarget) {
                        dragTarget.classList.remove("dragover");
                        dragTarget.classList.remove("bg-gray-100");
                    }

                });
            });


            function updateDragPos() {
                console.log("ajax...");

                // 섹션 변경 순서를 저장 pos 정보를 저장
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/api/pages/drag/pos");
                let data = new FormData();

                data.append('_uri', location.href);

                let token = document.querySelector('input[name=_token]').value;
                data.append('_token', token);
                console.log(token);

                let i = 0;
                const widgetPosition = document.querySelectorAll('#widget');
                widgetPosition.forEach(element => {
                    console.log(element.dataset.pos);
                    data.append("pos[" + i + "]", element.dataset.pos);
                    i++;
                });

                //console.log(data);

                xhr.onload = function() {
                    var data = JSON.parse(this.responseText);
                    console.log(data);

                    // 페이지 갱신
                    //location.reload();
                }

                xhr.send(data);


            }



        });
    </script>


    {{-- 페이지 변경 및 삭제 --}}
    @if ($design)
        @includeIf('jiny-site-page::pages.edit')
    @endif

</div>
