<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DownloadableCategory;
use App\Downloadable;
use App\DownloadableCounter;
use App\AffiliationAccess;
use App\User;
use Auth,DB;

class DownloadsController extends Controller {
    
	public function index() {
		$contacts = $this->contacts();

		// Get data
		$downloadableCategories = DownloadableCategory::where('is_published', '=', 1)->get();

		$downloadables = Downloadable::where('is_published', '=', 1)->get();

		$affiliationAccess = AffiliationAccess::get();

		if (Auth::check()) {
			// Get user affiliation
			$user = new User;
			$userID = Auth::user()->user_id;
	        $user_affiliation = $user->get_user_affiliation($userID);

	        return view('downloads.index', compact(['contacts', 'downloadableCategories', 'downloadables', 'affiliationAccess', 'user_affiliation']));
		} else {
			return view('downloads.index', compact(['contacts', 'downloadableCategories', 'downloadables']));
		}
		
		
	}

	public function download(Request $request){
		//dd($request->all());
		//$display_name = $request->display_name;
		$downloadable_id = $request->download_id;
		
		DB::beginTransaction();
		try{
			$downloadable = Downloadable::find($downloadable_id);
			$download_counter = DownloadableCounter::where('downloadable_id',$downloadable_id)
					->where('version',$downloadable->version)->first();

			//dd($download_counter);
			if(empty($download_counter)){
				$download_counter = new DownloadableCounter;
				$download_counter->downloadable_id = $downloadable_id;
				$download_counter->version = $downloadable->version;
				$download_counter->link = $downloadable->link;
				$download_counter->count = 1;
				$download_counter->save();
			}else{
				$download_counter->count++;
				$download_counter->save();
			}

			DB::commit();
			return redirect($downloadable->link);

		}catch(Exception $e){
			DB::rollBack();


		}
	}

}
