<div class="navbar navbar-inverse navbar-fixed-top">
    <!-- start: TOP NAVIGATION CONTAINER -->
    <div class="container">
        <div class="navbar-header">
            <!-- start: RESPONSIVE MENU TOGGLER -->
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                <span class="clip-list-2"></span>
            </button>
            <!-- end: RESPONSIVE MENU TOGGLER -->
            <!-- start: LOGO -->
            <a class="navbar-brand" href="javascript:void(0)">
                <span style="color: #fff">ISHO TEST <i class="clip-lamp"></i> CMS</span>
            </a>
            <!-- end: LOGO -->
        </div>
        <div class="navbar-tools">
            <!-- start: TOP NAVIGATION MENU -->
            <ul class="nav navbar-right">
                <li class="dropdown current-user">
                    <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                        @if(\Auth::check())
                            @if(\Auth::user()->user_role == "admin")
                                @if((\Auth::user()->user_profile_image != ''))
                                    <img width="30px" height="30px;" src="{{asset(\Auth::user()->user_profile_image)}}" class="circle-img" >
                                @else
                                    <img width="30px" height="30px;" src="{{asset('images/default.jpg')}}" class="circle-img" >
                                @endif
                                <span class="username">{{isset(\Auth::user()->name) ? \Auth::user()->name : ''}}</span>
                                <i class="clip-chevron-down"></i>
                            @endif
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            @if(\Auth::check())
                                @if((\Auth::user()->user_role == "admin"))
                                    <a href="{{url('admin/profile')}}">
                                        <i class="clip-user-2"></i>
                                        &nbsp;My Profile
                                    </a>
                                @endif
                            @endif

                        </li>
                        <li class="divider"></li>
                        <li>
                            @if(\Auth::check())
                                @if((\Auth::user()->user_role == "admin"))
                                    <a href="{{ url('admin/profile?tab=change_password') }}">
                                        <i class="fa fa-lock"></i>
                                        &nbsp;Change Password
                                    </a>
                                @endif
                            @endif
                        </li>
                        <li>
                            @if(\Auth::check() && \Auth::user()->user_role == "admin")
                                <a href="{{url('auth/admin/logout',isset(\Auth::user()->email) ? \Auth::user()->email : '')}}">
                                    <i class="clip-exit"></i>
                                    &nbsp;Log Out
                                </a>
                            @endif
                        </li>
                    </ul>
                </li>
                <!-- end: USER DROPDOWN -->
            </ul>
            <!-- end: TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- end: TOP NAVIGATION CONTAINER -->
</div>