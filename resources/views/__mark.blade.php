{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>


        <div>


            <style>
                .jiny.tree ul {
                    padding:0;
                    margin-left:30px;
                    /*
                    padding:0 0 0 0;
                    flex-grow: 1;
                    */

                    margin-top: -1px;
                    margin-bottom: -1px;
                }

                .jiny.tree > ul {
                    margin-left:0;
                }

                .jiny.tree li {
                    /*display:flex;*/
                    padding: 5px 0 5px 5px;

                    border-left-color: gray;
                    border-left-width: 1px;

                    border-bottom-color: #cccccc;
                    border-bottom-width: 1px;
                    border-bottom-style: dashed;

                    /*
                    border-top-color: #cccccc;
                    border-top-width: 1px;
                    border-top-style: solid;
                    */
                }

                .jiny.tree ul > li:first-child {
                    /* border-bottom:0; */
                }

                .jiny.tree ul > li:last-child {
                    /* border-bottom:0; */
                }

                .jiny.tree li > .title {
                    padding: 5px;
                    min-width:100px;
                }

                .jiny.tree .title-right {
                    padding: 0 5px;
                }

                .jiny.tree li > div:hover {
                    background: #def2fb;
                }

                .jiny.tree .title.target {
                    background: #def2fb;
                }

                .jiny.tree .btn-create {
                    padding: 10px;
                }



                /* 소스 드래깅 */
                .jiny.tree li.dragging {
                    background: #eeeeee;
                    border: 1px solid #cccccc;
                    opacity: 0.7;
                }

                .draggable-mirror {
                    background-color: yellow;
                    width: 950px;
                    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
                }
            </style>


            <hr>
            <form>
                @csrf
                <div class="jiny tree">
                    {!! (new \Jiny\Pages\Http\Controllers\Tree($slot))->make() !!}
                </div>
            </form>




        </div>

    {{-- tree drag move --}}
    <script>
        function findTagsParent(el, tag) {
            for(let i=0; i<tag.length;i++) {
                tag[i] = tag[i].toUpperCase();
            }
            let status = true;
            //console.log("찾기....")
            //console.log(el)
            while(status) {
                for(i=0;i<tag.length;i++) {
                    if(el.tagName == tag[i]) status = false;
                }
                if(status == true) {
                    if(el) {
                        if(el.classList) {
                            //console.log(el);
                            if(el.classList.contains('root')) return null;
                        } else {
                            //console.log("class list가 없어요")
                        }
                    } else {
                        //console.log("el 이 없어요")
                    }

                    el = el.parentElement;
                    //sif(el.tagName == "FORM") return null;
                }
            }
            return el;
        }

        const jinyTree = document.querySelector('.jiny.tree');
        //jinyTree.setAttribute('draggable', "true");


        let dragStart = null;
        let dragTarget = null;
        let dragOver = null;

        jinyTree.addEventListener('dragstart', (e) => {
            // node만 선택할 수 있음
            let target = findTagsParent(e.target, ['li']);
            if(target.classList.contains('drag-node')) {
                console.log("start=노드선택");
                dragStart = target;
                dragStart.classList.add('dragging'); // 드래깅 상태 클래스 표시
                //console.log(dragStart);
            } else {
                dragStart = null; // 초기화
            }
        });

        jinyTree.addEventListener('dragover', e => {
            e.preventDefault();
        });
        jinyTree.addEventListener('dragenter', e => {
            e.preventDefault();
        });
        jinyTree.addEventListener('dragleave', e => {
            e.preventDefault();
        });
        jinyTree.addEventListener('drop', (e) => {
            e.preventDefault();
            if(dragStart) {
                console.log("drop");
                let status = false;
                let target = findTagsParent(e.target, ['li','ul']);

                if(target.tagName == "UL") {
                    // 1. ul선택
                    console.log("ul은 대상이 될 수 없습니다.");
                }  else if(target.tagName == "LI") {
                    // 2. li선택
                    dragMoveToLi(dragStart, target);
                    status = true;
                }

                // 드래그 동작이 성공인 경우, ajax를 통하여 서버에 저장
                if(status) {
                    // 서버로 정보 전송
                    ajaxMenuDropSync();
                }

            } else {
                console.log("drag가 선택되어 있지 않습니다.");
            }
        });
        jinyTree.addEventListener('dragend', (e) => {
            console.log("dragend");
            if(dragStart) {
                if(dragStart.classList.contains('dragging')) {
                    dragStart.classList.remove('dragging');
                    dragStart = null;
                }
            }
        });

        /* 이동하고자 하는 대상의 자기 자신의 자식들인지 체크함 */
        function checkDropChild(dragTarget) {
            // drag-node가 아닌 cteate노드는 level값이 없어 계층 확인이 어려움.
            let target;
            if(!dragTarget.classList.contains('drag-node')) {
                // 실제 drag-node 찾기
                target = findTagsParent(dragTarget.parentElement, ['li']);
                //console.log("노드찾기")
                //console.log(target)
            } else {
                //console.log("노드검사")
                target = dragTarget; //findTagsParent(dragTarget, ['li']);
            }

            console.log("동일계층 checking....")
            //let parent = findTagsParent(dragTarget, ['li']);
            //console.log("source")
            //console.log(dragStart);
            while(target.dataset['id'] != dragStart.dataset['id']) {

                if(parseInt(target.dataset['level']) == 1) return false;
                //console.log("level=" + target.dataset['level']);
                //console.log("target")
                //console.log(target)
                //console.log("target id=" + target.dataset['id']);
                //console.log("start id=" +  dragStart.dataset['id']);


                target = findTagsParent(target.parentElement, ['li']);
            }

            //console.log("target id=" + target.dataset['id'] + ", start id=" + dragStart.dataset['id'])
            return true;
        }

        function dragMoveToLi(dragStart, dragTarget) {
            // 검사1.
            if(dragStart == dragTarget) {
                console.log("자기 자신은 이동할 수 없습니다.");
                return;
            }
            // 검사2,
            if(checkDropChild(dragTarget)) {
                console.log("동일계층 하위로 이동 할 수 없습니다.")
                return;
            }

            // 드래그 노드 (이동, 맞교환, 추가동작)
            if(dragTarget.classList.contains('drag-node')) {
                console.log("Li 노드에 드래그 되었습니다.");
                dragTargetToNode(dragStart, dragTarget);
            } else
            // 추가버튼 drop (추가동작)
            if(dragTarget.classList.contains('create')) {
                console.log("추가 버튼에 드래그 되었습니다.");
                dragTargetToCreate(dragStart, dragTarget);
            }
        }

        function dragTargetToNode(dragStart, dragTarget) {

            // 부모노드 검사
            // 부모가 같으면 노간 순서를 교환합니다.
            if(dragTarget.parentElement == dragStart.parentElement) {
                console.log("노드 순서 맞교환");
                targetNext = dragTarget.nextElementSibling;
                srcNext = dragStart.nextElementSibling;
                dragTarget.parentElement.insertBefore(dragStart, targetNext);
                dragStart.parentElement.insertBefore(dragTarget, srcNext);
            }

            // 부모 노드가 다름
            else {
                console.log("다른 노드로 이동합니다.");
                targetNext = dragTarget.nextElementSibling; // 대상 삽입위치 지정

                // 상위 노드값을 통하여
                // 이동노드의 ref, level 갑을 변경합니다.
                let parent = findTagsParent(dragTarget.parentElement, ['li']); //dragTarget.parentElement;
                //console.log(parent);
                if(parent) {
                    dragStart.dataset.ref = parent.dataset['id']; //부모 참조값 변경
                    dragStart.dataset.level = parseInt(parent.dataset['level']) + 1; // data 속성변경
                } else {
                    // root 노드로 이동
                    console.log("root 노드");
                    dragStart.dataset.ref = 0;
                    dragStart.dataset.level = 1;
                }


                // 기존 노드를 새로운 노드로 이동합니다.
                dragTarget.parentElement.insertBefore(dragStart, targetNext);

                //
            }

            //console.log(dragStart)

        }

        /* 생성 버튼 노트로 drop한 경우 처리*/
        function dragTargetToCreate(dragStart, dragTarget) {

            //console.log(dragTarget);


            // 동일 노드 검사
            // 예) 같은노드에서 +버튼으로 드래그하는 경
            let parentTarget = findTagsParent(dragTarget.parentElement, ['li']);
            let parentStart = findTagsParent(dragStart.parentElement, ['li']);
            if(parentTarget == parentStart) {
                console.log("동일한 부모노드 입니다. 서브등록을 취소합니다.");
            } else
            // 서브등록
            {
                //console.log("서브 노드가 추가됩니다.");

                console.log("선택한 노드를 새로운 노드에 이동합니다.");
                dragStart.dataset.ref = parentTarget.dataset['id'];
                dragStart.dataset.level = parseInt(parentTarget.dataset['level']) + 1;  // data 속성변경

                // ul에 자식 추가
                dragTarget.parentElement.appendChild(dragStart);
                //console.log(dragStart)
            }
        }




        function ajaxMenuDropSync() {
            // 변경된 노드를 다시 확인
            let node = jinyTree.querySelectorAll('.jiny.tree > ul > li.drag-node');

            let aaa=[];
            function __treepos(node) {
                //let pos = [];
                node.forEach(el => {
                    //console.log(el);
                    /*
                    id = el.dataset['id'];
                    pos[id] = {
                        'id':id,
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos'],
                        'sub':null
                    };
                    aaa.push(pos[id]);
                    */

                    //pos = {;
                    aaa.push({
                        'id':el.dataset['id'],
                        'level':el.dataset['level'],
                        'ref':el.dataset['ref'],
                        'pos':el.dataset['pos']
                    });

                    if(sub = el.querySelectorAll('[data-ref="'+el.dataset['id']+'"].drag-node')) {
                        //pos[id].sub =
                        __treepos(sub);
                    }

                });

                //return pos;
            }

             __treepos(node);


            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/menu/pos");

            let data = new FormData();
            let token = document.querySelector('input[name=_token]').value;
            data.append('_token', token);

            for(let i=0; i < aaa.length; i++) {
                data.append("menu[" + aaa[i].id + "][ref]", aaa[i].ref);
                data.append("menu[" + aaa[i].id + "][level]", aaa[i].level);
                data.append("menu[" + aaa[i].id + "][pos]", i+1);
            }

            xhr.onload = function() {
                var data = JSON.parse(this.responseText);
                console.log(data);
            }

            xhr.send(data);
        }

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
