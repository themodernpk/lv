$('.BSswitch').on('change', function () {
                var active_status = $(this).is(":checked");
                var exception = $(this).attr("data-exception");
                var update_table = $(this).attr("data-update-table");
                var href = $(this).attr("data-href");
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
                        url: href,
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
                            /*$('').modal('show');
                            $(this).modal('show');

*/
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



$(document).find("a[id^='delete_']").live('click', function () {
                var delete_id = this.id.split('_')[1];
                var exception = $(this).attr("data-exception");
                var href = $(this).attr("data-href");
                //alert(href);
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
                        url: href,
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


