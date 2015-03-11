
@if(Permission::check('admin'))
	<li class="dropdown">
		<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="glyphicon glyphicon-cog"></i> Admin <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="{{URL::route('permissions')}}">Permissions</a></li>
			<li><a href="{{URL::route('groups')}}">Groups</a></li>
			<li><a href="{{URL::route('users')}}">Users</a></li>
			<li class="divider"></li>
			<li><a href="{{URL::route('activities')}}">Activities</a></li>
			<li><a href="{{URL::route('modules')}}">Modules</a></li>
		</ul>
	</li>
@endif

<li class="dropdown">
	<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="glyphicon glyphicon-user"></i> {{Auth::user()->name}} <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="{{URL::route('account')}}">Account</a></li>
		<li><a href="{{URL::route('accountActivities')}}">Activities</a></li>
		<li><a href="{{URL::route('notifications')}}">Notifications</a></li>
		<li><a href="{{URL::route('settings')}}">Settings</a></li>
		<li class="divider"></li>
		<li><a href="{{URL::route('logout')}}">Logout</a></li>
	</ul>
</li>


<ul class="nav navbar-nav noticebar navbar-left">

	<li class="dropdown">
		<a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
			<i class="fa fa-bell"></i>
			<span class="navbar-visible-collapsed"></span>
			<span class="badge badge-primary num_noti">{{Notification::count_unread()}}</span>
		</a>

		<ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">
			<li class="nav-header">
				<div class="pull-left">
					Notifications
				</div>

				<div class="pull-right">
					<a class="markRead" href="{{URL::route('markRead')}}"><i class="fa fa-spinner"></i> Mark as Read</a>
				</div>
			</li>

			<script type="text/javascript">
			$( document ).ready(function() {
			    $( '.markRead' ).click(function() {

					$(this).children('i').addClass('fa-spin');



					$.ajax({
					    type: "POST",
					    url: "<?php echo URL::route('markRead'); ?>",
						context: this,
					    success: function(msg)
					    {
					        if(msg == "ok")
					        {
								$(this).children('i').removeClass('fa-spin');
								$(this).children('i').removeClass('fa-spinner');
								$(this).children('i').addClass('fa-check');
								$('.num_noti').text(0);
					        } else
					        {
					            alert(msg);
					        }

					    }
					});


					return false;
			    });
			});
			</script>


			<?php
			$notifications = Notification::get();

			if(count($notifications) > 0 )
			{
			?>
			@foreach($notifications as $item)
				<li>
					<a href="{{$item->link}}" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <i class="fa <?php if($item->icon != NULL){ echo $item->icon; } else { echo "fa-bell text-danger"; } ?>"></i>
                  </span>
                  <span class="noticebar-item-body">
                    {{--<strong class="noticebar-item-title">Sync Error</strong>--}}
					  @if($item->read == 1)
                    <span class="noticebar-item-text">{{$item->content}}</span>
					  @else
						  <strong class="noticebar-item-title">{{$item->content}}</strong>
					  @endif


                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> {{Dates::showTimeAgo($item->created_at)}}</span>
                  </span>
					</a>
				</li>

			@endforeach
			<?php } ?>


			<li class="noticebar-menu-view-all">
				<a href="{{URL::route('notifications')}}">View All Notifications</a>
			</li>
		</ul>
	</li>


</ul>