<?php

class Common {

//-----------------------------------------------------------------------------

    public static function generate_username($email)
    {
        $username = str_replace("@", '_', $email);
        $username = str_replace(".", '_', $username);
        return $username;
    }

//-----------------------------------------------------------------------------
    public static function stringToColorCode($str) {
        $code = dechex(crc32($str));
        $code = substr($code, 0, 6);
        return "#".$code;
    }
//-----------------------------------------------------------------------------
    public static function generate_password($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

//-------------------------------OLD FUNCTION----------------------------------------------


/*



    public static function ajax_toggle_status()
    {


        return $content;

    }
//-----------------------------------------------------------------------------

    public static function unique_code($digits = 4)
    {

        return rand(pow(10, $digits-1), pow(10, $digits)-1);

    }

//-----------------------------------------------------------------------------

    public static function recursive_scandir($dir){
        $contents = array();
        foreach(scandir($dir) as $file){
            if($file == '.' || $file == '..') continue;
            $path = $dir.DIRECTORY_SEPARATOR.$file;
            if(is_dir($path)){
               $do = \Common::recursive_scandir($path);
               $contents = array_merge($contents, $do);
           } else {
            $contents[] = $path;
        }
    }




    return $contents;
}

//-----------------------------------------------------------------------------

public static function status_label($status)
{
    if($status == "pending")
    {
        echo "<span class='label label-warning'>".ucwords($status)."</span>";
    } else if($status == "prospect")
    {
        echo "<span class='label label-success'>".ucwords($status)."</span>";
    } else if($status == "disapproved" || $status == 'disqualified' || $status == "duplicated" )
    {
        echo "<span class='label label-danger'>".ucwords($status)."</span>";
    }

}

//-----------------------------------------------------------------------------
public static function list_controller_methods()
{

    $dir = app_path().'\controllers';
    $paths = \Common::recursive_scandir($dir);

    unset($paths[0]);

    $permissions = array();

    
    foreach ($paths as $value) 
    {
      $class= str_replace(".php", "", basename($value));
      
      $files[] = $class;
      $methods = \Common::get_functions_in_file($value);

      $class_name = strtolower(str_replace("Controller", "", $class));


      $i = 0;
      foreach ($methods as $method) 
      {
         $permissions[$class][$i] = $method;
         $i++;
     }
 }


 return $permissions;

}   



//-----------------------------------------------------------------------------

public static function chat_url($lead_id, $email = NULL )
{

    $encryption = new \Encryption();
    $encrypt_id = $encryption->encode($lead_id);



    return URL::route('chat')."/".$encrypt_id;

}



//-----------------------------------------------------------------------------


public static function get_functions_in_file($file, $sort = FALSE) {
    $file = file($file);
    $functions = array();
    
    foreach ($file as $line) {
        $line = trim($line);
        if (substr($line, 0, 8) == 'function') {
            $functions[] = substr($line, 9, strpos($line, '(') - 9);
        }
    }
    
    if ($sort) {
        asort($functions);
        $functions = array_values($functions);
    }
    
    return $functions;
}


//-----------------------------------------------------------------------------
public static function thumbnail($email, $size=80)
{
    $thumb = "http://www.gravatar.com/avatar/".md5($email);
    if($size != 80)
    {
        $thumb .= $thumb."?size=".$size;
    }


    return $thumb;

}

*/
//-----------------------------------------------------------------------------


// this function will be used to upload files and $input_name should be an array

/*public static function upload_files($input_name)
{

    $files = Input::file($input_name);

    if(!is_array($files))
    {
        return false;
    }

    $i = 0;
    foreach($files as $file)
    {
        if(empty($file))
        {
            continue;
        }

        $uniqid = uniqid();
        $timestamp = time();

        if ($file->isValid())
        {
            $ext = $file->getClientOriginalExtension();
            $org_filename = $file->getClientOriginalName();

            $new_filename = $timestamp."_".$uniqid.".".$ext;

            $file->move('uploads', $new_filename);
            
            $result[$i]['file_name'] = $org_filename;
            $result[$i]['file_url'] = \URL::to("/")."/uploads/".$new_filename;

            $i++;
        }
    }

    return $result;

}*/

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------



}//end of class



