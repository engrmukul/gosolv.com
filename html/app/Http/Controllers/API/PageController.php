<?php


namespace App\Http\Controllers\API;

use App\UserDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Validator;
use Illuminate\Support\Facades\Storage;

class PageController extends BaseController
{
    public function aboutus()
	{
		//$this->verify_request();

        $pagecontents = \DB::table('app_pages')->where('slug', 'about')->first();
        $pagecontents->content = base64_decode($pagecontents->content);
        
        if (count((array)$pagecontents) > 0) {
            return $this->sendResponse($pagecontents, 'Page content served');
        }else{
            return $this->sendError('Data not found');
        }
	}

	public function howitwork()
	{
		//$this->verify_request();

		$pagecontents = \DB::table('app_pages')->where('slug', 'how-it-works')->first();
		$pagecontents->content = base64_decode($pagecontents->content);
		
		if (count((array)$pagecontents) > 0) {
            return $this->sendResponse($pagecontents, 'Page content served');
        }else{
            return $this->sendError('Data not found');
        }
	}

	public function pricing()
	{
		//VERIFY INPUT
		//$this->verify_request();

		
		$planAndFeatures = array();

		
		$pagecontents = \DB::table('app_pages')->where('slug', 'pricing')->first();
		$plans = \DB::table('plans')->get();

		foreach ($plans as $plan){
			$plansArray[$plan->title] = $plan;
		}

		$plans[0] = $plansArray['Bronze'];
		$plans[1] = $plansArray['Silver'];
		$plans[2] = $plansArray['Gold'];
		
		//dd($plans[0]);

		foreach ($plans as $plan) {
			$item = new \stdClass();
			$item->id = $plan->id;
			$item->expire = 'FOR A '.$plan->duration.' DAY LISTING' ;
			$item->title = strtoupper($plan->title . ' TIER') ;
			$item->shortDescription = strtoupper($plan->shortDescription);
			$item->amount = '$ '.$plan->amount;
			$item->duration = $plan->duration;

			$item->plan_features = $this->getPricingPlan( $plan->id ); 

			$planAndFeatures[] = $item;
		}
		
		$pagecontents->plan = $planAndFeatures;


		if (count((array)$pagecontents) > 0) {
            return $this->sendResponse($pagecontents, 'Page content served');
        }else{
            return $this->sendError('Data not found');
        }
	}

	private function getPricingPlan( $planID )
	{
		//BRONZE 13
		if( $planID == 13)
		{

			$pricingArray = array(
				array(
					'id' => 32,
					'title' => "Company Contact Details",
					'isActive' => 1
				),
				array(
					'id' => 33,
					'title' => "Company Description",
					'isActive' =>1
				),
				array(
					'id' => 34,
					'title' => "Product Details",
					'isActive' => 1
				),
				array(
					'id' => 37,
					'title' => "Maximum 5 Keywords",
					'isActive' => 1
				),
				array(
					'id' => 38,
					'title' => "Add 5 Image Files",
					'isActive' => 1
				),
				array(
					'id' => 41,
					'title' => "Add 1 PDF File",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Add Video File",
					'isActive' => 0
				),
				array(
					'id' => 0,
					'title' => "Add Power Point Presentation",
					'isActive' => 0
				),
				array(
					'id' => 0,
					'title' => "Receive Monthly Buyer Inquiry List",
					'isActive' => 0
				),
				array(
					'id' => 0,
					'title' => "Social Media Campaign",
					'isActive' => 0
				),
				array(
					'id' => 0,
					'title' => "Promotional Newsletter Campaign to Member Buyers",
					'isActive' => 0
				)
			);	
		}



		//SILVER 1
		if( $planID == 1)
		{

			$pricingArray = array(
				array(
					'id' => 32,
					'title' => "Company Contact Details",
					'isActive' => 1
				),
				array(
					'id' => 33,
					'title' => "Company Description",
					'isActive' =>1
				),
				array(
					'id' => 34,
					'title' => "Product Details",
					'isActive' => 1
				),
				array(
					'id' => 37,
					'title' => "Maximum 10 Keywords",
					'isActive' => 1
				),
				array(
					'id' => 38,
					'title' => "Add 10 Image Files",
					'isActive' => 1
				),
				array(
					'id' => 41,
					'title' => "Add 2 PDF Files",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Add 1 Video File",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Add Power Point Presentation",
					'isActive' => 0
				),
				array(
					'id' => 0,
					'title' => "Receive Monthly Buyer Inquiry List",
					'isActive' => 0
				),
				array(
					'id' => 0,
					'title' => "Social Media Campaign",
					'isActive' => 0
				),
				array(
					'id' => 0,
					'title' => "Promotional Newsletter Campaign to Member Buyers",
					'isActive' => 0
				)
			);	
		}


		//GOLD 2
		if( $planID == 2)
		{

			$pricingArray = array(
				array(
					'id' => 32,
					'title' => "Company Contact Details",
					'isActive' => 1
				),
				array(
					'id' => 33,
					'title' => "Company Description",
					'isActive' =>1
				),
				array(
					'id' => 34,
					'title' => "Product Details",
					'isActive' => 1
				),
				array(
					'id' => 37,
					'title' => "Maximum 20 Keywords",
					'isActive' => 1
				),
				array(
					'id' => 38,
					'title' => "Add 20 Image Files",
					'isActive' => 1
				),
				array(
					'id' => 41,
					'title' => "Add 10 PDF Files",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Add 5 Video Files",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Add Power Point Presentation",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Receive Monthly Buyer Inquiry List",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Social Media Campaign",
					'isActive' => 1
				),
				array(
					'id' => 0,
					'title' => "Promotional Newsletter Campaign to Member Buyers",
					'isActive' => 1
				)
			);	
		}

		return $pricingArray;
	}

	

	public function contactus()
	{
		//$this->verify_request();
		//$pagecontents = file_get_contents(FCPATH . "pages/contactus.html");
		$contents = \Storage::get('pages/contactus.html');
		if (count((array)$pagecontents) > 0) {
            return $this->sendResponse($pagecontents, 'Page content served');
        }else{
            return $this->sendError('Data not found');
        }
	}
}
