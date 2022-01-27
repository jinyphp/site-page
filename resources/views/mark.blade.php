{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>
        <br>

        <style>
            /* Section */
            #widgets section.element {
                padding: 5px;
                background-color: #f9cc9d;
                margin-bottom: 5px;
            }

            #widgets section.element:hover {
                border: 2px solid #f9cc9d;
            }

            #widgets section.element.dragging-target {
                background-color: #fddd9b;
            }

            #widgets section.element .inner {
                display: flex;
            }



            /* Article 위젯*/
            #widgets article.element {
                /*flex-grow: 1;*/
            }

            #widgets article.element:hover {
                border: 2px solid #8cb6c0;
            }

            #widgets article.element.selected {
                position:relative;
                border: 1px solid #116dff;
                cursor: move;
            }

            #widgets .resizer {
                position: absolute;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                border: 1px solid #116dff;
                background-color: #fff;
                z-index: 2;
            }

            #widgets .resizer.nw {
                top: -4px;
                left: -4px;
                cursor: nw-resize;
            }

            #widgets .resizer.ne {
                top: -4px;
                right: -4px;
                cursor: ne-resize;
            }

            #widgets .resizer.sw {
                bottom: -4px;
                left: -4px;
                cursor: sw-resize;
            }

            #widgets .resizer.se {
                bottom: -4px;
                right: -4px;
                cursor: se-resize;
            }

            #widgets .resizer.right {
                right: -4px;
                cursor: ew-resize;

                width: 8px;
                height: 16px;
                border-radius: 0;
            }

            #widgets .resizer.bottom {
                bottom: -4px;
                cursor: ns-resize;

                width: 16px;
                height: 8px;
                border-radius: 0;
            }

        </style>

        <form id="widgets">
            @foreach ($pages as $page)
                {!! $page !!}
            @endforeach
        </form>

        <!-- widget 선택 및 사이즈 조정 -->
        <script>
            let token = document.querySelector('input[name=_token]').value;
            const dragWidgets = document.querySelector('main.content');

            window.widget = {
                hover:null,
                selected:null
            }

            function findWidgetElement(target) {
                while(1) {
                    if(target.classList.contains('element')) break;
                    if(target.tagName == "MAIN") break;
                    target = target.parentElement;
                }

                if(target.classList.contains('element')) {
                    return target;
                }

                return null;
            }






            let widgets = dragWidgets.querySelectorAll('.widget');
            widgets.forEach(el => {
                el.addEventListener('click', function(e){
                    e.preventDefault();

                    let target = e.target;
                    target = findWidgetElement(target);
                    if(target) {

                        //console.log(target);

                        // 이전 선택값 해제
                        if(widgets.selected) {
                            //console.log("선택해제");
                            //console.log(widgets.selected);
                            widgets.selected.classList.remove('selected');
                            removeResizer(widgets.selected);

                        }

                        // 선택 재지정
                        target.classList.add('selected');
                        widgets.selected = target;

                        // --- resizer 등록 ---
                        addResizer(target);




                    }

                });
            });


            // 사이즈 조정
            const dragResize = function(element) {

                let resizers = element.querySelectorAll(".resizer");
                let currentResizer; // 선택된 현재 조작점

                for(let resizer of resizers) {
                    resizer.addEventListener('mousedown', mousedown);
                }

                function mousedown(e) {
                    console.log("start Resizing...");
                    e.preventDefault();

                    currentResizer = e.target;
                    isResizing = true;

                    let prevX = e.clientX;
                    let prevY = e.clientY;

                    // 드래그 중에는 잠시 숨김
                    e.target.parentElement.querySelectorAll('.resizer').forEach(el=>{
                        el.style.display = "none";
                    });

                    // 드래그 선택 잠시 중단.
                    e.target.parentElement.setAttribute('draggable',"false");




                    window.addEventListener('mousemove', mousemove);
                    window.addEventListener('mouseup', mouseup);
                    function mousemove(e) {
                        const rect = element.getBoundingClientRect();
                        if(currentResizer.classList.contains('right')) {
                            //currentResizer.style.left = parseInt(element.offsetWidth/2-8) + "px";
                            //console.log(parseInt(element.offsetWidth/2-8));
                            //console.log(currentResizer);


                            element.style.width = rect.width - (prevX - e.clientX) + "px";
                        }
                        else
                        if(currentResizer.classList.contains('bottom')) {
                            /// e.target.style.top = parseInt(element.offsetHeight/2-8) + "px";

                            //element.style.width = rect.width - (prevX - e.clientX) + "px";
                            element.style.height = rect.height - (prevY - e.clientY) + "px";
                        }
                        else
                        if(currentResizer.classList.contains('se')) {
                            element.style.width = rect.width - (prevX - e.clientX) + "px";
                            element.style.height = rect.height - (prevY - e.clientY) + "px";
                        }

                        /*
                        else if(currentResizer.classList.contains('sw')) {
                            element.style.width = rect.width + (prevX - e.clientX) + "px";
                            element.style.height = rect.height - (prevY - e.clientY) + "px";
                            element.style.paddingLeft= rect.left - (prevX - e.clientX) + "px";
                        }
                        */
                        /*
                        else if(currentResizer.classList.contains('ne')) {
                            element.style.width = rect.width - (prevX - e.clientX) + "px";
                            element.style.height = rect.height + (prevY - e.clientY) + "px";
                            element.style.top= rect.top - (prevY - e.clientY) + "px";
                        } else if(currentResizer.classList.contains('nw')) {
                            element.style.width = rect.width + (prevX - e.clientX) + "px";
                            element.style.height = rect.height + (prevY - e.clientY) + "px";
                            element.style.top = rect.top - (prevY - e.clientY) + "px";
                            element.style.left = rect.left - (prevX - e.clientX) + "px";
                        }
                        */

                        prevX = e.clientX;
                        prevY = e.clientY;
                    }
                    function mouseup() {
                        window.removeEventListener('mousemove',mousemove);
                        window.removeEventListener('mouseup',mouseup);
                        isResizing = false;

                        // 다시 보이기
                        e.target.parentElement.querySelectorAll('.resizer').forEach(el=>{
                            el.style.display = "block";
                        });

                        e.target.parentElement.setAttribute('draggable',"true");

                    }
                }
            }

            function addResizer(target) {
                let ne = document.createElement("div");
                ne.classList.add('resizer');
                ne.classList.add('ne');
                target.appendChild(ne);

                let nw = document.createElement("div");
                nw.classList.add('resizer');
                nw.classList.add('nw');
                target.appendChild(nw);

                let sw = document.createElement("div");
                sw.classList.add('resizer');
                sw.classList.add('sw');
                target.appendChild(sw);

                let se = document.createElement("div");
                se.classList.add('resizer');
                se.classList.add('se');
                target.appendChild(se);

                let right = document.createElement("div");
                right.classList.add('resizer');
                right.classList.add('right');
                right.style.top = parseInt(target.offsetHeight/2 - 8) + "px";
                //console.log(right.style.top)
                target.appendChild(right);

                let bottom = document.createElement("div");
                bottom.classList.add('resizer');
                bottom.classList.add('bottom');
                bottom.style.left = target.offsetWidth/2 - 8 + "px";

                target.appendChild(bottom);

                dragResize(target);
            }

            function removeResizer(target) {
                //console.log(target);
                target.querySelectorAll('.resizer').forEach(el=>{
                    el.remove();
                    //console.log("resizer 삭제");
                    //console.log(el);
                });
            }


        </script>

        <!-- context Menu -->
        <script>
            dragWidgets.addEventListener('contextmenu', function(e){
                e.preventDefault();
                console.log('context click');
                let target = e.target;
                target = findWidgetElement(target);
                if(target) {
                    console.log(target);
                }
            });
        </script>


        <!-- widget 드래그 이동 -->
        <script>
            dragWidgets.querySelectorAll('section.element').forEach(el => {
                el.setAttribute('draggable',"true");
            });

            dragWidgets.querySelectorAll('section.element .widget').forEach(el => {
                el.setAttribute('draggable',"true");
            });

            // Pages Drag 이벤트 main 위임
            //const pageEvent = dragWidgets;
            let dragStart, startWidget;
            let dragSelect;
            let dragPosition; //드래그할 위치 지정

            dragWidgets.addEventListener('dragstart', (e) => {
                //console.log("drag start...");
                //console.log(e.target);
                let target = e.target;
                while(1) {
                    if(target.classList.contains('element')
                        && target.tagName == "SECTION"
                    ) break;

                    if(target.classList.contains('element')
                        && target.tagName == "ARTICLE"
                    ) break;

                    //if(target.classList.contains('element')) break;
                    //if(target.classList.contains('widget')) break;
                    if(target.tagName == "FORM") return;
                    if(target.tagName == "MAIN") return;
                    target = target.parentElement;
                }

                if(target.tagName == "SECTION") {
                    dragStart = target;
                    dragSelect = "section";
                    //console.log(dragSelect);
                    console.log("drag start > section");

                    target.classList.add('dragging');

                } else
                if(target.tagName == 'ARTICLE') {
                    dragStart = target;
                    dragSelect = "widget";
                    //console.log(dragSelect);
                    console.log("drag start > widget");

                    target.classList.add('dragging');

                } else {
                    e.preventDefault();
                }
            });

            let dragOver;
            dragWidgets.addEventListener('dragover', e => {
                e.preventDefault();

                // 섹션 이동 위치를 지정합니다.
                if(dragSelect == "section") {
                    console.log('--section--');

                    // 섹션만 선택
                    let target = e.target;
                    while(1) {
                        if(target.classList.contains('element')
                        && target.tagName == "SECTION"
                        ) break;

                        if(target.tagName == "MAIN") return;
                        target = target.parentElement;
                    }

                    // 자기 자신은 제외
                    if(dragStart != target) {

                        if(dragOver && dragOver != target) dragOver.style = "";
                        dragOver = target;

                        if( e.offsetY > (target.offsetHeight /2 ) ) {
                            // 중복설정 배제
                            if(dragPosition != "bottom") {
                                target.style = "border-bottom: 3px solid #116dff";
                                dragPosition = "bottom";
                                return;
                            }
                        }

                        if( e.offsetY < (target.offsetHeight /2 ) ) {
                            // 중복설정 배제
                            if(dragPosition != "top") {
                                target.style = "border-top: 3px solid #116dff";
                                dragPosition = "top";
                                return;
                            }
                        }
                    }


                }


                // widget 대상만 선택가능
                if(dragSelect == "widget") {
                    console.log('--widget--');

                    // 섹션 선택
                    let target = e.target;
                    while(1) {
                        if(target.classList.contains('element')
                        && target.tagName == "ARTICLE"
                        ) break;

                        if(target.tagName == "MAIN") return;
                        target = target.parentElement;
                    }

                    if(dragStart != target) {

                        if(dragOver && dragOver != target) dragOver.style = "";
                        dragOver = target;

                        if( e.offsetX > (target.offsetWidth/2) ) {
                            if(dragPosition != "right") {
                                //dragOver = target;
                                target.style = "border-right: 3px solid #116dff";
                                dragPosition = "right";
                                return;
                            }
                        }

                        if( e.offsetX < (target.offsetWidth/2) ) {
                            if(dragPosition != "left") {
                                //dragOver = target;
                                target.style = "border-left: 3px solid #116dff";
                                dragPosition = "left";
                                return;
                            }
                        }

                    }
                }



            });

            dragWidgets.addEventListener('dragenter', e => {
                e.preventDefault();
            });

            dragWidgets.addEventListener('dragleave', e => {
                e.preventDefault();
                console.log("drag Leave");
                console.log(e.target);


            });

            dragWidgets.addEventListener('drop', (e) => {
                e.preventDefault();
                console.log('drop');

                if(dragSelect == "section") {
                    // 섹션 선택
                    let target = e.target;
                    while(1) {
                        if(target.classList.contains('element')
                        && target.tagName == "SECTION"
                        ) break;

                        if(target.tagName == "MAIN") return;
                        target = target.parentElement;
                    }

                    let dragTarget = target;
                    console.log("dragPosition="+dragPosition);

                    // 위치 표시바 삭제
                    dragTarget.style = "";
                    //dragTarget.classList.remove('dragging-target');

                    // 다른 섹션 선택
                    if(dragPosition == "top") {
                        // 섹션 위로 자리 이동
                        //targetNext = dragTarget.nextElementSibling;
                        dragTarget.parentElement.insertBefore(dragStart, dragTarget);


                    } else if(dragPosition == "bottom") {
                        // 섹션 아래 자리로 이동
                        targetNext = dragTarget.nextElementSibling;
                        dragTarget.parentElement.insertBefore(dragStart, targetNext);
                    }


                    // 섹션 변경 순서를 저장 pos 정보를 저장
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "/api/pages/pos");
                    let data = new FormData();
                    data.append('_token', token);

                    let i=1;
                    dragWidgets.querySelectorAll('section.element').forEach(element=>{
                        element.dataset['pos'] = i;
                        data.append("pos[" + element.dataset['id'] + "]", i);
                        i++;
                    });
                    //console.log(target.parentElement);


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

                    return;
                }


                // 선택소스가 widget일 경우
                if(dragSelect == "widget") {

                    // 섹션 선택
                    let target = e.target;
                    while(1) {
                        if(target.classList.contains('element')
                        && target.tagName == "SECTION"
                        ) break;

                        if(target.classList.contains('element')
                        && target.tagName == "ARTICLE"
                        ) break;

                        if(target.tagName == "MAIN") return;
                        target = target.parentElement;
                    }

                    let dragTarget = target;
                    console.log("dragPosition="+dragPosition);
                    //console.log(target);


                    dragTarget.style = "";

                    // 대상 타켓이 section인 경우
                    if(dragTarget.tagName == "SECTION") {
                        console.log("widget to section");
                        dragStart.setAttribute('data-ref', dragTarget.dataset['id']);
                        dragStart.setAttribute('data-level', parseInt(dragTarget.dataset['level']) + 1 );

                        dragTarget.querySelector('.inner').appendChild(dragStart);

                    } else
                    if(dragTarget.classList.contains('widget')) {
                        dragStart.setAttribute('data-ref', target.parentElement.parentElement.dataset['id']);
                        dragStart.setAttribute('data-level', parseInt(target.parentElement.parentElement.dataset['level']) + 1 );
                        console.log("id=" + target.parentElement.parentElement.dataset['id']);


                        if(dragPosition == "right") {
                            dragStart.dataset['pos'] = parseInt(target.parentElement.parentElement.dataset['pos']) + 1 ;

                            target.parentElement.appendChild(dragStart);
                            console.log("widget 뒤에 추가");
                        } else if(dragPosition == "left") {
                            dragStart.dataset['pos'] = parseInt(target.parentElement.parentElement.dataset['pos']) - 1 ;

                            target.parentElement.insertBefore(dragStart, target.parentElement.firstChild);
                            console.log("widget 앞에 추가");

                        }
                    }


                    // 계층이동
                    // 섹션 변경 순서를 저장 pos 정보를 저장
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "/api/pages/move");
                    let data = new FormData();
                    data.append('_token', token);

                    data.append("id", dragStart.dataset['id']);
                    data.append("ref", dragStart.dataset['ref']);
                    data.append("level", dragStart.dataset['level']);
                    data.append("pos", dragStart.dataset['pos']);




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



                    return;
                }


                //console.log(dragTarget);
                //dragTarget.classList.remove('target');



            });


            dragWidgets.addEventListener('dragend', (e) => {
                e.preventDefault();
                console.log('dragend');
                if(dragOver) dragOver.style = "";
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

        {{-- dropzone --}}
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
                //let token = document.querySelector('input[name=_token]').value;
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
