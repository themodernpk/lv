(function($) {



    //-------------------------------------------------------
    $("#createFrom").submit(function(e)
    {
        e.preventDefault();
        var data = $( this).serialize();
        var url = $( this ).attr('action');
        var debug = true;
        var that = $(this).find('.loader');
        Common.showSpinner(that);
        if ( $(this).parsley().isValid() == false )
        {
            Common.hideSpinner(that);
            return false;
        }

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            context: this,
            error: function (xhr, ajaxOptions, thrownError)
            {
                Common.ajaxErrorHandler(xhr, ajaxOptions, thrownError, that, debug);
            },
            success: function (response)
            {
                Common.ajaxSuccessHandler(response, that, debug);
                Common.hideSpinner(that);

                var object = JSON.parse(response);

                if(object.status == "success")
                {
                    $(this).find("input, textarea, select").val("");
                }
            }

        });

    });
    //-------------------------------------------------------

    $(".viewItem").click(function(e)
    {
        e.preventDefault();

        var modalID = $(this).attr('data-target');
        var pk = $(this).attr('data-pk');
        var url = $(this).attr('data-href');

        $(modalID+" .modalLoader").show();

        var debug = true;
        var that = $(this);

        $(modalID+" .modalLoader").show();

        $(modalID+" .row").html("");

        $.ajax({
            type: "POST",
            url: url,
            data: "id="+pk,
            context: this,
            error: function (xhr, ajaxOptions, thrownError)
            {
                Common.ajaxErrorHandler(xhr, ajaxOptions, thrownError, that, debug);
            },
            success: function (response)
            {
                response = Common.ajaxSuccessHandler(response, that, debug, false);
                object = JSON.parse(response);
                var getHTML = "";
                var i = 0;

                if(object.status == "success")
                {

                    //var count = Common.count(object.data);
                    var count = Object.keys(object.data).length;
                    var halfCount = Math.ceil(count/2);

                    console.log(count+"|"+halfCount);

                    $.each(object.data, function(key, value)
                    {

                        if(i == 0)
                        {
                            getHTML += '<div class="col-md-6"><dl class="dl-horizontal">';
                        }

                        if(key == "created_by")
                        {
                            key = "Created By";
                            if(object.data.created_by !== null)
                            {
                                value = object.data.created_by.name;
                            }else
                            {
                                value = "None";
                            }
                        }

                        if( key == "modified_by")
                        {
                            key = "Modified By";
                            if(object.data.modified_by !== null)
                            {
                                value = object.data.modified_by.name;
                            }else
                            {
                                value = "None";
                            }
                        }

                        if(key == "deleted_by")
                        {
                            key = "Deleted By";
                            if(object.data.deleted_by !== null)
                            {
                                value = object.data.deleted_by.name;
                            } else
                            {
                                value = "None";
                            }

                        }

                        if(key == "created_at")
                        {
                            key = "Created At";
                            value = object.data.created_at;
                        }

                        if(key == "updated_at")
                        {
                            key = "Updated At";
                            value = object.data.updated_at;
                        }

                        if(key == "deleted_at")
                        {
                                key = "Deleted At";
                                value = object.data.deleted_at;
                        }

                        if(key == "enable" && value == 1)
                        {
                            key = "Enable";
                            value = 'Yes';
                        } else if(key == "enable" && value == 0)
                        {
                            key = "Enable";
                            value = 'No';
                        }

                        getHTML += '<dt>'+key+': </dt><dd>'+value+'</dd>';

                        if(i == (halfCount-1))
                        {
                            getHTML += '</dl></div>';
                        }

                        i++;

                        if(i == halfCount)
                        {
                            i = 0
                        }


                    });

                    $(modalID+" .row").html(getHTML);

                    $(modalID+" .modalLoader").hide();
                }
            }

        });


    });

    //-------------------------------------------------------
    $(".updateItem").click(function(e)
    {
        e.preventDefault();

        var modalID = $(this).attr('data-target');
        var pk = $(this).attr('data-pk');
        var url = $(this).attr('data-href');
        $(modalID+" form")[0].reset();

        $(modalID+" .modalLoader").show();

        var debug = true;
        var that = $(this);

        $(modalID+" .modalLoader").show();

        $.ajax({
            type: "POST",
            url: url,
            data: "id="+pk,
            context: this,
            error: function (xhr, ajaxOptions, thrownError)
            {
                Common.ajaxErrorHandler(xhr, ajaxOptions, thrownError, that, debug);
            },
            success: function (response)
            {
                console.log(response);
                response = Common.ajaxSuccessHandler(response, that, debug, false);
                object = JSON.parse(response);

                if(object.status == "success")
                {
                    $.each(object.data, function(key, value)
                    {
                        if(key == 'enable')
                        {
                            return;
                        }
                        $(modalID+" input[name="+key+"]").val(value);
                    });

                    var $enableCheck = $(modalID+" input[name=enable]");
                    $enableCheck.filter('[value='+object.data.enable+']').prop('checked', true);

                    $(modalID+" .modalLoader").hide();
                }
            }

        });


    });
    //-------------------------------------------------------
    $("#formUpdate").submit(function(e)
    {
        e.preventDefault();

        var data = $( this).serialize();
        var url = $( this ).attr('action');
        var debug = true;
        var that = $(this).find('.loader');
        Common.showSpinner(that);

        if ( $(this).parsley().isValid() == false )
        {
            Common.hideSpinner(that);
            return false;
        }

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            context: this,
            error: function (xhr, ajaxOptions, thrownError)
            {
                Common.ajaxErrorHandler(xhr, ajaxOptions, thrownError, that, debug);
            },
            success: function (response)
            {
                Common.ajaxSuccessHandler(response, that, debug);

            }

        });

    });
    //-------------------------------------------------------
    //-------------------------------------------------------


})(jQuery);// end of jquery