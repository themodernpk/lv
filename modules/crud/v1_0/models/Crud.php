<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class Crud extends Eloquent
{

    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $fillable = [
        'name',
        'slug',
        'enable',
        'created_by',
        'modified_by',
        'deleted_by',
    ];


    //------------------------------------------------------------
    public static function create_rules()
    {
        return [
            'name' => 'required',
        ];
    }

    //------------------------------------------------------------
    public static function update_rules()
    {
        return [
            'name' => 'required',
        ];
    }
    //------------------------------------------------------------
    public static function getSettings()
    {
        $settings = new stdClass();
        $settings->prefix = 'crud';
        $settings->table = 'cruds';
        $settings->model = 'Crud';
        $settings->view = 'crud::crud.';
        $settings->rows = Config::get('core.settings.rows_to_display');

		$settings->enable = false;
        $settings->executor = true;

        return $settings;
    }
    //------------------------------------------------------------

    //------------------------------------------------------------

    public static function findOrStore($input=NULL)
    {

        $settings = Crud::getSettings();
        $model = $settings->model;

        if ($input == NULL) {
            $input = Input::all();
        }

        //remove empty array
        if(is_array($input))
        {
            $input = array_filter($input);
        }

        if (!is_object($input)) {
            $input = (object)$input;
        }

        if(isset($input->id) && !empty($input->id))
        {
            $item = $model::find($input->id);
            if($item)
            {
                $response['status'] = 'success';
                $response['data'] = $item;
                return $response;
            }
        }


        if(isset($input->slug) && !empty($input->slug))
        {
            $item = $model::where('slug', '=', $input->slug)->first();
            if($item)
            {
                $response['status'] = 'success';
                $response['data'] = $item;
                return $response;
            }
        }

        return $model::store($input);

    }

    //------------------------------------------------------------
    public static function store($input=NULL)
    {

        $settings = Crud::getSettings();
        $model = $settings->model;

        if ($input == NULL) {
            $input = Input::all();
        }

        //remove empty array
        if(is_array($input))
        {
            $input = array_filter($input);
        }

        if (!is_object($input)) {
            $input = (object)$input;
        }

        //if id is provided then find
        if (isset($input->id) && !empty($input->id))
        {
            $validator = Validator::make((array)$input, $model::update_rules());
            if($validator->fails())
            {
                $response['status'] = 'failed';
                $response['errors'][] = $validator->messages()->all();
                return $response;
            }

            $item = $model::find($input->id);

            if($settings->executor == true && Auth::check())
            {
                $item->modified_by = Auth::user()->id;
            }

        }

        if (isset($input->slug) && !empty($input->slug))
        {
            $validator = Validator::make((array)$input, $model::update_rules());
            if($validator->fails())
            {
                $response['status'] = 'failed';
                $response['errors'][] = $validator->messages()->all();
                return $response;
            }

            $item = $model::where('slug', '=', $input->slug)->first();

            if($settings->executor == true && Auth::check())
            {
                $item->modified_by = Auth::user()->id;
            }

        }

        if(!isset($item))
        {
            $validator = Validator::make((array)$input, $model::create_rules());
            if($validator->fails())
            {
                $response['status'] = 'failed';
                $response['errors'][] = $validator->messages()->all();
                return $response;
            }

            $item = new $model();
            if($settings->executor == true && Auth::check())
            {
                $item->created_by = Auth::user()->id;
            }

        }

        $columns = Schema::getColumnListing($settings->table);

        $input_array = (array)$input;


        foreach($columns as $key)
        {

            if(array_key_exists($key, $input_array))
            {
                if($key == 'name')
                {
                    $item->$key = ucwords($input_array[$key]);
                } else
                {
                    $item->$key = $input_array[$key];
                }

            } else if($key == 'slug' && !array_key_exists($key, $input_array))
            {
                $item->$key = Str::slug($input_array['name']);
            }
        }



        try{
            $item->save();
            $response['status'] = 'success';
            $response['data'] = $item;

        } catch(Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
        }

        return $response;

    }

    //------------------------------------------------------------
    public function createdBy()
    {
        return $this->belongsTo('User', 'created_by', 'id');
    }
    //------------------------------------------------------------
    public function modifiedBy()
    {
        return $this->belongsTo('User', 'modified_by', 'id');
    }
    //------------------------------------------------------------
    public function deletedBy()
    {
        return $this->belongsTo('User', 'deleted_by', 'id');
    }
    //------------------------------------------------------------
    //------------------------------------------------------------

} //end of the class