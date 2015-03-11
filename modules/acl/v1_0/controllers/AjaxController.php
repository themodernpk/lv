<?php



class AjaxController extends Controller 
{


	//--------------------------------------------------------------------

	function ajax_toggle_status()
	{

		$input = \Input::all();

		$identifier = $input['identifier'];

		$identifier_d = explode("|", $identifier);


		if($identifier_d[2] == 'deactive')
		{
			$status = 0;
		} else if($identifier_d[2] == 'active')
		{
			$status = 1;
		}

		\DB::table($identifier_d[0])
		->where('id', $identifier_d[1])
		->update(array('active' => $status));

		echo "ok";
		

	}

	//--------------------------------------------------------------------
	
	function ajax_update_col()
	{

		$input = Input::all();

		$name_d = explode('|', $input['name']);

		$table = $name_d[0];
		$column = $name_d[1];
		
		$value = $input['value'];
		$id = $input['pk'];

		$update_array[$column] =  $value;

		if(isset($name_d[2]) && $name_d[2] != "")
		{
			$update_array['slug'] = Str::slug($value);
		}

		try {
			DB::table($table)
			->where('id', $id)
			->update($update_array);
			echo "ok";
		}catch(\Exception $e){
			echo "error while update record";
		}

		

	}

	//--------------------------------------------------------------------
	function ajax_delete()
	{
		$input = Input::all();
		$identifier_d = explode("|", $input['identifier']);
		$instance = $identifier_d[0]::find($identifier_d[1]);
		$instance->delete();

		echo "ok";

	}
	//--------------------------------------------------------------------

	function ajax_update_col_href()
	{

		$input = \Input::all();


		$identifier = explode('|', $input['identifier']);



		$table = $identifier[0];
		$column = $identifier[1];
		$id = $identifier[2];
		$value = $identifier[3];
		

		try {
			\DB::table($table)
			->where('id', $id)
			->update(array($column => $value));
			echo "ok";
		}catch(\Exception $e){
			echo "error while update record";
		}

		die();


	}

	//--------------------------------------------------------------------
	function markRead()
	{

		try {
			DB::table('notifications')
				->where('user_id', Auth::user()->id)
				->update(array('read' => 1));
			echo "ok";
		}catch(Exception $e){
			echo "error while update record";
		}

		die();

	}
	//--------------------------------------------------------------------
	//--------------------------------------------------------------------
	//--------------------------------------------------------------------

	

} // end of class