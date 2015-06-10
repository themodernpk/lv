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
        'remember_token',
    ];

    protected static $rules = array(
        //'username'              => 'required|alpha_dash|min:4|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|alpha_num|min:4',
        'mobile' => 'integer',
        'group_id' => 'required',
    );

    //------------------------------------------------------------
    public static function validate($data, $update = false)
    {
        $rules = static::$rules;
        if($update == true)
        {
            unset($rules['password']);
        }
        return Validator::make($data, $rules);
    }

    //------------------------------------------------------------

    public static function authenticate($input = NULL)
    {
        if($input == NULL)
        {
            $input = Input::all();
        }

        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:4'
        );

        //validate inputs
        $validate = Validator::make($input, $rules);
        if($validate->fails())
        {
            $response = array();
            $response['status'] = 'failed';
            $response['errors'] = $validate->errors();
            return $response;
        }

        //if credentails are passed then attempt to login
        $credentials = array('email' => $input['email'], 'password' => $input['password']);
        $remember = false;
        if (isset($input['remember']) && $input['remember'] == true && !isset($input['format']))
        {
            $remember = true;
        }

        //check of for invalid credentails
        if (!Auth::attempt($credentials, $remember))
        {
            $response = array();
            $response['status'] = 'failed';
            $response['errors'][] = "Invalid Credentials";
            return $response;
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


        //if format is set then check api access permission as well
        if (isset($input['format']) && !Permission::check('api-access'))
        {
            $response = array();
            $response['status'] = 'failed';
            $response['errors'][] = "You don't have API access";
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

    public static function edit($input = NULL)
    {
        if ($input == NULL) {
            $input = Input::all();
            $input['id'] = Auth::user()->id;
        }
        $user_id = $input['id'];
        $user = User::findorFail($user_id);
        $rule = array();
        //set validation rules
        if (isset($input['email']) && $input['email'] != $user->email) {
            $rule['email'] = 'required|email|unique:users';
        }
        if (isset($input['name'])) {
            $rule['name'] = 'required|alpha_num|min:3';
        }
        if (isset($input['username']) && $input['username'] != $user->username) {
            $rule['username'] = 'required|alpha_dash|min:4|unique:users';
        }
        if (isset($input['password']) && !empty($input['password']) && $input['password'] != "") {
            $rule['password'] = 'required|alpha_num|min:4';
        }
        if (isset($input['mobile']) && !empty($input['mobile']) && $input['mobile'] != 10) {
            $rule['mobile'] = 'required|min:10';
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
            $user->save();
            //Activity::log("Account created for ".$user->email, Auth::user()->id, 'Updated');
            Activity::log("User - Updated, '" . $input['name'] . "' ", Auth::user()->id, 'Updated', 'users', $user->id);
            $response['status'] = "success";
            return $response;
        }
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
    //Add new user
    public static function add($input = NULL)
    {
        if (empty($input)) {
            $input = Input::all();
        }
        //check user exist or not
        $exist = User::where('email', '=', $input['email'])->first();
        if ($exist) {
            $response['status'] = "failed";
            $response['errors'] = array('Email already registered');
            return $response;
        }
        $input['username'] = Common::generate_username($input['email']);
        if (!isset($input['group_id'])) {
            $input['group_id'] = Group::where('slug', '=', 'registered')->first()->id;
        }
        if (!isset($input['password']) || $input['password'] == "") {
            $input['password'] = Common::generate_password();
        }
        unset($input['_token']);
        $input['password'] = Hash::make($input['password']);
        $input['name'] = trim(ucwords($input['name']));
        $user = new User();
        foreach ($input as $key => $value) {
            $user->$key = $value;
        }
        $user->save();
        if ($user) {
            Activity::log("Account created for " . $user->email, $user->id . 'Created');
            //Activity::log("User - Created, '".$input['name']."' ", Auth::user()->id, 'Created', 'users', $user->id);
            $response['status'] = "success";
            $response['data'] = $user;
            return $response;
        } else {
            $response['status'] = "failed";
            return $response;
        }
    }

    //------------------------------------------------------------
    /* ******\ Code Completed till 10th april */
}
