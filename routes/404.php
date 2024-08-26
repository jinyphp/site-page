<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/** ----- ----- ----- ----- -----
 * 404 page 처리
 */
// fallback 모듈이 설치되어 있지 않은경우에만,
// fallback 동작 실행 (모듈 중복코드 방지)
if(!function_exists("module_fallback")) {

    Route::fallback(function () {
        if(isset($_SERVER['PATH_INFO'])) {
            // url을 기반으로, 리소스명을 검출
            $uri = $_SERVER['PATH_INFO'];
        } else {
            $uri = "/";
        }
        $filename = str_replace('/','.',$uri);
        $filename = ltrim($filename,".");


        // resources/views/pages 에서 검출
        $filename = "pages.".$filename;

        $path = resource_path("views".DIRECTORY_SEPARATOR."pages").DIRECTORY_SEPARATOR;
        $path .= str_replace("/",DIRECTORY_SEPARATOR,$uri);

        // .blade 파일이 있는 경우
        if (view()->exists($filename)) {
            return view($filename);
        } else
        // markdown 파일이 있는 경우
        if(file_exists($path.".md")) {
            $markdown = file_get_contents($path.".md");

            // 마크다운 변환
            $Parsedown = new Parsedown();
            $html = $Parsedown->text($markdown);

            return $html;
        } else
        // 경로가 폴더인경우, 안의 index를 출력
        if (view()->exists($filename.".index")) {
            return view($filename.".index");
        } else
        if(file_exists($path.DIRECTORY_SEPARATOR."index.md")) {
            $markdown = file_get_contents($path.DIRECTORY_SEPARATOR."index.md");

            // 마크다운 변환
            $Parsedown = new Parsedown();
            $html = $Parsedown->text($markdown);

            return $html;
        }

        // 페이지 없음 404 출력
        if (view()->exists("jiny-site-page::errors.404")) {
            return view("jiny-site-page::errors.404");
        }

    })->middleware('web');
}


/*
use Modules\Fallback\API\Controllers\Upload404;
Route::middleware(['web'])
->group(function(){
    Route::post('/api/upload/404', [Upload404::class,"dropzone"]);
});
*/


