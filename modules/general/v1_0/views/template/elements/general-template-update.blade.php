<!--modal-->
<?php $prefix = "profile"; ?>
{{HtmlHelper::modal(array('title' => "Update Details", 'modal_id'=>"ModalUpdate", 'class' => 'modal-dialog-lg'))}}

{{ Form::open(array('class' =>'form form-horizontal', 'route' => $data->prefix.'-update',
'id'=>'formUpdate' ,'method' =>'POST', 'data-parsley-validate')) }}
        <!--modal body-->
<div class="modal-body">

    <div class="row-fluid">

        <div class="col-sm-4">


            <div class="form-group col-sm-12">
                {{ Form::text('name', null, array('class' => 'form-control ',
                'placeholder' => 'Name', 'required')) }}
            </div>

            <div class="form-group col-sm-12">
                {{ Form::text('category', null, array('class' => 'form-control categoryAutoComplete',
                'placeholder' => 'Category', 'required', 'autocomplete' => 'off')) }}
                <p>E.g. Lead, General, Thread, Mail etc</p>
            </div>

            @include('general::template.elements.general-template-tags')

        </div>

        <div class="col-sm-8">

            <div class="form-group">
                {{ Form::text('subject', null, array('class' => 'form-control ',
                'placeholder' => 'Subject')) }}
            </div>

            <div class="form-group">
                <textarea name="message" class="content-editor"></textarea>
            </div>


            <div class="form-group">
                <label class="col-md-3 col-sm-4 control-label">Choose Status:</label>
                <div class="col-md-9 col-sm-8">
                    <div class="radio">
                        <label>
                            <input type="radio" name="enable" value="1" required>
                            Enable
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="enable" value="0" required>
                            Disable
                        </label>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
<!--/modal body-->
<br clear="all"/>
<div class="modal-footer">

    <button type="submit" id="group_submit"
            class="btn btn-sm btn-success loader"><i class="fa fa-save"></i> Submit
    </button>

    <a href="{{Request::url()}}" class="btn btn-sm btn-white">Close & Refresh</a>

</div>
{{Form::hidden('format', 'json')}}
{{Form::hidden('id')}}
{{ Form::close() }}


{{HtmlHelper::modalClose()}}
<!--/modal-->
