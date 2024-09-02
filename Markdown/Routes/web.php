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
Route::prefix('markdown')->group(function() {
    Route::get('/', 'MarkdownController@index');
});
*/


/**
 * markdown pages
 */
use Modules\Markdown\Http\Controllers\Admin\MarkdownFiles;
Route::middleware(['web'])
    ->name('admin.pages.')
    ->prefix('/admin/pages')->group(function () {

    // 마크다운 파일 관리
    Route::get('markdown', [MarkdownFiles::class, "index"]);

});
