<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB, Auth, Entrust;
use App\Log;

class Monitoring extends Model
{
	//this function display all the transactions in tbl_release_pur
    public function getSeedSale($filter,$accred_no){
    	
    	$logTbl = Log::select('tblName')
				->orderBy('createdAt','ASC');
        if($filter['station_code'] != "0" ){
            $logTbl->where('tblName', 'like', '%tbl_release_pur_'.strtolower($filter['station_code']).'%');
        }
        else{
            $logTbl->where('tblName', 'like', '%tbl_release_pur_%');
        }
        $releaseTbl = $logTbl->get();

		$logs = array();
		foreach($releaseTbl as $rt){
				$stocks_tbl = substr_replace($rt->tblName, "tbl_stocks", 0, 15);
				$release = DB::connection('warehouse')
				->table($rt->tblName.' as releasing')
				->leftJoin($stocks_tbl.' as stocks','stocks.palletCode','releasing.pallet_code')
				->leftJoin('rsis_seed_seed.seed_characteristics as seeds','seeds.id','stocks.seedVarietyId')
				->select('releasing.order_id','releasing.accred_no','releasing.status','releasing.timestamp','releasing.pallet_code','releasing.browser','releasing.device','releasing.ip_env_address','stocks.seedVarietyId','seeds.variety');
                if(Entrust::hasRole('seed_producer')){
                    $release->where('accred_no',$accred_no);
                }
				if($filter['from'] != 0 && $filter['to'] != 0 ){
					$to = date('Y-m-d', strtotime($filter['to'] . ' +1 day'));
                    $from = date('Y-m-d', strtotime($filter['from']));
                $release->whereBetween('timestamp',[$from,$to]);
		        }
				if($accred_no != 0){
					$release->where('accred_no',$accred_no);
				}
				$data = $release->get();
				
				foreach($data as $dt){
					$logs[] = array(
						'order_id' => $dt->order_id,
	                    'status' => $dt->status,
	                    'timestamp' =>$dt->timestamp,
	                    'browser' => $dt->browser,
	                    'device' => $dt->device,
	                    'ip_address' => $dt->ip_env_address,
	                    'variety' => $dt->variety,
	                    'pallet_code' => $dt->pallet_code,
	                    'accred_no' => $dt->accred_no
					);
				}
		}

        return $logs;
    }

    //this function display all the transactions in tbl_temp_pur
    public function getTempLogs($filter,$accred_no){
    	
		$logTbl = Log::select('tblName')
		->where('tblName', 'like', '%tbl_temp_pur_%')
		->orderBy('createdAt','ASC');
        if($filter['station_code'] != "0" ){
            $logTbl->where('tblName', 'like', '%tbl_release_pur_'.strtolower($filter['station_code']).'%');
        }
        else{
            $logTbl->where('tblName', 'like', '%tbl_release_pur_%');
        }
        $tempTbl = $logTbl->get();

		$logs = array();
		foreach($tempTbl as $tt){
			$stocks_tbl = substr_replace($tt->tblName, "tbl_stocks", 0, 12);
			$temp = DB::connection('warehouse')
			->table($tt->tblName.' as temp')
			->leftJoin($stocks_tbl.' as stocks','stocks.palletCode','temp.pallet_code')
			->leftJoin('rsis_seed_seed.seed_characteristics as seeds','seeds.id','stocks.seedVarietyId')
			->select('temp.accred_no','temp.status','temp.timestamp','temp.pallet_code','temp.browser','temp.device','temp.ip_env_address','stocks.seedVarietyId','seeds.variety')
			->where('temp.status','Variety Cancelled');
			if($filter['from'] != 0 && $filter['to'] != 0 ){
				$to = date('Y-m-d', strtotime($filter['to'] . ' +1 day'));
                $from = date('Y-m-d', strtotime($filter['from']));
                $temp->whereBetween('timestamp',[$from,$to]);
	        }
			if(Entrust::hasRole('seed_producer')){
				$temp->where('accred_no',$accred_no);
			}
			$data = $temp->get();

			foreach($data as $dt){
					$logs[] = array(
	                    'status' => $dt->status,
	                    'timestamp' =>$dt->timestamp,
	                    'browser' => $dt->browser,
	                    'device' => $dt->device,
	                    'ip_address' => $dt->ip_env_address,
	                    'variety' => $dt->variety,
	                    'pallet_code' => $dt->pallet_code,
	                    'accred_no' => $dt->accred_no
					);
				}
		}
        return $logs;
    }

    public function getUserLogAccess($filter){
        $logs = DB::table('activities_user as act_user')
        ->leftJoin('activities as act','act_user.activity_id','act.activity_id')
        ->leftJoin('users','act_user.user_id','users.user_id')
        ->leftJoin('affiliation_user as aff_user','aff_user.user_id','users.user_id')
        ->leftJoin('affiliations as aff','aff.affiliation_id','aff_user.affiliation_id')
        ->select('act_user.*','users.firstname','users.lastname','users.middlename','act.name as activity', 'users.user_id','aff.affiliation_id')
        ->orWhere(function ($query) {
            $query->where('act_user.activity_id',9)
                  ->orWhere('act_user.activity_id',10);
        });
        if($filter['from'] != 0 && $filter['to'] != 0 ){
			$to = date('Y-m-d', strtotime($filter['to'] . ' +1 day'));
			$from = date('Y-m-d', strtotime($filter['from']));
            $logs->whereBetween('timestamp',[$from,$to]);
        }
        if(Entrust::hasRole(['seed_producer','seed_inspector'])){
            $logs->where('users.user_id',Auth::id());
        }
        if(Entrust::hasRole(['admin']))
        {
        	$logs->orWhere(function ($query) {
	            $query->where('aff.affiliation_id',3)
	                  ->orWhere('aff.affiliation_id',4);
	        });

        }
        if(Entrust::can(['view_national_data','view_regional_data','view_provincial_data'])){
            if($filter['region_id'] != "0")
            {
                $logs->where('users.region',$filter['region_id']);
            }
            if($filter['province_id'] != "0")
            {
                $logs->where('users.province',$filter['province_id']);
            }
            if($filter['municipality_id'] != "0")
            {
                $logs->where('users.municipality',$filter['municipality_id']);
            }
        }
        return $logs->get();
    }

    public function getUserLogAction($filter){
    	$logs = DB::table('activities_user as act_user')
        ->leftJoin('activities as act','act_user.activity_id','act.activity_id')
        ->leftJoin('users','act_user.user_id','users.user_id')
        ->leftJoin('affiliation_user as aff_user','aff_user.user_id','users.user_id')
        ->leftJoin('affiliations as aff','aff.affiliation_id','aff_user.affiliation_id')
        ->select('act_user.*','users.firstname','users.lastname','users.middlename','act.name as activity', 'users.user_id','aff.affiliation_id')
        ->orWhere(function ($query) {
            $query->where('act_user.activity_id','!=',9)
                  ->where('act_user.activity_id','!=',10);
        });
        if($filter['from'] != 0 && $filter['to'] != 0 ){
			$to = date('Y-m-d', strtotime($filter['to'] . ' +1 day'));
			$from = date('Y-m-d', strtotime($filter['from']));
            $logs->whereBetween('timestamp',[$from,$to]);
        }
        if(Entrust::hasRole(['seed_producer','seed_inspector'])){
            $logs->where('users.user_id',Auth::id());
        }
        if(Entrust::hasRole(['admin']))
        {
        	$logs->orWhere(function ($query) {
	            $query->where('aff.affiliation_id',3)
	                  ->orWhere('aff.affiliation_id',4);
	        });

        }
        if(Entrust::can(['view_national_data','view_regional_data','view_provincial_data'])){
            if($filter['region_id'] != "0")
            {
                $logs->where('users.region',$filter['region_id']);
            }
            if($filter['province_id'] != "0")
            {
                $logs->where('users.province',$filter['province_id']);
            }
            if($filter['municipality_id'] != "0")
            {
                $logs->where('users.municipality',$filter['municipality_id']);
            }
        }
        return $logs->get();
    }
}
