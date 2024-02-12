<?php

namespace App\Http\Controllers\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PhilRiceStation;
use App\ProductionPlans;
use DB;

class SeedProductionPlannerController extends Controller
{
    public function index() {
        $contacts = $this->contacts();

        // Get id of philrice stations
        $stations = PhilRiceStation::select('name', 'philrice_station_id')->get();

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

        // PRODUCTION PLANS
        $data = array();

        foreach ($stations as $station) {
            $philrice_station_id = $station->philrice_station_id;

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
                                        ['plans.is_deleted', '=', 0]
                                    ])
                                    ->get();

            $area = 0;

            foreach ($production_plans as $production_plan) {
                $area = $area + ($production_plan->area / 1);
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
                $percent_completed = round((($area / $production_area2) * 100));
            } else {
                $production_area2 = 0;
                $percent_completed = 0;
            }

            $data[] = array(
                'station' => $station->name,
                'area' => $area,
                'production_area' => $production_area2,
                'percentCompleted' => $percent_completed
            );
        }

        if ($semester == 1) {
            $semesterText = "SEM 1 (SEP 16 - MAR 15)";
        }

        if ($semester == 2) {
            $semesterText = "SEM 2 (MAR 16 - SEP 15)";
        }

        return view('monitoring.seedProductionPlanner')->with(compact('contacts', 'data', 'croppingYear', 'semesterText'));
    }
}
