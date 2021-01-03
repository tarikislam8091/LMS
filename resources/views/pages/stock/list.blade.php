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
                <ul id="myTab" class="nav nav-tabs tab-bricky">
                    <li class="">
                        <a href="{{url('/stock/create')}}">
                            <i class="green fa fa-bell"></i> Add Stock
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{url('/stock/list')}}">
                            <i class="green clip-feed"></i> Stock List
                        </a>
                    </li>
                </ul>
                <div class="panel-body">
                    <form method="get"  action="{{url('/stock/list')}}">
                    
                        <div class="col-md-2">
                            <div class="form-group has-feedback ">
                                <label for="search_from">
                                    <strong>Search by status : </strong>
                                </label>

                                <select  class="form-control search-select" name="product_id">
                                    <option value="">&nbsp;Please Select a Type</option>
                                    @if(!empty($all_product))
                                    @foreach($all_product as $key =>$value)
                                        <option {{(isset($_GET['product_id']) && ($_GET['product_id']==$value->id)) ? 'selected' : ''}} value="{{$value->id}}">{{$value->product_name}}</option>
                                    @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top:22px;">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-squared" value="Search">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-content">
                    <!-- PANEL FOR Album LIST -->
                    <div id="album_list" class="tab-pane active">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover" id="sample-table-1">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Color Name</th>
                                        <th>Qr Code</th>
                                        <th>Number Of Product</th>
                                        <th>Buy Price</th>
                                        <th>Demo Price</th>
                                        <th>Sell Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (isset($all_content)&& count($all_content)>0)
                                        <?php $page=isset($_GET['page'])? ($_GET['page']-1):0;?>
                                        @foreach ($all_content as $key => $list)
                                            <tr>
                                                <td>{{($key+1+($perPage*$page))}}</td>
                                                <td>{{ $list->product_name }}</td>
                                                <td>{{ $list->color_name }}</td>
                                                <td>{{ $list->qr_code }}</td>
                                                <td>{{ $list->num_of_product }}</td>
                                                <td>{{ $list->buy_price }}</td>
                                                <td>{{ $list->demo_price }}</td>
                                                <td>{{ $list->sell_price }}</td>

                                                <td>
                                                    @if($list->stock_status == 1)
                                                        <span class="label label-success btn-squared">Published</span>
                                                    @else
                                                        <span class="label label-danger btn-squared">Unpublished</span>
                                                    @endif
                                                </td>

                                                <td style="width:14%">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-purple"><i class="fa fa-wrench"></i> Action</button><button data-toggle="dropdown" class="btn btn-purple dropdown-toggle"><span class="caret"></span></button><ul class="dropdown-menu" role="menu">
                                                            <li>
                                                                <a href="{{url('/stock/edit/id-'.$list->id)}}">
                                                                    <i class="fa fa-pencil"></i> Edit
                                                                </a>
                                                            </li>

                                                            <li>

                                                                @if($list->stock_status == 1)
                                                                    <a class="status-change" data-stock-publish-status="0" data-stock-id="{{ $list->id}}" title="Click for unpublish">
                                                                        <i class="fa fa-lock"></i> Unpublish
                                                                    </a>
                                                                @else
                                                                    <a class="status-change " title="Click for publish" data-stock-publish-status="1" data-stock-id="{{ $list->id}}">
                                                                        <i class="fa fa-unlock"></i> Publish
                                                                    </a>
                                                                @endif
                                                            </li>

                                                            <li>
                                                                <a class="stock-delete" data-stock-id="{{$list->id}}">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                                </a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="16">
                                                <div class="alert alert-success" role="alert">
                                                    <h4>No Data Available !</h4>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                {{isset($pagination)?$pagination:''}}
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

            //publish and unpublish
            $('.status-change').on('click', function (e) {
                e.preventDefault();
                var campaign_publish_status = $(this).data('stock-publish-status');
                var id = $(this).data('stock-id');
                if(campaign_publish_status == 0) {
                    bootbox.dialog({
                        message: "Are you sure you want to unpublish ?",
                        title: "<i class='glyphicon glyphicon-eye-close'></i> Unpublish !",
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
                                        url: site_url+'/stock/change/status/'+id+'/'+campaign_publish_status
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

                } else if(campaign_publish_status == 5) {
                    bootbox.dialog({
                        message: "Are you sure you want to complete ?",
                        title: "<i class='glyphicon glyphicon-eye-close'></i> Unpublish !",
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
                                        url: site_url+'/stock/change/status/'+id+'/'+campaign_publish_status
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
                        message: "Are you sure you want to publish ?",
                        title: "<i class='glyphicon glyphicon-eye-open'></i> Publish !",
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
                                        url: site_url+'/stock/change/status/'+id+'/'+campaign_publish_status
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


            // stock delete
            $('.stock-delete').on('click', function (e) {
                e.preventDefault();
                var id = $(this).data('stock-id');
                bootbox.dialog({
                    message: "Are you sure you want to delete this stock ?",
                    title: "<i class='glyphicon glyphicon-trash'></i> Delete !",
                    buttons: {
                        success: {
                            label: "No",
                            className: "btn-success btn-squared",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        danger: {
                            label: "Delete!",
                            className: "btn-danger btn-squared",
                            callback: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: site_url+'/stock/delete/id-'+id,
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
            });


            // stock Push
            $('.stock-push').on('click', function (e) {
                e.preventDefault();
                var campaign_id = $(this).data('stock-id');
                bootbox.dialog({
                    message: "Are you sure you want to send stock Push Notification ?",
                    title: "<i class='clip-paperplane'></i> Send Push !",
                    buttons: {
                        success: {
                            label: "No",
                            className: "btn-success btn-squared",
                            callback: function() {
                                $('.bootbox').modal('hide');
                            }
                        },
                        danger: {
                            label: " Send!",
                            className: "btn-danger btn-squared",
                            callback: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: site_url+'/campaign-push/'+campaign_id+'/send',
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
            });





            
        })
    </script>
@endsection