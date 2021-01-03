<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


  
Route::get('/',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@authLogin'));
Route::get('/login',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@authLogin'));
Route::get('/auth',array('as'=>'Sign in', 'uses' =>'SystemAuthController@authLogin'));
Route::get('/auth/login',array('as'=>'Sign in', 'uses' =>'SystemAuthController@authLogin'));
Route::post('auth/post/login',array('as'=>'Sign in' , 'uses' =>'SystemAuthController@authPostLogin'));
Route::post('auth/registration',array('as'=>'Registration' , 'uses' =>'SystemAuthController@authRegistration'));
Route::post('auth/post/new/password/',array('as'=>'New Password Submit' , 'uses' =>'SystemAuthController@authSystemNewPasswordPost'));
Route::get('auth/admin/logout/{email}',array('as'=>'Logout' , 'uses' =>'SystemAuthController@authLogout'));

Route::get('/logout',array('as'=>'Logout' , 'uses' =>'SystemAuthController@authLogout'));
Route::get('/logout/{name_slug}',array('as'=>'Logout' , 'uses' =>'SystemController@Logout'));

#ChangeUserStatus
Route::get('admin/change/user/status/{user_id}/{status}',array('as'=>'Change User Status' , 'uses' =>'AdminController@ChangeUserStatus'));


Route::get('auth/google',array('as'=>'Google' , 'uses' =>'GoogleLoginController@redirectToGoogle'));
Route::get('auth/google/callback',array('as'=>'Google' , 'uses' =>'GoogleLoginController@handleGoogleCallback'));


/*
#####################
## Admins Module
######################
*/
Route::group(['middleware' => ['admin_auth']], function () {

      Route::get('/dashboard',array('as'=>'Dashboard' , 'uses' =>'SystemAuthController@Dashboard'));

      Route::get('/admin/dashboard',array('as'=>'Admin Dashboard' , 'uses' =>'SystemAuthController@Dashboard'));

      Route::get('/admin/profile',array('as'=>'Admin Profile' , 'uses' =>'AdminController@Profile'));

      Route::get('/admin/user/management',array('as'=>'Admin User management' , 'uses' =>'AdminController@UserManagement'));

      Route::post('/admin/user/create',array('as'=>'Admin User create' , 'uses' =>'AdminController@CreateUser'));

      Route::post('admin/profile/update',array('as'=>'Profile Update' , 'uses' =>'AdminController@ProfileUpdate'));

      Route::post('admin/profile/image/update',array('as'=>'Profile Image Update' , 'uses' =>'AdminController@ProfileImageUpdate'));
      
      Route::post('admin/change/password',array('as'=>'User  Password' , 'uses' =>'AdminController@UserChangePassword'));

      Route::get('change/password/by/admin',array('as'=>'User Password Change By admin' , 'uses' =>'AdminController@PasswordChangePage'));

      Route::post('change/password/by/admin',array('as'=>'User Password Change By admin' , 'uses' =>'AdminController@PasswordChangeByAdmin'));



      /*################
      ## Color Settings
      #################*/

      #getAllContent
      Route::get('/color/list',array('as'=>'Get All Color Content' , 'desc'=>'entry & Edit', 'uses' =>'ColorController@getAllContent'));
      #Create
      Route::get('/color/create',array('as'=>'Color Create' , 'desc'=>'entry & edit', 'uses' =>'ColorController@Create'));
      #Store
      Route::post('/color/save',array('as'=>'Color Save' , 'desc'=>'entry & edit', 'uses' =>'ColorController@Store'));
      #ChangeStatus
      Route::get('/color/change/status/{id}/{status}',array('as'=>'Color Status Change' , 'desc'=>'entry & edit', 'uses' =>'ColorController@ChangePublishStatus'));
      #Edit
      Route::get('/color/edit/id-{id}',array('as'=>'Color Edit' , 'desc'=>'entry & edit', 'uses' =>'ColorController@Edit'));
      #Update
      Route::post('/color/update/id-{id}',array('as'=>'Color Update' , 'desc'=>'entry & edit', 'uses' =>'ColorController@Update'));
      #Delete
      Route::get('/color/delete/id-{id}',array('as'=>'Color Delete' , 'desc'=>'entry & edit', 'uses' =>'ColorController@Delete'));


      /*################
      ## Product Settings
      #################*/

      #getAllContent
      Route::get('/product/list',array('as'=>'Get All Product Content' , 'desc'=>'entry & Edit', 'uses' =>'ProductsController@getAllContent'));
      #Create
      Route::get('/product/create',array('as'=>'Product Create' , 'desc'=>'entry & edit', 'uses' =>'ProductsController@Create'));
      #Store
      Route::post('/product/save',array('as'=>'Product Save' , 'desc'=>'entry & edit', 'uses' =>'ProductsController@Store'));
      #ChangeStatus
      Route::get('/product/change/status/{id}/{status}',array('as'=>'Product Status Change' , 'desc'=>'entry & edit', 'uses' =>'ProductsController@ChangePublishStatus'));
      #Edit
      Route::get('/product/edit/id-{id}',array('as'=>'Product Edit' , 'desc'=>'entry & edit', 'uses' =>'ProductsController@Edit'));
      #Update
      Route::post('/product/update/id-{id}',array('as'=>'Product Update' , 'desc'=>'entry & edit', 'uses' =>'ProductsController@Update'));
      #Delete
      Route::get('/product/delete/id-{id}',array('as'=>'Product Delete' , 'desc'=>'entry & edit', 'uses' =>'ProductsController@Delete'));

      Route::get('/product/pdf',array('as'=>'test' , 'desc'=>'entry & Edit', 'uses' =>'ProductsController@ProductPdf'));


      /*################
      ## Stock Settings
      #################*/

      #getAllContent
      Route::get('/stock/list',array('as'=>'Get All Stock Content' , 'desc'=>'entry & Edit', 'uses' =>'StockController@getAllContent'));
      #Create
      Route::get('/stock/create',array('as'=>'Stock Create' , 'desc'=>'entry & edit', 'uses' =>'StockController@Create'));
      #Store
      Route::post('/stock/save',array('as'=>'Stock Save' , 'desc'=>'entry & edit', 'uses' =>'StockController@Store'));
      #ChangeStatus
      Route::get('/stock/change/status/{id}/{status}',array('as'=>'Stock Status Change' , 'desc'=>'entry & edit', 'uses' =>'StockController@ChangePublishStatus'));
      #Edit
      Route::get('/stock/edit/id-{id}',array('as'=>'Stock Edit' , 'desc'=>'entry & edit', 'uses' =>'StockController@Edit'));
      #Update
      Route::post('/stock/update/id-{id}',array('as'=>'Stock Update' , 'desc'=>'entry & edit', 'uses' =>'StockController@Update'));
      #Delete
      Route::get('/stock/delete/id-{id}',array('as'=>'Stock Delete' , 'desc'=>'entry & edit', 'uses' =>'StockController@Delete'));






});
