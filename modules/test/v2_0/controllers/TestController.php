<?php
/**
 * Created by PhpStorm.
 * User: pradeep
 * Date: 2014-12-13
 * Time: 10:48 PM
 */
class TestController extends BaseController
{

    public static $view = 'test';

    public function __construct()
    {

    }

    //------------------------------------------------------
    function getIndex()
    {
        $data = array();
        return View::make(self::$view.'::index')->with('title', 'Test Module')->with('data', $data);
    }



    //------------------------------------------------------
    //------------------------------------------------------
    //------------------------------------------------------

    //------------------------------------------------------


} // end of the class