<?php

class HtmlHelper
{

    //----------------------------------------------
    /*
     * array("" => "");
     * $data['title'] = "Modal Title";
     * $data['modal_id'] = "Add";
     * $data['title'] = "#modal-email";
     */
    public static function modal($data=array())
    {
        $data['start'] = true;

        $view  = View::make('core::elements.modal')->with('data', $data);
        return $view;
    }
    //----------------------------------------------
    public static function modalClose()
    {
        $data['end'] = true;

        $view  = View::make('core::elements.modal')->with('data', $data);
        return $view;
    }
    //----------------------------------------------
    /*
     * array("title" => "Panel Title", "head_button" => "Add", "head_button_modal_id" => "#modalID")
     * $data['title'] = "Panel Title";
     * $data['head_button'] = "Add";
     * $data['head_button_modal_id'] = "#modal-email";
     */
    public static function panel($data = array())
    {

        $data['start'] = true;

        $view  = View::make('core::elements.panel')->with('data', $data);
        return $view;

    }
    //----------------------------------------------
    public static function panelClose()
    {
        $data = array();
        $data['end'] = true;
        $view  = View::make('core::elements.panel')->with('data', $data);
        return $view;

    }
    //----------------------------------------------
    //----------------------------------------------

}// end of class