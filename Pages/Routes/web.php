<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::prefix('pages')->group(function() {
    Route::get('/', 'PagesController@index');
});
*/


/**
 * pages 관리자 페이지
 */
use Jiny\Pages\Http\Controllers\AdminPageTrans;
use Modules\Pages\Http\Controllers\Admin\MarkdownFiles;
Route::middleware(['web'])
    ->name('admin.pages.')
    ->prefix('/admin/pages')->group(function () {

        // post 작성글 목록
        Route::resource('posts', \Modules\Pages\Http\Controllers\Admin\PostsController::class);

        ## 설정
        Route::resource('setting', \Modules\Pages\Http\Controllers\Admin\SettingController::class);

        Route::resource('files', \Modules\Pages\Http\Controllers\Admin\FilesController::class);






        // blade 파일 관리
        Route::get('blade', [MarkdownFiles::class, "index"]);
        //Route::resource('route', \Jiny\Pages\Http\Controllers\Admin\RouteController::class);

        Route::resource('routes',\Jiny\Admin\Http\Controllers\Jiny\RouteController::class);
        Route::resource('/trans', AdminPageTrans::class);
    });



use Modules\Pages\API\Controllers\Pages;
use Modules\Pages\API\Controllers\Panel;
use Modules\Pages\API\Controllers\UiWidget;
use Modules\Pages\API\Controllers\UiModal;
use Modules\Pages\API\Controllers\Drag;
use Modules\Pages\API\Controllers\Widgets;
Route::middleware(['web'])
    ->group(function(){
        // 페이지 드래그 section, widget 이동 및 삭제
        Route::post('/api/pages/delete',[Pages::class,"delete"]);
        Route::post('/api/pages/drag/widget',[Pages::class,"widget"]);
        Route::post('/api/pages/drag/section',[Pages::class,"section"]);

        // 요소 사이즈 조정
        Route::post('/api/pages/resize',[Pages::class,"resize"]);

        //
        Route::get('/api/pages/block/title',[Pages::class,"block_title"]);
        Route::post('/api/pages/block/save',[Pages::class,"blocksave"]);


        //Route::get('/api/pages/pannel/section/{id}',[Panel::class,"section"]);
        //Route::post('/api/pages/pannel/section',[Panel::class,"sectionUpdate"]);

        Route::get('/api/pages/ui/widget/{id}',[UiWidget::class,"index"]);
        Route::post('/api/pages/ui/Widget',[UiWidget::class,"update"]);

        Route::get('/api/pages/widgets',[Widgets::class, "index"]);

        Route::get('/api/modal/confirm',[UiModal::class, "confirm"]);
    });

