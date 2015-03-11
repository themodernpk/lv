<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Activity extends Eloquent
{
	protected $table = 'activities';
	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];
	protected $softDelete = true;

	protected $guarded = array('id');

	protected $fillable = [
		'user_id',
		'label',
		'name',
		'parent_id',
		'content'
	];

	//------------------------------------------------------------

	public static function log($content, $user_id = NULL, $label = NULL, $name=NULL, $parent_id = NULL)
	{
		$activity = new Activity;
		if($user_id != NULL)
		{
			$activity->user_id = $user_id;
		}
		if($label != NULL)
		{
			$activity->label = $label;
		}

		if($name != NULL)
		{
			$activity->name = $name;
		}

		if($parent_id != NULL)
		{
			$activity->parent_id = $parent_id;
		}

		$activity->content = $content;
		$activity->save();
	}

	//------------------------------------------------------------

	public static function get($filter = array(), $html = true, $title = "Recent Activity Log")
	{

		if(!empty($filter))
		{
			$q = new Activity();

			foreach ( $filter as $key => $value ) {
				$q = $q->where($key, $value);
			}

			$activities = $q->get();

			if($html == false)
			{
				return $activities;
			} else
			{
				return View::make('core::layout.inc.activities')->with('items', $activities)->with('title', $title)->render();
			}


		} else
		{
			$activities = Activity::all();

			if($html == false)
			{
				return $activities;
			} else
			{
				return View::make('core::layout.inc.activities')->with('items', $activities)->with('title', $title)->render();
			}
		}

	}

	//------------------------------------------------------------

	public static function html()
	{

		$group_slug = Auth::user()->group->slug;

		//entire log
		if($group_slug == 'admin')
		{
			$items = Activity::orderBy('created_at', 'desc')->orderBy('id', 'desc')->take(50)->get();

		}
		//user specific log
		else
		{
			$items = Activity::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->orderBy('id', 'desc')->take(50)->get();
		}

		return View::make('core/backend/modules/activities')->with('items', $items)->render();
	}

	//------------------------------------------------------------

	public static function getList($current_user_only = false)
	{
		$modelname = __CLASS__;
		$results = Custom::search($modelname, 'content', $current_user_only);
		return $results;
	}

	//------------------------------------------------------------
	//------------------------------------------------------------
	//------------------------------------------------------------


}