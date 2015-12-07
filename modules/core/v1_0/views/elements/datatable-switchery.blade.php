<script src="<?php echo asset_path(); ?>/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="<?php echo asset_path(); ?>/plugins/DataTables/js/dataTables.colVis.js"></script>
<script src="<?php echo asset_path(); ?>/js/table-manage-colvis.demo.min.js"></script>

<script src="<?php echo asset_path(); ?>/plugins/switchery/switchery.min.js"></script>
<script src="<?php echo asset_path(); ?>/js/form-slider-switcher.demo.min.js"></script>


<script>
    $(document).ready(function () {

        <!--datatable-->
        TableManageColVis.init();

        $( ".paginate_button" ).live("click", function(){
            var list = $("body").on('find', '#data-table tr');
            $.each(list, function (key, value) {
                var find_switch = $(this).find('.switchery');

                if (find_switch.length == 0)
                {
                    FormSliderSwitcher.init();
                }
            });
        });

        $("body").on('keyup', '.dataTables_filter input[type=search]', function () {
            var list = $("body").on('find', '#data-table tr');
            $.each(list, function (key, value) {
                var find_switch = $(this).find('.switchery');
                if (find_switch.length == 0) {
                    FormSliderSwitcher.init();
                }
            });
        });

        FormSliderSwitcher.init();
        <!--/datatable-->
    });
</script>