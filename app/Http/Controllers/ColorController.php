<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Color;
use App\Admin;
use File;

class ColorController extends Controller
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
        if(isset($_GET['color_status'])){

            $all_content =  \App\Color::where(function($query){
                if(isset($_GET['color_status'])){
                    $query->where(function ($q){
                        $q->where('color_status', $_GET['color_status']);
                    });
                }
            })
                ->orderBy('id','DESC')
                ->paginate(20);

            $color_status = isset($_GET['color_status'])? $_GET['color_status']:0;

            $all_content->setPath(url('/color/list'));
            $pagination = $all_content->appends(['color_status' => $color_status])->render();
            $data['pagination'] = $pagination;
            $data['perPage'] = $all_content->perPage();
            $data['all_content'] = $all_content;

        } else{

            $all_content= \App\Color::orderBy('id','DESC')->paginate(20);
            $all_content->setPath(url('/color/list'));
            $pagination = $all_content->render();
            $data['perPage'] = $all_content->perPage();
            $data['pagination'] = $pagination;
            $data['all_content'] = $all_content;

        }

        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.color.index',$data);
    }

    /********************************************
    ##  Create View
     *********************************************/
    public function Create()
    {
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.color.create',$data);
    }

    /********************************************
    ##  Store
     *********************************************/
    public function Store(Request $request)
    {
        $v = \Validator::make($request->all(), [
            'color_name' => 'required',
            // 'color_image' => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:width=150,height=150|max:1024',
        ]);


        if($v->passes()){

            try{
                $now = date('Y-m-d H:i:s');
                $color_name=$request->input('color_name');
                $slug=explode(' ', strtolower($request->input('color_name')));
                $color_name_slug=implode('-', $slug);

                $color_image="";
                $image_type="color";

                if($request->file('color_image')!=null){
                    #ImageUpload
                    $image_wide = $request->file('color_image');
                    $img_location_wide=$image_wide->getRealPath();
                    $img_ext_wide=$image_wide->getClientOriginalExtension();
                    $color_image=\App\Admin::ColorImageUpload($img_location_wide,$img_ext_wide,$image_type,$color_name);

                }

                $data['color_name']=$request->input('color_name');
                $data['color_image']=$color_image;
                $data['color_status']=0;
                $data['created_by']=\Auth::user()->id;
                $data['updated_by']=\Auth::user()->id;
                $data['created_at'] = $now;
                $data['updated_at'] = $now;
               
                $color_insert = \App\Color::firstOrCreate(
                    [
                        'color_name' => $data['color_name'],
                    ],
                    $data
                );

                if($color_insert->wasRecentlyCreated){

                    \App\System::EventLogWrite('insert,color_tbl',json_encode($data));
                    return redirect()->back()->with('message','Color Created Successfully');

                }else return redirect()->back()->with('errormessage','Color already created.');

            }catch (\Exception $e){
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);
                return redirect()->back()->with('errormessage','Something wrong happend in Color Upload');
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
        //check if this color has any content published or not
        $content_exists =\App\Color::where('id',$id)->first();
        if($content_exists)
        {
            $now = date('Y-m-d H:i:s');
            if($status=='1'){
                $data['color_status']='1';
                $data['updated_at'] = $now;
                $data['updated_by']=\Auth::user()->id;
            } else{
                $data['color_status']='0';
                $data['updated_at'] = $now;
                $data['updated_by']=\Auth::user()->id;
            }
            $update=\DB::table('color_tbl')->where('id',$id)->update($data);

            if($update) {
                echo 'Status updated successfully.';
                \App\System::EventLogWrite('update,color_status|Status updated successfully.',$id);
            } else {
                echo 'Status did not update.';
                \App\System::EventLogWrite('update,color_status|Status did not updated.',$id);
            }
        } else{
            echo 'There is no published content for this color. Please upload and publish any content to publish this content.';
        }

    }


    /********************************************
    ##  Edit View
     *********************************************/
    public function Edit($id)
    {
        $data['edit'] = \App\Color::where('id', $id)->first();
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = $this->page_desc;
        return view('pages.color.edit',$data);
    }

    /********************************************
    ##  Update
     *********************************************/
    public function Update(Request $request, $id)
    {
        $v = \Validator::make($request->all(), [
            'color_name' => 'required',
            // 'color_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|dimensions:width=150,height=150|max:1024',

        ]);

        if($v->passes()){

            try{
            	$now = date('Y-m-d H:i:s');
                $current_data= \App\Color::where('id', $id)->first();

                if(!empty($current_data)){

	                $color_name=$request->input('color_name');
	                $slug=explode(' ', strtolower($request->input('color_name')));
	                $color_name_slug=implode('-', $slug);

                    $color_image="";
                    $image_type="color";

                    if($request->file('color_image')!=null){
                        #ColorImage
                        $image_wide = $request->file('color_image');
                        $img_location_wide=$image_wide->getRealPath();
                        $img_ext_wide=$image_wide->getClientOriginalExtension();
                        $color_image=\App\Admin::ColorImageUpload($img_location_wide,$img_ext_wide,$image_type,$color_name);
                        if(File::exists($current_data->color_image))
                        {
                            unlink($current_data->color_image);
                        }
                    } else{
                        $color_image = $current_data->color_image;
                    }

	                
	                $data['color_image']=$color_image;
	                $data['color_name']=$color_name;
                	$data['updated_at'] = $now;
	                $data['updated_by']=\Auth::user()->id;


	                $update=\App\Color::where('id', $id)->update($data);

	                \App\System::EventLogWrite('update,color_tbl',json_encode($data));

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
        $delete = \App\Color::where('id',$id)->delete();
        if($delete) {
            \App\System::EventLogWrite('delete,color_tbl|Content deleted successfully.',$id);
            echo 'Content deleted successfully.';
        } else {
            \App\System::EventLogWrite('delete,color_tbl|Content did not delete.',$id);
            echo 'Content did not delete successfully.';
        }
    }
}
