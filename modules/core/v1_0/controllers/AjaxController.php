<?php

/* #################################
* This Controller is only for handling ajax request
* All the function handles request, comes from ajax
* #################################
*/

class AjaxController extends Controller
{

    /* ****** Code Completed till 10th april */
    public static $view = 'core';

    function ajax_update_col()
    {
        $input = Input::all();
        $name_d = explode('|', $input['name']);
        $table = $name_d[0];
        $column = $name_d[1];
        $value = $input['value'];
        $id = $input['pk'];
        $update_array[$column] = $value;
        if (isset($name_d[2]) && $name_d[2] != "") {
            $update_array['slug'] = Str::slug($value);
        }
        try {
            DB::table($table)
                ->where('id', $id)
                ->update($update_array);
            echo "ok";
        } catch (\Exception $e) {
            echo "error while update record";
        }
    }

    //--------------------------------------------------------------------
    function ajax_update_col_href()
    {
        $input = \Input::all();
        $identifier = explode('|', $input['identifier']);
        $table = $identifier[0];
        $column = $identifier[1];
        $id = $identifier[2];
        $value = $identifier[3];
        try {
            DB::table($table)
                ->where('id', $id)
                ->update(array($column => $value));
            echo "ok";
        } catch (\Exception $e) {
            echo "error while update record";
        }
        die();
    }

    //--------------------------------------------------------------------
    /* #################################
     * This handles the notification
     * update 'read' field of notification table for user
     * it makes all notification as "markRead"
     * this request is fired on click event of notification bar
     * #################################
     */
    function markRead()
    {
        try {
            DB::table('notifications')
                ->where('user_id', Auth::user()->id)
                ->update(array('read' => 1));
            echo "ok";
        } catch (Exception $e) {
            echo "error while update record";
        }
        die();
    }

    //--------------------------------------------------------------------
    /* #################################
     * This function Activate/Deactivate switch of group/permission/group-permission
     * it updates the active status of table and returns response in json format
     * This method is called from ajax
     * This method works for group/permission as well as pivot table "group-permission"
     * pivot table's model doesnot exist , so it is handled by if block
     * since we pass 'update_table' field from ajax for pivot table
     * if "update_table" is true it means we are updating pivot table
     * Otherwise we are go to else condition and update table which model exist
     * get the table name in encrypted form, decrypt it
     * #################################
     */
    function ajax_toggle_status()
    {
        $input = Input::all();
        $input['table'] = Crypt::decrypt($input['table']);
        // this gets the staus whether it is to activate/Deactivate
        if ($input['active_status'] != 'false') {
            $active = 1;
        } else {
            $active = 0;
        }
        /* this is only for updating group_permission pivot table
         * since pivot table  model doesnot exist , we use DB::table('tablename') query
         * it Activate/Deactivate the permission of a group
         * returns the response in json format
         * die() it. else block won't execute control goes back to ajax
         */
        if ($input['update_table']) {
            DB::table($input['table'])->where('id', $input['id'])->update(array('active' => $active));
            $response['status'] = "success";
            $response['data'][] = array(constant('core_success'));
            echo json_encode($response);
            die();
        } /*
	     * this is only for updating group/permission table 
	  	 * it Activate/Deactivate the permission/group since its model exist
	  	 * returns the response in json format
	  	 */
        else {
            $model = get_model_from_table($input['table']);
            // check model exist or not
            // if model does not exist returns the response as a "failed"
            $model_name = $model::find($input['id']);
            if (!$model_name) {
                $response['status'] = 'failed';
                $response['errors'][] = constant('core_failed_not_exist');
                echo json_encode($response);
                die();
            }
            /*
             * basically we check the exception for particular table
             * Check for Exception ,whether it is admin or something else
             * if Exception then return "permission denied " message
             * first get exception defined for this
             * call the core_settings(arg1) function ,defined in functions.php
             * @arg1 is the key for exception is to be checked
             * stores the result in $exceptions, which is an array of id
             */
            $exceptions = core_settings($input['table'])['exceptions'];
            if (in_array($input['id'], $exceptions)) {
                $response['status'] = 'failed';
                $response['errors'][] = constant('core_msg_exceptions');
                $response['errors'][] = constant('core_msg_exceptions');
                echo json_encode($response);
                die();
            } // else update the status
            else {
                $model = $model::find($input['id']);
                $model->active = $active;
                $model->save();
                // check whether update is performed or not
                // if not performed returns "failed" message
                if ($model == false) {
                    $response['status'] = "failed";
                    $response['errors'][] = array(constant('core_failed_undefined'));
                    echo json_encode($response);
                    die();
                }
                // here status is updated and send a successfull message
                $response['status'] = "success";
                $response['data'][] = array(constant('core_success'));
                echo json_encode($response);
                die();
            }
        }
    }

    //--------------------------------------------------------------------
    /* #################################
     * This method is defined to delete the single record
     * This delte single record of group/user/permission
     * Get the id and table name
     * Delete the record if exist else return error messageS
     * #################################
     */
    function ajax_delete()
    {
        $input = Input::all();
        $input['table'] = Crypt::decrypt($input['table']);
        if (!Permission::check('manage-group-permission')) {
            return Redirect::route('error')->with('flash_error', constant('core_msg_permission_denied'));
        }
        try {
            DB::table($input['table'])->where('id', $input['id'])->update(['deleted_at' => date('Y-m-d H:i:s')]);
            $response['status'] = "success";
            $response['data'][] = array(constant('core_success'));
            echo json_encode($response);
            die();
        } catch (Exception $e) {
            $response['status'] = "failed";
            $response['errors'][] = array(constant('core_failed_undefined'));
            echo json_encode($response);
            die();
        }
    }

    //--------------------------------------------------------------------
    /* #################################
     * This method is defined to update the single column in 'Editable table'
     * This updates name of group/user/permission in 'Editable table'
     * There is no need to pass data in ajax when using 'Editable', use Input::all();
     * This gives all atribute of editable
     * get all input of editable table , the id of user and table name
     * #################################
     */
    function ajax_edit()
    {
        $input = Input::all();
        $id = $input['pk'];
        $table = Crypt::decrypt($input['name']);
        $model = get_model_from_table($table);
        if (!Permission::check('manage-group-permission')) {
            return Redirect::route('error')->with('flash_error', constant('core_msg_permission_denied'));
        }
        $model_name = $model::find($input['pk']);
        if (!$model_name) {
            $response['status'] = 'failed';
            $response['errors'][] = constant('core_failed_not_exist');
            echo json_encode($response);
            die();
        }
        $model = $model::find($id);
        $model->name = $input['value'];
        $model->save();
        if ($model == false) {
            $response = 'failed';
            return $response;
        }
        $response = 'success';
        return $response;
    }

    //--------------------------------------------------------------------
    /* #################################
    * method is called from "core-admin-user-blade.php"
    * This method is defined to append the user informtion in model box
    * before updating the 'user', user info is filled in model box
    * This method is called from ajax ,
    * It returns response in Json Format to append the user info in model box
    * Get the id of user and  table name in 'encrypted' format
    * Decrypt the table , to do it call "get_model_from_table" function
    * "get_model_from_table" function defined in "helper/functions.php"
    * first find the user id in table , if exist then return User information
    * Otherwise return With Error message
    * All constants are defined in "helper/constant.php"
    * #################################
    */
    function ajax_model_box()
    {
        $input = Input::all();
        $id = $input['id'];
        $table = Crypt::decrypt($input['table']);
        $model = get_model_from_table($table);
        $user = $model::find($id);
        if (!$user) {
            $response['status'] = 'failed';
            $response['errors'][] = constant('core_failed_not_exist');
            echo json_encode($response);
            die();
        }
        if ($model) {
            $response['data'] = $user;
            echo json_encode($response);
            die();
        }
    }

    //--------------------------------------------------------------------
    /* ******\ Code Completed till 10th april */
} // end of class