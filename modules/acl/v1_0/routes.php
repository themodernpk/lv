<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/




/* protected urls */

Route::group(array('before' => 'auth', 'prefix' => 'acl'), function()
{

	//----------Permission
	Route::get('/permissions', array('as' => 'permissions', 'uses' => 'AdminController@getPermission'));
	Route::post('/permission/add', array('as' => 'postPermission', 'uses' => 'AdminController@postPermission'));

	//----------Group
	Route::get('/groups', array('as' => 'groups', 'uses' => 'AdminController@getGroups'));
	Route::post('/group/add', array('as' => 'postGroups', 'uses' => 'AdminController@postGroups'));
	Route::get('/group/permissions/{id}', array('as' => 'groupPermissions', 'uses' => 'AdminController@groupPermissions'));

	//----------Users
	Route::get('/users', array('as' => 'users', 'uses' => 'AdminController@getUsers'));
	Route::get('/users/add', array('as' => 'addUsers', 'uses' => 'AdminController@addUsers'));
	Route::post('/users/add', array('as' => 'postUsers', 'uses' => 'AdminController@postUsers'));

	Route::get('/users/edit/{id}', array('as' => 'editUser', 'uses' => 'AdminController@editUser'));
	Route::post('/users/edit/{id}', array('as' => 'editPostUser', 'uses' => 'AdminController@editPostUser'));


});


// for my account
Route::group(array('before' => 'auth', 'prefix' => 'account'), function()
{

	//----------account
	Route::get('/', array('as' => 'account', 'uses' => 'AccountController@getIndex'));
	Route::post('/', array('as' => 'postAccount', 'uses' => 'AccountController@postAccount'));
	Route::get('/settings', array('as' => 'settings', 'uses' => 'AccountController@getSettings'));
	Route::get('/activities', array('as' => 'accountActivities', 'uses' => 'AccountController@getActivities'));
	Route::get('/profile/{id}', array('as' => 'profile', 'uses' => 'AccountController@getProfile'));
	Route::get('/notifications', array('as' => 'notifications', 'uses' => 'AccountController@getNotifications'));

});


/* end of protected urls */


/* ajax based available urls */
Route::group(['prefix' => 'ajax'], function() {
	Route::any('/syncpermissions', array('as' => 'syncpermissions', 'uses' => 'AjaxController@syncPermissions'));
	Route::any('/ajax_toggle_status', array('as' => 'ajax_toggle_status', 'uses' => 'AjaxController@ajax_toggle_status'));
	Route::any('/ajax_update_col', array('as' => 'ajax_update_col', 'uses' => 'AjaxController@ajax_update_col'));
	
	Route::any('/ajax_delete', array('as' => 'ajax_delete', 'uses' => 'AjaxController@ajax_delete'));

	Route::any('/ajax_update_col_href', array('as' => 'ajax_update_col_href', 'uses' => 'AjaxController@ajax_update_col_href'));
	Route::any('/markRead', array('as' => 'markRead', 'uses' => 'AjaxController@markRead'));
});

/* End of ajax based available urls */


/* API based available urls */
Route::group(['prefix' => 'api'], function() {

	Route::any('/test', array('as' => 'apitest', 'uses' => 'ApiController@apiTest'));
	Route::any('/help', array('as' => 'apihelp', 'uses' => 'ApiController@apiHelp'));

	Route::any('/userCreate', array('as' => 'apiUserCreate', 'uses' => 'ApiController@userCreate'));

});

/* End of API based available urls */
