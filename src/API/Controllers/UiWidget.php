<?php
namespace Jiny\Site\Page\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UiWidget extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $requet)
    {
        $id = $requet->id;
        $row = DB::table("jiny_pages_content")->where('id', $id)->first();
        //dump($row->padding);
        return view('jiny-site-page::ui.widget.widget',[
            'id'=>$id,
            'row'=>$row
        ]);
    }

    public function update(Request $requet)
    {
        $res['update'] = "update";
        $res['post'] = $_POST;

        $id = $_POST['_id'];
        $form = [];
        if($id) {

            $row = DB::table("jiny_pages_content")->where('id', $id)->first();
            if($row->type == "markdown" || $row->type == "html" || $row->type == "blade") {
                if($row->path) {
                    $filename = resource_path('pages').$row->path;

                } else {
                    $filename = resource_path('pages').$row->route;
                    if(!is_dir($filename)) mkdir($filename,775, true);
                    $filename = $filename."/".$id.".md";

                    $form['path'] = $row->route."/".$id.".md";
                }

                file_put_contents($filename, $_POST['content']);
            }

        }

        $form['width'] = $_POST['width'] > 0 ? $_POST['width'] : null;
        $form['height'] = $_POST['height'] > 0 ? $_POST['height'] : null;
        $form['margin'] = $_POST['margin'] > 0 ? $_POST['margin'] : null;
        $form['padding'] = $_POST['padding'] > 0 ? $_POST['padding'] : null;

        DB::table("jiny_pages_content")->where('id', $id)->update($form);


        return response()->json($res);
    }



}
