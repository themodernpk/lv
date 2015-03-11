<?php

//------------------------------------------------------
function install()
{

    //you may need new permissions which belongs only to your application
    $response = createPermissions();

    if ($response['status'] == 'failed') {
        return $response;
    }

    //create new tables
    Schema::create('tests', function ($table) {
        $table->increments('id');
        $table->string('category');
    });

    $response['status'] = 'success';

    return $response;
}

//------------------------------------------------------
function upgrade($active_vesion)
{

    //upgrades code can very for all previous versions
    switch ($active_vesion) {

        case 1.0:

            //remove old permissions
            $permissionListOld = permissionListOld();
            deletePermissions($permissionListOld);

            //create new permissions
            $response = createPermissions();

            //should rename old tables
            Schema::rename('tests', 'testsV1_0');

            //create new db tables
            Schema::create('tests', function ($table) {
                $table->increments('id');
                $table->string('category');
            });

            //data migration from old table to new table
            $list = DB::table('testsV1_0')->get();

            if (is_object($list) && count($list) > 0) {
                foreach ($list as $item) {
                    DB::table('tests')->insert(
                        array('category' => $item->name)
                    );
                }

            }

            $response['status'] = 'success';

            return $response;

            break;
        //----------------------------------------------
        case 1.1:

            break;
        //----------------------------------------------
        //----------------------------------------------
        //----------------------------------------------

    }

}

//------------------------------------------------------
function uninstall()
{
    //delete permission of for this module
    $permission_list = permissionList();
    deletePermissions($permission_list);

    //drop tables
    Schema::dropIfExists('tests');
    Schema::dropIfExists('testsV1_0');


    $response['status'] = 'success';

    return $response;
}

//------------------------------------------------------


function createPermissions()
{

    $permissions = permissionList();

    foreach ($permissions as $permission) {
        $input['name'] = $permission;
        $input['slug'] = Str::slug($permission);

        //check if already exist
        $exist = Permission::where('slug', '=', $input['slug'])->first();
        if ($exist) {
            continue;
        }

        $response = Permission::createIt($input);

        if ($response['status'] == 'failed') {
            return $response;
            die();
        }

    }

    $response['status'] = 'success';
    return $response;

}

//------------------------------------------------------

function deletePermissions($permissions)
{
    foreach ($permissions as $permission) {
        $slug = Str::slug($permission);
        Permission::where('slug', '=', $slug)->forceDelete();
    }

}

//------------------------------------------------------

function permissionList()
{
    $permissions[] = 'Test v2.0';

    return $permissions;
}

//------------------------------------------------------
function permissionListOld()
{
    $permissions[] = 'Test v1.0';

    return $permissions;
}
//------------------------------------------------------
//------------------------------------------------------
//------------------------------------------------------
