<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::middleware(['web'])
->name('admin.pages.')
->prefix('/admin/pages')->group(function () {

    // drage page design
    Route::get('design', [
        Jiny\Site\Page\Http\Controllers\PageDesignMode::class,
        "index"]);

});

use Jiny\Site\Page\API\Controllers\Upload404;
use Jiny\Site\Page\API\Controllers\PageWidgetDrag;
Route::middleware(['web'])
->group(function(){
    Route::post('/api/upload/404', [Upload404::class,"dropzone"]);

    Route::post('/api/pages/drag/pos',[PageWidgetDrag::class,"pos"]);
});




// use Jiny\Pages\API\Controllers\Panel;
// use Jiny\Pages\API\Controllers\UiWidget;
// Route::middleware(['web'])
//     ->group(function(){
//         Route::post('/api/pages/delete',[Section::class,"delete"]);

//         Route::post('/api/pages/move',[Section::class,"move"]);
//         Route::post('/api/pages/resize',[Section::class,"resize"]);

//         Route::get('/api/pages/pannel/section/{id}',[Panel::class,"section"]);
//         Route::post('/api/pages/pannel/section',[Panel::class,"sectionUpdate"]);

//         Route::get('/api/pages/ui/widget/{id}',[UiWidget::class,"index"]);
//         Route::post('/api/pages/ui/Widget',[UiWidget::class,"update"]);
//     });
