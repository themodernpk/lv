<?php
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    /* ****** Code Completed till 10th april */
    use UserTrait, RemindableTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $table = 'users';
    protected $guarded = array('id');
    protected $hidden = array('password');
    protected $fillable = [
        'username',
        'password',
        'email',
        'name',
        'mobile',
        'group_id',
        'active',
        'remember_token'
    ];



     protected static $rulesadd = array(
       'name'       =>'required',
       'email'      => 'required|email|unique:users',
       'password'   => 'required|min:4'

    );

     protected static $rulesupdateUnique = array(

      'email'      => 'required|email|unique:users',
      'mobile'     => 'numeric'

    );

     protected static $rulesupdate = array(


      'mobile'     => 'numeric'

    );


    //------------------------------------------------------------

    public static function validate($input)
    {

        if (!empty($input['id']))
        {
            $user = User::findorFail($input['id']);


            $rules = static::$rulesupdateUnique;

            if(isset($input['email']) && $input['email'] == $user->email )
            {
                $rules = static::$rulesupdate;
            }


        }
        else
        {
            $rules  = static::$rulesadd;
        }

       return Validator::make($input,$rules);

    }

    //------------------------------------------------------------

   public static function authenticate($input = NULL)
    {

        if($input == NULL)
        {
            $input = Input::all();
        }

        if(isset($input['email']))
        {
            $credentials['email'] = $input['email'];
        }

        if(isset($input['password']))
        {
            $credentials['password'] = $input['password'];
        }


        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:4'
        );


        if (isset($input['apirequest']) && isset($input['apikey']) )
        {

            $existing_user = User::where('apikey', '=', $input['apikey'])->first();
            $response = array();
            if (!$existing_user) {
                $response['status'] = 'failed';
                $response['errors'][] = "Your Api Key Is Invalid";
                Auth::logout();
                return $response;
            }
            else{

                $get_user = User::find($existing_user->id);
            }

        }
        elseif(isset($input['apirequest']) && $input['apikey'] == "")
        {
            $response['status'] = 'failed';
            $response['errors'][] = "Api Key Is Required";
            Auth::logout();
            return $response;
        }
        else{
            $validate = Validator::make($input, $rules);
            if($validate->fails())
            {
                $response = array();
                $response['status'] = 'failed';
                $response['errors'] = $validate->errors();
                return $response;
            }
        }

        //if credentails are passed then attempt to login
        $remember = false;
        if (isset($input['remember']) && $input['remember'] == true && !isset($input['apirequest']))
        {
            $remember = true;
        }


        if (isset($input['apirequest']) && $input['apikey'] !='' )
        {
            //check of for invalid credentails if it is apirquest
            if (!Auth::loginUsingId($get_user->id))
            {

                $response = array();
                $response['status'] = 'failed';
                $response['errors'][] = "Invalid Credentials";
                return $response;
            }

        }
        else{
            //check of for invalid credentails
            if (!Auth::attempt($credentials, $remember))
            {
                $response = array();
                $response['status'] = 'failed';
                $response['errors'][] = "Invalid Credentials";
                return $response;
            }
        }


        //if credentials are valid then process further
        $user_id = Auth::id();
        $user = User::find($user_id);


        //check user group is inactive
        if($user->group->active == 0)
        {
            $response = array();
            $response['status'] = 'failed';
            $response['errors'][] = "Your account belongs to inactive user group.";
            Auth::logout();
            return $response;
        }

        //check user group is inactive
        if($user->active == 0)
        {
            $response = array();
            $response['status'] = 'failed';
            $response['errors'][] = "Your account is inactive.";
            Auth::logout();
            return $response;
        }


        //check where user permission "disallow-login"
        if (!Permission::check('allow-login'))
        {
            $response = array();
            $response['status'] = 'failed';
            $response['errors'][] = "You don't have permission to login";
            Auth::logout();
            return $response;
        }

        //check where user permission "disallow-login"
        if (!Permission::check('api-access'))
        {
            $response = array();
            $response['status'] = 'failed';
            $response['errors'][] = "API Access denied";
            Auth::logout();
            return $response;
        }

        //update last login column
        $user->lastlogin = Dates::now();
        $user->save();


        $response = array();
        $response['status'] = 'success';
        $response['data'] = $user;

        return $response;

    }

    //------------------------------------------------------------
    public function group()
    {
        return $this->belongsTo('Group');
    }

    //------------------------------------------------------------
    public static function getList($current_user_only = false)
    {
        $modelname = __CLASS__;
        $results = Custom::search($modelname, 'name', $current_user_only = false);
        return $results;
    }

    //------------------------------------------------------------
    public static function thumbnail($id = NULL, $size = 200)
    {
        if ($id == NULL) {
            $id = Auth::user()->id;
        }
        $email = User::find($id)->email;
        $thumb = md5($email);
        $thumb_url = "http://www.gravatar.com/avatar/" . $thumb . "?s=" . $size;
        return $thumb_url;
    }
    //------------------------------------------------------------

    /* ******\ Code Completed till 10th april */
    public static function store($input = NULL)
    {
        if($input == NULL)
        {
            $input = Input::all();
        }

        if (!empty($input['id']))
        {
            $user = User::findorFail($input['id']);
            $rule = array();
            if (isset($input['email']) && $input['email'] != $user->email) {
                $rule['email'] = 'required|email|unique:users';
            }
            if (isset($input['name']) && $input['name'] != $user->name) {
                $rule['name'] = 'required|alpha_num|min:3';
            }
            if (isset($input['username']) && $input['username'] != $user->username) {
                $rule['username'] = 'required|alpha_dash|min:4|unique:users';
            }
            if (isset($input['password']) && !empty($input['password']) && $input['password'] != "") {
                $rule['password'] = 'required|alpha_num|min:4';
            }
            if (isset($input['group_id']) && $input['group_id'] != $user->group_id) {
                $rule['group_id'] = 'required';
            }
            $v = Validator::make($input, $rule);
            if ($v->fails()) {
                $response['status'] = "failed";
                $response['errors'] = $v->messages()->all();
                return $response;
            } else {
                unset($input['apirequest']);
                unset($input['_token']);
                foreach ($input as $key => $value) {

                    if ($key == 'password') {
                        $value = Hash::make($input['password']);
                    }
                    if ($key == 'name') {
                        $value = trim(ucwords($input['name']));
                    }

                    $user->$key = $value;
                }


            }
            $user->save();
            Activity::log("User - Updated, '" . $input['name'] . "' ", Auth::user()->id, 'Updated', 'users', $user->id);

        }
        else
        {
            $exist = User::where('email', '=', $input['email'])->first();
            if ($exist) {
                $response['status'] = "failed";
                $response['errors'] = array('Email already registered');
                return $response;
            }
            $v = User::validate($input);
            if ($v->fails()) {
                $response['status'] = "failed";
                $response['errors'][] = $v->messages()->all();
                return $response;
            }
            else {
                unset($input['_token']);
                $input['username'] = Common::generate_username($input['email']);
                if (!isset($input['group_id'])) {
                    $input['group_id'] = Group::where('slug', '=', 'registered')->first()->id;
                }
                if (!isset($input['password']) || $input['password'] == "") {
                    $input['password'] = Common::generate_password();
                }
                unset($input['_token']);
                unset($input['apirequest']);
                $input['password'] = Hash::make($input['password']);
                $input['name'] = trim(ucwords($input['name']));
                $user = new User();
                $time = Crypt::encrypt(time());
                $key = array($input['username'],$time);
                $apikey = implode('#', $key );

                foreach ($input as $key => $value) {

                    $user->$key = $value;
                }
                $user->apikey = $apikey;
                $user->save();
                Activity::log("Account created for " . $user->email, $user->id . 'Created');
            }

        }

        if($user)
        {
            $response['status'] = "success";
            $response['data'] = $user;
            return $response;
        }
        else
        {
            $response['status'] = "failed";
            return $response;
        }
    }
}
