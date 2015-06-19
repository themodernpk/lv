          $('.markRead').click(function () {

                
                var href = $(this).data('href');
                alert(href);

                $(this).children('i').addClass('fa-spin');
                $(this).children('i').removeClass('fa-bell-o');
                $(this).children('i').addClass('fa-spinner');

                $.ajax({
                    type: "POST",
                    url: href,
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