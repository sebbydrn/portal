<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;
use Redirect;

class LoginController extends Controller
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
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

    public function username()
    {
        return 'username';
    }

    // Override for checking if user is active and is not deleted
    protected function credentials(Request $request) {
        return [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'isactive' => 1,
            'isdeleted' => 0
        ];
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        // Load user from database
        $user = User::where($this->username(), $request->{$this->username()})->first();

        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && \Hash::check($request->password, $user->password) && $user->isactive == 0) {
            $errors = [$this->username() => trans('auth.notactivated')];
        } else if ($user && \Hash::check($request->password, $user->password) && $user->isactive == 2) {
            $errors = [$this->username() => trans('auth.deactivated')];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    // Custom login design
    public function showLoginForm() {
        $contacts = $this->contacts();

        return view('auth.login')
        ->with(compact('contacts'));
    }

    // Login success
    protected function authenticated(Request $request, $user) {
        // Add log
        $log = array(
            'activity_id' => 9,
            'user_id' => Auth::user()->user_id,
            'browser' => $this->browser(),
            'device' => $this->device(),
            'ip_env_address' => $request->ip(),
            'ip_server_address' => request()->server('SERVER_ADDR'),
            'OS' => $this->operating_system()
        );

        $user_model = new User();

        $res = $user_model->add_log($log);

        // return redirect()->intended($this->redirectPath());
        $request->session()->flash('success', 'The collected personal information is utilized solely for documentation and processing purposes within RSIS and is not shared with any outside parties. These enable RSIS to properly address inquiries, requests, comments, or complaints, forward the same to appropriate internal units for action and response, and provide clients or complainants with appropriate updates and advisories in a legitimate format and in an orderly and timely manner.');
        $url = url('/');
        return Redirect::to($url);
    }

    // Logout 
    protected function logout(Request $request)
    {   
        // Add log
        $log = array(
            'activity_id' => 10,
            'user_id' => Auth::user()->user_id,
            'browser' => $this->browser(),
            'device' => $this->device(),
            'ip_env_address' => $request->ip(),
            'ip_server_address' => request()->server('SERVER_ADDR'),
            'OS' => $this->operating_system()
        );

        $user_model = new User();

        $res = $user_model->add_log($log);
        
        Auth::logout();

        return redirect('/'); 
    }
}
