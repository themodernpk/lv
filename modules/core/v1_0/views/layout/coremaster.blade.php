<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{$title}}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!--common css & js-->
{{--	<link href='http://fonts.googleapis.com/css?family=Lato:400,700,900,300,400italic' rel='stylesheet' type='text/css'>--}}
	{{HTML::style('assets/core/bootstrap.min.css')}}
	{{HTML::style('assets/core/style.css')}}
	{{HTML::style('assets/core/font-awesome.css')}}

	{{HTML::script('assets/core/jquery.min.js')}}
	{{HTML::script('assets/core/bootstrap.min.js')}}
	<!--common css & js-->

	<!--page specific css & js-->
	@yield('page_specific')
	<!--page specific css & js-->

	<?php
	$url = URL::current();
	$base = URL::to('/');

	$uri = str_replace($base, "", $url);
	$body_class = str_replace('/', " ", $uri);
	$body_class = trim($body_class);
	?>

</head>
<body class="{{$body_class}}">

<!--header-->

<!--navigation-->
<div role="navigation" class="navbar navbar-default navbar-fixed-top">
	<div class="navbar-header">
		<button data-target=".navbar-responsive-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a href="{{URL::to('/')}}" class="brand">{{Setting::value('app-name')}}</a>
	</div>
	<div class="navbar-collapse collapse navbar-responsive-collapse">


		<ul class="nav navbar-nav navbar-right">
			<li ><a href="{{URL::route('dashboard')}}">Dashboard</a></li>
			<?php $modules = modules_list();

			foreach($modules as $module)
				{
					if($module == 'core' || $module == 'acl')
						{
							continue;
						}
					?>

				@if(View::exists($module.'::extend_core.navigation'))
					@include($module.'::extend_core.navigation')
				@endif
			<?php

				}
			?>
			@include('acl::extend_core.navigation')
		</ul>
	</div>
</div>
<!--/navigation-->



<!--breadcrumb-->
<ul class="breadcrumb" >
	<li><a href="#">Home</a></li>
	<li><a href="#">Library</a></li>
	<li class="active">Data</li>
</ul>
<!--/breadcrumb-->

<!--/header-->

<!--content part-->

@yield('core_header')

<div class="container mr_top_15">
	@include('core::layout.inc.flash_msg')
	@include('core::layout.inc.error_msg')
</div>

@yield('core_content')
<!--content part-->


{{HTML::script('assets/core/common.js')}}
</body>
</html>





