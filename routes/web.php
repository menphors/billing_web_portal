<?php

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
Route::get('/', 'DashboardController@index');
// Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
// Route::get('/',   'HomeController@index');
Route::get('/home', 'DashboardController@index')->name('home');


Route::get('suboffering', 'ChangeSubOfferingController@index')->name('sub_offering');
// Change_SIM
Route::get('change_sim', 'ChangesimController@index');
Route::post('change_sim/save', 'ChangesimController@change_sim')
->name('change_sim');

//Change Primary Offering
Route::get('changeprimaryoffering', 'ChangeSubOfferingController@home');
Route::post('changeprimaryoffering/save', 'ChangeSubOfferingController@changeUserPrimaryOffering')
->name('change_user_primary_offering');

//Change Customer Information
Route::get('change_cust_info', 'ChangeCustInfoController@index');
Route::post('change_cust_info/save', 'ChangeCustInfoController@changeCustInfo')
->name('change_cust_info');

//Change EVC
Route::get('change_evc_info', 'ChangeEVCInfoController@index');
Route::post('change_evc_info/save', 'ChangeEVCInfoController@ChangeEVCInfo')
->name('change_evc_info');

//Change Dealer
Route::get('change_dealer_info', 'ChangeDealerInfoController@index');
Route::post('change_dealer_info/save', 'ChangeDealerInfoController@ChangeDealerInfo')
->name('change_dealer_info');

//Deactivate Sub
Route::get('deactivate_sub', 'DeactivateSubController@index');
Route::post('deactivate_sub/save', 'DeactivateSubController@DeactivateSub')
->name('deactivate_sub');

//Change Pre To Post
Route::get('change_pre_to_post', 'ChangePreToPostController@index');
Route::post('change_pre_to_post/save', 'ChangePreToPostController@ChangePreToPost')
->name('change_pre_to_post');

//Change Post To Pre
Route::get('change_post_to_pre', 'ChangePostToPreController@index');
Route::post('change_post_to_pre/save', 'ChangePostToPreController@ChangePostToPre')
->name('change_post_to_pre');

//Change Account Info
Route::get('change_acct_info', 'ChangeAcctInfoController@index');
Route::post('change_acct_info/save', 'ChangeAcctInfoController@ChangeAcctInfo')
->name('change_acct_info');

//Active Dub
Route::get('activate_sub', 'ActivateSubController@index');
Route::post('activate_sub/save', 'ActivateSubController@ActivateSub')
->name('activate_sub');



//To Do
Route::get('to_do', 'ChangeSubOfferingController@todo');
//Completed
Route::get('completed', 'ChangeSubOfferingController@index' );

Route::post('/user/login', 'UsersController@agentLogin')->name('agent_login');
Route::get('/user/logout', 'UsersController@agentLogout')->name('agent_logout');

//Batch Payment
Route::get('batch_payment/testing', 'BatchPaymentController@index');
Route::post('batch_payment/testing/save', 'BatchPaymentController@save');
// Route::get('batch_payment', 'BatchPaymentController@index');
// Route::get('batch_payment/save', 'BatchPaymentController@save');

//Request Temporary Suspend 
Route::get('request_suspend_view', 'RequestSuspendController@index');
Route::get('request_suspend', 'RequestSuspendController@getCompanyPhoneNumbeAmountBilling');
//Cancel Suspend
Route::get('cancel_suspend', 'CancelSuspendController@index');

//Dashboard
Route::get('/dashboard', 'DashboardController@index');

//Bill Medium
Route::get('change_bill_medium', 'ChangeBillMediumController@index')->name('bill_medium');
Route::post('/suboffering/change_bill_medium', 'ChangeBillMediumController@ChangeBillMedium')
->name('change_bill_medium');
//Route::get('/getDataGUI', 'ChangeBillMediumController@getDataGUI');

//Request Temporary Suspend 
Route::get('add_supplement_offer_view', 'AddSupOfferingController@index');
Route::get('add_supplement_offer', 'AddSupOfferingController@addSupOffering');

// QueryBatchCollection
Route::get('batch_collection', 'QueryBatchCollectionController@index');
Route::post('batch_collection/save', 'QueryBatchCollectionController@Querycollection')->name('batch_collection');
Route::get('show', 'QueryBatchCollectionController@show');
Route::get('export', 'QueryBatchCollectionController@export')->name('export');



Auth::routes();
