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
Route::prefix('fallback')->group(function() {
    Route::get('/', 'FallbackController@index');
});
*/


/** ----- ----- ----- ----- -----
 * 404 page 처리
 */
// smart 404 Page
Route::fallback(function () {

    // blade.php 파일이 있는 경우 찾아서 출력함
    $filename = str_replace('/','.',$_SERVER['PATH_INFO']);
    $filename = ltrim($filename,".");
    if (view()->exists($filename))
    {
        return view($filename);
    } else if (view()->exists($filename.".index"))
    {
        return view($filename.".index");
    }


    return view("fallback::errors.404");
})->middleware('web');


use Modules\Fallback\API\Controllers\Upload404;
Route::middleware(['web'])
->group(function(){
    Route::post('/api/upload/404', [Upload404::class,"dropzone"]);
});

