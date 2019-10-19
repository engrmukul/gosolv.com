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

	public function dashboard( Request $request )
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
        $featured = $this->getFeaturedListing();

        dd($featured);exit;
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
            $item = new \stdClass();
            $item->id = $cat->id;
            $item->title = $cat->name . ' Manufacturers & Suppliers';
            $item->listings = $this->getListingsOfCat($cat->id);

            if (count($item->listings) > 0) {
                $catListings[] = $item;
            }
        }


        $user_id = $request->id;

        $unReadMessageCount = DB::table('comments')
                            ->select('COUNT(*) UNREAD_MESSAGES')
                            ->where('comment_to', $user_id)
                            ->where('read_status', 'IS NULL')
                            ->get();

        //CURRENT USER VIEW LIST
        $views = $this->getRecentlyViewList($user_id);

        //CURRENT USER FAVORITE LIST
        $favorites = $this->getFavoriteList($user_id);

        $catList = $this->getAllCategory();

        //Auto Search Category List
        $autoSearchCatList = DB::table('tagging_tags')->select('id','name AS name')->get();

        $countryList = DB::table('listings')
                        ->select('country  AS country_name')
                        ->distinct('country')
                        ->where('isActive', 1)
                        ->orderBy('country', 'ASC')
                        ->get();

        $keywordList = DB::table('tagging_tags')
                        ->select('name as keyword')
                        ->orderBy('count', 'DESC')
                        ->limit(10)
                        ->get();
 

        $dashboardData = array(
            'totalRecentlylView' => count($views),
            'totalFavorites' => count($favorites),
            'unReadMessageCount' => $unReadMessageCount,
            'listingImageBaseUrl' => '',//$this->config->item('listing_image_base_url'),
            'catImageBaseUrl' => '',//$this->config->item('cat_image_base_url'),
            'cats' => $mainCats,
            'featuredListings' => $featured,
            'catListings' => $catListings,
            'lookup_topSearchKeywords' => $keywordList,
            'lookup_countryList' => $countryList,
            'lookup_categoryList' => $catList,
            'lookup_autoSearchCategoryList' => $autoSearchCatList
        );

        if (count($dashboardData) > 0) {
            return $this->sendResponse($dashboardData, 'Dashboard data served');
        }else{
            return $this->sendError('Data not found');
        }
    }


    private function getFeaturedListing()
    {
         
        $features = DB::table('listings')->select(
            'listings.id', 
            'listings.title',
            'listings.user_id',
            'listings.email as email'
            )
            ->select(DB::raw('GROUP_CONCAT(distinct(categories.title) SEPARATOR  "/") as keywords'))
            ->leftJoin('category_listing AS k', function($join){
                    $join->on(DB::raw("find_in_set(k.listing_id, listings.id)", "<>" , DB::raw("'0'")));
                })
            ->leftJoin("categories", "categories.id = k.category_id")
            ->where('categories.parent_id', 0)            
            ->get();

        dd($features);
        
        return \DB::table('listings')->select(
            'listings.id',
            'listings.title',
            'listings.user_id',
            'LOWER(listings.email) as email',		
            'listings.country',
            'listings.description as description',
            'images.image as banner_image',
            'listings.featured as is_featured',
            'listings.isVerified as is_verified',
            'coalesce(round(sum(ratings.rating)/count(ratings.rating)), 0) as rating',
            'GROUP_CONCAT(distinct(categories.title) SEPARATOR  "/") as keywords'
            
        )
        ->where('listings.featured', 1)
        ->leftJoin("images", "images.imageable_id = listings.id")
        ->leftJoin("ratings", "ratings.ratingable_id = listings.id")
        ->leftJoin("category_listing AS k", "find_in_set(k.listing_id, listings.id)<> 0")
        ->leftJoin("categories", "categories.id = k.category_id")
        ->where('categories.parent_id', 0)
        ->where('listings.isActive', 1)
        ->limit(10)
        ->groupBy('listings.id')
        ->get();
    }

    private function getListingsOfCat( $catID )
    {
        //GET LISTING OF CAT ID
        //$query = $this->db->select('listing_id')->where('category_id', $catID)->get('');
        //$listingIdArray = array_column($query->result_array(), 'listing_id');

        $listingIdArray = DB::table('category_listing')->select('listing_id')->where('category_id', $catID)->get();

        if (!empty($listingIdArray)) {
            return \DB::table('listings')->select('
            listings.id,
            listings.title,
            listings.user_id,
            LOWER(listings.email) as email,		
            listings.country,
            listings.description as description,
            images.image as banner_image,
            listings.featured as is_featured,
            listings.isVerified as is_verified,
            coalesce(round(sum(ratings.rating)/count(ratings.rating)), 0) as rating,
            GROUP_CONCAT(distinct(categories.title) SEPARATOR  "/") as keywords
            
        ')
            ->where_in('listings.id', $listingIdArray)
            ->where('listings.featured', 0)
            ->leftJoin("images", "listings.id = images.imageable_id")
            ->leftJoin("ratings", "listings.id = ratings.ratingable_id")
            ->leftJoin("category_listing AS k", "find_in_set(k.listing_id, listings.id)<> 0")
            ->leftJoin("categories", "k.category_id = categories.id")
            ->where('categories.parent_id', 0)
            ->where('listings.isActive', 1)
            ->limit(10)
            ->groupBy('listings.id')
            ->get();
        } else {
            return array();
        }
    }

    private function getRecentlyViewList( $user_id )
    {
        return \DB::table('trackables')->select('
		
			listings.id,
			listings.title,
			listings.user_id,
			LOWER(listings.email) as email,		
			listings.country,
			listings.description as description,			
			images.image as banner_image,
			listings.isVerified as is_verified,
			coalesce(round(sum(ratings.rating)/count(ratings.rating)), 0) as rating,
			GROUP_CONCAT(distinct(categories.title) SEPARATOR  "/") as keywords
        
        
        ')
			->where('trackables.user_id', $user_id)
			->leftJoin('listings', 'trackables.trackable_id = listings.id')
			->leftJoin("images", "listings.id = images.imageable_id")
			->leftJoin("ratings", "listings.id = ratings.ratingable_id")
			->leftJoin("category_listing AS k", "find_in_set(k.listing_id, listings.id)<> 0")
			->leftJoin("categories", "k.category_id = categories.id")
			->where('categories.parent_id', 0)
			->where('listings.isActive', 1)
			->groupBy('listings.id')
			->orderBy('trackables.created_at', 'DESC')
			->limit(20)
			->get();
    }

    private function getFavoriteList( $user_id )
    {
        return \DB::table('favouriteables')->select('
		
			listings.id,
			listings.title,
			listings.user_id,
			LOWER(listings.email) as email,		
			listings.country,
			listings.description as description,			
			images.image as banner_image,
			listings.isVerified as is_verified,
			coalesce(round(sum(ratings.rating)/count(ratings.rating)), 0) as rating,
			GROUP_CONCAT(distinct(categories.title) SEPARATOR  "/") as keywords
        
        
        ')
			->where('favouriteables.user_id', $user_id)
			->leftJoin('listings', 'favouriteables.favouriteable_id = listings.id')
			->leftJoin("images", "images.imageable_id = listings.id")
			->leftJoin("ratings", "listings.id = ratings.ratingable_id")
			->leftJoin("category_listing AS k", "find_in_set(k.listing_id, listings.id)<> 0")
			->leftJoin("categories", "k.category_id = categories.id", "left")
			->where('categories.parent_id', 0)
			->where('listings.isActive', 1)			
			->groupBy('listings.id')
			->get();
    }

    public function getAllCategory()
    {
        $mainCats = \DB::table('categories')->select('id','title as name')->where('id', 1)->where('parent_id', 0)->get();

		$allCategory = array();


		//ADD OTHER CATEGORIES 
		if (count($mainCats) > 0) {

			//ADD 'SELECT CATEGORY' AS FIRST ITEM
			$allCategory[] = array('id' => 99999, 'name' => 'Select Category', 'subCategory' => array() );

			foreach ($mainCats as $mainCat) {
				$subCats['id'] = $mainCat->id;
				$subCats['name'] = $mainCat->name;
				$subCats['subCategory'] = DB::table('categories')->select('id','title as name')->where('parent_id', $mainCat->id)->get();

				$allCategory[] = $subCats;

			}

			return $allCategory;
		} else {
			return array();
		}
    }
}
