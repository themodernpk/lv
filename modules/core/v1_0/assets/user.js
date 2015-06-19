
    (function($) {

        $('#addNew').submit(function (e) {
            e.preventDefault();

            var href        =   $('#href').val();
            var input_data  =   $( this ).serialize();

            //alert(input_data);

            $.ajax({
                type: "POST",
                url: href,
                data: input_data,
                error: function (request, status, error) {

                    console.log(input_data);
                },
                success: function (response) {

                    var object = JSON.parse(response);

                    if (object.status == "failed") {

                        //console.log( object.errors.name );
                        $.each(object.errors, function(key, value) {

                            $.gritter.add({
                                title: '<label class="btn btn-xs btn-icon btn-circle btn-danger"><i class="fa fa-times"></i></label> '+key + ': ' + value + '<br/>',
                                sticky: false,
                            });

                        });
                    }
                    if (object.status == "success") {


                        $('#modal-dialog').modal('hide');
                        $('#name').val('');
                        $('#email').val('');
                        $('#password').val('');
                        $('#mobile').val('');
                        $.gritter.add({
                            title: '<label class="btn btn-xs btn-icon btn-circle btn-success"><i class="fa fa-check"></i></label>User Added Successfully',
                            sticky: false,
                        });

                        location.reload();

                    }


                    //console.log(response);
                }
            });
        });
    })(jQuery);

    (function($) {
        $('#editform').submit(function (e) {
            e.preventDefault();

            var edit_href   =   $('#edithref').val();
            var input_data  =   $( this ).serialize();

            $.ajax({
                type: "POST",
                url: edit_href,
                data: input_data,
                error: function (request, status, error) {

                    console.log(request);
                    console.log(status);
                    console.log(error);
                },
                success: function (response) {
                    alert(response);

                    var object = JSON.parse(response);

                    if (object.status == "failed") {

                        //console.log( object.errors.name );
                        $.each(object.errors, function(key, value) {

                            $.gritter.add({
                                title: '<label class="btn btn-xs btn-icon btn-circle btn-danger"><i class="fa fa-times"></i></label> '+key + ': ' + value + '<br/>',
                                sticky: false,
                            });

                        });
                    }

                    if (object.status == "success") {

                        $('#edit').modal('hide');
                        $.gritter.add({
                            title: '<label class="btn btn-xs btn-icon btn-circle btn-success"><i class="fa fa-check"></i></label>User Eddited Successfully',
                            sticky: false,
                        });
                        location.reload();

                    }



                    //console.log(response);
                }
            });
        });
    })(jQuery);
