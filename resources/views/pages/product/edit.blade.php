@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    Edit Color
                    <div class="panel-tools">
                        <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                        </a>
                        <a class="btn btn-xs btn-link panel-close" href="#">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="panel-body">

                    <ul class="nav nav-tabs tab-bricky">
                        <li>
                            <a href="{{url('/product/create')}}">
                                <i class="green fa fa-bell"></i> Add Product
                            </a>
                        </li>
                        <li>
                            <a href="{{url('/product/list')}}">
                                <i class="green clip-feed"></i> Product List
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="panel-body">

                    @if($errors->count() > 0 )
                        <div class="alert alert-danger btn-squared">
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
                        <div class="alert alert-success btn-squared" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    @if(Session::has('errormessage'))
                        <div class="alert alert-danger btn-squared" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('errormessage') }}
                        </div>
                    @endif

                    <form role="form" class="form-horizontal" action="{{ url('/product/update/id-'.$edit->id) }}"
                          id="product" method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <strong>Product Name</strong>
                                <span class="symbol required" aria-required="true"></span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="product_name" value="{{isset($edit->product_name)? $edit->product_name :''}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <strong>Product Brand</strong>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="brand" value="{{isset($edit->brand)? $edit->brand :''}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <strong>Product Feature</strong>
                            </label>
                            <div class="col-sm-6">
                                <textarea name="product_feature" class="form-control" cols="10" rows="7">{{isset($edit->product_feature)? $edit->product_feature :''}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <strong>Product Description</strong>
                            </label>
                            <div class="col-sm-6">
                                <textarea name="product_description" class="form-control" cols="10" rows="7">{{isset($edit->product_description)? $edit->product_description :''}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                <strong>Product Feature Image</strong>
                            </label>
                            <div class="col-sm-9">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <?php
                                    if ($edit->product_feature_image != '') {
                                        $product_feature_image = $edit->product_feature_image;
                                    } else {
                                        $product_feature_image = '/assets/images/default.jpg';
                                    }
                                    ?>
                                    <div class="fileupload-new thumbnail" style="width: 150px; height: 150px;">
                                        <img width="150px" height="150px" src="{{asset($product_feature_image)}}" alt="">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists"
                                         style="max-width: 150px; max-height: 150px; line-height: 20px;">
                                    </div>
                                    <div class="user-edit-image-buttons">
                                        <span class="btn btn-light-grey btn-file">
                                            <span class="fileupload-new"><i class="fa fa-picture"></i>
                                                Select image
                                            </span>
                                            <span class="fileupload-exists"><i class="fa fa-picture"></i>
                                                Change
                                            </span>
                                            <input type="file" name="product_feature_image" value="{{ $edit->product_feature_image }}">
                                        </span>
                                        <a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
                                            <i class="fa fa-times"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                            </div>
                            <div class="col-sm-4">
                                <input class="btn btn-danger btn-squared" name="reset" value="Reset" type="reset">
                                <input class="btn btn-success btn-squared" name="submit" value="Update" type="submit">
                            </div>
                            <div class="col-sm-4">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('JScript')
    <script>
        $(function () {
            $('#product').validate({
                rules: {
                    product_name: {
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

        })
    </script>
@endsection