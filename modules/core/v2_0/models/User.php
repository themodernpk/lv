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
        'created_by',
        'modified_by',
        'deleted_by',
    ];




    //------------------------------------------------------------
    //------------------------------------------------------------
    public static function rules()
    {
        return [
            'name'       =>'required',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:4'
        ];
    }
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


        if (isset($input['apirequest']) )
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


        if (Auth::viaRemember())
        {
            return Redirect::route('dashboard')->with('flash_success', 'Remember login');
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
        if (isset($input['apirequest']) && !Permission::check('api-access'))
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
    public static function store($input=NULL)
    {
        if ($input == NULL) {
            $input = Input::all();
        }

        //remove empty array
        if(is_array($input))
        {
            $input = array_filter($input);
        }

        if(!in_array('username', $input))
        {
            $input['username'] = Common::generate_username($input['email']);
        }

        if (!is_object($input)) {
            $input = (object)$input;
        }

        //if id is provided then find
        if (isset($input->id) && !empty($input->id))
        {
            $item = User::find($input->id);

            if(isset(Auth::user()->id))
            {
                $item->modified_by = Auth::user()->id;
            }
        }
        else
        {
            $validator = Validator::make((array)$input, User::rules());
            if($validator->fails())
            {
                $response['status'] = 'failed';
                $response['errors'] = $validator->messages()->all();
                return $response;
            }

            $item = new User();

            if(isset(Auth::user()->id))
            {
                $item->created_by = Auth::user()->id;
            }

        }

        $columns = Schema::getColumnListing('users');

        foreach($input as $key => $value)
        {
            if(in_array($key, $columns))
            {
                if($key == 'name')
                {
                    $item->$key = ucwords($value);
                } else if($key =='password')
                {
                    $item->$key = Hash::make($value);
                } else
                {
                    $item->$key = $value;
                }

            }
        }


        try{
            $item->save();
            $response['status'] = 'success';
            $response['data'] = $item;

        } catch(Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
        }

        return $response;

    }
    //------------------------------------------------------------
    public static function register_by_email_only($input)
    {
        $rules = array(
            'email' => 'required|email|unique:users',
            'group_id' => 'required'
        );

        $validator = Validator::make($input, $rules);
        if($validator->fails())
        {
            $response['status'] = 'failed';
            $response['errors'] = $validator->messages()->all();
            return $response;
        }

        $email_d = explode("@", $input['email']);
        $input['name'] = $email_d[0];

        $input['username'] = Common::generate_username($input['email']);
        $input['password'] = Common::generate_password();


        $response = User::store($input);

        return $response;

    }
    //------------------------------------------------------------
}
