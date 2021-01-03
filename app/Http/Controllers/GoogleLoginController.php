<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;
use App\User;

class GoogleLoginController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                // return return redirect('/home');
                return redirect('/');

            }else{

                /*$newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id
                ]);*/

	            $name=$user->name;
	            $password='123456';

	            $data['name'] = $user->name;
	            $slug=explode(' ', strtolower($name));
	            $data['name_slug']=implode('-', $slug);
	            $data['email'] = $user->email;
	            $data['google_id'] = $user->id;
	            $data['password'] = bcrypt($password); 
	            $data['plain_password'] = $password; 
	            $data['user_role'] = 'admin';
	            $data['user_type'] = 'admin';
	            $data['user_mobile'] = 1;
	            $data['login_status'] = 0;
	            $data['status'] = 'active';

                $newUser = \App\Participate::updateOrCreate(
                    [
                        'email' => $data['email'],
                    ],
                    $data
                );

                Auth::login($newUser);

                return redirect()->back();
            }

        } catch (Exception $e) {
            return redirect('auth/google');
        }
    }





}
