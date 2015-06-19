@extends('core::layout.backend')
@section('page_specific_head')

@stop

@section('page_specific_head')

@stop


@section('content')

    <!-- begin page-header -->
    <h1 class="page-header">{{$title}}</h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">

        <!-- begin col-10 -->
        <div class="col-md-12">

            <!-- # modal-dialog for password-->
          <!--   <div class="modal fade" id="modal-dialog1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {{ Form::open(array('route' => 'updateAccount', 'class' =>'form', 'id'=>"pass" ,'method' =>'POST')) }}
                        <div class="modal-header">
                            <button type="button" class="close" id="close-password" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Change Password</h4>
                        </div>
                        <input type="hidden" name="id" value="{{Auth::user()->id}}" />
                        <input type="hidden" name="name" value="{{Auth::user()->name}}" />

                        <div class="modal-body">
                            <div class="form-group">
                                {{Form::label('password', 'Password', array('class' => ''))}}
                                {{ Form::password('password', array('class' => 'form-control ','id'=>'password-reset','placeholder' => 'Password', 'required')) }}
                            </div>
                        </div>
                        <!-- modal footer -->
                        <!-- <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-success loader"><i class="fa fa-edit"></i>
                                Submit
                            </button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>  -->
            <!-- #end of modal-dialog for password-->


            <!-- open model when click on change button for changing the setting-->
            <!-- #modal-dialog -->
            <div class="modal fade" id="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {{ Form::model($data ,array('route' => 'updateAccount', 'class' =>'form','id'=>'form' ,'method' =>'POST')) }}

                        <input type="hidden" name="id" value="{{Auth::user()->id}}" />
                        <input type="hidden" name="apikey" value="{{Auth::user()->apikey}}" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Update Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                {{Form::label('name', 'Name', array('class' => ''))}}
                                {{ Form::text('name', Auth::user()->name, array('class' => 'form-control ', 'placeholder' => 'Name', 'required')) }}
                            </div>

                            <div class="form-group">
                                {{Form::label('email', 'E-Mail Address', array('class' => ''))}}
                                {{ Form::email('email', Auth::user()->email, array('class' => 'form-control ', 'placeholder' => 'Email', 'required')) }}
                            </div>

                            <div class="form-group">
                                {{Form::label('mobile', 'Mobile', array('class' => ''))}}
                                {{ Form::number('mobile', Auth::user()->mobile, array('class' => 'form-control ', 'placeholder' => 'Mobile', 'required')) }}
                            </div>
                              <div class="form-group">
                                {{Form::label('username', 'Username', array('class' => ''))}}
                                {{ Form::text('username', Auth::user()->username, array('class' => 'form-control ', 'placeholder' => 'Username', 'required')) }}
                            </div>
                              <div class="form-group">
                                {{Form::label('password', 'Password', array('class' => ''))}}
                                {{ Form::password('password', array('class' => 'form-control ','id'=>'password-reset','placeholder' => 'Password', 'required')) }}
                            </div>
                        </div>
                        <!-- modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-success loader"><i class="fa fa-edit"></i>
                                Submit
                            </button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <!-- #end of modal-dialog -->

            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                           data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                           data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                           data-click="panel-remove"><i class="fa fa-times"></i></a>

                    </div>
                    <h4 class="panel-title">Account Detail of {{ $id=Auth::user()->name}}</h4>
                </div>

                <div class="panel-body">

                    <!--  form for activate/Deactivte delete/thrash   -->
                    {{ Form::open(array('route' => 'bulkAction', 'class' =>'form', 'method' =>'POST')) }}

                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <div class="btn-group">

                                    <!-- Change password Button -->
                                    <!--<a class="btn btn-sm btn-success" href="#modal-dialog1" data-toggle="modal"
                                       class="btn btn-sm btn-success "><i class="fa fa-key"></i>
                                        Change Password
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div> -->

                    <br/>

                    <div class="row">

                        <div class="table-responsive">
                            <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>

                                <tbody>


                                <!-- get the User Information and list them -->


                                <tr class="" id="{{Auth::user()->id}}">
                                    <td>{{Auth::user()->id}}</td>
                                    <td><a class="editable" data-pk="{{Auth::user()->id}}"
                                           data-name="{{get_table_name()}}" id="edit-{{Auth::user()->id}}"
                                           href="#">{{Auth::user()->name}}</a></td>
                                    <td>{{Auth::user()->username}}</td>
                                    <td>{{Auth::user()->email}}</td>
                                    <td>{{Auth::user()->mobile}}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-info" href="#modal-dialog" data-toggle="modal"
                                           class="btn btn-sm btn-success "><i class="fa fa-pencil"></i>
                                            Update
                                        </a>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- hidden field to send table name
                       // ist argument is hidden field name which is table
                       // used to send data to server side
                       // through name attribue we get value of this field which is table name
                       // 2nd argument is value which is actually table name in encrypted form
                       // 3rd argument is id to perfrorm client side operation  ,like ajax to switch status
                     -->
                    {{Form::hidden('table', get_table_name(), array('id' => 'table')) }}
                    {{Form::close()}}

                </div>
            </div>
            <!-- end panel -->


            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">API Details</h4>
                </div>

                <div class="panel-body">

                    <table id="data-table" class="table table-striped table-bordered">

                        <tr><th width="200">API Username</th><td>{{Auth::user()->username}}</td></tr>
                        <tr><th>API Key</th><td><textarea class="form-control bg-grey-lighter" rows="5">{{Auth::user()->apikey}}</textarea></td></tr>

                        </table>


                        </div>
                    </div>


                </div>
            </div>
            <!-- end panel -->




        </div>
        <!-- end col-10 -->
    </div>
    <!-- end row -->

@stop

@section('page_specific_foot')

    <script src="<?php echo asset_path(); ?>/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="<?php echo asset_path(); ?>/plugins/DataTables/js/jquery.dataTables.js"></script>
    <script src="<?php echo asset_path(); ?>/plugins/DataTables/js/dataTables.colVis.js"></script>
    <script src="<?php echo asset_path(); ?>/js/table-manage-colvis.demo.min.js"></script>
    <script src="<?php echo asset_path(); ?>/plugins/switchery/switchery.min.js"></script>
    <script src="<?php echo asset_path(); ?>/js/form-slider-switcher.demo.min.js"></script>
    <script src="<?php echo asset_path(); ?>/plugins/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="<?php echo asset_path(); ?>/js/apps.min.js"></script>

    <script>
        $(document).ready(function () {

        });
    </script>
@stop
