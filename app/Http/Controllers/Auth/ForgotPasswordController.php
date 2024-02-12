<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use DB;
use App\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        $contacts = $this->contacts();
        return view('auth.passwords.email')->with(compact('contacts'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        
        if(count($user)<1){
            return redirect()->back()->withErrors(['email' => trans("We can't find a user with that e-mail address.")]);
        }
            
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => str_random(60),
            'created_at' => Carbon::now()
        ]);
        //Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        $content = array();
        $content['firstname'] = $user->firstname;
        $content['token'] = $tokenData->token;
        $content['url'] = url('/').'/password/reset/' . $tokenData->token .'?email=' .urlencode($request->email);
        //$res4 = $this->send_email($request->email, $content);

        if ($this->send_email($request->email, $content)) {
            return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }

        //Retrieve the user from the database
        $userData = DB::table('users')->where('email', $request->email)->select('firstname', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        //$link = url('/'). '/password/reset/' . $tokenData->token . '?email=' . urlencode($user->email);
        
        try {
            
            
            
            /*if ($res4 == "success") {
                DB::commit(); // Only commit the transaction if success in sending email
                // $request->session()->flash('success', `Thank you for registering! A confirmation email has been sent to your inbox. If the email doesn't arrive shortly, please check your spam folder.`);
                $contacts = $this->contacts();

                return view('auth.passwords.email')->with(compact('contacts'));
            }*/
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function send_email($email, $content) {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();  // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'rsis.bpi.philrice@gmail.com'; // SMTP username
            // $mail->Password = 'rsisDA_2020'; // SMTP password
            $mail->Password = 'nbyklvyfxpemkydo';
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            //Recipients
            $mail->setFrom('rsis.bpi.philrice@gmail.com', 'Rice Seed Information System');
            $mail->addAddress($email); // Add a recipient
            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'RSIS Registration Acknowledgement';
            $mail->Body    = $this->email_content($content);
            $mail->AltBody = 'Please enable HTML content to view this email.';

            $mail->send();
            return "success";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function email_content($content) {
        return view('email.forgot_password')->with($content);
    }
}
