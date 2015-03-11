@extends('core::layout.coremaster')


@section('page_specific')
@stop



@section('core_header')
    <!--header-->
    <div class="container-fluid header-title">

        <div class="row">

            <div class="col-md-4 col-sm-4 col-ss-12 col-md-offset-0">
                <div class="title">{{$title}}</div>
            </div>


        </div>

    </div>
    </div>
    <!--/header-->
@stop


@section('core_content')


    <br clear="all" />
    <?php $user = $data['user']; ?>




    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-lg-offset-3 col-lg-6">
                <div class="well profile_con">
                    <div class="col-sm-12">
                        <div class="col-xs-12 col-sm-8">
                            <h2>{{$user->name}}</h2>
                            <p><strong>Email: </strong> {{$user->email}}</p>
                            <p><strong>Mobile: </strong> {{$user->mobile}}</p>
                            <p><strong>Last Login: </strong> {{Dates::showTimeAgo($user->lastlogin)}}</p>
                            <p><strong>Created on: </strong> {{Dates::showTimeAgo($user->created_at)}}</p>
                        </div>
                        <div class="col-xs-12 col-sm-4 text-center">
                            <figure>
                                <img src="{{User::thumbnail($user->id)}}" alt="" class="img-circle img-responsive">
                                <figcaption class="ratings">
                                    <p>Ratings
                                        <a href="#">
                                            <span class="fa fa-star"></span>
                                        </a>
                                        <a href="#">
                                            <span class="fa fa-star"></span>
                                        </a>
                                        <a href="#">
                                            <span class="fa fa-star"></span>
                                        </a>
                                        <a href="#">
                                            <span class="fa fa-star"></span>
                                        </a>
                                        <a href="#">
                                            <span class="fa fa-star-o"></span>
                                        </a>
                                    </p>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                    <div class="col-xs-12 divider text-center">
                        <div class="col-xs-12 col-sm-4 emphasis">
                            <h2><strong> 20,7K </strong></h2>
                            <p><small>Followers</small></p>
                            <button class="btn btn-success btn-block"><span class="fa fa-plus-circle"></span> Follow </button>
                        </div>
                        <div class="col-xs-12 col-sm-4 emphasis">
                            <h2><strong>245</strong></h2>
                            <p><small>Following</small></p>
                            <button class="btn btn-info btn-block"><span class="fa fa-user"></span> View Profile </button>
                        </div>
                        <div class="col-xs-12 col-sm-4 emphasis">
                            <h2><strong>43</strong></h2>
                            <p><small>Snippets</small></p>
                            <div class="btn-group dropup btn-block">
                                <button type="button" class="btn btn-primary"><span class="fa fa-gear"></span> Options </button>
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu text-left" role="menu">
                                    <li><a href="#"><span class="fa fa-edit pull-right"></span> Edit</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#"><span class="fa fa-warning pull-right"></span>Report this user for spam</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@stop