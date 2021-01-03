@extends('layout.master')
@section('content')
    <!--SHOW ERROR MESSAGE DIV-->
    <div class="row page_row">
        <div class="col-md-12">
            @if ($errors->count() > 0 )
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <h6>The following errors have occurred:</h6>
                    <ul>
                        @foreach( $errors->all() as $message )
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('message'))
                <div class="alert alert-success" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            @if (Session::has('errormessage'))
                <div class="alert alert-danger" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('errormessage') }}
                </div>
            @endif
        </div>
    </div>
    <!--END ERROR MESSAGE DIV-->
    <div class="row ">
        <div class="col-sm-12">
            <div class="tabbable">
                <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
                    <li class="{{($tab=='create_user') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#create_user">
                            Create User
                        </a>
                    </li>
                    <li class="{{($tab=='blocked_user') ? 'active' : ''}}">
                        <a data-toggle="tab" href="#blocked_user">
                            Blocked Users
                        </a>
                    </li>
                    <li class="{{$tab=='admins' ? 'active':''}}">
                        <a data-toggle="tab" href="#admins">
                            All Users
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- PANEL FOR CREATE USER -->
                    <div id="create_user" class="tab-pane {{$tab=='create_user' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="user-form"  action="{{url('admin/user/create')}}" method="post"
                                       enctype="multipart/form-data" class="user-form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Account Info</h3>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstname2" class="control-label">
                                                    Name
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <input id="first_name" type="text" placeholder="Name"
                                                       class="form-control" name="name"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="email2" class="control-label">
                                                    Email Address
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <input type="email" placeholder="email@example.com" class="form-control" name="email">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    Mobile
                                                    <span class="symbol " aria-required="true"></span>
                                                </label>
                                                <input type="text" placeholder="User Mobile" class="form-control"
                                                       id="user_mobile" name="user_mobile"  >
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    User Type
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <select class="form-control search-select" name="user_type">
                                                    <option value="" selected="selected"> Please select user type</option>
                                                    <option value="admin"> Administrator </option>
                                                    <option value="requester"> Requester </option>
                                                    <option value="surveyer"> Surveyor </option>
                                                    <!-- <option value="participate"> Participate </option> -->
                                                    <!-- <option value="partner"> Partner </option> -->
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    User Role
                                                    <span class="symbol required" aria-required="true"></span>
                                                </label>
                                                <select class="form-control search-select" name="user_role" >
                                                    <option value="" selected="selected">Please select user role</option>
                                                    <option value="admin"> Admin </option>
                                                    <option value="requester"> Requester </option>
                                                    <option value="surveyer"> Surveyor </option>
                                                    <!-- <option value="participate"> Participate </option> -->
                                                    <!-- <option value="partner"> Partner </option> -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label">
                                                    Password
                                                    <span class="symbol" aria-required="true"></span>
                                                </label>
                                                <input type="password" name="password" placeholder="********"
                                                       class="form-control" id="password" value="" />
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">
                                                    Confirm Password
                                                    <span class="symbol required" aria-required="true"></span>
                                                </label>
                                                <input type="password" id="confirm_password" class="form-control" name="confirm_password"
                                                       placeholder="********" value=""   />
                                            </div>
                                            <div class="form-group">
                                                <label> User Profile Image </label>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new thumbnail profile_img_size" >
                                                        <img width="150px" height="150px" src="{{asset('default.jpg')}}" alt="">
                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists thumbnail profile_img_size"
                                                         style="line-height: 20px;">
                                                    </div>
                                                    <div class="user-edit-image-buttons">
													<span class="btn btn-light-grey btn-file">
														<span class="fileupload-new image-filechange">
                                                            <i class="fa fa-picture"></i> Select image
                                                        </span>
														<span class="fileupload-exists image-filechange">
                                                            <i class="fa fa-picture"></i> Change
                                                        </span>
														<input type="file" name="image_url" value="" />
													</span>
                                                        <a href="#" class="btn fileupload-exists btn-light-grey"
                                                           data-dismiss="fileupload">
                                                            <i class="fa fa-times"></i> Remove
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>
                                                By clicking Register, you are agreeing to the Policy and Terms &amp; Conditions.
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <button class="btn btn-teal btn-block" type="submit">
                                                Register <i class="fa fa-arrow-circle-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL FOR CREATE USER -->
                    <!-- PANEL FOR BLOCK USER -->
                    <div id="blocked_user" class="tab-pane {{$tab=='blocked_user' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="sample-table-1">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Name Slug</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (!empty($block_users))
                                            @foreach ($block_users as $key => $blocked_user_list)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $blocked_user_list->name }}</td>
                                                    <td>{{ $blocked_user_list->name_slug }}</td>
                                                    <td>{{ $blocked_user_list->email }}</td>
                                                    <td>{{ $blocked_user_list->user_mobile }}</td>
                                                    <th><span class="label label-danger btn-squared">{{ $blocked_user_list->status }}</span></th>
                                                    <td>
                                                        <button class="btn btn-danger btn-xs user_status btn-squared"
                                                                data-user-id="{{$blocked_user_list->id}}" data-status="active">
                                                            Deactivate
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9">
                                                    <div class="alert alert-success" role="alert">
                                                        <center><h4>No Data Available !</h4></center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL FOR BLOCK USER -->
                    <!-- PANEL FOR ADMINS -->
                    <div id="admins" class="tab-pane {{$tab=='admins' ? 'active':''}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="sample-table-1">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Name Slug</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Password</th>
                                            <th>User Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (!empty($admins))
                                            @foreach ($admins as $key => $admin_user_list)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $admin_user_list->name }}</td>
                                                    <td>{{ $admin_user_list->name_slug }}</td>
                                                    <td>{{ $admin_user_list->email }}</td>
                                                    <td>{{ $admin_user_list->user_mobile }}</td>
                                                    <td>{{ $admin_user_list->plain_password }}</td>
                                                    <td>{{ $admin_user_list->user_role }}</td>
                                                    <td>
                                                        @if($admin_user_list->status == "active")
                                                            <span class="label label-success btn-squared">{{ $admin_user_list->status }}</span>
                                                        @else
                                                            <span class="label label-danger btn-squared">{{ $admin_user_list->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($admin_user_list->status=="active")
                                                            <button class="btn btn-success btn-xs user_status btn-squared"
                                                                    data-user-id="{{$admin_user_list->id}}" data-status="deactivate">
                                                                Active
                                                            </button>
                                                        @else
                                                            <button class="btn btn-danger btn-xs user_status btn-squared"
                                                                    data-user-id="{{$admin_user_list->id}}" data-status="active">
                                                                Deactivate
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9">
                                                    <div class="alert alert-success" role="alert">
                                                        <center><h4>No Data Available !</h4></center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('JScript')
<script>
    $(function () {
        var site_url = $('.site_url').val();
        $('#user-form').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                user_mobile: {
                    required: true
                },
                user_type: {
                    required:true
                },
                password : {
                    minlength : 4,
                    required : true
                },
                confirm_password : {
                    required : true,
                    minlength : 4,
                    equalTo : "#password"
                },
                user_role: {
                    required: true
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                    element.attr("placeholder",error.text());
                }
            }
        });
        $('.user_status').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('user-id');
            var value = $(this).data('status');
            if(value == "active") {
                bootbox.dialog({
                    message: "Are you sure you want to Active this user ?",
                    title: "<i class='glyphicon glyphicon-eye-open'></i> Active !",
                    buttons: {
                        danger: {
                            label: "No!",
                            className: "btn-danger btn-squared",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        success: {
                            label: "Yes!",
                            className: "btn-success btn-squared",
                            callback: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: site_url+'/admin/change/user/status/'+id+'/'+value,
                                }).done(function(response){
                                    bootbox.alert(response,
                                        function(){
                                            location.reload(true);
                                        }
                                    );

                                }).fail(function(response){
                                    bootbox.alert(response);
                                })
                            }
                        }
                    }
                });
            } else {
                bootbox.dialog({
                    message: "Are you sure you want to Deactivate this user ?",
                    title: "<i class='glyphicon glyphicon-eye-close'></i> Deactivate !",
                    buttons: {
                        danger: {
                            label: "No!",
                            className: "btn-danger btn-squared",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        success: {
                            label: "Yes!",
                            className: "btn-success btn-squared",
                            callback: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: site_url+'/admin/change/user/status/'+id+'/'+value,
                                }).done(function(response){
                                    bootbox.alert(response,
                                        function(){
                                            location.reload(true);
                                        }
                                    );
                                }).fail(function(response){
                                    bootbox.alert(response);
                                })
                            }
                        }
                    }
                });
            }
        });
    });
</script>
@endsection