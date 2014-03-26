<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


/**
 * Authorized Resources
 */
Route::group(array('prefix'=>'1', 'before'=>'auth'), function() {
	Route::controller('Attachment', 'AttachmentController');
	Route::controller('Subscription', 'SubscriptionController');
});

/**
 * Public Resources
 */
Route::group(array('prefix'=>'/1'), function() {
	Route::controller('User', 'UserController');
});


Route::get('attachment/{slug}', 'AttachmentController@getShow');
