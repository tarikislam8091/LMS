<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Session;
use PDF;
use PHPMailer\PHPMailer;
use Exception;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class AdminController extends Controller
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
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        return view('admin.index', $data);
    }


    /**
     * Display profile information
     * pass page title
     * Get User data by auth email
     * Get User meta data by joining user
     * Get Products by auth user.
     *
     * @return HTML view Response.
     */
    public function Profile()
    {

        $data['page_title'] = $this->page_title;

        if (isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])) {
            $tab = $_REQUEST['tab'];
        } else {
            $tab = 'panel_overview';
        }
        $data['tab'] = $tab;
        $last_login = (\Session::has('last_login')) ? \Session::get('last_login') : date('Y-m-d H:i:s');
        $data['last_login'] = \App\Common::TiemElapasedString($last_login);
        $user_info = \DB::table('users')
            ->where('email', \Auth::user()->email)
            ->first();
        $data['user_info'] = $user_info;
        return view('pages.admin.profile',$data);
    }

    /**
     * Update User Profile
     * if user meta data exist then update else insert user meta data.
     *
     * @param  Request  $request
     * @return Response
     */
    public function ProfileUpdate(Request $request)
    {
        $user_id = \Auth::user()->id;
        $user = \DB::table('users')->where('id', $user_id)->first();
        $v = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'user_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11',
        ]);
        if ($v->fails()) {
            return redirect('admin/profile')->withErrors($v)->withInput();
        }
        $now = date('Y-m-d H:i:s');
        if (!empty($request->file('image_url'))) {
            $image = $request->file('image_url');
            $img_location = $image->getRealPath();
            $img_ext = $image->getClientOriginalExtension();
            $user_profile_image = \App\Admin::UserImageUpload($img_location, $request->input('email'), $img_ext);
            $user_profile_image = $user_profile_image;
        } else {
            $user_profile_image = $user->user_profile_image;
        }
        $user_info_update_data = array(
            'name' => ucwords($request->input('name')),
            'email' => $request->input('email'),
            'user_mobile' => $request->input('user_mobile'),
            'user_profile_image' => $user_profile_image,
            'updated_at' => $now,
        );
        try {
            \DB::table('users')->where('id', $user_id)->update($user_info_update_data);
            return redirect('admin/profile')->with('message',"Profile updated successfully");
        } catch (\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            return redirect('admin/profile')->with('errormessage',"Something is wrong!");
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function ProfileImageUpdate(Request $request)
    {
        if (!empty($request->file('image_url'))) {
            $email=\Auth::user()->email;
            $image = $request->file('image_url');
            $img_location=$image->getRealPath();
            $img_ext=$image->getClientOriginalExtension();
            $user_profile_image=\App\Admin::UserImageUpload($img_location, $email, $img_ext);
            $user_profile_image=$user_profile_image;
            $user_new_img = array(
                'user_profile_image' => $user_profile_image,
            );
            try {
                \DB::table('users')
                    ->where('id', \Auth::user()->id)
                    ->update($user_new_img);
                return redirect('admin/profile')->with('message',"Profile updated successfully");
            } catch (\Exception $e) {
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                return redirect('admin/profile')->with('errormessage',$message);

            }
        }
    }
    
    /**
     * Update password for specific user
     * checked validation, if failed redirect with error message.
     *
     * @param Request $request
     * @return Response.
     */
    public function UserChangePassword(Request $request)
    {
        $now = date('Y-m-d H:i:s');

        $rules = array(
            'new_password' => 'required',
            'confirm_password' => 'required',
            'current_password' => 'required',
        );

        $v = \Validator::make(\Request::all(), $rules);

        if ($v->fails()) {
            return redirect('admin//profile?tab=change_password')
                ->withErrors($v)
                ->withInput();
        }

        $new_password = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');

        if ($new_password == $confirm_password) {
            if (\Hash::check($request->input('current_password'),
                \Auth::user()->password)) {
                $update_password=array(
                    'password' => bcrypt($request->input('new_password')),
                    'plain_password' => $request->input('new_password'),
                    'updated_at' => $now
                );
                try {
                    \DB::table('users')
                        ->where('id', \Auth::user()->id)
                        ->update($update_password);
                    return redirect('admin/profile')->with('message',"Password updated successfully !");
                } catch(\Exception $e) {
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    return redirect('admin/profile')->with('errormessage',"Password update failed !");
                }
            } else {
                return redirect('admin/profile?tab=change_password')->with('errormessage',"Current Password Doesn't Match !");
            }
        } else {
            return redirect('admin/profile?tab=change_password')->with('errormessage',"Password Combination Doesn't Match !");
        }
    }



    /**
     *
     * @return HTML view Response.
     */
    public function PasswordChangePage()
    {

        $data['page_title'] = $this->page_title;
        $user_info = \DB::table('users')
            ->where('email', \Auth::user()->email)
            ->first();
        $data['user_info'] = $user_info;
        return view('pages.admin.password-change',$data);
    }

    /**
     * Update password for specific user
     * checked validation, if failed redirect with error message.
     *
     * @param Request $request
     * @return Response.
     */
    public function PasswordChangeByAdmin(Request $request)
    {
        try {

            $now = date('Y-m-d H:i:s');

            $rules = array(
                'new_password' => 'required',
                'confirm_password' => 'required',
                'email' => 'required',
            );

            $v = \Validator::make(\Request::all(), $rules);

            if ($v->fails()) {
                return redirect()->back()->withErrors($v)->withInput();
            }

            $new_password = $request->input('new_password');
            $confirm_password = $request->input('confirm_password');
            $email = $request->input('email');

            $user_info =  \DB::table('users')
                        ->where('email', $email)
                        ->first();

            if (!empty($user_info)) {
                
                if ($new_password == $confirm_password) {

                        $update_password=array(
                            'password' => bcrypt($request->input('new_password')),
                            'plain_password' => $request->input('new_password'),
                            'updated_at' => $now
                        );
                        \DB::table('users')
                            ->where('email', $email)
                            ->update($update_password);
                        return redirect()->back()->with('message',"Password updated successfully !");

                } else {
                    return redirect()->back()->with('errormessage',"Password Combination Doesn't Match !");
                }

            } else {
                return redirect()->back()->with('errormessage',"Invalid User");
            }

        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            return redirect()->back()->with('errormessage',"Password update failed !");
        }
    }


    /**
     * Show the form for creating a new user
     * pass page title.
     *
     *@return HTML view Response.
     */
    public function UserManagement()
    {
        if ((\Auth::user()->user_role=="admin")) {
            $data['page_title'] = $this->page_title;
            if (isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])) {
                $tab = $_REQUEST['tab'];
            } else {
                $tab = 'create_user';
            }
            $data['tab'] = $tab;
            $data['user_info'] = \DB::table('users')->get();
            $data['block_users'] = \DB::table('users')->where('status','deactivate')->get();
            $data['admins'] = \DB::table('users')
                ->whereNotIn('user_role',array('app','participate',''))
                ->get();
            return view('pages.admin.user-management',$data);
        } else {
            return redirect('admin/dashboard')->with('errormessage',"Request Wrong Url");
        }
    }
    /**
     * Creating new User
     * insert user meta data if data input else insert null to user meta table.
     *
     * @param  Request  $request
     * @return Response
     */
    public function CreateUser(Request $request)
    {
        if ((\Auth::user()->user_role=="admin")) {
            $v = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'user_mobile' =>'Required|regex:/^[^0-9]*(88)?0/|max:11',
                'user_type' => 'required',
                'user_role' => 'required',
                'password' => 'required',
                'confirm_password' => 'required',
            ]);
            if ($v->fails()) {
                return redirect()->back()->withErrors($v)->withInput();
            }
            if ($request->input('password') == $request->input('confirm_password')) {
                $now=date('Y-m-d H:i:s');
                $slug=explode(' ', strtolower($request->input('name')));
                $name_slug=implode('.', $slug);
                if (!empty($request->file('image_url'))) {
                    $image = $request->file('image_url');
                    $img_location = $image->getRealPath();
                    $img_ext = $image->getClientOriginalExtension();
                    $user_profile_image = \App\Admin::UserImageUpload($img_location, $request->input('email'), $img_ext);
                    $user_profile_image = $user_profile_image;
                } else {
                    $user_profile_image='';
                }
                $user_insert_data=array(
                    'name' => ucwords($request->input('name')),
                    'name_slug' => $name_slug,
                    'user_type' => $request->input('user_type'),
                    'user_role' => $request->input('user_role'),
                    'email' => $request->input('email'),
                    'user_mobile' => $request->input('user_mobile'),
                    'password' => bcrypt($request->input('password')),
                    'plain_password' => $request->input('password'),
                    'user_profile_image' => $user_profile_image,
                    'login_status' => 0,
                    'status' => "deactivate",
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                );
                try {
                    \DB::table('users')->insert($user_insert_data);
                    return redirect('admin/user/management')->with('message',"User Account Created Successfully !");
                } catch(\Exception $e) {
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    return redirect('admin/user/management')->with('errormessage',"User Already Exist !");
                }
            } else {
                return redirect('admin/user/management')->with('errormessage',"Password Does Not Matched");
            }
        } else {
            return redirect('admin/dashboard')->with('errormessage',"Request Wrong Url !");
        }
    }

    /**
     * Change status for individual user.
     *
     * @param int $user_id
     * @param int $status.
     *
     * @return Response.
     */
    public function ChangeUserStatus($user_id, $status)
    {
        $now = date('Y-m-d H:i:s');
        $update = \DB::table('users')
            ->where('id',$user_id)
            ->update(array(
                'status' => $status,
                'updated_at' => $now
            ));
        if($update) {
            echo 'User update successfully.';
        } else {
            echo 'User did not update.';
        }
    }

    /**
     * Update password for specific user
     *
     * @param Request $request
     * @return Response.
     */
    public function userTypeUpdate(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        try {
            $email= isset($_GET['email']) ? $_GET['email'] : '';
            $user_role= isset($_GET['user_role']) ? $_GET['user_role'] : '';
            $user_mobile= isset($_GET['user_mobile']) ? $_GET['user_mobile'] : '';

            if (!empty($email)) {
                $data['email'] = $email;
            }

            if (!empty($user_role)) {
                $data['user_role'] = $user_role;
            }

            if (!empty($user_mobile)) {
                $data['user_mobile'] = $user_mobile;
            }

            $user_eamil_info = \DB::table('users')
                ->where('email',$email)
                ->first();

            $user_mobile_info = \DB::table('users')
                ->where('user_mobile',$user_mobile)
                ->first();

            if (!empty($user_eamil_info)) {

                \DB::table('users')
                    ->where('email', $email)
                    ->update($data);
                return 'Update successfully';

            }elseif (!empty($user_mobile_info)) {

                \DB::table('users')
                    ->where('user_mobile', $user_mobile)
                    ->update($data);
                return 'Update successfully';

            } else {
                return 'Invalid mobile or email';
            }
        } catch(\Exception $e) {
            return 'Somthing wrong';
        }
    }

    /**
     * send Mail.
     *
     * @return Response.
     */
    public function sendMail()
    {
        $now = date('Y-m-d H:i:s');


        $data['message_info'] = "Testing mail";

        $user_email= isset($_GET['user_mail']) ? $_GET['user_mail'] : '';
        $user_name = "Tarik";


        \Mail::send('mail.basic', $data, function($message) use ($user_email,$user_name) {
            
            $message->to($user_email,$user_name)->subject('Mail Send');

        });

        return \Redirect::to('/')->with('message',"Please Check Email !");

    }


    /**
     * Show the form for creating a new user
     * pass page title.
     *
     *@return HTML view Response.
     */
    public function mailPage()
    {

        $data['page_title'] = $this->page_title;
        return view('pages.mail.index',$data);
    }





}
