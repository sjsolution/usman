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
Route::namespace('Api')->group(function () {

  /***
   * Service Provider Login
   */
  Route::post('service_provider/login','ServiceProvider\AuthController@login');
  /**
   * End of service provider
   */
  /****************User signup*****************/
  Route::post('register','SignupController@register');
  Route::post('testsendSmsonMobile','SignupController@testsendSmsonMobile');
  //Route::post('sendSmsonMobile','SignupController@sendSmsonMobile');
  Route::post('checkEmailExist','SignupController@checkEmailExist');
  Route::post('getlastvalues','SignupController@getlastvalues');
  Route::post('verifyotp','SignupController@verifyotp');
  Route::post('resendOtp','SignupController@resendOtp');
  Route::post('userlogin','SignupController@userlogin');
  Route::post('logout','SignupController@logout');

  Route::post('guestuserlogin','SignupController@guestuserlogin');
  /************language and banners**************/
  Route::get('getLanguage','LabelsController@getLanguage');
  Route::get('getLanguagefortechnician','LabelsController@getLanguagefortechnician');
  Route::get('getBanners','LabelsController@getBanners');
  Route::post('updateLanguage','LabelsController@updateLanguage');
  
  /*************************Location******************************************/
  Route::get('getareas','LabelsController@getareas');

  Route::post('addressType','LabelsController@addressType'); 

  Route::post('delete/user/address','UserController@deleteAddress');
  Route::post('edit/user/address','UserController@editAddress');
  Route::post('delete/user/vehicles','VehicleController@deleteUserVehicle');
  Route::post('user/profile','UserController@userProfile');
  Route::post('user/notifications','UserController@notifications');


  /************Vehicles**************/
  Route::post('vehicletype','VehicleController@vehicletype');
  Route::post('createvehicle','VehicleController@createvehicle');
  Route::post('vehicleandtimeslot','VehicleController@vehicleandtimeslot');

  Route::post('listofvehicleandtype','VehicleController@listofvehicleandtype');

  Route::get('timeslotwithvehicleType','ServiceprovidersController@timeslotwithvehicleType');
  /********Dashboard and category and subcategory*****************/
  Route::post('dashboard','VehicleController@dashboard'); 
  Route::post('getsubcategory','VehicleController@getsubcategory');
  /******************user profile**************/
  Route::post('changepassword','ProfileController@changepassword');
  // Route::post('profileupdate','ProfileController@myprofile');
  Route::post('updateprofile','ProfileController@updateprofile');
  Route::post('notificationonoff','ProfileController@notificationonoff');
  Route::post('addaddress','VehicleController@addaddress');
  Route::post('edit/user/addresses','UserController@editAddress');
  Route::post('forgotpassword','SignupController@forgotpassword');
  /***********Normal service providers*****************/
  Route::post('getServiceProviders','ServiceprovidersController@getServiceProviders');
  /***********User Wallets**************/
  Route::post('wallethistory','WalletController@wallethistory');
  Route::post('addmoneytowallet','WalletController@addmoneytowallet');
  Route::get('amountsuggestionforwallet','WalletController@amountsuggestionforwallet');
  /******************Insurance**************/
  Route::post('insuranceserviceprovider','InsuranceController@insuranceserviceprovider');
  /**
   * Structure should be same as servicer provider details
   */ 
  Route::post('providerdetails','InsuranceController@providerdetails');
  /**
   * End of structures
   */ 
  Route::post('insurancevehicledetails','InsuranceController@insurancevehicledetails');
  Route::post('insuranceaddcardetails','InsuranceController@insuranceaddcardetails');
  Route::post('insurancetypewithcms','InsuranceController@insurancetypewithcms');
   
  /** Make Payment */
  Route::post('makepayment','InsuranceController@makepayment');
  Route::post('payment/url','InsuranceController@paymentUrl');
  Route::post('cancel/booking','BookingController@cancelBooking');
  Route::post('order/delete','InsuranceController@orderDelete');
  Route::post('order/acknowledge','InsuranceController@orderAacknowledge');

  /** Make Payment Fatora */
  Route::post('transaction/initiate','InsuranceControllerFatora@makepayment');

  /**
   * Bookmark Service Providers
   */
  Route::post('bookmark/serviceProviders','BookmarkController@markUnmark');
  Route::post('bookmark/serviceProviders/list','BookmarkController@bookmarkedServiceProviders');

  /**
   * End of Bookmark Service Providers
   */

  /**
   * Coupon Controller
   */
  Route::post('apply/coupon','CouponController@applyCoupon'); 
  /**
   * End of coupon controller
   */
  /**
   * Search Controller
   */
  Route::post('search','SearchController@search');
  /**
   * End of search
   */

  /**
   * Service details screen api for all category
   */
  Route::post('serviceDetails','ServiceprovidersController@serviceDetails');
  Route::post('serviceproviderdetails','ServiceprovidersController@serviceproviderdetails');
  Route::post('getsubcategoryservices','ServiceprovidersController@getsubcategoryservices');
  /**
   * End of service details
   */
  /*****************************User details********************************** */
  Route::post('user/addresses','UserController@addresses');
  Route::get('city/area/list','CityAreaController@areaList');
 /**********************Our partners*************************/
  Route::get('ourpartners','UserController@ourparterns');
  /*********************** Booking Apis******************************** */
  Route::post('user/booking/list','BookingController@userBookingList');
  Route::post('user/booking/details','BookingController@userBookingDetails');
  Route::post('user/order/rating','BookingController@rating');
  Route::post('user/booking/tracking','BookingController@userBookingTracking');


  /**
   * Technician Controller
   */
  Route::post('technician/service/on/off','TechnicianController@serviceOnOff');
  Route::post('technician/booking/list','TechnicianController@bookingList');
  Route::post('service/addons/list','TechnicianController@serviceAddonList');
  Route::post('booking/status/marking','TechnicianController@bookingStatusMarking');
  Route::post('user/report','TechnicianController@userReport');
  Route::post('user/review','TechnicianController@userReview');
  Route::post('extra/addons/service/payment/request','TechnicianController@addonServicePaymentRequest');//by kajal;
  Route::post('remove/addons/service','TechnicianController@removeAddOnService');//by kajal

  
  /**
   * End of Technician Controller
   */
  /**
   * Addons Service approval
   */
  Route::post('user/extra/booking/service/request/details','UserExtraServiceRequestController@extraAddonRequestDetail');
  Route::post('user/extra/booking/service/payment/details','UserExtraServiceRequestController@addonPaymentDetails');//kajal
  Route::post('user/addon/service/payment','UserExtraServiceRequestController@addOnServiceMakePayment');//by kajal
  /***
   * End of Addons Service approval
   */
  /**
   * Technician Tracking
   */
  Route::post('technician/tracking','TechnicianController@technicianTracking');

  /**
   * End of technician tracking
   */


});


