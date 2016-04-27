<?php

class GeneralTemplate extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'gn_templates';
    protected $dates = ['deleted_at'];
    protected $softDelete = true;
    protected $fillable = [
        'name',
        'category',
        'subject',
        'message',
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
            'category'     => 'required',
            'message'     => 'required',
            'enable'     => 'required',
        ];
    }

    //-----------------------------------------------------------
    public static function validate($input)
    {
        if(is_object($input))
        {
            $input = (array) $input;
        }

        return Validator::make($input, GeneralTemplate::rules());
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
    public static function categoryAutoComplete()
    {

        $db_list = DB::table('gn_templates')->groupBy('category')->lists('category');

        $final = array();
        $default = array("Candidates", "Lead", "Thread", "Mail");
        $result = "";


        $final = array_merge($db_list, $default);
        $final = array_unique($final);

        if($final)
        {
            foreach($final as $item)
            {
                $result .= " '".$item."', ";

            }
        }

        return $result;

    }
    //-----------------------------------------------------------
    //-----------------------------------------------------------
    //-----------------------------------------------------------

} // end of file