{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>
        <br>
        <style>

            section {
                padding: 25px 10px;
                background: #e5e7eb;
                border-bottom: 1px solid #9ca3af;

                display: flex;
            }

            section.target {
                background-color: #fef3c7;
            }

            section .inner {
                padding: 0;
                background: #ffffff;
                flex-grow: 1;

                min-height: 50px;
                display: flex;

            }

            section .setting {
                width:20px;
            }

            section .close {
                /*
                position: absolute;
                top:2px; right:2px;
                */
                color: #374151;
                background-color: #fff;
                border-radius: 50%;
            }

            section .close:hover {
                color:white;
                background-color: #b91c1c;
            }

            .widget {
                flex-grow: 1;
            }

            .widget.target {
                background-color: #fef3c7;
            }



        </style>

        <form>
            @if (is_array($slot))
                {{-- 여러개의 마크다운 파일을 출력합니다. --}}
                @foreach ($slot as $i => $item)
                    <section class="mb-4 element" data-pos="{{$i}}" data-path="{{$item->path}}" data-id="{{$item->id}}">




                        <div class="inner">
                            <div class="widget">
                                @if ($item->type == "markdown")
                                    <x-markdown>
                                        {!! $item->content !!}
                                    </x-markdown>

                                @elseif ($item->type == "htm")
                                    {!! $item->content !!}

                                @elseif ($item->type == "image")
                                    <img src="/images{{$item->path}}" width="50%" alt="">

                                @elseif ($item->type == "blade")

                                    @include($item->blade)
                                @else

                                @endif
                            </div>
                        </div>

                        <div class="setting">
                            <span class="close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </span>
                        </div>
                    </section>
                @endforeach
            @else
                {!! $slot !!}
            @endif
        </form>

        <!-- page drag 위치 이동 -->
        <script>
            const sections = document.querySelectorAll('section.element');
            sections.forEach(el => {
                el.setAttribute('draggable',"true");
                /*
                el.addEventListener('dragstart', (e) => {

                });
                el.addEventListener('dragover', e => {
                    e.preventDefault();
                });
                el.addEventListener('dragenter', e => {
                    e.preventDefault();
                });
                el.addEventListener('dragleave', e => {
                    e.preventDefault();
                });
                el.addEventListener('drop', (e) => {
                    e.preventDefault();
                    console.log('drop');
                });
                el.addEventListener('dragend', (e) => {
                    e.preventDefault();
                    let target = e.target;
                    target.classList.remove('dragging');
                });
                */

            });


            const widgets = document.querySelectorAll('section.element .widget');
            console.log(widgets);
            widgets.forEach(el => {
                el.setAttribute('draggable',"true");
                console.log(el);

            });

            // Pages Drag 이벤트 main 위임
            const pageEvent = document.querySelector('main');
            let dragStart, startWidget;
            let dragSelect;
            let dragPosition; //드래그할 위치 지정

            pageEvent.addEventListener('dragstart', (e) => {
                let target = e.target;
                while(1) {
                    if(target.classList.contains('element')) break;
                    if(target.classList.contains('widget')) break;
                    if(target.tagName == "FORM") return;
                    if(target.tagName == "MAIN") return;
                    target = target.parentElement;
                }

                if(target.classList.contains('element')) {
                    dragStart = target;
                    dragSelect = "section";
                    console.log(dragSelect);
                } else
                if(target.classList.contains('widget')) {
                    dragStart = target;
                    dragSelect = "widget";
                    console.log(dragSelect);
                } else {
                    e.preventDefault();
                }
            });

            pageEvent.addEventListener('dragover', e => {
                e.preventDefault();
                let target = e.target;
                while(1) {
                    if(target.classList.contains('element')) break;
                    if(target.classList.contains('widget')) break;
                    if(target.tagName == "FORM") return;
                    if(target.tagName == "MAIN") return;
                    target = target.parentElement;
                }

                // section 대상만 선택가능
                if(target.classList.contains('element') && dragSelect == "section") {
                    console.log('--section--');
                    // 자기자신은 선택 불가
                    if(dragStart != target) {

                        target.classList.add("target");

                        if( e.offsetY > (target.offsetHeight - 25 ) ) {
                            if(dragPosition != "bottom") {
                                target.style = "border-bottom: 2px solid #000";
                                dragPosition = "bottom";
                            }
                        }

                        if( e.offsetY < 25 ) {
                            if(dragPosition != "top") {
                                target.style = "border-top: 2px solid #000";
                                dragPosition = "top";
                            }
                        }
                    }
                }

                // widget 대상만 선택가능
                if(target.classList.contains('widget') && dragSelect == "widget") {
                    console.log('--widget--');
                    if(dragStart != target) {

                        target.classList.add("target");

                        if( e.offsetX > (target.offsetWidth - 20) ) {
                            if(dragPosition != "right") {
                                target.style = "border-right: 2px solid #000";
                                dragPosition = "right";
                                //dragX = e.clientX; dragY = e.clientY;
                                ///inner.style = "border-right: 2px solid #000";
                                ///target.style = "";
                            }
                        }

                        if( e.offsetX < 20 ) {
                            if(dragPosition != "left") {
                                target.style = "border-left: 2px solid #000;";
                                dragPosition = "left";
                                //dragX = e.clientX; dragY = e.clientY;
                                ///inner.style = "border-left: 2px solid #000";
                                ///target.style = "";
                            }
                        }

                    }
                }

            });
            pageEvent.addEventListener('dragenter', e => {
                e.preventDefault();
            });
            pageEvent.addEventListener('dragleave', e => {
                e.preventDefault();

                let target = e.target;
                while(1) {
                    if(target.classList.contains('element')) break;
                    if(target.classList.contains('widget')) break;
                    if(target.tagName == "FORM") return;
                    if(target.tagName == "MAIN") return;
                    target = target.parentElement;
                }

                target.classList.remove("target");
            });

            pageEvent.addEventListener('drop', (e) => {
                e.preventDefault();
                console.log('drop');

                let target = e.target;
                while(1) {
                    if(target.classList.contains('element')) break;
                    if(target.classList.contains('widget')) break;
                    if(target.tagName == "FORM") return;
                    if(target.tagName == "MAIN") return;
                    target = target.parentElement;
                }

                let dragTarget = target;
                console.log("dragPosition="+dragPosition);

                if(dragSelect == "section") {
                    // 위치 표시바 삭제
                    target.style = "";

                    // 다른 섹션 선택
                    if(dragPosition == "top") {
                        // 섹션 위로 자리 이동
                        targetNext = dragTarget.nextElementSibling;
                        target.parentElement.insertBefore(dragStart, dragTarget);


                    } else if(dragPosition == "bottom") {
                        // 섹션 아래 자리로 이동
                        targetNext = dragTarget.nextElementSibling;
                        target.parentElement.insertBefore(dragStart, targetNext);
                    }
                }

                // 선택소스가 widget일 경우
                if(dragSelect == "widget") {

                    target.style = "";

                    // 대상 타켓이 section인 경우
                    if(dragTarget.classList.contains('element')) {
                        console.log("widget to section");
                        dragTarget.querySelector('.inner').appendChild(dragStart);

                    } else if(dragTarget.classList.contains('widget')) {
                        if(dragPosition == "right") {
                            console.log(target);
                            target.parentElement.appendChild(dragStart);
                            console.log("widget 뒤에 추가");
                        } else if(dragPosition == "left") {
                            console.log(target);
                            target.parentElement.insertBefore(dragStart, target.parentElement.firstChild);

                            console.log("widget 앞에 추가");

                        }
                    }



                }

                console.log(dragTarget);
                dragTarget.classList.remove('target');

                return;

            });


            pageEvent.addEventListener('dragend', (e) => {
                e.preventDefault();
                console.log('dragend');
                //console.log(e.target);

                let target = e.target;
                target.classList.remove('dragging');

                /*
                target = e.target;
                while(!target.classList.contains('element')) {
                    if(target.tagName == "FORM") return;
                    target = target.parentElement;
                }
                target.classList.remove("target");
                */

                //초기화
                dragStart = null;
                startWidget = null;
                dragPosition = null;
                dragSelect = null;
            })


        </script>



        {{-- 마크다운 추가 dropzone --}}
        <div>
            @push('css')
            <style>
                .dropzone {
                    width: 100%;
                    height: 100px;
                    line-height: 100px;
                    text-align: center;
                    color: #333;
                    border: 2px dashed #bbb;
                    background-color: #ccc;
                }

                .dropzone span {
                    font-size: 1rem;
                    text-align: center;
                    font-weight: 500;
                }

                .dropzone.dragover {
                    border-color:#333;
                    background-color: rgb(240,240,240);
                    color:#000;
                }

                /* onProgress */
                .progress-area .details{
                    display: flex;
                    align-items: center;
                    margin-bottom: 7px;
                    justify-content: space-between;
                }

                .progress-area .progress-bar{
                    height: 6px;
                    width: 100%;
                    margin-bottom: 4px;
                    background: #fff;
                    border-radius: 30px;
                }

                .progress-bar .progress{
                    height: 100%;
                    width: 0%;
                    background: #6990F2;
                    border-radius: inherit;
                }

            </style>
            @endpush

            <form id="dropzone">
                @csrf
                <div class="dropzone">
                    <span>생성할 페이지의 컨덴츠를 여기에 드래그 하세요.</span>
                </div>
                <div class="progress-area"></div>
            </form>

            <script>
                const dropzone = document.querySelectorAll(".dropzone");
                var progressArea = document.querySelector(".progress-area");
                let token = document.querySelector('input[name=_token]').value;
                dropzone.forEach(el => {

                    el.addEventListener('drop', function(e){
                        e.preventDefault();

                        let target = e.target;
                        while(!target.classList.contains("dropzone")) {
                            target = target.parentElement;
                        }
                        target.classList.remove("dragover");

                        console.log("drop file");
                        var files = e.dataTransfer.files;
                        for(let i=0; i < e.dataTransfer.files.length; i++) {
                            uploadFile(e.dataTransfer.files[i]);
                            //console.log(e.dataTransfer.files[i]);
                        }
                    });

                    el.addEventListener('dragover', function(e){
                        e.preventDefault();

                        let target = e.target;
                        while(!target.classList.contains("dropzone")) {
                            target = target.parentElement;
                        }
                        target.classList.add("dragover");


                        console.log("drag over...");
                    });



                    el.addEventListener('dragleave', function(e){
                        e.preventDefault();
                        let target = e.target;
                        while(!target.classList.contains("dropzone")) {
                            target = target.parentElement;
                        }
                        target.classList.remove("dragover");
                    });
                });

                function uploadFile(file) {
                    var name = file.name;

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "/api/upload/404");

                    let data = new FormData();
                    data.append('file[]', file);
                    data.append('_token', token);
                    data.append('_uri', location.href);

                    /*
                    if (path) {
                        data.append('path', path);
                    }
                    */

                    xhr.upload.addEventListener("progress", ({loaded, total}) =>{
                        let fileLoaded = Math.floor((loaded / total) * 100);
                        let fileTotal = Math.floor(total / 1000);
                        let fileSize;
                        (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024*1024)).toFixed(2) + " MB";

                        console.log(name + "=" + fileSize);


                        let progressHTML = `<div class="details">
                                <span class="name">` + name + `</span>
                                <span class="percent">` +  " : " +fileLoaded + `%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress" style="width: `+fileLoaded +`%"></div>
                            </div>`;
                        progressArea.innerHTML = progressHTML;
                    });

                    xhr.onload = function() {
                        var data = JSON.parse(this.responseText);
                        console.log(data);

                        // 페이지 갱신
                        location.reload();

                        //console.log("테이블 갱신요청");
                        // 라이브와이어 테이블 갱신
                        //Livewire.emit('refeshTable');
                    }

                    xhr.send(data);
                }

            </script>
        </div>



        {{-- Admin Rule Setting --}}
        @include('jinypage::setMarkRule')

    </x-theme-layout>
</x-theme>
