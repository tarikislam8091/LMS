<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\Admin;
use File;

class ProductsController extends Controller
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
        if(isset($_GET['product_status'])){
            $all_content =  \App\Products::where(function($query){
                if(isset($_GET['product_status'])){
                    $query->where(function ($q){
                        $q->where('product_status', $_GET['product_status']);
                    });
                }
            })
                ->orderBy('id','DESC')
                ->paginate(20);

            $product_status = isset($_GET['product_status'])? $_GET['product_status']:0;

            $all_content->setPath(url('/product/list'));
            $pagination = $all_content->appends(['product_status' => $product_status])->render();
            $data['pagination'] = $pagination;
            $data['perPage'] = $all_content->perPage();
            $data['all_content'] = $all_content;

        } else{
            $all_content= \App\Products::orderBy('id','DESC')->paginate(20);
            $all_content->setPath(url('/product/list'));
            $pagination = $all_content->render();
            $data['perPage'] = $all_content->perPage();
            $data['pagination'] = $pagination;
            $data['all_content'] = $all_content;

        }

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.product.index',$data);
    }

    /********************************************
    ##  Create View
     *********************************************/
    public function Create()
    {
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.product.create',$data);
    }

    /********************************************
    ##  Store
     *********************************************/
    public function Store(Request $request)
    {
        $v = \Validator::make($request->all(), [
            'product_name' => 'required',
        ]);


        if($v->passes()){

            try{
            	$now = date('Y-m-d H:i:s');
                $product_name=$request->input('product_name');
                $slug=explode(' ', strtolower($request->input('product_name')));
                $product_name_slug=implode('-', $slug);

                $product_image="";
                $image_type="product";

                if($request->file('product_feature_image')!=null){
                    #ImageUpload
                    $image_wide = $request->file('product_feature_image');
                    $img_location_wide=$image_wide->getRealPath();
                    $img_ext_wide=$image_wide->getClientOriginalExtension();
                    $product_feature_image=\App\Admin::ProductImageUpload($img_location_wide,$img_ext_wide,$image_type,$product_name_slug);

                }

                $data['product_name']=$request->input('product_name');
                $data['brand']=$request->input('brand');
                $data['product_feature']=$request->input('product_feature');
                $data['product_description']=$request->input('product_description');
                $data['product_feature_image']=$product_feature_image;
                $data['product_sku']=time();
                $data['product_status']=0;
                $data['created_by']=\Auth::user()->id;
                $data['updated_by']=\Auth::user()->id;
                $data['created_at'] = $now;
                $data['updated_at'] = $now;

                $product_insert = \App\Products::firstOrCreate(
                    [
                        'product_name' => $data['product_name'],
                    ],
                    $data
                );

                if($product_insert->wasRecentlyCreated){

                    \App\System::EventLogWrite('insert,products_tbl',json_encode($data));
                    return redirect()->back()->with('message','product Created Successfully');

                }else return redirect()->back()->with('errormessage','product already created.');

            }catch (\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect()->back()->with('errormessage','Something wrong happend in product Upload');
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
        //check if this product has any content published or not
        $content_exists =\App\Products::where('id',$id)->first();
        if($content_exists)
        {
            $now = date('Y-m-d H:i:s');
            if($status=='1'){
                $data['product_status']=1;
            } else{
                $data['product_status']=0;
            }
            $update=\DB::table('products_tbl')->where('id',$id)->update($data);

            if($update) {
                echo 'Status updated successfully.';
                \App\System::EventLogWrite('update,product_status|Status updated successfully.',$id);
            } else {
                echo 'Status did not update.';
                \App\System::EventLogWrite('update,product_status|Status did not updated.',$id);
            }
        } else{
            echo 'There is no published content for this product. Please upload and publish any content to publish this content.';
        }

    }


    /********************************************
    ##  Edit View
     *********************************************/
    public function Edit($id)
    {
        $data['edit'] = \App\Products::where('id', $id)->first();
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.product.edit',$data);
    }

    /********************************************
    ##  Update
     *********************************************/
    public function Update(Request $request, $id)
    {
        $v = \Validator::make($request->all(), [
            'product_name' => 'required',
        ]);

        if($v->passes()){

            try{
            	$now = date('Y-m-d H:i:s');
                $current_data= \App\Products::where('id', $id)->first();

                if(!empty($current_data)){

	                $product_name=$request->input('product_name');
	                $slug=explode(' ', strtolower($request->input('product_name')));
	                $product_name_slug=implode('-', $slug);

                    $product_feature_image="";
                    $image_type="product";

                    if($request->file('product_feature_image')!=null){
                        #ProductImage
                        $image_wide = $request->file('product_feature_image');
                        $img_location_wide=$image_wide->getRealPath();
                        $img_ext_wide=$image_wide->getClientOriginalExtension();
                        $product_feature_image=\App\Admin::ProductImageUpload($img_location_wide,$img_ext_wide,$image_type,$product_name_slug);
                        if(File::exists($current_data->product_feature_image))
                        {
                            unlink($current_data->product_feature_image);
                        }
                    } else{
                        $product_feature_image = $current_data->product_feature_image;
                    }


	                $data['product_name']=$request->input('product_name');
	                $data['brand']=$request->input('brand');
	                $data['product_feature']=$request->input('product_feature');
	                $data['product_description']=$request->input('product_description');
	                $data['product_feature_image']=$product_feature_image;
	                $data['updated_by']=\Auth::user()->id;
                	$data['updated_at'] = $now;


	                $update=\App\Products::where('id', $id)->update($data);

	                \App\System::EventLogWrite('update,category_tbl',json_encode($data));

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
        $delete = \App\Products::where('id',$id)->delete();
        if($delete) {
            \App\System::EventLogWrite('delete,category_tbl|Content deleted successfully.',$id);
            echo 'Content deleted successfully.';
        } else {
            \App\System::EventLogWrite('delete,category_tbl|Content did not delete.',$id);
            echo 'Content did not delete successfully.';
        }
    }




    public function ProductPdf()
    {
        $data['page_title'] = $this->page_title;
        $data['today'] = date('Y-m-d');

        $data['all_content']= \App\Products::orderBy('id','DESC')->get();
        $pdf = \PDF::loadView('pages.product.product-pdf', $data);
        $pdfname = time().'product.pdf';
        return $pdf->stream($pdfname); 
    }

}
