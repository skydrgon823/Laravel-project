<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Mail\PhoneConfirm;
use Illuminate\Support\Facades\Mail;
use Authy\AuthyApi;

class AuthController extends Controller
{

    public function authenticate(Request $request){
      $this->validate($request, [
        'password' => 'required'
      ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if($user != null){
                if (Auth::attempt($credentials)) {
                    // if success login
                        return redirect('employee');
                }
                else{
                    return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
                                'password' => 'Wrong password',
                    ]);
                }
        }
        else{
            return redirect('login')->with(['message' => 'this user does not exist']);
        }

        // if failed login
        return redirect('login');
    }

    protected function create(Request $request)
    {

       $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],          
        ]);

              try {

                    $user = User::create([
                        'name' => $data['first_name'].' '.$data['last_name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                    ]);
                    DB::table('role_user')->insert(['role_id' => 2, 'user_id' => $user->id]); 
                    session()->flash('message', 'Your account is created');

                    return redirect('dashboard');

              } catch (\Exception $e){
                        return back()->with([ 'error' => "Error Happened"]);
              }       

    }   

}
