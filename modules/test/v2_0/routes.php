<?php


/* protected urls */
Route::group(array('before' => 'auth', 'prefix' => 'test'), function()
{

	//----------Permission
	Route::get('/', array('as' => 'test', 'uses' => 'TestController@getIndex'));

});