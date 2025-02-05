{{-- ajax drag move --}}
<form id="dragmove" style="display: none;">
    @csrf
</form>
<script>
    let designMode = false;
    document.addEventListener('livewire:init', () => {
        Livewire.on('design-mode', (event) => {
            console.log("design-mode!!!");

            // toggle
            designMode = !designMode;

            widgetDrag();

        });
    });

    function widgetDrag() {
        // 드레그 위젯 요소 추출
        const widgets = document.querySelectorAll('#widget');
        console.log(widgets);

        if (designMode) {
            widgets.forEach(element => {
                // element.style.paddingTop = "4px";
                // element.style.paddingBottom = "4px";
                element.classList.add("bg-green-100");
                element.classList.add("p-4");

                element.draggable = true;
            });
        } else {
            widgets.forEach(element => {
                element.classList.remove("bg-green-100");
                element.classList.remove("p-4");
                // element.style.paddingTop = "";
                // element.style.paddingBottom = "";

                element.draggable = false;
            });
        }

        let dragSource;
        let dragTarget;
        widgets.forEach(element => {

            //element.style.paddingTop = "4px";
            //element.style.paddingBottom = "4px";
            element.classList.add("bg-green-100");

            element.addEventListener('dragstart', function(e) {
                //e.preventDefault();
                console.log("drag start...");

                dragSource = e.target;
                dragSource.classList.add("bg-green-300");
                console.log(dragSource);
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

                    //target.classList.remove("bg-green-100");
                    //target.classList.add("bg-pink-200");

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
                    //target.classList.remove("dragover");
                    //target.classList.remove("bg-gray-100");

                    dragTarget = null;
                }
            });

            element.addEventListener('drop', function(e) {
                e.preventDefault();
                console.log('drop');


                if (dragSource.dataset.pos > dragTarget.dataset.pos) {
                    dragTarget.parentElement.insertBefore(dragSource, dragTarget);

                    let next; // 하위요소를 하나씩 이동
                    next = dragSource.nextElementSibling;
                    while (next && next.id == "widget") {
                        console.log("source next")
                        console.log(next);
                        dragTarget.parentElement.insertBefore(next
                            .nextElementSibling, next);
                        next = next.nextElementSibling;
                    }

                } else {
                    // 요소이동
                    dragTarget.parentElement.insertBefore(dragSource, dragTarget
                        .nextElementSibling);

                    let prev; // 상위요소를 하나씩 이동
                    prev = dragSource.previousElementSibling;
                    while (prev && prev.id == "widget") {
                        console.log(prev);
                        dragTarget.parentElement.insertBefore(prev, prev
                            .previousElementSibling);
                        prev = prev.previousElementSibling;
                    }
                }

                // ajax 통신
                updateDragPos();


            });

            element.addEventListener('dragend', function(e) {
                e.preventDefault();
                console.log('dragend');

                e.target.classList.remove("bg-green-300");
                //unselectWidget(e.target);

                if (dragTarget) {
                    dragTarget.classList.remove("dragover");
                    dragTarget.classList.remove("bg-gray-100");
                }

            });
        });
    }

    function updateDragPos() {
        console.log("ajax...");

        // 섹션 변경 순서를 저장 pos 정보를 저장
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/api/pages/drag/pos");
        let data = new FormData();

        data.append('_uri', location.href);

        let token = document.querySelector('input[name=_token]').value;
        data.append('_token', token);
        console.log(token);

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
</script>
