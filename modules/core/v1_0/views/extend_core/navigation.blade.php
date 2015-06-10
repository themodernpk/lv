<li class="@if(Request::is('dashboard')) active @endif">
    <a href="{{URL::route('dashboard')}}">
        <i class="fa fa-dashboard"></i><span>Dashboard</span>
    </a>
</li>

@if(Permission::check('show-admin-section'))
    <li class="has-sub @if(Request::is('admin/*')) expand @endif">
        <a href="javascript:;">
            <b class="caret pull-right"></b>
            <i class="fa fa-briefcase"></i>
            <span>Admin</span>
        </a>
        <ul class="sub-menu" style="@if(Request::is('admin/*')) display: block; @endif">
            <li @if(Request::is('admin/permissions')) class="active" @endif ><a href="{{URL::route('permissions')}}"><i
                            class="fa fa-flash"></i> Permissions</a></li>
            <li  @if(Request::is('admin/permissions')) class="groups" @endif><a href="{{URL::route('groups')}}"> <i
                            class="fa fa-slideshare"> </i> Groups</a></li>
            <li @if(Request::is('admin/users')) class="active" @endif><a href="{{URL::route('users')}}"> <i
                            class="fa fa-users"></i> Users</a></li>
            <li class="divider"></li>
            @if(Permission::check('view-activities'))
                <li @if(Request::is('admin/activities')) class="active" @endif><a href="{{URL::route('activities')}}"><i
                                class="fa fa-flag"></i> Activities</a></li>
            @endif
            <li @if(Request::is('admin/modules')) class="active" @endif><a href="{{URL::route('modules')}}"><i
                            class="fa fa-cubes"></i> Modules</a></li>
        </ul>
    </li>
@endif