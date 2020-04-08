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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1', 'namespace' => 'REST'], function () {
    Route::get('note', 'ListController@getNote');
    Route::get('about', 'ListController@getAboutUs');
    Route::get('policy', 'ListController@getPolicy');
    Route::group(['prefix' => 'user'], function () {
        Route::post('sign_up', 'UserController@signUp');
        Route::post('sign_in', 'UserController@signIn');
        Route::post('verify/resend', 'UserController@resendVerification');
        Route::get('verify/{token}', 'UserController@verifyEmail')->name('user.verify');
        Route::post('password/reset/send', 'UserController@sendResetMail');
        Route::post('forget-password', 'UserController@sendResetMail');
        Route::post('password/reset', 'UserController@resetPassword')->name('password.reset');
        Route::get('reset/{token}', 'UserController@showResetPage')->name('password.resetPage');
        Route::group(['middleware' => 'userAuthenticated'], function () {

            Route::get('children', 'UserController@getChildren');
            Route::post('children/add', 'UserController@addChildren');
            Route::post('children/{id}', 'UserController@editChildren');
            Route::delete('children/{id}', 'UserController@deleteChildren');

            Route::get('tags', 'UserController@getTags');
            Route::post('tag/add', 'UserController@addTags');
            Route::delete('tag/{id}', 'UserController@deleteTags');

            Route::post('profile', 'UserController@updateProfile');

            Route::group(['middleware' => 'emailAuthenticated'], function() {

                Route::get('authenticate', 'UserController@authenticate');

                Route::get('notifyVaccines', 'UserController@createNotificationForVaccines');
                Route::get('discount', 'ListController@getDiscount');
                Route::get('discount/create', 'ListController@createDiscount');
                Route::get('notify', 'UserController@sendUserNotification');
                Route::get('sendMail', 'UserController@sendMail');

                //Route::get('favourites', 'UserController@getFavourites');
                Route::get('favourites', 'ListController@getFavouritesDynamic');
                Route::post('favourites/add', 'UserController@addFavourites');
                Route::delete('favourites/{id}', 'UserController@deleteFavourites');
                Route::get('favourites/dynamic', 'ListController@getFavouritesDynamic');
                Route::get('recommendation', 'UserController@Recommendation');

                //Route::get('notifications', 'ListController@getNotifications');
                Route::post('article/create', 'UserController@createArticle');
                Route::get('/{id}', 'UserController@getUserById');
                Route::get('/{id}/children', 'UserController@getChildrenById');

            });
        });
    });
    Route::group(['middleware' => 'userAuthenticated'], function () {

        Route::get('tags', 'ListController@getTags');

        Route::group(['middleware' => 'emailAuthenticated'], function() {

        Route::get('all/get', 'ListController@getAll');
        Route::get('main', 'ListController@getAll');
        Route::get('all/getv2', 'ListController@getAllv2');
        Route::get('articles', 'ArticleController@getArticles');
        Route::get('articles/search', 'ArticleController@search');
        Route::get('articles/{id}', 'ArticleController@getArticle');
        Route::post('articles/image/add/{id}', 'ArticleController@addImageToArticle');

        Route::get('recipes', 'FeedingController@getRecipes');
        Route::get('feeding/categories', 'FeedingController@feedingCategories');
        Route::get('feedings', 'FeedingController@getFeedings');
        Route::get('feeding/{id}/details', 'FeedingController@details');
        Route::get('comments', 'CommentController@getComments');
        Route::post('comments/leave', 'CommentController@leaveComment');
        Route::post('comments/like', 'CommentController@likeComment');

        Route::get('forums', 'ForumController@index');
        Route::get('forums/categories', 'ForumController@categories');
        Route::post('forums', 'ForumController@create');
        Route::get('forums/search', 'ForumController@search');
        Route::get('forums/{id}/details', 'ForumController@details');

        Route::get('consultations', 'ConsultationController@index');
        Route::get('consultations/search', 'ConsultationController@search');
        Route::post('consultations', 'ConsultationController@create');
        Route::get('consultations/{id}', 'ConsultationController@show');

        Route::get('vaccines', 'VaccineController@index');
        Route::get('vaccines/{id}', 'VaccineController@show');

        Route::get('developments', 'DevelopmentController@index');
        Route::get('developments/{id}', 'DevelopmentController@show');

        Route::get('events', 'EventController@index');
        Route::post('events', 'EventController@create');
        Route::get('events/types', 'EventController@getTypes');
        Route::get('events/search', 'EventController@search');
        Route::get('events/{id}', 'EventController@show');

        Route::get('playlists', 'PlaylistController@index');
        Route::post('playlists/upload', 'PlaylistController@uploadMusic');
        Route::get('playlists/record/{id}', 'RecordController@show');
        Route::get('playlists/{id}', 'PlaylistController@show');

        Route::get('blogs', 'BlogController@index');
        Route::get('blogs/{id}', 'BlogController@show');


        Route::get('notifications', 'NotificationController@index');
        Route::delete('notifications/{id}', 'NotificationController@destroy');

        Route::post('firebase/send', 'UserController@sendFirebase');

        });
    });

    Route::get('consultations/{consultation}/approve', 'ConsultationController@approve');

    Route::get('favourite/types', 'ListController@getFavourableTypes');
});
Route::group(['prefix' => 'v2', 'namespace' => 'v2\REST'], function () {
    Route::get('note', 'ListController@getNote');
    Route::get('about', 'ListController@getAboutUs');
    Route::get('policy', 'ListController@getPolicy');
    Route::group(['prefix' => 'user'], function () {
        Route::post('sign_up', 'UserController@signUp');
        Route::post('sign_in', 'UserController@signIn');
        Route::post('verify/resend', 'UserController@resendVerification');
        Route::get('verify/{token}', 'UserController@verifyEmail')->name('user.verify');
        Route::post('password/reset/send', 'UserController@sendResetMail');
        Route::post('forget-password', 'UserController@sendResetMail');
        Route::post('password/reset', 'UserController@resetPassword')->name('password.reset');
        Route::get('reset/{token}', 'UserController@showResetPage')->name('password.resetPage');
        Route::group(['middleware' => 'userAuthenticated'], function () {

            Route::get('children', 'UserController@getChildren');
            Route::post('children/add', 'UserController@addChildren');
            Route::post('children/{id}', 'UserController@editChildren');
            Route::delete('children/{id}', 'UserController@deleteChildren');

            Route::get('tags', 'UserController@getTags');
            Route::post('tag/add', 'UserController@addTags');
            Route::delete('tag/{id}', 'UserController@deleteTags');

            Route::post('profile', 'UserController@updateProfile');

            Route::group(['middleware' => 'emailAuthenticated'], function() {

                Route::get('authenticate', 'UserController@authenticate');

                Route::get('notifyVaccines', 'UserController@createNotificationForVaccines');
                Route::get('discount', 'ListController@getDiscount');
                Route::get('discount/create', 'ListController@createDiscount');
                Route::get('notify', 'UserController@sendUserNotification');
                Route::get('sendMail', 'UserController@sendMail');

                //Route::get('favourites', 'UserController@getFavourites');
                Route::get('favourites', 'ListController@getFavouritesDynamic');
                Route::post('favourites/add', 'UserController@addFavourites');
                Route::delete('favourites/{id}', 'UserController@deleteFavourites');
                Route::get('favourites/dynamic', 'ListController@getFavouritesDynamic');
                Route::get('recommendation', 'UserController@Recommendation');

                //Route::get('notifications', 'ListController@getNotifications');
                Route::post('article/create', 'UserController@createArticle');
                Route::get('/{id}', 'UserController@getUserById');
                Route::get('/{id}/children', 'UserController@getChildrenById');

            });
        });
    });
    Route::group(['middleware' => 'userAuthenticated'], function () {

        Route::get('tags', 'ListController@getTags');

        Route::group(['middleware' => 'emailAuthenticated'], function() {

        Route::get('all/get', 'ListController@getAll');
        Route::get('main', 'ListController@getAll');
        Route::get('all/getv2', 'ListController@getAllv2');
        Route::get('articles', 'ArticleController@getArticles');
        Route::get('articles/search', 'ArticleController@search');
        Route::get('articles/{id}', 'ArticleController@getArticle');
        Route::post('articles/image/add/{id}', 'ArticleController@addImageToArticle');

        Route::get('recipes', 'FeedingController@getRecipes');
        Route::get('feeding/categories', 'FeedingController@feedingCategories');
        Route::get('feedings', 'FeedingController@getFeedings');
        Route::get('feeding/{id}/details', 'FeedingController@details');
        Route::get('comments', 'CommentController@getComments');
        Route::post('comments/leave', 'CommentController@leaveComment');
        Route::post('comments/like', 'CommentController@likeComment');

        Route::get('forums', 'ForumController@index');
        Route::get('forums/categories', 'ForumController@categories');
        Route::post('forums', 'ForumController@create');
        Route::get('forums/search', 'ForumController@search');
        Route::get('forums/{id}/details', 'ForumController@details');

        Route::get('consultations', 'ConsultationController@index');
        Route::get('consultations/search', 'ConsultationController@search');
        Route::post('consultations', 'ConsultationController@create');
        Route::get('consultations/{id}', 'ConsultationController@show');

        Route::get('vaccines', 'VaccineController@index');
        Route::get('vaccines/{id}', 'VaccineController@show');

        Route::get('developments', 'DevelopmentController@index');
        Route::get('developments/{id}', 'DevelopmentController@show');

        Route::get('events', 'EventController@index');
        Route::post('events', 'EventController@create');
        Route::get('events/types', 'EventController@getTypes');
        Route::get('events/search', 'EventController@search');
        Route::get('events/{id}', 'EventController@show');

        Route::get('playlists', 'PlaylistController@index');
        Route::post('playlists/upload', 'PlaylistController@uploadMusic');
        Route::get('playlists/record/{id}', 'RecordController@show');
        Route::get('playlists/{id}', 'PlaylistController@show');

        Route::get('blogs', 'BlogController@index');
        Route::get('blogs/{id}', 'BlogController@show');


        Route::get('notifications', 'NotificationController@index');
        Route::delete('notifications/{id}', 'NotificationController@destroy');

        Route::post('firebase/send', 'UserController@sendFirebase');

        });
    });

    Route::get('consultations/{consultation}/approve', 'ConsultationController@approve');

    Route::get('favourite/types', 'ListController@getFavourableTypes');
});

