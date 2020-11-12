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
Auth::routes();

//START ADMIN PART
Route::group(['middleware' => 'auth'], function (){
Route::get('admin', 'Admin\AdminController@index');
Route::resource('admin/suggested_services', 'Admin\SuggestedServicesController');
Route::resource('admin/what_they_say', 'Admin\WhatTheySayController');
Route::resource('admin/donations', 'Admin\DonationsController');
Route::resource('admin/donations_categories', 'Admin\DonationsCategoriesController');
Route::resource('admin/jobs', 'Admin\JobsController');
Route::resource('admin/jobs_categories', 'Admin\JobsCategoriesController');
Route::resource('admin/job_cities', 'Admin\JobCitiesController');
Route::resource('admin/stories', 'Admin\StoriesController');
Route::resource('admin/story_categories', 'Admin\StoryCategoriesController');
Route::resource('admin/story_comments', 'Admin\StoryCommentsController');
Route::resource('admin/forum', 'Admin\ForumController');
Route::resource('admin/forum_comments', 'Admin\ForumCommentsController');
Route::resource('admin/users', 'Admin\UsersController');
Route::resource('admin/events', 'Admin\EventsController');
Route::resource('admin/home_videos', 'Admin\HomeVideosController');
Route::resource('admin/images', 'Admin\ImagesController');
//Start user part
Route::get('account', 'Account@home');
Route::get('donation/donation', 'Donations@getDonation');
Route::get('events/delete/{id}', 'UpcomingEvents@deleteEventsByUserId');
Route::get('upcomingEvents/getEvent', 'UpcomingEvents@getEvent');
Route::post('upcomingEvents/updateEventAccount', 'UpcomingEvents@updateEventAccount');
Route::post('account/updateUserData', 'Account@updateUserData');
Route::post('account/updatePassword', 'Account@updatePassword');
Route::get('job/myjob', 'Job@getJobsByUserId');
Route::get('job/deletejob/{id}', 'Job@deleteJobsByUserId');
Route::get('events', 'UpcomingEvents@getEventsByUserId');
Route::get('job/edit/{id}','Job@edit');
Route::get('job/show/{id}','Job@show');
Route::post('job/update/{id}','Job@update');
Route::get('donation/edit/{id}','Donations@edit');
Route::post('donation/update/{id}','Donations@update');
Route::get('donation/show/{id}','Donations@show');
Route::get('events/edit/{id}','UpcomingEvents@edit');
Route::post('events/update/{id}','UpcomingEvents@update');
Route::get('events/show/{id}','UpcomingEvents@show');
Route::get('account/edit','Account@edit');
Route::post('account/update','Account@updateUserData');
});
//END ADMIN PART
Route::post('send','MailController@send');
//START AUTH PART
Route::get('auth/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');
//END AUTH PART

//START OURSERVICE PART
Route::get('our_service', 'OurService@home');
Route::get('our_service/{id}', 'OurService@home')->where('id', '[0-9]+');
Route::get('our_services/lastTwo', 'OurService@lastTwoServices');
Route::get('our_service/getAll', 'OurService@allServices');
Route::get('our_service/getById', 'OurService@getById');

//END OURSERVICE PART

//START DONATIONS PART
Route::get('donations', 'Donations@home');
Route::get('donations/category/{id}', 'Donations@home')->where('id', '[0-9]+');
//END DONATIONS PART
/*Route::get('job/getJobsByUserId', 'Job@getJobsByUserId');*/

//START JOBS PART
Route::get('job/{id}','Job@mygetjob');
Route::get('job', 'Job@home');
Route::get('jobs/categoriesAndSities', 'Job@getCategoriesAndCities');
Route::get('jobs/getByFilter', 'Job@getByFilter');
Route::post('jobs/create', 'Job@create');
//END JOBS PART

//START STORIES PART
Route::get('stories', 'Stories@home');
Route::get('stories/category/{id}', 'Stories@getByCategory')->where('id', '[0-9]+');
Route::get('stories/details/{id}', 'Stories@details')->where('id', '[0-9]+');
Route::get('stories/getCommentsById', 'Stories@getCommentsById');
Route::get('stories/getCommentsStartedWith', 'Stories@getCommentsStartedWith');
Route::post('stories/likes', 'Stories@likesDislikes');
Route::post('stories/addComment', 'Stories@addComment');
//END STORIES PART

//START UPCOMINGEVENTS PART
Route::get('upcoming_events', 'UpcomingEvents@home');
Route::get('upcoming_events/details/{id}', 'UpcomingEvents@details')->where('id', '[0-9]+');
Route::post('upcoming_events/addGoing', 'UpcomingEvents@addGoing');
Route::post('upcoming_events/addInterested', 'UpcomingEvents@addInterested');
//END UPCOMINGEVENTS PART

//START FORUMS PART
Route::get('forum', 'Forum@home');
Route::get('forums/getByFilter', 'Forum@getByFilter');
Route::get('forums/getMembersCount', 'Forum@getMembersCount');
Route::get('forums/getCommentsByForumId', 'Forum@getCommentsByForumId');
Route::get('forums/getItemsCount', 'Forum@getCountItems');
Route::get('forums/getCommentsStartedWith', 'Forum@getCommentsStartedWith');
Route::post('forums/addToViews', 'Forum@addToViews');
Route::post('forums/create', 'Forum@add');
Route::post('forums/likes', 'Forum@likesDislikes');
Route::post('forums/addComment', 'Forum@addComment');
//START FORUMS PART

//START REDIRECT TO HOME
Route::get('', 'HomeController@index');
Route::get('home', function () { return redirect(''); });
//END REDIRECT TO HOME

Route::get('video', 'HomeController@video');

//START ACCOUNT PART
/*Route::get('job/getJobsByUserId', 'Job@getJobsByUserId');*/
/*Route::get('job/deleteJobsByUserId', 'Job@deleteJobsByUserId');*/
Route::get('job/getJob', 'Job@getJob');
Route::post('job/updateJobAccount', 'Job@updateJobAccount');
Route::get('donations/getDonationsByUserId', 'Donations@getDonationsByUserId');
Route::get('donation/delete/{id}', 'Donations@deleteDonation');
Route::post('donations/create', 'Donations@create');
Route::post('donation/updateDonationAccount', 'Donations@updateDonationAccount');
/*Route::get('donation/getDonation', 'Donations@getDonation');*/

/*Route::get('upcomingEvents/getEventsByUserId', 'UpcomingEvents@getEventsByUserId');*/

Route::post('upcomingEvents/create', 'UpcomingEvents@create');
/*Route::get('upcomingEvents/deleteEventsByUserId', 'UpcomingEvents@deleteEventsByUserId');*/

//END ACCOUNT PART

//START TERMS AND CONDITIONS PART
Route::get('termsconditions', 'TermsConditions@home');
//END TERMS AND CONDITIONS PART

    Route::get('whatTheySay', 'AboutUs@getAllData');
  Route::get('about_us', 'AboutUs@home');
   Route::get('storyDetails', 'StoryDetails@home');

Route::get('termsconditions','AboutUs@terms');
Route::get('gallery','ImagesController@index');
Route::get('images_more/{id}','ImagesController@show');

Route::get('donCat','Donations@getDonCat');