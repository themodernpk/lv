<?php


/* protected urls */
Route::group(array('before' => 'auth', 'prefix' => 'general'), function()
{

	//----------Labels
	Route::get('/label/index', array('as' => 'general-label-index', 'uses' => 'GeneralLabelController@index'));

	Route::any('/label/create', array('as' => 'general-label-create', 'uses' => 'GeneralLabelController@create'));
	Route::any('/label/read/{id?}', array('as' => 'general-label-read', 'uses' => 'GeneralLabelController@read'));
	Route::any('/label/update/{id?}', array('as' => 'general-label-update', 'uses' => 'GeneralLabelController@update'));
	Route::any('/label/delete/{id?}', array('as' => 'general-label-delete', 'uses' => 'GeneralLabelController@delete'));

	Route::any('/label/bulk/action', array('as' => 'general-label-bulk-action', 'uses' => 'GeneralLabelController@bulkAction'));

	//----------Templates
	Route::get('/template/index', array('as' => 'general-template-index', 'uses' => 'GeneralTemplateController@index'));

	Route::any('/template/create', array('as' => 'general-template-create', 'uses' => 'GeneralTemplateController@create'));
	Route::any('/template/read/{id?}', array('as' => 'general-template-read', 'uses' => 'GeneralTemplateController@read'));
	Route::any('/template/update/{id?}', array('as' => 'general-template-update', 'uses' => 'GeneralTemplateController@update'));
	Route::any('/template/delete/{id?}', array('as' => 'general-template-delete', 'uses' => 'GeneralTemplateController@delete'));

	Route::any('/template/bulk/action', array('as' => 'general-template-bulk-action', 'uses' => 'GeneralLabelController@bulkAction'));

});