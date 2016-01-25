<?php

class CoreDbController extends BaseController
{

    public $data;

    public function __construct()
    {

        $this->data = new stdClass();

        $this->beforeFilter(function () {
            if (!Permission::check('show-admin-section')) {
                return Redirect::route('error')->with('flash_error', "You don't have permission to view this page");
            }
        });
    }

    //------------------------------------------------------

    public function update_2016_01_07_7PM()
    {

        Schema::table('groups', function($table)
        {
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->after('active');

            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('set null')->after('created_by');

            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null')->after('modified_by');

        });

    }

    //------------------------------------------------------
    public function update_2016_01_08_6PM()
    {

        Schema::table('permissions', function($table)
        {
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->after('active');

            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('set null')->after('created_by');

            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null')->after('modified_by');

        });

    }

    //------------------------------------------------------
    public function update_2016_01_08_7PM()
    {

        $permissions[] = 'Core Activity Create';
        $permissions[] = 'Core Activity Read';
        $permissions[] = 'Core Activity Update';
        $permissions[] = 'Core Activity Delete';
        $permissions[] = 'Core Manage All Users Activity';

        $permissions[] = 'Core Notification Create';
        $permissions[] = 'Core Notification Read';
        $permissions[] = 'Core Notification Update';
        $permissions[] = 'Core Notification Delete';
        $permissions[] = 'Core All Users Notification';

        $permissions[] = 'Core Permission Create';
        $permissions[] = 'Core Permission Read';
        $permissions[] = 'Core Permission Update';
        $permissions[] = 'Core Permission Delete';

        $permissions[] = 'Core Setting Create';
        $permissions[] = 'Core Setting Read';
        $permissions[] = 'Core Setting Update';
        $permissions[] = 'Core Setting Delete';

        $permissions[] = 'Core User Create';
        $permissions[] = 'Core User Read';
        $permissions[] = 'Core User Update';
        $permissions[] = 'Core User Delete';


        foreach ($permissions as $permission) {
            $input['name'] = $permission;
            $input['slug'] = Str::slug($permission);

            //check if already exist
            $exist = Permission::where('slug', '=', $input['slug'])->first();
            if ($exist) {
                continue;
            }

            $response = Permission::create($input);

            if ($response['status'] == 'failed') {
                return $response;
                die();
            }

        }

        //sync this permission with rest of the groups
        Custom::syncPermissions();


    }

    //------------------------------------------------------
    public function update_2016_01_08_7_30PM()
    {
        Schema::table('users', function($table)
        {
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->after('active');

            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('set null')->after('created_by');

            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null')->after('modified_by');

        });
    }

    //------------------------------------------------------


} // end of the class