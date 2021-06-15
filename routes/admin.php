<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LanguagesController;
use App\Http\Controllers\Admin\MainCategoriesController;
use App\Http\Controllers\Admin\VendorsController;
use App\Http\Controllers\Admin\SubCategoriesController;







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


Route::group(['namespace'=>'Admin','middleware' => 'auth:admin'], function(){
    Route::get('/', 'DashboardController@index') -> name('admin.dashboard');
});
############################## begin route of languages ####################

Route::prefix('languages')
    ->group(function(){
        Route::get('/', [LanguagesController::class, 'index'])->name('admin.languages');
        Route::get('create', [LanguagesController::class, 'create'])->name('admin.languages.create');
        Route::post('store', [LanguagesController::class, 'store'])->name('admin.languages.store');
        Route::get('edit/{id}', [LanguagesController::class, 'edit'])->name('admin.languages.edit');
        Route::post('update/{id}', [LanguagesController::class, 'update'])->name('admin.languages.update');
        Route::get('delete/{id}', [LanguagesController::class, 'destroy'])->name('admin.languages.delete');
        Route::get('changeStatus/{id}', [LanguagesController::class, 'changeStatus'])->name('admin.languages.status');



        //Other languages routes here
    });
############################## end route of languages ######################

############################## begin route of MainCategories ####################

Route::prefix('main_category')
    ->group(function(){
        Route::get('/', [MainCategoriesController::class, 'index'])->name('admin.maincategories');
        Route::get('create', [MainCategoriesController::class, 'create'])->name('admin.maincategories.create');
        Route::post('store', [MainCategoriesController::class, 'store'])->name('admin.maincategories.store');
        Route::get('edit/{id}', [MainCategoriesController::class, 'edit'])->name('admin.maincategories.edit');
        Route::post('update/{id}', [MainCategoriesController::class, 'update'])->name('admin.maincategories.update');
        Route::get('delete/{id}', [MainCategoriesController::class, 'destroy'])->name('admin.maincategories.delete');
        Route::get('changeStatus/{id}', [MainCategoriesController::class, 'changeStatus'])->name('admin.maincategories.status');



        //Other languages routes here
    });
############################## end route of MainCategories ######################

############################## begin route of MainCategories ####################

Route::prefix('sub_category')
    ->group(function(){
        Route::get('/', [SubCategoriesController::class, 'index'])->name('admin.subcategories');
        Route::get('create', [SubCategoriesController::class, 'create'])->name('admin.subcategories.create');
        Route::post('store', [SubCategoriesController::class, 'store'])->name('admin.subcategories.store');
        Route::get('edit/{id}', [SubCategoriesController::class, 'edit'])->name('admin.subcategories.edit');
        Route::post('update/{id}', [SubCategoriesController::class, 'update'])->name('admin.subcategories.update');
        Route::get('delete/{id}', [SubCategoriesController::class, 'destroy'])->name('admin.subcategories.delete');
        Route::get('changeStatus/{id}', [SubCategoriesController::class, 'changeStatus'])->name('admin.subcategories.status');



        //Other languages routes here
    });
############################## end route of MainCategories ######################

############################## begin route of vendors ####################

Route::prefix('vendors')
    ->group(function(){
        Route::get('/', [VendorsController::class, 'index'])->name('admin.vendors');
        Route::get('create', [VendorsController::class, 'create'])->name('admin.vendors.create');
        Route::post('store', [VendorsController::class, 'store'])->name('admin.vendors.store');
        Route::get('edit/{id}', [VendorsController::class, 'edit'])->name('admin.vendors.edit');
        Route::post('update/{id}', [VendorsController::class, 'update'])->name('admin.vendors.update');
        Route::get('delete/{id}', [VendorsController::class, 'destroy'])->name('admin.vendors.delete');
        Route::get('changeStatus/{id}', [VendorsController::class, 'changeStatus'])->name('admin.vendors.status');



    });
############################## end route of vendors ######################


############################## test relation of categories ######################
/*Route::get('test_relation', function(){
   $cat= \App\Models\SubCategory::find(1);

   return $cat -> MainCategories;


});*/
############################## test relation of categories ######################





Route::group(['namespace'=>'Admin','middleware' => 'guest:admin'], function(){
    Route::get('login' ,'LoginController@getLogin')-> name('get.admin.login');
    Route::post('login' ,'LoginController@login') -> name('admin.login');

});
