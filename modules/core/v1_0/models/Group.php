<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Group extends Eloquent
{

    /* ****** Code Completed till 10th april */
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static $rules = array(
        'name' => 'required|unique:groups',
    );

    //------------------------------------------------------------
    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    //------------------------------------------------------------
    // this method is called from custom class to synchronize permission and group
    public function permissions()
    {
        return $this->belongsToMany('Permission')->withPivot('active')->withPivot('id');
    }

    //------------------------------------------------------------
    public static function getList($current_user_only = false)
    {
        $modelname = __CLASS__;
        $results = Custom::search($modelname, 'name', $current_user_only = false);
        return $results;
    }

    //------------------------------------------------------------
    /* ******\ Code Completed till 10th april */
}