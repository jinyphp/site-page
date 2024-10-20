<div>
    <style>
        .html-content *:focus {
            outline: none;
        }

        .html-content .editable {
            background-color: #f3f4f6; /* gray-100 배경색 */
            cursor: text;
        }
    </style>

    <div id="html-content" class="html-content">
        {!! $html !!}
    </div>

    <button id="save-content" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">저장</button>

    @csrf

    <script>
        // Livewire의 $slot 값을 JavaScript 변수로 전달
        var currentSlot = @json($slot);

        document.addEventListener('DOMContentLoaded', () => {
            const htmlContent = document.querySelector('.html-content');
            const saveButton = document.getElementById('save-content');
            let currentEditableElement = null;

            function makeEditable(element) {
                element.contentEditable = 'true';
                element.classList.add('editable');
                currentEditableElement = element;
            }

            function removeEditable() {
                if (currentEditableElement) {
                    currentEditableElement.contentEditable = 'false';
                    currentEditableElement.classList.remove('editable');
                    currentEditableElement = null;
                }
            }

            htmlContent.addEventListener('click', (event) => {
                const target = event.target;

                // 이미 편집 중인 요소가 있다면 편집 모드 해제
                if (currentEditableElement && currentEditableElement !== target) {
                    removeEditable();
                }

                // 클릭된 요소가 텍스트 노드의 부모인 경우
                if (target.childNodes.length === 1 && target.childNodes[0].nodeType === Node.TEXT_NODE) {
                    makeEditable(target);
                }
                // 클릭된 요소가 텍스트 노드인 경우
                else if (target.nodeType === Node.TEXT_NODE) {
                    const span = document.createElement('span');
                    const parent = target.parentNode;
                    parent.insertBefore(span, target);
                    span.appendChild(target);
                    makeEditable(span);
                }
                // 그 외의 경우 (이미 태그로 감싸진 요소)
                else {
                    makeEditable(target);
                }
            });

            saveButton.addEventListener('click', () => {
                removeEditable();

                // 임시 컨테이너 생성
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = htmlContent.innerHTML;

                // 불필요한 span 태그 제거
                tempContainer.querySelectorAll('span').forEach(span => {
                    if (span.childNodes.length === 1 && span.childNodes[0].nodeType === Node.TEXT_NODE) {
                        span.replaceWith(span.textContent);
                    }
                });

                // contentEditable 관련 속성 제거
                tempContainer.querySelectorAll('*').forEach(element => {
                    element.removeAttribute('contenteditable');
                    element.classList.remove('editable');
                });

                const content = tempContainer.innerHTML;

                // 현재 페이지의 URI 가져오기
                const currentUri = window.location.pathname;

                // CSRF 토큰 가져오기
                const csrfToken = document.querySelector('input[name="_token"]').value;

                // AJAX 요청 보내기
                fetch('/api/save-content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        content: content,
                        uri: currentUri,
                        slot: currentSlot
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('내용이 성공적으로 저장되었습니다.');
                        console.log('서버 응답:', data);
                    } else {
                        alert('저장 중 오류가 발생했습니다.');
                        console.error('서버 응답:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('저장 중 오류가 발생했습니다.');
                });
            });
        });
    </script>
</div>
