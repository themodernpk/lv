@if(Permission::check('general-module-access'))
<li  class="has-sub @if(Request::is('general/*')) expand @endif">

    <a href="javascript:;">
        <b class="caret pull-right"></b>
        <i class="fa fa-book"></i>
        <span>General v1.0</span>
    </a>

    <ul class="sub-menu" style="@if(Request::is('general/*')) display: block; @endif">


        @if(Permission::check('general-label-read'))
        <li @if(Request::is('general/label/index')) class="active" @endif ><a href="{{URL::route('general-label-index')}}"><i
                        class="fa fa-tags"></i> Labels</a></li>
        @endif


        @if(Permission::check('general-template-read'))
            <li @if(Request::is('general/template/index')) class="active" @endif ><a href="{{URL::route('general-template-index')}}"><i
                            class="fa fa-tags"></i> Templates</a></li>
        @endif

    </ul>
</li>
@endif