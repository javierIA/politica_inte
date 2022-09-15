@extends('admin.layout')

@section('script_up')
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API','')}}&callback=initMap"></script>
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('address.index') }}">{{ trans('admin.address') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('address.store') }}" enctype="multipart/form-data">

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

        <div class="form-group{{ $errors->any() ? ' has-error' : '' }}">
            <div class="col-md-3 " style="margin-top: -10px; padding-top: 10px; height: 853px">
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
                        <div class="panel-title roboto">{!! trans('admin.address') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.address') !!}">
                                    <a href="#personal_data_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="personal_data_tab" name="{{trans('admin.address')}}">
                                <div class="rcorners" style="margin-top: 20px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="street" class="col-sm-3 control-label">{{trans('admin.street')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="street" id="street" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="internal_number" class="col-sm-3 control-label">{{trans('admin.internal_number')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" name="internal_number" id="internal_number" autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="external_number" class="col-sm-3 control-label">{{trans('admin.external_number')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" name="external_number" id="external_number" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="neighborhood" class="col-sm-3 control-label">{{trans('admin.neighborhood')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="neighborhood" id="neighborhood" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="postal_code" class="col-sm-3 control-label">{{trans('admin.postal_code')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" name="postal_code" id="postal_code" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="id_municipality" class="col-sm-3 control-label">{{trans('admin.municipality')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control make_user recorder" name="id_municipality" id="id_municipality" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($municipalitys as $m)
                                                        <option value="{{ $m->id }}">{{ ' ('. trans('admin.municipality_key').': '.$m->municipality_key . ') '.$m->municipality_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="id_fed_entity" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control make_user recorder" name="id_fed_entity" id="id_fed_entity" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($fed_entitys as $f)
                                                        <option value="{{ $f->id }}">{{ ' ('. trans('admin.entity_key').': '.$f->entity_key . ') '.$f->entity_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="id_section" class="col-sm-3 control-label">{{trans('admin.section')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control make_user recorder" name="id_section" id="id_section" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($sections as $s)
                                                        <option value="{{ $s->id }}">{{ ' ('. trans('admin.section_type').': '.$s->section_type . ') '.$s->section_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="latitude" class="col-sm-3 control-label">{{trans('admin.latitude')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" name="latitude" id="latitude" readonly autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="longitude" class="col-sm-3 control-label">{{trans('admin.longitude')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" name="longitude" id="longitude" readonly autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-sm-offset-4 col-sm-6">
                                                <div class="map-section">
                                                    <div id="googleMap" style="width:100%; height:300px"></div>
                                                </div>
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
@endsection

@section('script_down')
    <script type="text/javascript">
        jQuery(document).ready(function($){

            $('.icon-picker').qlIconPicker({
                'mode'       : 'dialog',// show overlay 'dialog' panel or slide down 'inline' panel
                'closeOnPick': true,    // whether to close panel after picking or 'no'
                'save'       : 'class', // save icon 'class' or 'code' in the input field
                'size'       : '20px',      // class to be added to icon panel, 'large' is supported.
                'defaultIcon': 'fa fa-chrome',
                'iconSets' : {               // used to specify which launchers will be created
                    'fa' : '{!! trans('admin.select') !!}'       // create a launcher to pick fontawesome icons
                }
            });

            $('.remove-icon').remove();
        });
    </script>

    <script type="text/javascript">
        var infoWindow = null;
        var map = null;

        function getLatLngByZipcode(zipcode){
            var geocoder = new google.maps.Geocoder();
            var address = { 'componentRestrictions': { 'postalCode': zipcode }, region: "mx" };
            geocoder.geocode(address, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK){
                    var response = {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()};
                    var content = '('+ results[0].geometry.location.lat() + ', '+ results[0].geometry.location.lng()+')';
                    setCartel(response, content);
                    map.setCenter(response);
                    $('#latitude').val(results[0].geometry.location.lng());
                    $('#longitude').val(results[0].geometry.location.lat());
                }
            });
        }

        function setCartel(myLatlng, content){
            if(infoWindow != null)
                infoWindow.close();
            infoWindow = new google.maps.InfoWindow({content: content, position: myLatlng});
            infoWindow.open(map);
            return infoWindow;
        }

        function initMap() {
            var myLatlng = {lat: 31.755084644235964, lng: -106.44977465087733};
            var content = "{!! trans('admin.click_map') !!}";

            map = new google.maps.Map(document.getElementById('googleMap'), {zoom: 8, center: myLatlng});
            // Create the initial InfoWindow.
            infoWindow = setCartel(myLatlng, content);

            // Configure the click listener.
            map.addListener('click', function(mapsMouseEvent) {
                myLatlng = {lat: mapsMouseEvent.latLng.lat(), lng: mapsMouseEvent.latLng.lng()};
                // Create a new InfoWindow.
                infoWindow = setCartel(myLatlng, mapsMouseEvent.latLng.toString());
                $('#latitude').val(mapsMouseEvent.latLng.lat());
                $('#longitude').val(mapsMouseEvent.latLng.lng());
            });

            $( "#postal_code" ).focusout(function() {
                getLatLngByZipcode($(this).val());
            });
        }
    </script>

@endsection