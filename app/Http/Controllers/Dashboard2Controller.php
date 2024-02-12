<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SGForm;
use App\Variable;
use App\Seeds;

class Dashboard2Controller extends Controller {
    
    public function index() {
        $contacts = $this->contacts();

        // Seed growers API
        $filepath = $this->sgFilePath();
        $xml = simplexml_load_file($filepath) or die("Error: Cannot create object");
        $json = json_encode($xml);
        $sg_array = json_decode($json,TRUE);

        // Seed cooperatives API
        $filepath = $this->SCFilePath();
        $xml = simplexml_load_file($filepath) or die("Error: Cannot create object");
        $json = json_encode($xml);
        $sc_array = json_decode($json,TRUE);

        // Seed growers count
        $sgCount = count($sg_array['seedgrower']);

        // Seed cooperatives count
        $scCount = count($sc_array['seedcoop']);

        // Count GrowApp data
        $sgForms = SGForm::select('id')
                        ->where([
                            ['is_test_data', '=', 0]
                        ])
                        ->get()
                        ->count();


        return view('dashboard2.index')->with(compact('contacts', 'sgCount', 'scCount', 'sgForms'));
    }

    public function growApp() {
        $contacts = $this->contacts();

        // Initial MC for DS
        $data = Variable::select('value')->where('variable_name', '=', 'init_MC_DS')->first();
        $init_MC_DS = $data->value;

        // Initial MC for WS
        $data = Variable::select('value')->where('variable_name', '=', 'init_MC_WS')->first();
        $init_MC_WS = $data->value;

        // Multiplier
        $data = Variable::select('value')->where('variable_name', '=', 'multiplier')->first();
        $multiplier = $data->value;

        // Processing Losses
        $data = Variable::select('value')->where('variable_name', '=', 'processing_losses')->first();
        $processing_losses = $data->value;

        // Cleaned weight percentage
        $cln_wt_percent = 100 - $processing_losses;
        $cln_wt_percent = $cln_wt_percent / 100; // convert to decimal

        // Rejection rate
        $data = Variable::select('value')->where('variable_name', '=', 'rejection_rate')->first();
        $rejection_rate = $data->value;

        // Tagged weight percentage
        $tagged_wt_percent = 100 - $rejection_rate;
        $tagged_wt_percent = $tagged_wt_percent / 100; // convert to decimal

        // Seed growers API
        $filepath = $this->sgFilePath();
        $xml = simplexml_load_file($filepath) or die("Error: Cannot create object");
        $json = json_encode($xml);
        $sg_array = json_decode($json,TRUE);

        $data = array(
            'init_MC_DS' => $init_MC_DS,
            'init_MC_WS' => $init_MC_WS,
            'multiplier' => $multiplier,
            'cln_wt_percent' => $cln_wt_percent,
            'tagged_wt_percent' => $tagged_wt_percent
        );

        // Planted seeds volume
        $plantedSeeds = $this->planted_seeds($sg_array['seedgrower'], $data);

        echo json_encode($plantedSeeds);

        // return view('dashboard2.growApp')->with(compact('contacts'));
    }

    public function planted_seeds($sgArray, $data) {
        $semester;
        $init_mc;
        $aveYld = 0;
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;

        // Current year and season
        $currDate = date('Y-m-d');
        $currYear = date('Y');
        $lastYear = $currYear - 1;
        $sem1_start = $lastYear . '-09-16';
        $sem1_end = $currYear . '-03-15';
        $sem2_start = $currYear . '-03-16';
        $sem2_end = $currYear . '-09-15';

        if ($currDate >= $sem1_start && $currDate <= $sem1_end) {
            $year = $currYear;
            $sem = 1;
            $semStart = $sem1_start;
            $semEnd = $sem1_end;
            $init_mc = $data['init_MC_DS'];
        } else if ($currDate >= $sem2_start && $currDate <= $sem2_end) {
            $year = $currYear;
            $sem = 2;
            $semStart = $sem2_start;
            $semEnd = $sem2_end;
            $init_mc = $data['init_MC_WS'];
        } else {
            // $year = $currYear + 1;
            // $sem = 1;
            // $semStart = $currYear . '-09-16';
            // $semEnd = $year . '-03-15';
            // $init_mc = $data['init_MC_DS'];
            $year = 2021;
            $sem = 2;
            $semStart = $sem2_start;
            $semEnd = $sem2_end;
            $init_mc = $data['init_MC_WS'];
        }

        // GrowApp data
        $sgForms = SGForm::select('quantity', 'variety', 'accredno', 'serial_number', 'areaplanted', 'dateplanted')
                        ->where([
                            ['dateplanted', '>=', $semStart],
                            ['dateplanted', '<=', $semEnd],
                            ['is_test_data', '=', 0]
                        ])
                        ->get();

        if ($sgForms) {
            foreach ($sgForms as $item) {
                foreach ($sgArray as $key => $value) {
                    // Check if serial number is empty
                    if ($item->serial_number) {
                        $isMatched = ($value['SerialNum'] == $item->serial_number) ? true : false;
                    } else {
                    // If serial number is empty check the accreditation no
                        $isMatched = ($value['SerialNum'] == $item->accredno) ? true : false;    
                    }

                    if ($isMatched) {
                        // Check if variety is in RSIS variety database
                        // Check if has NSIC in name
                        if (strpos($item->variety, 'NSIC') !== false) {
                            $varietyName = $item->variety;
                            $name = explode(" ", $varietyName);

                            // if naming is like NSIC RC 222
                            if (count($name) == 3) {
                                $oldName = $name[0] . " " . $name[1] . "" . $name[2];
                            } else {
                                $oldName = $item->variety;
                            }

                            // Query if variety exists in RSIS database
                            $varietyDetails = Seeds::select('NSICCode', 'VarietyName', 'ave_yld')
                                                    ->where([
                                                        ['variety', '=', $oldName],
                                                        ['variety_name', 'NOT LIKE', '%DWSR%']
                                                    ])
                                                    ->first();

                            if ($varietyDetails) {
                                $variety = $varietyDetails->NSICCode;
                                $aveYld = $varietyDetails->aveYld;
                            }
                        } else if (strpos($item->variety, 'PSB') !== false) {
                            $varietyName = $item->variety;
                            $name = explode(" ", $varietyName);

                            // if naming is link PSB Rc 10
                            if (count($name) == 3) {
                                $oldName = $name[0] . " " . $name[1] . "" . $name[2];
                            } else {
                                $oldName = $item->variety;
                            }

                            // Query if variety exists in RSIS database
                            $varietyDetails = Seeds::select('NSICCode', 'VarietyName', 'ave_yld')
                                                    ->where([
                                                        ['variety', '=', $oldName],
                                                        ['variety_name', 'NOT LIKE', '%DWSR%']
                                                    ])
                                                    ->first();

                            if ($varietyDetails) {
                                $variety = $varietyDetails->VarietyName;
                                $aveYld = $varietyDetails->aveYld;
                            }
                        } else {
                            $varietyName = $item->variety;
                            $name = str_replace("-", " ", $varietyName);

                            if (count($name) == 2) {
                                $oldName = $name[0] . "" . $name[1];

                                $varietyDetails = Seeds::select('NSICCode', 'VarietyName', 'ave_yld')
                                                    ->where([
                                                        ['variety', '=', $oldName],
                                                        ['variety_name', 'NOT LIKE', '%DWSR%']
                                                    ])
                                                    ->first();

                                if ($varietyDetails) {
                                    $variety = $varietyDetails->VarietyName;
                                    $aveYld = $varietyDetails->aveYld;
                                }
                            } else {
                                $varietyDetails = Seeds::select('NSICCode', 'VarietyName', 'ave_yld')
                                                    ->where([
                                                        ['variety', '=', $item->variety],
                                                        ['variety_name', 'NOT LIKE', '%DWSR%']
                                                    ])
                                                    ->first();

                                if ($varietyDetails) {
                                    $variety = $varietyDetails->VarietyName;
                                    $aveYld = $varietyDetails->aveYld;
                                }
                            }
                        }

                        if ($aveYld != 0) {
                            // Fresh weight
                            $freshWeight = $item->areaplanted * $aveYld;
                            $fresh += $freshWeight;

                            // Dried weight
                            $dried += $freshWeight * ((100 - $init_mc) / $data['multiplier']);
                        }
                    }
                }
            }
        }

        // Cleaned weight
        $cleaned = $dried * $data['cln_wt_percent'];

        // Tagged weight
        $tagged = $cleaned * $data['tagged_wt_percent'];

        $data = array(
            'fresh_wt' => $fresh,
            'dried_wt' => $dried,
            'cleaned_wt' => $cleaned,
            'tagged_wt' => $tagged
        );

        return $data;
    }

}
