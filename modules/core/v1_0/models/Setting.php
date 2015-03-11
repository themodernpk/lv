<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Setting extends Eloquent
{

    protected $guarded = array('id');

    protected $fillable = [
        'key',
        'type',
        'label',
        'value'
    ];

    //------------------------------------------------------------

    public static function value($key)
    {
        $setting = Setting::where('key', '=', $key)->first();

        if($setting)
        {
            return $setting->value;
        }else{
            return false;
        }

    }

    //------------------------------------------------------------


    //------------------------------------------------------------
    //------------------------------------------------------------
    //------------------------------------------------------------


}