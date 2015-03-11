<?php

class DashboardController extends BaseController {


	public function __construct()
	{

		$this->beforeFilter(function()
		{
			if(!Permission::check('admin'))
			{
				return Redirect::route('error')->with('flash_error', "You don't have permission to view this page");
			}
		});

	}


	//------------------------------------------------------

	function getIndex()
	{
		$data = array();
		return View::make('core::layout.dashboard')->with('title', 'Welcome')->with('data', $data);
	}


	//------------------------------------------------------
	//------------------------------------------------------
	//------------------------------------------------------
	//------------------------------------------------------

} // end of class