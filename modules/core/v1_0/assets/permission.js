$('#permission_submit').click(function () 
            {
                var name = $('#name').val();
                var href = $(this).data('href');
                context: this,
                $.ajax({
                    type: "POST",
                    url: href,
                    data:'name=' + name,
                    error: function (request, status, error) {
                            console.log(request.responseText);
                    },
                    success: function (response) {
                       
                       //console.log(response);
                       var object = JSON.parse(response); 
                        

                            if (object.status == "failed") {

                                //console.log( object.errors.name );
                                 $.each(object.errors, function() {
                                    
                                    $.gritter.add({
                                       title: '<label class="btn btn-xs btn-icon btn-circle btn-danger"><i class="fa fa-times"></i></label> ' + object.errors.name ,
                                        sticky: false,
                                     });

                                 });
                            } 
                            else
                            {
                            
                                if (object.status == "success") {
                                   
                                     $('#modal-dialog').modal('hide');
                                     $("#name").val('');
                                     //console.log( object.data.name );
                                                                           
                                        $.gritter.add({
                                        title: '<label class="btn btn-xs btn-icon btn-circle btn-success"><i class="fa fa-check"></i></label>Permission '+object.data.name+' added successfully',
                                        sticky: false,
                                        });
                                   
                                    
                                }
                                $('#modal-dialog').modal('show');
                            }
                            
                            //console.log(object);
                            

                    }
                });
                
                return false;

            });