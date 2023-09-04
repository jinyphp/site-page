<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

dd("aaa");

Route::middleware(['web'])
->group(function(){
    Route::get('/hello', function(){
        return "hello";
    });
});



/** ----- ----- ----- ----- -----
 * 404 page 처리
 */
Route::fallback(function () {

    // blade.php 파일이 있는 경우 찾아서 출력함
    /*
    $filename = str_replace('/','.',$_SERVER['PATH_INFO']);
    $filename = ltrim($filename,".");
    if (view()->exists($filename))
    {
        return view($filename);

    } else if (view()->exists($filename.".index"))
    {
        return view($filename.".index");
    }
    */


    return view("jinypage::errors.404");
})->middleware('web');

/*
use Modules\Fallback\API\Controllers\Upload404;
Route::middleware(['web'])
->group(function(){
    Route::post('/api/upload/404', [Upload404::class,"dropzone"]);
});
*/


