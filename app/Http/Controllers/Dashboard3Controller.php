<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seeds;
use App\SGForm;
use App\XMLData;
use App\Sales\FSSalesStation;
use App\Sales\FSSalesVariety;
use App\Sales\RSSalesStation;
use App\Sales\RSSalesVariety;
use App\Sales\SeedSales;
use App\Sales\VarietiesSold;
use App\ProductionEstimate;
use App\CSProductionData;
use App\RSAreaAppliedRegion;
use App\RSVarietiesApplied;
use App\RSVarietiesAppliedData;
use App\RSAreaInspPassed;
use App\RSAreaInspPassedData;
use App\RSVarietiesAppliedRegion;
use App\RSVarietiesAppliedRegionData;
use App\RSAreaPerProgram;
use App\RSVarietiesAppliedProgram;
use App\RSVarietiesAppliedProgramData;
use App\RSAreaAppliedCoop;
use App\RSAreaAppliedCoopData;
use App\CSEstimatedYield;
use App\CSEstimatedYieldData;
use App\CSEstimatedYieldDataVarieties;
use App\CSEstimatedYieldRegion;
use App\Helpers\DatabaseConnection;
use DB;

class Dashboard3Controller extends Controller
{
    public function simulation() {
        $contacts = $this->contacts();

        $serialNum1 = "R-0000000899";
        $serialNum2 = "R-0000000900";
        $serialNum3 = "R-0000000903";

        // Cleaned weight percentage
        $cleaned_multiplier = 100 - 15;
        $cleaned_percentage = $cleaned_multiplier / 100; // Convert to decimal

        $data = array();

        // PURCHASED SEEDS
        $data['purchased_fresh'] = 0;
        $data['purchased_dried'] = 0;
        $data['purchased_cleaned'] = 0;

        // Set database connection
        $connection = DatabaseConnection::setDBConnection('ces');
        $tableReleased = "tbl_sem2_2021_release_pur";
        $tableStocks = "tbl_sem2_2021_stocks";

        $purchased = $connection->table($tableReleased)
                        ->select('pallet_id', 'lot_no', 'quantity', 'area')
                        ->where([
                            ['activity', '=', 'Released Order']
                        ])
                        ->where('serialNum', '=', $serialNum1)
                        ->orWhere('serialNum', '=', $serialNum2)
                        ->orWhere('serialNum', '=', $serialNum3)
                        ->get();

        foreach ($purchased as $item) {
            $stock = $connection->table($tableStocks)
                        ->select('variety')
                        ->where([
                            ['pallet_id', '=', $item->pallet_id],
                            ['lot_no', '=', $item->lot_no]
                        ])
                        ->first();

            $variety = Seeds::select('ave_yld', 'variety')
                            ->where('variety', 'LIKE', '%'.$stock->variety.'')
                            ->where('variety_name', 'NOT LIKE', '%DWSR%')
                            ->first();

            if ($variety) {
                // Fresh weight
                $purchased_fresh = ($item->quantity / 40) * $variety->ave_yld;
                $purchased_fresh = round($purchased_fresh);

                // Dried weight
                $purchased_dried = $purchased_fresh * ((100 - 21.1) / 88);
                $purchased_dried = round($purchased_dried);

                // Cleaned weight
                $purchased_cleaned = $purchased_dried * $cleaned_percentage;
                $purchased_cleaned = round($purchased_cleaned);

                // Add to total weights
                $data['purchased_fresh'] = $data['purchased_fresh'] + ($purchased_fresh * 1000);
                $data['purchased_dried'] = $data['purchased_dried'] + ($purchased_dried * 1000);
                $data['purchased_cleaned'] = $data['purchased_cleaned'] + ($purchased_cleaned * 1000);
            }
        }

        // APPLIED FOR SEED CERTIFICATION
        $data['applied_fresh'] = 0;
        $data['applied_dried'] = 0;
        $data['applied_cleaned'] = 0;

        $applied = SGForm::select('areaplanted', 'variety')
                        ->where([
                            ['is_test_data', '=', 0]
                        ])
                        ->where('serial_number', '=', $serialNum1)
                        ->orWhere('serial_number', '=', $serialNum2)
                        ->orWhere('serial_number', '=', $serialNum3)
                        ->get();

        foreach ($applied as $item) {
            $variety = Seeds::select('ave_yld', 'variety')
                            ->where('variety', 'LIKE', '%'.$item->variety.'')
                            ->where('variety_name', 'NOT LIKE', '%DWSR%')
                            ->first();

            if ($variety) {
                // Fresh weight
                $applied_fresh = $item->areaplanted * $variety->ave_yld;
                $applied_fresh = round($applied_fresh);

                // Dried weight
                $applied_dried = $applied_fresh * ((100 - 21.1) / 88);
                $applied_dried = round($applied_dried);

                // Cleaned weight
                $applied_cleaned = $applied_dried * $cleaned_percentage;
                $applied_cleaned = round($applied_cleaned);

                // Add to total weights
                $data['applied_fresh'] = $data['applied_fresh'] + ($applied_fresh * 1000);
                $data['applied_dried'] = $data['applied_dried'] + ($applied_dried * 1000);
                $data['applied_cleaned'] = $data['applied_cleaned'] + ($applied_cleaned * 1000);
            }
        }

        // PRELIM INSPECTION
        $data['prelim_fresh'] = 0;
        $data['prelim_dried'] = 0;
        $data['prelim_cleaned'] = 0;

        $xmldata = XMLData::select('xml')
                        ->where([
                            ['name', '=', 'APISPIDataList']
                        ])
                        ->orderBy('timestamp', 'desc')
                        ->first();

        $prelim_xml = $xmldata->xml;
        $prelim_xml = simplexml_load_string($prelim_xml);
        $prelimdata = json_encode($prelim_xml);
        $prelimdata = json_decode($prelimdata, TRUE);

        foreach ($prelimdata['seedpreinspection'] as $item) {
            if ($item['SerialNum'] == $serialNum1 || $item['SerialNum'] == $serialNum2 || $item['SerialNum'] == $serialNum3) {
                // Check if inspection status is "Pass"
                if ($item['InspStatus'] == "Pass") {
                    // Search in grow app data if trackingID exists
                    $checkTracking = SGForm::select('trackingid', 'variety')
                                        ->where([
                                            ['trackingid', '=', $item['TrackingID']]
                                        ])
                                        ->first();

                    if (!$checkTracking == NULL) {
                        $variety = Seeds::select('ave_yld', 'variety')
                                        ->where('variety', 'LIKE', '%'.$checkTracking->variety.'')
                                        ->where('variety_name', 'NOT LIKE', '%DWSR%')
                                        ->first();

                        if ($variety) {
                            // Fresh weight
                            $prelim_fresh = $item['AreaPlanted'] * $variety->ave_yld;
                            $prelim_fresh = round($prelim_fresh);

                            // Dried weight
                            $prelim_dried = $prelim_fresh * ((100 - 21.1) / 88);
                            $prelim_dried = round($prelim_dried);

                            // Cleaned weight
                            $prelim_cleaned = $prelim_dried * $cleaned_percentage;
                            $prelim_cleaned = round($prelim_cleaned);

                            // Add to total weights
                            $data['prelim_fresh'] = $data['prelim_fresh'] + ($prelim_fresh * 1000);
                            $data['prelim_dried'] = $data['prelim_dried'] + ($prelim_dried * 1000);
                            $data['prelim_cleaned'] = $data['prelim_cleaned'] + ($prelim_cleaned * 1000);
                        }
                    }    
                }
            }
        }

        // FINAL INSPECTION
        $data['final_fresh'] = 0;
        $data['final_dried'] = 0;
        $data['final_cleaned'] = 0;

        $xmldata = XMLData::select('xml')
                        ->where([
                            ['name', '=', 'APISPFIDataList']
                        ])
                        ->orderBy('timestamp', 'desc')
                        ->first();

        $final_xml = $xmldata->xml;
        $final_xml = simplexml_load_string($final_xml);
        $finaldata = json_encode($final_xml);
        $finaldata = json_decode($finaldata, TRUE);

        // echo json_encode($finaldata);

        foreach ($finaldata['seedfinalinspection'] as $item) {
            if (!$item['TrackingID'] == NULL) {
                if ($item['SerialNum'] == $serialNum1 || $item['SerialNum'] == $serialNum2 || $item['SerialNum'] == $serialNum3) {
                    // Check if inspection status is "Passed"
                    if ($item['InspStatus'] == "Passed") {
                        // Search in grow app data if trackingID exists
                        $checkTracking = SGForm::select('trackingid', 'variety')
                                            ->where([
                                                ['trackingid', '=', $item['TrackingID']]
                                            ])
                                            ->first();

                        if (!$checkTracking == NULL) {
                            $variety = Seeds::select('ave_yld', 'variety')
                                            ->where('variety', 'LIKE', '%'.$checkTracking->variety.'')
                                            ->where('variety_name', 'NOT LIKE', '%DWSR%')
                                            ->first();

                            if ($variety) {
                                // Fresh weight
                                $final_fresh = $item['AreaPlanted'] * $variety->ave_yld;
                                $final_fresh = round($final_fresh);

                                // Dried weight
                                $final_dried = $final_fresh * ((100 - 21.1) / 88);
                                $final_dried = round($final_dried);

                                // Cleaned weight
                                $final_cleaned = $final_dried * $cleaned_percentage;
                                $final_cleaned = round($final_cleaned);

                                // Add to total weights
                                $data['final_fresh'] = $data['final_fresh'] + ($final_fresh * 1000);
                                $data['final_dried'] = $data['final_dried'] + ($final_dried * 1000);
                                $data['final_cleaned'] = $data['final_cleaned'] + ($final_cleaned * 1000);
                            }
                        }  
                    } 
                }  
            }
        }

        // SAMPLED
        $data['sampled_cleaned'] = 0;

        // CERTIFIED
        $data['certified_cleaned'] = 0;

        $xmldata = XMLData::select('xml')
                        ->where([
                            ['name', '=', 'APISEEDSAMPLINGDataList']
                        ])
                        ->orderBy('timestamp', 'desc')
                        ->first();

        $sampling_xml = $xmldata->xml;
        $sampling_xml = simplexml_load_string($sampling_xml);
        $samplingdata = json_encode($sampling_xml);
        $samplingdata = json_decode($samplingdata, TRUE);

        foreach ($samplingdata['seedsampling'] as $item) {
            if (!$item['GrowTrackingNum'] == NULL) {
                if ($item['SGSerialNum'] == $serialNum1 || $item['SGSerialNum'] == $serialNum2 || $item['SGSerialNum'] == $serialNum3) {
                    // Check if laboratory status is "Under Test" or "Completed"
                    if ($item['LaboratoryStatus'] == "Under Test" || $item['LaboratoryStatus'] == "Completed") {
                        // Search in grow app data if trackingID exists
                        $checkTracking = SGForm::select('trackingid', 'variety')
                                            ->where([
                                                ['trackingid', '=', $item['GrowTrackingNum']]
                                            ])
                                            ->first();

                        if (!$checkTracking == NULL) {
                            // Cleaned weight
                            $final_cleaned = $item['BagsRecived'] * $item['BagWeight'];
                            $final_cleaned = round($final_cleaned);

                            // Add to total weights
                            $data['sampled_cleaned'] = $data['sampled_cleaned'] + $final_cleaned;
                        }  
                    }

                    // Check if laboratory status is "Completed" and laboratory result is "Passed"
                    if ($item['LaboratoryStatus'] == "Completed" && $item['LaboratoryResult'] == "Passed") {
                        // Search in grow app data if trackingID exists
                        $checkTracking = SGForm::select('trackingid', 'variety')
                                            ->where([
                                                ['trackingid', '=', $item['GrowTrackingNum']]
                                            ])
                                            ->first();

                        if (!$checkTracking == NULL) {
                            // Cleaned weight
                            $final_cleaned = $item['BagsRecived'] * $item['BagWeight'];
                            $final_cleaned = round($final_cleaned);

                            // Add to total weights
                            $data['certified_cleaned'] = $data['certified_cleaned'] + $final_cleaned;
                        }
                    }
                }
            }
        }   

        // echo json_encode($data);

        return view('dashboard3.simulation')->with(compact('contacts', 'data'));
    }

    public function index() {
        $contacts = $this->contacts();

        // // get years in processed data table
        // $years = ProductionEstimate::select('year')->orderBy('year', 'DESC')->groupBy('year')->get();

        // $current_year_sem = $this->current_year_sem();
        // $year = $current_year_sem['year'];
        // $sem = $current_year_sem['sem'];

        // $production_estimates = ProductionEstimate::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();

        // if ($production_estimates->sem == 1) {
        //     $semesterText = $production_estimates->year . " SEM 1 (SEP 16 - MAR 15)";
        // }

        // if ($production_estimates->sem == 2) {
        //     $semesterText = $production_estimates->year . " SEM 2 (MAR 16 - SEP 15)";
        // }

        // $cs_production_data = CSProductionData::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();;

        // $rs_area_applied_region = RSAreaAppliedRegion::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();;

        // $rs_varieties_applied = RSVarietiesApplied::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();;

        // $rs_varieties_applied_data = RSVarietiesAppliedData::where('rs_varieties_applied_id', '=', $rs_varieties_applied->rs_varieties_applied_id)->orderBy('area', 'desc')->get();

        // $rs_varieties_applied_data_varieties = array();
        // $rs_varieties_applied_data_area = array();

        // foreach ($rs_varieties_applied_data as $item) {
        //     array_push($rs_varieties_applied_data_varieties, $item->variety);
        //     array_push($rs_varieties_applied_data_area, $item->area);
        // }

        // $rs_varieties_applied_data_arr = array(
        //     'rs_varieties_applied_data_varieties' => $rs_varieties_applied_data_varieties,
        //     'rs_varieties_applied_data_area' => $rs_varieties_applied_data_area
        // );

        // $rs_area_insp_passed = RSAreaInspPassed::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();

        // $rs_area_insp_passed_data = array();

        // $rs_area_insp_passed_data['prelim'] = RSAreaInspPassedData::where([
        //                                                     ['rs_area_insp_passed_id', '=', $rs_area_insp_passed->rs_area_insp_passed_id],
        //                                                     ['inspection', '=', 1]
        //                                                 ])
        //                                                 ->first();

        // $rs_area_insp_passed_data['final'] = RSAreaInspPassedData::where([
        //                                                     ['rs_area_insp_passed_id', '=', $rs_area_insp_passed->rs_area_insp_passed_id],
        //                                                     ['inspection', '=', 2]
        //                                                 ])
        //                                                 ->first();

        // $rs_varieties_applied_region = RSVarietiesAppliedRegion::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();

        // $rs_varieties_applied_region_data = RSVarietiesAppliedRegionData::where('rs_variety_applied_region_id', '=', $rs_varieties_applied_region->rs_variety_applied_region_id)->orderBy('variety', 'asc')->get();

        // $rs_area_per_program = RSAreaPerProgram::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();

        // $rs_varieties_applied_programs = RSVarietiesAppliedProgram::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();

        // $rs_varieties_applied_program_data = RSVarietiesAppliedProgramData::where('rs_varieties_applied_program_id', '=', $rs_varieties_applied_programs->rs_varieties_applied_programs_id)->orderBy('variety', 'asc')->get();

        // $rs_area_applied_coop = RSAreaAppliedCoop::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();

        // $rs_area_applied_coop_data = RSAreaAppliedCoopData::where('rs_area_applied_coop_id', '=', $rs_area_applied_coop->rs_area_applied_coop_id)->orderBy('total', 'desc')->take(10)->get();

        // $rs_area_applied_coop_data_all = RSAreaAppliedCoopData::where('rs_area_applied_coop_id', '=', $rs_area_applied_coop->rs_area_applied_coop_id)->orderBy('cooperative', 'asc')->get();

        // $cs_estimated_yield = CSEstimatedYield::where([
        //     ['year', '=', $year],
        //     ['sem', '=', $sem]
        // ])
        // ->orderBy('timestamp', 'desc')
        // ->first();

        // if ($cs_estimated_yield) {
        //     $cs_estimated_yield_data = CSEstimatedYieldData::where('cs_estimated_yield_id', '=', $cs_estimated_yield->cs_estimated_yield_id)
        //                                                     ->orderBy('year', 'asc')
        //                                                     ->orderBy('month', 'asc')
        //                                                     ->get();

        //     $cs_estimated_yield_data_varieties = array();
        //     $cs_estimated_yield_months = array();
        //     $cs_estimated_yield_totals = array();
        //     $cs_estimated_yield_varieties = array();
        //     $cs_estimated_yield_varieties_list = array();

        //     foreach ($cs_estimated_yield_data as $item) {
        //         $query = CSEstimatedYieldDataVarieties::where('cs_estimated_yield_data_id', '=', $item->cs_estimated_yield_data_id)->orderBy('variety', 'asc')->get();

        //         array_push($cs_estimated_yield_months, $this->month_name($item->month) . " " . $item->year);
        //         array_push($cs_estimated_yield_totals, floatval($item->total_estimated_yield));

        //         foreach ($query as $item2) {
        //             $cs_estimated_yield_varieties[$this->month_name($item->month) . " " . $item->year][$item2->variety] = $item2->estimated_yield;

        //             if (!in_array($item2->variety, $cs_estimated_yield_varieties_list)) {
        //                 array_push($cs_estimated_yield_varieties_list, $item2->variety);
        //             }
        //         }
        //     }

        //     $cs_estimated_yield_region = CSEstimatedYieldRegion::where('cs_estimated_yield_id', '=', $cs_estimated_yield->cs_estimated_yield_id)->get();

        //     $cs_estimated_yield_region_months = array();
        //     $cs_estimated_yield_region_data = array(
        //         'reg_1' => array('name' => 'REGION I', 'data' => array()),
        //         'reg_2' => array('name' => 'REGION II', 'data' => array()),
        //         'reg_3' => array('name' => 'REGION III', 'data' => array()),
        //         'reg_4a' => array('name' => 'REGION IV-A', 'data' => array()),
        //         'mimaropa' => array('name' => 'MIMAROPA', 'data' => array()),
        //         'reg_5' => array('name' => 'REGION V', 'data' => array()),
        //         'reg_6' => array('name' => 'REGION VI', 'data' => array()),
        //         'reg_7' => array('name' => 'REGION VII', 'data' => array()),
        //         'reg_8' => array('name' => 'REGION VIII', 'data' => array()),
        //         'reg_9' => array('name' => 'REGION IX', 'data' => array()),
        //         'reg_10' => array('name' => 'REGION X', 'data' => array()),
        //         'reg_11' => array('name' => 'REGION XI', 'data' => array()),
        //         'reg_12' => array('name' => 'REGION XII', 'data' => array()),
        //         'reg_13' => array('name' => 'REGION XIII', 'data' => array()),
        //         'car' => array('name' => 'CAR', 'data' => array()),
        //         'ncr' => array('name' => 'NCR', 'data' => array()),
        //         'barmm' => array('name' => 'BARMM', 'data' => array())
        //     );

        //     foreach ($cs_estimated_yield_region as $item) {
        //         array_push($cs_estimated_yield_region_months, $this->month_name($item->month) . " " . $item->year);
        //         array_push($cs_estimated_yield_region_data['reg_1']['data'], floatval($item->reg_1));
        //         array_push($cs_estimated_yield_region_data['reg_2']['data'], floatval($item->reg_2));
        //         array_push($cs_estimated_yield_region_data['reg_3']['data'], floatval($item->reg_3));
        //         array_push($cs_estimated_yield_region_data['reg_4a']['data'], floatval($item->reg_4a));
        //         array_push($cs_estimated_yield_region_data['mimaropa']['data'], floatval($item->mimaropa));
        //         array_push($cs_estimated_yield_region_data['reg_5']['data'], floatval($item->reg_5));
        //         array_push($cs_estimated_yield_region_data['reg_6']['data'], floatval($item->reg_6));
        //         array_push($cs_estimated_yield_region_data['reg_7']['data'], floatval($item->reg_7));
        //         array_push($cs_estimated_yield_region_data['reg_8']['data'], floatval($item->reg_8));
        //         array_push($cs_estimated_yield_region_data['reg_9']['data'], floatval($item->reg_9));
        //         array_push($cs_estimated_yield_region_data['reg_10']['data'], floatval($item->reg_10));
        //         array_push($cs_estimated_yield_region_data['reg_11']['data'], floatval($item->reg_11));
        //         array_push($cs_estimated_yield_region_data['reg_12']['data'], floatval($item->reg_12));
        //         array_push($cs_estimated_yield_region_data['reg_13']['data'], floatval($item->reg_13));
        //         array_push($cs_estimated_yield_region_data['car']['data'], floatval($item->car));
        //         array_push($cs_estimated_yield_region_data['ncr']['data'], floatval($item->ncr));
        //         array_push($cs_estimated_yield_region_data['barmm']['data'], floatval($item->barmm));
        //     }
        // }

        // // dd($cs_estimated_yield_region_data);
        // $production_estimates = [];
        // $semesterText = "";
        // $cs_production_data = [];
        // $rs_area_applied_region = [];
        // $rs_varieties_applied = [];
        // $rs_varieties_applied_data_arr = [];
        // $rs_area_insp_passed = [];
        // $rs_area_insp_passed_data = [];
        // $rs_varieties_applied_region = [];
        // $rs_varieties_applied_region_data = [];
        // $rs_area_per_program = [];
        // $rs_varieties_applied_programs = [];
        // $rs_varieties_applied_program_data = [];
        // $rs_area_applied_coop = [];
        // $rs_area_applied_coop_data = [];
        // $rs_area_applied_coop_data_all = [];
        // $cs_estimated_yield = [];
        // $cs_estimated_yield_data = [];
        // $cs_estimated_yield_months = [];
        // $cs_estimated_yield_totals = [];
        // $cs_estimated_yield_varieties = [];
        // $cs_estimated_yield_varieties_list = [];
        // $cs_estimated_yield_region_months = [];
        // $cs_estimated_yield_region_data = [];
        // $years = [];

        // get total area from plots table
        $total_area = DB::connection('seed_production_planner')
        ->table('plots')
        ->where('is_active', '=', 1)
        ->sum(DB::raw('CAST(area AS FLOAT)'));

        // get total number of farmers
        $total_farmers = DB::connection('seed_production_planner')->table('farmers')->where('is_active', 1)->count();

        // count the number of varieties planted
        $total_varieties = DB::connection('seed_production_planner')->table('production_plans')->select('variety')->distinct()->count();

        // count the total quantity of distributed seeds
        $total_distributed_seeds_inbred = DB::connection('seed_production_planner')->table('seed_distribution_list')->where('seed_type', 'Inbred')->sum(DB::raw('CAST(quantity AS FLOAT)'));
        $total_distributed_seeds_hybrid = DB::connection('seed_production_planner')->table('seed_distribution_list')->where('seed_type', 'Hybrid')->sum(DB::raw('CAST(quantity AS FLOAT)'));

        // count the total quantity of fertilizers distributed
        $total_distributed_fertilizers = DB::connection('seed_production_planner')->table('fertilizer_distribution_list')->sum(DB::raw('CAST(quantity AS FLOAT)'));


        

        return view('dashboard3.index')
                ->with(compact('contacts', 'total_area', 'total_farmers', 'total_varieties', 'total_distributed_seeds_inbred', 'total_distributed_seeds_hybrid', 'total_distributed_fertilizers'));
    }

    public function filter($year, $sem) {
        $contacts = $this->contacts();

        // get years in processed data table
        $years = ProductionEstimate::select('year')->orderBy('year', 'DESC')->groupBy('year')->get();

        $production_estimates = ProductionEstimate::where([
            ['year', '=', $year],
            ['sem', '=', $sem]
        ])
        ->orderBy('timestamp', 'desc')
        ->first();

        if ($production_estimates == null) {
            return view('dashboard3.no_data')->with(compact('contacts', 'years'));
        } else {        
            if ($sem == 1) {
                $semesterText = $year . " SEM 1 (SEP 16 - MAR 15)";
            }

            if ($sem == 2) {
                $semesterText = $year . " SEM 2 (MAR 16 - SEP 15)";
            }

            $cs_production_data = CSProductionData::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            $rs_area_applied_region = RSAreaAppliedRegion::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            $rs_varieties_applied = RSVarietiesApplied::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            $rs_varieties_applied_data_varieties = array();
            $rs_varieties_applied_data_area = array();

            if ($rs_varieties_applied) {
                $rs_varieties_applied_data = RSVarietiesAppliedData::where('rs_varieties_applied_id', '=', $rs_varieties_applied->rs_varieties_applied_id)
                ->orderBy('area', 'desc')->get();
                
                foreach ($rs_varieties_applied_data as $item) {
                    array_push($rs_varieties_applied_data_varieties, $item->variety);
                    array_push($rs_varieties_applied_data_area, $item->area);
                }
            }

            $rs_varieties_applied_data_arr = array(
                'rs_varieties_applied_data_varieties' => $rs_varieties_applied_data_varieties,
                'rs_varieties_applied_data_area' => $rs_varieties_applied_data_area
            );

            $rs_area_insp_passed = RSAreaInspPassed::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            $rs_area_insp_passed_data = array();

            if ($rs_area_insp_passed) {
                $rs_area_insp_passed_data['prelim'] = RSAreaInspPassedData::where([
                                                                ['rs_area_insp_passed_id', '=', $rs_area_insp_passed->rs_area_insp_passed_id],
                                                                ['inspection', '=', 1]
                                                            ])
                                                            ->first();

                $rs_area_insp_passed_data['final'] = RSAreaInspPassedData::where([
                                                                ['rs_area_insp_passed_id', '=', $rs_area_insp_passed->rs_area_insp_passed_id],
                                                                ['inspection', '=', 2]
                                                            ])
                                                            ->first();
            }

            $rs_varieties_applied_region = RSVarietiesAppliedRegion::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            if ($rs_varieties_applied_region) {
                $rs_varieties_applied_region_data = RSVarietiesAppliedRegionData::where('rs_variety_applied_region_id', '=', $rs_varieties_applied_region->rs_variety_applied_region_id)->orderBy('variety', 'asc')->get();
            }

            $rs_area_per_program = RSAreaPerProgram::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            $rs_varieties_applied_programs = RSVarietiesAppliedProgram::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            if ($rs_varieties_applied_programs) {
                $rs_varieties_applied_program_data = RSVarietiesAppliedProgramData::where('rs_varieties_applied_program_id', '=', $rs_varieties_applied_programs->rs_varieties_applied_programs_id)->orderBy('variety', 'asc')->get();
            }

            $rs_area_applied_coop = RSAreaAppliedCoop::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            if ($rs_area_applied_coop) {
                $rs_area_applied_coop_data = RSAreaAppliedCoopData::where('rs_area_applied_coop_id', '=', $rs_area_applied_coop->rs_area_applied_coop_id)->orderBy('total', 'desc')->take(10)->get();

                $rs_area_applied_coop_data_all = RSAreaAppliedCoopData::where('rs_area_applied_coop_id', '=', $rs_area_applied_coop->rs_area_applied_coop_id)->orderBy('cooperative', 'asc')->get();
            }

            $cs_estimated_yield = CSEstimatedYield::where([
                ['year', '=', $year],
                ['sem', '=', $sem]
            ])
            ->orderBy('timestamp', 'desc')
            ->first();

            if ($cs_estimated_yield) {
                $cs_estimated_yield_data = CSEstimatedYieldData::where('cs_estimated_yield_id', '=', $cs_estimated_yield->cs_estimated_yield_id)
                                                            ->orderBy('year', 'asc')
                                                            ->orderBy('month', 'asc')
                                                            ->get();

                $cs_estimated_yield_data_varieties = array();
                $cs_estimated_yield_months = array();
                $cs_estimated_yield_totals = array();
                $cs_estimated_yield_varieties = array();
                $cs_estimated_yield_varieties_list = array();

                foreach ($cs_estimated_yield_data as $item) {
                    $query = CSEstimatedYieldDataVarieties::where('cs_estimated_yield_data_id', '=', $item->cs_estimated_yield_data_id)->orderBy('variety', 'asc')->get();

                    array_push($cs_estimated_yield_months, $this->month_name($item->month) . " " . $item->year);
                    array_push($cs_estimated_yield_totals, floatval($item->total_estimated_yield));

                    foreach ($query as $item2) {
                        $cs_estimated_yield_varieties[$this->month_name($item->month) . " " . $item->year][$item2->variety] = $item2->estimated_yield;

                        if (!in_array($item2->variety, $cs_estimated_yield_varieties_list)) {
                            array_push($cs_estimated_yield_varieties_list, $item2->variety);
                        }
                    }
                }

                $cs_estimated_yield_region = CSEstimatedYieldRegion::where('cs_estimated_yield_id', '=', $cs_estimated_yield->cs_estimated_yield_id)->get();

                $cs_estimated_yield_region_months = array();
                $cs_estimated_yield_region_data = array(
                    'reg_1' => array('name' => 'REGION I', 'data' => array()),
                    'reg_2' => array('name' => 'REGION II', 'data' => array()),
                    'reg_3' => array('name' => 'REGION III', 'data' => array()),
                    'reg_4a' => array('name' => 'REGION IV-A', 'data' => array()),
                    'mimaropa' => array('name' => 'MIMAROPA', 'data' => array()),
                    'reg_5' => array('name' => 'REGION V', 'data' => array()),
                    'reg_6' => array('name' => 'REGION VI', 'data' => array()),
                    'reg_7' => array('name' => 'REGION VII', 'data' => array()),
                    'reg_8' => array('name' => 'REGION VIII', 'data' => array()),
                    'reg_9' => array('name' => 'REGION IX', 'data' => array()),
                    'reg_10' => array('name' => 'REGION X', 'data' => array()),
                    'reg_11' => array('name' => 'REGION XI', 'data' => array()),
                    'reg_12' => array('name' => 'REGION XII', 'data' => array()),
                    'reg_13' => array('name' => 'REGION XIII', 'data' => array()),
                    'car' => array('name' => 'CAR', 'data' => array()),
                    'ncr' => array('name' => 'NCR', 'data' => array()),
                    'barmm' => array('name' => 'BARMM', 'data' => array())
                );

                foreach ($cs_estimated_yield_region as $item) {
                    array_push($cs_estimated_yield_region_months, $this->month_name($item->month) . " " . $item->year);
                    array_push($cs_estimated_yield_region_data['reg_1']['data'], floatval($item->reg_1));
                    array_push($cs_estimated_yield_region_data['reg_2']['data'], floatval($item->reg_2));
                    array_push($cs_estimated_yield_region_data['reg_3']['data'], floatval($item->reg_3));
                    array_push($cs_estimated_yield_region_data['reg_4a']['data'], floatval($item->reg_4a));
                    array_push($cs_estimated_yield_region_data['mimaropa']['data'], floatval($item->mimaropa));
                    array_push($cs_estimated_yield_region_data['reg_5']['data'], floatval($item->reg_5));
                    array_push($cs_estimated_yield_region_data['reg_6']['data'], floatval($item->reg_6));
                    array_push($cs_estimated_yield_region_data['reg_7']['data'], floatval($item->reg_7));
                    array_push($cs_estimated_yield_region_data['reg_8']['data'], floatval($item->reg_8));
                    array_push($cs_estimated_yield_region_data['reg_9']['data'], floatval($item->reg_9));
                    array_push($cs_estimated_yield_region_data['reg_10']['data'], floatval($item->reg_10));
                    array_push($cs_estimated_yield_region_data['reg_11']['data'], floatval($item->reg_11));
                    array_push($cs_estimated_yield_region_data['reg_12']['data'], floatval($item->reg_12));
                    array_push($cs_estimated_yield_region_data['reg_13']['data'], floatval($item->reg_13));
                    array_push($cs_estimated_yield_region_data['car']['data'], floatval($item->car));
                    array_push($cs_estimated_yield_region_data['ncr']['data'], floatval($item->ncr));
                    array_push($cs_estimated_yield_region_data['barmm']['data'], floatval($item->barmm));
                }
            }

            return view('dashboard3.index')
                    ->with(compact('contacts', 'production_estimates', 'semesterText', 'cs_production_data', 'rs_area_applied_region', 'rs_varieties_applied', 'rs_varieties_applied_data_arr', 'rs_area_insp_passed', 'rs_area_insp_passed_data', 'rs_varieties_applied_region', 'rs_varieties_applied_region_data', 'rs_area_per_program', 'rs_varieties_applied_programs', 'rs_varieties_applied_program_data', 'rs_area_applied_coop', 'rs_area_applied_coop_data', 'rs_area_applied_coop_data_all', 'cs_estimated_yield', 'cs_estimated_yield_data', 'cs_estimated_yield_months', 'cs_estimated_yield_totals', 'cs_estimated_yield_varieties', 'cs_estimated_yield_varieties_list', 'cs_estimated_yield_region_months', 'cs_estimated_yield_region_data', 'years'));
        }
    }

    public function sales() {
        $contacts = $this->contacts();

        // Seed Sales
        $seed_sales = SeedSales::orderBy('year', 'DESC')
                                ->orderBy('sem', 'DESC')
                                ->orderBy('timestamp', 'DESC')
                                ->first();

        // RS Sold by Variety timestamp, year and sem
        $rs_sold_var_filter = RSSalesVariety::orderBy('year', 'DESC')
                                            ->orderBy('sem', 'DESC')
                                            ->orderBy('timestamp', 'DESC')
                                            ->first();

        $rs_sold_variety = array();
        $rs_sold_variety_quantity = array();

        if ($rs_sold_var_filter) {
            // RS Sold by Variety
            $rs_sold_variety_query = RSSalesVariety::select('variety', 'quantity')
                                            ->where([
                                                ['timestamp', '=', $rs_sold_var_filter->timestamp],
                                                ['year', '=', $rs_sold_var_filter->year],
                                                ['sem', '=', $rs_sold_var_filter->sem]
                                            ])
                                            ->orderBy('variety', 'asc')
                                            ->get();

            foreach ($rs_sold_variety_query as $item) {
                array_push($rs_sold_variety, $item->variety);
                array_push($rs_sold_variety_quantity, $item->quantity / 1);
            }
        }

        // FS Sold by Variety timestamp, year and sem
        $fs_sold_var_filter = FSSalesVariety::orderBy('year', 'DESC')
                                            ->orderBy('sem', 'DESC')
                                            ->orderBy('timestamp', 'DESC')
                                            ->first();
        $fs_sold_variety = array();
        $fs_sold_variety_quantity = array();

        if ($fs_sold_var_filter) {
            // FS Sold by Variety
            $fs_sold_variety_query = FSSalesVariety::select('variety', 'quantity')
                                            ->where([
                                                ['timestamp', '=', $fs_sold_var_filter->timestamp],
                                                ['year', '=', $fs_sold_var_filter->year],
                                                ['sem', '=', $fs_sold_var_filter->sem]
                                            ])
                                            ->orderBy('variety', 'asc')
                                            ->get();

            foreach ($fs_sold_variety_query as $item) {
                array_push($fs_sold_variety, $item->variety);
                array_push($fs_sold_variety_quantity, $item->quantity / 1);
            }
        }

        // RS Sold by Station timestamp, year and sem
        $rs_sold_station_filter = RSSalesStation::orderBy('year', 'DESC')
                                            ->orderBy('sem', 'DESC')
                                            ->orderBy('timestamp', 'DESC')
                                            ->first();

        $rs_sold_station = array();
        $rs_sold_station_quantity = array();

        if ($rs_sold_station_filter) {
            // RS Sold by Station
            $rs_sold_station_query = RSSalesStation::select('station', 'quantity')
                                            ->where([
                                                ['timestamp', '=', $rs_sold_station_filter->timestamp],
                                                ['year', '=', $rs_sold_station_filter->year],
                                                ['sem', '=', $rs_sold_station_filter->sem]
                                            ])
                                            ->orderBy('station', 'asc')
                                            ->get();

            foreach ($rs_sold_station_query as $item) {
                array_push($rs_sold_station, $item->station);
                array_push($rs_sold_station_quantity, $item->quantity / 1);
            }
        }

        // FS Sold by Station timestamp, year and sem
        $fs_sold_station_filter = FSSalesStation::orderBy('year', 'DESC')
                                            ->orderBy('sem', 'DESC')
                                            ->orderBy('timestamp', 'DESC')
                                            ->first();

        $fs_sold_station = array();
        $fs_sold_station_quantity = array();

        if ($fs_sold_station_filter) {
            // FS Sold by Station
            $fs_sold_station_query = FSSalesStation::select('station', 'quantity')
                                            ->where([
                                                ['timestamp', '=', $fs_sold_station_filter->timestamp],
                                                ['year', '=', $fs_sold_station_filter->year],
                                                ['sem', '=', $fs_sold_station_filter->sem]
                                            ])
                                            ->orderBy('station', 'asc')
                                            ->get();

            foreach ($fs_sold_station_query as $item) {
                array_push($fs_sold_station, $item->station);
                array_push($fs_sold_station_quantity, $item->quantity / 1);
            }
        }

        // Varieties Sold by Station timestamp, year and sem
        $varieties_sold_filter = VarietiesSold::orderBy('year', 'DESC')
                                            ->orderBy('sem', 'DESC')
                                            ->orderBy('timestamp', 'DESC')
                                            ->first();

        $varieties_sold = array();
        $varieties_sold_quantity = array();

        $top_varieties_sold = array();
        $top_varieties_sold_quantity = array();

        if ($varieties_sold_filter) {
            // Varieties Sold
            $varieties_sold_query = VarietiesSold::select('variety', 'quantity')
                                            ->where([
                                                ['timestamp', '=', $varieties_sold_filter->timestamp],
                                                ['year', '=', $varieties_sold_filter->year],
                                                ['sem', '=', $varieties_sold_filter->sem]
                                            ])
                                            ->orderBy('variety', 'asc')
                                            ->get();

            // Top Varieties Sold
            $where = "CAST(quantity AS FLOAT) DESC";
            $top_varieties_sold_query = VarietiesSold::select('variety', 'quantity')
                                            ->where([
                                                ['timestamp', '=', $varieties_sold_filter->timestamp],
                                                ['year', '=', $varieties_sold_filter->year],
                                                ['sem', '=', $varieties_sold_filter->sem]
                                            ])
                                            ->orderByRaw($where)
                                            ->take(5)
                                            ->get();

            foreach ($varieties_sold_query as $item) {
                array_push($varieties_sold, $item->variety);
                array_push($varieties_sold_quantity, $item->quantity / 1);
            }

            foreach ($top_varieties_sold_query as $item) {
                array_push($top_varieties_sold, $item->variety);
                array_push($top_varieties_sold_quantity, $item->quantity / 1);
            }
        }


        return view('dashboard3.sales')->with(compact(
            'contacts', 
            'seed_sales', 
            'rs_sold_var_filter', 
            'rs_sold_variety',
            'rs_sold_variety_quantity',
            'fs_sold_var_filter', 
            'fs_sold_variety',
            'fs_sold_variety_quantity',
            'rs_sold_station_filter', 
            'rs_sold_station',
            'rs_sold_station_quantity',
            'fs_sold_station_filter', 
            'fs_sold_station',
            'fs_sold_station_quantity',
            'varieties_sold_filter', 
            'varieties_sold',
            'varieties_sold_quantity',
            'top_varieties_sold',
            'top_varieties_sold_quantity'));
    }

    public function seed_production() {
        $contacts = $this->contacts();

        return view('dashboard3.seed_production')->with(compact('contacts'));;
    }

    private function month_name($month) {
        switch ($month) {
            case '01':
                $month = "JAN";
                break;
            case '02':
                $month = "FEB";
                break;
            case '03':
                $month = "MAR";
                break;
            case '04':
                $month = "APR";
                break;
            case '05':
                $month = "MAY";
                break;
            case '06':
                $month = "JUN";
                break;
            case '07':
                $month = "JUL";
                break;
            case '08':
                $month = "AUG";
                break;
            case '09':
                $month = "SEP";
                break;
            case '10':
                $month = "OCT";
                break;
            case '11':
                $month = "NOV";
                break;
            case '12':
                $month = "DEC";
                break;
        }

        return $month;
    }

    private function current_year_sem() {
        // CURRENT CROPPING SEM AND YEAR
        $monthToday = date('m');
        $monthDateToday = date('m-d');

        if ($monthDateToday >= date('m-d', strtotime('2022-09-16'))) {
            if ($monthToday == '09' || $monthToday == '10' || $monthToday == '11' || $monthToday == '12') {
                $croppingYear = date('Y') + 1;
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

        $data = array('year' => $croppingYear, 'sem' => $semester);
        return $data;
    }
}
