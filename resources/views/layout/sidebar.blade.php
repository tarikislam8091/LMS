<div class="main-navigation navbar-collapse collapse">
    <!-- start: MAIN MENU TOGGLER BUTTON -->
    <div class="navigation-toggler">
        <i class="clip-chevron-left"></i>
        <i class="clip-chevron-right"></i>
    </div>
    <!-- end: MAIN MENU TOGGLER BUTTON -->
    <!-- start: MAIN NAVIGATION MENU -->
    <ul class="main-navigation-menu">
        @if(\Auth::user()->user_role == "admin")
            <li class="{{isset($page_title) && ($page_title=='Admin Dashboard') ? 'active' : ''}} ">
                <a href="{{url('admin/dashboard')}}"><i class="clip-home-3"></i>
                    <span class="title"> Dashboard </span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="{{isset($page_title) && ($page_title=='Admin Profile') ? 'active' : ''}} ">
                <a href="{{url('admin/profile')}}"><i class="clip-user-2"></i>
                    <span class="title"> My Profile </span>
                    <span class="selected"></span>
                </a>
            </li>

            <li class="{{(isset($page_title) && (strpos($page_title,'User')!== false )) ? 'active' : ''}}">
                <a href="javascript:void (0)">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span class="title"> User Management </span><i class="icon-arrow"></i>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{url('admin/user/management?tab=create_user')}}">
                            <span class="title"> Create User </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/user/management?tab=blocked_user')}}">
                            <span class="title"> Blocked User </span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;">
                            User List <i class="icon-arrow"></i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{url('admin/user/management?tab=admins')}}">
                                    All Users
                                </a>
                            </li>
                            <li>
                                <a href="{{url('change/password/by/admin')}}">
                                    User Password Change
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>


            <li class="{{(isset($page_title) && (strpos($page_title,'Color')!== false )) ? 'active' : ''}}">
                <a href="javascript:void (0)">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span class="title"> Color </span><i class="icon-arrow"></i>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Color') !== false) ) ? 'block':'active'}};">
                    <li class="{{isset($page_title) && ($page_title=='Color Create') ? 'active' : ''}}">
                        <a href="{{url('/color/create')}}">
                            <i class="clip-plus-circle"></i>
                            <span class="title"> Add Color </span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    
                    <li class="{{isset($page_title) && ($page_title=='Get All Color Content') ? 'active' : ''}}">
                        <a href="{{url('/color/list')}}">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span class="title">Color List</span>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="{{(isset($page_title) && (strpos($page_title,'Product')!== false )) ? 'active' : ''}}">
                <a href="javascript:void (0)">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span class="title"> Product </span><i class="icon-arrow"></i>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Product') !== false) ) ? 'block':'active'}};">
                    <li class="{{isset($page_title) && ($page_title=='Product Create') ? 'active' : ''}}">
                        <a href="{{url('/product/create')}}">
                            <i class="clip-plus-circle"></i>
                            <span class="title"> Add Product </span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    
                    <li class="{{isset($page_title) && ($page_title=='Get All Product Content') ? 'active' : ''}}">
                        <a href="{{url('/product/list')}}">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span class="title">Product List</span>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>


            <li class="{{(isset($page_title) && (strpos($page_title,'Stock')!== false )) ? 'active' : ''}}">
                <a href="javascript:void (0)">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span class="title"> Stock </span><i class="icon-arrow"></i>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Stock') !== false) ) ? 'block':'active'}};">
                    <li class="{{isset($page_title) && ($page_title=='Stock Create') ? 'active' : ''}}">
                        <a href="{{url('/stock/create')}}">
                            <i class="clip-plus-circle"></i>
                            <span class="title"> Add Stock </span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    
                    <li class="{{isset($page_title) && ($page_title=='Get All Stock Content') ? 'active' : ''}}">
                        <a href="{{url('/stock/list')}}">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span class="title">Stock List</span>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>

        @else
            <li class="active">
                <a href="{{url('dashboard')}}">
                    <i class="clip-home-3"></i>
                </a>
            </li>
        @endif
    </ul>
    <!-- end: MAIN NAVIGATION MENU -->
</div>