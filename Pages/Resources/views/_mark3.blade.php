{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>
        <br>
        {{-- loading Spinner --}}
        <style>
            .lds-dual-ring {
                display: inline-block;
                width: 80px;
                height: 80px;
            }
            .lds-dual-ring:after {
                content: " ";
                display: block;
                width: 64px;
                height: 64px;
                margin: 8px;
                border-radius: 50%;
                border: 6px solid #fff;
                border-color: #fff transparent #fff transparent;
                animation: lds-dual-ring 1.2s linear infinite;
            }
            @keyframes lds-dual-ring {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }

        </style>

        <style>
            /* Section */
            #widgets section.element {
                /*padding: 5px;*/
                /* background-color: #f9cc9d; */
                /*margin-bottom: 5px;*/

                position: relative;
                border-left:2px solid #ccc;
                min-height: 20px;
            }

            #widgets section.element .section-grap {
                position: absolute;
                top:0; left:-16px;
            }

            #widgets section.element:hover {
                background-color: #f9cc9d;
                border: 1px solid #f9cc9d;
            }




            /*
            #widgets section.element.selected {
                border: 1px solid red;
                cursor: move;
            }
            */





            #widgets section.element.dragging-target {
                background-color: #fddd9b;
            }

            /*
            #widgets section.element .inner {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
            }
            */



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

            /*
            function findSectionElement(target) {
                while(1) {
                    if(target.tagName == "SECTION" && target.classList.contains('element')) break;
                    if(target.tagName == "MAIN") break;
                    target = target.parentElement;
                }

                if(target.tagName == "SECTION" && target.classList.contains('element')) {
                    return target;
                }

                return null;
            }


            let sections = dragWidgets.querySelectorAll('section.element');
            let sectionSelected;
            sections.forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();

                    let target = e.target;
                    target = findSectionElement(target);
                    if(target) {
                        console.log(target);

                        if(sectionSelected) {
                            //console.log("선택해제");
                            //console.log(widgets.selected);
                            sectionSelected.classList.remove('selected');


                        }

                        // 선택 재지정
                        target.classList.add('selected');
                        sectionSelected = target;

                        // 사이드판넬 설정값 표시
                        console.log(target);
                        let url = "/api/pages/pannel/section/" + target.dataset['id'];
                        ajaxGet(url, function(data){
                            offSideRight.innerHTML = data;
                            let form = offSideRight.querySelector('form');

                            ajaxSubmit(form, function(json){
                                console.log(json);
                            });
                        });


                    }


                });
            });

            */



            let widgets = dragWidgets.querySelectorAll('.widget');
            widgets.forEach(el => {
                // 위젯 선텍
                el.addEventListener('click', widgetResizeClickEvent);
            });

            function widgetResizeClickEvent(e) {
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

                        // 사이드판넬 설정값 표시

                        console.log(target);
                        let url = "/api/pages/pannel/section/" + target.dataset['id'];
                        ajaxGet(url, function(data){
                            offSideRight.innerHTML = data;
                            let form = offSideRight.querySelector('form');

                            ajaxSubmit(form, function(json){
                                console.log(json);
                            });
                        });




                    }
            }


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

                        // resize 정보 저장
                        console.log("resize 저장");
                        console.log(e.target.parentElement);

                        let targetElement = e.target.parentElement;

                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "/api/pages/resize");
                        let data = new FormData();
                        data.append('_token', token);

                        data.append('id', targetElement.dataset['id']);
                        data.append('width', targetElement.style.width);
                        data.append('height', targetElement.style.height);

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

        <!-- widget Popup 수정 -->
        <!-- Include stylesheet -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        @livewire('PageContextPopup')

        <!-- Include the Quill library -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

        <!-- Initialize Quill editor -->
        <script>
            /*
            window.addEventListener('DOMContentLoaded', function(){
                var quill = new Quill('#editor', {
                    theme: 'snow'
                });
            });
            */
        </script>





        <!-- context Menu -->
        @push("scripts")
        <script>
            // 섹션, 위젯 설정 contextMenu
            window.addEventListener('DOMContentLoaded',function(e){
                // page 작업영억에 contextMenu 추가...
                _contextMenu(dragWidgets, function(e){
                    let target = e.target;

                    // 구성요소 타입 검출
                    let section, widget;
                    while(1) {
                        if(target.classList.contains('element') && target.tagName == "SECTION") {
                            section = target;
                            break;
                        }

                        if(target.classList.contains('element') && target.tagName == "ARTICLE") {
                            widget = target;
                            // break;
                        }

                        if(target.tagName == "MAIN") return;
                        target = target.parentElement;
                    }

                    // context에 추가할 ul 목록 생성
                    let menu = document.createElement("ul");
                    menu.classList.add('context-menu');

                    // 위젯 삭제
                    console.log("생성");
                    if(widget) {
                        console.log(widget);
                        menu.appendChild( widgetDelete(widget) );
                        menu.appendChild( setWidget(widget) );

                        menu.appendChild( setPopupWidget(widget) );
                    }

                    menu.appendChild( setSection(section) );
                    return menu;
                });

                function widgetDelete(widget) {
                    //let li, link;
                    let li = document.createElement("li");
                    let link = document.createElement("a");
                    link.innerHTML = "삭제";
                    //link.href = "/apiadmin/easy/menu/"+menu_id+"/items/create?ref=" + id;
                    li.appendChild(link);



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
                        let id = widget.dataset['id'];
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

                    return li;
                }

                function setWidget(widget) {

                    let li = document.createElement("li");
                    let link = document.createElement("button");
                    let id = widget.dataset['id'];

                    link.innerHTML = id + "위젯 수정";
                    //link.href = "#";
                    link.setAttribute('wire:click', "$emit('sectionOpen')");
                    link.setAttribute('id',"btn-livepopup-create");

                    link.addEventListener("click",function(e){
                        e.preventDefault();
                        Livewire.emit('sectionOpen', id);
                    });


                    li.appendChild(link);
                    return li;
                }

                function setPopupWidget(widget) {

                    let li = document.createElement("li");
                    let link = document.createElement("button");
                    let id = widget.dataset['id'];

                    link.innerHTML = id + "팝업 위젯 수정";
                    //link.href = "#";
                    link.setAttribute('href',"javascript: void(0);");
                    //link.setAttribute('wire:click', "$emit('sectionOpen')");
                    //link.setAttribute('id',"btn-livepopup-create");

                    link.addEventListener("click",function(e){
                        e.preventDefault();
                        window.pageContextMenu.remove();
                        // Livewire.emit('sectionOpen', id);
                        _modal('/api/pages/ui/widget/'+id);
                    });


                    li.appendChild(link);
                    return li;
                }



                // context: 섹션 수정버튼
                function setSection(section) {

                    let link = document.createElement("a");
                    let id = section.dataset['id'];
                    link.innerHTML = id + "섹션 설정";
                    link.setAttribute('href',"javascript: void(0);");

                    link.addEventListener("click",function(e){
                        e.preventDefault();
                        // 사이드판넬 설정값 표시
                        let url = "/api/pages/pannel/section/" + id;
                        ajaxGet(url, function(data){
                            offSideRight.innerHTML = data;
                            let form = offSideRight.querySelector('form');

                            ajaxSubmit(form, function(json){
                                console.log(json);
                            });
                        });
                    });

                    let li = document.createElement("li");
                    li.appendChild(link);
                    return li;
                }

                //
            });
        </script>
        @endpush


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
            let templateWidget;
            dragWidgets.addEventListener('dragstart', (e) => {
                console.log("drag start...");
                //console.log(e.target);
                if(e.target.classList.contains('template')) {
                    console.log("템플릿 선택");
                    dragStart = e.target.cloneNode(true);
                    dragStart.classList.remove('template'); // 템플릿 중복 복사 방지
                    dragStart.addEventListener('click', widgetResizeClickEvent);
                    dragSelect = "widget";
                    templateWidget = dragStart;
                    return;
                }


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

                    if(templateWidget) {
                        templateWidget = null; //초기화
                        data.append("id", 0); // 새로입력
                        data.append("type", dragStart.dataset['type']);
                    } else {
                        data.append("id", dragStart.dataset['id']);
                    }

                    data.append("ref", dragStart.dataset['ref']);
                    data.append("level", dragStart.dataset['level']);
                    data.append("pos", dragStart.dataset['pos']);
                    data.append('_uri', location.href);




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
        @include('pages::setMarkRule')



        <div >
            <article class="widget element template" data-type="html" draggable="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-body-text" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M0 .5A.5.5 0 0 1 .5 0h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 0 .5Zm0 2A.5.5 0 0 1 .5 2h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5Zm9 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm-9 2A.5.5 0 0 1 .5 4h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Zm5 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm7 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Zm-12 2A.5.5 0 0 1 .5 6h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5Zm8 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm-8 2A.5.5 0 0 1 .5 8h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm7 0a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5Zm-7 2a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5Zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5Zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Z"/>
                </svg>

                Html
            </article>

            <article class="widget element template" data-type="markdown" draggable="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                    <path d="M8.39 12.648a1.32 1.32 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1.06 1.06 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.512.512 0 0 0-.523-.516.539.539 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532 0 .312.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531 0 .313.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242l-.515 2.492zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z"/>
                </svg>
                Markdown
            </article>

            <article class="widget element template" data-type="image" draggable="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"/>
                </svg>
                Image
            </article>

            <article class="widget element template" data-type="blade" draggable="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-code-slash" viewBox="0 0 16 16">
                    <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294l4-13zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0zm6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0z"/>
                </svg>
                Blade
            </article>

            <article class="widget element template" data-type="video" draggable="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right" viewBox="0 0 16 16">
                    <path d="M6 12.796V3.204L11.481 8 6 12.796zm.659.753 5.48-4.796a1 1 0 0 0 0-1.506L6.66 2.451C6.011 1.885 5 2.345 5 3.204v9.592a1 1 0 0 0 1.659.753z"/>
                </svg>
                Video
            </article>

            <article class="widget element template" data-type="calender" draggable="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>
                Calender
            </article>

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bounding-box-circles" viewBox="0 0 16 16">
                <path d="M2 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zM0 2a2 2 0 0 1 3.937-.5h8.126A2 2 0 1 1 14.5 3.937v8.126a2 2 0 1 1-2.437 2.437H3.937A2 2 0 1 1 1.5 12.063V3.937A2 2 0 0 1 0 2zm2.5 1.937v8.126c.703.18 1.256.734 1.437 1.437h8.126a2.004 2.004 0 0 1 1.437-1.437V3.937A2.004 2.004 0 0 1 12.063 2.5H3.937A2.004 2.004 0 0 1 2.5 3.937zM14 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zM2 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
            </svg>


            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.854 3.646a.5.5 0 0 1 0 .708L8.207 8l3.647 3.646a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708 0zM4.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4.146 3.646a.5.5 0 0 0 0 .708L7.793 8l-3.647 3.646a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708 0zM11.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M3.646 4.146a.5.5 0 0 1 .708 0L8 7.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zM1 11.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M3.646 11.854a.5.5 0 0 0 .708 0L8 8.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708zM2.4 5.2c0 .22.18.4.4.4h10.4a.4.4 0 0 0 0-.8H2.8a.4.4 0 0 0-.4.4z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-columns-gap" viewBox="0 0 16 16">
                <path d="M6 1v3H1V1h5zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12v3h-5v-3h5zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8v7H1V8h5zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6v7h-5V1h5zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-columns-gap" viewBox="0 0 16 16">
                <path d="M6 1v3H1V1h5zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12v3h-5v-3h5zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8v7H1V8h5zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6v7h-5V1h5zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid-3x3" viewBox="0 0 16 16">
                <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h13A1.5 1.5 0 0 1 16 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13zM1.5 1a.5.5 0 0 0-.5.5V5h4V1H1.5zM5 6H1v4h4V6zm1 4h4V6H6v4zm-1 1H1v3.5a.5.5 0 0 0 .5.5H5v-4zm1 0v4h4v-4H6zm5 0v4h3.5a.5.5 0 0 0 .5-.5V11h-4zm0-1h4V6h-4v4zm0-5h4V1.5a.5.5 0 0 0-.5-.5H11v4zm-1 0V1H6v4h4z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-input-cursor" viewBox="0 0 16 16">
                <path d="M10 5h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4v1h4a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-4v1zM6 5V4H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v-1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h4z"/>
                <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13A.5.5 0 0 1 8 1z"/>
              </svg>

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
              </svg>

        </div>
        <script>
            /*
            let templates = document.querySelectorAll(".article.template");
            templates.forEach(el=>{

                el.addEventListener('dragstart', function(e){
                    console.log(e.target);
                });
                el.addEventListener('dragenter', function(e){

                });
                el.addEventListener('dragover', function(e){
                    e.preventDefault();
                });
                el.addEventListener('dragleave', function(e){

                });
                el.addEventListener('drop', function(e){
                    e.preventDefault();
                });
                el.addEventListener('dragend', function(e){

                });
            });
            */
        </script>

        <!-- 사이드 패널 -->
        <script>
            /*
            let offSideRight = document.createElement('div');
            offSideRight.classList.add('off-side-right');
            offSideRight.style.width = "300px";

            document.querySelector('.wrapper').appendChild( offSideRight );
            */


            function ajaxGet(url, callback) {
                // ajax 데이터 호출
                fetch(url, {
                    method: 'get'
                })
                .then(response => {
                    return response.text();
                })
                .then(data => {
                    callback(data);
                });
            }

            function ajaxSubmit(form, callback) {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    console.log('ajax submit');

                    let url = form.action;
                    console.log("url=" + url);

                    let formData = new FormData(form);
                    let searchParams = new URLSearchParams();
                    for(let pair of formData) {
                        searchParams.append(pair[0], pair[1]);
                        console.log("key=" + pair[0] + " , value=" + pair[1]);
                    }

                    fetch(url, {
                        method:'post',
                        body: searchParams
                    }).then(function(response){
                        return response.json();

                    }).then(function(json){
                        callback(json);
                        //console.log(json);
                        //Livewire.emit('refeshTable'); // 라이브와이어 테이블 갱신
                        //modals.pop().remove(); //모달 제거

                    }).catch(function(error){
                        //console.log(error);
                    });
                });
            }


            /*
            let url = "/api/pages/pannel/section";
            ajaxGet(url, function(data){
                offSideRight.innerHTML = data;
                let form = offSideRight.querySelector('form');

                ajaxSubmit(form, function(json){
                    console.log(json);
                });
            });
            */



        </script>

    </x-theme-layout>
</x-theme>
