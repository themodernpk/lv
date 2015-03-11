@extends('core::layout.coremaster')

@section('core_content')
	<?php $modules = modules_list();
	foreach($modules as $module)
	{
	if($module == 'core')
	{
		continue;
	}
	?>

	@if(View::exists($module.'::extend_core.dashboard'))
		@include($module.'::extend_core.dashboard')
	@endif

	<?php

	}
	?>

@stop
