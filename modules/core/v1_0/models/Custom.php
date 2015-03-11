<?php

class Custom
{


	//------------------------------------------------------------

    public static function search($modelname, $column, $current_user_only)
    {

        $input = Input::all();
        $paginate = Setting::value('item-per-page');
        //$paginate = 2;


        if(!isset($input['q'])) 
        {
            if($current_user_only == false)
            {
                $results = $modelname::orderBy('created_at', 'desc')
                            ->orderBy('id', 'desc')
                            ->paginate($paginate);
            } else
            {

                $results = $modelname::where('user_id', '=', Auth::user()->id)
                            ->orderBy('created_at', 'desc')
                            ->orderBy('id', 'desc')
                            ->paginate($paginate);
            }



            return $results;
        }

        $tablename = strtolower($modelname)."s";

        $q = $input['q'];

        $searchTerms = explode(' ', $q);


        $results = $modelname::where($column, 'LIKE', '%'. $q .'%')
        ->where(function ($query) use ($input) 
        {
           if(isset($input['start']) && !empty($input['start']))
           {
            $query->where('created_at', '>=', $input['start']);
            }
        })
        ->where(function ($query) use ($input) 
        {
            if(isset($input['end']) && !empty($input['end']))
            {
                $query->where('created_at', '<=', $input['end']);
            }
        })
        ->where(function ($query) use ($current_user_only)
        {
            if($current_user_only == true)
            {
                $query->where('user_id', '=', Auth::user()->id);
            }
        })
        ->orderBy('created_at', 'desc')
        ->orderBy('id', 'desc')
        ->paginate($paginate);

        return $results;

    }

	//------------------------------------------------------------


	//------------------------------------------------------------
    public static function syncPermissions()
    {
        $fetch_groups = Group::all();
        $fetch_permissions = Permission::all();

        $groups = DB::table('groups')->lists('id');
        $permissions = DB::table('permissions')->lists('id');


        foreach ($fetch_permissions as $item) {
            $permission = Permission::find($item->id);
            //we are trying to sync all the groups with existing permission
            $permission->groups()->sync($groups);
        }

        foreach ($fetch_groups as $item) {
            $group = Group::find($item->id);
            //we are trying to sync all the groups with existing permission
            $group->permissions()->sync($permissions);
        }
    }
	//------------------------------------------------------------
    public static function selectbox($modelname, $selected = NULL) 
    {
        $all = $modelname::all();
        $html = "";
        foreach($all as $item)
        {
            if($selected != "NULL" && $selected == $item->id)
            {
                $html .= "<option value='".$item->id."' selected = 'selected' >".$item->name."</option>";
            } else
            {
                $html .= "<option value='".$item->id."'>".$item->name."</option>";
            }
            
        }

        return $html;
    }
	//------------------------------------------------------------



	//------------------------------------------------------------

}