<?php

Route::get('/', 'DefaultController@dashboard')->name('dashboard');
Route::get('/logout', 'DefaultController@logout')->name('logout');
Route::get('/search', function(){
    return redirect('dashboard');
});

//rutas de autenticación
Auth::routes();

Route::get('/about_us', 'DefaultController@about_us')->name('about_us');
Route::get('/contact_us', 'DefaultController@contact_us')->name('contact_us');
Route::get('/mission', 'DefaultController@mission')->name('mission');
Route::get('/services', 'DefaultController@services')->name('services');
Route::post('/sendmail', 'DefaultController@sendmail')->name('sendmail');

Route::get('/home', 'HomeController@index')->name('home');

//call filters
Route::post('/admin/person/filter', 'PersonController@filter')->name('person.filter');
Route::post('/admin/address/filter', 'AddressController@filter')->name('address.filter');
Route::post('/admin/social_network/filter', 'SocialNetworkController@filter')->name('social_network.filter');
Route::post('/admin/group/filter', 'GroupController@filter')->name('group.filter');
Route::post('/admin/box_type/filter', 'BoxTypeController@filter')->name('box_type.filter');
Route::post('/admin/zone/filter', 'ZoneController@filter')->name('zone.filter');
Route::post('/admin/political_function/filter', 'PoliticalFunctionController@filter')->name('political_function.filter');
Route::post('/admin/system_function/filter', 'SystemFunctionController@filter')->name('system_function.filter');
Route::post('/admin/validation/filter', 'ValidationController@filter')->name('validation.filter');
Route::post('/admin/municipality/filter', 'MunicipalityController@filter')->name('municipality.filter');
Route::post('/admin/fed_entity/filter', 'FedEntityController@filter')->name('fed_entity.filter');
Route::post('/admin/history/filter', 'HistoryController@filter')->name('history.filter');
Route::post('/admin/role/filter', 'RoleController@filter')->name('role.filter');
Route::post('/admin/phone_code/filter', 'PhoneCodeController@filter')->name('phone_code.filter');
Route::post('/admin/area/filter', 'AreaController@filter')->name('area.filter');
Route::post('/admin/loc_district/filter', 'LocDistrictController@filter')->name('loc_district.filter');
Route::post('/admin/fed_district/filter', 'FedDistrictController@filter')->name('fed_district.filter');
Route::post('/admin/communication/filter', 'CommunicationController@filter')->name('communication.filter');
Route::post('/admin/section/filter', 'SectionController@filter')->name('section.filter');
Route::post('/admin/block/filter', 'BlockController@filter')->name('block.filter');
Route::post('/admin/colony/filter', 'ColonyController@filter')->name('colony.filter');
Route::post('/admin/ocupation/filter', 'OcupationController@filter')->name('ocupation.filter');
Route::post('/admin/street/filter', 'StreetController@filter')->name('street.filter');
Route::post('/admin/postal_code/filter', 'PostalCodeController@filter')->name('postal_code.filter');
Route::post('/admin/user/filter', 'UserController@filter')->name('user.filter');


//switch entre los lenguajes
Route::get('lang/{lang}','LanguageController@swap')->name('lang.swap');

//middleware para asegurar autenticacion
Route::group(['middleware' => 'auth','middleware' => 'role'],function(){

    //dashboard
    Route::get('/dashboard/manage_persons', 'DashBoardController@managePerson')->name('dashboard.managePerson');
    Route::get('/dashboard/assign_representing_table', 'DashBoardController@assignRepresentingTable')->name('dashboard.assignRepresentingTable');

    Route::get('/admin/mydata/{id}', 'PersonController@myData')->name('person.mydata');
    Route::post('/admin/updateMyData/{id}', 'PersonController@updateMyData')->name('person.updateMyData');

    Route::get('/admin/home/', 'HomeController@index')->name('home');
    Route::get('/user/profile', 'UserController@profile')->name('profile');
    Route::post('/user/profile', 'UserController@update_profile')->name('update_profile');

    Route::resource('/admin/area', 'AreaController');
    Route::resource('/admin/box', 'BoxController');
    Route::resource('/admin/box_type', 'BoxTypeController');
    Route::resource('/admin/group', 'GroupController');
    Route::resource('/admin/political_function', 'PoliticalFunctionController');
    Route::resource('/admin/system_function', 'SystemFunctionController');
    Route::resource('/admin/role', 'RoleController');
    Route::resource('/admin/social_network', 'SocialNetworkController');
    Route::resource('/admin/validation', 'ValidationController');
    Route::resource('/admin/municipality', 'MunicipalityController');
    Route::resource('/admin/fed_entity', 'FedEntityController');
    Route::resource('/admin/history', 'HistoryController');
    Route::resource('/admin/person', 'PersonController');
    Route::resource('/admin/address', 'AddressController');
    Route::resource('/admin/phone_code', 'PhoneCodeController');
    Route::resource('/admin/fed_district', 'FedDistrictController');
    Route::resource('/admin/loc_district', 'LocDistrictController');
    Route::resource('/admin/zone', 'ZoneController');
    Route::resource('/admin/user', 'UserController');
    Route::resource('/admin/section', 'SectionController');
    Route::resource('/admin/block', 'BlockController');
    Route::resource('/admin/colony', 'ColonyController');
    Route::resource('/admin/ocupation', 'OcupationController');
    Route::resource('/admin/street', 'StreetController');
    Route::resource('/admin/postal_code', 'PostalCodeController');
    Route::resource('/admin/gen_representation', 'GenRepresentationController');

    Route::resource('/admin/setting', 'SettingController');
    Route::resource('/admin/notification', 'NotificationController');


    //import data
    Route::post('/admin/group/import/data', 'GroupController@importData')->name('group.importData');
    Route::post('/admin/social_network/import/data', 'SocialNetworkController@importData')->name('social_network.importData');
    Route::post('/admin/role/import/data', 'RoleController@importData')->name('role.importData');
    Route::post('/admin/area/import/data', 'AreaController@importData')->name('area.importData');
    Route::post('/admin/loc_district/import/data', 'LocDistrictController@importData')->name('loc_district.importData');
    Route::post('/admin/fed_district/import/data', 'FedDistrictController@importData')->name('fed_district.importData');
    Route::post('/admin/person/import/data', 'PersonController@importData')->name('person.importData');
    Route::post('/admin/address/import/data', 'AddressController@importData')->name('address.importData');
    Route::post('/admin/fed_entity/import/data', 'FedEntityController@importData')->name('fed_entity.importData');
    Route::post('/admin/system_function/import/data', 'SystemFunctionController@importData')->name('system_function.importData');
    Route::post('/admin/political_function/import/data', 'PoliticalFunctionController@importData')->name('political_function.importData');
    Route::post('/admin/box_type/import/data', 'BoxTypeController@importData')->name('box_type.importData');
    Route::post('/admin/validation/import/data', 'ValidationController@importData')->name('validation.importData');
    Route::post('/admin/zone/import/data', 'ZoneController@importData')->name('zone.importData');
    Route::post('/admin/municipality/import/data', 'MunicipalityController@importData')->name('municipality.importData');
    Route::post('/admin/section/import/data', 'SectionController@importData')->name('section.importData');
    Route::post('/admin/block/import/data', 'BlockController@importData')->name('block.importData');
    Route::post('/admin/colony/import/data', 'ColonyController@importData')->name('colony.importData');
    Route::post('/admin/ocupation/import/data', 'OcupationController@importData')->name('ocupation.importData');
    Route::post('/admin/street/import/data', 'StreetController@importData')->name('street.importData');
    Route::post('/admin/postal_code/import/data', 'PostalCodeController@importData')->name('postal_code.importData');
    Route::post('/admin/gen_representation/import/data', 'GenRepresentationController@importData')->name('gen_representation.importData');

    //call address search
    Route::post('/admin/address/post_add', 'AddressController@post_add')->name('address.post_add');

    Route::get('/admin/role/{id}/assign_functions/', 'RoleController@assignFunction')->name('role.assignFunction');
    Route::get('/admin/role/{id}/assign_groups/', 'RoleController@assignGroup')->name('role.assignGroup');
    Route::get('/admin/municipality/{id}/responsible/', 'MunicipalityController@setResponsible')->name('municipality.responsible');
    Route::get('/admin/fed_entity/{id}/responsible/', 'FedEntityController@setResponsible')->name('fed_entity.responsible');

    Route::post('/admin/role/save_role_system_function/{id}', 'RoleController@saveFunctionRole')->name('role.saveFunctionRole');
    Route::post('/admin/role/save_role_group/{id}', 'RoleController@saveGroupRole')->name('role.saveGroupRole');
    Route::post('/admin/municipality/save_responsible/{id}', 'MunicipalityController@saveResponsible')->name('municipality.saveResponsible');
    Route::post('/admin/fed_entity/save_responsible/{id}', 'FedEntityController@saveResponsible')->name('fed_entity.saveResponsible');

    //excel, csv
    Route::get('/admin/group/export/{format}/{ids}', 'GroupController@exportData')->name('group.exportData');
    Route::get('/admin/role/export/{format}/{ids}', 'RoleController@exportData')->name('role.exportData');
    Route::get('/admin/zone/export/{format}/{ids}', 'ZoneController@exportData')->name('zone.exportData');
    Route::get('/admin/history/export/{format}/{ids}', 'HistoryController@exportData')->name('history.exportData');
    Route::get('/admin/area/export/{format}/{ids}', 'AreaController@exportData')->name('area.exportData');
    Route::get('/admin/loc_district/export/{format}/{ids}', 'LocDistrictController@exportData')->name('loc_district.exportData');
    Route::get('/admin/fed_district/export/{format}/{ids}', 'FedDistrictController@exportData')->name('fed_district.exportData');
    Route::get('/admin/person/export/{format}/{ids}', 'PersonController@exportData')->name('person.exportData');
    Route::get('/admin/setting/export/{format}', 'SettingController@exportData')->name('setting.exportData');
    Route::get('/admin/address/export/{format}/{ids}', 'AddressController@exportData')->name('address.exportData');
    Route::get('/admin/fed_entity/export/{format}/{ids}', 'FedEntityController@exportData')->name('fed_entity.exportData');
    Route::get('/admin/system_function/export/{format}/{ids}', 'SystemFunctionController@exportData')->name('system_function.exportData');
    Route::get('/admin/political_function/export/{format}/{ids}', 'PoliticalFunctionController@exportData')->name('political_function.exportData');
    Route::get('/admin/box/export/{format}/{ids}', 'BoxController@exportData')->name('box.exportData');
    Route::get('/admin/box_type/export/{format}/{ids}', 'BoxTypeController@exportData')->name('box_type.exportData');
    Route::get('/admin/validation/export/{format}/{ids}', 'ValidationController@exportData')->name('validation.exportData');
    Route::get('/admin/social_network/export/{format}/{ids}', 'SocialNetworkController@exportData')->name('social_network.exportData');
    Route::get('/admin/municipality/export/{format}/{ids}', 'MunicipalityController@exportData')->name('municipality.exportData');
    Route::get('/admin/phone_code/export/{format}/{ids}', 'PhoneCodeController@exportData')->name('phone_code.exportData');
    Route::get('/admin/user/export/{format}/{ids}', 'UserController@exportData')->name('user.exportData');
    Route::get('/admin/section/export/{format}/{ids}', 'SectionController@exportData')->name('section.exportData');
    Route::get('/admin/block/export/{format}/{ids}', 'BlockController@exportData')->name('block.exportData');
    Route::get('/admin/colony/export/{format}/{ids}', 'ColonyController@exportData')->name('colony.exportData');
    Route::get('/admin/ocupation/export/{format}/{ids}', 'OcupationController@exportData')->name('ocupation.exportData');
    Route::get('/admin/street/export/{format}/{ids}', 'StreetController@exportData')->name('street.exportData');
    Route::get('/admin/postal_code/export/{format}/{ids}', 'PostalCodeController@exportData')->name('postal_code.exportData');
    Route::get('/admin/notification/export/{format}/{ids}', 'NotificationController@exportData')->name('notification.exportData');

    //import data
    Route::post('/admin/group/import/data', 'GroupController@importData')->name('group.importData');
    Route::post('/admin/social_network/import/data', 'SocialNetworkController@importData')->name('social_network.importData');
    Route::post('/admin/role/import/data', 'RoleController@importData')->name('role.importData');
    Route::post('/admin/area/import/data', 'AreaController@importData')->name('area.importData');
    Route::post('/admin/loc_district/import/data', 'LocDistrictController@importData')->name('loc_district.importData');
    Route::post('/admin/person/import/data', 'PersonController@importData')->name('person.importData');
    Route::post('/admin/address/import/data', 'AddressController@importData')->name('address.importData');
    Route::post('/admin/fed_entity/import/data', 'FedEntityController@importData')->name('fed_entity.importData');
    Route::post('/admin/system_function/import/data', 'SystemFunctionController@importData')->name('system_function.importData');
    Route::post('/admin/political_function/import/data', 'PoliticalFunctionController@importData')->name('political_function.importData');
    Route::post('/admin/box_type/import/data', 'BoxTypeController@importData')->name('box_type.importData');
    Route::post('/admin/box/import/data', 'BoxController@importData')->name('box.importData');
    Route::post('/admin/validation/import/data', 'ValidationController@importData')->name('validation.importData');
    Route::post('/admin/municipality/import/data', 'MunicipalityController@importData')->name('municipality.importData');
    Route::post('/admin/user/import/data', 'UserController@importData')->name('user.importData');
    Route::post('/admin/section/import/data', 'SectionController@importData')->name('section.importData');
    Route::post('/admin/block/import/data', 'BlockController@importData')->name('block.importData');
    Route::post('/admin/colony/import/data', 'ColonyController@importData')->name('colony.importData');
    Route::post('/admin/street/import/data', 'StreetController@importData')->name('street.importData');
    Route::post('/admin/postal_code/import/data', 'PostalCodeController@importData')->name('postal_code.importData');
    Route::post('/admin/phone_code/import/data', 'PhoneCodeController@importData')->name('phone_code.importData');

    //call asign political functions
    Route::post('/admin/person/responsibilities/save/{id}', 'PersonController@saveResponsibilities')->name('person.saveResponsibilities');
    Route::get('/admin/person/responsibilities/assign/{id}', 'PersonController@assignResponsibilities')->name('person.assignResponsibilities');

    //call assign representants in box
    Route::get('/admin/box/representing/assign/{id}', 'BoxController@assignRepresenting')->name('box.assignRepresenting');
    Route::post('/admin/box/representing/save/{id}', 'BoxController@saveRepresenting')->name('box.saveRepresenting');

    Route::get('/admin/dashboard/representing/assign/{id}', 'DashBoardController@assignRepresenting')->name('dashboard.assignRepresenting');
    Route::post('/admin/dashboard/representing/save/{id}', 'DashBoardController@saveRepresenting')->name('dashboard.saveRepresenting');

    //acceptNotification
    Route::post('/admin/notification/accept/{id}', 'NotificationController@acceptNotification')->name('notification.acceptNotification');

    //assign boxes
    Route::get('/admin/gen_representation/assign_boxes/{id}', 'GenRepresentationController@assignBoxes')->name('gen_representation.assignBoxes');
    Route::post('/admin/gen_representation/assign_boxes/save/{id}', 'GenRepresentationController@saveAssignBoxes')->name('gen_representation.saveAssignBoxes');

});

//
////call filters
//Route::post('/admin/person/filter', 'PersonController@filter')->name('person.filter');
//Route::post('/admin/address/filter', 'AddressController@filter')->name('address.filter');
//Route::post('/admin/social_network/filter', 'SocialNetworkController@filter')->name('social_network.filter');
//Route::post('/admin/group/filter', 'GroupController@filter')->name('group.filter');
//Route::post('/admin/box/filter', 'BoxController@filter')->name('box.filter');
//Route::post('/admin/box_type/filter', 'BoxTypeController@filter')->name('box_type.filter');
//Route::post('/admin/political_function/filter', 'PoliticalFunctionController@filter')->name('political_function.filter');
//Route::post('/admin/system_function/filter', 'SystemFunctionController@filter')->name('system_function.filter');
//Route::post('/admin/validation/filter', 'ValidationController@filter')->name('validation.filter');
//Route::post('/admin/municipality/filter', 'MunicipalityController@filter')->name('municipality.filter');
//Route::post('/admin/fed_entity/filter', 'FedEntityController@filter')->name('fed_entity.filter');
//Route::post('/admin/history/filter', 'HistoryController@filter')->name('history.filter');
//Route::post('/admin/role/filter', 'RoleController@filter')->name('role.filter');
//Route::post('/admin/phone_code/filter', 'PhoneCodeController@filter')->name('phone_code.filter');
//Route::post('/admin/area/filter', 'AreaController@filter')->name('area.filter');
//Route::post('/admin/loc_district/filter', 'LocDistrictController@filter')->name('loc_district.filter');
//Route::post('/admin/communication/filter', 'CommunicationController@filter')->name('communication.filter');
//Route::post('/admin/zone/filter', 'ZoneController@filter')->name('zone.filter');
//Route::post('/admin/user/filter', 'UserController@filter')->name('user.filter');
//Route::post('/admin/section/filter', 'SectionController@filter')->name('section.filter');
//Route::post('/admin/block/filter', 'BlockController@filter')->name('block.filter');
//Route::post('/admin/notification/filter', 'NotificationController@filter')->name('notification.filter');
//Route::post('/admin/gen_representation/filter', 'GenRepresentationController@filter')->name('gen_representation.filter');
//Route::post('/admin/representing_table/filter', 'DashBoardController@representing_table_filter')->name('representing_table.filter');
//verify person
Route::get('/admin/person_validation/{temp_id}/verify','DefaultController@verify')->name('person.verify');
Route::post('/admin/person_validation/{temp_id}/save_verify', 'DefaultController@saveVerify')->name('person.saveVerify');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');















































