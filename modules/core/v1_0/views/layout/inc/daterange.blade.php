<!--datepicker-->
{{HTML::script('assets/core/moment.min.js')}}
{{HTML::script('assets/core/daterangepicker.js')}}
{{HTML::style('assets/core/daterangepicker-bs3.css')}}




<div id="reportrange" class="pull-left">
    <i class="fa fa-calendar fa-lg"></i>
    <span><?php 
    $start = Input::get('start');
    $end = Input::get('end');
        if(!empty($start))
        {
            $start = Dates::dateformat($start);
            echo $start;
        }

        if(!empty($end))
        {
            echo " - ".Dates::dateformat($end);
        }

        if(empty($start) && empty($end))
        {
            echo "All Time";
        }

        ?> <b class="caret"></b>
    </span>
</div>

<script>

    $('#reportrange').daterangepicker(
            {
                ranges: {
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                startDate: moment().subtract('days', 29),
                endDate: moment()
            },
            function(start, end)
            {
                $('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
            }
    );


    $('#reportrange').on('apply.daterangepicker', function(ev, picker)
    {

        var start = picker.startDate.format('YYYY-MM-DD');
        var end = picker.endDate.format('YYYY-MM-DD');

        $(".start").val(start);
        $(".end").val(end);

    });

    $('#reportrange').on('cancel.daterangepicker', function(ev, picker)
    {

        $(".start").val("");
        $(".end").val("");
        $('#reportrange span').html("All Time");

    });
</script>
<!--/datepicker-->