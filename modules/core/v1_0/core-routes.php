<?php

/* ****** Code Completed till 10th april */

//----------Doc
Route::get('/doc', array('as' => 'doc', 'uses' => 'CoreController@doc'));

//----------Setup
Route::get('/setup', array('as' => 'setup', 'uses' => 'SetupController@getIndex'));
Route::post('/setup', array('as' => 'setupPost', 'uses' => 'SetupController@setupPost'));
Route::get('/migrationsAndSeeds', array('as' => 'migrationsAndSeeds', 'uses' => 'SetupController@migrationsAndSeeds'));
Route::any('/migrationsAndSeedsRun', array('as' => 'migrationsAndSeedsRun', 'uses' => 'SetupController@migrationsAndSeedsRun'));
Route::get('/createAdmin', array('as' => 'createAdmin', 'uses' => 'SetupController@createAdmin'));

//----------Common pages
Route::get('/', array('as' => 'home', 'uses' => 'CoreController@getLogin'));
Route::get('/login', array('as' => 'login', 'uses' => 'CoreController@getLogin'));
Route::get('/error', array('as' => 'error', 'uses' => 'CoreController@getError'));
Route::any('/postlogin', array('as' => 'postlogin', 'uses' => 'CoreController@postLogin'));
Route::get('/register', array('as' => 'register', 'uses' => 'CoreController@getRegister'));
Route::any('/postregister', array('as' => 'postregister', 'uses' => 'CoreController@postRegister'));
Route::get('/logout', array('as' => 'logout', 'uses' => 'CoreController@getLogout'));
Route::get('/upload', array('as' => 'upload', 'uses' => 'CoreController@getUpload'));
Route::any('/uploadFile', array('as' => 'uploadFile', 'uses' => 'CoreController@uploadFile'));



//----------Ajax
Route::group(['prefix' => 'ajax'], function()
{
    Route::any('/syncpermissions', array('as' => 'syncpermissions', 'uses' => 'AjaxController@syncPermissions'));
    Route::any('/ajax_update_col', array('as' => 'ajax_update_col', 'uses' => 'AjaxController@ajax_update_col'));
    Route::any('/ajax_update_col_href', array('as' => 'ajax_update_col_href', 'uses' => 'AjaxController@ajax_update_col_href'));
    Route::any('/markRead', array('as' => 'markRead', 'uses' => 'AjaxController@markRead'));
    Route::any('/ajax_toggle_status', array('as' => 'ajax_toggle_status', 'uses' => 'AjaxController@ajax_toggle_status'));
    Route::any('/ajax_delete', array('as' => 'ajax_delete', 'uses' => 'AjaxController@ajax_delete'));
    Route::any('/ajax_edit', array('as' => 'ajax_edit', 'uses' => 'AjaxController@ajax_edit'));

});

//----------API
Route::group(['prefix' => 'api'], function()
{
    Route::any('/test', array('as' => 'apitest', 'uses' => 'ApiController@apiTest'));
    Route::any('/help', array('as' => 'apihelp', 'uses' => 'ApiController@apiHelp'));


    

});

//----------Protected

Route::group(array('before' => 'auth'), function()
{

    Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'CoreController@getDashboard'));

    Route::group(array('prefix' => 'admin'), function()
    {
        Route::post('bulkAction', array('as' => 'bulkAction', 'uses' => 'AdminController@bulkAction'));

        //----------Modules
        Route::get('modules', array('as' => 'modules', 'uses' => 'AdminController@getModules'));
        Route::any('moduleInstall', array('as' => 'moduleInstall', 'uses' => 'AdminController@moduleInstall'));

        //----------Permission
        Route::any('/permissions/list', array('as' => 'permissions', 'uses' => 'AdminController@permissionList'));
        Route::any('/permissions/item/{id}', array('as' => 'permissionsItem', 'uses' => 'AdminController@permissionsItem'));

        Route::any('/permissions/store', array('as' => 'permissionStore', 'uses' => 'AdminController@permissionStore'));
       
        //----------Users
        Route::get('/users/list', array('as' => 'users', 'uses' => 'AdminController@getUsers'));

   
    });
});


Route::group(['prefix' => 'ajax'], function()
{
    Route::any('/ajax_model_box', array('as' => 'ajax_model_box', 'uses' => 'AjaxController@ajax_model_box'));
});

Route::group(array('prefix' => 'admin','before' => 'auth'), function()
{
   Route::post('user/store', array('as' => 'userStore', 'uses' => 'AdminController@userStore'));
   Route::post('updateUser', array('as' => 'updateUser', 'uses' => 'AdminController@updateUser'));
   Route::post('createUser', array('as' => 'createUser', 'uses' => 'AdminController@createUser'));
   Route::get('activities/list/', array('as' => 'activities', 'uses' => 'AdminController@getActivities'));
    Route::any('/setting', array('as' => 'setting', 'uses' => 'CoreController@setting'));
    Route::post('settingStore', array('as' => 'settingStore', 'uses' => 'CoreController@settingStore'));

});

// route for group

Route::group(array('prefix' => 'admin','before' => 'auth'), function()
{
   Route::get('/groups/list', array('as' => 'groups', 'uses' => 'AdminController@groupList'));
   Route::get('/groups/item/{id}/', array('as' => 'getGroupitem', 'uses' => 'AdminController@groupItem'));
   Route::post('/groups/create', array('as' => 'groupStore', 'uses' => 'AdminController@groupStore'));

   Route::get('/groups/item/{id}/permissions/list/', array('as' => 'groupPermissions', 'uses' => 'AdminController@groupPermissionsList'));

});

//----------account
Route::group(array('prefix' => 'account','before' => 'auth'), function()
{
    Route::get('/', array('as' => 'account', 'uses' => 'AccountController@getIndex'));
    Route::post('/', array('as' => 'updateAccount', 'uses' => 'AccountController@updateAccount'));
    Route::get('/settings', array('as' => 'settings', 'uses' => 'AccountController@getSettings'));

    Route::get('/activities', array('as' => 'accountActivities', 'uses' => 'AccountController@getActivities'));
    Route::get('/profile/{id}', array('as' => 'profile', 'uses' => 'AccountController@getProfile'));
    Route::get('/notifications', array('as' => 'notifications', 'uses' => 'AccountController@getNotifications'));
});

/* ******\ Code Completed till 10th april */