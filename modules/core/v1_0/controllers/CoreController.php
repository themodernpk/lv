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




        //-------------------------------------------------

/*        $data = array();
        $input = Input::all();
        $rules = array('email' => 'required|email', 'password' => 'required');
        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            return Redirect::route('login')->withErrors($v)->withInput();
        } else {
            $credentials = array('email' => $input['email'], 'password' => $input['password']);
            $remember = false;
            if (isset($input['remember']) && $input['remember'] == true) {
                $remember = true;
            }

            if (Auth::attempt($credentials, $remember))
            {
                $user_id = Auth::id();
                $user = User::find($user_id);
                //check where user permission "disallow-login"
                if (!Permission::check('allow-login')) {
                    return Redirect::route('error')->with('flash_error', "You don't have permission to login");
                }

                //update last login column
                $user->lastlogin = Dates::now();
                $user->save();

                if ($redirect = Session::get('redirect')) {
                    Session::forget('redirect');
                    return Redirect::to($redirect);
                } else {
                    return Redirect::route('dashboard')->with('flash_success', 'You are successfully logged in.');
                }
            } else {
                return Redirect::back()->withErrors($v)->with('flash_error', 'Enter valid account details');
            }
        }*/
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
        $response = User::add($input);
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
} // end of class