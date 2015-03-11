@extends('core::layout.master')


@section('content')

@include('core::layout.inc.header_public')

<div class="container ">

	<div class="row featurette mr_top_140">
		<div class="col-md-7">
			<div class="slogan">
				<h2>Best start</h2>
				<h3>for your business</h3>
				<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.</p>
			</div>


		</div>
		<div class="col-md-5">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span class="glyphicon glyphicon-pencil"></span> Register</div>
					<div class="panel-body">

						@include('core::layout.inc.error_msg')
						@include('core::layout.inc.flash_msg')

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
												Sign Up</button>
												<button type="reset" class="btn btn-default btn-sm">
													Reset</button>
												</div>
											</div>
										{{ Form::close() }}
									</div>
									<div class="panel-footer">
										Already have account? <a href="{{URL::route('login')}}">Signin here</a></div>
									</div>
								</div>
							</div>


						</div>
						@stop