<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\User;
use App\AffiliationUser;
use App\Affiliation;
use App\Passwords;
use App\RegNotifReceiver;
use DB;
use PHPMailer\PHPMailer;
use Redirect;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

        // affiliation and accreNo variables are used in validating accreditation number if unique
        $affiliation = 0;
        $accreNo = "";
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {   
        $contacts = $this->contacts();

        // Stations
        $stations = $this->stations();

        // Countries
        $countries = $this->countries();

        // Provinces
        $provinces = $this->provinces();

        // Affiliations
        $affiliations = $this->affiliations();

        return view('auth.register')
        ->with(compact('contacts'))
        ->with(compact('stations'))
        ->with(compact('countries'))
        ->with(compact('provinces'))
        ->with(compact('affiliations'));
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:users',
            'email' => 'email|required|unique:users',
            // 'birthday' => 'required',
            'age' => 'required',
            'sex' => 'required',
            // 'country' => 'required',
            // 'province' => 'required_if:country,PH',
            // 'municipality' => 'required_if:country,PH',
            // 'barangay' => 'required_if:country,PH',
            'province' => 'required',
            'municipality' => 'required',
            'barangay' => 'required',
            'affiliation' => 'required',
            'station' => 'required_if:affiliation,1',
            'philrice_idno' => 'nullable|required_if:affiliation,1|unique:users,philrice_idno',
            'agency' => 'required_if:affiliation,6',
            'school' => 'required_if:affiliation,5',
            'accreditation_no' => 'required_if:affiliation,3',
            'agree' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'firstname.required' => 'The first name field is required.',
            'lastname.required' => 'The last name field is required.',
            'province.required_if' => 'The province field is required when country is Philippines.',
            'municipality.required_if' => 'The municipality field is required when country is Philippines.',
            'barangay.required_if' => 'The barangay field is required when country is Philippines.',
            'philrice_idno.required_if' => 'The PhilRice ID No. field is required if you selected a PhilRice as affiliation.',
            'philrice_idno.unique' => 'The PhilRice ID No. has already been taken.',
            'station.required_if' => 'The PhilRice Station field is required if you selected PhilRice as affiliation.',
            'agency.required_if' => 'The Agency field is required if you selected Researcher as affiliation.',
            'school.required_if' => 'The University/ School field is required if you selected Student as affiliation.',
            'accreditation_no.required_if' => 'The Accreditation No. field is required if you selected Seed Grower as affiliation.',
            'agree.required' => 'Please agree to the terms.'
        ]);

        $this->affiliation = $request->affiliation;
        $this->accreNo = $request->accreditation_no;

        // Validate if accreditation number is unique if affiliation is seed grower
        $validator->after(function ($validator) {
            if ($this->affiliation == 3) {
                $accreditation_no = $this->accreNo;

                $isAccreNoUnique = User::where('accreditation_no', $accreditation_no)->count();

                if ($isAccreNoUnique > 0) {
                    $validator->errors()->add('accreditation_no', 'The Accreditation No. field has already been taken.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Fullname of user
        if ($request->middlename == "" && $request->extname == "") {
            $fullname = $request->firstname . ' ' . $request->lastname;
        } elseif ($request->middlename != "" && $request->extname == "") {
            $fullname = $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname;
        } elseif ($request->middlename == "" && $request->extname != "") {
            $fullname = $request->firstname . ' ' . $request->lastname . ' ' . $request->extname;
        } elseif ($request->middlename != "" && $request->extname != "") {
            $fullname = $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname . ' ' . $request->extname;
        }

        $user_model = new User();

        $user_data = array(
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'extname' => $request->extname,
            'fullname' => $fullname,
            'username' => $request->username,
            'email' => $request->email,
            // 'birthday' => $request->birthday,
            'age' => $request->age,
            'sex' => $request->sex,
            // 'country' => $request->country,
            // 'region' => ($request->country == "PH") ? $request->region : '',
            // 'province' => ($request->country == "PH") ? $request->province : '',
            // 'municipality' => ($request->country == "PH") ? $request->municipality : '',
            // 'barangay' => ($request->country == "PH") ? $request->barangay : '',
            'region' => $request->region,
            'province' => $request->province,
            'municipality' => $request->municipality,
            'barangay' => $request->barangay,
            'philrice_idno' => ($request->affiliation == 1) ? $request->philrice_idno : '',
            'designation' => $request->designation,
            'cooperative' => ($request->affiliation == 3 || $request->affiliation == 9) ? $request->coop : '',
            'agency' => ($request->affiliation == 6) ? $request->agency : '',
            'school' => ($request->affiliation == 5) ? $request->school : '',
            'accreditation_no' => ($request->affiliation == 3) ? $request->accreditation_no : '',
            'isactive' => 0,
            'isapproved' => 0
        );

        DB::beginTransaction();
        try {
            // Add user
            $res = User::create($user_data);
            $user_id = $res->user_id;

            // Add password
            $password_data = array(
                'password' => "",
                'user_id' => $user_id,
                'system_id' => 0
            );
            // Add Password
            Passwords::create($password_data);

            $affiliation_data = array(
                'affiliation_id' => $request->affiliation,
                'user_id' => $user_id,
                'affiliated_to' => ($request->affiliation == 1) ? $request->station : 0
            );

            $res2 = AffiliationUser::create($affiliation_data);

            // Add log
            $log = array(
                'activity_id' => 11,
                'user_id' => $user_id,
                'browser' => $this->browser(),
                'device' => $this->device(),
                'ip_env_address' => $request->ip(),
                'ip_server_address' => request()->server('SERVER_ADDR'),
                'OS' => $this->operating_system()
            );

            $res3 = $user_model->add_log($log);

            // Send email notification for registration
            $content = array();
            $content['firstname'] = $user_data['firstname'];

            // Get affiliation name
            $affiliation = Affiliation::find($affiliation_data['affiliation_id']);
            $affiliation_name = $affiliation->name;

            $content['affiliation'] = $affiliation_name;
            $res4 = $this->send_email($request->email, $content);

            // Send email notification to developers and admins
            $notifData = array(
                'email' => $user_data['email'],
                'username' => $user_data['username']
            );

            $notif_res = $this->send_email_notification($notifData);

            if ($res4 == "success" && $notif_res == "success") {
                DB::commit(); // Only commit the transaction if success in sending email
                // $request->session()->flash('success', `Thank you for registering! A confirmation email has been sent to your inbox. If the email doesn't arrive shortly, please check your spam folder.`);
                $contacts = $this->contacts();

                return view('auth.success_register')
                ->with(compact('contacts'));
            } else {
                $request->session()->flash('error', $res);
            }

            return redirect()->route('register');
        } catch (Exception $e) {
            $res_msg = $e->getMessage();
            DB::rollback();
            echo $res_msg;
        }
    }

    public function send_email($email, $content) {
        $mail = new PHPMailer\PHPMailer(true);

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
            return $e->getMessage();
        }
    }

    public function email_content($content) {
        return view('email.reg_acknowledgement')->with($content);
    }

    public function region_code(Request $request) {
        $region_id = $request->region_id;
        $user_model = new User();
        $region_code = $user_model->get_region_code($region_id);
        
        echo json_encode($region_code);
    }

    public function municipalities(Request $request) {
        $province_id = $request->province_id;
        $user_model = new User();
        $municipalities = $user_model->get_municipalities($province_id);

        echo json_encode($municipalities);
    }

    public function success() {
        $contacts = $this->contacts();

        return view('auth.success_register')
        ->with(compact('contacts'));
    }

    public function send_email_notification($content) {
        $reg_notif_receivers = RegNotifReceiver::select('user_id', 'receive_type')->get();

        $receivers = array();

        // Get receiver emails
        foreach ($reg_notif_receivers as $item) {
            $user = User::select('email')->where('user_id', '=', $item->user_id)->first();

            $receivers[] = array(
                'email' => $user->email,
                'receive_type' => $item->receive_type
            );
        }

        $mail = new PHPMailer\PHPMailer(true);

        try {
            // Server settings
            //$mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();  // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'rsis.bpi.philrice@gmail.com'; // SMTP username
            // $mail->Password = 'rsisDA_2020'; // SMTP password
            $mail->Password = 'nbyklvyfxpemkydo';
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('rsis.bpi.philrice@gmail.com', 'Rice Seed Information System');

            foreach ($receivers as $receiver) {
                if ($receiver['receive_type'] == 1) {
                    $mail->addAddress($receiver['email']); // Add a recipient
                } else if ($receiver['receive_type'] == 2) {
                    $mail->addCC($receiver['email']); // Add CC
                } else if ($receiver['receive_type'] == 3) {
                    $mail->addBCC($receiver['email']); // Add BCC
                }
            }

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'RSIS New User Registration';
            $mail->Body    = $this->email_notification_content($content);
            $mail->AltBody = 'Please enable HTML content to view this email.';

            $mail->send();
            return "success";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function email_notification_content($content) {
        return view('email.registration_notification')->with($content);
    }
}
