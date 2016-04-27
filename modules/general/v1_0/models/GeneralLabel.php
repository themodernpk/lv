<?php

class GeneralLabel extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'gn_labels';
    protected $dates = ['deleted_at'];
    protected $softDelete = true;
    protected $fillable = [
        'name',
        'category',
        'color',
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
            'color'     => 'required',
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

        return Validator::make($input, GeneralLabel::rules());
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
        $db_list = GeneralLabel::select('category')->groupBy('category')->get();
        $final = array();
        $default = array("Candidates", "Lead", "Thread", "Mail");
        $result = "";

        if($db_list)
        {
            foreach($db_list as $item)
            {
                $final[] = $item->category;
            }
        }

        $final = array_merge($final, $default);
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