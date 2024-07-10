<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MatchOldPassword;
use App\User;
use App\System;
use App\Producer;
use Entrust;
use Auth, DB, Hash;
use App\Monitoring;
use App\Passwords;
use App\AffiliationUser;
use Yajra\Datatables\Datatables;

class ProfileController extends Controller {

	public function __construct() {
        $this->middleware('permission:view_settings')->only(['settings']);
    }
	
	public function index() {
		$avatar = DB::table('profile_pictures')->where('user_id',Auth::id())->where('is_active',true)->first();
		$contacts = $this->contacts();
		
		//$user = Auth::user();
		$user = DB::table('users')
		->leftJoin('affiliation_user as aff_user','aff_user.user_id','users.user_id')
		->leftJoin('affiliations','affiliations.affiliation_id','aff_user.affiliation_id')
		->select('users.*','affiliations.name as affiliation_name')
		->where('users.user_id',Auth::id())
		->first();
		$province = DB::connection('seed_grow')->table('provinces')->select('name')->where('prov_code',$user->province)->first();
		$municipality = DB::connection('seed_grow')->table('municipalities')->select('name')->where('mun_code',$user->municipality)->first();
		return view('profile.index')
		->with(compact('contacts','user','province','municipality','avatar'));
	}

	public function edit($id){
		$contacts = $this->contacts();
		// Get user
        $user = new User();
        $user_data = $user->getUser($id);

        // Get municipalities for user's province
        $province = $user->get_province_id($user_data->province);
        if ($province) {
            $province_id = $province->province_id;
            $municipalities = $user->get_municipalities($province_id);
        } else {
            $municipalities = '';
        }
        
        // Stations
        $stations = $this->stations();

        // Countries
        // $countries = $this->countries();

        // Provinces
        $provinces = $this->provinces();

        // Affiliations
        $affiliations = $this->affiliations();

        // Get user affiliation
        $user_affiliation = $user->get_user_affiliation($id);
        if ($user_affiliation == NULL) {
            $user_affiliation = '';
        }

        return view('profile.edit')
        ->with(compact('contacts'))
        ->with(compact('user_data'))
        ->with(compact('stations'))
        // ->with(compact('countries'))
        ->with(compact('provinces'))
        ->with(compact('municipalities'))
        ->with(compact('affiliations'))
        ->with(compact('user_affiliation'));
	}

	public function update(Request $request, $id)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:users,username, '.$id.',user_id',
            'email' => 'required|email|unique:users,email, '.$id.',user_id',
            'secondaryEmail' => 'email|unique:users,email, '.$id.',user_id',
            'birthday' => 'required',
            'contact_no' => 'unique:users,contact_no, '.$id.',user_id',
            // 'country' => 'required',
            'province' => 'required_if:country,PH',
            'municipality' => 'required_if:country,PH',
            'barangay' => 'required_if:country,PH',
            'affiliation' => 'required',
            'station' => 'required_if:affiliation,1',
            'philrice_idno' => 'nullable|required_if:affiliation,1|unique:users,philrice_idno, '.$id.',user_id',
            'agency' => 'required_if:affiliation,6',
            'school' => 'required_if:affiliation,5',
        ], [
            'firstname.required' => 'The first name field is required.',
            'lastname.required' => 'The last name field is required',
            'contact_no.unique' => 'The contact no. has already been taken.',
            'province.required_if' => 'The province field is required when country is Philippines.',
            'municipality.required_if' => 'The municipality field is required when country is Philippines.',
            'barangay.required_if' => 'The barangay field is required when country is Philippines.',
            'philrice_idno.required_if' => 'The PhilRice ID No. field is required if you selected a PhilRice as affiliation.',
            'philrice_idno.unique' => 'The PhilRice ID No. has already been taken.',
            'station.required_if' => 'The PhilRice Station field is required if you selected PhilRice as affiliation.',
            'agency.required_if' => 'The Agency field is required if you selected Researcher as affiliation.',
            'school.required_if' => 'The University/ School field is required if you selected Student as affiliation.'
        ]);

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
            'secondaryemail' => $request->secondaryemail,
            'birthday' => $request->birthday,
            'sex' => $request->sex,
            'contact_no' => $request->contact_no,
            // 'country' => $request->country,
            'region' => ($request->country == "PH") ? $request->region : '',
            'province' => ($request->country == "PH") ? $request->province : '',
            'municipality' => ($request->country == "PH") ? $request->municipality : '',
            'barangay' => ($request->country == "PH") ? $request->barangay : '',
            'philrice_idno' => ($request->affiliation == 1) ? $request->philrice_idno : '',
            'designation' => $request->designation,
            'affiliation' => $request->affiliation,
            'station' => ($request->affiliation == 1) ? $request->station : '',
            'cooperative' => ($request->affiliation == 3 || $request->affiliation == 9) ? $request->coop : '',
            'agency' => ($request->affiliation == 6) ? $request->agency : '',
            'school' => ($request->affiliation == 5) ? $request->school : ''
        );

        $old_user_data = array(
            'old_firstname' => $request->old_firstname,
            'old_middlename' => $request->old_middlename,
            'old_lastname' => $request->old_lastname,
            'old_extname' => $request->old_extname,
            'old_fullname' => $request->old_fullname,
            'old_username' => $request->old_username,
            'old_email' => $request->old_email,
            'old_secondaryemail' => $request->old_secondaryemail,
            'old_birthday' => $request->old_birthday,
            'old_sex' => $request->old_sex,
            'old_contact_no' => $request->old_contact_no,
            // 'old_country' => $request->old_country,
            'old_region' => $request->old_region,
            'old_province' => $request->old_province,
            'old_municipality' => $request->old_municipality,
            'old_barangay' => $request->barangay,
            'old_philrice_idno' => $request->old_philrice_idno,
            'old_designation' => $request->old_designation,
            'old_affiliation' => $request->old_affiliation,
            'old_station' => $request->old_station,
            'old_cooperative' => $request->old_coop,
            'old_agency' => $request->old_agency,
            'old_school' => $request->old_school
        );

        DB::beginTransaction();
        try {
            // Update user
            $user = User::find($id);
            $user->firstname = $user_data['firstname'];
            $user->middlename = $user_data['middlename'];
            $user->lastname = $user_data['lastname'];
            $user->extname = $user_data['extname'];
            $user->fullname = $user_data['fullname'];
            $user->username = $user_data['username'];
            $user->email = $user_data['email'];
            $user->secondaryemail = $user_data['secondaryemail'];
            $user->birthday = $user_data['birthday'];
            $user->sex = $user_data['sex'];
            $user->contact_no = $user_data['contact_no'];
            // $user->country = $user_data['country'];
            $user->region = $user_data['region'];
            $user->province = $user_data['province'];
            $user->municipality = $user_data['municipality'];
            $user->barangay = $user_data['barangay'];
            $user->philrice_idno = $user_data['philrice_idno'];
            $user->designation = $user_data['designation'];
            $user->cooperative = $user_data['cooperative'];
            $user->agency = $user_data['agency'];
            $user->school = $user_data['school'];
            $user->save();

            // Update user affiliation
            /*$user_affiliation = AffiliationUser::where('user_id', $id)
            ->update([
                'affiliation_id' => $user_data['affiliation'],
                'affiliated_to' => $user_data['station']
            ]);*/
            
            if ($old_user_data['old_affiliation'] != '') {
                AffiliationUser::where('user_id', $id)
                ->update([
                    'affiliation_id' => $user_data['affiliation'],
                    'affiliated_to' => ($user_data['station']) ? $user_data['station'] : 0
                ]); 
            } else {
                $user_affiliation = AffiliationUser::updateOrCreate([
                    'user_id' => $id,
                    'affiliation_id' => $user_data['affiliation'],
                    'affiliated_to' => $user_data['station']
                ]);
            }
            

            // if ($user_data['affiliation'] == 1) {
            //     $user_affiliation = AffiliationUser::updateOrCreate([
            //         'user_id' => $id,
            //         'affiliation_id' => $user_data['affiliation'],
            //         'affiliated_to' => $user_data['station']
            //     ]);
            // } else {
            //     if ($user_data['affiliation'] != $old_user_data['old_affiliation']) {
            //         $user_affiliation = AffiliationUser::updateOrCreate([
            //             'user_id' => $id,
            //             'affiliation_id' => $user_data['affiliation'],
            //             'affiliated_to' => 0
            //         ]);  
            //     } else {
            //         $user_affiliation = AffiliationUser::updateOrCreate([
            //             'user_id' => $id,
            //             'affiliation_id' => $user_data['affiliation'],
            //             'affiliated_to' => 0
            //         ]);
            //     }
            // }
            

            // Check if original value is different from changed value
            // If true save as log
            foreach ($user_data as $key => $value) {
                if ($old_user_data['old_'.$key] != $value) {
                    $log = array(
                        'activity_id' => 4,
                        'user_id' => $id,
                        'browser' => $this->browser(),
                        'device' => $this->device(),
                        'ip_env_address' => $request->ip(),
                        'ip_server_address' => request()->server('SERVER_ADDR'),
                        'old_value' => $old_user_data['old_'.$key],
                        'new_value' => $value,
                        'OS' => $this->operating_system()
                    );

                    $res = $user_model->add_log($log);
                }
            }

            DB::commit();
            $res = "success";
        } catch (Exception $e) {
            DB::rollback();
            $res = $e->getMessage();
        }

        if ($res == "success") {
            $request->session()->flash('success', 'User successfully updated.');
        } else {
            $request->session()->flash('error', $res);
        }

        if($request->has('portal_edit'))
        {
            return redirect('../portal/profile');
        }
        return redirect()->route('profile.index');
    }

	public function password() {
		$contacts = $this->contacts();
		$user_id = Auth::id();
		return view('profile.password')
		->with(compact('contacts','user_id'));
	}

	public function updatePassword(MatchOldPassword $request) {
		$user_id = $request->user_id;
		$password = Hash::make($request->new_password);
		DB::beginTransaction();
        try {
            // Update password
            Passwords::where('user_id', $user_id)
            ->update(['password' => $password]);

            // Add log
            $log = array(
                'activity_id' => 5,
                'user_id' => $user_id,
                'browser' => $this->browser(),
                'device' => $this->device(),
                'ip_env_address' => $request->ip(),
                'ip_server_address' => request()->server('SERVER_ADDR'),
                'OS' => $this->operating_system()
            );

            $user_model = new User;
            $res = $user_model->add_log($log);

            DB::commit();
            $res = "success";
        } catch (Exception $e) {
            DB::rollback();
            $res = $e->getMessage();
        }

        if ($res == "success") {
            $request->session()->flash('success', 'Password successfully changed.');
        } else {
            $request->session()->flash('error', $res);
        }

        return redirect()->route('profile.index');

	}

	public function portal() {
		$contacts = $this->contacts();

		// Get systems user has access to
    	$user_model = new User();
    	$user_id = Auth::user()->user_id;
    	$user_systems = $user_model->get_user_systems($user_id);

    	$system_id = array();

    	foreach ($user_systems as $user_system) {
    		array_push($system_id, $user_system->system_id);
    	}

    	// Get systems
    	$system_model = new System();

    	$systems = $system_model->systems();

    	$data = array(); // Systems that the logged in user has access to

    	foreach ($systems as $system) {
    		if (in_array($system->system_id, $system_id)) {
    			$data[] = array(
    				'display_name' => $system->display_name,
    				'name' => $system->name,
    				'url' => url('../'.$system->name.''),
				'group' => $system->group
    			);
    		}
    	}

    	return view('profile.portal')
		->with(compact('contacts'))
		->with(compact('data'));
	}

	public function analytics() {
		$contacts = $this->contacts();

		return view('profile.analytics')
		->with(compact('contacts'));
	}

	public function seedsale() {
		$filter = array(
			'from' => 0,
			'to' => 0,
			'station_code' => 0,
			'region_id' => 0,
			'province_id' => 0,
			'municipality_id' => 0,
		);
		$contacts = $this->contacts();
		$logs = $this->getSeedLogs($filter);
		$regions = $this->regions();
		$provinces = $this->getProvincesByUsersRegionCode(Auth::user()->region);
		
		$municipalities = $this->getMunicipalitiesByUsersProvinceCode(Auth::user()->province);
		$stations = $this->stations();
		//remove all the duplicate activity
		$unique_status = array();
        foreach($logs as $l){
            $unique_status[] = $l['status'];
        }
        $statuses = array_unique($unique_status);
		return view('profile.seedsale')
		->with(compact('contacts','statuses','regions','provinces','municipalities','stations'));
	}

	public function logaccess(){
		$filter = array(
			'from' => 0,
			'to' => 0,
			'region_id' => 0,
			'province_id' => 0,
			'municipality_id' => 0,
		);

		$contacts = $this->contacts();
		$logs = $this->getUserLogAccess($filter);
		$regions = $this->regions();
		$provinces = $this->getProvincesByUsersRegionCode(Auth::user()->region);
		$municipalities = $this->getMunicipalitiesByUsersProvinceCode(Auth::user()->province);
		$unique_activity = array();
        foreach($logs as $l){
            $unique_activity[] = $l['activity'];
        }
        $activities = array_unique($unique_activity);
		return view('profile.logaccess')
		->with(compact('contacts','activities','regions','provinces','municipalities'));
	}

	public function logaction(){
		$filter = array(
			'from' => 0,
			'to' => 0,
			'region_id' => 0,
			'province_id' => 0,
			'municipality_id' => 0,
		);

		$contacts = $this->contacts();
		$logs = $this->getUserLogAction($filter);

		$regions = $this->regions();
		$provinces = $this->getProvincesByUsersRegionCode(Auth::user()->region);
		$municipalities = $this->getMunicipalitiesByUsersProvinceCode(Auth::user()->province);
		$unique_activity = array();
        foreach($logs as $l){
            $unique_activity[] = $l['activity'];
        }
        $activities = array_unique($unique_activity);
		return view('profile.action')
		->with(compact('contacts','activities','regions','provinces','municipalities'));
	}
	public function settings() {
		$contacts = $this->contacts();

		return view('profile.settings')
		->with(compact('contacts'));
	}

	public function seedLogDatatable(Request $request) {
		$filter = array(
			'from' => $request->date_from,
			'to' => $request->date_to,
			'station_code' => $request->station_code,
			'region_id' => $request->region_id,
			'province_id' => $request->province_id,
			'municipality_id' => $request->municipality_id,
		);
		$logs = $this->getSeedLogs($filter);
		$data = array();
		foreach($logs as $log){
			if($request->status == "none") {
				$data[] = array(
					'order_id' => $log['order_id'],
					'seedgrower' => $log['seedgrower'],
					'status' => $log['status'],
					'timestamp' => date('m/d/Y', strtotime($log['timestamp'])),
					'device' => $log['device'],
					'browser' => $log['browser'],
					'ip_address' => $log['ip_address'],
					'variety' => $log['variety']
				);
        	}

        	if($log['status'] == $request['status'] )
        	{
				$data[] = array(
					'order_id' => $log['order_id'],
					'seedgrower' => $log['seedgrower'],
					'status' => $log['status'],
					'timestamp' => date('m/d/Y', strtotime($log['timestamp'])),
					'device' => $log['device'],
					'browser' => $log['browser'],
					'ip_address' => $log['ip_address'],
					'variety'=> $log['variety']
					);
        	}
		}
		$datatable = Datatables::of($data);
	    return $datatable->make(true);
	}

	//get all the logaccess
	public function logAccessDatatable(Request $request) {
		$filter = array(
			'from' => $request->date_from,
			'to' => $request->date_to,
			'region_id' => $request->region_id,
			'province_id' => $request->province_id,
			'municipality_id' => $request->municipality_id,
		);
		$logs = $this->getUserLogAccess($filter);
		$data = array();
		foreach($logs as $log){
			if($request->activity == "none") {
				$data[] = array(
					'activity' => $log['activity'],
					'user' => $log['user'],
					'device' => $log['device'],
					'browser' => $log['browser'],
					'ip_address' => $log['ip_address'],
					'timestamp' => $log['timestamp']
				);
			}

			if($log['activity'] == $request->activity){
				$data[] = array(
					'activity' => $log['activity'],
					'user'=> $log['user'],
					'device' => $log['device'],
					'browser' => $log['browser'],
					'ip_address' => $log['ip_address'],
					'timestamp' => $log['timestamp']
				);
			}
		}
		$datatable = Datatables::of($data);
	    return $datatable->make(true);
	}


	//get all the action\update the user done
	public function logActionDatatable(Request $request) {
		$filter = array(
			'from' => $request->date_from,
			'to' => $request->date_to,
			'region_id' => $request->region_id,
			'province_id' => $request->province_id,
			'municipality_id' => $request->municipality_id,
		);
		$logs = $this->getUserLogAction($filter);
		$data = array();
		foreach($logs as $log){
			if($request->activity == "none") {
				$data[] = array(
					'activity' => $log['activity'],
					'user' => $log['user'],
					'device' => $log['device'],
					'new_value' => $log['new_value'],
					'browser' => $log['browser'],
					'ip_address' => $log['ip_address'],
					'timestamp' => $log['timestamp']
				);
			}

			if($log['activity'] == $request->activity){
				$data[] = array(
					'activity' => $log['activity'],
					'user'=> $log['user'],
					'device' => $log['device'],
					'new_value' => $log['new_value'],
					'browser' => $log['browser'],
					'ip_address' => $log['ip_address'],
					'timestamp' => $log['timestamp']
				);
			}
		}
		$datatable = Datatables::of($data);
	    return $datatable->make(true);
	}
	// Provinces for datatable filter
   	public function getProvinces(Request $request) {
   		// Get data
   		$provinces = $this->getProvinceByRegionId($request->region_id);

   		echo json_encode($provinces);
   	}

   	// Municipalities for datatable filter
   	public function getMunicipalities(Request $request) {
   		// Get data
   		$municipalities = $this->getMunicipalitiesByProvinceId($request->province_id);

   		echo json_encode($municipalities);
   	}

   	public function region_code(Request $request) {
        $region_id = $request->region_id;
        $user = new User();
        $region_code = $user->get_region_code($region_id);
        
        echo json_encode($region_code);
    }

    public function municipalities_code(Request $request) {
        $province_id = $request->province_id;
        $user = new User();
        $municipalities = $user->get_municipalities($province_id);

        echo json_encode($municipalities);
    }

    public function addAvatar(Request $request){

    	$user = new User();
    	
    	if($request->profile_id != "none")
    	{
    		DB::table('profile_pictures')
    		->where('profile_pic_id',$request->profile_id)
    		->update([
    			'is_active' => false,
    		]);
    	}
    	$log = array(
                'activity_id' => 18,
                'user_id' => Auth::id(),
                'browser' => $this->browser(),
                'device' => $this->device(),
                'ip_env_address' => $request->ip(),
                'ip_server_address' => request()->server('SERVER_ADDR'),
                'OS' => $this->operating_system()
            );
    	$user->add_log($log);
    	DB::table('profile_pictures')
    	->insert([
    		'image_name' => $request->image,
    		'user_id' => Auth::id(),
    		'is_active' => true
    	]);

    	return "success";
    }
}
