@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- start: FORM VALIDATION 2 PANEL -->
            <div class="panel panel-default btn-squared">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i>
                    Edit Stock
                    <div class="panel-tools">
                        <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                        </a>
                        <a class="btn btn-xs btn-link panel-close" href="#">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
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


                    <form role="form" class="form-horizontal" action="{{ url('/stock/update/id-'.$edit->id) }}"
                          id="stock" method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong> Product </strong>
                                <span class="symbol required" aria-required="true"></span>
                            </label>
                            <div class="col-sm-6">
                                <select id="form-field-select-3" class="form-control search-select" name="product_id">
                                    @if(!empty($all_product))
                                    @foreach($all_product as $key =>$value)
                                        <option {{($edit->product_id == $value->id) ? 'selected' : ''}} value="{{$value->id}}">{{$value->product_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>Color</strong>
                                <span class="symbol required" aria-required="true"></span>
                            </label>
                            <div class="col-sm-6">
                                <select id="form-field-select-3" class="form-control search-select" name="color_id">
                                    @if(!empty($all_color))
                                    @foreach($all_color as $key =>$list)
                                        <option {{($edit->color_id == $list->id) ? 'selected' : ''}}  value="{{$list->id}}">{{$list->color_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>Num Of Product</strong>
                                <span class="symbol required" aria-required="true"></span>
                            </label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="num_of_product" value="{{isset($edit->num_of_product)? $edit->num_of_product:''}}" >
                            </div>
                        </div>                                            

                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>Buy Price</strong>
                                <span class="symbol required" aria-required="true"></span>
                            </label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="buy_price" value="{{isset($edit->buy_price)? $edit->buy_price:''}}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>Demo Price</strong>
                                <span class="symbol required" aria-required="true"></span>
                            </label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="demo_price" value="{{isset($edit->demo_price)? $edit->demo_price:''}}" >
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>Sell Price</strong>
                                <span class="symbol required" aria-required="true"></span>
                            </label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="sell_price" value="{{isset($edit->sell_price)? $edit->sell_price:''}}" >
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>Description</strong>
                            </label>
                            <div class="col-sm-6">
                                <textarea name="stock_description" class="form-control" cols="10" rows="7">{{isset($edit->stock_description)? $edit->stock_description:''}}</textarea>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                <strong>Product Image</strong>
                            </label>
                            <div class="col-sm-9">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <?php
                                    if ($edit->product_image != '') {
                                        $product_image = $edit->product_image;
                                    } else {
                                        $product_image = '/images/default.jpg';
                                    }
                                    ?>
                                    <div class="fileupload-new thumbnail" style="width: 150px; height: 150px;">
                                        <img width="150px" height="150px" src="{{asset($product_image)}}" alt="">
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
                                            <input type="file" name="product_image" value="{{ $edit->product_image }}">
                                        </span>
                                        <a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
                                            <i class="fa fa-times"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <div class="col-sm-2">
                            </div>
                            <label class="checkbox-inline col-sm-9">
                                <input type="checkbox" value="1" class="grey" name="product_special" {{!empty($edit->product_special) && ($edit->product_special == 1 )? 'checked':''}}>
                                <strong>Is Special? </strong>
                            </label>
                        </div>
           
                        <div class="form-group">
                            <div class="col-sm-5">
                            </div>
                            <div class="col-sm-4">
                                <input class="btn btn-danger btn-squared" name="reset" value="Reset" type="reset">
                                <input class="btn btn-success btn-squared" name="submit" value="Update" type="submit">
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
            $('#campaign').validate({
                rules: {
                    campaign_name: {
                        required: true
                    },
                    campaign_category: {
                        required: true
                    },
                    campaign_requester_id:{
                        required: true
                    },
                    campaign_start_date:{
                        required: true
                    },
                    campaign_end_date:{
                        required: true
                    },
                    campaign_num_of_days:{
                        required: true
                    },
                    campaign_target_user:{
                        required: true
                    },
                    campaign_total_cost:{
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