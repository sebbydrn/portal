<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Browser;
use DB;
use Storage;
use App\Contact;
use App\Link;
use App\Slider;
use App\Partner;
use App\Monitoring;
use Entrust, Auth;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Get all stations
    public function stations()
    {
        $stations = DB::table('philrice_station')->select('*')->orderBy('name', 'asc')->get();
        return $stations;
    }

    // Browser name for logs
    public function browser() {
    	return Browser::browserName();
    }

    // Device for logs
    public function device() {
    	if (Browser::isMobile()) {
    		if (Browser::deviceModel() != "Unknown") {
    			return Browser::deviceModel();
    		} else {
    			return "Mobile";
    		}
    	} else if (Browser::isTablet()) {
    		if (Browser::deviceModel() != "Unknown") {
    			return Browser::deviceModel();
    		} else {
    			return "Tablet";
    		}
    	} else if (Browser::isDesktop()) {
    		return "Desktop";
    	}
    }

    // Countries
    public function countries() {
        // json file is in storage folder
        $json = Storage::disk('local')->get('countries.json');
        $countries = json_decode($json, true);
        asort($countries);
        return $countries;
    }

    // Provinces
    public function regions() {
        $regions = DB::connection('seed_grow')->table('regions')->orderBy('name', 'asc')->get();
        return $regions;
    }

    // Provinces
    public function provinces() {
        $provinces = DB::connection('seed_grow')->table('provinces')->orderBy('name', 'asc')->get();
        return $provinces;
    }

    // municipalities
    /*public function municipalities() {
        $municipalities = DB::connection('seed_grow')->table('municipalities')->orderBy('name', 'asc')->get();
        return $municipalities;
    }*/

    //Get province by id
    public function getRegionById($region_id) {
        $region = DB::connection('seed_grow')->table('regions')->where('region_id',$region_id)->orderBy('name', 'asc')->first();
        return $region;
    }

    //Get province by id
    public function getProvinceById($province_id) {
        $provinces = DB::connection('seed_grow')->table('provinces')->where('province_id',$province_id)->orderBy('name', 'asc')->first();
        return $provinces;
    }

    //Get province by id
    public function getMunicipalityById($municipality_id) {
        $municipality = DB::connection('seed_grow')->table('municipalities')->where('municipality_id',$municipality_id)->orderBy('name', 'asc')->first();
        return $municipality;
    }

    //Get Provinces base by region id
    public function getProvinceByRegionId($region_id) {
        $provinces = DB::connection('seed_grow')->table('provinces')->where('region_id',$region_id)->orderBy('name', 'asc')->get();
        return $provinces;
    }

    //Get Municipalities base by Province id
    public function getMunicipalitiesByProvinceId($province_id) {
        $municipality = DB::connection('seed_grow')->table('municipalities')->where('province_id',$province_id)->orderBy('name', 'asc')->get();
        return $municipality;
    }

    //Get municipalities by User's province code
    public function getProvincesByUsersRegionCode($region_code){
        $region = DB::connection('seed_grow')->table('regions')->where('reg_code',$region_code)->orderBy('name', 'asc')->first();
        $province = DB::connection('seed_grow')->table('provinces')->where('region_id',$region->region_id)->orderBy('name', 'asc')->get();
        return $province;
    }

    //Get municipalities by User's province code
    public function getMunicipalitiesByUsersProvinceCode($province_code){
        $province = DB::connection('seed_grow')->table('provinces')->where('prov_code',$province_code)->orderBy('name', 'asc')->first();
        $municipality = DB::connection('seed_grow')->table('municipalities')->where('province_id',$province->province_id)->orderBy('name', 'asc')->get();
        return $municipality;
    }
    // Affiliations
    public function affiliations() {
        $affiliations = DB::table('affiliations')->orderBy('name', 'asc')->get();
        return $affiliations;
    }

    // OS name for logs
    public function operating_system() {
        return Browser::platformName();
    }

    // Get contacts for landing page
    public function contacts() {
        $contact_model = new Contact();

        $contacts_data = $contact_model->contacts();

        $contacts = array();

        foreach ($contacts_data as $contact) {
            $contacts[$contact->name] = $contact->contact_detail;
        }

        return $contacts;
    }

    // Get links for landing page
    /*public function links() {
        $link_model = new Link();

        $links_data = $link_model->links();

        $links = array();

        foreach ($links_data as $link) {
            $links[$link->name] = $link->link;
        }

        return $links;
    }*/

    // Get slider images for landing page
    public function sliders() {
        $slider_model = new Slider();

        $sliders = $slider_model->sliders();

        return $sliders;
    }

    // Get partners for landing page
    public function partners_list() {
        $partner_model = new Partner();

        $partners = $partner_model->partners();

        return $partners;
    }

    //get seedLogs
    public function getSeedLogs($filter) {
        $monitoring = new Monitoring;
        $accred_no ="0";
        if(Entrust::hasRole('seed_producer')){
            $user = DB::connection('pgsql')
                ->table('users')
                ->join('affiliation_user as au','au.user_id','users.user_id')
                ->join('accreditation_numbers as an','an.affiliation_user_id','au.affiliation_user_id')
                ->select('an.*','users.firstname','users.lastname')
                ->where('au.user_id',Auth::id())
                ->first();

            if($user != null){
                $accred_no = $user->accred_no;
            }  
        }

        if($filter['region_id'] != 0){
            $region = $this->getRegionById($filter['region_id']);
            $filter['region_id'] = $region->reg_code;
        }

        if($filter['province_id'] != 0){
            $province = $this->getProvinceById($filter['province_id']);
            $filter['province_id'] = $province->prov_code;
        }

        if($filter['municipality_id'] != 0){
            $municipality = $this->getMunicipalityById($filter['municipality_id']);
            $filter['municipality_id'] = $municipality->mun_code;
        }

        $logs = array();
        if(Entrust::can('view_seed_sale_logs')){
            $seedSale = $monitoring->getSeedSale($filter,$accred_no);
            $seedTemp = $monitoring->getTempLogs($filter,$accred_no);
            $user = array();
            foreach($seedSale as $sl){
                    $data = DB::connection('pgsql')
                    ->table('users')
                    ->join('affiliation_user as au','au.user_id','users.user_id')
                    ->join('accreditation_numbers as an','an.affiliation_user_id','au.affiliation_user_id')
                    ->select('an.*','users.firstname','users.lastname','users.region','users.province','users.municipality')
                    ->where('an.accred_no',$sl['accred_no']);

                    if(Entrust::can('view_regional_data')){
                        $data->where('users.region',Auth::user()->region);
                    }

                    if(Entrust::can('view_provincial_data')){
                        $data->where('users.province',Auth::user()->province);
                    }

                    if(Entrust::can('view_municipal_data'))
                    {
                        $data->where('users.municipality',Auth::user()->municipality);
                    }

                    if(Entrust::can(['view_national_data', 'view_regional_data', 'view_provincial_data'])){

                        if($filter['region_id'] != "0")
                        {
                            $data->where('users.region',$filter['region_id']);
                        }
                        if($filter['province_id'] != "0")
                        {
                            $data->where('users.province',$filter['province_id']);
                        }
                        if($filter['municipality_id'] != "0")
                        {
                            $data->where('users.municipality',$filter['municipality_id']);
                        }
                    }
                    //if($filter['regional'])
                    $user = $data->get()->first();
                
                if($user != null){
                    $logs[] = array(
                        'order_id' => $sl['order_id'],
                        'seedgrower' =>$user->firstname.' '. $user->lastname,
                        'status' => $sl['status'],
                        'timestamp' =>date('m/d/Y H:i:s', strtotime($sl['timestamp'])),
                        'browser' => $sl['browser'],
                        'device' => $sl['device'],
                        'ip_address' => $sl['ip_address'],
                        'variety' => $sl['variety'],
                        'pallet_code' => $sl['pallet_code'],
                        'province' => $user->province,
                        
                    );
                }
                /*else{
                    $logs[] = array(
                        'order_id' => $sl['order_id'],
                        'seedgrower' => '',
                        'status' => $sl['status'],
                        'timestamp' =>date('m/d/Y H:i:s', strtotime($sl['timestamp'])),
                        'browser' => $sl['browser'],
                        'device' => $sl['device'],
                        'ip_address' => $sl['ip_address'],
                        'variety' => $sl['variety'],
                        'pallet_code' => $sl['pallet_code'],
                        'province' => ''
                    );
                }*/
            }

            foreach($seedTemp as $st){
                $user = array();
                
                   $data = DB::connection('pgsql')
                    ->table('users')
                    ->join('affiliation_user as au','au.user_id','users.user_id')
                    ->join('accreditation_numbers as an','an.affiliation_user_id','au.affiliation_user_id')
                    ->select('an.*','users.firstname','users.lastname')
                    ->where('an.accred_no',$st['accred_no']);
                    if(Entrust::can('view_regional_data'))
                    {
                        $data->where('users.region',Auth::user()->region);
                    }
                    if(Entrust::can('view_provincial_data'))
                    {
                        $data->where('users.province',Auth::user()->province);
                    }

                    if(Entrust::can('view_municipal_data'))
                    {
                        $data->where('users.municipality',Auth::user()->municipality);
                    }
                    if(Entrust::can(['view_national_data','view_regional_data','view_provincial_data'])){
                        if($filter['region_id'] != "0")
                        {
                            $data->where('users.region',$filter['region_id']);
                        }
                        if($filter['province_id'] != "0")
                        {
                            $data->where('users.province',$filter['province_id']);
                        }
                        if($filter['municipality_id'] != "0")
                        {
                            $data->where('users.municipality',$filter['municipality_id']);
                        }
                    }
                    //if($filter['regional'])
                    $user = $data->get()->first();

                if($user != null) {
                    $logs[] = array(
                        'order_id' => 'N/A',
                        'seedgrower' => $user->firstname.' '.$user->lastname,
                        'status' => $st['status'],
                        'timestamp' => date('m/d/Y H:i:s', strtotime($st['timestamp'])),
                        'browser' => $st['browser'],
                        'device' => $st['device'],
                        'ip_address' => $st['ip_address'],
                        'variety' => $st['variety'],
                        'pallet_code' => $st['pallet_code']
                    );
                    
                }
                /*else{
                    $logs[] = array(
                        'order_id' => 'N/A',
                        'seedgrower' => '',
                        'status' => $st['status'],
                        'timestamp' => date('m/d/Y H:i:s', strtotime($st['timestamp'])),
                        'browser' => $st['browser'],
                        'device' => $st['device'],
                        'ip_address' => $st['ip_address'],
                        'variety' => $st['variety'],
                        'pallet_code' => $st['pallet_code']
                    );
                }*/
            }
        }
        return $logs;
    }

    public function getUserLogAccess($filter) {
        $monitoring = new Monitoring;
        if($filter['region_id'] != 0){
            $region = $this->getRegionById($filter['region_id']);
            $filter['region_id'] = $region->reg_code;
        }

        if($filter['province_id'] != 0){
            $province = $this->getProvinceById($filter['province_id']);
            $filter['province_id'] = $province->prov_code;
        }

        if($filter['municipality_id'] != 0){
            $municipality = $this->getMunicipalityById($filter['municipality_id']);
            $filter['municipality_id'] = $municipality->mun_code;
        }
        $userLogs = $monitoring->getUserLogAccess($filter);
        $logs = array();
        foreach($userLogs as $ul) {
            $logs[] = array(
                'user_id' => $ul->user_id,
                'activity' => $ul->activity,
                'user' => $ul->firstname.' '.$ul->lastname,
                'device' => $ul->device,
                'browser' => $ul->browser,
                'ip_address'=> $ul->ip_env_address,
                'timestamp' => date('m/d/Y H:i:s', strtotime($ul->timestamp))
            );
        }
        return $logs;
    }

    public function getUserLogAction($filter){
        $monitoring = new Monitoring;
        if($filter['region_id'] != 0){
            $region = $this->getRegionById($filter['region_id']);
            $filter['region_id'] = $region->reg_code;
        }

        if($filter['province_id'] != 0){
            $province = $this->getProvinceById($filter['province_id']);
            $filter['province_id'] = $province->prov_code;
        }

        if($filter['municipality_id'] != 0){
            $municipality = $this->getMunicipalityById($filter['municipality_id']);
            $filter['municipality_id'] = $municipality->mun_code;
        }

        $userLogs = $monitoring->getUserLogAction($filter);
        $logs = array();
        foreach($userLogs as $ul) {
            $logs[] = array(
                'user_id' => $ul->user_id,
                'activity' => $ul->activity,
                'user' => $ul->firstname.' '.$ul->lastname,
                'new_value' => $ul->new_value,
                'device' => $ul->device,
                'browser' => $ul->browser,
                'ip_address'=> $ul->ip_env_address,
                'timestamp' => date('m/d/Y H:i:s', strtotime($ul->timestamp))
            );
        }
        return $logs;
    }

    // XML location of SG API
    public function sgFilePath() {
        return $_SERVER['DOCUMENT_ROOT'].'/api/xml/bpi/APISG/APISGDataList';
    }

    // Preliminary inspection XML
    public function SPIFilePath() {
        return $_SERVER['DOCUMENT_ROOT'].'/api/xml/bpi/APISPI/APISPIDataList';
    }

    // Final inspection XML
    public function SPFIFilePath() {
        return $_SERVER['DOCUMENT_ROOT'].'/api/xml/bpi/APISPFI/APISPFIDataList';
    }

    // Seed Cooperatives XML
    public function SCFilePath() {
        return $_SERVER['DOCUMENT_ROOT'].'/api/xml/bpi/APISC/APISCDataList';
    }

}
