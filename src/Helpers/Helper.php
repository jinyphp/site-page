<?php
use \Jiny\Html\CTag;
use Illuminate\Support\Facades\Request;

/**
 * DB에서 슬라이더 정보를 읽어 옵니다.
 */
function site_slider($code)
{
    $row = DB::table("site_sliders")->where('code', $code)->first();
    if($row) {
        $slider = [];
        foreach($row as $k => $v) {
            $slider[$k] = $v;
        }

        $slider['items'] = [];

        $imgs = DB::table("site_slider_images")
            ->where('code', $row->code)
            ->get();
        if($imgs) {
            foreach($imgs as $item) {
                $img = [];

                foreach($item as $k => $v) {
                    $img[$k] = $v;
                }

                $slider['items'] []= $img;
            }
        }

        return $slider;
    }
}

function widgetList($type=null)
{
    if($type) {
        // type 방법으로 연결
        // ex) database
        return [];
    }

    $path = __DIR__."/../../widgets".DIRECTORY_SEPARATOR."raws.json";
    return json_file_decode($path)['items'];
}

function widgetTemplates($type=null)
{
    $db = DB::table('site_widgets');
    if($type) {
        $db->where('type', $type);
    }
    $rows = $db->get();
    //$rows = DB::table('site_widgets')->get();
    $widgets = [];
    foreach($rows as $row) {
        $temp = [];
        foreach($row as $key => $value) {
            $temp[$key] = $value;
        }
        $widgets[$row->id] = $temp;
    }

    return $widgets;

    $path = resource_path("templates");
    $path .= DIRECTORY_SEPARATOR."widgets.json";
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

if (!function_exists('clean')) {
    function clean($content) {
        return strip_tags($content, '<p><br><strong><em><ul><li><ol><h1><h2><h3><h4><h5><h6><blockquote><code><pre>');
    }
}
