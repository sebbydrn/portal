<?php

namespace App\Http\Controllers\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PhilRiceStation;
use App\XMLData;
use App\SGForm;

class GrowAppController extends Controller
{
    public function index() {
        $contacts = $this->contacts();

        // Get serial number of philrice stations
        $stations = PhilRiceStation::select('name', 'serial_number')->get();

        // Get SG API from database
        $xmldata = XMLData::select('xml')
                        ->where([
                            ['name', '=', 'APISGDataList']
                        ])
                        ->orderBy('timestamp', 'desc')
                        ->first();

        $sg_xml = $xmldata->xml;
        $sg_xml = simplexml_load_string($sg_xml);
        $sgdata = json_encode($sg_xml);
        $sgdata = json_decode($sgdata, TRUE);

        // Current cropping sem
        // $dateToday = date('Y-m-d');
        $monthToday = date('m');
        $monthDateToday = date('m-d');
        // $year = date('Y');
        // $nextYear = date('Y') + 1;
        // $lastYear = date('Y') - 1;

        if ($monthDateToday >= date('m-d', strtotime('2022-09-16'))) {
            if ($monthToday == '09' || $monthToday == '10' || $monthToday == '11' || $monthToday == '12') {
                $croppingYear = date('Y');
                $semester = 1;
            } else {
                $croppingYear = date('Y');
                $semester = 1;
            }
        }

        if ($monthDateToday >= date('m-d', strtotime('2022-01-01'))) {
            if ($monthToday == '01' || $monthToday == '02' || $monthToday == '03') {
                $croppingYear = date('Y');
                $semester = 1;
            }
        }

        if ($monthDateToday >= date('m-d', strtotime('2022-03-16')) && $monthDateToday <= date('m-d', strtotime('2022-09-15'))) {
            $croppingYear = date('Y');
            $semester = 2;
        }

        // if (date('Y-m-d' , strtotime($dateToday)) > ($year . "-09-16") && date('Y-m-d', strtotime($dateToday)) < ($nextYear . "-03-15")) {
        //     $semester = 1;
        // } else if (date('Y-m-d', strtotime($dateToday)) > ($year . "-03-16") && date('Y-m-d', strtotime($dateToday)) < ($year . "-09-15")) {
        //     $semester = 2;
        // }

        // if ($dateToday > "")

        $data = array();

        foreach ($stations as $station) {
            // Get accredited area from SG API
            $accreditedArea = 0;

            foreach ($sgdata['seedgrower'] as $item) {
                if ($station->serial_number == $item['SerialNum']) {
                    $accreditedArea = $item['AccreArea'];
                }
            }

            // Get applied area from GrowApp table
            $growApp = SGForm::select('areaplanted', 'dateplanted')
                            ->where('serial_number', '=', $station->serial_number)
                            ->get();

            $areaPlanted = 0;

            if (!empty($growApp)) {
                foreach ($growApp as $item2) {
                    $datePlanted = $item2->dateplanted;

                    $date_planted_year = date('Y', strtotime($datePlanted));
                    $date_planted_month = date('m', strtotime($datePlanted));
                    $date_planted_month_date = date('m-d', strtotime($datePlanted));

                    if ($date_planted_month_date >= date('m-d', strtotime('2022-09-16'))) {
                        if ($date_planted_month == '09' || $date_planted_month == '10' || $date_planted_month == '11' || $date_planted_month == '12') {
                            if ($semester == 1) {
                                $areaPlanted = $areaPlanted + ($item2->areaplanted / 1);
                            }
                        } else {
                            if ($semester == 1) {
                                $areaPlanted = $areaPlanted + ($item2->areaplanted / 1);
                            }
                        }
                    }

                    if ($date_planted_month_date >= date('m-d', strtotime('2022-01-01'))) {
                        if ($date_planted_month == '01' || $date_planted_month == '02' || $date_planted_month == '03') {
                            if ($semester == 1) {
                                $areaPlanted = $areaPlanted + ($item2->areaplanted / 1);
                            }
                        }
                    }

                    if ($date_planted_month_date >= date('m-d', strtotime('2022-03-16')) && $date_planted_month_date <= date('m-d', strtotime('2022-09-15'))) {
                        if ($semester == 2) {
                            $areaPlanted = $areaPlanted + ($item2->areaplanted / 1);
                        }
                    }

                    // if ($semester == 1) {
                    //     if (date('Y-m-d' , strtotime($datePlanted)) > $year . "-09-16" && date('Y-m-d', strtotime($datePlanted)) < $nextYear . "-03-15") {
                    //         $areaPlanted = $areaPlanted + ($item2->areaplanted / 1);
                    //     }
                    // } else if ($semester == 2) {
                    //     if (date('Y-m-d', strtotime($datePlanted)) > $year . "-03-16" && date('Y-m-d', strtotime($datePlanted)) < $year . "-09-15") {
                    //         $areaPlanted = $areaPlanted + ($item2->areaplanted / 1);
                    //     }
                    // }
                    
                }
            }

            if ($semester == 1) {
                $semesterText = "SEM 1 (SEP 16 - MAR 15)";
            }

            if ($semester == 2) {
                $semesterText = "SEM 2 (MAR 16 - SEP 15)";
            }

            $data[] = array(
                'station' => $station->name,
                'areaPlanted' => $areaPlanted,
                'accreditedArea' => $accreditedArea,
                'percentCompleted' => round((($areaPlanted / $accreditedArea) * 100))
            );
        }

        // echo json_encode($data);

        return view('monitoring.growApp')->with(compact('contacts', 'data', 'croppingYear', 'semesterText'));


    }
}
