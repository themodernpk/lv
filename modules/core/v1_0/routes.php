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



/* publically available urls */
Route::get('/setup', array('as' => 'setup', 'uses' => 'SetupController@getIndex'));
Route::post('/setup', array('as' => 'setupPost', 'uses' => 'SetupController@setupPost'));
Route::get('/migrationsAndSeeds', array('as' => 'migrationsAndSeeds', 'uses' => 'SetupController@migrationsAndSeeds'));
Route::any('/migrationsAndSeedsRun', array('as' => 'migrationsAndSeedsRun', 'uses' => 'SetupController@migrationsAndSeedsRun'));
Route::get('/createAdmin', array('as' => 'createAdmin', 'uses' => 'SetupController@createAdmin'));


Route::get('/', array('as' => 'home', 'uses' => 'CoreController@getLogin'));
Route::get('/login', array('as' => 'login', 'uses' => 'CoreController@getLogin'));
Route::get('/error', array('as' => 'error', 'uses' => 'CoreController@getError'));

Route::post('/postlogin', array('as' => 'postlogin', 'uses' => 'CoreController@postLogin'));
Route::get('/register', array('as' => 'register', 'uses' => 'CoreController@getRegister'));
Route::post('/postregister', array('as' => 'postregister', 'uses' => 'CoreController@postRegister'));

Route::get('/logout', array('as' => 'logout', 'uses' => 'CoreController@getLogout'));



/* end of publically available urls */


/* protected urls */
Route::group(array('before' => 'auth', 'prefix' => 'dashboard'), function()
{
    Route::get('/', array('as' => 'dashboard', 'uses' => 'DashboardController@getIndex'));


});

Route::group(array('before' => 'auth'), function()
{
    Route::get('core/activities', array('as' => 'activities', 'uses' => 'CoreController@getActivities'));
    Route::get('core/modules', array('as' => 'modules', 'uses' => 'CoreController@getModules'));
    Route::any('core/moduleInstall', array('as' => 'moduleInstall', 'uses' => 'CoreController@moduleInstall'));


});
/* end of protected urls */