<?php

use App\Http\Controllers\BmoocController;

Route::get('/', 'BmoocController@index');
Route::get('archive', 'BmoocController@archive');
Route::get('topic/{id}', 'BmoocController@topic');
Route::get('relation/{id}/{child?}', 'BmoocController@relation');
Route::get('artefact/{id}', 'BmoocController@artefact');
Route::get('instruction/{id}', 'BmoocController@instruction');
Route::get('search/{author?}/{tag?}/{keyword?}', 'BmoocController@search');
Route::get('log/{id}', 'BmoocController@log');
Route::get('me', 'BmoocController@me');
Route::get('manual', 'BmoocController@manual');

Route::post('topic/new', 'BmoocController@newTopic');
Route::patch('topic/new', 'BmoocController@newTopic');
Route::post('instruction/new', 'BmoocController@newInstruction');
Route::post('artefact/new', 'BmoocController@newArtefact');
Route::post('log/new', 'BmoocController@newLog');
Route::post('log/save', 'BmoocController@saveLog');

Route::get('artefact/{id}/thumbnail', 'BmoocController@getImageThumbnail');
Route::get('artefact/{id}/medium', 'BmoocController@getImage');
Route::get('artefact/{id}/original', 'BmoocController@getImageOriginal');

Route::get('instruction/{id}/thumbnail', 'BmoocController@getInstructionImageThumbnail');
Route::get('instruction/{id}/medium', 'BmoocController@getInstructionImage');
Route::get('instruction/{id}/original', 'BmoocController@getInstructionImageOriginal');

Route::get('json/artefact/{id}', 'BmoocJsonController@artefact');
Route::get('json/artefact/{id}/children', 'BmoocJsonController@children');

Route::get('json/instruction/{id}', 'BmoocJsonController@instruction');
Route::get('json/topic/{id}/answers', 'BmoocJsonController@answers');
Route::get('json/topic/{id}/answers/search/{author?}/{tag?}/{keyword?}', 'BmoocJsonController@answers');
Route::get('json/topic/{id}', 'BmoocJsonController@discussion');

Route::auth();
//Route::get('login', 'BmoocController@index');

// Authentication and registration
//Route::get('auth/login', 'Auth\AuthController@getLogin');
//Route::post('auth/login', 'Auth\AuthController@postLogin');
//Route::get('auth/logout', 'Auth\AuthController@logout');
//Route::get('auth/register', 'Auth\AuthController@getRegister');
//Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::post('feedback', 'BmoocController@feedback');
Route::get('about', 'BmoocController@about');

/**
 * ADMIN PANEL
 */
Route::get('admin', function(){
    return Redirect::to('admin/actions/');
});
Route::get('admin/data', function(){
    return Redirect::to('admin/data/basic');
});
Route::get('admin/data/basic', 'AdminController@basic');
Route::get('admin/data/progress', 'AdminController@progress');
Route::get('admin/data/tree', 'AdminController@tree');
Route::get('admin/data/topics', 'AdminController@topics');
Route::get('admin/actions', 'AdminController@actions');
Route::post('admin/videos', 'AdminController@newVideo');
Route::post('admin/users', 'AdminController@userRole');
Route::post('admin/users/delete', 'AdminController@userDelete');
Route::delete('admin/videos', 'AdminController@deleteVideo');
Route::get('admin/actions/thumbnails', 'AdminController@getThumbnails');
Route::post('admin/actions/thumbnails', 'AdminController@postThumbnails');
Route::get('admin/actions/tags', 'AdminController@getTags');
Route::post('admin/actions/tags', 'AdminController@postTags');
Route::get('admin/actions/migrate', 'AdminController@getMigrate');
Route::post('admin/actions/migrate', 'AdminController@postMigrate');
Route::post('admin/actions/migrate/new', 'AdminController@postMigrateNew');
