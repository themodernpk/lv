<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

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
            'username'              => 'required|alpha_dash|min:4|unique:users',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|alpha_num|min:4',
            'group_id'              => 'required',
        );

	//------------------------------------------------------------
	public static function validate($data)
	{
		return Validator::make($data, static::$rules);

	}
    //------------------------------------------------------------

    public static function add($input = NULL)
    {
        if($input == NULL)
        {
            $input = Input::all();
        }

        if( (!isset($input['username']) || $input['username'] =="") && isset($input['email']))
        {
            $input['username'] = Common::generate_username($input['email']);
        }

        if(!isset($input['group_id']))
        {
            $input['group_id'] = Group::where('slug', '=', 'registered')->first()->id;
        }

        if(!isset($input['password']) || $input['password'] =="")
        {
            $input['password'] = Common::generate_password();
        }

        $v = User::validate($input);
        if($v->fails())
        {
            $response['status'] = "failed";
            $response['errors'] = $v->messages()->all();
            return $response;
        } else
        {
            unset($input['_token']);
            $input['password'] = Hash::make($input['password']);
            $input['name'] = trim(ucwords($input['name']));

            $user = new User();
            foreach($input as $key => $value)
            {
                $user->$key = $value;
            }
            $user->save();

            Activity::log("Account created for ".$user->email, $user->id, 'Created');

            $response['status'] = "success";
            return $response;

        }

    }
	//------------------------------------------------------------
    public static function edit($input = NULL)
    {
        if ($input == NULL) {
            $input = Input::all();
            $input['id'] = Auth::user()->id;
        }

        $user_id = $input['id'];

        $user = User::find($user_id);

        $rule = array();
        //set validation rules
        if(isset($input['email']) && $input['email'] != $user->email)
        {
            $rule['email'] = 'required|email|unique:users';
        }

        if(isset($input['username']) && $input['username'] != $user->username)
        {
            $rule['username'] = 'required|alpha_dash|min:4|unique:users';
        }

        if(isset($input['password']) && !empty($input['password']) && $input['password'] != "")
        {
            $rule['password'] = 'required|alpha_num|min:4';
        }

        if(isset($input['group_id']) && $input['group_id'] != $user->group_id)
        {
            $rule['group_id'] = 'required';
        }

        $v = Validator::make($input, $rule);
        if($v->fails())
        {
            $response['status'] = "failed";
            $response['errors'] = $v->messages()->all();

            return $response;
        } else
        {
            foreach($input as $key => $value)
            {
                if($key == 'password')
                {
                    $value = Hash::make($input['password']);
                }

                if($key == 'name')
                {
                    $value = trim(ucwords($input['name']));
                }

                $user->$key = $value;
            }

            $user->save();
            Activity::log("Account created for ".$user->email, Auth::user()->id, 'Updated');
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

    public static function thumbnail($id = NULL)
    {
        if($id == NULL)
        {
            $id = Auth::user()->id;
        }
        $email = User::find($id)->email;
        $thumb = md5($email);
        $thumb_url = "http://www.gravatar.com/avatar/".$thumb."?s=200";
        return $thumb_url;
    }



	//------------------------------------------------------------
	//------------------------------------------------------------


}
