<?php

class Crud extends Eloquent
{
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;
    protected $fillable = [
        'name',
        'enable',
        'created_by',
        'modified_by',
        'deleted_by',
    ];

    //-----------------------------------------------------------
    public static function rules()
    {
        return [
            'name'     => 'required',
        ];
    }

    //-----------------------------------------------------------
    public static function validate($input)
    {
        if(is_object($input))
        {
            $input = (array) $input;
        }

        return Validator::make($input, Self::rules());
    }
    //-----------------------------------------------------------
    //-----------------------------------------------------------
    public function createdBy()
    {
        return $this->belongsTo('User', 'created_by', 'id');
    }
    //------------------------------------------------------------
    public function modifiedBy()
    {
        return $this->belongsTo('User', 'modified_by', 'id');
    }
    //-----------------------------------------------------------
    public function deletedBy()
    {
        return $this->belongsTo('User', 'deleted_by', 'id');
    }
    //-----------------------------------------------------------
    //-----------------------------------------------------------
    //-----------------------------------------------------------
    //-----------------------------------------------------------

} // end of file