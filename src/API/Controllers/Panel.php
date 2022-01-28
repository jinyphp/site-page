<?php

namespace Jiny\Pages\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Panel extends Controller
{


    public function __construct()
    {

    }

    public function section(Request $requet)
    {
        $id = $requet->id;
        $row = DB::table("jiny_pages_content")->where('id', $id)->first();
        //dump($row->padding);
        return view('jinypage::pannel.section',[
            'id'=>$id,
            'row'=>$row
        ]);
    }

    public function sectionUpdate(Request $requet)
    {
        $res['update'] = "update";
        $res['post'] = $_POST;

        $id = $_POST['_id'];

        $width = $_POST['width'] > 0 ? $_POST['width'] : null;
        $height = $_POST['height'] > 0 ? $_POST['height'] : null;
        $margin = $_POST['margin'] > 0 ? $_POST['margin'] : null;
        $padding = $_POST['padding'] > 0 ? $_POST['padding'] : null;

        DB::table("jiny_pages_content")->where('id', $id)->update([
            'width' => $width,
            'height' => $height,
            'margin' => $margin,
            'padding' => $padding
        ]);


        return response()->json($res);

        //return "section update...";
        //return view('jinypage::pannel.section');
    }



}
