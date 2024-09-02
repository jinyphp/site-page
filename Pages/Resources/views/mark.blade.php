<x-theme>
    <x-theme-layout>
        <!-- 요소 선택 스타일 -->
        <style>
            #widgets .element.hovered {
                position:relative;
                cursor: move;
                /* margin:5px; */
            }

            #widgets .element .inner {
                min-height: 30px;
            }

            #widgets .element.margin {
                margin:10px;
                padding:10px;
            }

            #widgets .element.selected {
                border: 1px solid #116dff;
            }

            .element.margin {
                border: 1px solid #555;
            }

            /* Vertical */
            section.element.margin.vertical {
                border: 1px solid #fca5a5;
            }

            section.element.margin.vertical.hovered {
                border: 1px solid #991b1b;
            }

            section.element.vertical.hovered {
                border: 1px solid #dc2626;
            }




            /* Horizontal */
            section.element.margin.horizontal {
                border: 1px solid #d8b4fe;
            }

            section.element.margin.horizontal.hovered {
                border: 1px solid #9333ea;
            }

            section.element.margin.hovered {
                border: 1px solid #9333ea;
            }

            section.element.dragover {
                border: 1px solid #6b21a8;
                background-color: #f3e8ff;
            }

            section.element.dragover.top {
                border-top: 2px solid #581c87;

            }
            section.element.dragover.bottom {
                border-bottom: 2px solid #581c87;
            }
            section.element.dragover.left {
                border-left: 2px solid #581c87;
            }
            section.element.dragover.right {
                border-right: 2px solid #581c87;
            }
            section.element.dragover.enter {
                border: 2px solid #581c87;
            }


            /* Article */
            article.element.margin {
                border: 1px solid #86efac; /*green-300*/
            }

            article.element.margin.hovered {
                border: 1px solid #16a34a; /*green-600*/
            }

            article.element.hovered {
                border: 1px solid #16a34a;
            }

            article.element.dragover {
                border: 1px solid #166534; /*green-800*/
                background-color: #f0fdf4;
            }

            article.element.dragover.top {
                border-top: 3px solid #14532d;
            }

            article.element.dragover.bottom {
                border-bottom: 3px solid #14532d;
            }

            article.element.dragover.left {
                border-left: 3px solid #14532d;
            }

            article.element.dragover.right {
                border-right: 3px solid #14532d;
            }




            /*
            section.element.hovered {
                border: 1px solid #eab308;
            }
            section.element.dragover {
                background-color: #fef08a;
            }
            section.element.dragover.bottom {
                border-bottom: 2px solid #1d4ed8;
            }
            section.element.dragover.top {
                border-top: 2px solid #0369a1;
            }
            section.element.dragover.in {
                border: 2px solid #22d3ee;
            }




            section.element.hovered.horizontal {
                border: 1px solid #ec4899;
            }
            section.element.horizontal.dragover {
                background-color: #fecdd3;
            }


            section.element.hovered.vertical {
                border: 1px solid #a855f7;
            }
            section.element.vertical.dragover {
                background-color: #e9d5ff;
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
            */
            /*



            article.element.hovered {
                border: 1px solid #22c55e;
            }
            article.element.dragover {
                background-color: #bbf7d0;
            }
            article.element.top {
                border-top: 2px solid #0369a1;
            }
            article.element.bottom {
                border-bottom: 2px solid #0369a1;
            }
            article.element.left {
                border-left: 2px solid #0369a1;
            }
            article.element.right {
                border-right: 2px solid #0369a1;
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
            */
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
            @foreach ($pages as $page)
                {!! $page !!}
            @endforeach
        </form>

        <script>
            let token = document.querySelector('input[name=_token]').value;
            const dragResizes = document.querySelector('main.content');

            function _findTagClass(target, className) {
                if(target) {
                    while(1) {
                        if(target.classList.contains(className)) return target;
                        if(target.tagName == "MAIN") break;
                        target = target.parentElement;
                    }
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
                    if(element) {
                        if(element.classList.contains('template')) return; // 템플릿은 호버 선택 안함
                    }

                    if(element && hoveredElement != element) { //동일선택 배제

                        // 이전에 hover한 값이 있는 경우,
                        // 설정 클래스를 제거합니다.
                        if(hoveredElement) {
                            //// hoveredElement.style.border = null;
                            //hoveredElement.style.margin = null;
                            hoveredElement.classList.remove('hovered');

                            if(hoveredElement.classList.contains('selected')) {
                                hoveredElement.classList.remove('selected');
                                //_removeResizer(hoveredElement);

                            }
                        }

                        // 새로운 hover 설정
                        hoveredElement = element;
                        hoveredElement.classList.add('hovered');
                        //console.log(hoveredElement);
                        let level = hoveredElement.dataset['level'];
                        if(hoveredElement.tagName == "SECTION") {
                            //hoveredElement.style.margin = "10px";
                        } else {
                            //hoveredElement.style.border = "1px solid #14532d"; //green-900
                        }



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
                        console.log(selectedElement);
                        console.log("width=" + selectedElement.style.width);
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

            function resizeGridColumns(inner) {
                let section = inner.parentElement;
                if(section.tagName == "SECTION" && section.classList.contains("horizontal")) {
                    //console.log(inner.style.gridTemplateColumns);
                    //console.log(inner.childNodes);
                    let style = "";
                    let width, height;

                    //console.log("가로배치");
                    inner.childNodes.forEach(el=>{
                        //console.log(el);
                        if(el.style.width) {
                            width = parseInt(el.style.width, 10);
                            //console.log("width=" + width);
                            style += width + "px ";
                        } else {
                            style += "1fr ";
                        }
                    });

                    //console.log("style="+style);
                    inner.style.gridTemplateColumns = style;
                    //console.log("변경저장");
                    //console.log(inner.style.gridTemplateColumns);
                }



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
                        console.log("사이즈 저장")
                        //.grid-template-columns
                        resizeGridColumns(e.target.parentElement.parentElement);

                        ___saveResizeInfo(e);
                    }



                    function ___saveResizeInfo(e) {

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

            function _removeResizer(target) {
                target.querySelectorAll('.resizer').forEach(el=>{
                    el.remove();
                });
            }
        </script>

        <script>
            // 키 이벤트
            document.addEventListener('keydown', function(e){

                if(e.keyCode === 27) { //ESC키
                    /*
                    if(selectedElement) {
                        // selectedElement 토글
                        selectedElement.classList.remove('selected');
                        _removeResizer(selectedElement);
                        selectedElement = null;
                    }
                    */
                }
                //&& e.shiftKey)
                if(e.key.toLowerCase() === 'a' && e.ctrlKey ) {
                    e.preventDefault();

                    let section = document.querySelectorAll('.element');
                    section.forEach(el=>{
                        if(el.classList.contains('template')) {
                            // 템플릿은 배제
                        } else
                        if(el.classList.contains('margin')) {
                            el.classList.remove('margin');
                        } else {
                            el.classList.add('margin');
                        }
                    });
                }

            });
        </script>

        <!-- widget 드래그 이동 -->
        <script>
        window.addEventListener('DOMContentLoaded',function(e){

            function getSectionType(dragOver) {
                let parentSection = _findTagClass(dragOver.parentElement, 'element');
                if(parentSection) {
                    if(parentSection.classList.contains("vertical")) {
                        return "vertical";
                    } else {
                        return "horizontal";
                    }
                } else {
                    // 상위 섹션이 존재하지 않음. root
                    return "vertical";
                }
            }

            function dragOverTop(dragOver) {
                dragOver.classList.add('top');
                dragOver.classList.remove('bottom');
                dragOver.classList.remove('enter');
                dragPosition = "top";
                console.log('position: section top');
                return dragPosition;
            }

            function dragOverBottom(dragOver) {
                dragOver.classList.add('bottom');
                dragOver.classList.remove('top');
                dragOver.classList.remove('enter');
                dragPosition = "bottom";
                console.log('position: section bottom');
            }

            function dragOverLeft(dragOver) {
                dragOver.classList.add('left');
                dragOver.classList.remove('right');
                dragOver.classList.remove('enter');
                dragPosition = "left";
                console.log('position: section left');
            }

            function dragOverRight(dragOver) {
                dragOver.classList.add('right');
                dragOver.classList.remove('left');
                dragOver.classList.remove('enter');
                dragPosition = "right";
                console.log('position: section right');
            }

            function dragOverEnter(dragOver) {
                dragOver.classList.add('enter');
                dragOver.classList.remove('top');
                dragOver.classList.remove('bottom');
                dragPosition = "inner";
                console.log('position: section inner');
            }




            // page 요소 드래그 이동배치
            class PageDragDrop {
                constructor(page) {
                    this.page = page;

                    this.sections = this.page.querySelectorAll('section.element');
                    this.sections.forEach(el => {
                        el.setAttribute('draggable',"true"); // 드래그 이동 활성화
                    });

                    this.widgets = this.page.querySelectorAll('section.element .widget');
                    this.widgets.forEach(el => {
                        el.setAttribute('draggable',"true"); // 드래그 이동 활성화
                    });

                    // 이벤트위임 : 드래그 이동
                    dragWidgets.addEventListener('dragstart', this.eventDragStart);
                    dragWidgets.addEventListener('dragover', this.eventDragOver);
                    dragWidgets.addEventListener('dragenter', e => {
                        e.preventDefault();
                    });

                    dragWidgets.addEventListener('dragleave', e => {
                        e.preventDefault();
                    });
                    dragWidgets.addEventListener('drop', this.eventDragDrop);
                    dragWidgets.addEventListener('dragend', this.eventDragEnd);
                }

                // 드래그 시작
                eventDragStart(e){
                    console.log("drag start...");

                    // 요소 선택
                    let target;
                    target = _findTagClass(e.target, 'element');
                    //console.log(target);

                    // 템플릿 선택
                    if(target.classList.contains('template')) {
                        console.log("템플릿 선택");
                        //dragStart = _selectedTemplate(target);
                        dragStart = target.cloneNode(true);
                        //console.log(dragStart);
                        if(dragStart.tagName == "SECTION") {
                            dragSelect = "section";
                        } else if(dragStart.tagName == 'ARTICLE') {
                            dragSelect = "widget";
                        }

                        if(dragStart.dataset['type']) {
                            dragStart.classList.add( dragStart.dataset['type'] );
                        }

                        // 템플릿 캠버스 숨기기
                        let templateCanvas = document.querySelector('.jiny-offcanvas');
                        if(!templateCanvas.classList.contains('slide')) {
                            //console.log("canvas slide");
                            templateCanvas.classList.add('slide');
                        }

                        return;
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
                }

                // 드래그 hover 위치 파악 출력
                eventDragOver(e) {
                    e.preventDefault();

                    let target = _findTagClass(e.target, 'element');
                    if(target && dragStart != target) {

                        // 중복선택 배제
                        if(dragOver && dragOver != target) {
                            //dragOver.style = null;
                            console.log("dragover 초기화")
                            dragPosition = null;
                            dragOver.classList.remove('dragover');
                            dragOver.classList.remove('bottom');
                            dragOver.classList.remove('top');
                            dragOver.classList.remove('right');
                            dragOver.classList.remove('left');
                            dragOver.classList.remove('enter');
                        }

                        dragOver = target;
                        if(dragSelect == "section") {
                            if(dragOver.tagName == "SECTION") {

                                let refPos = dragStart.compareDocumentPosition(dragOver);
                                if(refPos == 20) {
                                    console.log("자손 선택 불가 = "+refPos);
                                    return;
                                }

                                dragOver.classList.add('dragover');
                                let sectionType = getSectionType(dragOver);


                                if( sectionType == "vertical" && e.offsetY < (dragOver.offsetHeight/3) ) {
                                    // 중복설정 배제
                                    if(dragPosition != "top") {
                                        dragOverTop(dragOver);
                                        return;
                                    }
                                }

                                if( sectionType == "vertical" && e.offsetY > (dragOver.offsetHeight/3)*2 ) {
                                    // 중복설정 배제
                                    if(dragPosition != "bottom") {
                                        dragOverBottom(dragOver);
                                        return;
                                    }
                                }

                                if( sectionType == "horizontal" && e.offsetX < (dragOver.offsetWidth/3) ) {
                                    // 중복설정 배제
                                    if(dragPosition != "left") {
                                        dragOverLeft(dragOver);
                                        return;
                                    }
                                }

                                if( sectionType == "horizontal" && e.offsetX > (dragOver.offsetWidth/3)*2 ) {
                                    // 중복설정 배제
                                    if(dragPosition != "right") {
                                        dragOverRight(dragOver);
                                        return;
                                    }
                                }

                                if( (sectionType == "vertical"
                                    && e.offsetY > (dragOver.offsetHeight/3)
                                    && e.offsetY<(dragOver.offsetHeight/3)*2)
                                    ||
                                    (sectionType == "horizontal"
                                    && e.offsetX > (dragOver.offsetWidth/3)
                                    && e.offsetX<(dragOver.offsetWidth/3)*2)
                                ) {
                                    // 중복설정 배제
                                    if(dragPosition != "inner") {
                                        dragOverEnter(dragOver);
                                        return;
                                    }
                                }
                                //
                            } else {
                                // section -> widget
                                dragOver.classList.add('dragover');

                                let sectionType = getSectionType(dragOver);

                                if( sectionType == "horizontal" && e.offsetX < (dragOver.offsetWidth/3) ) {
                                    // 중복설정 배제
                                    if(dragPosition != "left") {
                                        dragOverLeft(dragOver);
                                        return;
                                    }
                                }

                                if( sectionType == "horizontal" && e.offsetX > (dragOver.offsetWidth/3)*2 ) {
                                    // 중복설정 배제
                                    if(dragPosition != "right") {
                                        dragOverRight(dragOver);
                                        return;
                                    }
                                }

                                if( sectionType == "vertical" && e.offsetY < (dragOver.offsetHeight/3) ) {
                                    // 중복설정 배제
                                    if(dragPosition != "top") {
                                        dragOverTop(dragOver);
                                        return;
                                    }
                                }

                                if( sectionType == "vertical" && e.offsetY > (dragOver.offsetHeight/3)*2 ) {
                                    // 중복설정 배제
                                    if(dragPosition != "bottom") {
                                        dragOverBottom(dragOver);
                                        return;
                                    }
                                }


                            }

                        } else if(dragSelect == "widget") {
                            dragOver.classList.add('dragover');

                            if(dragOver.tagName == "SECTION") {

                                let parentSection = _findTagClass(dragStart.parentElement, 'element');

                                if(parentSection == dragOver) {
                                    //부모섹션, 배제
                                    return;
                                } else {
                                    // 섹션 포지션
                                    let sectionType = getSectionType(dragOver);


                                    if( sectionType == "horizontal" && e.offsetX < (dragOver.offsetWidth/3) ) {
                                        // 중복설정 배제
                                        if(dragPosition != "left") {
                                            dragOverLeft(dragOver);
                                            return;
                                        }
                                    }

                                    if( sectionType == "horizontal" && e.offsetX > (dragOver.offsetWidth/3)*2 ) {
                                        // 중복설정 배제
                                        if(dragPosition != "right") {
                                            dragOverRight(dragOver);
                                            return;
                                        }
                                    }

                                    if( sectionType == "vertical" && e.offsetY < (dragOver.offsetHeight/3) ) {
                                        // 중복설정 배제
                                        if(dragPosition != "top") {
                                            dragOverTop(dragOver);
                                            return;
                                        }
                                    }

                                    if( sectionType == "vertical" && e.offsetY > (dragOver.offsetHeight/3)*2 ) {
                                        // 중복설정 배제
                                        if(dragPosition != "bottom") {
                                            dragOverBottom(dragOver);
                                            return;
                                        }
                                    }

                                    if( (sectionType == "vertical"
                                        && e.offsetY > (dragOver.offsetHeight/3)
                                        && e.offsetY<(dragOver.offsetHeight/3)*2)
                                        ||
                                        (sectionType == "horizontal"
                                        && e.offsetX > (dragOver.offsetWidth/3)
                                        && e.offsetX<(dragOver.offsetWidth/3)*2)
                                    ) {
                                        // 중복설정 배제
                                        if(dragPosition != "inner") {
                                            dragOverEnter(dragOver);
                                            return;
                                        }
                                    }


                                }

                            } else if(dragOver.tagName == "ARTICLE") {

                                let parentSection = _findTagClass(dragOver.parentElement, 'element');
                                if(parentSection) {
                                    if(parentSection.classList.contains('vertical')) {
                                        console.log('vertical');

                                        if( e.offsetY > (target.offsetHeight/2) ) {
                                            if(dragPosition != "bottom") {
                                                dragOverBottom(dragOver);
                                                return;
                                            }
                                        }

                                        if( e.offsetY < (target.offsetHeight/2) ) {
                                            if(dragPosition != "top") {
                                                dragOverTop(dragOver);
                                                return;
                                            }
                                        }


                                    } else {
                                        console.log("section hover = horizontal");

                                        if( e.offsetX > (target.offsetWidth/2) ) {
                                            if(dragPosition != "right") {
                                                dragOverRight(dragOver);
                                                return;
                                            }
                                        }

                                        if( e.offsetX < (target.offsetWidth/2) ) {
                                            if(dragPosition != "left") {
                                                dragOverLeft(dragOver);
                                                return;
                                            }
                                        }
                                        //
                                    }
                                } else {
                                    console.log("article hover, 상위 section을 선택할 수 없습니다.");
                                    console.log(parentSection);
                                    console.log(dragOver);
                                }
                                //




                            }
                        }

                    } else {
                        if(dragOver) dragOver.classList.remove('dragover');
                    }

                }

                eventDragDrop(e){
                    e.preventDefault();




                    let target = _findTagClass(e.target, 'element');
                    let dragTarget = target;




                    if(dragSelect == "section") {
                        let refPos = dragStart.compareDocumentPosition(dragTarget);
                        if(refPos == 20) {
                            console.log("자손으로 이동을 할 수 없습니다. = "+refPos);
                            return;
                        }

                        let src = _findTagClass(dragStart.parentElement, 'element');

                        // 섹션 위로 자리 이동
                        if(dragPosition == "left") {
                            dropMoveLeft(dragStart, dragTarget);
                        } else if(dragPosition == "right") {
                            console.log("section 오른쪽 이동")
                            dropMoveRight(dragStart, dragTarget);
                        } else if(dragPosition == "top") {
                            console.log("section 위로 이동")
                            dropMoveTop(dragStart, dragTarget);
                        } else if(dragPosition == "bottom") {
                            dropMoveBottom(dragStart, dragTarget);
                        } else if(dragPosition == "inner") {
                            dragMoveEnter(dragStart, dragTarget);
                        }

                        // 드래그 사이즈 재정렬
                        if(src) resizeGridColumns(src.firstChild);
                        resizeGridColumns(dragStart.parentElement);

                        // 변경된 섹션 정보를 서버에 저장합니다.
                        jiny.pages.saveDragSection();

                    } else

                    // 선택소스가 widget일 경우
                    if(dragSelect == "widget") {
                        //let target = _findTagClass(e.target, 'element');
                        //let dragTarget = target;

                        let src = _findTagClass(dragStart.parentElement, 'element');

                        // 대상 타켓이 section인 경우
                        if(dragTarget.tagName == "SECTION") {

                            if(dragPosition == "inner") {
                                dragMoveEnter(dragStart, dragTarget);
                            } else if(dragPosition == "right") {
                                dropMoveRight(dragStart, dragTarget);
                            } else if(dragPosition == "left") {
                                dropMoveLeft(dragStart, dragTarget);
                            } else if(dragPosition == "top") {
                                console.log("위젯 섹션 위로")
                                dropMoveTop(dragStart, dragTarget);
                            } else if(dragPosition == "bottom") {
                                dropMoveBottom(dragStart, dragTarget);
                            }

                        } else
                        // 위젯을 이동합니다.
                        if(dragTarget.classList.contains('widget')) {
                            if(dragPosition == "right") {
                                dropMoveRight(dragStart, dragTarget);
                            } else if(dragPosition == "left") {
                                dropMoveLeft(dragStart, dragTarget);
                            } else if(dragPosition == "top") {
                                dropMoveTop(dragStart, dragTarget);
                            } else if(dragPosition == "bottom") {
                                dropMoveBottom(dragStart, dragTarget);
                            }
                        }

                        // 드래그 사이즈 재정렬
                        if(src) resizeGridColumns(src.firstChild);
                        resizeGridColumns(dragStart.parentElement);

                        // 계층이동
                        // 섹션 변경 순서를 저장 pos 정보를 저장
                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "/api/pages/drag/widget");
                        let data = new FormData();
                        data.append('_token', token);
                        data.append('_uri', location.href);

                        data.append("element", "widget");
                        //console.log(dragStart);

                        if(dragStart.classList.contains('template')) {
                            dragStart.classList.remove('template');
                        }

                        let node = dragStart.parentElement;
                        //console.log(node);
                        let i = 1;
                        let insert;
                        node.childNodes.forEach(el=>{
                            el.dataset['pos'] = i;
                            if(el.dataset['id'] == undefined) insert = true;
                            data.append("pos[" + el.dataset['id'] + "][pos]", i);
                            data.append("pos[" + el.dataset['id'] + "][type]", el.dataset['type']);
                            data.append("pos[" + el.dataset['id'] + "][ref]", el.dataset['ref']);
                            data.append("pos[" + el.dataset['id'] + "][level]", el.dataset['level']);
                            i++;
                        });

                        xhr.onload = function() {
                            var data = JSON.parse(this.responseText);
                            console.log(data);
                            if(insert) {
                                //dragStart.setAttribute('data-ref', parentSection.dataset['id']);

                                dragStart.setAttribute('data-id', data.insertGetId);
                                console.log(dragStart);

                                console.log("block");
                                //let url = "/api/pages/block";
                                let url = dragStart.dataset['url'];
                                fetch(url, {
                                    method: 'get'
                                })
                                .then(response => {
                                    return response.text();
                                })
                                .then(data => {
                                    console.log(data);
                                    dragStart.innerHTML = data;
                                    console.log(dragStart);


                                    // =====
                                    // block save
                                    let xhr = new XMLHttpRequest();
                                    xhr.open("POST", "/api/pages/block/save");
                                    let data1 = new FormData();
                                    data1.append('_token', token);
                                    data1.append('_uri', location.href);
                                    data1.append('id', dragStart.dataset['id']);
                                    data1.append("content", data);
                                    xhr.onload = function() {
                                        var data2 = JSON.parse(this.responseText);
                                        console.log(data2);

                                    }
                                    xhr.send(data1);


                                    // =====

                                    //dragStart = null;
                                });




                            }


                            // 페이지 갱신
                            //location.reload();

                            //console.log("테이블 갱신요청");
                            // 라이브와이어 테이블 갱신
                            //Livewire.emit('refeshTable');
                        }

                        xhr.send(data);


                        // 이동, 삽입된 section grid 재조정
                        //console.log("drag grid 사이즈 재조정");
                        //resizeGridColumns(dragStart.parentElement);
                    }



                }

                saveDragSection() {
                    // 섹션 변경 순서를 저장 pos 정보를 저장
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "/api/pages/drag/section");

                    let data = new FormData();
                    data.append('_token', token);
                    data.append('_uri', location.href);

                    data.append("element", "section");

                    console.log("save...");
                    let node = dragStart.parentElement;
                    console.log(node);
                    let i = 1;
                    node.childNodes.forEach(el=>{
                        if(el.nodeType == Node.ELEMENT_NODE) {
                            console.log(el);

                            el.dataset['pos'] = i;
                            data.append("pos[" + el.dataset['id'] + "][pos]", i);
                            data.append("pos[" + el.dataset['id'] + "][type]", el.dataset['type']);
                            data.append("pos[" + el.dataset['id'] + "][ref]", el.dataset['ref']);
                            data.append("pos[" + el.dataset['id'] + "][level]", el.dataset['level']);
                            i++;
                        }

                    })

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

                eventDragEnd(e) {
                    e.preventDefault();
                    console.log('dragend');

                    if(dragOver) {
                        dragOver.classList.remove('dragover');
                        dragOver.classList.remove('bottom');
                        dragOver.classList.remove('top');
                        dragOver.classList.remove('right');
                        dragOver.classList.remove('left');
                    }
                    //console.log(e.target);

                    let target = e.target;
                    target.classList.remove('dragging');


                    //초기화
                    startWidget = null;
                    dragPosition = null;
                    dragSelect = null;

                    // block content, download 받기
                    if(dragStart.dataset['type'] == "block") {


                        //return;
                    } else {
                        dragStart = null;
                    }


                    // 템플릿
                    let templateCanvas = document.querySelector('.jiny-offcanvas');
                    if(templateCanvas && templateCanvas.classList.contains('slide')) {
                        templateCanvas.classList.remove('slide');
                    }
                }

            }

            // Pages Drag 이벤트 main 위임
            let dragStart, startWidget;
            let dragSelect;
            let dragPosition; //드래그할 위치 지정
            let templateWidget;
            let dragOver;

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

                    //console.log(dragStart);
                    templateWidget = dragStart; // DB 저장
                    dragSelect = "widget";

                    return dragStart;
                }
            }


            function dragMoveEnter(dragStart, dragTarget) {
                dragTarget.querySelector('.inner').appendChild(dragStart);

                dragStart.setAttribute('data-ref', dragTarget.dataset['id']);
                dragStart.setAttribute('data-level', parseInt(dragTarget.dataset['level']) + 1 );
                dragStart.setAttribute('data-pos', 1);
            }

            function dropMoveRight(dragStart, dragTarget) {
                //dragStart.dataset['pos'] = parseInt(dragTarget.parentElement.parentElement.dataset['pos']) + 1 ;
                let next = dragTarget.nextElementSibling;
                if(next) {
                    //console.log(" 뒤에 추가");
                    dragTarget.parentElement.insertBefore(dragStart, next);
                } else {
                    //console.log("다음요소 없음. 마지막 추가");
                    dragTarget.parentElement.appendChild(dragStart);
                }

                let parentSection = _findTagClass(dragTarget.parentElement, 'element');
                if(parentSection) {
                    dragStart.setAttribute('data-ref', parentSection.dataset['id']);
                    dragStart.setAttribute('data-level', parseInt(parentSection.dataset['level']) + 1 );
                }
            }

            function dropMoveLeft(dragStart, dragTarget) {
                //dragStart.dataset['pos'] = parseInt(dragTarget.parentElement.parentElement.dataset['pos']) - 1 ;

                //console.log("앞에 추가");
                dragTarget.parentElement.insertBefore(dragStart, dragTarget);

                let parentSection = _findTagClass(dragTarget.parentElement, 'element');
                if(parentSection) {
                    dragStart.setAttribute('data-ref', parentSection.dataset['id']);
                    dragStart.setAttribute('data-level', parseInt(parentSection.dataset['level']) + 1 );
                }
            }

            function dropMoveTop(dragStart, dragTarget) {
                //  상위 root 또는 섹션 inner
                dragTarget.parentElement.insertBefore(dragStart, dragTarget);

                let parentSection = _findTagClass(dragTarget.parentElement, 'element');
                if(parentSection) {
                    // 상위 섹션
                    dragStart.setAttribute('data-ref', parentSection.dataset['id']);
                    dragStart.setAttribute('data-level', parseInt(parentSection.dataset['level']) + 1 );
                } else {
                    // root
                    dragStart.setAttribute('data-ref', 0);
                    dragStart.setAttribute('data-level', 1 );
                }
            }

            function dropMoveBottom(dragStart, dragTarget) {
                //console.log('section 하단');

                // 섹션 아래 자리로 이동
                let targetNext = dragTarget.nextElementSibling;
                if(targetNext) {
                    //다음요소 앞에 삽입
                    dragTarget.parentElement.insertBefore(dragStart, targetNext);
                } else {
                    // 마지막 노드
                    dragTarget.parentElement.appendChild(dragStart);
                }

                let parentSection = _findTagClass(dragTarget.parentElement, 'element');
                if(parentSection) {
                    dragStart.setAttribute('data-ref', parentSection.dataset['id']);
                    dragStart.setAttribute('data-level', parseInt(parentSection.dataset['level']) + 1 );

                } else {
                    //root
                    dragStart.setAttribute('data-ref', 0);
                    dragStart.setAttribute('data-level', 1 );

                }
            }

            const dragWidgets = document.querySelector('main.content');
            jiny.pages = new PageDragDrop(dragWidgets);

        });





        </script>

        <!-- context Menu -->
        @livewire('PageContextPopup')
        @push("scripts")
        <script>
            // 섹션, 위젯 설정 contextMenu
            window.addEventListener('DOMContentLoaded',function(e){
                const dragWidgets = document.querySelector('main.content');
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

                    // 섹션 수정
                    menu.appendChild( setSection(section) );
                    let secDel = section.querySelector('.element');
                    if(!secDel) {
                        menu.appendChild( widgetDelete(section, "섹션 삭제") );
                    }
                    return menu;
                });

                // 섹션, 위젝 삭제
                function widgetDelete(widget, title) {
                    let li = document.createElement("li");
                    let link = document.createElement("a");

                    if(title) {
                        link.innerHTML = title;
                    } else {
                        link.innerHTML = "삭제";
                    }

                    li.appendChild(link);
                    link.addEventListener('click', function(e){
                        e.preventDefault();
                        console.log('delete click');

                        jiny.modal.confirm("삭제 하시겠습니까?", function(){
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
                                console.log("drag grid 사이즈 재조정");

                                // 페이지 갱신
                                let parentSection = _findTagClass(widget.parentElement, 'element');
                                widget.remove();
                                resizeGridColumns(parentSection); // 사위 섹션의 grid 사이즈 조정
                            }

                            xhr.send(data);
                        });


                    });

                    return li;
                }

                // 위젯 컨덴츠 수정
                function setWidget(widget) {

                    let li = document.createElement("li");
                    let link = document.createElement("button");
                    let id = widget.dataset['id'];
                    link.innerHTML = "위젯 수정("+id+")";

                    link.setAttribute('wire:click', "$emit('sectionOpen')");
                    link.setAttribute('id',"btn-livepopup-create");

                    link.addEventListener("click",function(e){
                        e.preventDefault();
                        Livewire.emit('sectionOpen', id);
                    });


                    li.appendChild(link);
                    return li;
                }

                // 섹션 정보버튼
                function setSection(section) {
                    let link = document.createElement("a");
                    let id = section.dataset['id'];
                    link.innerHTML = "섹션 정보 수정("+ id +")";

                    link.setAttribute('href',"javascript: void(0);");
                    //link.setAttribute('wire:click', "$emit('sectionOpen')");
                    //link.setAttribute('id',"btn-pages-section");
                    link.addEventListener("click",function(e){
                        e.preventDefault();
                        Livewire.emit('sectionOpen', id);
                    });


                    let li = document.createElement("li");
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





                //
            });
        </script>
        @endpush

        <script>
            /*
            window.addEventListener('DOMContentLoaded', function(e){
                jiny.modal.confirm("삭제 하시겠습니까?", function(){
                    alert("확인");
                });
            });
            */






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
                        ///if(dragStart) return;
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

        <!-- 템플릿 -->
        <style>

        </style>
        <!-- offcanvas-->
        <button id="offcanvas">
            <div class="flex items-center p-2 bg-white">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-magic" viewBox="0 0 16 16">
                        <path d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0v1.829Zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707L14 2.707ZM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707L7.293 4Zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1h1.829Zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1h1.829ZM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707L13.293 10ZM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0v1.829Zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0L8.354 9.06Z"/>
                    </svg>
                </div>
                <div class="flex-grow ml-2">
                    위젯 추가
                </div>
            </div>
        </button>

<script>
    window.addEventListener('DOMContentLoaded',function(e){
        let mainContent = document.querySelector('main.content');
        jiny.OffCanvas = new JinyOffCanvas(mainContent);
    });

    document.querySelector('#offcanvas').addEventListener('click',function(e){
        jiny.OffCanvas.position('right').load("/api/pages/widgets");
    });
</script>





    </x-theme-layout>
</x-theme>
