
	<div class="pull-right">

	<?php

	if(isset($_GET['q']))
	{

		$arr['q'] = $_GET['q'];

		if(isset($_GET['start']))
		{
			$arr['start'] = $_GET['start'];
		}

		if(isset($_GET['end']))
		{
			$arr['end'] = $_GET['end'];
		}

		echo $data['list']->appends($arr)->links();

	} else
	{
		echo $data['list']->links();
	}

	?>
	</div>