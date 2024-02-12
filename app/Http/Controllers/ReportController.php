<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Entrust;
use Auth, DB, Hash, Excel;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function seedsaleExcel(Request $request){

    	$filter = array(
			'from' => $request->date_from,
			'to' => $request->date_to,
			'region_id' => $request->region_id,
			'station_code' => $request->station_code,
			'province_id' => $request->province_id,
			'municipality_id' => $request->municipality_id,
		);
		$logs = $this->getSeedLogs($filter);
		$data = array();
		$filename = "Seed Sale-".Carbon::now();
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

		Excel::create($filename, function($excel) use($data) {
                    $excel->sheet('Seed Sale', function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->download('csv');
    }

    public function logaccessExcel(Request $request){
    	$filter = array(
			'from' => $request->date_from,
			'to' => $request->date_to,
			'region_id' => $request->region_id,
			'province_id' => $request->province_id,
			'municipality_id' => $request->municipality_id,
		);
		$logs = $this->getUserLogAccess($filter);
		$data = array();
		$filename = "Log Access-".Carbon::now();
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
		Excel::create($filename, function($excel) use($data) {
                    $excel->sheet('Log Access', function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->download('csv');
    }
    public function logactionExcel(Request $request) {
    	$filter = array(
			'from' => $request->date_from,
			'to' => $request->date_to,
			'region_id' => $request->region_id,
			'province_id' => $request->province_id,
			'municipality_id' => $request->municipality_id,
		);
		$logs = $this->getUserLogAction($filter);
		$data = array();
		$filename = "Log Action-".Carbon::now();
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
		Excel::create($filename, function($excel) use($data) {
                    $excel->sheet('Log Action', function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->download('csv');
    }
}
