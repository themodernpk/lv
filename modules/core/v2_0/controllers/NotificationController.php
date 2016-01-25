<?php

class NotificationController extends BaseController
{

    public $data;

    public function __construct()
    {

        $this->data = new stdClass();
        $this->data->prefix = "notification";

        $this->data->table = 'notifications';
        $this->data->input = (object)Input::all();
        $this->data->model = "Notification";
        $this->data->view = "core::notification.";
    }

    //--------------------------------------------------------------------
    function index()
    {
        $model = $this->data->model;
        $this->data->list = $model::get();
        if($this->data->input->format == 'json')
        {
            DB::table('notifications')
                ->where('user_id', Auth::user()->id)
                ->update(array('read' => 1));

            echo json_encode($this->data->list);
            die();
        }
    }
    //--------------------------------------------------------------------
    function create()
    {
        $user_id = 1;
        $content = "Testing";
        $link = URL::route('dashboard');
        $icon = 'fa-bug bg-red';

        Notification::log($user_id, $content, $link, $icon);

    }
    //--------------------------------------------------------------------


    //--------------------------------------------------------------------

} // end of class