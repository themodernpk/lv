<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{


    //this code is handle database errors
    App::error(function(\PDOException $e, $code)
    {

        Log::error( 'FATAL DATABASE ERROR: ' . $code . ' = ' . $e->getMessage() );



        if ( Config::get('app.debug') == true )
        {
            $dbCode = $e->getCode();
            echo $dbCode;
            // codes specific to MySQL
            switch ($dbCode)
            {

                case 1049:
                    return Redirect::route('setup')->with('flash_error', "Setup Database Configuration");
                    break;

                case '3D000':
                    $userMessage = $e->getMessage();
                    return Redirect::route('setup')->with('flash_error', "Setup Database First!");
                    break;

                default:
                    $userMessage = $e->getMessage();
                    return View::make('core::error/errordb')->with('title', 'Error Page')->withErrors(array($userMessage));
                    break;
            }

        }



    }); // end of App::error


});


App::after(function($request, $response)
{
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{



    try{
        if (Auth::guest())
        {
            Session::put('redirect', URL::full());
            return Redirect::guest('login')->with('flash_error', 'You must be logged in to view this page!');
        }



        //if user is logged in than do further validation

        if (Auth::check())
        {
            $user = Auth::user();

            $group = Group::find($user->group_id);

            //check user group is active
            if($group->active == 0)
            {
                Auth::logout();
                return Redirect::route('login')->with('flash_error', 'Your account belongs to inactive user group.');
            }

            //check user is active
            if($user->active == 0)
            {
                Auth::logout();
                return Redirect::route('login')->with('flash_error', 'Your account is inactive.');
            }



        }
    }
    catch(PDOException $e)
    {




        if ( Config::get('app.debug') == true )
        {
            $dbCode = $e->getCode();
            // codes specific to MySQL
            switch ($dbCode)
            {

                case 1049:
                    return Redirect::route('setup')->with('flash_error', "Setup Database Configuration");
                    break;

                case '3D000':
                    $userMessage = $e->getMessage();
                    return Redirect::route('setup')->with('flash_error', "Setup Database First!");
                    break;

                default:
                    $userMessage = $e->getMessage();
                    return View::make('core::error/errordb')->with('title', 'Error Page')->withErrors(array($userMessage));
                    break;
            }

        }



    }


});


Route::filter('auth.basic', function()
{
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    if (Session::token() != Input::get('_token'))
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
