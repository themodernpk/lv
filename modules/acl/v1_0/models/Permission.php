<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Collection;

class Permission extends Eloquent 
{
	
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $fillable = [
        'name',
        'slug'
    ];

	protected static $rules = array(
		'name'              => 'required|unique:permissions',
		);

	//------------------------------------------------------------
	public static function validate($data)
	{
		return Validator::make($data, static::$rules);

	}

	//------------------------------------------------------------
 	public function groups()
    {
        return $this->belongsToMany('Group')->withPivot('active')->withPivot('id');
    }
	//------------------------------------------------------------
	
    public static function getList($current_user_only = false)
    {
    	$modelname = __CLASS__;
    	$results = Custom::search($modelname, 'name', $current_user_only = false);
        return $results;
    }

	//------------------------------------------------------------


    public static function check($permission_slug)
    {
        $user = Auth::user();
		$group_id = $user->group_id;

        /* if user belong to admin & active then permission slug will not matter */
        $group = Group::find($group_id);
        if($group->slug == 'admin')
        {
            //check user is active
            if($user->active == 0)
            {
                return false;
            } else
            {
                return true;
            }
        }

        /* end of only in case of admin */

        $permission_d = Permission::where('slug', '=', $permission_slug)->first();

        if(count($permission_d) < 1) 
        {
            return false;
        }

        $permission = Group::find($group_id)->permissions()->wherePivot('permission_id', $permission_d->id)->get();

        if($permission[0]->pivot->active == 0)
        {
            return false;
        } else if($permission[0]->pivot->active == 1 )
        {
            return true;
        }

    }

	//------------------------------------------------------------

    public static function createIt($input = array())
    {


        if(isset($input['slug']))
        {
            $slug = $input['slug'];
        } else
        {
            $slug = Str::slug($input['name']);
        }

        $v = Permission::validate($input);
        if($v->fails())
        {
            $response['status'] = "failed";
            $response['errors'] = $v->messages();
            return $response;

        } else {

            $permission = Permission::firstorNew(array('slug' => $slug));
            $permission->name = $input['name'];
            $permission->slug = $slug;
            $permission->save();

            //sync this permission with rest of the groups
            Custom::syncPermissions();

            $response['status'] = "success";
            $response['data'] = $permission;
            return $response;

        }



    }

	//------------------------------------------------------------
	//------------------------------------------------------------



} //end of the class