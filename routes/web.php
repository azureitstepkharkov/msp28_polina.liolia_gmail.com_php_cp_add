<?php

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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');

Route::resource('users', 'UserController', ['parameters' => [
    'index' => 'filter'
]]);
Route::resource('roles', 'RoleController');
Route::resource('contacts', 'ContactsController', ['parameters' => [
    'create' => 'user_id'
]]);
Route::resource('permissions', 'PermissionController');
Route::resource('tasks', 'TaskController');
Route::resource('technologies', 'TechnologyController');
Route::resource('projects', 'ProjectController');
Route::resource('contact_type', 'ContactTypeController');
Route::resource('files', 'FileTaskController');

Route::get('user_account/{id}/edit', 'UserAccountController@edit')->name('userAccount.edit');
Route::put('user_account/{id}', 'UserAccountController@update')->name('userAccount.update');
Route::put('user_account/{id}/password', 'UserAccountController@updatePassword')->name('userAccount.updatePassword');
Route::post('user_account/{id}/avatar', 'UserAccountController@updateAvatar')->name('userAccount.updateAvatar');

//Route::middleware(["rolespermissionsverifier:roles:Admin;User|permissions:adminperm"])->group(function (){
//    Route::resource('users', 'UserController');
//    Route::resource('roles', 'RoleController');
//    Route::resource('permissions', 'PermissionController');
//
//});

Route::group(['middleware' => ['permission:adminperm']], function() {
    Route::resource('users', 'UserController', ['parameters' => [
        'index' => 'filter'
    ]]);
    Route::put('users/{id}/updateRoles', 'UserController@updateRoles')->name('users.updateRoles');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
//    Route::resource('technologies', 'TechnologyController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::resource('tasks', 'TaskController', ['parameters' => [
        'create' => 'request',
        'edit' => ['id', 'request']
    ]]);
    Route::put('tasks/{id}/change_status', 'TaskController@change_status')->name('tasks.change_status');
    Route::resource('technologies', 'TechnologyController');
    Route::resource('projects', 'ProjectController');
    Route::put('projects/{id}/change_status', 'ProjectController@change_status')->name('projects.change_status');
    Route::resource('contact_type', 'ContactTypeController');
    Route::resource('comment_user', 'CommentUserController');
    Route::resource('comment_task', 'CommentTaskController');
    Route::post('users/{id}/attach_technology','UserController@attach_technology')->name('users.attach_technology');
    Route::delete('users/{id}/detach_technology/{tech_id}', 'UserController@detach_technology')->name('users.detach_technology');
    Route::post('projects/{id}/attach_technology','ProjectController@attach_technology')->name('projects.attach_technology');
    Route::delete('projects/{id}/detach_technology/{tech_id}', 'ProjectController@detach_technology')->name('projects.detach_technology');

});

//Task attach/detach routes
Route::post('tasks/{id}/attach_technology','TaskController@attach_technology')->name('tasks.attach_technology');
Route::delete('tasks/{id}/detach_technology/{tech_id}', 'TaskController@detach_technology')->name('tasks.detach_technology');

Route::post('tasks/{id}/attach_user','TaskController@attach_user')->name('tasks.attach_user');
Route::delete('tasks/{id}/detach_user/{user_id}', 'TaskController@detach_user')->name('tasks.detach_user');

//Project attach/detach routes
Route::post('projects/{id}/attach_technology','ProjectController@attach_technology')->name('projects.attach_technology');
Route::delete('projects/{id}/detach_technology/{tech_id}', 'ProjectController@detach_technology')->name('projects.detach_technology');

//User attach/detach technologies
Route::post('users/{id}/attach_technology','UserController@attach_technology')->name('users.attach_technology');
Route::delete('users/{id}/detach_technology/{tech_id}', 'UserController@detach_technology')->name('users.detach_technology');


Route::group(['middleware' => ['permission:adminperm|index_user|index_client|index_programmer']], function() {
    Route::resource('users', 'UserController', ['only' => ['index', 'show'], 'parameters' => [
        'index' => 'filter'
    ]]);
});

Route::group(['middleware' => ['permission:adminperm|add_user|add_client|add_programmer']], function() {
    Route::resource('users', 'UserController', ['only' => ['create', 'store'], 'parameters' => [
        'index' => 'filter']]);
});

Route::group(['middleware' =>
    ['permission:adminperm|deactivate_user|edit_user|deactivate_client|edit_client|deactivate_programmer|edit_programmer']],
    function() {
    Route::resource('users', 'UserController', ['only' => ['edit', 'update'], 'parameters' => [
        'index' => 'filter']]);
    Route::resource('contacts', 'ContactsController', ['parameters' => [
        'create' => 'user_id'
    ]]);
    Route::post('users/{id}/attach_technology','UserController@attach_technology')->name('users.attach_technology');
    Route::delete('users/{id}/detach_technology/{tech_id}', 'UserController@detach_technology')->name('users.detach_technology');
});

Route::group(['middleware' => ['permission:adminperm|add_technology']], function() {
    Route::resource('technologies', 'TechnologyController', ['only' => ['create', 'store']]);

});

Route::group(['middleware' => ['permission:adminperm|edit_technology']], function() {
    Route::resource('technologies', 'TechnologyController', ['only' => ['edit', 'update']]);
});

Route::group(['middleware' => ['permission:adminperm|remove_technology']], function() {
    Route::resource('technologies', 'TechnologyController', ['only' => [ 'destroy']]);
});

Route::group(['middleware' => ['permission:adminperm|index_technology']], function() {
    Route::resource('technologies', 'TechnologyController', ['only' => [ 'index']]);
});

Route::group(['middleware' => ['permission:adminperm|index_contact_type']], function() {
    Route::resource('contact_type', 'ContactTypeController', ['only' => [ 'index']]);
});

Route::group(['middleware' => ['permission:adminperm|add_contact_type']], function() {
    Route::resource('contact_type', 'ContactTypeController', ['only' => [ 'create', 'store']]);
});

Route::group(['middleware' => ['permission:adminperm|edit_contact_type']], function() {
    Route::resource('contact_type', 'ContactTypeController', ['only' => [ 'edit', 'update']]);
});

Route::group(['middleware' => ['permission:adminperm|remove_contact_type']], function() {
    Route::resource('contact_type', 'ContactTypeController', ['only' => [ 'destroy']]);
});

Route::group(['middleware' => ['permission:adminperm|index_comment_user']], function() {
    Route::resource('comment_user', 'CommentUserController', ['only' => ['index']]);
});

Route::group(['middleware' => ['permission:adminperm|comment_client|comment_programmer']], function() {
    Route::resource('comment_user', 'CommentUserController', ['only' => ['create', 'store']]);
});

Route::group(['middleware' => ['permission:adminperm|index_project']], function() {
    Route::resource('projects', 'ProjectController', ['only' => [ 'index', 'show']]);
});

Route::group(['middleware' => ['permission:adminperm|add_project']], function() {
    Route::resource('projects', 'ProjectController', ['only' => [ 'create', 'store']]);
});

Route::group(['middleware' => ['permission:adminperm|edit_project|deactivate_project']], function() {
    Route::resource('projects', 'ProjectController', ['only' => [ 'edit', 'update']]);
    Route::put('projects/{id}/change_status', 'ProjectController@change_status')->name('projects.change_status');
    Route::post('projects/{id}/attach_technology','ProjectController@attach_technology')->name('projects.attach_technology');
    Route::delete('projects/{id}/detach_technology/{tech_id}', 'ProjectController@detach_technology')->name('projects.detach_technology');

});

Route::group(['middleware' => ['permission:adminperm|index_task']], function() {
    Route::resource('tasks', 'TaskController', ['only' => [ 'index', 'show']]);
});

Route::group(['middleware' => ['permission:adminperm|add_task']], function() {
    Route::resource('tasks', 'TaskController', ['only' => [ 'create', 'store']]);
});

Route::group(['middleware' => ['permission:adminperm|edit_task|change_task_status']], function() {
    Route::resource('tasks', 'TaskController', ['only' => ['edit', 'update']]);
    Route::resource('comment_task', 'CommentTaskController');
    Route::resource('files', 'FileTaskController');
    Route::put('tasks/{id}/change_status', 'TaskController@change_status')->name('tasks.change_status');
});

Route::group(['middleware' => ['permission:adminperm|remove_task']], function() {
    Route::resource('tasks', 'TaskController', ['only' => [ 'destroy']]);
});

Route::group(['middleware' => ['checkTaskViewAccess']], function() {
    Route::resource('tasks', 'TaskController', ['only' => [ 'show']]);
});

Route::group(['middleware' => ['permission:adminperm|index_comment_task']], function() {
    Route::resource('comment_task', 'CommentTaskController', ['only' => [ 'index']]);
});

Route::group(['middleware' => ['permission:adminperm|comment_task']], function() {
    Route::resource('comment_task', 'CommentTaskController', ['only' => [ 'create', 'store']]);
});

Route::group(['middleware' => ['checkProjectViewAccess']], function() {
    Route::resource('projects', 'ProjectController', ['only' => [ 'show']]);
});



Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});


Route::put('inactive/{id}',array('uses' => 'UserController@inactive', 'as' => 'users.inactive'), function ($id){

});

Route::put('users/{id}/updateRoles', 'UserController@updateRoles')->name('users.updateRoles');
