<?php

class ApiController extends BaseController 
{

	var $input;

	function __construct()
	{
		$this->input = Input::all();
	}

//--------------------------------------------------------------------

	private function authenticate()
	{
		if(!isset($this->input['login_email']) || !isset($this->input['login_password']))
		{

			$response['status'] = "failed";
			$response['errors'] = "API Credentials are not send";
			Activity::log($response['errors'], NULL, 'API');
			return $response;

		}
		$credentials = array('email' => $this->input['login_email'], 'password' => $this->input['login_password']);

	 	//authenticate
		if(!Auth::attempt($credentials, false))
		{
			$response['status'] = "failed";
			$response['errors'] = "API Authentication Failed";
			Activity::log($this->input['login_email']." | ".$response['errors'], NULL,  'API');
			return $response;
		} else
		{
			Activity::log("User authenticated via API", Auth::user()->id,  'API' );
			unset($this->input['login_email']);
			unset($this->input['login_password']);
		}


		//check api access
		if(!Permission::check('api-access'))
		{
			$response['status'] = "failed";
			$response['errors'] = "This account does not have API access";
			Activity::log($response['errors'], Auth::user()->id,  'API' );
			return $response;
		}

	}

//--------------------------------------------------------------------

	/**
	 * This function will find user details
	 * from email or add new user
	 * @return string
     */
	public function userCreate()
	{

		$response = $this->authenticate();

		if($response['status'] == 'failed')
		{
			return json_encode($response);
		}


		$response = User::add($this->input);

		if($response['status'] == "failed")
		{
			return json_encode($response);
		} else if($response['status'] == "success")
		{
			$response['data'] = "User account successfully created for ".$this->input['email'];
			Activity::log("Account created for ".$this->input['email'], Auth::user()->id,  'API' );
			return json_encode($response);
		}

	}

//--------------------------------------------------------------------

	public function apiTest()
	{

		$url = URL::route('apiUserCreate');

		//$query = "login_email=help@webreinvent.com&login_password=mac007";
		$query = "login_email=reg@webreinvent.com&login_password=mac007";
		$query .= "&name=Registered User";
		$query .= "&email=regis@webreinvnt.com";
		$query .= "&password=mac007";


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		$response = $server_output;
		$response = json_decode($response);
		if ($errno = curl_errno($ch)) {
			echo $errno;
		}
		curl_close ($ch);

		
		
		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}
//--------------------------------------------------------------------
//--------------------------------------------------------------------
//--------------------------------------------------------------------
//--------------------------------------------------------------------
//--------------------------------------------------------------------
//--------------------------------------------------------------------

	public static function apiHelp()
	{
		$data = array();      
		return View::make('apihelp')->with('title', 'API Help')->with('data', $data);
	}


//--------------------------------------------------------------------

} // end of class