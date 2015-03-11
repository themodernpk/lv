@extends('core::layout.master')


@section('content')

@include('core::layout.inc.header_public')




<div class="container" style="margin-top: 90px;">

	<div class="col-xs-12 text-center"><h1>Application Installation</h1><hr/></div>

	<div class="row bs-wizard" style="border-bottom:0;">

		<div class="col-xs-4 bs-wizard-step active">
			<div class="text-center bs-wizard-stepnum">Step 1</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center"><strong>Database Configuration</strong> <br/><p><small>Setup Database Configurations</small></p></div>
		</div>

		<div class="col-xs-4 bs-wizard-step disabled"><!-- complete -->
			<div class="text-center bs-wizard-stepnum">Step 2</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Run Migrations & Seeds<br/><p><small>It will create database schema & insert required data.</small></p></div>
		</div>

		<div class="col-xs-4 bs-wizard-step disabled"><!-- complete -->
			<div class="text-center bs-wizard-stepnum ">Step 3</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Create Admin Account <br/><p><small>Final step is to create first admin account</small></p></div>
		</div>


	</div>
	<hr/>

	@include('core::layout.inc.error_msg')
	@include('core::layout.inc.flash_msg')





	<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-pencil"></span> Database Setup</div>
		<div class="panel-body">



			{{ Form::open(array('route' => 'setupPost', 'class' =>'form-horizontal', 'role' => 'form')) }}

			<div class="form-group">
				<label class="col-sm-3 control-label">
					Hostname </label>
				<div class="col-sm-9">
					<input type="text" name="hostname" class="form-control" placeholder="Hostname" value="{{Input::old('hostname')}}" required>
				</div>
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">
					Database</label>
				<div class="col-sm-9">
					<input type="text" name="dbname" class="form-control"  value="{{Input::old('dbname')}}" placeholder="Database Name" autocomplete="off" required>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">
					Username</label>

				<div class="col-sm-9">
					<input type="text" name="dbuser" class="form-control" value="{{Input::old('dbuser')}}" placeholder="DB Username" required>

				</div>

			</div>


			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">
					Password</label>
				<div class="col-sm-9">
					<input type="password" name="dbpass" class="form-control" placeholder="dbpass" >
				</div>
			</div>


			<div class="form-group last">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" class="btn btn-success btn-sm">
						Setup</button>
					<button type="reset" class="btn btn-default btn-sm">
						Reset</button>
				</div>
			</div>
			{{ Form::close() }}
		</div>

	</div>
	</div>


</div>
</div>









@stop