<?php

namespace App\Http\Controllers\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PhilRiceStation;
use App\XMLData;
use App\SGForm;
use App\ProductionPlans;
use App\PalletPlan;
use App\DataComplianceEmailReceiver;
use App\PhilRiceStationSerialNumbers;
use App\Helpers\DatabaseConnection;
use DB;
use PHPMailer\PHPMailer;

class DataComplianceController extends Controller
{
    public function index() {
        // CURRENT CROPPING SEM AND YEAR
        $monthToday = date('m');
        $monthDateToday = date('m-d');

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

        // Get id of philrice stations
        $stations = PhilRiceStation::select('name', 'philrice_station_id', 'serial_number', 'station_code')->orderBy('philrice_station_id', 'ASC')->get();

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

        $data = array();

        foreach ($stations as $station) {
            $philrice_station_id = $station->philrice_station_id;

            // Get serial numbers of stations
            $serials = PhilRiceStationSerialNumbers::select('serial_number')->where('philrice_station_id', '=', $philrice_station_id)->get();

            // PRODUCTION PLAN

            $production_plans = DB::connection('seed_production_planner')
                                    ->table('production_plans as plans')
                                    ->leftJoin('production_plots as production_plots', function($join) {
                                        $join->on('plans.production_plan_id', '=', 'production_plots.production_plan_id');
                                    })
                                    ->leftJoin('plots as plots', function($join) {
                                        $join->on('production_plots.plot_id', '=', 'plots.plot_id');
                                    })
                                    ->select('plans.production_plan_id', 'plots.area')
                                    ->where([
                                        ['plans.year', '=', $croppingYear],
                                        ['plans.sem', '=', $semester],
                                        ['plans.philrice_station_id', '=', $philrice_station_id],
                                        ['plans.is_finalized', '=', 1],
                                        ['plans.is_deleted', '=', 0],
                                        ['plans.variety', '!=', 'NSIC 2022 Rc 682GR2E'] // do not include GR variety
                                    ])
                                    ->get();

            $areaInputtedSP = 0;

            foreach ($production_plans as $production_plan) {
                $areaInputtedSP = $areaInputtedSP + ($production_plan->area / 1);
            }

            $production_area = DB::connection('seed_production_planner')
                                ->table('plots')
                                ->select(DB::raw('SUM(CAST(area as DECIMAL(9,2))) AS area'))
                                ->where([
                                    ['philrice_station_id', '=', $philrice_station_id],
                                    ['is_active', '=', 1]
                                ])
                                ->first();

            if ($production_area->area) {
                $production_area2 = $production_area->area;
            } else {
                $production_area2 = 0;
            }

            // GROWAPP

            // Get accredited area from SG API
            $accreditedArea = 0;

            foreach ($sgdata['seedgrower'] as $item) {
                if ($station->serial_number == $item['SerialNum']) {
                    $accreditedArea = $item['AccreArea'];
                }
            }

            // Get applied area from GrowApp table
            $areaApplied = 0;

            foreach ($serials as $serial) {
                $growApp = SGForm::select('areaplanted', 'dateplanted')
                                ->where([
                                    ['serial_number', '=', $serial->serial_number],
                                    ['is_test_data', '=',  0],
                                    ['variety', '!=', 'NSIC 2022 Rc 682GR2E'] // do not include GR variety
                                ])
                                ->get();

                if (!empty($growApp)) {
                    foreach ($growApp as $item2) {
                        $datePlanted = $item2->dateplanted;

                        $date_planted_year = date('Y', strtotime($datePlanted));
                        $date_planted_month = date('m', strtotime($datePlanted));
                        $date_planted_month_date = date('m-d', strtotime($datePlanted));

                        if ($date_planted_month_date >= date('m-d', strtotime('2022-09-16'))) {
                            if ($date_planted_month == '09' || $date_planted_month == '10' || $date_planted_month == '11' || $date_planted_month == '12') {
                                if ($semester == 1) {
                                    if (($croppingYear-1) == $date_planted_year) {
                                        $areaApplied = $areaApplied + ($item2->areaplanted / 1);
                                    }
                                }
                            }

                            //  else {
                            //     if ($semester == 1) {
                            //         if (($croppingYear-1) == $date_planted_year) {
                            //             $areaApplied = $areaApplied + ($item2->areaplanted / 1);
                            //         }
                            //     }
                            // }
                        }

                        if ($date_planted_month_date >= date('m-d', strtotime('2022-01-01'))) {
                            if ($date_planted_month == '01' || $date_planted_month == '02' || $date_planted_month == '03') {
                                if ($semester == 1) {
                                    if ($croppingYear == $date_planted_year) {
                                        $areaApplied = $areaApplied + ($item2->areaplanted / 1);
                                    }
                                }
                            }
                        }

                        if ($date_planted_month_date >= date('m-d', strtotime('2022-03-16')) && $date_planted_month_date <= date('m-d', strtotime('2022-09-15'))) {
                            if ($semester == 2) {
                                if ($croppingYear == $date_planted_year) {
                                    $areaApplied = $areaApplied + ($item2->areaplanted / 1);
                                }
                            }
                        }
                    }
                }

            }

            // WAREHOUSE AND SEED ORDERING
            $pallet_plan = DB::connection('warehouse')
                            ->table('warehouses as warehouses')
                            ->leftJoin('pallet_plans as pallet_plans', function($join) {
                                $join->on('warehouses.warehouse_id', '=', 'pallet_plans.warehouse_id');
                            })
                            ->select('pallet_plans.semester', 'pallet_plans.year')
                            ->where([
                                ['warehouses.station_id', '=', $philrice_station_id],
                                ['warehouses.is_active', '=', 1],
                                ['pallet_plans.status', '=', 1]
                            ])
                            ->first();

            if ($pallet_plan) {
                $stocks_activities_tbl_name = "tbl_sem" . $pallet_plan->semester . "_" . $pallet_plan->year . "_stock_activities";

                $release_pur_tbl_name = "tbl_sem" . $pallet_plan->semester . "_" . $pallet_plan->year . "_release_pur";

                $stocks_tbl_name = "tbl_sem" . $pallet_plan->semester . "_" . $pallet_plan->year . "_stocks";

                // Set database connection
                $connection = DatabaseConnection::setDBConnection(strtolower($station->station_code));

                // Get last activity timestamp
                $activity = $connection->table($stocks_activities_tbl_name)
                                        ->select('timestamp')
                                        ->orderBy('timestamp', 'desc')
                                        ->first();

                if ($activity) {
                    $lastUpdateWarehouse = date('F d, Y', strtotime($activity->timestamp));
                } else {
                    $lastUpdateWarehouse = "";
                }

                // Check if pallet plan has available stocks remaining
                $available_stocks = $connection->table($stocks_tbl_name)
                                    ->sum('available_stocks');

                $init_stocks = $connection->table($stocks_tbl_name)
                                        ->sum('init_stocks');

                if ($init_stocks > 0 && $available_stocks == 0) {
                    $lastUpdateWarehouse = $lastUpdateWarehouse . " (NO AVAILABLE SEEDS REMAINING)";
                }

                // Get last processed order or released order timestamp
                $processed = $connection->table($release_pur_tbl_name)
                                        ->select('timestamp')
                                        ->where('activity', '=', 'Processed Order')
                                        ->orWhere('activity', '=', 'Released Order')
                                        ->orderBy('timestamp', 'desc')
                                        ->first();

                if ($processed) {
                    $lastProcessedOrder = date('F d, Y', strtotime($processed->timestamp));
                } else {
                    $lastProcessedOrder = "";
                }
            } else {
                 $lastUpdateWarehouse = "";
                 $lastProcessedOrder = "";
            }



            $data[] = array(
                'station' => $station->name,
                'areaInputtedSP' => $areaInputtedSP,
                'productionArea' => $production_area2,
                'areaApplied' => $areaApplied,
                'accreditedArea' => $accreditedArea,
                'lastUpdateWarehouse' => $lastUpdateWarehouse,
                'lastProcessedOrder' => $lastProcessedOrder
            );
        }

        if ($semester == 1) {
            $semesterText = "SEM 1 (SEP 16 - MAR 15)";
        }

        if ($semester == 2) {
            $semesterText = "SEM 2 (MAR 16 - SEP 15)";
        }

        // Email receivers
        $emails = DataComplianceEmailReceiver::select('email', 'receive_type')->get();

        // SEND EMAIL
        // $email = "lemdoronio.24@gmail.com";
        $content = array();
        $content['data'] = $data;
        $content['semesterText'] = $semesterText;
        $content['croppingYear'] = $croppingYear;

        $res = $this->send_email($emails, $content);
        // $res = $this->send_email($email, $content);

        if ($res == "success") {
            echo "Success [".date('Y-m-d H:i:s')."] \n";
        } else {
            echo $res;
        }
    }

    public function send_email($emails, $content) {
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
            foreach ($emails as $email) {
                if ($email->receive_type == 1) {
                    $mail->addAddress($email->email); // Add a recipient
                } else if ($email->receive_type == 2) {
                    $mail->addCC($email->email); // Add CC
                } else if ($email->receive_type == 3) {
                    $mail->addBCC($email->email); // Add BCC
                }
            }
            // $mail->addAddress($emails); // Add a recipient
            // $mail->addAddress("lemdoronio.24@gmail.com");
            
            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'RSIS Data Compliance';
            $mail->Body    = $this->email_content($content);
            $mail->AltBody = 'Please enable HTML content to view this email.';

            $mail->send();
            return "success";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function email_content($content) {
        return view('email.data_compliance')->with($content);
    }
}
