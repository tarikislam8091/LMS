<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Admin;
use File;

class StockController extends Controller
{

    /**
     * Class constructor.
     * get current route name for page title
     *
     * @param Request $request;
     */
    public function __construct(Request $request)
    {
        $this->page_title = $request->route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:$this->page_title;
        // \App\System::AccessLogWrite();
    }

    /********************************************
    ## Show the list of all Content
     *********************************************/
    public function getAllContent()
    {
        if(isset($_GET['product_id'])){
            $all_content =  \App\Stock::where(function($query){
                if(isset($_GET['product_id'])){
                    $query->where(function ($q){
                        $q->where('product_id', $_GET['product_id']);
                    });
                }
            })
            	->join('color_tbl', 'color_tbl.id', '=', 'stock_tbl.color_id')
            	->join('products_tbl', 'products_tbl.id', '=', 'stock_tbl.product_id')
            	->select('stock_tbl.*','products_tbl.product_name','color_tbl.color_name')
                ->orderBy('stock_tbl.id','DESC')
                ->paginate(20);

            $product_id = isset($_GET['product_id'])? $_GET['product_id']:0;

            $all_content->setPath(url('/stock/list'));
            $pagination = $all_content->appends(['product_id' => $product_id])->render();
            $data['pagination'] = $pagination;
            $data['perPage'] = $all_content->perPage();
            $data['all_content'] = $all_content;

        } else{
            $all_content= \App\Stock::orderBy('stock_tbl.id','DESC')
            ->join('products_tbl', 'products_tbl.id', '=', 'stock_tbl.product_id')
            ->join('color_tbl', 'color_tbl.id', '=', 'stock_tbl.color_id')
            ->select('stock_tbl.*','products_tbl.product_name','color_tbl.color_name')
            ->paginate(20);
            $all_content->setPath(url('/stock/list'));
            $pagination = $all_content->render();
            $data['perPage'] = $all_content->perPage();
            $data['pagination'] = $pagination;
            $data['all_content'] = $all_content;

        }

        $data['all_product'] = \DB::table('products_tbl')->where('product_status', '1')->orderby('id','desc')->get();
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.stock.list',$data);
    }

    /********************************************
    ##  Create View
     *********************************************/
    public function Create()
    {
    	$data['all_color'] = \DB::table('color_tbl')->where('color_status', '1')->orderby('id','desc')->get();
        $data['all_product'] = \DB::table('products_tbl')->where('product_status', '1')->orderby('id','desc')->get();
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.stock.create',$data);
    }

    /********************************************
    ##  Store
     *********************************************/
    public function Store(Request $request)
    {
        $v = \Validator::make($request->all(), [
            'product_id' => 'required',
            'color_id' => 'required',
            'num_of_product' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'demo_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            // 'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|dimensions:width=480,height=270|max:1024',

        ]);

        if($v->passes()){

            try{

                $product_id=$request->input('product_id');
                $color_id=$request->input('color_id');

        		$content_exists =\App\Stock::where('product_id',$product_id)->where('color_id',$color_id)->first();

	        	if(empty($content_exists)){

	            	$now = date('Y-m-d H:i:s');
	                $qr_code=time();
	                $product_image="";
	                $image_type="stock";

	                if($request->file('product_image')!=null){
	                    #ImageUpload
	                    $image_wide = $request->file('product_image');
	                    $img_location_wide=$image_wide->getRealPath();
	                    $img_ext_wide=$image_wide->getClientOriginalExtension();
	                    $product_image=\App\Admin::StockImageUpload($img_location_wide,$img_ext_wide,$image_type,$qr_code);
	                }

	                $data['product_id']=$request->input('product_id');
	                $data['color_id']=$request->input('color_id');
	                $data['product_image']=$product_image;
	                $data['qr_code']=$qr_code;
	                $data['num_of_product']=$request->input('num_of_product');
	                $data['buy_price']=$request->input('buy_price');
	                $data['demo_price']=$request->input('demo_price');
	                $data['sell_price']=$request->input('sell_price');
	                $data['product_special']=$request->input('product_special');
	                $data['stock_description']=$request->input('stock_description');
	                $data['stock_status']=0;
	                $data['created_by']=\Auth::user()->id;
	                $data['updated_by']=\Auth::user()->id;
	                $data['created_at'] = $now;
	                $data['updated_at'] = $now;

	                // $insert=\DB::table('stock_tbl')->insert($data);

	                $stock_insert = \App\Stock::firstOrCreate(
	                    [
	                        'qr_code' => $qr_code,
	                    ],
	                    $data
	                );

	                if($stock_insert->wasRecentlyCreated){

	                    \App\System::EventLogWrite('insert,stock_tbl',json_encode($data));
	                    return redirect()->back()->with('message','stock Created Successfully');

	                }else return redirect()->back()->with('errormessage','stock already created.');

	            }else{
	                return redirect()->back()->with('errormessage','Alreay exist');
	            }

            }catch (\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect()->back()->with('errormessage','Something wrong happend in stock Upload');
            }

        } else{
            return redirect()->back()->withErrors($v)->withInput();
        }
    }

    /********************************************
    ## Change publish status for individual.
     *********************************************/
    public function ChangePublishStatus($id, $status)
    {
        //check if this stock has any content published or not
        $content_exists =\App\Stock::where('id',$id)->first();
        if($content_exists)
        {
            $now = date('Y-m-d H:i:s');
            if($status=='1'){
                $data['stock_status']=1;
            } else{
                $data['stock_status']=0;
            }
            $update=\DB::table('stock_tbl')->where('id',$id)->update($data);

            if($update) {
                echo 'Status updated successfully.';
                \App\System::EventLogWrite('update,stock_status|Status updated successfully.',$id);
            } else {
                echo 'Status did not update.';
                \App\System::EventLogWrite('update,stock_status|Status did not updated.',$id);
            }
        } else{
            echo 'There is no published content for this category. Please upload and publish any content to publish this content.';
        }

    }


    /********************************************
    ##  Edit View
     *********************************************/
    public function Edit($id)
    {
        $data['edit'] = \App\Stock::where('id', $id)->first();
        $data['all_color'] = \DB::table('color_tbl')->where('color_status', '1')->orderby('id','desc')->get();
        $data['all_product'] = \DB::table('products_tbl')->where('product_status', '1')->orderby('id','desc')->get();
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.stock.edit',$data);
    }

    /********************************************
    ##  Update
     *********************************************/
    public function Update(Request $request, $id)
    {
        $v = \Validator::make($request->all(), [
            'product_id' => 'required',
            'color_id' => 'required',
            'num_of_product' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'demo_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
        ]);

        if($v->passes()){

            try{

	           	$now = date('Y-m-d H:i:s');
                $current_data= \App\Stock::where('id', $id)->first();

                if(!empty($current_data)){

	                $qr_code=$current_data->qr_code;
	                $product_image="";
	                $image_type="stock";

                    if($request->file('product_image')!=null){
                        #Image
                        $image_wide = $request->file('product_image');
                        $img_location_wide=$image_wide->getRealPath();
                        $img_ext_wide=$image_wide->getClientOriginalExtension();
                        $product_image=\App\Admin::StockImageUpload($img_location_wide,$img_ext_wide,$image_type,$qr_code);
                        if(File::exists($current_data->product_image))
                        {
                            unlink($current_data->product_image);
                        }
                    } else{
                        $product_image = $current_data->product_image;
                    }


	                $data['product_id']=$request->input('product_id');
	                $data['color_id']=$request->input('color_id');
	                $data['product_image']=$product_image;
	                $data['qr_code']=$qr_code;
	                $data['num_of_product']=$request->input('num_of_product');
	                $data['buy_price']=$request->input('buy_price');
	                $data['demo_price']=$request->input('demo_price');
	                $data['sell_price']=$request->input('sell_price');
	                $data['product_special']=$request->input('product_special');
	                $data['stock_description']=$request->input('stock_description');
	                $data['updated_by']=\Auth::user()->id;
	                $data['updated_at'] = $now;

	                $update=\App\Stock::where('id', $id)->update($data);

	                \App\System::EventLogWrite('update,stock_tbl',json_encode($data));

	                return redirect()->back()->with('message','Content Updated Successfully !!');
	                
	            }else return redirect()->back()->with('message','Content not found !!');

            }catch (\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect()->back()->with('errormessage','Something wrong happend in Content Update !!');
            }
        }else return redirect()->back()->withErrors($v)->withInput();
    }

    /********************************************
    ## Delete
     *********************************************/
    public function Delete($id)
    {
        $delete = \App\Stock::where('id',$id)->delete();
        if($delete) {
            \App\System::EventLogWrite('delete,stock_tbl|Content deleted successfully.',$id);
            echo 'Content deleted successfully.';
        } else {
            \App\System::EventLogWrite('delete,stock_tbl|Content did not delete.',$id);
            echo 'Content did not delete successfully.';
        }
    }
}
