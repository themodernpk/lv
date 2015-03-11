<?php
/*
We are using this file because this allow me to include specific javascript code with PHP code while if include this in javascript then I will not able to include PHP code. If I this code on the specific page then I can't reuse it.
*/
?>

{{-- ----------------------------------------------------------------------------- --}}

@if ($block_name == 'ajax_toggle_status')
<script type="text/javascript">
//enable and disable function
$( document ).ready(function() {

    $('.switch-status').on('switchChange.bootstrapSwitch', function(event, state) 
    {

        if(state == true)
        {
            var todo = "active";
        } else
        {
            var todo = "deactive";
        }


        var identifier = $(this).attr('alt');
        var identifier = identifier+"|"+todo;

        $.ajax({
            type: "POST",
            url: "<?php echo URL::route('ajax_toggle_status'); ?>",
            data: "identifier="+identifier,
            context: this,
            success: function(msg)
            {

                if(msg != "ok")
                {
                    alert(msg);
                }

            }
        });

    });

});
</script>
@endif
{{-- ------------------------------------------------------------------------------- --}}
@if ($block_name == 'ajax_update_col')
{{-- 
To inline editing like name etc
--}}
{{HTML::style('assets/core/bootstrap-editable.css')}}
{{HTML::script('assets/core/bootstrap-editable.js')}}

<script type="text/javascript">
        $( document ).ready(function() {
            $('.editable').editable({
                type: 'text',
                url: '<?php echo URL::route('ajax_update_col'); ?>',
                success: function(data) {
                    if(data != 'ok')
                    {
                        $('#msg').removeClass('alert-success').removeClass('hide').addClass('alert-danger').show().children('p').html(data);
                    } else
                    {
                        $('#msg').hide();
                    }
                }

            });
        });
    </script>
@endif
{{-- ------------------------------------------------------------------------------- --}}
@if ($block_name == 'ajax_delete')
<script type="text/javascript">
$( document ).ready(function() {
    $( '.ajax_delete' ).click(function() {
        $(this).children('i').addClass('fa-refresh fa-spin');
        var identifier = $(this).attr('alt');

        $.ajax({
            type: "POST",
            url: "<?php echo URL::route('ajax_delete'); ?>",
            data: "identifier="+identifier,
            context: this,
            success: function(msg)
            {

                if(msg != "ok")
                {
                    alert(msg);
                } else
                {
                    $(this).parent().parent().fadeOut('slow');
                }

            }
        });


        return false;
    });
});
</script>
@endif
{{-- ------------------------------------------------------------------------------- --}}
@if ($block_name == 'diable_copy')
<script type="text/javascript" language="JavaScript">
<!--
//Disable right mouse click Script
function isEmpty(str)   
{
    var t = /\S/
    return !t.test(str)
}

// Function called when form submits
function disabletext(e){
    return false
}

function reEnable(){
    return true
}

//if the browser is IE4+
document.onselectstart=new Function ("return false")

//if the browser is NS6
if (window.sidebar){
    document.onmousedown=disabletext
    document.onclick=reEnable
}
// Code finish 

</script>
@endif
{{-- ---------------------------------------------------------------------------- --}}
@if ($block_name == 'typeahead_company')
<script>
$(document).ready(function() {
    var companies;

    companies = new Bloodhound({
        datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.company); },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: [
        <?php 
        $items = \Company::all();
        foreach ($items as $item) {

            echo "{ company: '$item->name' },";
        }
        ?>
        ]
    });

    companies.initialize();

    $('.typeahead_company').typeahead(null, {
        displayKey: 'company',
        source: companies.ttAdapter()
    });

});

</script>
@endif

{{-- --------------------------------------------------------------------------- --}}