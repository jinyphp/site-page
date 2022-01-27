{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>








        <br>
        <style>
            section.element {
                /*position: relative; */

                --section-select-hover : #6990F2;
            }

            section.element:hover:before {
                content: "";
                position: absolute;
                top:0; left:0;
                width:100%; height: 1px;
                background-color: var(--section-select-hover);
            }

            section.element:hover:after {
                content: "";
                position: absolute;
                bottom:0; left:0;
                width:100%; height: 1px;
                background-color: var(--section-select-hover);
            }

            section.element .inner {
                margin: 0 auto;
                max-width: 95%;
                display: flex;
            }

            section.element .inner .widget {
                flex-grow: 1;
                order: 1;
            }




            /*
            section.element:hover .inner {
                border-top: 1px solid blue;
                border-bottom: 1px solid blue;
            }
            */

            section.element div {
                z-index:1;
            }

            section.element .el-setting {
                position: absolute;
                top:0;right:0;
                z-index:2;

                background-color: #fff;
                border-radius: 50%;
            }



            section.element:hover {
                /* border: 1px dotted #ccc; */
                background: #fff;
            }


            section.element.dragging{
                background: #ccc;
            }
        </style>

        <form>
            @if (is_array($slot))
                {{-- 여러개의 마크다운 파일을 출력합니다. --}}
                @foreach ($slot as $i => $item)
                    <section class="mb-4 element" data-pos="{{$i}}" data-path="{{$item->path}}" data-id="{{$item->id}}">

                        <span class="el-setting">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>


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
                    </section>
                @endforeach
            @else
                {!! $slot !!}
            @endif
        </form>

        <!-- page drag 위치 이동 -->
        <script>
            /*
            window.addEventListener('click', function(e){
                // 배경 클릭시 contextMenu 닫기
                if(jiny.contextMenu) {
                    console.log("contextMenu 제거");
                    jiny.contextMenu.remove();
                }
            });
            */

            const section = document.querySelectorAll('section.element');
            let dragStart, dragWidget;
            let dragX, dragY;
            section.forEach(el=>{
                /*
                el.querySelector('.el-setting').addEventListener('contextmenu', function(e){
                    e.preventDefault();
                    console.log('right click');

                });
                */
                el.querySelector('.el-setting').addEventListener('click', function(e){
                    e.preventDefault();
                    console.log('delete click');

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "/api/pages/delete");
                    let data = new FormData();
                    data.append('_token', token);

                    let target = e.target;
                    while(!target.classList.contains('element')) {
                        target = target.parentElement;
                    }
                    console.log(target);
                    data.append('path', target.dataset['path']);
                    data.append('id', target.dataset['id']);

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

                });

                el.setAttribute('draggable',"true");


                // 드래그 동작
                el.addEventListener('dragstart', (e) => {
                    // dragstart
                    console.log("dragstart");
                    console.log(e.target);

                    let target = e.target;
                    while(!target.classList.contains('element')) {
                        target = target.parentElement;

                        console.log(target);
                        if(target.classList.contains('widget')) {
                            dragWidget = target;
                        }
                    }

                    target.classList.add('dragging');
                    dragStart = target;
                    console.log(dragStart);
                    console.log(dragWidet);
                });


                let dragPosition; //드래그할 위치 지정
                el.addEventListener('dragover', e => {
                    e.preventDefault();
                    //console.log("section drag over...");

                    let target = e.target;
                    while(!target.classList.contains('element')) {
                        target = target.parentElement;
                    }

                    let inner = target.querySelector(".inner");

                    if(dragStart != target) {
                        //console.log("offsetHeight="+target.offsetHeight);
                        //console.log("offsetY="+e.offsetY);

                        if( e.offsetY > (target.offsetHeight - 10 ) ) {
                            if(dragPosition != "bottom") {
                                target.style = "border-bottom: 2px solid #000";
                                dragPosition = "bottom";
                                //dragX = e.clientX; dragY = e.clientY;
                                inner.style = "";
                            }
                        }

                        if( e.offsetY < 10 ) {
                            if(dragPosition != "top") {
                                target.style = "border-top: 2px solid #000";
                                dragPosition = "top";
                                //dragX = e.clientX; dragY = e.clientY;
                                inner.style = "";
                            }
                        }

                        if( e.offsetX > (target.offsetWidth - 10) ) {
                            if(dragPosition != "right") {
                                target.style = "border-right: 2px solid #000";
                                dragPosition = "right";
                                //dragX = e.clientX; dragY = e.clientY;
                                ///inner.style = "border-right: 2px solid #000";
                                ///target.style = "";
                            }

                        }

                        /*
                        let xr = target.offsetWidth - inner.offsetWidth;
                        if(xr) xr = xr/2;
                        xr = xr+10; // 10px
                        */
                        if( e.offsetX < 10 ) {
                        //if( e.offsetX < xr ) {
                            if(dragPosition != "left") {
                                target.style = "border-left: 2px solid #000;";
                                dragPosition = "left";
                                //dragX = e.clientX; dragY = e.clientY;
                                ///inner.style = "border-left: 2px solid #000";
                                ///target.style = "";
                            }
                        }

                        /*
                        //console.log(e.target);

                        //console.log("x="+e.clientX+", y="+e.clientY);
                        let move_x = Math.abs(dragX - e.clientX);
                        let move_y = Math.abs(dragY - e.clientY);
                        //console.log("(x)="+move_x+", (y)="+move_y);

                        if(move_x > move_y) {
                            console.log("x축 이동");
                            if(dragX > e.clientX) {
                                console.log("<<< 왼쪽이동");

                            } else {
                                console.log("오른쪽이동 >>>")
                            }

                        } else {
                            console.log("y축 이동");

                            if(dragY > e.clientY) {
                                console.log("^^^ 위쪽 이동");
                                console.log(e.target)
                                target.style = "border-top: 2px solid #000";
                            } else {
                                console.log("... 아래 이동");
                                target.style = "border-bottom: 2px solid #000";
                            }
                        }
                        */
                    } else {
                        // 시작과 동일한 객체는 제외...
                    }

                });
                el.addEventListener('dragenter', e => {
                    e.preventDefault();
                    //dragX = e.clientX; dragY = e.clientY;

                });
                el.addEventListener('dragleave', e => {
                    e.preventDefault();
                });

                el.addEventListener('drop', (e) => {
                    e.preventDefault();
                    console.log('drop');

                    let target = e.target;
                    while(!target.classList.contains('element')) {
                        target = target.parentElement;
                    }

                    // 위치 표시바 삭제
                    target.style = "";

                    let dragTarget = target;
                    if(dragTarget == dragStart) {
                        // 동일한 섹션 선택
                        let widgets = dragStart.querySelectorAll(".widget");
                        console.log(dragStart);
                        console.log(widgets);
                        if(widgets.length > 1) {
                            console.log("위젯 위치 이동");
                            console.log(dragWidget);
                        } else {
                            console.log("단일 위젯은 이동이 불가능 합니다.");
                        }

                    } else {
                        // 다른 섹션 선택
                        if(dragPosition == "top") {
                            // 섹션 위로 자리 이동
                            targetNext = dragTarget.nextElementSibling;
                            target.parentElement.insertBefore(dragStart, dragTarget);

                        } else if(dragPosition == "bottom") {
                            // 섹션 아래 자리로 이동
                            targetNext = dragTarget.nextElementSibling;
                            target.parentElement.insertBefore(dragStart, targetNext);

                        } else if(dragPosition == "right") {
                            let targetWidget = target.querySelector('.inner');
                            console.log(dragStart);
                            let srcWidget = dragStart.querySelector('.inner .widget');
                            console.log(srcWidget);

                            console.log(targetWidget.firstChild);

                            targetWidget.appendChild(srcWidget);
                            console.log("widget 뒤에 추가");
                        } else if(dragPosition == "left") {

                            let targetWidget = target.querySelector('.inner');
                            console.log(dragStart);
                            let srcWidget = dragStart.querySelector('.inner .widget');
                            console.log(srcWidget);

                            console.log(targetWidget.firstChild);

                            targetWidget.insertBefore(srcWidget, targetWidget.firstChild);
                            console.log("widget 앞에 추가");

                        }
                    }




/*
                    console.log(target);
                    let dragTarget = target;
                    console.log("start=" + dragStart.dataset['pos'] + ", target= " +dragTarget.dataset['pos'])
                    if(dragStart.dataset['pos'] < dragTarget.dataset['pos']) {
                        console.log("아래로 내리기");
                        targetNext = dragTarget.nextElementSibling;
                        target.parentElement.insertBefore(dragStart, targetNext);
                    } else {
                        console.log("위로 올리기");
                        targetNext = dragTarget.nextElementSibling;
                        target.parentElement.insertBefore(dragStart, dragTarget);
                    }



                    // 변경된 pos 정보를 저장
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "/api/pages/pos");
                    let data = new FormData();
                    data.append('_token', token);

                    let i=1;
                    target.parentElement.querySelectorAll('section.element').forEach(element=>{
                        element.dataset['pos'] = i;
                        data.append("pos[" + element.dataset['id'] + "]", i);
                        i++;
                    });
                    console.log(target.parentElement);


                    xhr.onload = function() {
                        var data = JSON.parse(this.responseText);
                        console.log(data);

                        // 페이지 갱신
                        //location.reload();

                        //console.log("테이블 갱신요청");
                        // 라이브와이어 테이블 갱신
                        //Livewire.emit('refeshTable');
                    }

                    xhr.send(data);
                    */




                });

                el.addEventListener('dragend', (e) => {
                    e.preventDefault();
                    let target = e.target;
                    target.classList.remove('dragging');
                })

            });



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



        <script>
            //let token = document.querySelector('input[name=_token]').value;
            // 키 이벤트
            document.addEventListener('keydown', function(e){
                e.preventDefault();
                //&& e.shiftKey)
                if(e.key.toLowerCase() === 'a' && e.ctrlKey ) {
                    console.log(e);
                    alert("admin mode");
                }
            });
        </script>


        {{-- Admin Rule Setting --}}
        @include('jinypage::setMarkRule')

    </x-theme-layout>
</x-theme>
