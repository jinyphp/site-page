<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


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
