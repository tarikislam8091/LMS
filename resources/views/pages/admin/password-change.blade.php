@extends('layout.master')
@section('content')
    <!--ERROR MESSAGE-->
    <div class="row ">
        <div class="col-md-12">
            @if($errors->count() > 0 )
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
            @if(Session::has('message'))
                <div class="alert alert-success" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('message') }}
                </div>
            @endif
            @if(Session::has('errormessage'))
                <div class="alert alert-danger" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('errormessage') }}
                </div>
            @endif
        </div>
    </div>
    <!--END ERROR MESSAGE-->
    <!--PAGE CONTENT -->


    <div class="row">
        <div class="col-md-6 col-md-offset-3 change_password">

            <div class="col-md-12 info"><h1><i class="fa fa-lock"></i> User Password Change</h1>
                <form action="{{url('change/password/by/admin')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}" >
                    <div class="row">
                        <div class="col-md-6" style="padding-right:0">
                            <span><i>New Password</i></span>
                            <input type="password" name="new_password" placeholder="New Password" class="form-control" value="">
                        </div>
                        <div class="col-md-6">
                            <span><i>Confirm Password</i></span>
                            <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" value="">
                        </div>
                    </div>
                    <div class="input-group" style="margin-top:7px">
                        <input type="email" name="email" placeholder="Email" class="form-control" value="">
                        <span class="input-group-btn">
							<button class="btn btn-blue" type="submit">
								<i class="fa fa-chevron-right"></i>
							</button>
						</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>

    <!--END PAGE CONTENT-->
@endsection