<li><a href="{{URL::route('account')}}">Account</a></li>
<li><a href="{{URL::route('accountActivities')}}">Activities</a></li>
<li><a href="{{URL::route('notifications')}}">Notifications</a></li>
<li><a href="{{URL::route('profile',Auth::user()->id)}}">Profile</a></li>
<li class="divider"></li>
<li><a href="{{URL::route('settings')}}">Settings</a></li>