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
                            <button id="search_query" type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>


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

    <br clear="all" />

    <!--content-->
    <div class="container">
        <table class="table table-striped table-hover table-bordered small">
            <?php $items = $data['list']; ?>
            @foreach ($items as $item)
                <tr>
                    <td>#{{$item->id}}</td>
                    <td>
                        @if(!is_null($item->label))
                            <span class="label" style="background-color: {{Common::stringToColorCode($item->label)}};">{{$item->label}}</span>
                        @endif
                        {{$item->content}}
                    </td>
                    <td>
                        {{ Dates::showTimeAgo($item->created_at) }}
                        @if(!is_null($item->user_id))
                            <em>by <a href="{{URL::route('profile', $item->user_id)}}">{{User::find($item->user_id)->name}}</a> </em>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        @include('core::layout.inc.pagination')

    </div>

    <!--/content-->




@stop