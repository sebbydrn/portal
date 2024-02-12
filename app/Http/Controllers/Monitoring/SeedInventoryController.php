<?php

namespace App\Http\Controllers\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PalletPlan;
use App\Warehouse;
use App\PhilRiceStation;
use App\Schema;
use App\Seeds;
use App\SeedInventoryEmailReceiver;
use App\Helpers\DatabaseConnection;
use App\User;
use PHPMailer\PHPMailer;

class SeedInventoryController extends Controller
{
    public function index() {
        // Get active pallet plans
        $palletPlans = PalletPlan::select('warehouse_id', 'year', 'semester')
                                ->where('status', '=', 1)
                                ->get();

        $data = array();

        foreach ($palletPlans as $palletPlan) {

            $warehouseID = $palletPlan->warehouse_id;
            $year = $palletPlan->year;
            $semester = $palletPlan->semester;

            // Get stationID
            $warehouse = Warehouse::select('station_id')
                                    ->where('warehouse_id', '=', $warehouseID)
                                    ->first();

            if ($warehouse) {
                $stationID = $warehouse->station_id;

                // Get station
                $station = PhilRiceStation::select('name')
                                    ->where('philrice_station_id', '=', $stationID)
                                    ->first();

                $stationName = $station->name;

                // Get schema
                $schema = Schema::where('station_id', $stationID)->first();
                $schema_name = $schema->name;

                // Set database connection
                $connection = DatabaseConnection::setDBConnection($schema_name);

                // Stocks table name
                $year = $palletPlan->year;
                $semester = $palletPlan->semester;
                $stocks_tbl_name = "tbl_sem".$semester."_".$year."_stocks";
                $stocks_activities_tbl_name = "tbl_sem".$semester."_".$year."_stock_activities";

                // Get stocks
                $stocks = $connection->table($stocks_tbl_name)
                                    ->select('variety', 'year_harvested', 'semester_harvested')
                                    ->groupBy('variety', 'year_harvested', 'semester_harvested')
                                    ->orderBy('variety', 'asc')
                                    ->get();

                $data[$stationName] = array();

                foreach ($stocks as $stock) {
                    $variety = $stock->variety;
                    $yearHarvested = $stock->year_harvested;
                    $semHarvested = $stock->semester_harvested;

                    $seed = Seeds::select('ecosystem', 'max_yld', 'maturity')
                                ->where([
                                    ['variety', '=', $variety],
                                    ['variety_name', 'NOT LIKE', '%DWSR%']
                                ])
                                ->first();

                    if ($seed) {
                        $ecosystem = $seed->ecosystem;
                        $maxYield = $seed->max_yld;
                        $maturity = $seed->maturity;
                    }

                    // Get total FS
                    $foundation = $connection->table($stocks_tbl_name)
                                            ->where([
                                                ['variety', '=', $variety],
                                                ['year_harvested', '=', $yearHarvested],
                                                ['semester_harvested', '=', $semHarvested],
                                                ['inbred_seed_class_id', '=', 3],
                                            ])
                                            ->where(function($query) {
                                                $query->where('seed_status_id', '=', 3)
                                                        ->orWhere('seed_status_id', '=', 4);
                                            })
                                            ->sum('available_stocks');

                    // get the last update of the foundation seeds of this variety
                    $stock_ids = $connection->table($stocks_tbl_name)
                    ->where([
                        ['variety', '=', $variety],
                        ['year_harvested', '=', $yearHarvested],
                        ['semester_harvested', '=', $semHarvested],
                        ['inbred_seed_class_id', '=', 3],
                    ])
                    ->where(function($query) {
                        $query->where('seed_status_id', '=', 3)
                                ->orWhere('seed_status_id', '=', 4);
                    })
                    ->pluck('stock_id')
                    ->toArray();

                    $foundation_update = "-";
                    $foundation_last_updated_by = "-";

                    if (!$stock_ids == null) {
                        $foundation_update = $connection->table($stocks_activities_tbl_name)
                        ->select('user_id', 'timestamp', 'activity')
                        ->whereIn('stock_id', $stock_ids)
                        ->where(function($query) {
                            $query->where('activity', '=', "Added new stock")
                            ->orWhere('activity', '=', "Added stock from previous semester")
                            ->orWhere('activity', '=', "Transferred Seeds")
                            ->orwhere('activity', '=', "Updated available stocks")
                            ->orWhere('activity', '=', "Deducted stock quantity");
                        })
                        ->orderBy('timestamp', 'DESC')
                        ->first();

                        $user = User::select('firstname', 'lastname')
                        ->where('user_id', '=', $foundation_update->user_id)
                        ->first();

                        $foundation_update = date('M d, Y g:i A', strtotime($foundation_update->timestamp));
                        $foundation_last_updated_by = strtoupper($user->firstname . ' ' . $user->lastname);
                    }

                    // Get total RS
                    $registered = $connection->table($stocks_tbl_name)
                                            ->where([
                                                ['variety', '=', $variety],
                                                ['year_harvested', '=', $yearHarvested],
                                                ['semester_harvested', '=', $semHarvested],
                                                ['inbred_seed_class_id', '=', 4],
                                            ])
                                            ->where(function($query) {
                                                $query->where('seed_status_id', '=', 3)
                                                        ->orWhere('seed_status_id', '=', 4);
                                            })
                                            ->sum('available_stocks');

                    // get the last update of the registered seeds of this variety
                    $stock_ids = $connection->table($stocks_tbl_name)
                    ->where([
                        ['variety', '=', $variety],
                        ['year_harvested', '=', $yearHarvested],
                        ['semester_harvested', '=', $semHarvested],
                        ['inbred_seed_class_id', '=', 4],
                    ])
                    ->where(function($query) {
                        $query->where('seed_status_id', '=', 3)
                                ->orWhere('seed_status_id', '=', 4);
                    })
                    ->pluck('stock_id')
                    ->toArray();

                    $registered_update = "-";
                    $registered_last_updated_by = "-";
                    
                    if (!$stock_ids == null) {
                        $registered_update = $connection->table($stocks_activities_tbl_name)
                        ->select('user_id', 'timestamp', 'activity')
                        ->whereIn('stock_id', $stock_ids)
                        ->where(function($query) {
                            $query->where('activity', '=', "Added new stock")
                            ->orWhere('activity', '=', "Added stock from previous semester")
                            ->orWhere('activity', '=', "Transferred Seeds")
                            ->orwhere('activity', '=', "Updated available stocks")
                            ->orWhere('activity', '=', "Deducted stock quantity");
                        })
                        ->orderBy('timestamp', 'DESC')
                        ->first();

                        $user = User::select('firstname', 'lastname')
                        ->where('user_id', '=', $registered_update->user_id)
                        ->first();

                        $registered_update = date('M d, Y g:i A', strtotime($registered_update->timestamp));
                        $registered_last_updated_by = strtoupper($user->firstname . ' ' . $user->lastname);
                    }

                    // Get total CS
                    $certified = $connection->table($stocks_tbl_name)
                                            ->where([
                                                ['variety', '=', $variety],
                                                ['year_harvested', '=', $yearHarvested],
                                                ['semester_harvested', '=', $semHarvested],
                                                ['inbred_seed_class_id', '=', 5],
                                            ])
                                            ->where(function($query) {
                                                $query->where('seed_status_id', '=', 3)
                                                        ->orWhere('seed_status_id', '=', 4);
                                            })
                                            ->sum('available_stocks');

                    // get the last update of the certified seeds of this variety
                    $stock_ids = $connection->table($stocks_tbl_name)
                    ->where([
                        ['variety', '=', $variety],
                        ['year_harvested', '=', $yearHarvested],
                        ['semester_harvested', '=', $semHarvested],
                        ['inbred_seed_class_id', '=', 5],
                    ])
                    ->where(function($query) {
                        $query->where('seed_status_id', '=', 3)
                                ->orWhere('seed_status_id', '=', 4);
                    })
                    ->pluck('stock_id')
                    ->toArray();

                    $certified_update = "-";
                    $certified_last_updated_by = "-";

                    if (!$stock_ids == null) {
                        $certified_update = $connection->table($stocks_activities_tbl_name)
                        ->select('user_id', 'timestamp', 'activity')
                        ->whereIn('stock_id', $stock_ids)
                        ->where(function($query) {
                            $query->where('activity', '=', "Added new stock")
                            ->orWhere('activity', '=', "Added stock from previous semester")
                            ->orWhere('activity', '=', "Transferred Seeds")
                            ->orwhere('activity', '=', "Updated available stocks")
                            ->orWhere('activity', '=', "Deducted stock quantity");
                        })
                        ->orderBy('timestamp', 'DESC')
                        ->first();

                        $user = User::select('firstname', 'lastname')
                        ->where('user_id', '=', $certified_update->user_id)
                        ->first();

                        $certified_update = date('M d, Y g:i A', strtotime($certified_update->timestamp));
                        $certified_last_updated_by = strtoupper($user->firstname . ' ' . $user->lastname);
                    }

                    if ($foundation > 0 || $registered > 0 || $certified > 0) {
                        $data[$stationName][] = array(
                            'variety' => $variety,
                            'year_harvested' => $yearHarvested,
                            'semester_harvested' => ($semHarvested == 1) ? "S1" : "S2",
                            'foundation' => number_format($foundation),
                            'registered' => number_format($registered),
                            'certified' => number_format($certified),
                            'ecosystem' => ($ecosystem) ? $ecosystem : "",
                            'maxYield' => ($maxYield) ? $maxYield : "",
                            'maturity' => ($maturity) ? $maturity : "",
                            'foundation_last_update' => $foundation_update,
                            'foundation_last_updated_by' => $foundation_last_updated_by,
                            'registered_last_update' => $registered_update,
                            'registered_last_updated_by' => $registered_last_updated_by,
                            'certified_last_update' => $certified_update,
                            'certified_last_updated_by' => $certified_last_updated_by,
                        );
                    }
                }
            }

        }

        ksort($data);

        // echo json_encode($data);

        // Email receivers
        $emails = SeedInventoryEmailReceiver::select('email', 'receive_type')->get();

        // SEND EMAIL
        $content = array();
        $content['data'] = $data;

        $res = $this->send_email($emails, $content);

        if ($res == "success") {
            echo "Success [".date('Y-m-d H:i:s')."] \n";
        } else {
            echo $res;
        }
    }

    // for selling
    public function seed_inventory_for_selling() {
        // Get active pallet plans
        $palletPlans = PalletPlan::select('warehouse_id', 'year', 'semester')
                                ->where('status', '=', 1)
                                ->get();

        $data = array();

        foreach ($palletPlans as $palletPlan) {

            $warehouseID = $palletPlan->warehouse_id;
            $year = $palletPlan->year;
            $semester = $palletPlan->semester;

            // Get stationID
            $warehouse = Warehouse::select('station_id')
                                    ->where('warehouse_id', '=', $warehouseID)
                                    ->first();

            if ($warehouse) {
                $stationID = $warehouse->station_id;

                // Get station
                $station = PhilRiceStation::select('name')
                                    ->where('philrice_station_id', '=', $stationID)
                                    ->first();

                $stationName = $station->name;

                // Get schema
                $schema = Schema::where('station_id', $stationID)->first();
                $schema_name = $schema->name;

                // Set database connection
                $connection = DatabaseConnection::setDBConnection($schema_name);

                // Stocks table name
                $year = $palletPlan->year;
                $semester = $palletPlan->semester;
                $stocks_tbl_name = "tbl_sem".$semester."_".$year."_stocks";
                $stocks_activities_tbl_name = "tbl_sem".$semester."_".$year."_stock_activities";

                // Get stocks
                $stocks = $connection->table($stocks_tbl_name)
                                    ->select('variety', 'year_harvested', 'semester_harvested')
                                    ->groupBy('variety', 'year_harvested', 'semester_harvested')
                                    ->orderBy('variety', 'asc')
                                    ->get();

                $data[$stationName] = array();

                foreach ($stocks as $stock) {
                    $variety = $stock->variety;
                    $yearHarvested = $stock->year_harvested;
                    $semHarvested = $stock->semester_harvested;

                    $seed = Seeds::select('ecosystem', 'max_yld', 'maturity')
                                ->where([
                                    ['variety', '=', $variety],
                                    ['variety_name', 'NOT LIKE', '%DWSR%']
                                ])
                                ->first();

                    if ($seed) {
                        $ecosystem = $seed->ecosystem;
                        $maxYield = $seed->max_yld;
                        $maturity = $seed->maturity;
                    }

                    // Get total FS
                    $foundation = $connection->table($stocks_tbl_name)
                                            ->where([
                                                ['variety', '=', $variety],
                                                ['year_harvested', '=', $yearHarvested],
                                                ['semester_harvested', '=', $semHarvested],
                                                ['inbred_seed_class_id', '=', 3],
                                            ])
                                            ->where(function($query) {
                                                $query->where('seed_status_id', '=', 3)
                                                        ->orWhere('seed_status_id', '=', 4);
                                            })
                                            ->sum('available_stocks');

                    // get the last update of the foundation seeds of this variety
                    $stock_ids = $connection->table($stocks_tbl_name)
                    ->where([
                        ['variety', '=', $variety],
                        ['year_harvested', '=', $yearHarvested],
                        ['semester_harvested', '=', $semHarvested],
                        ['inbred_seed_class_id', '=', 3],
                    ])
                    ->where(function($query) {
                        $query->where('seed_status_id', '=', 3)
                                ->orWhere('seed_status_id', '=', 4);
                    })
                    ->pluck('stock_id')
                    ->toArray();

                    $foundation_update = "-";
                    $foundation_last_updated_by = "-";

                    if (!$stock_ids == null) {
                        $foundation_update = $connection->table($stocks_activities_tbl_name)
                        ->select('user_id', 'timestamp', 'activity')
                        ->whereIn('stock_id', $stock_ids)
                        ->where(function($query) {
                            $query->where('activity', '=', "Added new stock")
                            ->orWhere('activity', '=', "Added stock from previous semester")
                            ->orWhere('activity', '=', "Transferred Seeds")
                            ->orwhere('activity', '=', "Updated available stocks")
                            ->orWhere('activity', '=', "Deducted stock quantity");
                        })
                        ->orderBy('timestamp', 'DESC')
                        ->first();

                        $user = User::select('fullname')
                        ->where('user_id', '=', $foundation_update->user_id)
                        ->first();

                        $foundation_update = date('M d, Y g:i A', strtotime($foundation_update->timestamp));
                        $foundation_last_updated_by = strtoupper($user->fullname);
                    }

                    // Get total RS
                    $registered = $connection->table($stocks_tbl_name)
                                            ->where([
                                                ['variety', '=', $variety],
                                                ['year_harvested', '=', $yearHarvested],
                                                ['semester_harvested', '=', $semHarvested],
                                                ['inbred_seed_class_id', '=', 4],
                                            ])
                                            ->where(function($query) {
                                                $query->where('seed_status_id', '=', 3)
                                                        ->orWhere('seed_status_id', '=', 4);
                                            })
                                            ->sum('available_stocks');

                    // get the last update of the registered seeds of this variety
                    $stock_ids = $connection->table($stocks_tbl_name)
                    ->where([
                        ['variety', '=', $variety],
                        ['year_harvested', '=', $yearHarvested],
                        ['semester_harvested', '=', $semHarvested],
                        ['inbred_seed_class_id', '=', 4],
                    ])
                    ->where(function($query) {
                        $query->where('seed_status_id', '=', 3)
                                ->orWhere('seed_status_id', '=', 4);
                    })
                    ->pluck('stock_id')
                    ->toArray();

                    $registered_update = "-";
                    $registered_last_updated_by = "-";
                    
                    if (!$stock_ids == null) {
                        $registered_update = $connection->table($stocks_activities_tbl_name)
                        ->select('user_id', 'timestamp', 'activity')
                        ->whereIn('stock_id', $stock_ids)
                        ->where(function($query) {
                            $query->where('activity', '=', "Added new stock")
                            ->orWhere('activity', '=', "Added stock from previous semester")
                            ->orWhere('activity', '=', "Transferred Seeds")
                            ->orwhere('activity', '=', "Updated available stocks")
                            ->orWhere('activity', '=', "Deducted stock quantity");
                        })
                        ->orderBy('timestamp', 'DESC')
                        ->first();

                        $user = User::select('fullname')
                        ->where('user_id', '=', $registered_update->user_id)
                        ->first();

                        $registered_update = date('M d, Y g:i A', strtotime($registered_update->timestamp));
                        $registered_last_updated_by = strtoupper($user->fullname);
                    }

                    // Get total CS
                    $certified = $connection->table($stocks_tbl_name)
                                            ->where([
                                                ['variety', '=', $variety],
                                                ['year_harvested', '=', $yearHarvested],
                                                ['semester_harvested', '=', $semHarvested],
                                                ['inbred_seed_class_id', '=', 5],
                                            ])
                                            ->where(function($query) {
                                                $query->where('seed_status_id', '=', 3)
                                                        ->orWhere('seed_status_id', '=', 4);
                                            })
                                            ->sum('available_stocks');

                    // get the last update of the certified seeds of this variety
                    $stock_ids = $connection->table($stocks_tbl_name)
                    ->where([
                        ['variety', '=', $variety],
                        ['year_harvested', '=', $yearHarvested],
                        ['semester_harvested', '=', $semHarvested],
                        ['inbred_seed_class_id', '=', 5],
                    ])
                    ->where(function($query) {
                        $query->where('seed_status_id', '=', 3)
                                ->orWhere('seed_status_id', '=', 4);
                    })
                    ->pluck('stock_id')
                    ->toArray();

                    $certified_update = "-";
                    $certified_last_updated_by = "-";

                    if (!$stock_ids == null) {
                        $certified_update = $connection->table($stocks_activities_tbl_name)
                        ->select('user_id', 'timestamp', 'activity')
                        ->whereIn('stock_id', $stock_ids)
                        ->where(function($query) {
                            $query->where('activity', '=', "Added new stock")
                            ->orWhere('activity', '=', "Added stock from previous semester")
                            ->orWhere('activity', '=', "Transferred Seeds")
                            ->orwhere('activity', '=', "Updated available stocks")
                            ->orWhere('activity', '=', "Deducted stock quantity");
                        })
                        ->orderBy('timestamp', 'DESC')
                        ->first();

                        $user = User::select('fullname')
                        ->where('user_id', '=', $certified_update->user_id)
                        ->first();

                        $certified_update = date('M d, Y g:i A', strtotime($certified_update->timestamp));
                        $certified_last_updated_by = strtoupper($user->fullname);
                    }

                    $data[$stationName][] = array(
                        'variety' => $variety,
                        'year_harvested' => $yearHarvested,
                        'semester_harvested' => ($semHarvested == 1) ? "S1" : "S2",
                        'foundation' => number_format($foundation),
                        'registered' => number_format($registered),
                        'certified' => number_format($certified),
                        'ecosystem' => ($ecosystem) ? $ecosystem : "",
                        'maxYield' => ($maxYield) ? $maxYield : "",
                        'maturity' => ($maturity) ? $maturity : "",
                        'foundation_last_update' => $foundation_update,
                        'foundation_last_updated_by' => $foundation_last_updated_by,
                        'registered_last_update' => $registered_update,
                        'registered_last_updated_by' => $registered_last_updated_by,
                        'certified_last_update' => $certified_update,
                        'certified_last_updated_by' => $certified_last_updated_by,
                    );
                }
            }

        }

        ksort($data);

        echo json_encode($data);

        // Email receivers
        // $emails = SeedInventoryEmailReceiver::select('email', 'receive_type')->get();

        // SEND EMAIL
        // $email = "lemdoronio.24@gmail.com";
        // $content = array();
        // $content['data'] = $data;

        // $res = $this->send_email($emails, $content);

        // if ($res == "success") {
        //     echo "Success [".date('Y-m-d H:i:s')."] \n";
        // } else {
        //     echo $res;
        // }
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
            
            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'PhilRice Seed Inventory as of ' . date('F d, Y');
            $mail->Body    = $this->email_content($content);
            $mail->AltBody = 'Please enable HTML content to view this email.';

            $mail->send();
            return "success";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function email_content($content) {
        return view('email.seed_inventory')->with($content);
    }

    public function seed_inventory_updates() {
        // Get active pallet plans
        $palletPlans = PalletPlan::select('warehouse_id', 'year', 'semester')
                                ->where('status', '=', 1)
                                ->get();

        $data = array();

        foreach ($palletPlans as $palletPlan) {

            $warehouseID = $palletPlan->warehouse_id;
            $year = $palletPlan->year;
            $semester = $palletPlan->semester;

            // Get stationID
            $warehouse = Warehouse::select('station_id')
                                    ->where('warehouse_id', '=', $warehouseID)
                                    ->first();

            if ($warehouse) {
                $stationID = $warehouse->station_id;

                // Get station
                $station = PhilRiceStation::select('name')
                                    ->where('philrice_station_id', '=', $stationID)
                                    ->first();

                $stationName = $station->name;

                // Get schema
                $schema = Schema::where('station_id', $stationID)->first();
                $schema_name = $schema->name;

                // Set database connection
                $connection = DatabaseConnection::setDBConnection($schema_name);

                // Stocks table name
                $year = $palletPlan->year;
                $semester = $palletPlan->semester;
                $stocks_tbl_name = "tbl_sem".$semester."_".$year."_stocks";
                $stocks_activities_tbl_name = "tbl_sem".$semester."_".$year."_stock_activities";

                $inventory = $connection->table($stocks_tbl_name)
                ->select('lot_no', 
                    'variety', 
                    'available_stocks', 
                    'year_harvested', 
                    'semester_harvested', 
                    'inbred_seed_class_id',
                    'stock_id'
                )
                ->where('seed_type_id', '=', 1)
                ->where(function($query) {
                    $query->where('seed_status_id', '=', 3)
                    ->orWhere('seed_status_id', '=', 4);
                })
                ->get();

                foreach ($inventory as $item) {
                    $activity = $connection->table($stocks_activities_tbl_name)
                    ->select('user_id', 'activity', 'timestamp')
                    ->where('stock_id', '=', $item->stock_id)
                    ->where(function($query) {
                        $query->where('activity', '=', "Added new stock")
                        ->orWhere('activity', '=', "Added stock from previous semester")
                        ->orWhere('activity', '=', "Transferred Seeds")
                        ->orwhere('activity', '=', "Updated available stocks")
                        ->orWhere('activity', '=', "Deducted stock quantity");
                    })
                    ->orderBy('timestamp', 'DESC')
                    ->first();

                    $user = User::select('fullname')
                    ->where('user_id', '=', $activity->user_id)
                    ->first();

                    $date_harvested = $item->year_harvested; 
                    $date_harvested .= ($item->semester_harvested == 1) ? " S1" : " S2";

                    if ($item->inbred_seed_class_id == 2)
                        $seed_class = "BS";
                    else if ($item->inbred_seed_class_id == 3)
                        $seed_class = "FS";
                    else if ($item->inbred_seed_class_id == 4)
                        $seed_class = "RS";
                    else if ($item->inbred_seed_class_id == 5)
                        $seed_class = "CS";

                    $data[$stationName][] = array(
                        'variety' => $item->variety,
                        'seed_class' => $seed_class,
                        'lot_no' => $item->lot_no,
                        'year_sem_harvested' => $date_harvested,
                        'available_stocks' => $item->available_stocks,
                        'last_update' => date('M d, Y g:i A', strtotime($activity->timestamp)),
                        'updated_by' => strtoupper($user->fullname)
                    );
                }
            }
        }

        echo json_encode($data);
    }
}
