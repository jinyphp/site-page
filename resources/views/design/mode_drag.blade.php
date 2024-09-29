<div>
    <button wire:click="edit">edit</button>

    {{-- {{ dd(sitePageWidgets()) }} --}}
    @foreach (sitePageWidgets() as $i => $widget)
        <div id="widget" data-pos="{{ $i }}" style="position: relative;">

            <div id="edit" style="position: absolute;top:4px;right:4px;z-index:50;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                    <path
                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                </svg>
            </div>

            <div>
                @livewire(
                    $widget['element'],
                    [
                        'widget' => $widget,
                    ],
                    $i
                )
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const widgets = document.querySelectorAll('#widget');
            console.log(widgets);

            const dropzone = document.getElementById('dropzone');
            let token = dropzone.querySelector('input[name=_token]').value;

            let dragSource;
            let dragTarget;

            widgets.forEach(element => {
                // 위젯 수정버튼
                element.querySelector('#edit').addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log("edit");

                    let target = e.target;
                    while (target && target.id !== "edit") {
                        target = target.parentElement;
                    }
                    console.log(target);
                    //unselectWidget(target);
                })

                element.addEventListener('click', function(e) {
                    e.preventDefault();

                    let target = e.target;
                    //console.log(target);
                    while (target && target.id !== "widget") {
                        if (target.id == "edit") return; // edit를 선택하는 경우 click 동작 제외
                        target = target.parentElement;
                    }
                    console.log(target);

                    selectActiveWidget(target);


                });

                console.log(element);


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

                    console.log(dragSource);
                    console.log(dragTarget);

                    if (dragSource.dataset.pos > dragTarget.dataset.pos) {
                        dragTarget.parentElement.insertBefore(dragSource, dragTarget);
                    } else {
                        dragTarget.parentElement.insertBefore(dragSource, dragTarget.nextSibling);
                    }

                    // ajax 통신
                    updateDragPos();

                    unselectWidget(dragSource);

                });

                element.addEventListener('dragend', function(e) {
                    e.preventDefault();
                    console.log('dragend');

                    e.target.classList.remove("bg-green-200");
                    unselectWidget(e.target);

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
                data.append('_token', token);
                data.append('_uri', location.href);

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


            function unselectWidget(target) {
                // 선택삭제
                target.classList.remove("bg-green-100");
                target.classList.remove("p-4");

                const firstChildDiv = target.querySelector('div:first-child');
                firstChildDiv.classList.remove("bg-blue-100");

                // 드레그 활성화
                target.setAttribute('draggable', "true");
            }

            function selectActiveWidget(target) {
                // 선체 선택 삭제
                removeActiveWidget();

                if (!target.classList.contains("bg-green-100")) {

                    target.classList.add("p-4");
                    target.classList.add("bg-green-100");

                    const firstChildDiv = target.querySelector('div:first-child');
                    firstChildDiv.classList.add("bg-blue-100");

                    // 드레그 활성화
                    target.setAttribute('draggable', "true");



                }
            }


            function removeActiveWidget() {
                widgets.forEach(element => {
                    // 드레그 비활성화
                    unselectWidget(element);
                });
            }
        });
    </script>

</div>