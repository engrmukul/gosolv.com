<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use DB;
use Validator;

class BuyerController extends BaseController
{
    /**
	 *
	 * GETTING DASHBOARD FORMATIONS *
	 *
	 * @return array $dashboardCollections
	 *
	 */

	public function dashboard()
	{

        //VERIFY USER TOKEN
        //$this->verify_request();

        //PREPARE MAIN CATEGORIES
        $mainCats = \DB::table('categories')->select('id','title as name')->where('id', 1)->where('parent_id', 0)->get();

        $catImagesArray = array(
            '1' => "app-04.jpg",
            '2' => "yarn-04.jpg",
            '6' => "fas-03.jpg",
            '15' => "dyf-02.jpg"
        );

        //PREPARE FEATURED LISTINGS
        $featured = $this->Dashboard_model->getFeaturedListings();
        //PREPARE CAT WISE LISTINGS
        $catListings = array();
        foreach ($mainCats as $key => $cat) {

            //APPEND IMAGE FOR MAIN CATEGORIES
            if( array_key_exists( $cat->id, $catImagesArray ) )
            {
                $cat->cat_image = $catImagesArray[ $cat->id ];
            }
            else
            {
                $cat->cat_image = "new_added_cat.jpg";
            }

            

            //FETCH LISTINGS OF PARENT CATEGORIES
            $item = new stdClass();
            $item->id = $cat->id;
            $item->title = $cat->name . ' Manufacturers & Suppliers';
            $item->listings = $this->Dashboard_model->getListingsOfCat($cat->id);

            if (count($item->listings) > 0) {
                $catListings[] = $item;
            }
        }


        $user_id = $this->input->post('id');
        $unReadMessageCount = $this->Dashboard_model->getUnreadMesage($user_id);

        //CURRENT USER VIEW LIST
        $views = $this->Dashboard_model->getRecentlyViewList($user_id);

        //CURRENT USER FAVORITE LIST
        $favorites = $this->Dashboard_model->getFavoriteList($user_id);

        //showArray($favorites);

        $catList = $this->Dashboard_model->getAllCategory();

        //Auto Search Category List
        $autoSearchCatList = $this->Dashboard_model->getCategoryForAutosearch();

        $countryList = $this->Location_model->getCountryList();

        $keywordList = $this->Dashboard_model->getTopKeywords();

        //RENDER
        echo json_encode(
            array(
                'status' => 200,
                'message' => 'Dashboard data served',
                'totalRecentlylView' => count($views),
                'totalFavorites' => count($favorites),
                'unReadMessageCount' => $unReadMessageCount,
                'listingImageBaseUrl' => $this->config->item('listing_image_base_url'),
                'catImageBaseUrl' => $this->config->item('cat_image_base_url'),
                'cats' => $mainCats,
                'featuredListings' => $featured,
                'catListings' => $catListings,
                'lookup_topSearchKeywords' => $keywordList,
                'lookup_countryList' => $countryList,
                'lookup_categoryList' => $catList,
                'lookup_autoSearchCategoryList' => $autoSearchCatList
            )
        );
    }
}
