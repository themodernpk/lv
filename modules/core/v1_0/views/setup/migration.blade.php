@extends('core::layout.master')


@section('content')

@include('core::layout.inc.header_public')




<div class="container" style="margin-top: 90px;">


	<div class="col-xs-12 text-center"><h1>Application Installation</h1><hr/></div>

	<div class="row bs-wizard" style="border-bottom:0;">

		<div class="col-xs-4 bs-wizard-step complete ">
			<div class="text-center bs-wizard-stepnum">Step 1</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Database Configuration <br/><p><small>Setup Database Configurations</small></p></div>
		</div>

		<div class="col-xs-4 bs-wizard-step active"><!-- complete -->
			<div class="text-center bs-wizard-stepnum">Step 2</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center"><strong>Run Migrations & Seeds</strong><br/><p><small>It will create database schema & insert required data.</small></p></div>
		</div>

		<div class="col-xs-4 bs-wizard-step disabled"><!-- complete -->
			<div class="text-center bs-wizard-stepnum ">Step 3</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a href="#" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Create Admin Account <br/><p><small>Final step is to create first admin account</small></p></div>
		</div>

	</div>
	<hr/>

	@include('core::layout.inc.error_msg')
	@include('core::layout.inc.flash_msg')

	<div class="text-center col-md-12">
		<a href="{{URL::route('migrationsAndSeedsRun')}}" class="btn btn-success ajaxMigrations"><i class="fa fa-spinner"></i> Run Migrations</a>
	</div>

	<script type="text/javascript">
	$( document ).ready(function() {

	    $( '.ajaxMigrations' ).click(function() {


			$(this).find('i').addClass('fa-spin');

			$.ajax({
			    type: "POST",
			    url: "<?php echo URL::route('migrationsAndSeedsRun'); ?>",
			    data: "ajax=true",
			    context: this,
			    success: function(msg)
			    {

			        if(msg != "ok")
			        {
			            alert(msg);
			        } else
			        {
						$(this).find('i').removeClass('fa-spin');
						$(this).find('i').removeClass('fa-spinner');
						$(this).find('i').addClass('fa-check');
						window.location.replace("<?php URL::route('createAdmin'); ?>");
			        }

			    }
			});



			return false;

	    });
	});
	</script>

</div>
</div>









@stop