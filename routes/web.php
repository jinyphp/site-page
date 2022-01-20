<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * 관리자 페이지
 */
use Jiny\Pages\Http\Controllers\AdminPageTrans;
use Jiny\Filesystem\Http\Controllers\Admin\FileController;
Route::middleware(['web'])
    ->name('admin.pages.')
    ->prefix('/admin/pages')->group(function () {
        // post 작성글 출력
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
