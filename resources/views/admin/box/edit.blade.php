@extends('admin.layout')

@section('script_up')
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API','')}}&callback=initMap"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('box.index') }}">{{ trans('admin.box') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('box.update', $box) }}" enctype="multipart/form-data">

        {{ csrf_field() }}

        @if ($errors->any())
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <input type="hidden" name="_method" value="PUT"/>

        <div class="form-group{{ $errors->any() ? ' has-error' : '' }}">
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 406px">
                <h4 class="roboto">{{trans('admin.data')}}</h4>
                <div class="rcorners" style="overflow-y: auto">
                    <table style="border: none; font-family: 'Open Sans Light'" id="data_recorded">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-8">
                <div class="col-md-12" style="margin-bottom: 15px">
                    <div class="col-md-7">
                        <div class="top_rcorners" style="margin-left: -30px; color: #003756; font-family: 'Century Gothic'">
                            {{$title}}
                        </div>
                    </div>
                </div>

                <div class="panel minimal" >
                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title roboto">{!! trans('admin.box') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.box') !!}">
                                    <a href="#group_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="data_tab" name="{{trans('admin.box')}}">
                                <div class="rcorners" style="margin-top: 10px">
                                    <div class="verticalLine">
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_section" class="col-sm-3 control-label">{{trans('admin.section')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="select2 form-control recorder" name="id_section" id="id_section" data-allow-clear="true" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($section as $item)
                                                        <option value="{{ $item->id }}" @if($box->id_section == $item->id) selected @endif>{{ $item->section_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_box_type" class="col-sm-3 control-label">{{trans('admin.box_type')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="select2 form-control recorder" name="id_box_type" id="id_box_type" data-allow-clear="true" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($type as $item)
                                                        <option value="{{ $item->id }}" @if($box->id_box_type == $item->id) selected @endif>{{ $item->box_type_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="owner_name" class="col-sm-3 control-label">{{trans('admin.owner')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control recorder"  name="owner_name" id="owner_name" value="{{$box->owner_name}}" autofocus/>
                                            </div>
                                        </div>

                                        <div class="row" data-class="id_address"  style="margin-top: 5px">
                                            <label for="id_address_text"
                                                   class="col-sm-3 control-label">{{ trans('admin.address') }}</label>
                                            <div class="col-sm-7">
                                                <input type="hidden" name="id_address" id="id_address" value="{{$box->id_address}}"/>
                                                <input type="text"
                                                       class="form-control readonly recorder modal_input_button"
                                                       id="id_address_text"
                                                       data-button="address_text_button"
                                                       data-column='address',
                                                       data-filter = 'filter-menu-address',
                                                       autocomplete="off"
                                                       value="{{!is_null($box->id_address)? $box->get_address()->street.', No.'.$box->get_address()->external_number.', '.trans('admin.neighborhood').': '. $box->get_address()->neighborhood.', '.trans('admin.postal_code').' '.$box->get_address()->postal_code:''}}"
                                                       required
                                                       readonly/>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-primary btn-sm add_address_button"
                                                        title="{{trans('admin.add_address')}}" data-toggle="modal"
                                                        id="myModalAddAddress_button"
                                                        data-target="#myModalAddAddress"
                                                        data-class="id_address">
                                                    <i class="entypo-plus"></i>
                                                </button>
                                                <button type="button" id="address_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                        title="{{trans('admin.search_address')}}" data-toggle="modal"
                                                        data-target="#myModalAddress" data-class="id_address">
                                                </button>
                                                <button type="button" name="btnTrash"
                                                        class="btn btn-danger btn-sm clean_button"
                                                        title="{{trans('admin.clean')}}"
                                                        data-class="id_address">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{--<div class="row" data-class="owner" style="margin-top: 5px">
                                            <label for="owner_text" class="col-sm-3 control-label recorder">{{ trans('admin.owner') }}</label>
                                            <div class="col-sm-7">
                                                <input type="hidden" name="owner" id="owner" value="{{$box->owner}}"/>
                                                <input type="text"
                                                       class="form-control readonly recorder modal_input_button"
                                                       id="owner_text"
                                                       data-button="owner_text_button"
                                                       data-column='person',
                                                       data-filter = 'filter-menu-person',
                                                       autocomplete="off"
                                                       value="{{!is_null($box->owner)? $box->owner()->get()[0]->get_full_name(): ''}}"
                                                       readonly/>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" id="owner_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                        title="{{trans('admin.search_owner')}}" data-toggle="modal"
                                                        data-target="#myModalPerson" data-class="owner">
                                                    <i class="entypo-search"></i>
                                                </button>
                                                <button type="button" name="btnTrash"
                                                        class="btn btn-danger btn-sm clean_button"
                                                        title="{{trans('admin.clean')}}" data-class="owner">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </div>--}}

                                       {{-- <div class="row" style="margin-top: 5px">
                                            <label for="address_text" class="col-sm-3 control-label">{{trans('admin.address')}}</label>
                                            <div class="col-sm-9" >
                                                <textarea style="resize:none; height: 91px" class="form-control recorder" name="address_text" id="address_text">{{$box->address_text}}</textarea>
                                            </div>
                                        </div>--}}

                                        <div class="row" style="margin-top: 5px">
                                            <label for="file" class="col-sm-3 control-label">{{trans('admin.map')}}</label>
                                            <div class="col-sm-9">
                                                <input  class="form-control make_user recorder" type="file" name="file" id="file" accept="application/pdf">
                                                <br>
                                                @if(!is_null($box->map_pdf))
                                                    <a href="{{ url('pdf/'.$box->getTable().'/'.basename($box->map_pdf)) }}" add target="_blank" class="d-flex align-items-center justify-content-center login"><span ><i class="fa fa-map fa-lg"> {{basename($box->map_pdf)}}</i></span></a>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="description" class="col-sm-3 control-label">{{trans('admin.description')}}</label>
                                            <div class="col-sm-9" >
                                                <textarea style="resize:none; height: 91px" class="form-control make_user recorder" name="description" id="description">{{$box->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-offset-5 col-sm-3 text-center">
                <button type="submit" class="btn btn-default submit_form">{{ trans('admin.send') }}</button>
            </div>
        </div>

    </form>

    <!-- Address modal -->
    <div class="modal fade" id="myModalAddress">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{trans('admin.filter') . ' '. trans('admin.of'). ' '. trans('admin.address')}}</h4>
                </div>
                <div class="modal-body" id="modalBody">
                    {{-- start filter--}}
                    <div class="rcorners" style="margin-bottom: 5px">
                        <form role="form" id="filter-form-address" class="form-horizontal form-groups-bordered" method="post">
                            {{ csrf_field() }}
                            <div class="row" style="padding-left: 30px">
                                <div class="col-sm-offset-11 col-sm-1 filter_submit" >
                                    <button type="submit" class="btn btn-blue btn-icon pull-right" style="margin-top: -5px;">
                                        {{trans('admin.search')}}
                                        <i class="entypo-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="filter-menu-address" hidden style="margin-bottom: 10px"></div>
                        </form>
                    </div>
                    {{-- end filter--}}
                    <table class="table table-bordered table-striped datatable" id="search-address-table" style="width: 100%">
                        <thead>
                        <tr>
                            <th>{{trans('admin.street')}}</th>
                            <th>{{trans('admin.postal_code')}}</th>
                            <th>{{trans('admin.municipality')}}</th>
                            <th>{{trans('admin.fed_entity')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close"
                            data-dismiss="modal">{{trans('admin.close')}}</button>
                    <button type="button"
                            class="btn btn-primary modal-select-address disabled">{{trans('admin.select')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Address modal end -->

    <!-- Add Address modal -->
    <div class="modal fade" id="myModalAddAddress">
        <div class="modal-dialog" role="document" style="width: 600px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{trans('admin.add_address')}}</h4>
                </div>
                <div class="modal-body ui-front" id="modalBody">
                    <form role="form" id="modal-form-add-address"
                          class="form-horizontal form-groups-bordered modal-form-address" method="post"
                          data-class="id_address">
                        {{ csrf_field() }}
                        <div class="tab-content">
                            <div class="tab-pane active" id="personal_data_tab" name="{{trans('admin.address')}}">
                                <div class="rcorners" style="margin-top: 10px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="postal_code"
                                                   class="col-sm-3 control-label">{{trans('admin.postal_code')}}<span
                                                        style="color: red" >*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control postal_code"
                                                       name="postal_code"
                                                       autocomplete="off"
                                                       id="postal_code" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="street" class="col-sm-3 control-label">{{trans('admin.street')}}
                                                <span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control autocomplete"
                                                       data-url="{{route('street.filter')}}"
                                                       data-data="name"
                                                       autocomplete="off"
                                                       name="street" id="street"
                                                       required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="neighborhood" class="col-sm-3 control-label">{{trans('admin.neighborhood')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control autocomplete"
                                                       data-url="{{route('colony.filter')}}"
                                                       data-data="name"
                                                       autocomplete="off"
                                                       name="neighborhood" id="neighborhood" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="internal_number"
                                                   class="col-sm-3 control-label">{{trans('admin.internal_number')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="internal_number"
                                                       id="internal_number" autocomplete="off" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="external_number"
                                                   class="col-sm-3 control-label">{{trans('admin.external_number')}}
                                                <span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="external_number"
                                                       id="external_number" autocomplete="off" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_fed_entity_address" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="id_fed_entity_address" id="id_fed_entity_address" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($fed_entitys as $f)
                                                        <option value="{{ $f->id }}" >{{ $f->entity_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_municipality_address"
                                                   class="col-sm-3 control-label">{{trans('admin.municipality')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="id_municipality_address"
                                                        id="id_municipality_address" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($municipalitys as $m)
                                                        <option value="{{ $m->id }}" data-parent="{{$m->fed_entity_id}}">{{ $m->municipality_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px"><label for="id_section_address" class="col-sm-3 control-label">{{trans('admin.section')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="id_section_address" id="id_section_address" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($sections as $s)
                                                        <option value="{{ $s->id }}" data-parent="{{$s->municipality_id}}">{{ $s->section_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="latitude"
                                                   class="col-sm-3 control-label">{{trans('admin.latitude')}}<span
                                                        style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="latitude" id="latitude"
                                                       readonly autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="longitude"
                                                   class="col-sm-3 control-label">{{trans('admin.longitude')}}<span
                                                        style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="longitude"
                                                       id="longitude" readonly autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-sm-12">
                                                <div class="map-section">
                                                    <div id="googleMap" style="width:100%; height:300px"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="row">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-default">{{trans('admin.send')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Address modal end -->

    <!-- Person modal -->
    <div class="modal fade" id="myModalPerson">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{trans('admin.filter') . ' '. trans('admin.of'). ' '. trans('admin.person')}}</h4>
                </div>
                <div class="modal-body" id="modalBody">
                     {{--start filter--}}
                    <div class="rcorners">
                        <form role="form" id="filter-form-person" class="form-horizontal form-groups-bordered" method="post">
                            {{ csrf_field() }}
                            <div class="row" style="padding-left: 30px">
                                <div class="col-sm-offset-11 col-sm-1 filter_submit" >
                                    <button type="submit" class="btn btn-blue btn-icon pull-right" style="margin-top: -5px;">
                                        {{trans('admin.search')}}
                                        <i class="entypo-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="filter-menu-person" hidden style="margin-bottom: 10px"></div>
                        </form>
                    </div>
                     {{--end filter--}}
                    <table class="table table-bordered table-striped datatable" id="search-person-table" style="width: 100%">
                        <thead>
                        <tr>
                            <th>{{trans('admin.name')}}</th>
                            <th>{{trans('admin.email')}}</th>
                            <th>{{trans('admin.person_cellphone')}}</th>
                            <th>{{trans('admin.elector_key')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close"
                            data-dismiss="modal">{{trans('admin.close')}}</button>
                    <button type="button"
                            class="btn btn-primary modal-select-person disabled">{{trans('admin.select')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Person modal end -->
@endsection

@section('script_down')
    <script type="text/javascript">
        var infoWindow = null;
        var map = null;

        function getLatLngByZipcode(zipcode) {
            var geocoder = new google.maps.Geocoder();
            var address = {'componentRestrictions': {'postalCode': zipcode}, region: "mx"};
            geocoder.geocode(address, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var response = {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()};
                    var content = '(' + results[0].geometry.location.lat() + ', ' + results[0].geometry.location.lng() + ')';
                    setCartel(response, content);
                    map.setCenter(response);
                    $('#latitude').val(results[0].geometry.location.lng());
                    $('#longitude').val(results[0].geometry.location.lat());
                }
            });
        }

        function setCartel(myLatlng, content) {
            if (infoWindow != null)
                infoWindow.close();
            infoWindow = new google.maps.InfoWindow({content: content, position: myLatlng});
            infoWindow.open(map);
            return infoWindow;
        }

        function initMap() {
            var myLatlng = {lat: 31.755084644235964, lng: -106.44977465087733};
            var content = "{!! trans('admin.click_map') !!}";

            map = new google.maps.Map(document.getElementById('googleMap'), {zoom: 16, center: myLatlng});
            // Create the initial InfoWindow.
            infoWindow = setCartel(myLatlng, content);

            // Configure the click listener.
            map.addListener('click', function (mapsMouseEvent) {
                myLatlng = {lat: mapsMouseEvent.latLng.lat(), lng: mapsMouseEvent.latLng.lng()};
                // Create a new InfoWindow.
                infoWindow = setCartel(myLatlng, mapsMouseEvent.latLng.toString());
                $('#latitude').val(mapsMouseEvent.latLng.lat());
                $('#longitude').val(mapsMouseEvent.latLng.lng());
            });

            $('.postal_code').focusout(function () {
                var filter = {'number':$(this).val()}

                $.post("{{route('postal_code.filter')}}",
                    {
                        "_token": "{{ csrf_token() }}",
                        filter,
                        'autocomplete':true,
                        'allparams':true
                    },
                    function (data, status) {
                        var decoded_data = jQuery.parseJSON(data);
                        if(decoded_data.length > 0 ){
                            $('#id_fed_entity_address').val(decoded_data[0].fed_entity_id);
                            $('#id_municipality_address').val(decoded_data[0].municipality_id);
                        }
                    });
                getLatLngByZipcode($(this).val());
            });
        }

        function fillsearch(columns, menu){
            var keys = Object.keys(columns);
            update_filters(keys, columns, menu, false);
        }

        (function ($) {
            $(window).on('load', function () {

                $('.recorder').change();

                $(".modal-select-address").click(function () {
                    var item = search_address_table.DataTable().rows({selected: true}).data();
                    var obj = item[0];
                    $('#' + $(this).data("class") + '_text').val(obj.street + ', No.' + obj.external_number + ', {!! trans('admin.neighborhood') !!}:' + obj.neighborhood + ', {!! trans('admin.postal_code') !!}:' + obj.postal_code);

                    $('#' + $(this).data("class")).val(obj.id_address);
                    if ($(this).data("class") == 'id_address')
                        setMunicipioEstado(obj);
                    record($('#' + $(this).data("class") + '_text'));
                    $('.modal-close').click();
                });

                $('.modal_input_button').click(function(){
                    var butt = $(this).data('button');
                    var column = $(this).data('column');
                    var filter = $(this).data('filter');
                    $('#'+butt).click();

                    $('#'+ filter).empty();
                    if(column == 'person')
                        fillsearch({!! json_encode($columns_person) !!}, filter);
                    else if(column == 'address')
                        fillsearch({!! json_encode($columns_address) !!}, filter);
                });

                /*address table*/
                var search_address_table = $("#search-address-table");
                search_address_table.DataTable({
                    "language":{
                        "url": "{{ asset('js/datatable/'.App::getLocale().'/datatable.json') }}",
                        "select": {
                            rows: "{!!trans('admin.rows_selected')!!}"
                        }
                    },
                    select: {
                        style: 'single'
                    },
                    "searching": false,
                    "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                    "stateSave": true,
                    "displayLength": 10,
                    "pageLength": 10,
                    //desde aqui
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        "url": "{{route('address.filter')}}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d){return update_params(d,'filter-form-address')}
                    },
                    "columns": [
                        { "data": "street" },
                        { "data": "postal_code" },
                        { "data": "municipality_name" },
                        { "data": "entity_name" },
                    ]
                });
                search_address_table.DataTable()
                    .on('select', function (e, dt, type, indexes) {
                        $('.modal-select-address').removeClass('disabled');
                    })
                    .on('deselect', function (e, dt, type, indexes) {
                        $('.modal-select-address').addClass('disabled');
                    });

                search_address_table.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
                    minimumResultsForSearch: -1
                });

                /*person table*/
                var search_person_table = $("#search-person-table");
                search_person_table.DataTable({
                    "language":{
                        "url": "{{ asset('js/datatable/'.App::getLocale().'/datatable.json') }}",
                        "select": {
                            rows: "{!!trans('admin.rows_selected')!!}"
                        }
                    },
                    select: {
                        style: 'single'
                    },
                    "searching": false,
                    "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                    "stateSave": true,
                    "displayLength": 10,
                    "pageLength": 10,
                    //desde aqui
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        "url": "{{route('person.filter')}}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d){update_params(d,'filter-form-person')}
                    },
                    "columns": [
                        { "data": "full_name" },
                        { "data": "persons_email" },
                        { "data": "persons_cellphone" },
                        { "data": "elector_key" },
                    ]
                });
                search_person_table.DataTable()
                    .on('select', function (e, dt, type, indexes) {
                        $('.modal-select-person').removeClass('disabled');
                    })
                    .on('deselect', function (e, dt, type, indexes) {
                        $('.modal-select-person').addClass('disabled');
                    });

                search_person_table.closest('.dataTables_wrapper').find('select').select2({
                    minimumResultsForSearch: -1
                });


                $(".search_button").click(function () {
                    $('#modal-form-person').trigger("reset");
                    $('#modal-form-address').trigger("reset");
                    $(".modal-select-person").data('class', $(this).data("class"));
                    $(".modal-select-address").data('class', $(this).data("class"));
                });

                function setMunicipioEstado(obj) {
                    var municipal = {!! $municipalitys !!};
                    for (var i = 0; i < municipal.length; i++)
                        if (municipal[i].id == obj.id_municipality) {
                            $('#id_municipality').val(obj.id_municipality);
                            $('#id_municipality_text').val(municipal[i].municipality_name);
                            break
                        }

                    var fed_entity = {!! $fed_entitys !!};
                    for (var i = 0; i < fed_entity.length; i++)
                        if (fed_entity[i].id == obj.id_fed_entity) {
                            $('#id_fed_entity').val(obj.id_fed_entity);
                            $('#id_fed_entity_text').val(fed_entity[i].entity_name);
                            break;
                        }

                    var sections = {!! $sections !!};
                    for (var i = 0; i < sections.length; i++)
                        if (sections[i].id == obj.id_section) {
                            $('#id_section').val(obj.id_section);
                            $('#id_section_text').val(sections[i].section_key);
                            break;
                        }
                }

                $(".modal-select-person").click(function () {
                    var item = search_person_table.DataTable().rows({selected: true}).data();
                    var obj = item[0];

                    $('#' + $(this).data("class") + '_text').val(obj.full_name);
                    $('#' + $(this).data("class")).val(obj.id_persons);
                    record($('#' + $(this).data("class") + '_text'));
                    $('.modal-close').click();
                });

                $(".clean_button").click(function () {
                    var name = $(this).data('class');
                    $('#' + name + '_text').val('');
                    $('#' + name).val('');
                    record($('#' + name + '_text'));
                    fill_icons();
                });

                $( "#filter-form-person" ).submit(function( event ) {
                    event.preventDefault();
                    search_person_table.DataTable().ajax.reload();
                });
            });
        })(jQuery);
    </script>
@endsection