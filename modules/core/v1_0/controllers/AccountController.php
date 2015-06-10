<?php

/**
 * Created by PhpStorm.
 * User: pradeep
 * Date: 2014-12-13
 * Time: 10:48 PM
 */
class AccountController extends BaseController
{

    /* ****** Code Completed till 10th april */
    public static $view = 'core::account.core-account';

    public function __construct()
    {
    }

    //------------------------------------------------------
    function updateAccount()
    {
        $response = User::edit();
        if ($response['status'] == 'failed') {
            return Redirect::back()->withErrors($response['errors']);
        } else if ($response['status'] == 'success') {
            return Redirect::back()->with('flash_success', 'Account details successfully updated');
        }
    }

    //------------------------------------------------------
    function getProfile($id)
    {
        $data = array();
        $data['user'] = User::findorFail($id);
        return View::make(self::$view . '-profile')->with('title', 'Profile')->with('data', $data);
    }

    //------------------------------------------------------
    function getActivities()
    {
        $input = Input::all();
        //if start and end is set
        if (Input::has('start') && Input::has('end')) {
            $data['list'] = Activity::where("user_id", Auth::user()->id)->whereBetween('created_at', array($input['start'], $input['end']))->get();
        } else {
            $data['list'] = Activity::where("user_id", Auth::user()->id)->get();
        }
        return View::make(self::$view . '-activities')->with('title', 'My Activity Log')->with('data', $data);
    }

    // ................................................
    function getNotifications()
    {
        $input = Input::all();
        //if start and end is set
        if (Input::has('start') && Input::has('end')) {
            $data['list'] = Notification::where("user_id", Auth::user()->id)->whereBetween('created_at', array($input['start'], $input['end']))->get();
        } else {
            $data['list'] = Notification::where("user_id", Auth::user()->id)->get();
        }
        return View::make(self::$view . '-notifications')->with('title', 'My Notifications')->with('data', $data);
    }

    //------------------------------------------------------
    function getSettings()
    {
        $data = array();
        return View::make(self::$view . '-settings')->with('title', 'Setting ')->with('data', $data);
    }

    //------------------------------------------------------
    /*
    * get the id of current logged in user
    * use this Auth::user()->id to get id
    * get data of user from User Model using this id
    * pass all detail of user to setting page
    */
    function getIndex()
    {
        $data = array();
        return View::make(self::$view)->with('title', 'Account')->with('data', $data);
    }
    /* ******\ Code Completed till 10th april */
} // end of the class