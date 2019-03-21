<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'building'  => 'API\BuildingController',
    'group'     => 'API\GroupController',
    'people'    => 'API\PeopleController',
    'note'      => 'API\NoteController',
    'user'      => 'API\UsersController',
    'emergency'      => 'API\EmergencyController'
]);

Route::get('/search', 'API\PeopleController@search');
Route::get('/all_groups', 'API\NoteController@get_all_groups');
Route::get('/create_xml', 'API\PeopleController@exportToXml');
Route::get('/get_all_buildings', 'API\BuildingController@get_all_buildings');
Route::get('/get_all_group_ids', 'API\BuildingController@get_all_group_ids');
Route::get('/get_choosen_builds/{id}', 'API\BuildingController@get_choosen_builds');
Route::post('/building/{id}/up_down', 'API\BuildingController@up_down');
