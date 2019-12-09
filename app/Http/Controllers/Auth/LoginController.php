<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class LoginController extends Controller
{
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
      }

     public function login(){

        $credentials = $this->validate(request(),[
             'email' => 'email|required|string',
            'password'=>'required|string'
         ]);

         if(Auth::attempt($credentials))
         {
           

            return redirect()->route('dashboard');
         }

         return back()->withErrors(['email'=>'These credentials do not match our records.']);
     }
     /**
  *Redirigir al usuario a la página de autenticación de Google.
  *
  *@return \ Illuminate \ Http \ Response
  */
     public function redirectToProvider ()
     {
         return Socialite::driver('google')->redirect();

     }


     /**
     *Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/');
        }
        // only allow people with @company.com to login
       /* if(explode("@", $user->email)[1] !== 'company.com'){
            return redirect()->to('/');
        }*/
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
            $profile_image=$user->avatar;
            $existingUser->profile_image=$profile_image;
            $existingUser->ip_client=\Request::ip();
            $existingUser->save();
            return redirect()->route('dashboard');

        } else {
            // create a new user
            /*
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            $newUser->avatar          = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->save();
            auth()->login($newUser, true);*/
            return redirect()->to('/')->withErrors(['email'=>'These credentials do not match our records.']);

        }
        return redirect()->to('/home');
    }


}
