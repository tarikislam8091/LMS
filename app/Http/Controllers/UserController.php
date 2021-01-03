<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller; 
use App\User;
use App\Products;
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    
	public $successStatus = 200;

    public function login(){ 
        try{

            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                $user = Auth::user(); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                return response()->json(['success' => $success], $this-> successStatus); 
            } 
            else{ 
                return response()->json(['error'=>'Unauthorised'], 401); 
            } 

        }catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage',$message);
        }
    }

    public function register(Request $request) 
    { 
        try{
            $validator = Validator::make($request->all(), [ 
                'name' => 'required', 
                'email' => 'required|email', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
            ]);
    		if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
    		$input = $request->all(); 
            $data['name'] = $input['name'];
            $slug=explode(' ', strtolower($input['name']));
            $data['name_slug']=implode('-', $slug);
            $data['email'] = $input['email'];
            $data['password'] = bcrypt($input['password']); 
            $data['plain_password'] = $input['password']; 
            $data['user_role'] = 'admin';
            $data['user_type'] = 'admin';
            $data['user_mobile'] = 1;
            $data['login_status'] = 0;
            $data['status'] = 'active';

            $user = User::create($data); 

            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
            $success['success'] =  'success';
    		return response()->json(['success'=>$success], $this-> successStatus); 

        }catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage',$message);
        }
    }

    public function UserDetails() 
    { 
        try{
            if(Auth::user()){
                $user = Auth::user(); 
                return response()->json(['success' => $user], $this-> successStatus); 
            }else{
                return 'Invalid User';
            }
        }catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Invalid Token');
        }
    } 


    public function AllProducts() 
    { 
        try{
            // if(Auth::user()){

                $data['products'] = Products::where('product_status','1')->get(); 
                return response()->json(['success'=>$data], $this-> successStatus); 
                
            /*}else{
                return 'Invalid User';
            }*/
        }catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Invalid Token');
        }
    } 



}
