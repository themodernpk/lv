<?php

class AdminController extends BaseController
{

	public static $view = 'acl';


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



	//------------------------------------------------------

	function getPermission()
	{

		$data['list'] = Permission::getList();

		return View::make(self::$view.'::permission/index')->with('title', 'Permissions')->with('data', $data);

	}

	//------------------------------------------------------

	function postPermission()
	{
		$input = Input::all();

		$response = Permission::createIt($input);

		if($response['status'] == 'failed')
		{
			return Redirect::route('permissions')->withErrors($response['errors'])->withInput();
		} else
		{
			return Redirect::route('permissions')->with('flash_success', 'Details Saved');
		}


	}

	//------------------------------------------------------
	//------------------------------------------------------
	//------------------------------------------------------
	//------------------------------------------------------


	function getGroups()
	{

		$data = array();
		$data['list'] = Group::getList();
		
		return View::make(self::$view.'::group/index')->with('title', 'User Groups')->with('data', $data);

	}
	//------------------------------------------------------

	function postGroups()
	{


		$input = Input::all();
		$slug = Str::slug($input['name']);

		$v = Group::validate($input);
		if($v->fails())
		{
			return Redirect::route('groups')->withErrors($v)->withInput();
		} else
		{

			$group = Group::firstorNew(array('slug' => $slug));
			$group->name = $input['name'];
			$group->slug = $slug;
			$group->save();

			//sync this Group with rest of the groups
			Custom::syncPermissions();

			return Redirect::route('groups')->with('flash_success', 'Details Saved');
		}



	}

	//------------------------------------------------------

	function groupPermissions($id)
	{

		$data = array();

		$data['group'] = Group::find($id);


		return View::make(self::$view.'::group/permissions')->with('title', 'Permissions For Group : '.$data['group']->name)->with('data', $data);

	}

	//------------------------------------------------------


	function getUsers()
	{

		$data['list'] = User::getList();
		return View::make(self::$view.'::user/index')->with('title', 'Users')->with('data', $data);

	}

	//------------------------------------------------------

	function addUsers()
	{
		$data = array();
		return View::make(self::$view.'::user/add')->with('title', 'Add Users')->with('data', $data);
	}

	//------------------------------------------------------
	function postUsers()
	{

		$response = User::add();
		if($response['status'] == 'failed')
		{
			return Redirect::route('addUsers')->withErrors($response['errors']);
		} else if($response['status'] == 'success')
		{
			return Redirect::route('addUsers')->with('flash_success', 'Account details successfully updated');
		}

	}
	//------------------------------------------------------
	function editUser($id)
	{
		$data = array();

		$data['item'] = User::findorFail($id);

		return View::make(self::$view.'::user/edit')->with('title', 'Edit Users')->with('data', $data);
	}
	//------------------------------------------------------

	function editPostUser($id)
	{
		$input = Input::all();
		$input['id'] = $id;

		$response = User::edit($input);

		if($response['status'] == 'failed')
		{
			return Redirect::back()->withErrors($response['errors']);
		} else if($response['status'] == 'success')
		{
			return Redirect::back()->with('flash_success', 'Account details successfully updated');
		}

	}

	//------------------------------------------------------

	//------------------------------------------------------
	//------------------------------------------------------
	//------------------------------------------------------
	//------------------------------------------------------

} // end of class