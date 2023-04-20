<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BossmanFilamentApp\Http\Controllers\MainController::class,'index'])->name('index');
Route::get('/view-category-posts/{slug}', [\BossmanFilamentApp\Http\Controllers\MainController::class, 'view_category_posts'])->name('category.posts.index');


Route::group(['prefix' => 'content', 'as' => 'content.'], function () {
    Route::get('/view/{slug}', [BossmanFilamentApp\Http\Controllers\ContentObjectController::class, 'view'])->name('view');
    Route::get('/list/{slug}', [BossmanFilamentApp\Http\Controllers\ContentObjectController::class, 'list'])->name('list');
    Route::get('/archive/{view}/{parent_key}/{date}', [BossmanFilamentApp\Http\Controllers\ContentObjectController::class, 'archive'])->name('archive');
    Route::post('/submit-form', [BossmanFilamentApp\Http\Controllers\FormsController::class, 'submitForm'])->name('form.submit');
});


