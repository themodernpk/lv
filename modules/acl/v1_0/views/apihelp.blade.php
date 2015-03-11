@extends('layout.master')

@section('page_specific')
{{HTML::style('html/assets/systax_highlighter.css')}}

{{HTML::script('html/assets/systax_highlighter.js')}}
{{HTML::script('html/assets/systax_highlighter_php.js')}}
<script type="text/javascript">SyntaxHighlighter.all();</script>
@stop


@section('content')

@include('layout.inc.header_public')

<div class="container ">

	<div class="row featurette mr_top_140">
		<div class="col-md-12">
			@include('layout.inc.error_msg')
			@include('layout.inc.flash_msg')



			<p><h4 class="text-success">Following url must be set for authentication:</h4></p>
			<table class="table table-striped table-bordered ">
				<tr><td width="250"><b>login_email</b></td><td>{Required} Email for validation</td></tr>
				<tr><td><b>login_password</b></td><td>{Required} Set valid password address</td></tr>
			</table>

			<p><h4 class="text-success">To Create/Fetch User:</h4></p>

			<table class="table table-striped table-bordered ">
				<tr width="250"><td><b>API URL</b></td><td>{{URL::route('apiUserCreate')}}</td></tr>

				<tr><td><b>email</b></td><td>{Required} Set valid email address</td></tr>
				<tr><td><b>group_id</b></td><td>{Required} | If not set than application will use default group_id</td></tr>
				<tr><td><b>username</b></td><td>If not set than application will generator username from email</td></tr>
				<tr><td><b>password</b></td><td>If not set than application will generator random password</td></tr>
				<tr><td><b>name</b></td><td>If not set than application will use email</td></tr>
				<tr><td><b>mobile</b></td><td></td></tr>
				<tr><td colspan="2" class="center"><b>Following response will be generated in json format</b></td></tr>
				<tr><td><b>status</b></td><td>failed | success</td></tr>
				<tr><td><b>errors</b></td><td>errors in array format</td></tr>
				<tr><td><b>data</b></td><td>it variable will contain actuall response data</td></tr>
			</table>


			<p><h4 class="text-success">How to execute:</h4></p>




			<pre class="brush: php;">


				<?php 

				$html = '
				// api url
				$url = "'.URL::route('apiUserCreate').'";

				// build query string
				$query = "login_email=demo@email.com&login_password=demopassword";
				$query .= "&name=Client Name";
				$query .= "&email=passemail@gmail.com";

				// execute via curl
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

				// response generated
				print_r($response);

				'; 
				echo $html; ?>
			</pre>

		</div>

	</div>


</div>
@stop