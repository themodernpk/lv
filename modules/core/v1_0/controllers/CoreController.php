<?php

class CoreController extends BaseController
{
    /* ****** Code Completed till 10th april */
    public static $view = 'core::';

    public function __construct()
    {
    }

    //------------------------------------------------------
    public function doc()
    {
        Debugbar::disable();
        $data = array();
        return View::make(self::$view . "doc")->with('title', 'Application Documentations')->with('data', $data);
    }

    //------------------------------------------------------
    public function getUpload()
    {
        Debugbar::disable();
        $data = array();
        return View::make(self::$view . "upload")->with('title', 'Upload')->with('data', $data);
    }

    //------------------------------------------------------
    public function uploadFile()
    {

        Debugbar::disable();

        error_reporting(E_ALL | E_STRICT);
        $upload_handler = new UploadHandler();





    }
    //------------------------------------------------------
    public function getError()
    {
        $data = array();
        return View::make(self::$view . '.error.error')->with('title', 'Error Page')->with('data', $data);
    }

    //--------------------------------------------------
    public function getLogin()
    {
        //call logout function
        Auth::logout();

        $data = array();
        $count_users = User::count();
        if ($count_users == 0) {
            return Redirect::route('register');
        }
        return View::make(self::$view . 'login')->with('title', 'App Login')->with('data', $data);
    }

    //--------------------------------------------------
    public function postLogin()
    {
        $response = User::authenticate();

        if($response['status'] == 'failed')
        {
            return Redirect::back()->withErrors($response['errors']);
        } else{
            if ($redirect = Session::get('redirect')) {
                Session::forget('redirect');
                return Redirect::to($redirect);
            } else {
                return Redirect::route('dashboard')->with('flash_success', 'You are successfully logged in.');
            }
        }
           
    }


    //--------------------------------------------------
    function getRegister()
    {
        //call logout function
        Auth::logout();
        $data = array();
        $count_users = User::count();
        if ($count_users == 0) {
            Session::flash("flash_warning", "Create first user, it will be treated as admin");
        }
        return View::make(self::$view . 'register')->with('title', 'Create Account')->with('data', $data);
    }

    //--------------------------------------------------
    public function postRegister()
    {

        $data = array();
        $input = Input::all();
        $count_users = User::count();

        if ($count_users < 1) {
            //Create Admin permission
            $permission = Permission::firstOrCreate(array('name' => 'Admin'));
            $permission->slug = Str::slug('Admin');
            $permission->save();
            //Create Admin group if not exist
            $group = Group::firstOrCreate(array('name' => 'Admin'));
            $group->slug = Str::slug('Admin');
            $group->save();
            $input['group_id'] = $group->id;
            //Allocate Admin Permission to Admin Group
            $insert = array($group->id, array('active' => 1));
            $group->permissions()->sync($insert);
        } else {
            //Create Registered permission
            $permission = Permission::firstOrCreate(array('name' => 'Registered'));
            $permission->slug = Str::slug('Registered');
            $permission->save();
            //Create Registered group if not exist
            $group = Group::firstOrCreate(array('name' => 'Registered'));
            $group->slug = Str::slug('Registered');
            $group->save();
            $input['group_id'] = $group->id;
            //Allocate Admin Permission to Admin Group
            $insert = array($group->id, array('active' => 0));
            $group->permissions()->sync($insert);
        }


        //check if already registered
        $response = User::store($input);
       /* $errors ='';
        foreach($response['errors'][0] as $list)
        {
            $errors .= $list;
        }*/
        if ($response['status'] == 'failed') {
            return Redirect::route('register')->withErrors($response['errors']);
        } else if ($response['status'] == 'success') {
            return Redirect::route('login')->with('flash_success', 'Account details successfully created');
        }



    }

    //--------------------------------------------------
    function getLogout()
    {
        Auth::logout();
        return Redirect::route('login')->with('flash_success', 'Successfully logged out');
    }

    //------------------------------------------------------
    function getDashboard()
    {
        $data = array();
        return View::make('core::admin.core-admin-dashboard')->with('title', 'Dashboard')->with('data', $data);
    }

    //------------------------------------------------------
    /* ******\ Code Completed till 10th april */

    function setting()
    {
        $data = array();
        $data['list'] = Setting::getSettings();
        $format = Input::get('format');

        if (isset($format)) {
            Switch ($format) {
                case 'json':
                    $response = array('status' => 'success', 'data' => $data);
                    return json_encode($response);
                    break;
            }
        }
        return View::make('core::admin.core-admin-setting')->with('title', 'Setting')->with('data', $data);
    }

    function settingStore()
    {
        $input = Input::all();
        unset($input['_token']);
        unset($input['href']);

        if($input != '')
        {
            $settings = Setting::createSettings($input);
         
            if ($settings['status'] == 'success')
            {
                $response = array('status' => 'success', 'data' =>$settings);
            }
            else
            {
                $response = array('status' => 'failed', 'error' => $settings['error'] );
            }
            return json_encode($response);
        }

    }




} // end of class