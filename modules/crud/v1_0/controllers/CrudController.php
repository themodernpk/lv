<?php

class CrudController extends BaseController
{

    public $data;

    public function __construct()
    {

        $this->data = new stdClass();
        $this->data->prefix = "crud";

        $this->beforeFilter(function () {
            if (!Permission::check($this->data->prefix.'-read')) {
                return Redirect::route('error')->with('flash_error', "You don't have permission to view this page");
            }
        });

        $this->data->table = 'crud';
        $this->data->input = (object)Input::all();
        $this->data->model = "Crud";
        $this->data->view = "crud::crud.";
    }

    //------------------------------------------------------
    function index()
    {
        $model = $this->data->model;

        if(isset($this->data->input->show) && $this->data->input->show == 'trash')
        {
            $this->data->list = $model::onlyTrashed()->get();
        } else
        {
            $this->data->list = $model::all();
        }


        $this->data->trash_count = $model::onlyTrashed()->count();

        return View::make($this->data->view .'index')->with('title', 'Get List')->with('data', $this->data);
    }



    //------------------------------------------------------
    function create()
    {

        $model = $this->data->model;

        //first run validation
        $validate = $model::validate($this->data->input);

        if($validate->fails())
        {
            $response['status'] = 'failed';
            $response['errors'] = $validate->messages()->all();
            echo json_encode($response);
            die();
        }

        // create item
        $create = new $model();
        $ignore = array('format', 'id', '_token');
        foreach($this->data->input as $key => $val)
        {
            if(in_array($key,$ignore) )
            {
                continue;
            }

            $create->$key = $val;
        }

        //set created_by
        $create->created_by = Auth::user()->id;

        try
        {
            $create->save();

            $response['status'] = 'success';
            $response['data'] = $create;
            echo json_encode($response);
            die();

        } catch(Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
            echo json_encode($response);
            die();
        }

    }
    //------------------------------------------------------
    function read()
    {

        $model = $this->data->model;
        $item = $model::withTrashed()->where('id', $this->data->input->id)->first();

        if($item)
        {
            $item->createdBy;
            $item->modifiedBy;
            $item->deletedBy;
            $response['status'] = 'success';
            $response['data'] = $item;
        } else
        {
            $response['status'] = 'success';
            $response['errors'][] = 'Not found';
        }

        if($this->data->input->format == 'json')
        {
            echo json_encode($response);
            die();
        } else
        {
            return $response;
        }

    }

    //------------------------------------------------------
    function update()
    {
        $model = $this->data->model;

        if(!isset($this->data->input->id))
        {
            if($this->data->input->format == 'json')
            {
                $response['status'] = 'failed';
                $response['errors'][] = 'Item ID is not set';
                echo json_encode($response);
                die();
            }
        }

        $item = $model::withTrashed()->where('id', $this->data->input->id)->first();

        if(!$item)
        {
            if($this->data->input->format == 'json')
            {
                $response['status'] = 'failed';
                $response['errors'][] = 'Item ID is not set';
                echo json_encode($response);
                die();
            }
        }

        $ignore = array('_token', 'table', 'format');
        foreach($this->data->input as $key => $val)
        {
            if(in_array($key,$ignore) )
            {
                continue;
            }

            $item->$key = $val;
        }

        try{
            $item->save();

            $response['status'] = 'success';
            $response['data'] = $item;
            if($this->data->input->format == 'json')
            {
                echo json_encode($response);
                die();
            }
        } catch(Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
            if($this->data->input->format == 'json')
            {
                echo json_encode($response);
                die();
            }
        }

    }
    //------------------------------------------------------
    function enable()
    {

        $model = $this->data->model;

        if(isset($this->data->input->pk))
        {
            $item = $model::find($this->data->input->pk);
            $item->enable = 1;
            $item->modified_by = Auth::user()->id;
            $item->save();
        } else if(is_array($this->data->input->id))
        {
            foreach($this->data->input->id as $id)
            {
                $item = $model::find($id);
                $item->enable = 1;
                $item->modified_by = Auth::user()->id;
                $item->save();
            }
        }

    }
    //------------------------------------------------------
    function disable()
    {

        $model = $this->data->model;

        if(isset($this->data->input->pk))
        {
            $item = $model::find($this->data->input->pk);
            $item->enable = 0;
            $item->modified_by = Auth::user()->id;
            $item->save();
        } else if(is_array($this->data->input->id))
        {

            if(empty($this->data->input->id))
            {
                return Redirect::back()->with('flash_error', constant('core_no_item_selected'));
            }
            foreach($this->data->input->id as $id)
            {
                $item = $model::find($id);
                $item->enable = 0;
                $item->modified_by = Auth::user()->id;
                $item->save();
            }
        }



    }
    //------------------------------------------------------

    function delete()
    {

        $model = $this->data->model;

        if(isset($this->data->input->pk))
        {
            $item = $model::find($this->data->input->pk);
            $item->enable = 0;
            $item->deleted_by = Auth::user()->id;
            $item->save();
            $item->delete();
        } else if(is_array($this->data->input->id))
        {

            if(empty($this->data->input->id))
            {
                return Redirect::back()->with('flash_error', constant('core_no_item_selected'));
            }

            foreach($this->data->input->id as $id)
            {
                $item = $model::find($id);
                $item->enable = 0;
                $item->deleted_by = Auth::user()->id;
                $item->save();
                $item->delete();
            }
        }


    }

    //------------------------------------------------------
    function bulkAction()
    {

        $model = $this->data->model;

        switch($this->data->input->action)
        {
            case 'enable':

                if (!Permission::check($this->data->prefix.'-update'))
                {
                    return Redirect::back()->with('flash_error', constant('core_msg_permission_denied'));
                }

                if(!isset($this->data->input->pk) && !isset($this->data->input->id))
                {
                    return Redirect::back()->with('flash_error', constant('core_no_item_selected'));
                }

                $this->enable();

                if(isset($this->data->input->format) && $this->data->input->format == 'json')
                {
                    $response['status'] = 'success';
                    echo json_encode($response);
                    die();
                } else
                {
                    return Redirect::back()->with('flash_success', constant('core_success'));
                }

                break;
            //------------------------------
            case 'disable':

                if (!Permission::check($this->data->prefix.'-update'))
                {
                    return Redirect::back()->with('flash_error', constant('core_msg_permission_denied'));
                }

                if(!isset($this->data->input->pk) && !isset($this->data->input->id))
                {
                    return Redirect::back()->with('flash_error', constant('core_no_item_selected'));
                }

                $this->disable();
                if(isset($this->data->input->format) && $this->data->input->format == 'json')
                {
                    $response['status'] = 'success';
                    echo json_encode($response);
                    die();
                } else
                {
                    return Redirect::back()->with('flash_success', constant('core_success'));
                }
                break;
            //------------------------------
            case 'delete':

                if (!Permission::check($this->data->prefix.'-delete'))
                {
                    return Redirect::back()->with('flash_error', constant('core_msg_permission_denied'));
                }

                if(!isset($this->data->input->pk) && !isset($this->data->input->id))
                {
                    return Redirect::back()->with('flash_error', constant('core_no_item_selected'));
                }

                $this->delete();

                if(isset($this->data->input->format) && $this->data->input->format == 'json')
                {
                    $response['status'] = 'success';
                    echo json_encode($response);
                    die();
                } else
                {
                    return Redirect::back()->with('flash_success', constant('core_success'));
                }
                break;
            //------------------------------
            case 'restore':
                if (!Permission::check($this->data->prefix.'-delete'))
                {
                    return Redirect::back()->with('flash_error', constant('core_msg_permission_denied'));
                }

                if(!isset($this->data->input->pk) && !isset($this->data->input->id))
                {
                    return Redirect::back()->with('flash_error', constant('core_no_item_selected'));
                }

                foreach($this->data->input->id as $id)
                {
                    $model::withTrashed()->where('id', $id)->restore();
                }

                return Redirect::back()->with('flash_success', constant('core_msg_permission_denied'));

                break;
            //------------------------------
            case 'permanent_delete':
                if (!Permission::check($this->data->prefix.'-delete'))
                {
                    return Redirect::back()->with('flash_error', constant('core_msg_permission_denied'));
                }


                if(!isset($this->data->input->pk) && !isset($this->data->input->id))
                {
                    return Redirect::back()->with('flash_error', constant('core_no_item_selected'));
                }

                foreach($this->data->input->id as $id)
                {
                    $model::withTrashed()->where('id', $id)->forceDelete();
                }
                return Redirect::back()->with('flash_success', constant('crm_success_message'));

                break;
            //------------------------------

        }


    }
    //------------------------------------------------------

    //------------------------------------------------------


} // end of the class