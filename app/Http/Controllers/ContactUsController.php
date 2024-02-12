<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inquiry;
use App\Receiver;
use App\AutoResponse;
use DB;
use Auth;
use PHPMailer\PHPMailer;

class ContactUsController extends Controller
{
    public function index() {
    	$contacts = $this->contacts();

    	return view('contact_us.index')
    	->with(compact('contacts'));
    }

    public function store(Request $request) {
    	$this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $inquiry_model = new Inquiry();

        $data = array(
            'sender' => $request->name,
            'email' => $request->email,
            'inquiry' => $request->message,
            'status' => 0
        );

        DB::beginTransaction();
        try {
            // Add inquiry
            $res = Inquiry::create($data);
            $inquiry_id = $res->inquiry_id;

            // Add log
            $log = array(
                'inquiry_id' => $inquiry_id,
                'user_id' => (Auth::guest()) ? 0 : Auth::user()->user_id,
                'browser' => $this->browser(),
                'activity' => "Sent new inquiry",
                'device' => $this->device(),
                'ip_env_address' => $request->ip(),
                'ip_server_address' => request()->server('SERVER_ADDR'),
                'OS' => $this->operating_system()
            );

            $res2 = $inquiry_model->add_log($log);

            // Send email to receivers
            $receivers = $this->send_email($data);

            if ($receivers != "error") {
            	foreach ($receivers as $receiver) {
            		// Add log
            		$log = array(
            			'receiver_id' => $receiver,
		                'inquiry_id' => $inquiry_id,
		                'browser' => $this->browser(),
		                'activity' => "Sent user inquiry",
		                'device' => $this->device(),
		                'ip_env_address' => $request->ip(),
		                'ip_server_address' => request()->server('SERVER_ADDR'),
		                'OS' => $this->operating_system()
		            );

		            $res3 = $inquiry_model->add_inquiry_receiver_log($log);
            	}
            }

            // Send auto response email
            $auto_response_model = new AutoResponse();

            $auto_response = $auto_response_model->auto_response();

            if ($auto_response) {
                $auto_response_data = array(
                    'recipient_email' => $data['email'],
                    'recipient_name' => $data['sender'],
                    'sender' => $auto_response->sender,
                    'title' => $auto_response->title,
                    'body' => $auto_response->body
                );

                $auto_response_sent = $this->send_auto_response($auto_response_data);

                if ($auto_response_sent == "success") {
                    // Add log
                    $log = array(
                        'auto_response_id' => $auto_response->auto_response_id,
                        'inquiry_id' => $inquiry_id,
                        'browser' => $this->browser(),
                        'activity' => "Sent auto response",
                        'device' => $this->device(),
                        'ip_env_address' => $request->ip(),
                        'ip_server_address' => request()->server('SERVER_ADDR'),
                        'OS' => $this->operating_system()
                    );

                    $res4 = $inquiry_model->add_inquiry_autores_log($log);
                }
            }
            
            DB::commit();
            $res3 = "success";
        } catch (Exception $e) {
            DB::rollback();
            $res3 = $e->getMessage();
        }

        if ($res3 == "success") {
            $request->session()->flash('success', 'Your message has been sent. Thank you for filling out our form!');
        } else {
            $request->session()->flash('error', $res3);
        }

        return redirect()->route('contact_us.index');
    }

    public function send_email($data) {
        $mail = new PHPMailer\PHPMailer(true);

        $receiver_model = new Receiver();

        // Get main recipients
        $main_recipients = $receiver_model->main_recipients();

        // Get CCs
        $carbon_copies = $receiver_model->carbon_copies();

        // Get BCCs
        $blind_carbon_copies = $receiver_model->blind_carbon_copies();

        $receivers = array();

        try {
            // Server settings
            // $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();  // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'rsis.bpi.philrice@gmail.com'; // SMTP username
            // $mail->Password = 'rsisDA_2020'; // SMTP password
            $mail->Password = 'nbyklvyfxpemkydo';
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom($data['email'], $data['sender']);

            foreach ($main_recipients as $main_recipient) {
            	$mail->addAddress($main_recipient['email']); // Add a recipient
            	array_push($receivers, $main_recipient['receiver_id']);
            }

            foreach ($carbon_copies as $carbon_copy) {
            	$mail->addCC($carbon_copy['email']); // Add CC
            	array_push($receivers, $carbon_copy['receiver_id']);
            }

            foreach ($blind_carbon_copies as $blind_carbon_copy) {
            	$mail->addBCC($blind_carbon_copy['email']); // Add BCC
            	array_push($receivers, $blind_carbon_copy['receiver_id']);
            }
            
            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'RSIS User Inquiry';
            $mail->Body    = $this->email_content($data);
            $mail->AltBody = 'Please enable HTML content to view this email.';

            $mail->send();

            return $receivers;
        } catch (Exception $e) {
            return 'error';
        }
    }

    public function email_content($data) {
        return view('email.inquiry')->with($data);
    }

    public function send_auto_response($auto_response_data) {
        $mail = new PHPMailer\PHPMailer(true);

        try {
            // Server settings
            // $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();  // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'rsis.bpi.philrice@gmail.com'; // SMTP username
            // $mail->Password = 'rsisDA_2020'; // SMTP password
            $mail->Password = 'nbyklvyfxpemkydo';
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('rsis.bpi.philrice@gmail.com', $auto_response_data['sender']);
            $mail->addAddress($auto_response_data['recipient_email']); // Add a recipient
            
            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = $auto_response_data['title'];
            $mail->Body    = $this->auto_response_content($auto_response_data);
            $mail->AltBody = 'Please enable HTML content to view this email.';

            $mail->send();

            return "success";
        } catch (Exception $e) {
            return 'error';
        }
    }

    public function auto_response_content($data) {
        return view('email.auto_response')->with($data);
    }
}
