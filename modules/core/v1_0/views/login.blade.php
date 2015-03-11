@extends('core::layout.master')


@section('content')

@include('core::layout.inc.header_public')

<div class="container ">

	<div class="row featurette mr_top_140">
		<div class="col-md-7">
			<div class="slogan">
				<h2>CRM App</h2>
				<h3>with a brain of it's own</h3>
				<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.</p>
			</div>


		</div>
		<div class="col-md-5">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span class="glyphicon glyphicon-lock"></span> Login</div>
					<div class="panel-body">

						@include('core::layout.inc.error_msg')
						@include('core::layout.inc.flash_msg')

						{{ Form::open(array('route' => 'postlogin', 'class' =>'form-horizontal', 'role' => 'form')) }}
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-3 control-label">
									Email</label>
									<div class="col-sm-9">
										<input type="text" value="{{Input::old('email')}}" name="email" class="form-control" id="inputEmail3" placeholder="Email" >
									</div>
								</div>
								<div class="form-group">
									<label for="inputPassword3" class="col-sm-3 control-label">
										Password</label>
										<div class="col-sm-9">
											<input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password" required>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-9">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="remember"/>
													Remember me
												</label>
											</div>
										</div>
									</div>
									<div class="form-group last">
										<div class="col-sm-offset-3 col-sm-9">
											<button type="submit" class="btn btn-success btn-sm">
												Sign in</button>
												<button type="reset" class="btn btn-default btn-sm">
													Reset</button>
												</div>
											</div>


										{{ Form::close() }}
									</div>
									<div class="panel-footer">
										Not Registred? <a href="{{URL::route('register')}}">Register here</a></div>
									</div>
								</div>
							</div>


						</div>
						@stop