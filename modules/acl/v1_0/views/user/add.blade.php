@extends('core::layout.coremaster')


@section('page_specific')
@stop



@section('core_header')
    <!--header-->
    <div class="container-fluid header-title">

        <div class="row">

            <div class="col-md-6 col-sm-4 col-ss-12 col-md-offset-0">
                <div class="title">{{$title}}</div>
            </div>

            <div class="col-md-6 col-sm-8 col-xs-12">
                <div class="header-btn ">

                    <div class="input-group ">
                        <div class="input-group-btn">
                            <a href="{{URL::route('users')}}" class="btn btn-sm btn-primary pull-right"><i
                                        class="fa fa-angle-double-left"></i> Back</a>
                        </div>

                    </div>
                </div>


            </div>

        </div>
    </div>
    <!--/header-->
@stop


@section('core_content')

    <br clear="all"/>

    <!--content-->
    <div class="container">

        <div class="container">
            {{ Form::open(array('route' => 'postUsers', 'role' => 'form')) }}

            <!--left-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                <div class="form-group">
                    <label class="col-sm-4 control-label pull"><span class="pull-right">Full Name</span></label>

                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control" placeholder="Full Name"
                               value="{{Input::old('name')}}">
                    </div>
                    <br clear='both'/>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label pull"><span class="pull-right">Email</span></label>

                    <div class="col-sm-8">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                               value="{{Input::old('email')}}" required>
                    </div>
                    <br clear='both'/>
                </div>


                <div class="form-group">
                    <label class="col-sm-4 control-label pull"><span class="pull-right">Password</span></label>

                    <div class="col-sm-8">
                        <input type="password" name="password" class="form-control" placeholder="Password"
                               value="{{Input::old('password')}}" required>
                    </div>
                    <br clear='both'/>
                </div>

            </div>
            <!--/left-->

            <!--right-->
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                <div class="form-group">
                    <label class="col-sm-4 control-label pull"><span class="pull-right">Mobile</span></label>

                    <div class="col-sm-8">
                        <input type="text" name="mobile" class="form-control" placeholder="Mobile"
                               value="{{Input::old('mobile')}}">
                    </div>
                    <br clear='both'/>
                </div>


                <div class="form-group">
                    <label class="col-sm-4 control-label pull"><span class="pull-right">User Group</span></label>

                    <div class="col-sm-8">

                        <select name="group_id" class="form-control">
                            {{Custom::selectbox('Group')}}
                        </select>

                    </div>
                    <br clear='both'/>
                </div>


            </div>
            <!--/right-->

            <!--submit-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="form-group pull-right">

                    <div class="col-sm-8">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                    </div>
                    <br clear="all">
                </div>


                <div class="pull-right">

                </div>

            </div>
            <!--submit-->
            {{ Form::close() }}


        </div>




    </div>
    <!--/content-->


    @stop


<!--content-->


