<!-- begin #header -->
<div id="header" class="header navbar navbar-default navbar-fixed-top">
    <!-- begin container-fluid -->
    <div class="container-fluid">
        <!-- begin mobile sidebar expand / collapse button -->
        <div class="navbar-header">
            <a href="{{URL::to('/')}}" class="navbar-brand"><span
                        class="navbar-logo"></span> {{Setting::value('app-name')}}</a>
            <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- end mobile sidebar expand / collapse button -->

        <!-- begin header navigation right -->
        <ul class="nav navbar-nav navbar-right hide-on-sm-device">

            <li class="dropdown">
                <a href="<?php echo URL::route('notification'); ?>?format=json" data-toggle="dropdown"
                   data-href="<?php echo URL::route('markRead'); ?>"
                   class="dropdown-toggle f-s-14 markRead">
                    <i class="fa fa-bell-o"></i>
                    <span class="label num_noti">{{Notification::count_unread()}}</span>
                </a>
                <ul class="dropdown-menu media-list pull-right animated fadeInDown">

                    <li class="dropdown-header">Notifications</li>

                    <?php
                    $notifications = Notification::get();

                    if(count($notifications) > 0 )
                    {
                    ?>
                    @foreach($notifications as $item)

                        <li class="media notifications">
                            <a href="{{$item->link}}">
                                <div class="media-left"><i class="fa <?php if ($item->icon != NULL) {
                                        echo $item->icon;
                                    } else {
                                        echo "fa-bell bg-grey";
                                    } ?> media-object"></i></div>
                                <div class="media-body">
                                    <p>
                                        @if($item->read == 1)
                                            {{$item->content}}
                                        @else
                                            <b>{{$item->content}}</b>
                                        @endif

                                    </p>

                                    <div class="text-muted f-s-11">{{Dates::showTimeAgo($item->created_at)}}</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                    <?php } ?>
                    <li class="dropdown-footer text-center">
                        <a href="{{URL::route('notifications')}}">View more</a>
                    </li>
                </ul>

            </li>

            <li class="dropdown navbar-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{User::thumbnail()}}" alt=""/>
                    <span class="hidden-xs">{{Auth::user()->name}}</span> <b class="caret"></b>
                </a>



                <ul class="dropdown-menu animated fadeInLeft">
                    <li class="arrow"></li>
                    <?php
                    $modules = modules_list();
                    foreach($modules as $module)
                    {
                    ?>
                    @if(View::exists($module.'::extend_core.usermenu'))
                        @include($module.'::extend_core.usermenu')
                    @endif
                    <?php
                    }
                    ?>
                </ul>

            </li>





        </ul>
        <!-- end header navigation right -->
    </div>
    <!-- end container-fluid -->
</div><!-- end #header -->