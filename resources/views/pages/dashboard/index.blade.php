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

        <div class="col-md-12 dashboard_btn">
            <div id="dashboard">
              <div class="to"> Manage</div>
            </div>

            <div class="col-md-3"  onclick="location.href='{{url('/admin/user/management')}}';">
                <div class="core-box">
                    <div class="heading">
                        <i class="clip-user-4 circle-icon circle-green"></i>
                        <h2>Manage Users</h2>
                    </div>
                    
                </div>
            </div>

            <div class="col-md-3" onclick="location.href='{{url('/color/create')}}';">
                <div class="core-box">
                    <div class="heading">
                        <i class="clip-clip circle-icon circle-red"></i>
                        <h2>Color</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3"  onclick="location.href='{{url('/product/create')}}';">
                <div class="core-box">
                    <div class="heading">
                        <i class="clip-database circle-icon circle-bricky"></i>
                        <h2>Product</h2>
                    </div>
                    
                </div>
            </div>

            <div class="col-md-3"  onclick="location.href='{{url('/stock/create')}}';">
                <div class="core-box">
                    <div class="heading">
                        <i class="fa fa-list-alt circle-icon circle-teal"></i>
                        <h2>Stock</h2>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
@endsection
@section('JScript')
    <script>

    </script>
@endsection