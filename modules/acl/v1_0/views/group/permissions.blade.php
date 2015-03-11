@extends('core::layout.coremaster')


@section('page_specific')
	{{HTML::style('assets/core/bootstrap-switch.min.css')}}
	{{HTML::script('assets/core/bootstrap-switch.min.js')}}
@stop



@section('core_header')
<!--header-->
<div class="container-fluid header-title">

	<div class="row">

		<div class="col-md-6 col-sm-4 col-ss-12 col-md-offset-0">
			<div class="title">{{$title}}</div>
		</div>

		<div class="col-md-6 col-sm-8 col-xs-12 text-right">
			<div class="header-btn pull-right" >

					<div class="input-group ">
						<div class="input-group-btn">
							<a href="{{URL::previous()}}" class="btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> Back</a>
						</div>

					</div>
			</div>


			</div>
		</div>
</div>
<!--/header-->
@stop


@section('core_content')

<br clear="all" />

<!--content-->
<div class="container">

<?php
$permissions = $data['group']->permissions;
?>



	<table class="table table-striped table-hover table-bordered ">
		<thead>
			<tr>
				<th width="50">#</th>
				<th>Name</th>
				<th >Slug</th>
				<th style="width:50px;">Status</th>
			</tr>
		</thead>
		<tbody>

			@foreach ($permissions as $item)			
			<tr>
				<td>{{$item->id}}</td>
				<td>{{$item->name}}</td>
				<td>{{$item->slug}}</td>
				<td>

					@if ($item->pivot->active == 1)
					<input  class="switch-status" alt="group_permission|{{$item->pivot->id}}" type="checkbox" checked>
					@else
					<input class="switch-status" alt="group_permission|{{$item->pivot->id}}" type="checkbox">
					@endif
				</td>
			</tr>
			@endforeach


		</tbody>

	</table>


</div>
{{ View::make('core::layout.common')->with('block_name', 'ajax_toggle_status'); }}
<!--/content-->


@stop