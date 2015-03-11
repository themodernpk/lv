<?php

class CoreController extends BaseController
{

    public static $view = 'core';

    public function __construct()
    {

    }

    //------------------------------------------------------

    //------------------------------------------------------

    //------------------------------------------------------


    //------------------------------------------------------


    public function getError()
    {
        $data = array();

        return View::make('error')->with('title', 'Error Page')->with('data', $data);
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

        return View::make('core::login')->with('title', 'Login to CRM')->with('data', $data);

    }

    //--------------------------------------------------
    public function postLogin()
    {
        $data = array();


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

            if (Auth::attempt($credentials, $remember)) {
                $user_id = Auth::id();
                $user = User::find($user_id);

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

        return View::make('core::register')->with('title', 'Create Account')->with('data', $data);
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


    function getActivities()
    {

        $this->beforeFilter(function()
        {
            if(!Permission::check('admin'))
            {
                return Redirect::route('error')->with('flash_error', "You don't have permission to view this page");
            }
        });

        $data['list'] = Activity::getList();

        return View::make(self::$view.'::activity/index')->with('title', 'All Activity Log')->with('data', $data);
    }

    //------------------------------------------------------

    function getModules()
    {

        $this->beforeFilter(function()
        {
            if(!Permission::check('admin'))
            {
                return Redirect::route('error')->with('flash_error', "You don't have permission to view this page");
            }
        });

        $path = base_path()."/.." ."/modules";

        $module_list = list_sub_folders($path, true);

        $i = 0;
        foreach($module_list as $module)
        {
/*            if($item == 'core' || $item == 'acl')
            {
                continue;
            }*/

            $list[$i]['name'] = $module;
            $details_xml = modules_path($module)."/details.xml";

            if(!file_exists($details_xml))
            {
                $details = "details.xml does not exist";
            } else{
                $details = simplexml_load_file($details_xml);
            }




            $list[$i]['details'] = $details;

            $versions = module_versions_list($module);

            foreach($versions as $ver)
            {
                $ver_xml = modules_path($module)."/".str_replace(".", "_", $ver)."/version.xml";
                $ver_num = str_replace("_", ".", $ver);
                $ver_num = str_replace("v", "", $ver_num);

                if(!file_exists($ver_xml))
                {
                    $details = "version.xml does not exist";
                } else{
                    $details = simplexml_load_file($ver_xml);
                }

                $list[$i]['versions'][$ver_num] = $details;
            }

            ksort($list[$i]['versions']);
            $list[$i]['versions'] = array_reverse($list[$i]['versions']);

            //check if any version is already installed
            $active = Module::select('version')->where('name', '=', $module)->where('active', '=', 1)->first();
            if($active)
            {
                $list[$i]['active'] = $active;
            }


            $i++;

        }



        $data['list'] = $list;

        return View::make(self::$view.'::modules/index')->with('title', 'All Modules')->with('data', $data);
    }

    //------------------------------------------------------


    function moduleInstall()
    {

        $this->beforeFilter(function()
        {
            if(!Permission::check('admin'))
            {
                return Redirect::route('error')->with('flash_error', "You don't have permission to view this page");
            }
        });


        $input = Input::all();


        //load helper
        $ver_folder = 'v'.str_replace(".", "_", $input['version']);

        $class_path = modules_path($input['name'])."/".$ver_folder."/setup.php";



        if (!File::exists($class_path))
        {
            echo "Installation helper file does not exist at ".$class_path;
            die();
        } else{
            //echo $class_path;
            include_once($class_path);
        }


        if($input['task'] == 'install')
        {

            //check if current version is already active or installed
            $exist = Module::where('name', '=', $input['name'])->where('version', '=', $input['version'])->first();

            if($exist)
            {
                echo "This module version is already installed";
                die();
            }

            //mark all existing dabase version as deactive
            Module::where('name', '=', $input['name'])->forceDelete();



            $response = install();



            if($response['status'] == 'success')
            {
                $module = new Module();
                $module->name = $input['name'];
                $module->version = $input['version'];
                $module->active = 1;
                $module->save();
                echo "ok";
            } else{
                echo json_decode($response);
            }


        }
        //------------------------------------------------------
        else if($input['task'] == 'uninstall')
        {
            $response = uninstall();

            if($response['status'] == 'success')
            {
                Module::where('name', '=', $input['name'])->forceDelete();
                echo "ok";
            } else{
                echo json_decode($response);
            }

        }

        //------------------------------------------------------
        else if($input['task'] == 'upgrade')
        {


            $response = upgrade($input['active_version']);

            if($response['status'] == 'success')
            {
                Module::where('name', '=', $input['name'])->forceDelete();

                $module = new Module();
                $module->name = $input['name'];
                $module->version = $input['version'];
                $module->active = 1;
                $module->save();

                echo "ok";
            } else{
                echo json_decode($response);
            }

        }


    }

    //------------------------------------------------------
    //------------------------------------------------------
    //------------------------------------------------------
    //------------------------------------------------------
    //------------------------------------------------------
    //------------------------------------------------------

} // end of class