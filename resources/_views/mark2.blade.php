{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>
        <br>

        <style>
            /*마진 영역을 시각적으로 표시*/
            .desgin section.element {
                padding: 20px;
                margin-bottom: 20px;
                background-color: #f9cc9d;
            }

            .desgin section.element .inner {
                min-height: 20px;
                display: flex;
            }

            .widget {
                background-color: #fff;
                flex-grow: 1;
            }

            .desgin section.element.dragging {
                background-color: #e2f0f6
            }

            .desgin article.widget.dragging {
                background-color: #e2f0f6
            }

            .element.selected {
                box-sizing: border-box;
                border: 1px solid #116dff;
            }


            /*
            section {
                padding: 25px 10px;
                display: flex;
                margin: 10px;
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
            */



        </style>

        <form class="desgin">
            @foreach ($pages as $page)
                {!! $page !!}
            @endforeach
        </form>



        <!-- page drag 위치 이동 -->
        <script>
            let token = document.querySelector('input[name=_token]').value;
            const mainDesign = document.querySelector('main.content');
            /*
            let hover = null;
            mainDesign.addEventListener("mouseover", function( e ) {
                //console.log(e.target);
                let target = e.target;
                while(1) {
                    if(target.classList.contains('element')) break;
                    if(target.tagName == "MAIN") return;
                    target = target.parentElement;
                }

                if(hover != target) {
                    if(hover) {
                        hover.style = ""; //이전 요소 초기화
                    }

                    hover = target;
                    hover.style = "box-sizing: border-box;border: 1px solid #116dff;";
                    console.log(target);
                }
                console.log("mouseover");
            }, false);
            */


            const sections = document.querySelectorAll('section.element');
            sections.forEach(el => {
                el.setAttribute('draggable',"true");

            });


            const widgets = document.querySelectorAll('section.element .widget');
            console.log(widgets);
            widgets.forEach(el => {
                el.setAttribute('draggable',"true");
                console.log(el);

            });

            // Pages Drag 이벤트 main 위임
            const pageEvent = mainDesign; //document.querySelector('main');
            let dragStart, startWidget;
            let dragSelect;
            let dragPosition; //드래그할 위치 지정

            pageEvent.addEventListener('dragstart', (e) => {
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
                    console.log(dragSelect);

                    target.classList.add('dragging');

                } else
                if(target.tagName == 'ARTICLE') {
                    dragStart = target;
                    dragSelect = "widget";
                    console.log(dragSelect);

                    target.classList.add('dragging');

                } else {
                    e.preventDefault();
                }
            });

            let dragOver;
            pageEvent.addEventListener('dragover', e => {
                e.preventDefault();
                if(dragSelect == "section") {
                    console.log('--section--');

                    // 섹션 선택
                    let target = e.target;
                    while(1) {
                        if(target.classList.contains('element')
                        && target.tagName == "SECTION"
                        ) break;

                        if(target.tagName == "MAIN") return;
                        target = target.parentElement;
                    }

                    if(dragStart != target) {
                        if( e.offsetY > (target.offsetHeight /2 ) ) {
                            if(dragPosition != "bottom") {
                                dragOver = target;
                                target.style = "border-bottom: 3px solid #116dff";
                                dragPosition = "bottom";
                                return;
                            }
                        }

                        if( e.offsetY < (target.offsetHeight /2 ) ) {
                            if(dragPosition != "top") {
                                dragOver = target;
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

                        if( e.offsetX > (target.offsetWidth/2) ) {
                            if(dragPosition != "right") {
                                dragOver = target;
                                target.style = "border-right: 3px solid #116dff";
                                dragPosition = "right";
                                return;
                            }
                        }

                        if( e.offsetX < (target.offsetWidth/2) ) {
                            if(dragPosition != "left") {
                                dragOver = target;
                                target.style = "border-left: 3px solid #116dff";
                                dragPosition = "left";
                                return;
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

                /*
                let target = e.target;
                while(1) {
                    if(target.classList.contains('element')) break;
                    if(target.classList.contains('widget')) break;
                    if(target.tagName == "FORM") return;
                    if(target.tagName == "MAIN") return;
                    target = target.parentElement;
                }

                target.classList.remove("target");
                */
            });

            pageEvent.addEventListener('drop', (e) => {
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
                    mainDesign.querySelectorAll('section.element').forEach(element=>{
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
                    console.log(target);


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


            pageEvent.addEventListener('dragend', (e) => {
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


<script>
    let pageContextMenu;
    mainDesign.addEventListener('contextmenu', function(e){
        e.preventDefault();
        console.log('context click');

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

        let contextMenu;
        /*
        if(pageContextMenu) {
            console.log("존재");
            contextMenu = pageContextMenu;
        } else {
            console.log("생성");
            console.log(target.dataset['id']);
            contextMenu = createPageContext(target.dataset['id']);
            pageContextMenu = contextMenu;
        }
        */
        if(pageContextMenu) {
            pageContextMenu.remove();
        }


        console.log("생성");
        console.log(target.dataset['id']);
        contextMenu = createPageContext(target.dataset['id']);
        pageContextMenu = contextMenu;

        let wrapper = document.querySelector(".wrapper");
        wrapper.appendChild(contextMenu);

        // context Menu활성화
        contextMenu.style.display = 'block';
        contextClickPosition(e, contextMenu);


    });

    function createPageContext(id)
    {
        //let menu_id = 10;
        let menu = document.createElement("ul");
        menu.classList.add('context-menu');

        let li, link;
        li = document.createElement("li");
        link = document.createElement("a");
        link.innerHTML = "삭제";
        //link.href = "/apiadmin/easy/menu/"+menu_id+"/items/create?ref=" + id;
        li.appendChild(link);
        menu.appendChild(li);


        link.addEventListener('click', function(e){
            e.preventDefault();
            console.log('delete click');


            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/pages/delete");
            let data = new FormData();
            data.append('_token', token);

            /*
            let target = e.target;
            while(!target.classList.contains('element')) {
                target = target.parentElement;
            }
            console.log(target);
            */
            //data.append('path', target.dataset['path']);
            //data.append('id', target.dataset['id']);
            data.append('id', id);

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

        li = document.createElement("li");
        link = document.createElement("button");
        link.innerHTML = id + "수정";
        //link.href = "#";
        link.setAttribute('wire:click', "$emit('sectionOpen')");
        link.setAttribute('id',"btn-livepopup-create");

        link.addEventListener("click",function(e){
            e.preventDefault();
            Livewire.emit('sectionOpen', id);
        });


        li.appendChild(link);
        menu.appendChild(li);



        /*


        li = document.createElement("li");
        link = document.createElement("a");
        link.innerHTML = "수정";
        link.href = "/admin/easy/menu/" + menu_id + '/items/' + id + "/edit";
        li.appendChild(link);

        menu.appendChild(li);
        */

        return menu;
    }

    function contextClickPosition(e, contextMenu)
    {
        // top위치 구하기
        var top;
        if((e.clientY + contextMenu.offsetHeight)  > window.innerHeight) {
            top = window.innerHeight - contextMenu.offsetHeight ;
        } else {
            top = e.clientY;
        }
        contextMenu.style.top =  top + "px";

        // left 위치 구하기
        var left;
        if((e.clientX + contextMenu.offsetWidth) > window.innerWidth) {
            left = window.innerWidth - contextMenu.offsetWidth ;
        } else {
            left = e.clientX;
        }
        contextMenu.style.left = left + 'px';
    }


    window.addEventListener('click', function(e){
        // 배경 클릭시 contextMenu 닫기
        if(pageContextMenu) {
            console.log("contextMenu 제거");
            pageContextMenu.remove();
        }
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


        {{--
            <button wire:click="$emit('sectionOpen')">수정...</button>
        <x-button id="btn-livepopup-create" primary wire:click="$emit('sectionOpen')">신규추가</x-button>

        <script>
            document.querySelector("#btn-livepopup-create").addEventListener("click",function(e){
                e.preventDefault();
                Livewire.emit('sectionOpen');
            });
        </script>
        --}}


        @livewire('PageContents')






{{-- resizer --}}

<style>
    .item {
        height: 100px;
        width:100px;
        position: absolute;
        background: orange;
    }

    .resizer {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 1px solid #116dff;
        background-color: #fff;
        z-index: 2;
    }

    .resizer.nw {
        top: -2px;
        left: -2px;
        cursor: nw-resize;
    }

    .resizer.ne {
        top: -2px;
        right: -2px;
        cursor: ne-resize;
    }

    .resizer.sw {
        bottom: -2px;
        left: -2px;
        cursor: sw-resize;
    }

    .resizer.se {
        bottom: -2px;
        right: -2px;
        cursor: se-resize;
    }
</style>


<div class="item element">
    {{--
    <div class="resizer ne"></div>
    <div class="resizer nw"></div>
    <div class="resizer sw"></div>
    <div class="resizer se"></div>
    --}}
</div>

<script>








    const el = document.querySelector(".item");
    let isResizing = false;
    el.addEventListener('mousedown', mousedown);
    function mousedown(e) {
        window.addEventListener('mousemove',mousemove);
        window.addEventListener('mouseup',mouseup);

        let prevX = e.clientX;
        let prevY = e.clientY;

        function mousemove(e) {
            if(!isResizing) {
                let newX = prevX - e.clientX;
                let newY = prevY - e.clientY;

                // https://webisfree.com/2020-09-21/[%EC%9E%90%EB%B0%94%EC%8A%A4%ED%81%AC%EB%A6%BD%ED%8A%B8]-%EC%97%98%EB%A6%AC%EB%A8%BC%ED%8A%B8%EC%9D%98-%ED%8E%98%EC%9D%B4%EC%A7%80-%EC%9C%84%EC%B9%98-%EC%95%8C%EC%95%84%EB%82%B4%EA%B8%B0-getboundingclientrect
                const rect = el.getBoundingClientRect(); // 보여지는 화면 기준, 엘리먼트 위치 구하기

                el.style.left = rect.left - newX + "px";
                el.style.top = rect.top - newY + "px";

                prevX = e.clientX;
                prevY = e.clientY;
            }
        }

        function mouseup() {
            window.removeEventListener('mousemove',mousemove);
            window.removeEventListener('mouseup',mouseup);
        }
    }

    // 사이즈 조정
    const dragResize = function(element) {

            // 사이즈이동 조작점
            /*
            let resizers = [];
            let ne = document.createElement("div");
            ne.classList.add('resizer');
            ne.classList.add('ne');

            let nw = document.createElement("div");
            nw.classList.add('resizer');
            nw.classList.add('nw');

            let sw = document.createElement("div");
            sw.classList.add('resizer');
            sw.classList.add('sw');

            let se = document.createElement("div");
            se.classList.add('resizer');
            se.classList.add('se');

            element.appendChild(ne);
            element.appendChild(nw);
            element.appendChild(sw);
            element.appendChild(se);
            */

            /*
            resizers.push(ne);
            resizers.push(nw);
            resizers.push(sw);
            resizers.push(se);

            console.log("resizer");
            console.log(resizers);
            */


            let resizers = element.querySelectorAll(".resizer");
            let currentResizer; // 선택된 현재 조작점

            //console.log(resizers);

            for(let resizer of resizers) {
                resizer.addEventListener('mousedown', mousedown);
            }

            function mousedown(e) {
                currentResizer = e.target;
                isResizing = true;

                let prevX = e.clientX;
                let prevY = e.clientY;

                window.addEventListener('mousemove', mousemove);
                window.addEventListener('mouseup', mouseup);
                function mousemove(e) {
                    const rect = element.getBoundingClientRect();
                    if(currentResizer.classList.contains('se')) {
                        element.style.width = rect.width - (prevX - e.clientX) + "px";
                        element.style.height = rect.height - (prevY - e.clientY) + "px";
                    } else if(currentResizer.classList.contains('sw')) {
                        element.style.width = rect.width + (prevX - e.clientX) + "px";
                        element.style.height = rect.height - (prevY - e.clientY) + "px";
                        element.style.left= rect.left - (prevX - e.clientX) + "px";
                    } else if(currentResizer.classList.contains('ne')) {
                        element.style.width = rect.width - (prevX - e.clientX) + "px";
                        element.style.height = rect.height + (prevY - e.clientY) + "px";
                        element.style.top= rect.top - (prevY - e.clientY) + "px";
                    } else if(currentResizer.classList.contains('nw')) {
                        element.style.width = rect.width + (prevX - e.clientX) + "px";
                        element.style.height = rect.height + (prevY - e.clientY) + "px";
                        element.style.top = rect.top - (prevY - e.clientY) + "px";
                        element.style.left = rect.left - (prevX - e.clientX) + "px";
                    }

                    prevX = e.clientX;
                    prevY = e.clientY;
                }
                function mouseup() {
                    window.removeEventListener('mousemove',mousemove);
                    window.removeEventListener('mouseup',mouseup);
                    isResizing = false;
                }
            }

    }



    window.drag = {
        selected:null
    }
    window.addEventListener('click', function(e){
        //console.log("click window");
        //console.log(drag.selected);

        // drag
        if(drag.selected) {
            let target = e.target;
            while(1) {
                if(target.classList.contains('element')) break;
                if(target.tagName == "MAIN") break;
                target = target.parentElement;
            }

            if(target.classList.contains('element')) {
                //console.log("target=");
                //console.log(target);
                //console.log("selected=");
                //console.log(drag.selected);

                if(drag.selected != target) {
                    target.classList.remove('selected');
                }
            } else {
                drag.selected.classList.remove('selected');
            }

        } else {
            //console.log("not selected");
        }

    });


    const dragSelectBox = function (element) {
        let hover = null;
        element.addEventListener('click', function(e){
            let target = e.target;
            while(1) {
                if(target.classList.contains('element')) break;
                if(target.tagName == "MAIN") return;
                target = target.parentElement;
            }

            target.classList.add('selected');
            drag.selected = target;

            // 사이즈 조절 버튼 삽입
            console.log("--- selected ---");
            console.log(target);

            let ne = document.createElement("div");
            ne.classList.add('resizer');
            ne.classList.add('ne');

            let nw = document.createElement("div");
            nw.classList.add('resizer');
            nw.classList.add('nw');

            let sw = document.createElement("div");
            sw.classList.add('resizer');
            sw.classList.add('sw');

            let se = document.createElement("div");
            se.classList.add('resizer');
            se.classList.add('se');

            target.appendChild(ne);
            target.appendChild(nw);
            target.appendChild(sw);
            target.appendChild(se);


        });

        /*
        element.addEventListener("mouseover", function( e ) {
            //console.log(e.target);
            let target = e.target;
            while(1) {
                if(target.classList.contains('element')) break;
                if(target.tagName == "MAIN") return;
                target = target.parentElement;
            }

            if(hover != target) {
                if(hover) {
                    hover.style = ""; //이전 요소 초기화
                }

                hover = target;
                hover.style = "box-sizing: border-box;border: 1px solid #116dff;";
                console.log(target);
            }
            console.log("mouseover");
        }, false);
        */
    };


    dragSelectBox(el);
    dragResize(el); // 사이즈 조정 설정


    console.log('--- resizer ----');
    const pageElements = document.querySelectorAll(".element");
    console.log(pageElements);

    pageElements.forEach(el => {
        dragSelectBox(el);
        dragResize(el); // 사이즈 조정 설정
    });



</script>


        {{-- Admin Rule Setting --}}
        @include('jiny-site-page::setMarkRule')

    </x-theme-layout>
</x-theme>
