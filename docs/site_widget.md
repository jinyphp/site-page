# Site Widget
사이트 빌더에서 사용되는 위젯 컴포넌트입니다.

## 내부 위젯 종류

### site-widget-markdown
widget Loop 에서 마크다운 기능을 처리할 수 있는 위젯입니다.

Actions json 설정에서 widgets 값을 참고하여 동작합니다.
```json
{
    "filename": "/aaa/ccc/9be53b54ee",
    "element": "site-widget-markdown",
    "name": "markdown",
    "key": "9be53b54ee",
    "route": "/aaa/ccc",
    "path": "9be53b54ee.md",
    "view": {
        "list": "jiny-widgets::markdown.list",
        "form": "jiny-widgets::markdown.form"
    },
    "pos": 0,
    "ref": 0,
    "level": 1
}
```

직접 위젯 컨포넌트를 지정할 수도 있습니다.
```php
@livewire($widget['element'],[
    'widget' => $widget,
    'widget_id' => $i],key($i))
```

### site-widget-html
widget Loop 에서 HTML 기능을 처리할 수 있는 위젯입니다.

### site-widget-blade
widget Loop 에서 Blade 기능을 처리할 수 있는 위젯입니다.

### site-widget-image
widget Loop 에서 이미지 기능을 처리할 수 있는 위젯입니다.

### site-widget-slider
widget Loop 에서 슬라이더 기능을 처리할 수 있는 위젯입니다.

## 외부 위젯
외부 위젯은 ui-widgets 에 정의되어 있는 것을 의미합니다. 또한, 외부 위젯은 각각의 다른 ui를 통하여 템플릿으로 등록하여 관리 할 수 있습니다.


