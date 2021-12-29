<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

if(!function_exists("siteRoute")) {
    function siteRoute($uri) {
        $row = DB::table('site_route')
        ->where('enable',true)
        ->where('route',$_SERVER['PATH_INFO'])->first();
        return $row;
    }
}


// 페이지 라우트 검사.
if(isset($_SERVER['PATH_INFO'])) {
    if($row = siteRoute($_SERVER['PATH_INFO'])) {
        $uris = explode('/', $_SERVER['PATH_INFO']);

        //livewire 통신은 제외
        if($uris[1] != "livewire") {
            Route::middleware(['web'])
                ->name( str_replace("/",".",$_SERVER['PATH_INFO']).".")
                ->group(function () use ($row){
                    Route::get($_SERVER['PATH_INFO'],[
                        Jiny\Pages\Http\Controllers\PageView::class,
                        "index"
                    ]);
                });

        }
    }
}




/**
 * 마크다운 페이지 라우트
 * resource/docs 폴더를 스켄하여 페이지를 출력합니다.
 */
/*
$prefix = "docs";
Route::middleware(['web'])
    ->get('/'.$prefix.'/{slug1?}/{slug2?}/{slug3?}/{slug4?}/{slug5?}/{slug6?}/{slug7?}/{slug8?}/{slug9?}',
    [\Jiny\Pages\Http\PageController::class,"index"]);


// reource 페이지...
Route::middleware(['web'])->group(function(){

    // 테마지정
    xTheme()->setTheme("admin.sidebar2");

    Route::get('/pages/{slug1?}/{slug2?}/{slug3?}/{slug4?}/{slug5?}/{slug6?}/{slug7?}/{slug8?}/{slug9?}',[
        Jiny\Pages\Http\Controllers\PageView::class,
        "index"
    ]);
});
*/


/**
 * 관리자 페이지
 */
use Jiny\Pages\Http\Controllers\AdminPageTrans;
Route::middleware(['web'])
    ->name('admin.pages.')
    ->prefix('/admin/pages')->group(function () {

        Route::resource('route', \Jiny\Pages\Http\Controllers\Admin\RouteController::class);

        Route::resource('posts', \Jiny\Pages\Http\Controllers\Admin\PostsController::class);

        Route::resource('files', \Jiny\Pages\Http\Controllers\Admin\FilesController::class);

        ## 설정
        Route::resource('setting', \Jiny\Pages\Http\Controllers\Admin\SettingController::class);

        Route::resource('/trans', AdminPageTrans::class);


    });
