<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//USER REGISTRATION
Route::post('register', 'API\UserController@saveUser');
Route::post('resend', 'API\UserController@resendVerificationLink');
Route::post('update_fcm', 'API\UserController@updateFcmToken');
Route::post('update_profile', 'API\UserController@updateProfile');

//LOGIN and LOGOUT
Route::post('login', 'API\LoginController@signin');
Route::post('logout', 'API\LoginController@signout');
Route::post('forgot_password', 'API\LoginController@forgotPasswordRequest');
Route::post('password/reset/{code}',  'API\LoginController@updateNewPasswordForm');
Route::post('reset/success', 'API\LoginController@passwordResetSuccess');


//COUNTRIES LIST
Route::post('countries', 'API\LocationController@country');
Route::post('listingCountry', 'API\LocationController@searchCountryList');

//STATE LIST
Route::post('states', 'API\LocationController@state');

//STATIC PAGE CONTENT
Route::post('aboutus', 'API\PageController@aboutus');
Route::post('pricing', 'API\PageController@pricing');
Route::post('howitwork', 'API\PageController@howitwork');
Route::post('contactus', 'API\PageController@contactus');


//DASHBOARD
Route::post('dashboard', 'API\BuyerController@dashboard');
Route::post('company', 'API\BuyerController@company');
Route::post('search', 'API\BuyerController@searchListing'); //PREVIOUSLY USED: dashboard / listingSearch
Route::post('more', 'API\BuyerController@moreListing');
Route::post('topKeywords', 'API\BuyerController@topKeywordList');

//CATEGORY LIST
Route::post('category', 'API\BuyerController@categoryList');
Route::post('rating', 'API\BuyerController@saveRating');
Route::post('favorite', 'API\BuyerController@favorite');
Route::post('myfavorite', 'API\BuyerController@myFavorite');
Route::post('views', 'API\BuyerController@recentlyViewList');

//SELLER DASHBOARD
Route::post('seller/dashboard', 'API\SellerController@dashboard');
Route::post('seller/company', 'API\SellerController@company');
Route::post('seller/listing', 'API\SellerController@myListing');
Route::post('seller/company', 'API\SellerController@company');
Route::post('seller/listing_update', 'API\SellerController@listingUpdate');




//MESSAGE
Route::post('mail_to_listing','API\MessagingController@messageToListing');
Route::post('message/list','API\MessagingController@messageList');
Route::post('message/details','API\MessagingController@getMessageDetails');
Route::post('message/del','API\MessagingController@delMessage');
Route::post('message/support','API\MessagingController@support');

//SELLER MESSAGE
Route::post('mail_to_buyer','API\MessagingController@messageToBuyer');

//REPLY
Route::post('reply_to_mail','API\MessagingController@replyToMail');


//COMPARISON
Route::post('comparison','API\ComparisonController@comparisonList');


Route::post('activate/{code}','API\UserController@activate');


//MENUAL NOTIFICATION ROUTE
Route::post('notification/login','API\NotificationController@login');
Route::post('notification/dashboard','API\NotificationController@notificationDashboard');
Route::post('notification/save','API\NotificationController@notificationSave');
Route::post('notifications','API\NotificationController@notificationList');
Route::post('notification/logout','API\NotificationController@logout');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
