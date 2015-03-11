<?php

    //------------------------------------------------------
    function install()
    {

        //you may need new permissions which belongs only to your application
        $response = createPermissions();

        if($response['status'] == 'failed')
        {
            return $response;
        }

        //create new tables
        Schema::create('tests', function($table)
        {
            $table->increments('id');
            $table->string('name');
        });

        $response['status'] = 'success';

        return $response;
    }
    //------------------------------------------------------
    function upgrade($active_vesion)
    {

        //upgrades code can very for differrent last version
        switch($active_vesion->version)
        {
            case 1.0:

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
        deletePermissions();

        //drop tables
        Schema::dropIfExists('tests');

        $response['status'] = 'success';

        return $response;
    }
    //------------------------------------------------------


    function createPermissions()
    {

        $permissions = permissionList();

        foreach($permissions as $permission )
        {
            $input['name'] = $permission;
            $input['slug'] = Str::slug($permission);

            //check if already exist
            $exist = Permission::where('slug', '=', $input['slug'])->first();
            if($exist)
            {
                continue;
            }

            $response = Permission::createIt($input);

            if($response['status'] == 'failed')
            {
                return $response;
                die();
            }

        }

        $response['status'] = 'success';
        return $response;

    }

    //------------------------------------------------------

    function deletePermissions()
    {
        $permissions = permissionList();

        foreach($permissions as $permission ) {
            $slug = Str::slug($permission);
            Permission::where('slug', '=', $slug)->forceDelete();
        }

    }

    //------------------------------------------------------

    function permissionList()
    {
        $permissions[] = 'Test v1.0';

        return $permissions;
    }

    //------------------------------------------------------
    //------------------------------------------------------
    //------------------------------------------------------
    //------------------------------------------------------
