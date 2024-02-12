<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use DB, Auth;
use App\User;
use App\Passwords;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null) {
        $contacts = $this->contacts();
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email])->with(compact('contacts'));
    }

    public function reset(Request $request){
        $contacts = $this->contacts();
         $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $tokenData = DB::table('password_resets')->where('token',$request->token)->first();

        if(!$tokenData) return view('auth.passwords.email');

        $user = User::where('email',$tokenData->email)->first();
        if(!$user) return redirect()->back()->withErrors(['email' => 'Email not found']);

        
        DB::beginTransaction();
        try {
            
            $password = Passwords::where('user_id',$user->user_id)->first();
            //dd($password);
            $password->password = \Hash::make($request->password);
            $password->update();

            //Auth::login($user);

            DB::table('password_resets')->where('email',$user->email)->delete();
            DB::commit();

            $res =  'success';
        } catch (\Exception $e) {
            DB::rollback();
            $res = 'false';
        }

        if ($res == 'success') {
            return redirect('login');
        } else {
            return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
        }  
    }
}
