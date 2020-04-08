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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => '/admin', 'namespace' => 'Admin'], function () {
    Route::get('/sign-in', 'MainController@viewSignIn')->name('viewSignIn');
    Route::post('/sign-in', 'MainController@signIn')->name('signIn');
    Route::get('/sign-out', 'MainController@signOut')->name('signOut');

    Route::group(['middleware' => 'accessAdmin'], function () {
        Route::group(['middleware' => 'typeIsAdmin'], function () {
            Route::get('/', 'MainController@viewIndex')->name('viewIndex');
            Route::get('details', 'DetailsController@index');

            Route::resource('managers', 'ManagerController')->only([
                'index', 'destroy', 'update', 'edit', 'store', 'show'
            ]);

            Route::resource('users', 'UserController')->only([
                'index', 'destroy', 'show', 'edit'
            ]);

            Route::resource('news', 'NewsController')->only([
                'index', 'destroy', 'show', 'edit', 'store', 'update'
            ]);

            Route::delete('articles/tag', 'ArticleController@removeTag')->name('articles.removeTag');
            Route::post('articles/{article}/show_delete', 'ArticleController@removeShow')->name('articles.removeShow');
            Route::post('articles/{article}/show_main', 'ArticleController@addShow')->name('articles.addShow');
            Route::resource('articles', 'ArticleController');


            Route::resource('authors', 'AuthorController')->parameters([
                //'authors' => 'user',
            ]);

            Route::resource('slides', 'SlideController');

            Route::resource('eventTypes', 'EventTypeController');


            Route::resource('comments', 'CommentController');

            Route::resource('tags', 'TagController')->only([
                'index', 'destroy', 'show', 'edit', 'store', 'update'
            ]);

            Route::resource('details', 'DetailsController');

            Route::delete('forums/tag', 'ForumController@removeTag')->name('forums.removeTag');
            Route::resource('forums', 'ForumController');

            Route::resource('forummods', 'ForumModerationController')->parameters([
                'forummods' => 'forum',
            ]);


            Route::resource('forumCats', 'ForumCatsController');


            Route::resource('vaccines', 'VaccineController');

            Route::resource('developments', 'DevelopmentController');

            Route::resource('events', 'EventController');

            Route::resource('eventmods', 'EventModerationController')->parameters([
                'eventmods' => 'event',
            ]);;

            Route::resource('feedings', 'FeedingController');

            Route::resource('feedingCats', 'FeedingCatController')->parameters([
                'feedingCats' => 'feedingCategory',
            ]);

            Route::resource('playlists', 'PlaylistController');

            Route::resource('records', 'RecordController');

            Route::resource('consultations', 'ConsultationController');

            Route::resource('consultationmods', 'ConsultationModerationController')->parameters([
                'consultationmods' => 'consultation',
            ]);

            Route::resource('notes', 'NoteController');


            Route::resource('blogs', 'BlogController');
        });


    });
});

Route::group(['prefix' => 'blogger'], function () {
    Route::get('/sign-in', 'Blogger\MainController@viewSignIn')->name('blogger.sign-in');
    Route::post('/sign-in', 'Blogger\MainController@signIn')->name('blogger.sign-in');

    Route::group(['middleware' => 'accessBlogger'], function() {
        Route::resource('blogDetails', 'DetailsController')->parameters([
            'blogDetails' => 'detail',
        ]);
        Route::resource('blogs', 'Admin\BlogController')->names([
            'index' => 'bloggers.index',
            'edit' => 'bloggers.edit',
            'destroy' => 'bloggers.destroy',
            'store' => 'bloggers.store',
            'show' => 'bloggers.show',
            'update' => 'bloggers.update',
        ]);

    });
});
