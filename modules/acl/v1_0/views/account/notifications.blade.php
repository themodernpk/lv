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
    <?php $notifications = $data['list']; ?>

    <!--content-->
    <div class="container">
        <a class="markRead btn btn-primary" href="{{URL::route('markRead')}}"><i class="fa fa-spinner"></i> Mark as Read</a>

        <script type="text/javascript">
            $( document ).ready(function() {
                $( '.markRead' ).click(function() {

                    $(this).children('i').addClass('fa-spin');



                    $.ajax({
                        type: "POST",
                        url: "<?php echo URL::route('markRead'); ?>",
                        context: this,
                        success: function(msg)
                        {
                            if(msg == "ok")
                            {
                                $(this).children('i').removeClass('fa-spin');
                                $(this).children('i').removeClass('fa-spinner');
                                $(this).children('i').addClass('fa-check');
                                $('.num_noti').text(0);
                            } else
                            {
                                alert(msg);
                            }

                        }
                    });


                    return false;
                });
            });
        </script>


        <br clear="both"/>
        <br clear="both"/>

        <table class="table table-striped table-hover table-bordered small">

            <tr>
                <th>#</th>
                <th>Content</th>
                <th>Time</th>
            </tr>

            @foreach($notifications as $item)
            <tr>
                <td>{{$item->id}}</td>
                @if($item->read == 1)
                <td><a href="{{$item->link}}">{{$item->content}}</a></td>
                @else
                    <td><strong><a href="{{$item->link}}">{{$item->content}}</a></strong></td>
                @endif
                <td><span><i class="fa fa-clock-o"></i> {{Dates::showTimeAgo($item->created_at)}}</span></td>
            </tr>

                @endforeach

        </table>


        @include('core::layout.inc.pagination')

    </div>

    <!--/content-->




@stop