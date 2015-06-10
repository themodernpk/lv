@if ($block_name == 'markRead')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.markRead').click(function () {

                $(this).children('i').addClass('fa-spin');
                $(this).children('i').removeClass('fa-bell-o');
                $(this).children('i').addClass('fa-spinner');

                $.ajax({
                    type: "POST",
                    url: "<?php echo URL::route('markRead'); ?>",
                    context: this,
                    success: function (msg) {
                        if (msg == "ok") {
                            $(this).children('i').removeClass('fa-spin');
                            $(this).children('i').removeClass('fa-spinner');
                            $(this).children('i').addClass('fa-check');
                            $('.num_noti').text(0);

                        } else {
                            alert(msg);
                        }

                    }
                });

            });
        });
    </script>
@endif
@if ($block_name == 'switch_button')
    <!-- ### For Activate Or Deactivate of a switch-->
    <script>
        $(document).ready(function () {

            $('.BSswitch').on('change', function () {
                var active_status = $(this).is(":checked");
                var exception = $(this).attr("data-exception");
                var update_table = $(this).attr("data-update-table");
                if (update_table == 'undefined') {
                    update_table = false;
                }
                var id = $(this).val();
                var table = $('#table').val();

                //we are excluding admin, its id is 1
                if (exception == true) {
                    $.gritter.add({
                        title: '<label><i class="icon-user-unfollow"></i></label> Action cannot be performed over "Admin".',
                        sticky: false,
                    });
                    $(this).attr('checked', 'checked');
                }
                else {
                    $(".pace").removeClass('pace-inactive');

                    $.ajax({
                        type: "POST",
                        data: 'id=' + id + '&table=' + table + '&active_status=' + active_status + '&update_table=' + update_table,
                        url: "<?php echo URL::route('ajax_toggle_status'); ?>",
                        success: function (response) {

                            var object = JSON.parse(response);
                            if (object.status == "failed") {
                                object.errors.forEach(function (entry) {
                                    $.gritter.add({
                                        title: '<label class="btn btn-xs btn-icon btn-circle btn-danger"><i class="fa fa-times"></i></label> ' + object.errors,
                                        sticky: false,
                                    });
                                });

                            }


                            if (object.status == "success") {
                                object.data.forEach(function (entry) {
                                    $.gritter.add({
                                        title: '<label class="btn btn-xs btn-icon btn-circle btn-success"><i class="fa fa-check"></i></label> ' + object.data,
                                        sticky: false,
                                    });
                                });
                            }

                            $(".pace").addClass('pace-inactive');

                        }
                    });
                }

                return false;

            });
        });
    </script>
@endif

@if ($block_name == 'row_delete')
    <!-- ### Deleting Single Row-->
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).find("a[id^='delete_']").live('click', function () {
                var delete_id = this.id.split('_')[1];
                var exception = $(this).attr("data-exception");
                var parent = $(this).parent().parent();

                if (exception == true) {
                    $.gritter.add({
                        title: 'Action cannot be performed over "Admin".',
                        sticky: false,
                    });
                    $(this).attr('checked', 'checked');
                }
                else {
                    var id = delete_id;
                    var table = $('#table').val();

                    $.ajax({
                        type: "POST",
                        data: 'action=delete &id=' + id + '&table=' + table,
                        url: "<?php echo URL::route('ajax_delete'); ?>",
                        beforeSend: function () {
                            $(".pace").removeClass('pace-inactive');
                        },
                        success: function (response) {
                            var object = JSON.parse(response);

                            if (object.status == "failed") {
                                object.errors.forEach(function (entry) {
                                    $.gritter.add({
                                        title: '<label class="btn btn-xs btn-icon btn-circle btn-danger"><i class="fa fa-times"></i></label> ' + object.errors,
                                        sticky: false,
                                    });
                                });
                            }

                            if (object.status == "success") {
                                object.data.forEach(function (entry) {
                                    $.gritter.add({
                                        title: '<label class="btn btn-xs btn-icon btn-circle btn-success"><i class="fa fa-check"></i></label> ' + object.data,
                                        sticky: false,
                                    });
                                });
                                parent.fadeOut('slow', function () {
                                    $(this).remove();
                                });
                            }

                            $(".pace").addClass('pace-inactive');
                        }
                    });
                }
                return false;

            });
        });
    </script>
@endif


@if ($block_name == 'row_edit')
    <!--### Editing Single Row-->
    <script type="text/javascript">
        $(document).ready(function () {
            $('.editable').editable({
                type: 'text',
                url: "<?php echo URL::route('ajax_edit'); ?>",
                success: function (response) {
                    if (response == "failed") {
                        $.gritter.add({
                            title: '<label class="btn btn-xs btn-icon btn-circle btn-danger"><i class="fa fa-times"></i></label>  Undefined Error',
                            sticky: false,
                        });
                        $(this).attr('checked', 'checked');
                    }

                    if (response == "success") {
                        $.gritter.add({
                            title: '<label class="btn btn-xs btn-icon btn-circle btn-success"><i class="fa fa-check"></i></label>  Edited Successfully',
                            sticky: false,
                        });
                    }
                    $(".pace").addClass('pace-inactive');
                }
            });
        });
    </script>
@endif