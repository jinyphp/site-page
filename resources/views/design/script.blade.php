<!-- 요소 선택 스타일 -->
<style>
    #widgets .element.hovered {
        position:relative;
        border: 1px solid #116dff;
        cursor: move;
        margin:3px;
    }

    #widgets section.element.hovered::before {
        content: "Section";
        position: absolute;
        top:0; left:0;
        background-color: #116dff;
        color:white;
        font-size:0.5rem;
        padding: 2px 4px;
    }

    #widgets section.element .inner {
        min-height: 30px;
    }

    #widgets article.element.hovered::before {
        content: "Article";
        position: absolute;
        top:0; left:0;
        background-color: #116dff;
        color:white;
        font-size:0.5rem;
        padding: 2px 4px;
    }
</style>

{{--resizer --}}
<style>
    #widgets .resizer {
        position: absolute;
        z-index: 3;
    }

    #widgets .resizer.nw {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 1px solid #116dff;
        background-color: #fff;

        top: -4px; left: -4px;
        cursor: nw-resize;
    }

    #widgets .resizer.ne {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 1px solid #116dff;
        background-color: #fff;

        top: -4px; right: -4px;
        cursor: ne-resize;
    }

    #widgets .resizer.sw {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 1px solid #116dff;
        background-color: #fff;

        bottom: -4px; left: -4px;
        cursor: sw-resize;
    }

    #widgets .resizer.se {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        border: 1px solid #116dff;
        background-color: #fff;

        bottom: -4px; right: -4px;
        cursor: se-resize;
    }


    /*margin*/
    #widgets .resizer.margin-top {
        width: 10px;
        height: 4px;

        top: -5px;
        left: 10px;
        cursor: nw-resize;
        border: 1px solid #f9cc9d;
        background-color: #f9cc9d;
    }

    #widgets .resizer.margin-top-text {
        top: -14px;
        left: 10px;

        font-size: 0.5rem;
        vertical-align: text-bottom;
        color: #116dff;
        border-left:1px dotted #116dff;
    }

    #widgets .resizer.margin-right {
        width: 4px;
        height: 10px;

        top: 15px;
        right: -5px;
        cursor: ne-resize;
        border: 1px solid #f9cc9d;
        background-color: #f9cc9d;
    }

    #widgets .resizer.margin-right-text {
        top: 7px;
        right: -13px;

        font-size: 0.5rem;
        color: #116dff;
        border-bottom:1px dotted #116dff;
    }

    #widgets .resizer.margin-left {
        width: 4px;
        height: 10px;

        bottom: 10px;
        left: -5px;
        cursor: sw-resize;
        border: 1px solid #f9cc9d;
        background-color: #f9cc9d;
    }

    #widgets .resizer.margin-left-text {
        bottom: 10px;
        left: -13px;

        font-size: 0.5rem;
        color: #116dff;
        border-bottom:1px dotted #116dff;
    }

    #widgets .resizer.margin-bottom {
        width: 10px;
        height: 4px;

        bottom: -5px;
        right: 10px;
        cursor: se-resize;
        border: 1px solid #f9cc9d;
        background-color: #f9cc9d;
    }

    #widgets .resizer.margin-bottom-text {
        bottom: -14px;
        right: 10px;

        font-size: 0.5rem;
        vertical-align: text-bottom;
        color: #116dff;
        border-right:1px dotted #116dff;
    }

    /*padding*/
    #widgets .resizer.padding-top {
        width: 10px;
        height: 4px;

        top: 0px;
        right: 10px;
        cursor: nw-resize;
        border: 1px solid #c3d08b;
        background-color: #c3d08b;
    }

    #widgets .resizer.padding-top-text {
        top: -14px;
        right: 10px;

        font-size: 0.5rem;
        vertical-align: text-bottom;
        color: #116dff;
        border-right:1px dotted #116dff;
    }

    #widgets .resizer.padding-right {
        width: 4px;
        height: 10px;

        bottom: 15px;
        right: 0px;
        cursor: ne-resize;
        border: 1px solid #c3d08b;
        background-color: #c3d08b;
    }

    #widgets .resizer.padding-right-text {
        bottom: 15px;
        right: 0px;

        font-size: 0.5rem;
        color: #116dff;
        border-bottom:1px dotted #116dff;
    }

    #widgets .resizer.padding-left {
        width: 4px;
        height: 10px;

        top: 20px;
        left: 0px;
        cursor: sw-resize;
        border: 1px solid #c3d08b;
        background-color: #c3d08b;
    }

    #widgets .resizer.padding-left-text {
        top: 20px;
        left: 0px;

        font-size: 0.5rem;
        color: #116dff;
        border-bottom:1px dotted #116dff;
    }

    #widgets .resizer.padding-bottom {
        width: 10px;
        height: 4px;

        bottom: 0px;
        left: 10px;
        cursor: se-resize;
        border: 1px solid #c3d08b;
        background-color: #c3d08b;
    }

    #widgets .resizer.padding-bottom-text {
        bottom: 0px;
        left: 10px;

        font-size: 0.5rem;
        vertical-align: text-bottom;
        color: #116dff;
        border-left:1px dotted #116dff;
    }


</style>

<!-- 드래그 css -->
<style>
    #widgets section.element.dragging-target {
        background-color: #fddd9b;
    }
</style>

{{-- 드래그 컨덴츠 --}}
<form id="widgets">
    @if(isset($pages))
    @foreach ($pages as $page)
        {!! $page !!}
    @endforeach
    @endif
</form>


<script>
    let token = document.querySelector('input[name=_token]').value;
    const dragResizes = document.querySelector('main.content');

    function _findTagClass(target, className) {
        while(1) {
            if(target.classList.contains(className)) return target;
            if(target.tagName == "MAIN") break;
            target = target.parentElement;
        }
        return null;
    }

    // mouse hover를 이용한 요소 선택
    let hoveredElement, selectedElement;

    dragResizes.addEventListener("mouseover",function(e){
        e.preventDefault();
        if(selectedElement) {
            // 선택한 요소가 있는 경우, 다른 hover 금지
        } else {
            let element = _findTagClass(e.target, 'element');
            if(element && hoveredElement != element) { //동일선택 배제

                // 이전에 hover한 값이 있는 경우,
                // 설정 클래스를 제거합니다.
                if(hoveredElement) {
                    hoveredElement.classList.remove('hovered');
                    if(hoveredElement.classList.contains('selected')) {
                        hoveredElement.classList.remove('selected');
                        //_removeResizer(hoveredElement);
                    }
                }

                // 새로운 hover 설정
                hoveredElement = element;
                hoveredElement.classList.add('hovered');
            }
        }
    });

    // 클릭 제어모드 전환
    dragResizes.addEventListener("click",function(e){
        e.preventDefault();

        if(selectedElement) {
            // selectedElement 토글
            selectedElement.classList.remove('selected');
            _removeResizer(selectedElement);
            selectedElement = null;

            hoveredElement.classList.remove('hovered');
            hoveredElement = null; //토글 해제시, 다시 호버 선택 요구

        } else {
            if(hoveredElement) {
                selectedElement = hoveredElement;
                selectedElement.classList.add('selected');
                _resizer(selectedElement);
            }
        }
    });

    function _resizer(target) {
        function __point(name) {
            let mt = document.createElement("div");
            mt.classList.add('resizer');
            mt.classList.add(name);
            return mt;
        }

        target.appendChild(__point('ne'));
        target.appendChild(__point('nw'));
        target.appendChild(__point('sw'));
        target.appendChild(__point('se'));


        target.appendChild(__point('margin-top'));
        target.appendChild(__point('margin-top-text'));
        target.appendChild(__point('margin-bottom'));
        target.appendChild(__point('margin-bottom-text'));
        target.appendChild(__point('margin-right'));
        target.appendChild(__point('margin-right-text'));
        target.appendChild(__point('margin-left'));
        target.appendChild(__point('margin-left-text'));


        target.appendChild(__point('padding-top'));
        target.appendChild(__point('padding-top-text'));
        target.appendChild(__point('padding-bottom'));
        target.appendChild(__point('padding-bottom-text'));
        target.appendChild(__point('padding-right'));
        target.appendChild(__point('padding-right-text'));
        target.appendChild(__point('padding-left'));
        target.appendChild(__point('padding-left-text'));


        _dragResize(target);
    }

    const _dragResize = function(element) {
        let currentResizer; // 선택된 현재 조작점
        let resizers = element.querySelectorAll(".resizer");

        for(let resizer of resizers) {
            resizer.addEventListener('mousedown', __resizerDown);
        }

        function __resizerDown(e) {
            console.log("start Resizing...");
            e.preventDefault();

            // resizing 중에는 드래그 선택 잠시 중단.
            e.target.parentElement.setAttribute('draggable',"false");
            e.target.parentElement.classList.add('resizing');

            currentResizer = e.target;
            isResizing = true;

            let prevX = e.clientX;
            let prevY = e.clientY;
            let prevXX = e.clientX;
            let prevYY = e.clientY;

            window.addEventListener('mousemove', __resizerMove);
            window.addEventListener('mouseup', __resizerUp);
            function __resizerMove(e) {

                const rect = element.getBoundingClientRect();

                if(currentResizer.classList.contains('margin-top')) {
                    element.style.marginTop = (e.clientY - prevYY) + "px";
                    console.log("marginTop = " + element.style.marginTop);

                    let value = element.querySelector('.margin-top-text');
                    value.textContent = (e.clientY - prevYY);
                    value.style.height = Math.abs(e.clientY - prevYY) + "px";

                    value.style.padding = "0 3px";

                    if((prevYY - e.clientY) < 0) {
                        value.style.top = (prevYY - e.clientY) + "px";
                        console.log("top="+value.style.top);

                    } else {
                        value.style.top = 0;
                        console.log("top="+value.style.top);
                    }



                } else
                if(currentResizer.classList.contains('margin-bottom')) {
                    element.style.marginBottom = (prevYY-e.clientY) + "px";
                    console.log("marginBottom= " + element.style.marginBottom)

                    let value = element.querySelector('.margin-bottom-text');
                    value.textContent = (prevYY-e.clientY);
                    value.style.height = Math.abs(prevYY-e.clientY) + "px";

                    value.style.padding = "0 3px";

                    if((prevYY-e.clientY) < 0) {
                        value.style.bottom = 0;
                        console.log("bottom="+value.style.bottom);


                    } else {
                        //value.style.bottom = 0;
                        value.style.bottom = "-" + (prevYY-e.clientY) + "px";
                        console.log("bottom="+value.style.bottom);
                    }

                } else
                if(currentResizer.classList.contains('margin-left')) {
                    element.style.marginLeft = (e.clientX - prevXX) + "px";

                    let value = element.querySelector('.margin-left-text');
                    value.textContent = (e.clientX - prevXX);
                    value.style.width = Math.abs(e.clientX - prevXX) + "px";

                    value.style.padding = "0 3px";

                    if((prevXX - e.clientX) < 0) {
                        value.style.left = (prevXX - e.clientX) + "px";
                        console.log("left="+value.style.left);
                    } else {
                        value.style.left = 1;
                        console.log("left="+value.style.left);
                    }


                } else
                if(currentResizer.classList.contains('margin-right')) {
                    element.style.marginRight = (prevXX - e.clientX) + "px";

                    let value = element.querySelector('.margin-right-text');
                    value.textContent = (prevXX - e.clientX);
                    value.style.width = Math.abs(prevXX - e.clientX) + "px";

                    value.style.padding = "0 3px";

                    if((prevXX - e.clientX) > 0) {
                        value.style.right = "-" + (prevXX - e.clientX) + "px";
                        console.log("right="+value.style.right);
                    } else {
                        value.style.right = 1;
                        console.log("right="+value.style.right);
                    }

                }


                if(currentResizer.classList.contains('padding-top')) {

                    let value = element.querySelector('.padding-top-text');
                    if((e.clientY - prevYY) > 0) {
                        let height = Math.abs(e.clientY - prevYY);
                        element.style.paddingTop = height + "px";
                        console.log("paddingTop = " + height);

                        value.textContent = height;
                        value.style.height = height + "px";
                        value.style.padding = "0 3px";

                        value.style.top = 0;
                        console.log("top="+value.style.top);
                    } else {
                        value.textContent = "";
                        value.style.height = "0px";
                        value.style.padding = 0;
                    }

                } else
                if(currentResizer.classList.contains('padding-bottom')) {
                    //element.style.paddingBottom = (e.clientY - prevYY) + "px";
                    //console.log("paddingBottom= " + element.style.paddingBottom);

                    let value = element.querySelector('.padding-bottom-text');
                    if((e.clientY - prevYY) < 0) {
                        let height = Math.abs(e.clientY - prevYY);

                        element.style.paddingBottom = height  + "px";
                        console.log("paddingBottom = " + height );

                        if(height == 0) {
                            value.textContent = "";
                            value.style.height = 0;

                        } else if(height <16) {
                            value.textContent = height;
                            value.style.height = "16px";

                        } else {
                            value.textContent = height ;
                            value.style.height = height  + "px";
                            value.style.padding = "0 3px";
                        }

                        value.style.bottom = 0;
                        console.log("bottom="+value.style.top);
                    } else {
                        value.textContent = "";
                        value.style.height = "0px";
                        value.style.padding = 0;
                    }


                } else
                if(currentResizer.classList.contains('padding-left')) {
                    //element.style.paddingLeft = (e.clientX - prevXX) + "px";
                    //console.log("paddingLeft = " + element.style.paddingLeft);
                    let value = element.querySelector('.padding-left-text');
                    if((e.clientX - prevXX) > 0) {
                        let width = Math.abs(e.clientX - prevXX);

                        element.style.paddingLeft = width + "px";
                        console.log("paddingLeft = " + width);


                        if(width == 0) {
                            value.textContent = "";
                            value.style.width = 0;

                        } else if(width <16) {
                            value.textContent = width;
                            value.style.width = "16px";

                        } else {
                            value.textContent = width;
                            value.style.width = Math.abs(e.clientX - prevXX) + "px";
                        }

                        value.style.padding = "0 3px";

                        value.style.left = 0;
                        console.log("left="+value.style.left);
                    } else {
                        value.textContent = "";
                        value.style.padding = 0;
                        value.style.width = 0;
                    }

                } else
                if(currentResizer.classList.contains('padding-right')) {
                    //element.style.paddingRight = (e.clientX - prevXX) + "px";
                    //console.log("paddingRight = " + element.style.paddingRight);

                    let value = element.querySelector('.padding-right-text');
                    if((e.clientX - prevXX) < 0) {
                        let width = Math.abs(e.clientX - prevXX);

                        element.style.paddingRight = width + "px";
                        console.log("paddingRight = " + width);


                        if(width == 0) {
                            value.textContent = "";
                            value.style.width = 0;

                        } else if(width <16) {
                            value.textContent = width;
                            value.style.width = "16px";

                        } else {
                            value.textContent = width;
                            value.style.width = Math.abs(e.clientX - prevXX) + "px";
                        }

                        value.style.padding = "0 3px";

                        value.style.right = 0;
                        console.log("top="+value.style.right);
                    } else {
                        value.textContent = "";
                        value.style.padding = 0;
                        value.style.width = 0;
                    }
                }





                if(currentResizer.classList.contains('se')) {

                    element.style.width = rect.width - (prevX - e.clientX) + "px";
                    element.style.height = rect.height - (prevY - e.clientY) + "px";

                }


                else if(currentResizer.classList.contains('sw')) {
                    element.style.width = rect.width + (prevX - e.clientX) + "px";
                    element.style.height = rect.height - (prevY - e.clientY) + "px";
                    element.style.left = (prevX - e.clientX) + "px";

                }



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

            function __resizerUp() {
                window.removeEventListener('mousemove',__resizerMove);
                window.removeEventListener('mouseup',__resizerUp);
                isResizing = false;

                // 드래그 이동 허용
                e.target.parentElement.setAttribute('draggable', "true");
                e.target.parentElement.classList.remove('resizing');

                // resize 정보 저장
                //___saveResizeInfo();
            }

            function ___saveResizeInfo() {
                /*
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
                */
            }
        }


    }

    function _removeResizer(target) {
        target.querySelectorAll('.resizer').forEach(el=>{
            el.remove();
        });
    }
</script>

<!-- widget 드래그 이동 -->
<script>
    const dragWidgets = document.querySelector('main.content');
    dragWidgets.querySelectorAll('section.element').forEach(el => {
        // 드래그 이동 활성화
        el.setAttribute('draggable',"true");
    });

    dragWidgets.querySelectorAll('section.element .widget').forEach(el => {
        // 드래그 이동 활성화
        el.setAttribute('draggable',"true");
    });



    // Pages Drag 이벤트 main 위임
    let dragStart, startWidget;
    let dragSelect;
    let dragPosition; //드래그할 위치 지정
    let templateWidget;

    function _selectedTemplate(target) {
        if(target.dataset['type'] == 'section') {
            let section = document.createElement('section');
            section.classList.add('element');
            section.setAttribute('draggable',"true");

            let inner = document.createElement('section');
            inner.classList.add('inner');
            section.appendChild(inner);

            templateWidget = section; // DB 저장
            dragSelect = "section";

            return section;
        } else {
            dragStart = target.cloneNode(true); //템플릿 복사
            //dragStart.classList.remove('template'); // 중복 복사를 방지하지 위해서 클래스 제거
            //dragStart.addEventListener('click', widgetResizeClickEvent);
            /*
            let article = document.createElement('article');
            article.classList.add('element');
            article.classList.add('widget');
            article.setAttribute('draggable',"true");
            */

            console.log(dragStart);
            templateWidget = dragStart; // DB 저장
            dragSelect = "widget";

            return dragStart;
        }
    }

    dragWidgets.addEventListener('dragstart', (e) => {
        console.log("drag start...");

        // 템플릿 선택
        if(e.target.classList.contains('template')) {
            console.log("템플릿 선택");
            dragStart = _selectedTemplate(e.target);
            console.log(dragStart);
            return;
        }

        // 요소 검출
        let target = e.target;
        while(1) {
            if(target.classList.contains('element')
                && target.tagName == "SECTION"
            ) break;

            if(target.classList.contains('element')
                && target.tagName == "ARTICLE"
            ) break;

            if(target.tagName == "FORM") return;
            if(target.tagName == "MAIN") return;
            target = target.parentElement;
        }

        if(target.tagName == "SECTION") {
            dragStart = target;
            dragSelect = "section";
            console.log("drag start > section");
            target.classList.add('dragging');

        } else
        if(target.tagName == 'ARTICLE') {
            dragStart = target;
            dragSelect = "widget";
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
        //console.log("drag Leave");
        //console.log(e.target);
    });

    dragWidgets.addEventListener('drop', (e) => {
        e.preventDefault();
        console.log('drop');
        console.log(dragSelect);

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

            // 이동 대상 영역 선택
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
                dragStart.setAttribute('data-pos', 1);

                dragTarget.querySelector('.inner').appendChild(dragStart);
                console.log(dragTarget);

            } else
            // 위젯을 이동합니다.
            if(dragTarget.classList.contains('widget')) {
                dragStart.setAttribute('data-ref', target.parentElement.parentElement.dataset['id']);
                dragStart.setAttribute('data-level', parseInt(target.parentElement.parentElement.dataset['level']) + 1 );
                //console.log("id=" + target.parentElement.parentElement.dataset['id']);


                if(dragPosition == "right") {
                    dragStart.dataset['pos'] = parseInt(target.parentElement.parentElement.dataset['pos']) + 1 ;
                    let next = target.nextElementSibling;
                    if(next) {
                        console.log("widget 뒤에 추가");
                        target.parentElement.insertBefore(dragStart, next);
                    } else {
                        console.log("다음요소 없음. 마지막 추가");
                        target.parentElement.appendChild(dragStart);
                    }

                } else if(dragPosition == "left") {
                    dragStart.dataset['pos'] = parseInt(target.parentElement.parentElement.dataset['pos']) - 1 ;

                    console.log("widget 앞에 추가");
                    target.parentElement.insertBefore(dragStart, target);
                    //

                } else {
                    // 위지 지정값 없음.
                    // 비어있는 섹션에 이동시...
                    console.log("위젯을 섹션 처음에 추가");
                    target.parentElement.insertBefore(dragStart, target.parentElement.firstChild);
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
    });



</script>

<!-- context Menu -->
@livewire('PageContextPopup')

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
            let li = document.createElement("li");
            let link = document.createElement("a");
            link.innerHTML = "삭제";
            //link.href = "/apiadmin/easy/menu/"+menu_id+"/items/create?ref=" + id;
            li.appendChild(link);
            link.addEventListener('click', function(e){
                e.preventDefault();
                console.log('delete click');

                let confirm = window.confirm("delete widget?");
                if(confirm) {
                    //ajax 호출
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "/api/pages/delete");
                    let data = new FormData();
                    data.append('_token', token);

                    let id = widget.dataset['id'];
                    data.append('id', id);

                    xhr.onload = function() {
                        var data = JSON.parse(this.responseText);
                        console.log(data);

                        // 페이지 갱신
                        widget.remove();
                        location.reload();

                        //console.log("테이블 갱신요청");
                        // 라이브와이어 테이블 갱신
                        //Livewire.emit('refeshTable');
                    }

                    xhr.send(data);
                }
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

<script>
    // section row grid 재정렬
    /*
    function _sectionGridReset(section) {
        let style = "grid-template-columns:";
        section.querySelectorAll('.element').forEach(el=>{
            if()
        });
    }
    */
</script>
