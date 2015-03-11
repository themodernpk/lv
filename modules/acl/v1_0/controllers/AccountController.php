<?php
/**
 * Created by PhpStorm.
 * User: pradeep
 * Date: 2014-12-13
 * Time: 10:48 PM
 */
class AccountController extends BaseController
{

    public static $view = 'acl';

    public function __construct()
    {

    }

    //------------------------------------------------------
    function getIndex()
    {
        $data = array();
        $id = Auth::user()->id;
        $data['item'] = User::find($id);
        return View::make(self::$view.'::account/index')->with('title', 'Account Details')->with('data', $data);
    }



    //------------------------------------------------------

    function postAccount()
    {

        $response = User::edit();
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
    function getSettings()
    {
        $data = array();
        return View::make(self::$view.'::account/settings')->with('title', 'Account Setting')->with('data', $data);
    }
    //------------------------------------------------------
    function getProfile($id)
    {
        $data = array();
        $data['user'] = User::findorFail($id);
        return View::make(self::$view.'::account/profile')->with('title', 'Account Profile')->with('data', $data);
    }
    //------------------------------------------------------
    function getActivities()
    {
        $data['list'] = Activity::getList(true);

        return View::make(self::$view.'::account/activities')->with('title', 'My Activity Log')->with('data', $data);
    }
    //------------------------------------------------------
    function getNotifications()
    {
        $data['list'] = Notification::getList(true);

        return View::make(self::$view.'::account/notifications')->with('title', 'My Notifications')->with('data', $data);
    }
    //------------------------------------------------------


} // end of the class