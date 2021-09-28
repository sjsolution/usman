<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
  

// Route::domain(config('app.subdomain'))->group(function () {

Route::get('/admin/adminlogin',function(){
    return redirect('/admin/');
});
Route::get('/admin/webpush','TestController@ss');
Route::get('/admin/gift','TestController@gift');


/**
 * Admin Web Notification 
 */
Route::post('/admin/notification/order/request/render','SendNotificationController@adminNotificationRender');
Route::post('/admin/notification/seen/status','SendNotificationController@adminNotificationStatus');
Route::post('/admin/notification/is_clicked','SendNotificationController@adminNotificationClicked');
/**
 * End of admin web notification
 */


Route::post('/admin/booking/order/details','Admin\BookingController@orderDetails');
Route::post('/admin/transaction/settlement/details','Admin\BookingController@settlementDetails');
Route::post('/admin/transaction/export','Admin\BookingController@exportTransaction');





Route::post('admin/technician/assign/list','Admin\BookingController@technicianList');
Route::post('admin/booking/technician/update','Admin\BookingController@technicianUpdate');
Route::post('/admin/booking/transaction/details','Admin\BookingController@transactionDetails');

Route::get('/admin/dashboard/booking/order/list','Admin\BookingController@recentBooking');

Route::get('/admin/','Admin\LoginController@adminlogin');
Route::post('/admin/adminlogin', 'Admin\LoginController@adminlogin')->name('adminlogin');

Route::group(['middleware'=>['auth:admin']],function (){

    Route::namespace('Admin')->group(function () {


      /**
      * Notification Routes
      */
      Route::get('/admin/notification','NotificationController@index')->name('admin.notifications');
      Route::get('/admin/notification/list','NotificationController@list')->name('admin.notification.list');

      /**
       * End of notification routes
      */
       /**
       * Invoice download
       */
      Route::get('admin/booking/invoice/{order}','InvoiceController@invoice');
      Route::get('admin/booking/invoice/download/{order}','InvoiceController@downloadPDF');
      /**
       * End of invoice download
       */

       /**
        * Push Notification Controller
        */
      Route::get('admin/setting/push-notification','PushNotificationController@index')->name('push.send.form');
      Route::post('admin/user/push/notification/email/list','PushNotificationController@userlist')->name('push.user.list');
      Route::post('admin/setting/push-notification','PushNotificationController@store')->name('push.send.form.store');
      Route::get('admin/settings/push-notification/list','PushNotificationController@notificationList')->name('push.send.list');
      Route::get('admin/settings/push-notification/list/view','PushNotificationController@notificationListView')->name('push.send.list.view');
      Route::get('admin/settings/push-notification/notification/view/{notification}','PushNotificationController@notificationView')->name('push.send.store.view');
      Route::post('admin/setting/push-notification/send','PushNotificationController@sendUserPushNotification')->name('push.send');
      Route::post('admin/user/delete/{user}','UsersController@delete');




      /**
       * End of Push notification controller
       */

      /**
       * Review Tab controller
       */
      Route::get('/admin/review/list','ReviewController@index')->name('admin.review.list.view');
      Route::get('/admin/review','ReviewController@list')->name('admin.review.list');
      Route::post('/admin/review/details','ReviewController@details')->name('admin.review.details');

      /**
       * End of Review tab controller
       */

      /**
       * Admin Dashboard graph
       */
      Route::get('/admin/dashboard/revenue/graph','DashboardController@revenueGraph');
      Route::get('/admin/dashboard/order/graph','DashboardController@orderGraph');
      Route::get('/admin/dashboard/statistics','DashboardController@dashbaordCardDetails');

      /**
       * End of admin dashbaord graph
       */

      Route::post('/admin/logout', 'LoginController@logout')->name('admin.logout');
      Route::get('/admin/home', 'DashboardController@index')->name('admin.home');
      Route::get('/admin/settings/timeslot/create','AdmintimeslotController@create')->name('admin.timeslot');
      Route::post('/admin/settings/timeslot/create','AdmintimeslotController@store');
      
    

        /******************Service Type  section************/
      Route::match(['get','post'],'/admin/servicetype', 'ServicecategoryController@index')->name('service.index');
      Route::match(['get','post'],'/admin/servicetype/getServiceList', 'ServicecategoryController@getservicelist')->name('service.getservicelist');
      
      /**
       * User Routes
       */
      Route::match(['get','post'],'/admin/user', 'UsersController@index')->name('user.index');
      Route::match(['get','post'],'/admin/user/getuserlist', 'UsersController@getuserlist')->name('user.getuserlist');
      Route::post('admin/user/selectedstatusupdate','UsersController@selectedstatusupdate')->name('user.bulkstatusUpdate');
      Route::match(['get','post'],'/admin/user/userUpdate', 'UsersController@userUpdate')->name('user.userUpdate');
      Route::get('/admin/user/usersDetails/{id}', 'UsersController@usersDetails')->name('user.usersDetails');
      Route::post('admin/user/add/cashback','UsersController@addCashback')->name('admin.users.add.cashback');
      Route::get('admin/user/wallet/histories/{user}','UsersController@userWallet')->name('admin.users.wallet');
      Route::match(['get','post'],'/admin/user/transaction/histories/{user}', 'UsersController@walletHistoriesList')->name('user.transaction.histories');

      /**
       * End of user routes
       */

      /**
       * Admin profile settings routes
       */
       Route::match(['get','post'],'/admin/user/myprofile', 'UsersController@myprofile')->name('admin.myprofile');
       Route::match(['get','post'],'/admin/user/changepassword', 'UsersController@changepassword')->name('admin.changepassword');
      /**
       * End of admin profile settings routes
       */
      
       /**
       * Role Management
       */
      Route::get('admin/settings/role/management','RoleManagementController@index')->name('role.management');
      Route::post('admin/settings/role/management','RoleManagementController@store')->name('role.management');
      Route::get('admin/settings/role/management/users','RoleManagementController@userList')->name('user.role.list');
      Route::get('admin/settings/role/management/users/list','RoleManagementController@list')->name('user.role.listing');
      Route::get('admin/settings/role/management/add/user','RoleManagementController@addUser')->name('role.management.add.user');
      Route::post('admin/settings/role/management/add/user','RoleManagementController@userStore')->name('role.user.store');
      Route::post('admin/settings/role/management/user/status','RoleManagementController@changeStatus')->name('role.user.status');
      Route::get('admin/settings/role/management/edit/{id}','RoleManagementController@edit')->name('role.user.edit');
      Route::post('admin/settings/role/management/edit/{id}','RoleManagementController@update')->name('role.user.update');
      Route::get('admin/settings/role/management/details/{id}','RoleManagementController@userDetails')->name('role.user.details');
      
      Route::get('settings/role/list','RoleManagementController@role')->name('role.list');
      Route::get('settings/role/list/view','RoleManagementController@roleList')->name('role.list.view');
      Route::get('settings/role/edit/{id}','RoleManagementController@roleEdit')->name('role.edit');
      Route::post('settings/role/edit/{id}','RoleManagementController@roleUpdate')->name('role.update');
      Route::post('settings/role/status','RoleManagementController@roleStatus')->name('role.status');



      /**
       * End of role management
       */

      /**
       * Add money Management
       */
      Route::get('/admin/settings/money', 'AddMoneyController@index')->name('add.money.index');
      Route::get('/admin/settings/addmoney/list', 'AddMoneyController@getAdminwalletlist')->name('admin.getAdminwallet.list');
      Route::get('/admin/settings/addmoney/update/{id}','AddMoneyController@update')->name('addmoney.update');
      Route::post('/admin/settings/addmoney/update/{id}','AddMoneyController@update')->name('addmoney.updated');

       /**
       * Payment gateway settings 
       */
      
        Route::get('/admin/settings/paymentgateway', 'PaymentGatewaySettingController@index')->name('paymentgateway.index');
        Route::get('/admin/settings/paymentgateway/list', 'PaymentGatewaySettingController@list')->name('paymentgateway.getlist');
        Route::get('/admin/settings/paymentgateway/change', 'PaymentGatewaySettingController@setting')->name('paymentgateway.setting');
        Route::post('/admin/settings/paymentgateway/store', 'PaymentGatewaySettingController@store')->name('paymentgatewaysetting.store');
       
        /**
       * End if payment gateway settings
       */


      /**
       * Labels Management
       */
      Route::match(['get','post'],'/admin/settings/label', 'LabelController@index')->name('label.index');
      Route::get('admin/settings/label/export', 'LabelController@export')->name('export');
     
      Route::post('admin/settings/label/unique/key/name','LabelController@uniqueLabelKey');

      Route::post('admin/settings/label/import', 'LabelController@import')->name('import');
      Route::match(['get','post'],'/admin/settings/label/getlabelslist', 'LabelController@getlabelslist')->name('label.getlabelslist');
      Route::get('/admin/settings/label/update/{id}','LabelController@update')->name('label.update');
      Route::post('/admin/settings/label/update/{id}','LabelController@update')->name('label.updated');
      Route::get('admin/settings/label/create', 'LabelController@create')->name('label.create');
      Route::post('admin/settings/label/create', 'LabelController@store')->name('label.store');
      /**
       * End of label management
       */
      
      /**
       * Banner Management
       */
      Route::match(['get','post'],'/admin/settings/banner', 'BannerController@index')->name('banner.index');
      Route::match(['get','post'],'/admin/settings/banner/getbannerlist', 'BannerController@getbannerlist')->name('banner.getbannerlist');
      Route::match(['get','post'],'/admin/settings/banner/createBanner', 'BannerController@createBanner')->name('banner.createBanner');
      Route::get('/admin/settings/banner/bannerDetails/{id}', 'BannerController@bannerDetails')->name('banner.bannerDetails');
      Route::match(['get','post'],'/admin/settings/banner/updateBanner/{id}', 'BannerController@updateBanner')->name('banner.updateBanner');
      Route::match(['get','post'],'/admin/settings/banner/bannerUpdate', 'BannerController@bannerUpdate')->name('banner.bannerUpdate');
      /**
       * End of banner management
       */

      /**
       * Our Partners Management
       */
      Route::get('admin/settings/ourpartners/create','PartnerController@createpartner')->name('op.createPartner');
      Route::post('admin/settings/ourpartners/create','PartnerController@createpartner')->name('op.createPartner');
      Route::get('admin/settings/ourpartnerslist','PartnerController@index')->name('op.partnerslist');
      Route::get('admin/settings/getpartnerslist','PartnerController@getpartnerslist')->name('op.getpartnerslist');
      Route::get('admin/settings/ourpartner/details/{id}','PartnerController@ourpartnerDetails')->name('op.ourPartnerDetails');
      Route::get('admin/settings/ourpartner/update/{id}','PartnerController@ourpartnerUpdate')->name('op.ourpartnerUpdate');
      Route::post('admin/settings/ourpartner/update/{id}','PartnerController@ourpartnerUpdate')->name('op.ourpartnerUpdated');
      Route::post('admin/settings/ourpartner/delete','PartnerController@ourpartnerDelete')->name('op.ourpartnerDelete');
      Route::post('admin/settings/ourpartner/selectedstatusupdate','PartnerController@selectedstatusupdate')->name('op.bulkstatusUpdate');

      /**
       * End of our partners managent
       */
 
      /**
       * Category Management
       */
      Route::match(['get','post'],'/admin/settings/categoryView', 'CategoryController@index')->name('category.categoryView');
      Route::match(['get','post'],'/admin/category/getcategorylist', 'CategoryController@getcategorylist')->name('category.getcategorylist');
      Route::match(['get','post'],'/admin/category/createCategory', 'CategoryController@createCategory')->name('category.createCategory');
      Route::match(['get','post'],'/admin/category/updateCategory/{id}', 'CategoryController@updateCategory')->name('category.updateCategory');
      Route::match(['get','post'],'/admin/category/categoryUpdate', 'CategoryController@categoryUpdate')->name('category.categoryUpdate');
      Route::get('/admin/category/categoryDetails/{id}', 'CategoryController@categoryDetails')->name('category.categoryDetails');
      /**
       * End of category management
       */

      /**
       * Sub-Category Management
       */
      Route::match(['get','post'],'/admin/settings/category/subcategory', 'CategoryController@subcategory')->name('category.subcategory');
      Route::match(['get','post'],'/admin/settings/category/getsubcategorylist', 'CategoryController@getsubcategorylist')->name('category.getsubcategorylist');
      Route::match(['get','post'],'/admin/settings/category/createSubCategory', 'CategoryController@createSubCategory')->name('category.createSubCategory');
      Route::match(['get','post'],'/admin/settings/category/subcategoryUpdate', 'CategoryController@subcategoryUpdate')->name('category.subcategoryUpdate');
      Route::match(['get','post'],'/admin/settings/category/updateSubCategory/{id}', 'CategoryController@updateSubCategory')->name('category.updateSubCategory');
      Route::match(['get','post'],'/admin/settings/category/categorylist', 'CategoryController@categorylist')->name('category.categorylist');
      /**
       * End of sub-category management
       */

      /**
       * Vehicle Type Management
       */
      Route::get('admin/settings/vehicle/list','VehiclesController@getvehiclelist')->name('getvehiclelist');
      Route::match(['get','post'],'admin/vehicle/getvehiclelistdata','VehiclesController@getvehiclelistdata')->name('getvehiclelistdata');
      Route::match(['get','post'],'admin/vehicletype/created','VehiclesController@createvehicle')->name('vehicle.createvehicle');
      Route::post('admin/vehicle/delete','VehiclesController@vehicleDelete')->name('vehicle.deleteVehicle');
      Route::get('admin/vehicle/update/{id}','VehiclesController@vehicleUpdate')->name('vehicle.update');
      Route::post('admin/vehicle/update/{id}','VehiclesController@vehicleUpdate')->name('vehicle.updated');
      /**
       * End of vehicle type management
       */

      /**
       * Vehicle brand management
       */
      Route::get('admin/vehicle/brand/brandlist','VehiclesController@vehicleBrandList')->name('vehicle.brandlist.type');
      Route::match(['get','post','put'],'admin/settings/brand/brandlist','VehiclesController@brandlist')->name('vehicle.brandlist');
      Route::match(['get','post','put'],'admin/settings/brand/createbrand','VehiclesController@createbrand')->name('vehicle.createbrand');
      Route::match(['get','post','put'],'admin/settings/brand/updatebrand/{id}','VehiclesController@updatebrand')->name('vehicle.updatebrand');
      Route::match(['get','post','put'],'admin/settings/brand/deleteBrand','VehiclesController@deleteBrand')->name('vehicle.deleteBrand');
      /**
       * End of vehicle brand management
       */

      /**
       * Vehicle model management
       */
      Route::get('admin/model/vehicle/modellist','VehiclesController@vehicleModelList')->name('vehicle.model.modellist');
      Route::match(['get','post','put'],'admin/settings/model/modellist','VehiclesController@modellist')->name('vehicle.modellist');
      Route::match(['get','post','put'],'admin/settings/model/createmodel','VehiclesController@createmodel')->name('vehicle.createmodel');
      Route::match(['get','post','put'],'admin/settings/model/updatemodel/{id}','VehiclesController@updatemodel')->name('vehicle.updatemodel');
      Route::match(['get','post','put'],'admin/settings/model/deletemodel','VehiclesController@deletemodel')->name('vehicle.deletemodel');
      Route::match(['get','post','put'],'admin/settings/coverage','VehiclesController@coverage')->name('vehicle.coverage');
      /**
       * End of vehicle model management
       */

      /**
       * Vehicle Manufacture year management
       */
      Route::get('admin/settings/vehicle/manufacture/manufacturelist','VehiclesController@vehicleManufactureList')->name('vehicle.manufacturelist.view');
      Route::match(['get','post','put'],'admin/settings/manufacture/manufacturelist','VehiclesController@manufacturelist')->name('vehicle.manufacturelist');
      Route::match(['get','post','put'],'admin/settings/manufacture/createmanufacture','VehiclesController@createmanufacture')->name('vehicle.createmanufacture');
      Route::match(['get','post','put'],'admin/settings/manufacture/updatemanufacture/{id}','VehiclesController@updatemanufacture')->name('vehicle.updatemanufacture');
      Route::match(['get','post','put'],'admin/settings/manufacture/deletemanufacture','VehiclesController@deletemanufacture')->name('vehicle.deletemanufacture');
      Route::match(['get','post'],'admin/settings/manufacture/yearselection','VehiclesController@yearselection')->name('vehicle.yearselection');
      /**
       * End of vehicle manufacture year management
       */
 
      /**
       * Service provider route listing
       */
      Route::get('admin/serviceprovider/create','ServiceproviderController@createServiceProvider')->name('sp.createServiceprovider');
      Route::post('admin/serviceprovider/create','ServiceproviderController@createServiceProvider')->name('sp.createServiceprovider');
      Route::get('admin/serviceprovider/area/{status?}','ServiceproviderController@getareas')->name('get-area-bystatus');
      Route::get('admin/serviceprovider/getcities/{countryid?}','ServiceproviderController@getcities')->name('get-getcities');
      Route::post('admin/serviceprovider/getSubcategorydata','ServiceproviderController@getSubcategorydata')->name('getSubcategorydata');
      Route::post('admin/serviceprovider/deletecountry','ServiceproviderController@deletecountry')->name('deletecountry');
      Route::post('admin/serviceprovider/checkexists','ServiceproviderController@checkexists')->name('checkexists');
 
      Route::get('admin/serviceproviderlist','ServiceproviderController@index')->name('sp.listServiceprovider');
      Route::get('admin/serviceprovider/list','ServiceproviderController@getserviceproviderlist')->name('sp.getserviceproviderlist');
      Route::get('admin/serviceprovider/details/{id}','ServiceproviderController@serviceproviderDetails')->name('sp.serviceproviderdetails');
      Route::get('admin/serviceprovider/update/{serviceprovider}','ServiceproviderController@serviceproviderUpdate')->name('sp.serviceproviderupdate');
      Route::post('admin/serviceprovider/update/{id}','ServiceproviderController@serviceproviderUpdate')->name('sp.serviceproviderupdated');
      Route::post('admin/serviceprovider/statusupdate','ServiceproviderController@statusUpdate')->name('sp.statusUpdate');
      Route::post('admin/serviceprovider/selectedstatusupdate','ServiceproviderController@selectedstatusupdate')->name('sp.bulkstatusUpdate');
 
      Route::get('admin/serviceprovider/service/list/{user}','ServiceproviderController@serviceList');
      Route::post('admin/serviceprovider/service/listing/{user}','ServiceproviderController@servicelisting')->name('admin.sp.service.list');
      Route::get('admin/serviceprovider/technician/list/{user}','ServiceproviderController@technicianList');
      Route::post('admin/serviceprovider/technician/listing/{user}','ServiceproviderController@technicianlisting')->name('admin.sp.technician.list');
      Route::get('admin/serviceprovider/change/password/{user}','ServiceproviderController@changePassword');
      Route::post('admin/serviceprovider/change/password','ServiceproviderController@changePasswordUpdate')->name('admin.change.password');

      /**
       * End of service provider route listing
       */

       /**
        * User signup Rewards routes
        */
      Route::get('admin/settings/rewards','RewardsController@index')->name('reward.settings');
      Route::post('admin/settings/rewards','RewardsController@store')->name('reward.setting.update');


        /**
         * End of user signup rewards routes
         */
     Route::post('/admin/user/export','UsersController@exportUser');

   
 
    /*********************************Booking /Transaction Tab ************************/
    Route::get('admin/booking/list','BookingController@index')->name('booking.list.view');
    Route::get('admin/booking','BookingController@list')->name('booking.list');
    Route::get('admin/transaction/list','BookingController@transaction')->name('transaction.list.view');
    Route::get('admin/transaction/{dateRange?}/{settleStatus?}/{service_provider_id?}','BookingController@listTransaction')->name('transaction.list');
    Route::post('/admin/transaction/settlement','BookingController@settlement');
    Route::post('/admin/transaction/settlement/all','BookingController@settlementAllTransaction')->name('user.bulksettlementUpdate');

    /**********************************Fee charges tab********************************************* */
    Route::get('admin/settings/fee_charge','FeeChargeController@index')->name('fee.charge');
    Route::post('admin/settings/fee_charge','FeeChargeController@store')->name('fee.charge');
     
    /************************************Listing************************************************ */
    Route::get('admin/settings/list-mgnt/create_listing','ListManagementController@index')->name('create.list');
    Route::get('admin/settings/list-mgnt/create','ListManagementController@create')->name('list.create');
    Route::post('admin/settings/list-mgnt/create','ListManagementController@store')->name('list.create');
    Route::get('admin/settings/service/provider/list','ListManagementController@serviceProviders')->name('service.provider.list');
    Route::post('/admin/sp/list/type','ListManagementController@spListType');
    Route::post('/admin/sp/ranking/assignment','ListManagementController@spListTypeAssignment');

    /*********************************************Coupon Management******************** */
    Route::get('admin/coupon/create','CouponController@create')->name('coupon.create');
    Route::get('admin/category/service/providers/{categoryId}','CouponController@getServiceProviders');
    Route::get('admin/category/services/{serviceProviderId}','CouponController@getServices');
    Route::post('admin/coupon/create','CouponController@store')->name('coupon.store');
    Route::get('admin/coupon/list','CouponController@index')->name('coupon.list.view');
    Route::get('admin/coupon/list/view','CouponController@couponList')->name('coupon.list');
    Route::post('admin/coupon/check','CouponController@couponCheck');
    Route::post('admin/coupon/status','CouponController@statusChange');
    Route::post('admin/coupon/display/status','CouponController@displayStatusChange');

    Route::post('admin/coupon/details','CouponController@couponDetails');

    });

  });

// });
