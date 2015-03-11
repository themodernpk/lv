@extends('core::layout.coremaster')


@section('page_specific')
	{{HTML::style('assets/core/bootstrap-switch.min.css')}}
	{{HTML::script('assets/core/bootstrap-switch.min.js')}}
@stop



@section('core_header')
<!--header-->
<div class="container-fluid header-title">

	<div class="row">

		<div class="col-md-4 col-sm-4 col-ss-12 col-md-offset-0">
			<div class="title">{{$title}}</div>
		</div>

		<div class="col-md-6 col-sm-8 col-xs-12 pull-right">
			<div class="header-btn">

				{{ Form::open(array('method' => 'GET')) }}

					@include('core::layout.inc.daterange')

					<div class="input-group">

						<input type="text" class="form-control search_query" value="{{Input::get('q')}}" name="q">
						<div class="input-group-btn">
							<button id="search_query" type="submit" class="btn btn-sm btn-success"><i class="fa fa-search"></i></button>



							<a class="btn btn-lg btn-primary" href="{{URL::route('addUsers')}}"><i class="fa fa-plus"></i> Add</a>


						</div>
					</div>

					{{Form::hidden('start', null, array('class' => "start") )}}
					{{Form::hidden('end', null, array('class' => "end"))}}

					{{ Form::close() }}

				</div>



			</div>
		</div>
</div>
<!--/header-->
@stop


@section('core_content')

	<a href="http://bootswatch.com/flatly/#" class="btn btn-primary btn-lg">Large button</a>
	<a href="http://bootswatch.com/flatly/#" class="btn btn-primary">Default button</a>
	<a href="http://bootswatch.com/flatly/#" class="btn btn-primary btn-sm">Small button</a>
	<a href="http://bootswatch.com/flatly/#" class="btn btn-primary btn-xs">Mini button</a>

<br clear="all" />

<!--content-->
<div class="container">
	<table class="table table-striped table-hover table-bordered ">
		<thead>
			<tr>
				<th width="30">#</th>
				<th>Full Name</th>
				<th>Email</th>
				<th>Mobile</th>
				<th>Group</th>
				<th>Last Login</th>
				<th style="width:100px;">Actions</th>
				<th style="width:50px;">Status</th>
				<th width="100">Updated</th>
				<th width="100">Created</th>
			</tr>
		</thead>
		<tbody>
			<?php $items = $data['list']; ?>
			@foreach ($items as $item)
			<tr>
				<td>{{$item->id}}</td>
				<td><a href="#" data-pk="{{$item->id}}" data-name="users|name" class="editable">{{$item->name}}</a></td>
				<td>{{$item->email}}</td>
				<td>{{$item->mobile}}</td>
				<td>{{$item->group->name}}</td>
				<td>{{Dates::dateformat($item->lastlogin)}}</td>
				<td>

					<a class="btn btn-success btn-xs" data-toggle="tooltip" title="Edit" href="{{URL::route('editUser', array('id' => $item->id))}}" ><i class="fa fa-pencil"></i></a>
					<a class="btn btn-danger btn-xs ajax_delete" data-toggle="tooltip" title="Delete" href="#" alt="User|{{$item->id}}" ><i class="fa fa-times"></i></a>

				</td>
				<td>

					
					@if ($item->active == 1)
					<input  class="switch-status" alt="users|{{$item->id}}" type="checkbox" checked>
					@else
					<input class="switch-status" alt="users|{{$item->id}}" type="checkbox">
					@endif
					

				</td>
				<td>{{Dates::showTimeAgo($item->updated_at)}}</td>
				<td>{{Dates::showTimeAgo($item->created_at)}}</td>
			</tr>

			@endforeach




		</tbody>

	</table>

	@include('core::layout.inc.pagination')

</div>
{{ View::make('core::layout.common')->with('block_name', 'ajax_toggle_status'); }}
{{ View::make('core::layout.common')->with('block_name', 'ajax_update_col'); }}
{{ View::make('core::layout.common')->with('block_name', 'ajax_delete'); }}
<!--/content-->


@stop