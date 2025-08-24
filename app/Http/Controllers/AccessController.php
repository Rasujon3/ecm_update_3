<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Session;
session_start();

class AccessController extends Controller
{
    public function adminLogin(Request $request)
    {
        try {
            $data = $request->all();

            // Determine if login is via email or phone
            if ($request->has('email')) {
                $credentials = ['email' => $data['email'], 'password' => $data['password']];
            } else {
                $credentials = ['phone' => $data['phone'], 'password' => $data['password']];
            }

            // Attempt login
            if (Auth::attempt($credentials)) {
                $notification = [
                    'messege' => 'Successfully Logged In',
                    'alert-type' => 'success'
                ];
                return redirect('/dashboard')->with($notification);
            } else {
                $notification = [
                    'messege' => 'Email or Password Invalid',
                    'alert-type' => 'error'
                ];
                return redirect()->back()->with($notification);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'exception_code' => $e->getCode(),
                'exception_string' => $e->__toString()
            ]);
        }
    }


    public function Logout()
    {
    	try
    	{  
            $redirectRoute = user()->role_id == 1?'/':'/user/login';
            Session::put('redirectRoute',$redirectRoute);
    		Auth::logout();
            $getRoute = Session::get('redirectRoute');
    		$notification=array(
                'messege'=>'Successfully Logged Out',
                'alert-type'=>'success'
            );

            return redirect($redirectRoute)->with($notification);
    	}catch(Exception $e){
                  
                $message = $e->getMessage();
      
                $code = $e->getCode();       
      
                $string = $e->__toString();       
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
                exit;
        }
    }
}
