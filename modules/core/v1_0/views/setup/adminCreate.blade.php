@extends('core::layout.master')


@section('content')

@include('core::layout.inc.header_public')




<div class="container" style="margin-top: 90px;">

	<div class="col-xs-12 text-center"><h1>Application Installation</h1><hr/></div>

	<div class="row bs-wizard" style="border-bottom:0;">

		<div class="col-xs-4 bs-wizard-step complete ">
			<div class="text-center bs-wizard-stepnum">Step 1</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Database Configuration <br/><p><small>Setup Database Configurations</small></p></div>
		</div>

		<div class="col-xs-4 bs-wizard-step complete"><!-- complete -->
			<div class="text-center bs-wizard-stepnum">Step 2</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Run Migrations & Seeds<br/><p><small>It will create database schema & insert required data.</small></p></div>
		</div>

		<div class="col-xs-4 bs-wizard-step active"><!-- complete -->
			<div class="text-center bs-wizard-stepnum ">Step 3</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center"><strong>Create Admin Account</strong> <br/><p><small>Final step is to create first admin account</small></p></div>
		</div>


	</div>
	<hr/>

	@include('core::layout.inc.error_msg')
	@include('core::layout.inc.flash_msg')


	<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-pencil"></span> Create Admin Account</div>
		<div class="panel-body">
			{{ Form::open(array('route' => 'postregister', 'class' =>'form-horizontal', 'role' => 'form')) }}

			<div class="form-group">
				<label class="col-sm-3 control-label">
					Full Name</label>
				<div class="col-sm-9">
					<input type="text" name="name" class="form-control" placeholder="First Name" value="{{Input::old('name')}}" required>
				</div>
			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">
					Email</label>
				<div class="col-sm-9">
					<input type="email" name="email" class="form-control"  value="{{Input::old('email')}}" placeholder="Email" >
				</div>
			</div>

			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">
					Password</label>
				<div class="col-sm-9">
					<input type="password" name="password" class="form-control" placeholder="Password" required>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">
					Mobile</label>

				<div class="col-sm-9">
					<input type="text" name="mobile" class="form-control" value="{{Input::old('mobile')}}" placeholder="Mobile Number" maxlength="10">

				</div>

			</div>

			<div class="form-group last">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" class="btn btn-success btn-sm">
						Create</button>
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