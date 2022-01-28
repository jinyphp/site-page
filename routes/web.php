<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * pages 관리자 페이지
 */
use Jiny\Pages\Http\Controllers\AdminPageTrans;
use Jiny\Filesystem\Http\Controllers\Admin\FileController;
Route::middleware(['web'])
    ->name('admin.pages.')
    ->prefix('/admin/pages')->group(function () {

        // post 작성글 목록
        Route::resource('posts', \Jiny\Pages\Http\Controllers\Admin\PostsController::class);

        // 마크다운 파일 관리
        Route::get('markdown', [FileController::class, "index"]);

        // blade 파일 관리
        Route::get('blade', [FileController::class, "index"]);

        ## 설정
        Route::resource('setting', \Jiny\Pages\Http\Controllers\Admin\SettingController::class);

        Route::resource('routes',\Jiny\Admin\Http\Controllers\Jiny\RouteController::class);

        //Route::resource('route', \Jiny\Pages\Http\Controllers\Admin\RouteController::class);
        Route::resource('files', \Jiny\Pages\Http\Controllers\Admin\FilesController::class);
        Route::resource('/trans', AdminPageTrans::class);
    });


use Jiny\Pages\API\Controllers\Section;
use Jiny\Pages\API\Controllers\Panel;
Route::middleware(['web'])
    ->group(function(){
        Route::post('/api/pages/delete',[Section::class,"delete"]);
        Route::post('/api/pages/pos',[Section::class,"pos"]);
        Route::post('/api/pages/move',[Section::class,"move"]);
        Route::post('/api/pages/resize',[Section::class,"resize"]);

        Route::get('/api/pages/pannel/section/{id}',[Panel::class,"section"]);
        Route::post('/api/pages/pannel/section',[Panel::class,"sectionUpdate"]);
    });
