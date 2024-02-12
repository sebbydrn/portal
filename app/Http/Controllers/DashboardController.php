<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Variable;
use App\Dashboard;
use App\PalletPlan;
use App\Schema;
use App\Warehouse;
use App\Seeds;
use Auth, DB, Entrust;
use App\Helpers\DatabaseConnection;

class DashboardController extends Controller {

    // Constructor
    public function __construct() {
        $semester;
        $initMC;
        $yearFilterOnly = false;
        $loopPlantedSeeds = false;
    }
    
    // Seed growers API
	public function SG_API() {
        $filepath = $this->sgFilePath();
        $xml = simplexml_load_file($filepath) or die("Error: Cannot create object");
        $json = json_encode($xml);
        $sg_array = json_decode($json,TRUE);

        return $sg_array;
    }

    // Preliminary inspection API
    public function SPI_API() {
        $SPIFilePath = $this->SPIFilePath();
        $SPI_XML = simplexml_load_file($SPIFilePath) or die("Error: Cannot create object");
        $SPI_JSON = json_encode($SPI_XML);
        $SPIArray = json_decode($SPI_JSON, TRUE);

        return $SPIArray;
    }

    // Final inspection API
    public function SPFI_API() {
        $SPFIFilePath = $this->SPFIFilePath();
        $SPFI_XML = simplexml_load_file($SPFIFilePath) or die("Error: Cannot create object");
        $SPFI_JSON = json_encode($SPFI_XML);
        $SPFIArray = json_decode($SPFI_JSON, TRUE);

        return $SPFIArray;
    }

    // Find serial number of logged in user
    public function SGSerialNum() {
        // Logged in user's accreditation no
        $accreNo = Auth::user()->accreditation_no;

        // Remove dashes and slashes in accreditation no
        $accreNo = str_replace("-", "", $accreNo);
        $accreNo = str_replace("/", "", $accreNo);

        // Make accreditation no to lowercases
        $accreNo = strtolower($accreNo);

        $SGData = $this->SG_API();

        foreach ($SGData as $key => $value) {
            $currAccreNo = $value['AccreNum'];

            // Remove dashes and slashes in accreditation no
            $currAccreNo = str_replace("-", "", $currAccreNo);
            $currAccreNo = str_replace("/", "", $currAccreNo);

            // Make accreditation no to lowercases
            $currAccreNo = strtolower($currAccreNo);

            if ($currAccreNo == $accreNo) {
                $serialNum = $value['SerialNum'];
                return $serialNum;
            }
        }
    }

    public function index() {
        $contacts = $this->contacts();

        if (!Auth::guest()) {
            if (Entrust::hasRole('seed_producer')) {
        		$variable = new Variable();
        		// Get moisture content variable
                $moisture_content = $variable->variable("moisture_content");
                $moisture_content = $moisture_content->value;

                // Get years for filtering of seed production volume estimates
                $years = $this->seedProdYears();
                $yearsArr = array();

                foreach ($years as $year) {
                    $datePlantedYear = date('Y', strtotime($year->dateplanted));

                    if (!in_array($datePlantedYear, $yearsArr)) {
                        array_push($yearsArr, $datePlantedYear);
                    }
                }

        		return view('dashboard.index')
                    ->with(compact('contacts', 'moisture_content', 'yearsArr'));
            } else {
                return view('dashboard.index')
                    ->with(compact('contacts'));
            }
        } else {
            return view('dashboard.index')
                ->with(compact('contacts'));
        }
	}

	public function production_volume(Request $request) {
    	$dashboard = new Dashboard();
        $variable = new Variable();

        // Initial MC for DS
        $init_MC_DS = $variable->variable("init_MC_DS")->value;

        // Initial MC for WS
        $init_MC_WS = $variable->variable("init_MC_WS")->value;

        // Multiplier
        $multiplier = $variable->variable("multiplier")->value;

        // Processing losses
        $processing_losses = $variable->variable("processing_losses")->value;

        // Cleaned weight percentage
        $cln_weight_per = 100 - $processing_losses;
        $cln_weight_per = $cln_weight_per / 100; // Convert to decimal

        // Rejection rate
        $rejection_rate = $variable->variable("rejection_rate")->value;

        // Tagged weight percentage
        $tgd_weight_per = 100 - $rejection_rate;
        $tgd_weight_per = $tgd_weight_per / 100;

        // Get moisture content variable
        $moisture_content = $variable->variable("moisture_content");
        $initial_mc = $moisture_content->value; // Convert to decimal

        $filters = array(
            'region' => $request->region,
            'year' => $request->year,
            'sem' => $request->sem,
            'init_MC_DS' => $init_MC_DS,
            'init_MC_WS' => $init_MC_WS,
            'multiplier' => $multiplier,
            'cln_weight_per' => $cln_weight_per,
            'tgd_weight_per' => $tgd_weight_per
        );

        // Seed Growers API
        $SGData = $this->SG_API();

        // Preliminary inspection API
        $SPIData = $this->SPI_API();

        // Final inspection API
        $SPFIData = $this->SPFI_API();

        // Get logged in user's serial number in SG API
        $serialNum = $this->SGSerialNum();

        // Get purchased seeds volume
        $purchasedSeeds = $this->purchasedSeeds($filters, $serialNum);

        // Get planted seeds volume
        $plantedSeeds = $this->plantedSeeds($filters, $serialNum);

        // Get preliminary inspection seeds volume
        $prelimInspection = $this->prelimInspection($filters, $serialNum, $SPIData);

        // Get final inspection seeds volume
        $finalInspection = $this->finalInspection($filters, $serialNum, $SPFIData);

        $data = array(
            'purchased_seeds_fresh' => round($purchasedSeeds['fresh'], 2),
            'purchased_seeds_dried' => round($purchasedSeeds['dried'], 2),
            'purchased_seeds_cleaned' => round($purchasedSeeds['cleaned'], 2),
            'purchased_seeds_tagged' => round($purchasedSeeds['tagged'], 2),
            'planted_seeds_fresh' => round($plantedSeeds['fresh'], 2),
            'planted_seeds_dried' => round($plantedSeeds['dried'], 2),
            'planted_seeds_cleaned' => round($plantedSeeds['cleaned'], 2),
            'planted_seeds_tagged' => round($plantedSeeds['tagged'], 2),
            'SPI_fresh' => round($prelimInspection['fresh'], 2),
            'SPI_dried' => round($prelimInspection['dried'], 2),
            'SPI_cleaned' => round($prelimInspection['cleaned'], 2),
            'SPI_tagged' => round($prelimInspection['tagged'], 2),
            'SPFI_fresh' => round($finalInspection['fresh'], 2),
            'SPFI_dried' => round($finalInspection['dried'], 2),
            'SPFI_cleaned' => round($finalInspection['cleaned'], 2),
            'SPFI_tagged' => round($finalInspection['tagged'], 2)
        );

    	echo json_encode($data);
    }

    // Drilldown for seed production volume estimates bar chart
    public function production_volume_dd(Request $request) {
        $dashboard = new Dashboard();
        $variable = new Variable();

        // Initial MC for DS
        $init_MC_DS = $variable->variable("init_MC_DS")->value;

        // Initial MC for WS
        $init_MC_WS = $variable->variable("init_MC_WS")->value;

        // Multiplier
        $multiplier = $variable->variable("multiplier")->value;

        // Processing losses
        $processing_losses = $variable->variable("processing_losses")->value;

        // Cleaned weight percentage
        $cln_weight_per = 100 - $processing_losses;
        $cln_weight_per = $cln_weight_per / 100; // Convert to decimal

        // Rejection rate
        $rejection_rate = $variable->variable("rejection_rate")->value;

        // Tagged weight percentage
        $tgd_weight_per = 100 - $rejection_rate;
        $tgd_weight_per = $tgd_weight_per / 100;

        // Get moisture content variable
        $moisture_content = $variable->variable("moisture_content");
        $initial_mc = $moisture_content->value; // Convert to decimal

        $filters = array(
            'region' => $request->region,
            'year' => $request->year,
            'sem' => $request->sem,
            'init_MC_DS' => $init_MC_DS,
            'init_MC_WS' => $init_MC_WS,
            'multiplier' => $multiplier,
            'cln_weight_per' => $cln_weight_per,
            'tgd_weight_per' => $tgd_weight_per
        );

        // Seed Growers API
        $SGData = $this->SG_API();

        // Preliminary inspection API
        $SPIData = $this->SPI_API();

        // Final inspection API
        $SPFIData = $this->SPFI_API();

        // Get logged in user's serial number in SG API
        $serialNum = $this->SGSerialNum();

        $data;
        $legend = "";
        $bg = "";

        if ($request->key == 0) {
            // Purchased seeds drill down
            // Get purchased seeds volume
            if ($request->plotindex == 0) {
                $data = $this->purchasedSeedsDD($filters, $serialNum, "Fresh");
                $legend = "Purchased - Fresh";
                $bg = "#10817a";
            } elseif ($request->plotindex == 1) {
                $data = $this->purchasedSeedsDD($filters, $serialNum, "Dried");
                $legend = "Purchased - Dried";
                $bg = "#3e9b5e";
            } elseif ($request->plotindex == 2) {
                $data = $this->purchasedSeedsDD($filters, $serialNum, "Cleaned");
                $legend = "Purchased - Cleaned";
                $bg = "#96ab27";
            } elseif ($request->plotindex == 3) {
                $data = $this->purchasedSeedsDD($filters, $serialNum, "Tagged");
                $legend = "Purchased - Tagged";
                $bg = "#ffa600";
            }
        } elseif ($request->key == 1) {
            // Planted seeds drill down
            // Get planted seeds volume
            if ($request->plotindex == 0) {
                $data = $this->plantedSeedsDD($filters, $serialNum, "Fresh");
                $legend = "Planted - Fresh";
                $bg = "#10817a";
            } elseif ($request->plotindex == 1) {
                $data = $this->plantedSeedsDD($filters, $serialNum, "Dried");
                $legend = "Planted - Dried";
                $bg = "#3e9b5e";
            } elseif ($request->plotindex == 2) {
                $data = $this->plantedSeedsDD($filters, $serialNum, "Cleaned");
                $legend = "Planted - Cleaned";
                $bg = "#96ab27";
            } elseif ($request->plotindex == 3) {
                $data = $this->plantedSeedsDD($filters, $serialNum, "Tagged");
                $legend = "Planted - Tagged";
                $bg = "#ffa600";
            }
        } elseif ($request->key == 2) {
            // Preliminary inspection drill down
            // Get preliminary inspection estimates data
            if ($request->plotindex == 0) {
                $data = $this->prelimInspectionDD($filters, $serialNum, $SPIData, "Fresh");
                $legend = "Preliminary Inspection - Fresh";
                $bg = "#10817a";
            } elseif ($request->plotindex == 1) {
                $data = $this->prelimInspectionDD($filters, $serialNum, $SPIData, "Dried");
                $legend = "Preliminary Inspection - Dried";
                $bg = "#3e9b5e";
            } elseif ($request->plotindex == 2) {
                $data = $this->prelimInspectionDD($filters, $serialNum, $SPIData, "Cleaned");
                $legend = "Preliminary Inspection - Cleaned";
                $bg = "#96ab27";
            } elseif ($request->plotindex == 3) {
                $data = $this->prelimInspectionDD($filters, $serialNum, $SPIData, "Tagged");
                $legend = "Preliminary Inspection - Tagged";
                $bg = "#ffa600";
            }
        } elseif ($request->key == 3) {
            // Final inspection drill down
            // Get final inspection estimates data
            if ($request->plotindex == 0) {
                $data = $this->finalInspectionDD($filters, $serialNum, $SPFIData, "Fresh");
                $legend = "Final Inspection - Fresh";
                $bg = "#10817a";
            } elseif ($request->plotindex == 1) {
                $data = $this->finalInspectionDD($filters, $serialNum, $SPFIData, "Dried");
                $legend = "Final Inspection - Dried";
                $bg = "#3e9b5e";
            } elseif ($request->plotindex == 2) {
                $data = $this->finalInspectionDD($filters, $serialNum, $SPFIData, "Cleaned");
                $legend = "Final Inspection - Cleaned";
                $bg = "#96ab27";
            } elseif ($request->plotindex == 3) {
                $data = $this->finalInspectionDD($filters, $serialNum, $SPFIData, "Tagged");
                $legend = "Final Inspection - Tagged";
                $bg = "#ffa600";
            }
        }

        $labels = array();
        $values = array();

        foreach ($data as $item) {
            $labels[] = $item['variety'];
            $values[] = round($item['quantity'], 2);
        }

        $dataArr = array(
            'labels' => $labels,
            'values' => $values,
            'legend' => $legend,
            'bg' => $bg
        );

        echo json_encode($dataArr);
    }

    // Production area for geotag map
    public function production_area(Request $request) {
        $filter = array(
            'sem' => $request->sem
        );

        // Seed Growers API
        $SGData = $this->SG_API();

        // Get logged in user's serial number in SG API
        $serialNum = $this->SGSerialNum();

        // ADD FILTERS FOR DATE RANGE

        $productionArea = DB::connection('grow_app')
            ->table('sg_forms')
            ->select('*')
            ->where('is_test_data', 0)
            ->where('serial_number', $serialNum)
            ->orWhere('accredno', $serialNum)
            ->get();

        $data = array();

        if ($productionArea) {
            foreach ($productionArea as $item) {
                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                $variety = str_replace("NSIC ", '', $item->variety);
                $variety = str_replace("Rc ", "Rc", $variety);

                $seed = $this->findSeedByName($variety);

                $data[] = array(
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'seed_class' => $item->seedclass,
                    'variety' => $item->variety,
                    'area_planted' => $item->areaplanted,
                    'ave_yield' => ($seed) ? $seed->ave_yld : 0,
                    'serial_num' => $item->serial_number
                );
            }
        }

        echo json_encode($data);
    }

    // Purchased seeds
    public function purchasedSeeds($filters, $serialNum) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;

        $orders = $this->orders($filters);

        foreach ($orders as $order) {
            $orderID = $order->order_id;
            // $tblName = $order->tbl_name;

            // Get pallet plan
            $palletPlanID = $order->pallet_plan_id;

            if ($palletPlanID) {

                $pallet_plan = PalletPlan::find($palletPlanID);

                if ($pallet_plan) {

                    // Get warehouse name
                    $warehouse_id = $pallet_plan->warehouse_id;
                    $warehouse = Warehouse::find($warehouse_id);

                    // Get schema
                    $station_id = $warehouse->station_id;
                    $schema = Schema::where('station_id', $station_id)->first();
                    $schema_name = $schema->name;

                    // Set database connection
                    $connection = DatabaseConnection::setDBConnection($schema_name);

                    // Release table name
                    $year = $pallet_plan->year;
                    $semester = $pallet_plan->semester;
                    $stocks_tbl_name = "tbl_sem".$semester."_".$year."_stocks";
                    $release_pur_tbl_name = "tbl_sem".$semester."_".$year."_release_pur";

                    if ($this->yearFilterOnly) {
                        if ($order->sem == "1st") {
                            $this->initMC = $filters['init_MC_DS']; 
                        } elseif ($order->sem == "2nd") {
                            $this->initMC = $filters['init_MC_WS'];
                        }
                    }

                    // Get the released ordes in the released table
                    // $releasedOrders = DB::connection('warehouse')
                    //     ->table($tblName)
                    //     ->select('pallet_code', 'quantity', 'serialNum')
                    //     ->where('order_id', $orderID)
                    //     ->where('serialNum', $serialNum)
                    //     ->where('status', "Released")
                    //     ->get();

                    $releasedOrders = $connection->table($release_pur_tbl_name)
                                                ->select('pallet_id', 'quantity', 'serialNum')
                                                ->where('order_id', $orderID)
                                                ->where('activity', "Released Order")
                                                ->get();

                    // Stocks table name
                    // $stocksTblName = str_replace("tbl_release_pur_", "tbl_stocks_", $tblName);

                    if ($releasedOrders) {
                        // Loop the released orders in case there is more than 1 seed variety
                        foreach ($releasedOrders as $releasedOrder) {
                            $palletID = $releasedOrder->pallet_id;
                            // $palletCode = $releasedOrder->pallet_code;
                            $quantity = $releasedOrder->quantity;

                            // Get the variety of the orders in the pallet plan
                            // $stocks = DB::connection('warehouse')
                            //     ->table($stocksTblName)
                            //     ->select('taggedSeedClass', 'seedVarietyId')
                            //     ->where('palletCode', $palletCode)
                            //     ->first();

                            $stocks = $connection->table($stocks_tbl_name)
                                                    ->select('variety', 'seed_type_id', 'inbred_seed_class_id')
                                                    ->where('pallet_id', $palletID)
                                                    ->first();

                            switch ($stocks->inbred_seed_class_id) {
                                case 1:
                                    $seedClass = "Breeder";
                                    break;
                                case 2:
                                    $seedClass = "Foundation";
                                    break;
                                case 3:
                                    $seedClass = "Registered";
                                    break;
                                case 4:
                                    $seedClass = "Certified";
                                    break;
                            }

                            // $varietyID = $stocks->seedVarietyId;
                            // $seedClass = $stocks->taggedSeedClass;

                            $seed = Seeds::where('variety_name', 'NOT LIKE', '%DWSR%')
                                            ->where('variety', '=', $stocks->variety)
                                            ->first();

                            // $seed = $this->findSeedByID($varietyID);

                            if ($seed) {
                                // Fresh weight
                                $freshWeight = (($quantity / 40) * $seed->ave_yld);

                                $fresh += $freshWeight;

                                // Dried weight
                                $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                            }
                        }
                    }

                }

                

            }
        }

        // Cleaned weight
        $cleaned += $dried * $filters['cln_weight_per'];

        // Tagged weight
        $tagged += $cleaned * $filters['tgd_weight_per'];

        $data = array(
            'fresh' => $fresh,
            'dried' => $dried,
            'cleaned' => $cleaned,
            'tagged' => $tagged
        );

        return $data;
    }

    // Purchased seeds drill down
    public function purchasedSeedsDD($filters, $serialNum, $status) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;
        $data = array();

        $orders = DB::connection('seed_ordering')
                    ->table('tbl_planting_season')
                    ->select('order_id', 'pallet_plan_id', 'sem');

        $orders = $this->orders($filters);

        foreach ($orders as $order) {
            $orderID = $order->order_id;
            // $tblName = $order->tbl_name;

            // Get pallet plan
            $palletPlanID = $order->pallet_plan_id;

            if ($palletPlanID) {

                $pallet_plan = PalletPlan::find($palletPlanID);

                if ($pallet_plan) {

                    // Get warehouse name
                    $warehouse_id = $pallet_plan->warehouse_id;
                    $warehouse = Warehouse::find($warehouse_id);

                    // Get schema
                    $station_id = $warehouse->station_id;
                    $schema = Schema::where('station_id', $station_id)->first();
                    $schema_name = $schema->name;

                    // Set database connection
                    $connection = DatabaseConnection::setDBConnection($schema_name);

                    // Release table name
                    $year = $pallet_plan->year;
                    $semester = $pallet_plan->semester;
                    $stocks_tbl_name = "tbl_sem".$semester."_".$year."_stocks";
                    $release_pur_tbl_name = "tbl_sem".$semester."_".$year."_release_pur";

                    if ($this->yearFilterOnly) {
                        if ($order->sem == "1st") {
                            $this->initMC = $filters['init_MC_DS']; 
                        } elseif ($order->sem == "2nd") {
                            $this->initMC = $filters['init_MC_WS'];
                        }
                    }

                    // Get the released ordes in the released table
                    // $releasedOrders = DB::connection('warehouse')
                    //     ->table($tblName)
                    //     ->select('pallet_code', 'quantity', 'serialNum')
                    //     ->where('order_id', $orderID)
                    //     ->where('serialNum', $serialNum)
                    //     ->where('status', "Released")
                    //     ->get();

                    $releasedOrders = $connection->table($release_pur_tbl_name)
                                                ->select('pallet_id', 'quantity', 'serialNum')
                                                ->where([
                                                    ['order_id', '=', $orderID],
                                                    ['activity', '=', "Released Order"]
                                                ])
                                                ->get();

                    // Stocks table name
                    // $stocksTblName = str_replace("tbl_release_pur_", "tbl_stocks_", $tblName);

                    if ($releasedOrders) {
                        // Loop the released orders in case there is more than 1 seed variety
                        foreach ($releasedOrders as $releasedOrder) {
                            $palletID = $releasedOrder->pallet_id;
                            // $palletCode = $releasedOrder->pallet_code;
                            $quantity = $releasedOrder->quantity;

                            // Get the variety of the orders in the pallet plan
                            // $stocks = DB::connection('warehouse')
                            //     ->table($stocksTblName)
                            //     ->select('taggedSeedClass', 'seedVarietyId')
                            //     ->where('palletCode', $palletCode)
                            //     ->first();

                            $stocks = $connection->table($stocks_tbl_name)
                                                    ->select('variety', 'seed_type_id', 'inbred_seed_class_id')
                                                    ->where('pallet_id', $palletID)
                                                    ->first();

                            // $varietyID = $stocks->seedVarietyId;
                            // $seedClass = $stocks->taggedSeedClass;

                            switch ($stocks->inbred_seed_class_id) {
                                case 1:
                                    $seedClass = "Breeder";
                                    break;
                                case 2:
                                    $seedClass = "Foundation";
                                    break;
                                case 3:
                                    $seedClass = "Registered";
                                    break;
                                case 4:
                                    $seedClass = "Certified";
                                    break;
                            }

                            // $seed = $this->findSeedByID($varietyID);

                            $seed = Seeds::where('variety_name', 'NOT LIKE', '%DWSR%')
                                            ->where('variety', '=', $stocks->variety)
                                            ->first();

                            if ($seed) {
                                switch ($status) {
                                    case "Fresh":
                                        // Fresh purchased data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $data[$seed->variety]['quantity'] += ($quantity / 40) * $seed->ave_yld;
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $data[$seed->variety]['quantity'] = ($quantity / 40) * $seed->ave_yld;
                                        }
                                        break;
                                    case "Dried":
                                        // Fresh purchased data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $data[$seed->variety]['quantity'] += (($quantity / 40) * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $data[$seed->variety]['quantity'] = (($quantity / 40) * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        }
                                        break;
                                    case "Cleaned":
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $dried = (($quantity / 40) * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $dried = (($quantity / 40) * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                        }
                                        break;
                                    case "Tagged":
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $dried = (($quantity / 40) * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $cleaned = $dried * $filters['cln_weight_per'];
                                            $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $dried = (($quantity / 40) * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $cleaned = $dried * $filters['cln_weight_per'];
                                            $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                    }

                }

                

            }
        }

        return $data;
    }

    // Planted seeds
    public function plantedSeeds($filters, $serialNum) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;

        if ($filters['year'] != 0) {
            $selectedYear = $filters['year'];
            
            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($selectedYear);

            if ($filters['sem'] == "1") {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                $this->loopPlantedSeeds = true;
            } elseif ($filters['sem'] == "2") {
                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                $this->loopPlantedSeeds = true;
            } else {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        // Remove NSIC prefix from NSIC varieties to match with NSIC API
                        // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                        $variety = str_replace("NSIC ", "", $item->variety);
                        $variety = str_replace("Rc ", "Rc", $variety);

                        $seed = $this->findSeedByName($variety);

                        if ($seed) {
                            // Fresh weight
                            $freshWeight = $item->areaplanted * $seed->ave_yld;

                            $fresh += $freshWeight;

                            // Dried weight
                            $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                        }
                    }
                }

                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        // Remove NSIC prefix from NSIC varieties to match with NSIC API
                        // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                        $variety = str_replace("NSIC ", "", $item->variety);
                        $variety = str_replace("Rc ", "Rc", $variety);

                        $seed = $this->findSeedByName($variety);

                        if ($seed) {
                            // Fresh weight
                            $freshWeight = $item->areaplanted * $seed->ave_yld;

                            $fresh += $freshWeight;

                            // Dried weight
                            $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                        }
                    }
                }
            }
        } else {
            // No filter for year and semester
            // Get current year and semester
            $currentDate = date('Y-m-d');

            $currentYear = date('Y');

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($currentYear);

            if ($currentDate >= $dates['sem1Start'] && $currentDate <= $dates['sem1End']) {
                $this->semester = 1;
            } elseif ($currentDate >= $dates['sem2Start'] && $currentDate <= $dates['sem2End']) {
                $this->semester = 2;
            }

             if ($this->semester == 1) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
             } elseif ($this->semester == 2) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
             }

             $this->loopPlantedSeeds = true;
        }

        if ($this->loopPlantedSeeds) {
            if ($plantedSeeds) {
                foreach ($plantedSeeds as $item) {
                    // Remove NSIC prefix from NSIC varieties to match with NSIC API
                    // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                    $variety = str_replace("NSIC ", "", $item->variety);
                    $variety = str_replace("Rc ", "Rc", $variety);

                    $seed = $this->findSeedByName($variety);

                    if ($seed) {
                        // Fresh weight
                        $freshWeight = $item->areaplanted * $seed->ave_yld;

                        $fresh += $freshWeight;

                        // Dried weight
                        $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                    }
                }
            }
        }

        // Cleaned weight
        $cleaned += $dried * $filters['cln_weight_per'];

        // Tagged weight
        $tagged += $cleaned * $filters['tgd_weight_per'];

        $data = array(
            'fresh' => $fresh,
            'dried' => $dried,
            'cleaned' => $cleaned,
            'tagged' => $tagged
        );

        return $data;
    }

    // Planted seeds drill down
    public function plantedSeedsDD($filters, $serialNum, $status) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;
        $data = array();

        if ($filters['year'] != 0) {
            $selectedYear = $filters['year'];

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($selectedYear);

            if ($filters['sem'] == "1") {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                $this->loopPlantedSeeds = true;
            } elseif ($filters['sem'] == "2") {
                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                $this->loopPlantedSeeds = true;
            } else {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        // Remove NSIC prefix from NSIC varieties to match with NSIC API
                        // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                        $variety = str_replace("NSIC ", "", $item->variety);
                        $variety = str_replace("Rc ", "Rc", $variety);

                        $seed = $this->findSeedByName($variety);

                        if ($seed) {
                            switch ($status) {
                                case "Fresh":
                                    // Fresh planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $data[$seed->variety]['quantity'] += $item->areaplanted * $seed->ave_yld;
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $data[$seed->variety]['quantity'] = $item->areaplanted * $seed->ave_yld;
                                    }
                                    break;
                                case "Dried":
                                    // Dried planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $data[$seed->variety]['quantity'] += ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $data[$seed->variety]['quantity'] = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    }
                                    break;
                                case "Cleaned":
                                    // Cleaned planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                    }
                                    break;
                                case "Tagged":
                                    // Tagged planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $cleaned = $dried * $filters['cln_weight_per'];
                                        $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $cleaned = $dried * $filters['cln_weight_per'];
                                        $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                    }
                                default:
                                    break;
                            }
                        }
                    }
                }

                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        // Remove NSIC prefix from NSIC varieties to match with NSIC API
                        // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                        $variety = str_replace("NSIC ", "", $item->variety);
                        $variety = str_replace("Rc ", "Rc", $variety);

                        $seed = $this->findSeedByName($variety);

                        if ($seed) {
                            switch ($status) {
                                case "Fresh":
                                    // Fresh planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $data[$seed->variety]['quantity'] += $item->areaplanted * $seed->ave_yld;
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $data[$seed->variety]['quantity'] = $item->areaplanted * $seed->ave_yld;
                                    }
                                    break;
                                case "Dried":
                                    // Dried planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $data[$seed->variety]['quantity'] += ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $data[$seed->variety]['quantity'] = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    }
                                    break;
                                case "Cleaned":
                                    // Cleaned planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                    }
                                    break;
                                case "Tagged":
                                    // Tagged planted data
                                    if (array_key_exists($seed->variety, $data)) {
                                        // Add to current index if seed variety exists inside array
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $cleaned = $dried * $filters['cln_weight_per'];
                                        $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                    } else {
                                        // Create new index if seed variety doesn't exist inside the array
                                        $data[$seed->variety]['variety'] = $seed->variety;
                                        $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        $cleaned = $dried * $filters['cln_weight_per'];
                                        $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                    }
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
        } else {
            // No filter for year and semester
            // Get current year and semester
            $currentDate = date('Y-m-d');

            $currentYear = date('Y');

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($currentYear);

            if ($currentDate >= $dates['sem1Start'] && $currentDate <= $dates['sem1End']) {
                $this->semester = 1;
            } elseif ($currentDate >= $dates['sem2Start'] && $currentDate <= $dates['sem2End']) {
                $this->semester = 2;
            }

            if ($this->semester == 1) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
            } elseif ($this->semester == 2) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
            }

            $this->loopPlantedSeeds = true;
        }

        if ($this->loopPlantedSeeds) {
            if ($plantedSeeds) {
                foreach ($plantedSeeds as $item) {
                    // Remove NSIC prefix from NSIC varieties to match with NSIC API
                    // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                    $variety = str_replace("NSIC ", "", $item->variety);
                    $variety = str_replace("Rc ", "Rc", $variety);

                    $seed = $this->findSeedByName($variety);

                    if ($seed) {
                        switch ($status) {
                            case "Fresh":
                                // Fresh planted data
                                if (array_key_exists($seed->variety, $data)) {
                                    // Add to current index if seed variety exists inside array
                                    $data[$seed->variety]['quantity'] += $item->areaplanted * $seed->ave_yld;
                                } else {
                                    // Create new index if seed variety doesn't exist inside the array
                                    $data[$seed->variety]['variety'] = $seed->variety;
                                    $data[$seed->variety]['quantity'] = $item->areaplanted * $seed->ave_yld;
                                }
                                break;
                            case "Dried":
                                // Dried planted data
                                if (array_key_exists($seed->variety, $data)) {
                                    // Add to current index if seed variety exists inside array
                                    $data[$seed->variety]['quantity'] += ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                } else {
                                    // Create new index if seed variety doesn't exist inside the array
                                    $data[$seed->variety]['variety'] = $seed->variety;
                                    $data[$seed->variety]['quantity'] = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                }
                                break;
                            case "Cleaned":
                                // Cleaned planted data
                                if (array_key_exists($seed->variety, $data)) {
                                    // Add to current index if seed variety exists inside array
                                    $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                } else {
                                    // Create new index if seed variety doesn't exist inside the array
                                    $data[$seed->variety]['variety'] = $seed->variety;
                                    $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                }
                                break;
                            case "Tagged":
                                // Tagged planted data
                                if (array_key_exists($seed->variety, $data)) {
                                    // Add to current index if seed variety exists inside array
                                    $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    $cleaned = $dried * $filters['cln_weight_per'];
                                    $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                } else {
                                    // Create new index if seed variety doesn't exist inside the array
                                    $data[$seed->variety]['variety'] = $seed->variety;
                                    $dried = ($item->areaplanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                    $cleaned = $dried * $filters['cln_weight_per'];
                                    $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                }
                            default:
                                break;
                        }
                    }
                }
            }
        }

        return $data;
    }

    // Preliminary inspection
    public function prelimInspection($filters, $serialNum, $SPIData) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;
 
        if ($filters['year'] != 0) {
            $selectedYear = $filters['year'];

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($selectedYear);

            if ($filters['sem'] == "1") {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                $this->loopPlantedSeeds = true;
            } elseif ($filters['sem'] == "2") {
                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                $this->loopPlantedSeeds = true;
            } else {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                // Query data for sem 2
                $plantedSeeds2 = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC2 = $filters['init_MC_WS'];

                foreach ($SPIData as $key => $value) {
                    $currSerialNum = $value['SerialNum'];
                    $currTrackingID = $value['TrackingID'];
                    $currVariety = $value['Variety'];
                    $currAreaPlanted = $value['AreaPlanted'];

                    if ($plantedSeeds) {
                        foreach ($plantedSeeds as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    // Fresh weight
                                    $freshWeight = $currAreaPlanted * $seed->ave_yld;

                                    $fresh += $freshWeight;

                                    // Dried weight
                                    $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                                }
                            }
                        }
                    }

                    if ($plantedSeeds2) {
                        foreach ($plantedSeeds2 as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    // Fresh weight
                                    $freshWeight = $currAreaPlanted * $seed->ave_yld;

                                    $fresh += $freshWeight;

                                    // Dried weight
                                    $dried += $freshWeight * ((100 - $this->initMC2) / $filters['multiplier']);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            // No filter for semester
            // Get current semester
            $currentDate = date('Y-m-d');

            $currentYear = date('Y');

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($currentYear);

            if ($currentDate >= $dates['sem1Start'] && $currentDate <= $dates['sem1End']) {
                $this->semester = 1;
            } elseif ($currentDate >= $dates['sem2Start'] && $currentDate <= $dates['sem2End']) {
                $this->semester = 2;
            }

             if ($this->semester == 1) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
             } elseif ($this->semester == 2) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
             }

             $this->loopPlantedSeeds = true;
        }

        if ($this->loopPlantedSeeds) {
            foreach ($SPIData as $key => $value) {
                $currSerialNum = $value['SerialNum'];
                $currTrackingID = $value['TrackingID'];
                $currVariety = $value['Variety'];
                $currAreaPlanted = $value['AreaPlanted'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        if ($item->trackingid == $currTrackingID) {
                            // Remove NSIC prefix from NSIC varieties to match with NSIC API
                            // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                            $variety = str_replace("NSIC ", '', $item->variety);
                            $variety = str_replace("Rc ", "Rc", $variety);

                            $seed = $this->findSeedByName($variety);

                            if ($seed) {
                                // Fresh weight
                                $freshWeight = $currAreaPlanted * $seed->ave_yld;

                                $fresh += $freshWeight;

                                // Dried weight
                                $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                            }
                        }
                    }
                }
            }
        }

        // Cleaned weight
        $cleaned += $dried * $filters['cln_weight_per'];

        // Tagged weight
        $tagged += $cleaned * $filters['tgd_weight_per'];

        $data = array(
            'fresh' => $fresh,
            'dried' => $dried,
            'cleaned' => $cleaned,
            'tagged' => $tagged
        );

        return $data;
    }

    // Preliminary inspection drill down
    public function prelimInspectionDD($filters, $serialNum, $SPIData, $status) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;
        $data = array();

        if ($filters['year'] != 0) {
            $selectedYear = $filters['year'];

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($selectedYear);

            if ($filters['sem'] == "1") {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                $this->loopPlantedSeeds = true;
            } elseif ($filters['sem'] == "2") {
                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                $this->loopPlantedSeeds = true;
            } else {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                // Query data for sem 2
                $plantedSeeds2 = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC2 = $filters['init_MC_WS'];

                foreach ($SPIData as $key => $value) {
                    $currSerialNum = $value['SerialNum'];
                    $currTrackingID = $value['TrackingID'];
                    $currVariety = $value['Variety'];
                    $currAreaPlanted = $value['AreaPlanted'];

                    if ($plantedSeeds) {
                        foreach ($plantedSeeds as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    switch ($status) {
                                        case "Fresh":
                                            // Fresh planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += $currAreaPlanted * $seed->ave_yld;
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = $currAreaPlanted * $seed->ave_yld;
                                            }
                                            break;
                                        case "Dried":
                                            // Dried planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            }
                                            break;
                                        case "Cleaned":
                                            // Cleaned planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                            }
                                            break;
                                        case "Tagged":
                                            // Tagged planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                            }
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                    }

                    if ($plantedSeeds2) {
                        foreach ($plantedSeeds2 as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    switch ($status) {
                                        case "Fresh":
                                            // Fresh planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += $currAreaPlanted * $seed->ave_yld;
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = $currAreaPlanted * $seed->ave_yld;
                                            }
                                            break;
                                        case "Dried":
                                            // Dried planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            }
                                            break;
                                        case "Cleaned":
                                            // Cleaned planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                            }
                                            break;
                                        case "Tagged":
                                            // Tagged planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                            }
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            // No filter for semester
            // Get current semester
            $currentDate = date('Y-m-d');

            $currentYear = date('Y');

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($currentYear);

            if ($currentDate >= $dates['sem1Start'] && $currentDate <= $dates['sem1End']) {
                $this->semester = 1;
            } elseif ($currentDate >= $dates['sem2Start'] && $currentDate <= $dates['sem2End']) {
                $this->semester = 2;
            }

            if ($this->semester == 1) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
            } elseif ($this->semester == 2) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
            }

            $this->loopPlantedSeeds = true;
        }

        if ($this->loopPlantedSeeds) {
            foreach ($SPIData as $key => $value) {
                $currSerialNum = $value['SerialNum'];
                $currTrackingID = $value['TrackingID'];
                $currVariety = $value['Variety'];
                $currAreaPlanted = $value['AreaPlanted'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        if ($item->trackingid == $currTrackingID) {
                            // Remove NSIC prefix from NSIC varieties to match with NSIC API
                            // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                            $variety = str_replace("NSIC ", '', $item->variety);
                            $variety = str_replace("Rc ", "Rc", $variety);

                            $seed = $this->findSeedByName($variety);

                            if ($seed) {
                                switch ($status) {
                                    case "Fresh":
                                        // Fresh planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $data[$seed->variety]['quantity'] += $currAreaPlanted * $seed->ave_yld;
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $data[$seed->variety]['quantity'] = $currAreaPlanted * $seed->ave_yld;
                                        }
                                        break;
                                    case "Dried":
                                        // Dried planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $data[$seed->variety]['quantity'] += ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $data[$seed->variety]['quantity'] = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        }
                                        break;
                                    case "Cleaned":
                                        // Cleaned planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                        }
                                        break;
                                    case "Tagged":
                                        // Tagged planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $cleaned = $dried * $filters['cln_weight_per'];
                                            $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $cleaned = $dried * $filters['cln_weight_per'];
                                            $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                        }
                                    default:
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    // Final inspection
    public function finalInspection($filters, $serialNum, $SPFIData) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;

        if ($filters['year'] != 0) {
            $selectedYear = $filters['year'];

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($selectedYear);

            if ($filters['sem'] == "1") {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                $this->loopPlantedSeeds = true;
            } elseif ($filters['sem'] == "2") {
                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                $this->loopPlantedSeeds = true;
            } else {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                // Query data for sem 2
                $plantedSeeds2 = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC2 = $filters['init_MC_WS'];

                foreach ($SPFIData as $key => $value) {
                    $currSerialNum = $value['SerialNum'];
                    $currTrackingID = $value['TrackingID'];
                    $currVariety = $value['Variety'];
                    $currAreaPlanted = $value['AreaPlanted'];

                    if ($plantedSeeds) {
                        foreach ($plantedSeeds as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    // Fresh weight
                                    $freshWeight = $currAreaPlanted * $seed->ave_yld;

                                    $fresh += $freshWeight;

                                    // Dried weight
                                    $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                                }
                            }
                        }
                    }

                    if ($plantedSeeds2) {
                        foreach ($plantedSeeds2 as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    // Fresh weight
                                    $freshWeight = $currAreaPlanted * $seed->ave_yld;

                                    $fresh += $freshWeight;

                                    // Dried weight
                                    $dried += $freshWeight * ((100 - $this->initMC2) / $filters['multiplier']);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            // No filter for semester
            // Get current semester
            $currentDate = date('Y-m-d');

            $currentYear = date('Y');

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($currentYear);

            if ($currentDate >= $dates['sem1Start'] && $currentDate <= $dates['sem1End']) {
                $this->semester = 1;
            } elseif ($currentDate >= $dates['sem2Start'] && $currentDate <= $dates['sem2End']) {
                $this->semester = 2;
            }

             if ($this->semester == 1) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
             } elseif ($this->semester == 2) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
             }

             $this->loopPlantedSeeds = true;
        }

        if ($this->loopPlantedSeeds) {
            foreach ($SPFIData as $key => $value) {
                $currSerialNum = $value['SerialNum'];
                $currTrackingID = $value['TrackingID'];
                $currVariety = $value['Variety'];
                $currAreaPlanted = $value['AreaPlanted'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        if ($item->trackingid == $currTrackingID) {
                            // Remove NSIC prefix from NSIC varieties to match with NSIC API
                            // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                            $variety = str_replace("NSIC ", '', $item->variety);
                            $variety = str_replace("Rc ", "Rc", $variety);

                            $seed = $this->findSeedByName($variety);

                            if ($seed) {
                                // Fresh weight
                                $freshWeight = $currAreaPlanted * $seed->ave_yld;

                                $fresh += $freshWeight;

                                // Dried weight
                                $dried += $freshWeight * ((100 - $this->initMC) / $filters['multiplier']);
                            }
                        }
                    }
                }
            }
        }

        // Cleaned weight
        $cleaned += $dried * $filters['cln_weight_per'];

        // Tagged weight
        $tagged += $cleaned * $filters['tgd_weight_per'];

        $data = array(
            'fresh' => $fresh,
            'dried' => $dried,
            'cleaned' => $cleaned,
            'tagged' => $tagged
        );

        return $data;
    }

    // Final inspection drill down
    public function finalInspectionDD($filters, $serialNum, $SPFIData, $status) {
        $fresh = 0;
        $dried = 0;
        $cleaned = 0;
        $tagged = 0;
        $data = array();

        if ($filters['year'] != 0) {
           $selectedYear = $filters['year'];
           
           // Get dates of sem 1 and sem 2 of selected year
           $dates = $this->semDates($selectedYear);

            if ($filters['sem'] == "1") {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                $this->loopPlantedSeeds = true;
            } elseif ($filters['sem'] == "2") {
                // Query data for sem 2
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];

                $this->loopPlantedSeeds = true;
            } else {
                // Query data for sem 1
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];

                // Query data for sem 2
                $plantedSeeds2 = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC2 = $filters['init_MC_WS'];

                foreach ($SPFIData as $key => $value) {
                    $currSerialNum = $value['SerialNum'];
                    $currTrackingID = $value['TrackingID'];
                    $currVariety = $value['Variety'];
                    $currAreaPlanted = $value['AreaPlanted'];

                    if ($plantedSeeds) {
                        foreach ($plantedSeeds as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    switch ($status) {
                                        case "Fresh":
                                            // Fresh planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += $currAreaPlanted * $seed->ave_yld;
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = $currAreaPlanted * $seed->ave_yld;
                                            }
                                            break;
                                        case "Dried":
                                            // Dried planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            }
                                            break;
                                        case "Cleaned":
                                            // Cleaned planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                            }
                                            break;
                                        case "Tagged":
                                            // Tagged planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                            }
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                    }

                    if ($plantedSeeds2) {
                        foreach ($plantedSeeds2 as $item) {
                            if ($item->trackingid == $currTrackingID) {
                                // Remove NSIC prefix from NSIC varieties to match with NSIC API
                                // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                                $variety = str_replace("NSIC ", '', $item->variety);
                                $variety = str_replace("Rc ", "Rc", $variety);

                                $seed = $this->findSeedByName($variety);

                                if ($seed) {
                                    switch ($status) {
                                        case "Fresh":
                                            // Fresh planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += $currAreaPlanted * $seed->ave_yld;
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = $currAreaPlanted * $seed->ave_yld;
                                            }
                                            break;
                                        case "Dried":
                                            // Dried planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $data[$seed->variety]['quantity'] += ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $data[$seed->variety]['quantity'] = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            }
                                            break;
                                        case "Cleaned":
                                            // Cleaned planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                            }
                                            break;
                                        case "Tagged":
                                            // Tagged planted data
                                            if (array_key_exists($seed->variety, $data)) {
                                                // Add to current index if seed variety exists inside array
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                            } else {
                                                // Create new index if seed variety doesn't exist inside the array
                                                $data[$seed->variety]['variety'] = $seed->variety;
                                                $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                                $cleaned = $dried * $filters['cln_weight_per'];
                                                $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                            }
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            // No filter for semester
            // Get current semester
            $currentDate = date('Y-m-d');

            $currentYear = date('Y');

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($currentYear);

            if ($currentDate >= $dates['sem1Start'] && $currentDate <= $dates['sem1End']) {
                $this->semester = 1;
            } elseif ($currentDate >= $dates['sem2Start'] && $currentDate <= $dates['sem2End']) {
                $this->semester = 2;
            }

             if ($this->semester == 1) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem1Start'], $dates['sem1End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
             } elseif ($this->semester == 2) {
                $plantedSeeds = $this->GrowAppQuery($dates['sem2Start'], $dates['sem2End'], $serialNum);

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
             }

             $this->loopPlantedSeeds = true;
        }

        if ($this->loopPlantedSeeds) {
            foreach ($SPFIData as $key => $value) {
                $currSerialNum = $value['SerialNum'];
                $currTrackingID = $value['TrackingID'];
                $currVariety = $value['Variety'];
                $currAreaPlanted = $value['AreaPlanted'];

                if ($plantedSeeds) {
                    foreach ($plantedSeeds as $item) {
                        if ($item->trackingid == $currTrackingID) {
                            // Remove NSIC prefix from NSIC varieties to match with NSIC API
                            // Because NSIC API doesn't have year included in NSIC API naming but PhilRice database has
                            $variety = str_replace("NSIC ", '', $item->variety);
                            $variety = str_replace("Rc ", "Rc", $variety);

                            $seed = $this->findSeedByName($variety);

                            if ($seed) {
                                switch ($status) {
                                    case "Fresh":
                                        // Fresh planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $data[$seed->variety]['quantity'] += $currAreaPlanted * $seed->ave_yld;
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $data[$seed->variety]['quantity'] = $currAreaPlanted * $seed->ave_yld;
                                        }
                                        break;
                                    case "Dried":
                                        // Dried planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $data[$seed->variety]['quantity'] += ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $data[$seed->variety]['quantity'] = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                        }
                                        break;
                                    case "Cleaned":
                                        // Cleaned planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $data[$seed->variety]['quantity'] += $dried * $filters['cln_weight_per'];
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $data[$seed->variety]['quantity'] = $dried * $filters['cln_weight_per'];
                                        }
                                        break;
                                    case "Tagged":
                                        // Tagged planted data
                                        if (array_key_exists($seed->variety, $data)) {
                                            // Add to current index if seed variety exists inside array
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $cleaned = $dried * $filters['cln_weight_per'];
                                            $data[$seed->variety]['quantity'] += $cleaned * $filters['tgd_weight_per'];
                                        } else {
                                            // Create new index if seed variety doesn't exist inside the array
                                            $data[$seed->variety]['variety'] = $seed->variety;
                                            $dried = ($currAreaPlanted * $seed->ave_yld) * ((100 - $this->initMC) / $filters['multiplier']);
                                            $cleaned = $dried * $filters['cln_weight_per'];
                                            $data[$seed->variety]['quantity'] = $cleaned * $filters['tgd_weight_per'];
                                        }
                                    default:
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    // Returns dates of sem 1 and sem 2 of given year
    public function semDates($year) {
        $lastYear = $year - 1;

        // Get sem 1 start and end dates
        $sem1Start = '' . $lastYear . '-09-16';
        $sem1End = '' . $year . '-03-15';

        // Get sem 2 start and end dates
        $sem2Start = '' . $year . '-03-16';
        $sem2End = '' . $year . '-09-15';

        $dates = array(
            'sem1Start' => $sem1Start,
            'sem1End' => $sem1End,
            'sem2Start' => $sem2Start,
            'sem2End' => $sem2End
        );

        return $dates;
    }

    // Find seed using variety ID
    public function findSeedByID($varietyID) {
        $seed = DB::connection('seeds')
            ->table('seed_characteristics')
            ->select('ave_yld', 'variety')
            ->where('id', $varietyID)
            ->first();

        return $seed;
    }

    // Find seed using name
    public function findSeedByName($variety) {
        $seed = DB::connection('seeds')
            ->table('seed_characteristics')
            ->select('ave_yld', 'variety')
            ->where('variety', 'LIKE', '%'.$variety.'')
            ->where('variety_name', 'NOT LIKE', '%DSWR%')
            ->first();

        return $seed;
    }

    // Returns GrowApp data
    public function GrowAppQuery($startDate, $endDate, $serialNum) {
        $query = "SELECT quantity, variety, accredno, serial_number, areaplanted, trackingid FROM grow_app.sg_forms WHERE CAST(dateplanted as DATE) >= '" . $startDate . "' AND CAST(dateplanted as DATE) <= '" . $endDate . "' AND serial_number = '" . $serialNum . "' AND is_test_data = 0 OR accredno = '" .$serialNum. "';";

        $data = DB::select($query);

        return $data;
    }

    // Returns list of seed production years of seed grower
    public function seedProdYears() {
        // Get logged in user's serial number in SG API
        $serialNum = $this->SGSerialNum();

        $years = DB::connection('grow_app')
            ->table('sg_forms')
            ->select('dateplanted')
            ->where('serial_number', $serialNum)
            ->orWhere('accredno', $serialNum)
            ->distinct()
            ->get(); 

        return $years;
    }

    // Returns orders
    public function orders($filters) {
        // $orders = DB::connection('seed_ordering')
        //     ->table('tbl_planting_season')
        //     ->select('order_id', 'tbl_name', 'sem');

        $orders = DB::connection('seed_ordering')
                    ->table('tbl_planting_season')
                    ->select('order_id', 'pallet_plan_id', 'sem');

        // Add where clause if year filter has value
        if ($filters['year'] != 0) {
            $orders = $orders->where('year', $filters['year']);
            $this->yearFilterOnly = true;
        } else {
            $this->yearFilterOnly = false;
        }

        // Add where clause if sem filter has value
        if ($filters['sem'] != 0) {
            if ($filters['sem'] == 1) {
                $orders = $orders->where('sem', '1st');

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
            } else {
                $orders = $orders->where('sem', '2nd');

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
            }
        }

        // Get current year and sem data if year and sem filter values are empty
        if ($filters['year'] == 0 && $filters['sem'] == 0) {
            // Get current semester
            $currentDate = date('Y-m-d');

            $currentYear = date('Y');

            // Get dates of sem 1 and sem 2 of selected year
            $dates = $this->semDates($currentYear);

            if ($currentDate >= $dates['sem1Start'] && $currentDate <= $dates['sem1End']) {
                $orders = $orders->where('year', '=', $currentYear)
                    ->where('sem', '=', '1st');

                // Initial MC
                $this->initMC = $filters['init_MC_DS'];
            } elseif ($currentDate >= $dates['sem2start'] && $currentDate <= $dates['sem2End']) {
                $orders = $orders->where('year', '=', $currentYear)
                    ->where('sem', '=', '2nd');

                // Initial MC
                $this->initMC = $filters['init_MC_WS'];
            }
        }

        $orders = $orders->get();

        return $orders;
    }
}
