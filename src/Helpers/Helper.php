<?php
use \Jiny\Html\CTag;
use Illuminate\Support\Facades\Request;

function widgetList($type=null)
{
    if($type) {
        // type 방법으로 연결
        // ex) database
        return [];
    }

    $path = __DIR__."/../../widgets".DIRECTORY_SEPARATOR."template.json";
    return json_file_decode($path)['items'];
}

function widgetTemplates()
{
    $path = resource_path("templates");
    $path .= DIRECTORY_SEPARATOR."template.json";
    return json_file_decode($path)['items'];
}

function sitePageWidgets($uri=null)
{
    if(!$uri) {
        $uri = "/".Request::path();
    }


    $path = resource_path('www');
    $slot = www_slot();

    $path .= DIRECTORY_SEPARATOR.$slot;
    $path .= str_replace('/', DIRECTORY_SEPARATOR, $uri);
    $path .= DIRECTORY_SEPARATOR."widgets.json";

    if(file_exists($path)) {
        $widgets = json_file_decode($path);
    } else {
        $widgets = [];
    }

    //dd($widgets);
    return $widgets;

    // return DB::table('site_page_widgets')->where('route', $uri)->get();
}

function xDirectory($tree)
{
    $ul = new CTag('ul', true);
    $_li = new CTag('li', true);
    $_a = new CTag('a',true);

    $icon_plus = xIcon($name="plus-circle-dotted", $type="bootstrap")->setClass("w-3 h-3");
    $icon_folder_open = xIcon($name="folder2-open", $type="bootstrap")->setClass("w-4 h-4 inline-block");

    foreach($tree as $item) {
        $li = clone $_li;

        if( isset($item['dir']) ) {
            $li->addItem($icon_folder_open);
        }

        if( isset($item['dir']) ) {
            $li->addItem(xSpan($item['name'])->addClass("px-2"));

            $link = (clone $_a)
                ->addItem($icon_plus)
                ->setAttribute('href', "javascript: void(0);");
            $link->setAttribute("wire:click", "$"."emit('create','".str_replace("\\","\\\\",$item['path'])."')");
            $li->addItem($link);
            $li->addItem( xDirectory($item['dir']));

        } else {
            // 파일
            $link = (clone $_a)
                ->addItem($item['name'])
                ->setAttribute('href', "javascript: void(0);");
            $link->addClass("ml-2");
            $link->setAttribute("wire:click", "$"."emit('edit','".str_replace("\\","\\\\",$item['path'])."')");
            $li->addItem($link);

        }

        $ul->addItem($li);
    }

    return $ul;
}

