<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Collection;

class Permission extends Eloquent
{

    /* ****** Code Completed till 10th april */
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected static $rules = array(
        'name' => 'required|min:2|max:50|unique:permissions',
    );

    //------------------------------------------------------------
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    //------------------------------------------------------------
    public function groups($active = NULL)
    {
        $groups = $this->belongsToMany('Group')->withPivot('active')->withPivot('id');

        if($active != NULL)
        {
            $groups->wherePivot('active', $active);
        }

        return $groups;
    }

    //------------------------------------------------------------
    public static function userList($permission_slug, $admin=true)
    {
        $permission = Permission::where('slug', '=', $permission_slug)->first();

        //find all groups which has this permission and permission is active to the group
        $groups = $permission->groups('1')->get();

        $group_ids = array();
        if($groups)
        {
            foreach($groups as $group)
            {
                $group_ids[] = $group->id;
            }
        }

        if($admin == true)
        {
            $admin_group = Group::where('slug', '=', 'admin')->first();

            if(!in_array($admin_group->id, $group_ids))
            {
                $group_ids[] = $admin_group->id;
            }
        }

        $users = User::where('group_id', $group_ids)->get();

        return $users;

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
        if ($group->slug == 'admin') {
            //check user is active
            if ($user->active == 0) {
                return false;
            } else {
                return true;
            }
        }
        /* end of only in case of admin */
        $permission_d = Permission::where('slug', '=', $permission_slug)->first();
        if (count($permission_d) < 1) {
            return false;
        }
        $permission = Group::find($group_id)->permissions()->wherePivot('permission_id', $permission_d->id)->get();
        if ($permission[0]->pivot->active == 0) {
            return false;
        } else if ($permission[0]->pivot->active == 1) {
            return true;
        }
    }

    //------------------------------------------------------------

    //------------------------------------------------------------
    /* ******\ Code Completed till 10th april */
} //end of the class