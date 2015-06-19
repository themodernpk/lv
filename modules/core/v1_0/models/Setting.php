<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Setting extends Eloquent
{

    /* ****** Code Completed till 10th april */
    protected $guarded = array('id');
    protected $table = 'settings';
    protected $fillable = [
        'key',
        'type',
        'label',
        'value'
    ];

    protected static $rules = array(
        'key' => 'key|unique:settings'
    );

    //------------------------------------------------------------
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }
    //------------------------------------------------------------
    public static function value($key)
    {
        $setting = Setting::where('key', '=', $key)->first();
        if ($setting) {
            return $setting->value;
        } else {
            return false;
        }
    }

    //------------------------------------------------------------

    public static function createSettings($input= NULL)
    {
        if($input == NULL)
        {
            $input = Input::all();
        }
        $setting = new Setting();
        unset($input['_token']);
        unset($input['href']);
        unset($input['general_href']);
        unset($input['smtp_href']);

        if(!empty($input))
        {
            foreach ($input as $key => $value)
            {
                $exists = array_key_exists('id', $value);
                if($exists)
                {


                    $setting = Setting::findorFail($value['id'] );
                    $setting->value = $value['value'];
                    $setting->save();
                }
                else
                {

                   $setting = Setting::insert($value);
                }

            }

            if($setting)
            {
                $response['status'] = "success";
                $response['data'] = $setting;
                return $response;
            }
            else
            {
                $response['status'] = "failed";
                return $response;
            }
        }
    }
    //------------------------------------------------------------
    public static function getSettings()
    {
        $settings = Setting::all();
        $setting_list = $settings->groupBy('group');
        if ($setting_list) {
            return $setting_list->toArray();
        } else {
            return false;
        }
    }


    
}