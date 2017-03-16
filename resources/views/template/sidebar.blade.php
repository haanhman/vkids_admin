<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.jpg") }}" class="img-circle"
                     alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>{{Auth::user() != null ? Auth::user()->name : ''}}</p>
                <!-- Status -->
                <a><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{trans('sidebar.sidebar_header')}} - {{$router_controller}}</li>
            <li class="treeview {{in_array($router_controller, ['role', 'users']) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-users"></i> <span>{{trans('sidebar.user_group')}}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{$router_controller == 'users' ? 'active' : ''}}"><a href="{{route('users.index')}}"><i class="fa fa-user"></i> {{trans('sidebar.users')}}</a></li>
                    <li class="{{$router_controller == 'role' ? 'active' : ''}}"><a href="{{route('role.index')}}"><i class="fa fa-user-secret"></i> {{trans('sidebar.role')}}</a></li>
                </ul>
            </li>
            <li><a href="{{route('player.index')}}"><i class="fa fa-gamepad"></i> <span>Player</span></a></li>
            <li><a href="{{route('masterdata.index')}}"><i class="fa fa-file-excel-o"></i> <span>Master data</span></a></li>
            <li><a target="_blank" href="{{route('log.index')}}"><i class="fa fa-circle-o text-red"></i> <span>View log</span></a></li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>