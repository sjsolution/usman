<?php
use App\Jobs\SendInvoiceEmailJob;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/ 
 
Route::get('test_invoice',function(){

   $order = \App\Models\Orders::where('id','1764')->with('subOrder')->first();
  
   // $img = explode(',', $order->suborder[0]->insurance->images);
   // dd(config('app.AWS_URL').$img[0] );
   $spEmail = 'sanu@o2onelabs.com';
   dispatch(new SendInvoiceEmailJob($order,$spEmail));
// return $order;
});

Route::get('welcome_mail',function(){
$data = [];
  \Mail::send('emails.register_welcome', $data, function($message)
{
    $message->to('sanu@o2onelabs.com', 'Sanu')->subject('Welcome!');
});
   
  // return view('emails.register_welcome');
});

// Route::get('test_invoice',function(){
//    $order = \App\Models\Orders::where('id','1581')->with('subOrder')->first();
// return view('invoice.invoice_mail',compact('order'));
// });
Route::get('changeLanguage/{lang}', 'IndexController@changeLanguage')->name('changeLanguage');
Route::get('/', 'IndexController@index')->name('homePage');
Route::get('/site-design', 'IndexController@siteDesign')->name('siteDesign');
Route::get('/store-design', 'IndexController@storeDesign')->name('storeDesign');
Route::get('/app-design', 'IndexController@appDesign')->name('appDesign');
Route::get('/social-media', 'IndexController@eMarketing')->name('eMarketing');
// Route::get('/home-ar', 'LandingPageController@homepage_ar');
Route::get('/test-tech-notify', 'Api\UserExtraServiceRequestController@testTechnotification');

Route::get('/apple-app-site-association', function () {
  
  $json = file_get_contents(base_path('.well-known/apple-app-site-association'));
  return response($json, 200)
      ->header('Content-Type', 'application/json');
});

// Route::pattern('domain', '(maak.live|cars.maak.live)');
// Route::domain('{domain}')->group(function () {
//   Route::get('/', 'LandingPageController@homepage');
//   Route::get('/home-ar', 'LandingPageController@homepage_ar');

//   Route::post('/be-vendor/store', 'LandingPageController@beVendor');
//   Route::get('/terms', 'LandingPageController@terms');

// });
 Route::get('settings/notification/push','Admin\PushNotificationController@sendPushToUser')->name('sp.push.send.delete');

// Route::domain(config('app.subdomain'))->group(function () {
  Route::get('settings/notification/delete/{id?}','Admin\PushNotificationController@notificationDelete')->name('sp.push.send.delete');
  Route::post('serviceprovider/notification/order/request/render','SendNotificationController@spNotificationRender');
  Route::post('serviceprovider/notification/seen/status','SendNotificationController@spNotificationStatus');
  Route::post('serviceprovider/notification/is_clicked','SendNotificationController@spNotificationClicked');
 
  Route::get('payment/success','Api\PaymentController@succeess')->name('transaction-success');
  Route::get('payment/failure','Api\PaymentController@failure')->name('transaction-error');
  
  Route::get('wallet/payment/success','Api\WalletController@success');
  Route::get('wallet/payment/failure','Api\WalletController@failure');
  
  Route::get('addons/payment/success','Api\TechnicianController@success');
  Route::get('addons/payment/failure','Api\TechnicianController@failure');

  /**Payment Urls for fatora */
  Route::get('transaction-success','Api\PaymentController@successCallback')->name('transaction-success');
  Route::get('transaction-error','Api\PaymentController@errorCallback')->name('transaction-error');

  //where all kind of paymet happend
  Route::get('addons/service/payment/success','Api\UserExtraServiceRequestController@success'); //by kajal
  Route::get('addons/service/payment/failure','Api\UserExtraServiceRequestController@failure');//by kajal

  Route::get('/import/cars/brands','CarBrandImportController@index');
  Route::post('/import/cars/brands','CarBrandImportController@import')->name('car.brand');


  Route::match(['get','post'],'/resetPassword/{has?}','CommonController@resetPassword')->name('resetPassword');
  Route::get('/serviceprovider/technician/test','TestController@forgotpassword')->name('sp.test');
  Route::namespace('ServiceProvider')->group(function () {
    // Route::get('/',function(){  
    //   return redirect('/userlogin');
    // });

    /**
     * Forgot Password
     */
    Route::get('forgot/password','ProviderLoginController@forgotPasswort')->name('sp.forgot');
    Route::post('/serviceprovider/email/exists','ProviderLoginController@uniqueEmailExists');
    Route::post('/serviceprovider/forgot-password', 'ProviderLoginController@forgotPasswordNotify');
    /**
     * End of forgot password
     */
    
    Route::match(['get','post'],'/userlogin','ProviderLoginController@userlogin')->name('userlogin');
    Route::get('/getLogout', 'ProviderLoginController@logout')->name('getLogout');
    Route::get('/activation/{id}', 'ProviderLoginController@activation')->name('activation');
  
    Route::group(['middleware'=>['auth']],function (){
          Route::get('/home', 'DashboardController@index')->name('user.home');
          Route::get('/serviceprovider/dashboard/booking/order/list','BookingController@recentBooking');
           /**
          * Notification Routes
          */
          Route::get('/serviceprovider/notification','NotificationController@index')->name('sp.notifications');
          Route::get('/serviceprovider/notification/list','NotificationController@list')->name('sp.notification.list');

          /**
           * End of notification routes
          */
          /**
           * Booking Tab Controllers
           */
          Route::get('/serviceprovider/booking-view','BookingController@spBookingList')->name('sp.booking.view');
          Route::get('/serviceprovider/booking','BookingController@list')->name('sp.booking.list');
          Route::post('/serviceprovider/booking/order/details','BookingController@orderDetails');
          Route::post('/serviceprovider/order/status/changed','BookingController@changeStatus');
          Route::post('/serviceprovider/booking/transaction/details','BookingController@transactionDetails');
          Route::post('/serviceprovider/booking/reschedule','RescheduleBookingController@index');
          Route::post('/serviceprovider/booking/reschedule/action','RescheduleBookingController@rescheduleBooking');


          /**
           * End of Booking Tab Controllers
           */

          /**
          * Dashboard controlller
          */
          Route::get('/serviceprovider/dashboard/revenue/graph','DashboardController@revenueGraph');
          Route::get('/serviceprovider/dashboard/order/graph','DashboardController@orderGraph');
          Route::get('/serviceprovider/dashboard/statistics','DashboardController@dashbaordCardDetails');

          /**
           * End of dashboard controller
           */
 
          /**
           * Users tab controller
           */
          Route::get('/serviceprovider/users-view','SPUserController@index')->name('sp.users');
          Route::get('/serviceprovider/users-list','SPUserController@list')->name('sp.users.list');
          Route::get('/serviceprovider/users/details/{user}','SPUserController@usersDetails')->name('sp.users.details');

 
          /**
           * End of user tab controller
           */
          /**
           * Invoice download
           */
          Route::get('booking/invoice/{order}','InvoiceController@invoice');
          Route::get('booking/invoice/download/{order}','InvoiceController@downloadPDF');

          /**
           * End of invoice download
           */

          /**
           * Transaction Tab controller
           */
          Route::get('/serviceprovider/transaction-view','BookingController@spTransactionList')->name('sp.transaction.view');
          Route::get('/serviceprovider/transaction','BookingController@listTransaction')->name('sp.transaction.list');
          /**
           * End of transaction tab controller
           */

          /**
           * Review Tab controller
           */
          Route::get('/serviceprovider/review-view','ReviewController@index')->name('review.list.view');
          Route::get('/serviceprovider/review','ReviewController@list')->name('review.list');
          Route::post('/serviceprovider/review/details','ReviewController@details')->name('review.details');


          /**
           * End of Review tab controller
           */

          /**
            * Push Notification Controller
            */
          Route::get('settings/push-notification','PushNotificationController@index')->name('sp.pushnotification');
          Route::post('settings/sp/user/push/notification/email/list','PushNotificationController@userlist')->name('push.spuser.list');
          Route::post('settings/push-notification','PushNotificationController@store')->name('sp.user.pushnotification');
          Route::get('settings/push-notification/list','PushNotificationController@notificationList')->name('sp.push.send.list');

          Route::get('settings/push-notification/list/view','PushNotificationController@notificationListView')->name('sp.push.send.list.view');
          Route::get('settings/push-notification/notification/view/{notification}','PushNotificationController@notificationView')->name('sp.push.send.store.view');
          Route::post('settings/push-notification/send','PushNotificationController@sendUserPushNotification')->name('sp.push.send');

           Route::get('settings/car-groups','CarBarndGroupController@index')->name('cargroup.view');
           Route::post('settings/add-car-groups','CarBarndGroupController@store')->name('cargroup.save');

          /**
           * End of Push notification controller
           */
 

          Route::get('/serviceprovider/updateprofile','DashboardController@myprofile')->name('sp.profileUpdate');
          Route::post('/serviceprovider/updateprofile','DashboardController@myprofile')->name('sp.profileUpdated');
          Route::get('/serviceprovider/changepassword','DashboardController@changepassword')->name('sp.changepassword');
          Route::post('/serviceprovider/changepassword','DashboardController@changepassword')->name('sp.passwordchanged');
          Route::post('/serviceprovider/coverimagedelete','DashboardController@coverimagedelete')->name('coverimagedelete');
          Route::get('/serviceprovider/servicelist','ServiceController@index')->name('sp.getservicelist');
          Route::post('/serviceprovider/service/status','ServiceController@changeStatus')->name('sp.service.statusUpdate');
 
          Route::get('/serviceprovider/servicelisting','ServiceController@servicelisting')->name('sp.servicelisting');
          Route::match(['get','post'],'/serviceprovider/createservice','ServiceController@createservice')->name('sp.createservice');
          Route::post('/serviceprovider/createdNewService','ServiceController@createdNewService')->name('sp.createdServices');
          Route::get('admin/serviceprovider/service/{status?}','ServiceController@getcategory')->name('get-category-bystatus');
          Route::get('admin/serviceprovider/service_subcategory/{category?}','ServiceController@getsubcategory')->name('get_subcategory_bycategory');
          Route::get('/serviceprovider/servicedetails/{service?}','ServiceController@getServiceDetails')->name('sp.serviceDetails');
          Route::match(['get','post'],'/serviceprovider/updateservice/{id?}','ServiceController@Updateservice')->name('sp.serviceupdate');
          Route::match(['get','post'],'/serviceprovider/deleteaddons','ServiceController@deleteaddons')->name('deleteaddons');
          Route::post('service/deletedescription','ServiceController@deletedescription')->name('deletedescription');
  
          /********************Technicians********************/
          Route::get('/serviceprovider/technician','TechnicianController@index')->name('sp.getTechnicianlist');
          Route::get('/serviceprovider/technician/view','TechnicianController@getTechnicianList')->name('sp.technicianList');
          Route::get('/serviceprovider/technician/create','TechnicianController@createTechnician')->name('sp.createTechnician');
          Route::post('/serviceprovider/technician/create','TechnicianController@createTechnician')->name('sp.technicianCreated');
          Route::get('/serviceprovider/technician/details/{id?}','TechnicianController@technicianDetails')->name('sp.technicianDetails');
          Route::get('/serviceprovider/technician/edit/{id?}','TechnicianController@updateTechnician')->name('sp.technicianUpadte');
          Route::post('/serviceprovider/technician/edit/{id}','TechnicianController@update')->name('sp.technicianUpdated');
          Route::post('/serviceprovider/technician/statusupdate','TechnicianController@statusUpdate')->name('tec.statusUpdate');
          Route::post('/serviceprovider/technician/delete','TechnicianController@technicianDelete')->name('tec.delete');
          Route::post('/serviceprovider/technician/bulkstatusupdate','TechnicianController@selectedstatusupdate')->name('tech.bulkstatusUpdate');
          // Route::post('/serviceprovider/technician/statusupdate','TechnicianController@statusUpdate')->name('tec');
          /*******************Time slot managment to service provider and technician********************/
          //Route::match(['get','post'],'/technician/techtimeslot','TechnicianController@techtimeslot')->name('tech.techtimeslot');

          /*****************Service provider time slot management **********/
          //Route::match(['get','post'],'/serviceprovider/sptimeslot','DashboardController@sptimeslot')->name('sp.sptimeslot');
          Route::get('/serviceprovider/create','SpTimeSlotController@create')->name('sp.sptimeslot');
          Route::post('/serviceprovider/create','SpTimeSlotController@store');
  
          Route::match(['get','post'],'/technician/create/{id?}','TechnicianTimeSlotController@create')->name('tech.techtimeslot');
          Route::post('/technician/create/{id?}','TechnicianTimeSlotController@store');

    });
  });
  Auth::routes();

// });

