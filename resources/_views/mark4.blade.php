
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


        <!-- widget 선택 및 사이즈 조정 -->
        <script>

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
                // 위젯 선텍
                /////el.addEventListener('click', widgetResizeClickEvent);
            });

            function widgetResizeClickEvent(e) {
                e.preventDefault();

                    let target = e.target;
                    target = findWidgetElement(target);



                    // 선택된 경우
                    if(target) {
                        // 이전 선택값 해제
                        if(widgets.selected) {
                            widgets.selected.classList.remove('selected');
                            /////////removeResizer(widgets.selected);
                        }

                        // 선택 재지정
                        target.classList.add('selected');
                        widgets.selected = target;

                        // --- resizer 등록 ---
                        ///////addResizer(target);

                        // 사이드판넬 설정값 표시
                        /*
                        console.log(target);
                        let url = "/api/pages/pannel/section/" + target.dataset['id'];
                        ajaxGet(url, function(data){
                            offSideRight.innerHTML = data;
                            let form = offSideRight.querySelector('form');

                            ajaxSubmit(form, function(json){
                                console.log(json);
                            });
                        });
                        */
                    }
            }

            /*
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

            */



        </script>

        <script>
            // 키 이벤트
            document.addEventListener('keydown', function(e){
                e.preventDefault();
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
                    console.log(e);
                    alert("admin mode");
                }
            });
        </script>

        <!-- widget Popup 수정 -->
        <!-- Include stylesheet -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


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

















        <div >


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
