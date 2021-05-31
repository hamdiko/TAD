<?php


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

/*****************************************
 *              Public Routes             *
 *****************************************/
Route::middleware('api')->group(function () {
    Route::get('trends', [TrendController::class, 'index']);

});


/*****************************************
 *             Protected Routes           *
 *****************************************/

Route::middleware(['auth:sanctum', 'user.verified'])->group(function () {

    Route::get('visits', [VisitController::class, 'index']);
    Route::post('visits/add', [VisitController::class, 'store']);


    /*****************************************
     *      Subscription Resource Routes     *
     *****************************************/
    Route::get('subscriptions', [SubscriptionController::class, 'subscriptions']);
    Route::get('subscribers', [SubscriptionController::class, 'subscribers']);

});
