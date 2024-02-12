<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Dashboard extends Model
{
	
	function __construct() {
		$this->recovery = 1 - ((27 - 12) / 100);
	}

    // Get active transactions table in warehouse database
	function active_transaction_tbl() {
		$transaction_tbl = DB::connection('warehouse')
		->table('tbl_logs')
		->select('tblName')
		->where('tblName', 'like', '%tbl_release_pur_%')
		->where('isActive', 1)
		->get();

		return $transaction_tbl;
	}

	// Get active stocks table in warehouse database
	function active_stocks_tbl($initial_mc, $stationid) {
		$station = DB::connection('rsis')
		->table('lib_stations')
		->select('stationcode')
		->where('id', $stationid)
		->first();

		$stocks_tbl = DB::connection('warehouse')
		->table('tbl_logs')
		->select('tblName')
		// ->where('tblName', 'like', '%tbl_stocks_'.strtolower($station->stationcode).'%')
		->where('tblName', 'like', '%tbl_stocks_%')
		->where('isActive', 1)
		->get();

		return $stocks_tbl;
	}

	// Get purchased seeds volume
	function purchased_seeds($table_transactions, $table_stocks, $accreditation_no, $initial_mc) {
		$purchased_seeds = DB::connection('warehouse')
		->table($table_transactions . ' as transactions')
		->leftJoin($table_stocks . ' as stocks', 'stocks.palletCode', '=', 'transactions.pallet_code')
		->select('transactions.quantity', 'transactions.accred_no', 'stocks.seedVarietyId')
		->where('transactions.status', "Released")
		->where('transactions.accred_no', $accreditation_no)
		->get();

		$fresh = 0;
		$processed = 0;
		
		if ($purchased_seeds) {
			foreach ($purchased_seeds as $item) {
	    		$seed = DB::connection('seeds')
							->table('seed_characteristics')
							->select('ave_yld')
							->where('id', $item->seedVarietyId)
							->where('variety_name', 'NOT LIKE', '%DWSR%')
							->first();

							$fresh += (($item->quantity / 40) * $seed->ave_yld) * 1000;
			}
		}

		/* RECOVERY FORMULA */
		$recovery = 1 - (($initial_mc - 12) / 100);
		$processed = $fresh * $recovery;

		$data = array(
			'fresh' => $fresh,
			'processed' => $processed
		);

		return $data;
	}

	// Get planted seeds volume
	function planted_seeds($accreditation_number, $initial_mc) {
		$planted_seeds = DB::connection('grow_app')
		->table('sg_forms')
		->select('quantity', 'variety', 'accredno', 'dateplanted')
		->where('accredno', $accreditation_number)
		->get();

		$fresh = 0;
		$processed = 0;


		if ($planted_seeds) {
			foreach ($planted_seeds as $item) {
				$seed = DB::connection('seeds')
							->table('seed_characteristics')
							->select('ave_yld')
							->where('variety', $item->variety)
							->where('variety_name', 'NOT LIKE', '%DWSR%')
							->first();

							$fresh += (($item->quantity / 40) * $seed->ave_yld) * 1000;
			}
		}


		/* RECOVERY FORMULA */
		$recovery = 1 - (($initial_mc - 12) / 100);
		$processed = $fresh * $recovery;

		$data = array(
			'fresh' => $fresh,
			'processed' => $processed
		);

		return $data;
	}

	// Get production area
	function production_area($accreditation_number) {
		$production_area = DB::connection('grow_app')
		->table('sg_forms')
		->select('*')
		->where('accredno', $accreditation_number)
		->get();

		$data = array();

		if ($production_area) {
			foreach ($production_area as $item) {

				$seed = DB::connection('seeds')
							->table('seed_characteristics')
							->select('ave_yld')
							->where('variety', $item->variety)
							->where('variety_name', 'NOT LIKE', '%DWSR%')
							->first();

							$data[] = array(
								'latitude' => $item->latitude,
				                'longitude' => $item->longitude,
				                'seed_class' => $item->seedclass,
				                'accreditation_number' => $item->accredno,
				                'variety' => $item->variety,
				                'area_planted' => $item->areaplanted,
				                'ave_yield' => $seed->ave_yld,
							);
			}
		}

		return $data;
	}

	// Get seed producer
	function seed_producer($accreditation_number) {
		$seed_producer = DB::connection('producers')
		->table('seed_growers_all')
		->select('*')
		->where('Code_Number', $accreditation_number)
		->first();

		return $seed_producer;
	}

	// Get seed variety
	function seed_variety($variety) {
		$variety = DB::connection('seeds')
		->table('seed_characteristics')
		->select('*')
		->where('variety', $variety)
		->where('variety_name', 'NOT LIKE', '%DWSR%')
		->first();

		return $variety;
	}

	// Drill down data query for purchased seeds
	function purchased_seeds_dd($table_transactions, $table_stocks, $status, $accreditation_number) {
		$purchased_seeds = DB::connection('warehouse')
		->table($table_transactions . ' as transactions')
		->leftJoin($table_stocks . ' as stocks', 'stocks.palletCode', '=', 'transactions.pallet_code')
		->select('transactions.quantity', 'transactions.accred_no', 'stocks.seedVarietyId')
		->where('transactions.status', 'Released')
		->where('transactions.accred_no', $accreditation_number)
		->get();

		$data = array();

		if ($purchased_seeds) {
			foreach ($purchased_seeds as $item) {

				$seed = DB::connection('seeds')
							->table('seed_characteristics')
							->select('ave_yld', 'variety')
							->where('id', $item->seedVarietyId)
							->where('variety_name', 'NOT LIKE', '%DWSR%')
							->first();

							if ($status == "Fresh") {
								// Check if variety index exists in the array
								if (array_key_exists($seed->variety, $data)) {
									$data[$seed->variety]['quantity'] += (($item->quantity / 40) * $seed->ave_yld) * 1000;
								} else {
									$data[$seed->variety]['variety'] = $seed->variety;
									$data[$seed->variety]['quantity'] = (($item->quantity / 40) * $seed->ave_yld) * 1000;
								}
							} elseif ($status == "Processed") {
								if (array_key_exists($seed->variety, $data)) {
									$data[$seed->variety]['quantity'] += ((($item->quantity / 40) * $seed->ave_yld) * 1000) * $this->recovery;
								} else {
									$data[$seed->variety]['variety'] = $seed->variety;
									$data[$seed->variety]['quantity'] = ((($item->quantity / 40) * $seed->ave_yld) * 1000) * $this->recovery;
								}
							}
			}
		}

		return $data;
	}

	// Planted seeds drill down query
	function planted_seeds_dd($status, $accreditation_number) {
		$planted_seeds = DB::connection('grow_app')
		->table('sg_forms')
		->select('quantity', 'variety', 'accredno', 'dateplanted')
		->where('accredno', $accreditation_number)
		->get();

		$data = array();

		if ($planted_seeds) {
			foreach ($planted_seeds as $item) {

				$seed = DB::connection('seeds')
							->table('seed_characteristics')
							->select('ave_yld', 'variety')
							->where('variety', $item->variety)
							->where('variety_name', 'NOT LIKE', '%DWSR%')
							->first(); 

							if ($status == "Fresh") {
								if (array_key_exists($seed->variety, $data)) {
									$data[$seed->variety]['quantity'] += (($item->quantity / 40) * $seed->ave_yld) * 1000;
								} else {
									$data[$seed->variety]['variety'] = $seed->variety;
									$data[$seed->variety]['quantity'] = (($item->quantity / 40) * $seed->ave_yld) * 1000;
								}
							} elseif ($status == "Processed") {
								if (array_key_exists($seed->variety, $data)) {
									$data[$seed->variety]['quantity'] += ((($item->quantity / 40) * $seed->ave_yld) * 1000) * $this->recovery;
								} else {
									$data[$seed->variety]['variety'] = $seed->variety;
									$data[$seed->variety]['quantity'] = ((($item->quantity / 40) * $seed->ave_yld) * 1000) * $this->recovery;
								}
							}

				
			}
		}

		return $data;
	}

	/*function inspected_seeds_dd($filter, $inspection, $status) {
		$inspected = DB::connection('inspection')
		->table('tbl_values')
		->select('Area_culture_in_ha', 'Seed_variety', 'Accreditation_number')
		->where('Inspection', $inspection);
		if ($filter['year'] != 0) {
            $inspected = $inspected->whereYear('Date_planted', $filter['year']);
        }
        if ($filter['season'] == "jj") {
            $inspected = $inspected->whereMonth('Date_planted', '>=', '01')
            ->whereMonth('Date_planted', '<=', '06');
        } elseif ($filter['season'] == "jd") {
            $inspected = $inspected->whereMonth('Date_planted', '>=', '07')
            ->whereMonth('Date_planted', '<=', '12');
        }
		$inspected = $inspected->get();

		$data = array();

		if ($inspected) {
			if ($filter['region_id'] != "0") {
				foreach ($inspected as $item) {
					$producer = DB::connection('producers')
		    		->table('seed_growers_all as producers')
		    		->leftJoin('regions', 'regions.id', '=', 'producers.regionId')
		    		->leftJoin('provinces', 'provinces.provinceId', '=', 'producers.provinceId')
		    		->leftJoin('municipalities as city', 'city.cityId', '=', 'producers.cityId')
		    		->select('producers.Name', 'producers.Code_Number', 'regions.region_name', 'provinces.province_name', 'city.municipality_name')
		    		->where('producers.Code_Number', $item->Accreditation_number);
		            if ($filter['region_id'] != 0) {
		                $producer = $producer->where('producers.regionId', $filter['region_id']);
		            }
		            if ($filter['province_id'] != 0) {
		                $producer = $producer->where('producers.provinceId', $filter['province_id']);
		            }
		            if ($filter['city_id'] != 0) {
		                $producer = $producer->where('producers.cityId', $filter['city_id']);
		            }
		    		$producer = $producer->first();

		    		if ($producer) {
		    			$seed = DB::connection('seeds')
						->table('seed_characteristics')
						->select('ave_yld', 'variety')
						->where('variety', $item->Seed_variety)
						->where('variety_name', 'NOT LIKE', '%DWSR%')
						->first();

						if ($status == "Fresh") {
							if (array_key_exists($seed->variety, $data)) {
								$data[$seed->variety]['quantity'] += ($item->Area_culture_in_ha * $seed->ave_yld) * 1000;
							} else {
								$data[$seed->variety]['variety'] = $seed->variety;
								$data[$seed->variety]['quantity'] = ($item->Area_culture_in_ha * $seed->ave_yld) * 1000;
							}
						} elseif ($status == "Processed") {
							if (array_key_exists($seed->variety, $data)) {
								$data[$seed->variety]['quantity'] += (($item->Area_culture_in_ha * $seed->ave_yld) * 1000) * $this->recovery;
							} else {
								$data[$seed->variety]['variety'] = $seed->variety;
								$data[$seed->variety]['quantity'] = (($item->Area_culture_in_ha * $seed->ave_yld) * 1000) * $this->recovery;
							}
						}
		    		}
				}
			} else {
				foreach ($inspected as $item) {
					$seed = DB::connection('seeds')
					->table('seed_characteristics')
					->select('ave_yld', 'variety')
					->where('variety', $item->Seed_variety)
					->where('variety_name', 'NOT LIKE', '%DWSR%')
					->first();

					if ($status == "Fresh") {
						if (array_key_exists($seed->variety, $data)) {
							$data[$seed->variety]['quantity'] += ($item->Area_culture_in_ha * $seed->ave_yld) * 1000;
						} else {
							$data[$seed->variety]['variety'] = $seed->variety;
							$data[$seed->variety]['quantity'] = ($item->Area_culture_in_ha * $seed->ave_yld) * 1000;
						}
					} elseif ($status == "Processed") {
						if (array_key_exists($seed->variety, $data)) {
							$data[$seed->variety]['quantity'] += (($item->Area_culture_in_ha * $seed->ave_yld) * 1000) * $this->recovery;
						} else {
							$data[$seed->variety]['variety'] = $seed->variety;
							$data[$seed->variety]['quantity'] = (($item->Area_culture_in_ha * $seed->ave_yld) * 1000) * $this->recovery;
						}
					}
				}	
			}
		}

		return $data;
	}*/
}
