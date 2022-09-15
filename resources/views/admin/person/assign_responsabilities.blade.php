@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('person.index') }}">{{ trans('admin.person') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered methods-form" method="POST" action="{{ route('person.saveResponsibilities', $person) }}">
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

        <input type="hidden" name="_method" value="POST"/>

        <div class="form-group{{ $errors->any() ? ' has-error' : '' }}">
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px">
                <h4 class="roboto">{{trans('admin.data')}}</h4>
                <div class="rcorners" style="height: 483px">
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
                    <div class="panel-heading">
                        <div class="panel-title roboto">{!! trans('admin.person') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.person') !!}">
                                    <a href="#user_tab" data-toggle="tab"><i class="entypo-user"></i></a>
                                </li>
                                @if($person->electoral_volunteer && (intval(explode('/', $person->credential_date)[2]) > intval($setting_info->election_year)))
                                    <li data-name="{!! trans('admin.political') !!}">
                                        <a href="#political_tab" data-toggle="tab"><i class="entypo-briefcase"></i></a>
                                    </li>
                                @endif
                                @if($person->territory_volunteer)
                                    <li data-name="{!! trans('admin.territorial') !!}">
                                        <a href="#territorial_tab" data-toggle="tab"><i class="entypo-map"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="user_tab" name="{{trans('admin.person')}}">
                                <div class="rcorners" style="height: 414px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="person_name" class="col-sm-3 control-label">{{trans('admin.name').'(s)'}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{$person->get_full_name()}}" readonly autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="birth_date" class="col-sm-3 control-label">{{trans('admin.birth_date')}}</label>
                                            <div class='col-sm-9' >
                                                <input type='text' class="form-control" value="{{$person->birth_date}}" readonly autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="person_sex" class="col-sm-3 control-label">{{trans('admin.person_sex')}}</label>
                                            <div class='col-sm-9'>
                                                <input type='text' class="form-control" value="{{$person_sex}}" readonly autofocus/>
                                            </div>
                                        </div>
                                        @if(!is_null($phone->getFullphone()))
                                            <div class="row" style="margin-top: 5px">
                                                <label for="person_phone" class="col-sm-3 control-label">{{trans('admin.person_phone')}}</label>
                                                <div class='col-sm-9'>
                                                    <input type='text' class="form-control" value="{{$phone->getFullphone()}}" readonly autofocus/>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!is_null($cellphone->getFullphone()))
                                            <div class="row" style="margin-top: 5px">
                                                <label for="person_cellphone" class="col-sm-3 control-label">{{trans('admin.person_cellphone')}}</label>
                                                <div class='col-sm-9'>
                                                    <input type='text' class="form-control" value="{{$cellphone->getFullphone()}}" readonly autofocus/>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!is_null($email->info))
                                            <div class="row" style="margin-top: 5px">
                                                <label for="person_email" class="col-sm-3 control-label">{{trans('admin.email')}}</label>
                                                <div class='col-sm-9'>
                                                    <input type='text' class="form-control" value="{{$email->info}}" readonly autofocus/>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row" style="margin-top: 5px">
                                            <label for="credential_date" class="col-sm-3 control-label">{{trans('admin.credential_date')}}<span style="color: red">*</span></label>
                                            <div class='input-group date col-sm-9 datetimepicker'  style="padding-right: 300px; padding-left: 15px;">
                                                <input type='text' class="form-control" value="{{$person->credential_date}}" @if(intval(explode('/', $person->credential_date)[2]) < intval($setting_info->election_year)) style="border-color: red" title="{{trans('admin.credential_out_date')}}" @endif readonly autofocus/>
                                            </div>
                                        </div>
                                        @if(!is_null($email->info))
                                            <div class="row" style="margin-top: 5px">
                                                <label for="person_address" class="col-sm-3 control-label">{{trans('admin.address')}}</label>
                                                <div class='col-sm-9'>
                                                    <textarea class="form-control" style="resize:none; height: 50px" readonly>{{$oficial_address->street. ', No.' .$oficial_address->external_number. ', ' .trans('admin.neighborhood'). ':' .$oficial_address->neighborhood. ', ' .trans('admin.postal_code'). ':'. $oficial_address->postal_code }}</textarea>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($person->electoral_volunteer && (intval(explode('/', $person->credential_date)[2]) > intval($setting_info->election_year)))
                                <div class="tab-pane" id="political_tab" name="{{trans('admin.political')}}" >
                                    <div class="rcorners">
                                        <div class="verticalLine">
                                            <div class="row">
                                                <label for="id_political" class="col-sm-3 control-label">{{trans('admin.responsibility')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="id_political" name="id_political" class="form-control select2 recorder personal_data geografic_association">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($functions as $f)
                                                            @if($f->type == 'political')
                                                                <option value="{{ $f->id }}" @if(!is_null($p_function) && $p_function->id == $f->id) selected @endif>{{ $f->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rcorners" style="margin-top: 10px; height:350px">
                                        <div class="verticalLine">
                                            <div class="row">
                                                <label for="fed_entity_id_political" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="fed_entity_id_political" name="fed_entity_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($fed_entitys as $f)
                                                            <option value="{{ $f->id }}" @if(!is_null($p_function) && $p_function->fed_entity_id == $f->id) selected @endif>{{ ' ('. trans('admin.entity_key').': '.$f->entity_key . ') '.$f->entity_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 5px">
                                                <label for="municipality_id_political" class="col-sm-3 control-label">{{trans('admin.municipality')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="municipality_id_political" name="municipality_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($municipalitys as $m)
                                                            <option value="{{ $m->id }}" @if(!is_null($p_function) && $p_function->municipality_id == $m->municipality_id) selected @endif >{{ ' ('. trans('admin.municipality_key').': '.$m->municipality_key . ') '.$m->municipality_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="fed_district_id_political" class="col-sm-3 control-label">{{trans('admin.fed_district')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="fed_district_id_political" name="fed_district_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($fed_district as $fd)
                                                            <option value="{{ $fd->id }}" @if(!is_null($p_function) && $p_function->fed_district_id == $fd->fed_district_id) selected @endif>{{ $fd->district_number }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="loc_district_id_political" class="col-sm-3 control-label">{{trans('admin.loc_district')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="loc_district_id_political" name="loc_district_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($loc_district as $ld)
                                                            <option value="{{ $ld->id }}" @if(!is_null($p_function) && $p_function->loc_district_id == $ld->loc_district_id) selected @endif>{{ $ld->district_number }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="area_id_political" class="col-sm-3 control-label">{{trans('admin.area')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="area_id_political" name="area_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($area as $a)
                                                            <option value="{{ $a->id }}" @if(!is_null($p_function) && $p_function->area_id == $ld->area_id) selected @endif>{{ $a->area_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="zone_id_political" class="col-sm-3 control-label">{{trans('admin.zone')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="zone_id_political" name="zone_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($zone as $z)
                                                            <option value="{{ $z->id }}" @if(!is_null($p_function) && $p_function->zone_id == $z->zone_id) selected @endif>{{ $z->zone_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="section_id_political" class="col-sm-3 control-label">{{trans('admin.section')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="section_id_political" name="section_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($sections as $s)
                                                            <option value="{{ $s->id }}" @if(!is_null($p_function) && $p_function->section_id == $s->section_id) selected @endif>{{ ' ('. trans('admin.section_type').': '.$s->section_type . ') '.$s->section_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="block_id_political" class="col-sm-3 control-label">{{trans('admin.block')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="block_id_political" name="block_id_political" class="form-control select2 recorder political">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($block as $b)
                                                            <option value="{{ $b->id }}" @if(!is_null($p_function) && $p_function->block_id == $b->block_id) selected @endif>{{ $b->block_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($person->territory_volunteer)
                                <div class="tab-pane" id="territorial_tab" name="{{trans('admin.territorial')}}">
                                    <div class="rcorners">
                                        <div class="verticalLine">
                                            <div class="row">
                                                <label for="id_territorial" class="col-sm-3 control-label">{{trans('admin.responsibility')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="id_territorial" name="id_territorial" class="form-control select2 recorder personal_data geografic_association">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($functions as $f)
                                                            @if($f->type == 'territorial')
                                                                <option value="{{ $f->id }}" @if(!is_null($t_function) && $t_function->id == $f->id) selected @endif>{{ $f->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rcorners" style="margin-top: 10px; height:350px">
                                        <div class="verticalLine">
                                            <div class="row">
                                                <label for="fed_entity_id_territorial" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="fed_entity_id_territorial" name="fed_entity_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($fed_entitys as $f)
                                                            <option value="{{ $f->id }}" @if(!is_null($t_function) && $t_function->fed_entity_id == $f->id) selected @endif>{{ ' ('. trans('admin.entity_key').': '.$f->entity_key . ') '.$f->entity_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 5px">
                                                <label for="municipality_id_territorial" class="col-sm-3 control-label">{{trans('admin.municipality')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="municipality_id_territorial" name="municipality_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($municipalitys as $m)
                                                            <option value="{{ $m->id }}" @if(!is_null($t_function) && $t_function->municipality_id == $m->municipality_id) selected @endif>{{ ' ('. trans('admin.municipality_key').': '.$m->municipality_key . ') '.$m->municipality_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="fed_district_id_territorial" class="col-sm-3 control-label">{{trans('admin.fed_district')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="fed_district_id_territorial" name="fed_district_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($fed_district as $fd)
                                                            <option value="{{ $fd->id }}" @if(!is_null($t_function) && $t_function->fed_district_id == $fd->fed_district_id) selected @endif>{{ $fd->district_number }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="loc_district_id_territorial" class="col-sm-3 control-label">{{trans('admin.loc_district')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="loc_district_id_territorial" name="loc_district_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($loc_district as $ld)
                                                            <option value="{{ $ld->id }} "@if(!is_null($t_function) && $t_function->loc_district_id == $ld->loc_district_id) selected @endif>{{ $ld->district_number }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="area_id_territorial" class="col-sm-3 control-label">{{trans('admin.area')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="area_id_territorial" name="area_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($area as $a)
                                                            <option value="{{ $a->id }}" @if(!is_null($t_function) && $t_function->area_id == $ld->area_id) selected @endif>{{ $a->area_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="zone_id_territorial" class="col-sm-3 control-label">{{trans('admin.zone')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="zone_id_territorial" name="zone_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($zone as $z)
                                                            <option value="{{ $z->id }}" @if(!is_null($t_function) && $t_function->zone_id == $z->zone_id) selected @endif>{{ $z->zone_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="section_id_territorial" class="col-sm-3 control-label">{{trans('admin.section')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="section_id_territorial" name="section_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($sections as $s)
                                                            <option value="{{ $s->id }}" @if(!is_null($t_function) && $t_function->section_id == $s->section_id) selected @endif>{{ ' ('. trans('admin.section_type').': '.$s->section_type . ') '.$s->section_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top: 5px">
                                                <label for="block_id_territorial" class="col-sm-3 control-label">{{trans('admin.block')}}</label>
                                                <div class="col-sm-9">
                                                    <select id="block_id_territorial" name="block_id_territorial" class="form-control select2 recorder territorial">
                                                        <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                        @foreach($block as $b)
                                                            <option value="{{ $b->id }}" @if(!is_null($t_function) && $t_function->block_id == $b->block_id) selected @endif >{{ $b->block_key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
        (function ($) {
            $(window).on('load', function () {
                $('.geografic_association').change(function() {
                    if($(this).attr('name') === undefined)
                        return;

                    var value = $(this).val();
                    var functions = {!! $functions !!};
                    var it = null;

                    if($(this).attr('name').includes("political")) $('.political').val('').change();
                    else $('.territorial').val('').change();

                    functions.forEach(function (item, index) {
                        if(item.id == value)
                            it = item;
                    });

                    if(it != null){
                        var temp = '_territorial';
                        var fun = {!! is_null($t_function)? 0: $t_function !!};
                        if(it.type === 'political'){
                            temp = '_political';
                            fun = {!! is_null($p_function)? 0: $p_function !!};
                        }

                        if(it.fed_entity === false) $('#fed_entity_id'+ temp).parent().parent().hide();
                        else {
                            $('#fed_entity_id'+ temp).parent().parent().show();
                            $('#fed_entity_id'+ temp).val(fun != 0 && fun.pivot.fed_entity_id != null? fun.pivot.fed_entity_id: null).change();
                        }

                        if(it.municipality === false) $('#municipality_id'+ temp).parent().parent().hide()
                        else {
                            $('#municipality_id'+ temp).parent().parent().show();
                            $('#municipality_id'+ temp).val(fun != 0 && fun.pivot.municipality_id != null? fun.pivot.municipality_id: null).change();
                        }

                        if(it.fed_district === false) $('#fed_district_id'+ temp).parent().parent().hide()
                        else {
                            $('#fed_district_id'+ temp).parent().parent().show();
                            $('#fed_district_id'+ temp).val(fun != 0 && fun.pivot.fed_district_id != null? fun.pivot.fed_district_id: null).change();
                        }

                        if(it.loc_district === false) $('#loc_district_id'+ temp).parent().parent().hide()
                        else {
                            $('#loc_district_id'+ temp).parent().parent().show();
                            $('#loc_district_id'+ temp).val(fun != 0 && fun.pivot.loc_district_id != null? fun.pivot.loc_district_id: null).change();
                        }

                        if(it.area === false) $('#area_id'+ temp).parent().parent().hide()
                        else {
                            $('#area_id'+ temp).parent().parent().show();
                            $('#area_id'+ temp).val(fun != 0 && fun.pivot.area_id != null? fun.pivot.area_id: null).change();
                        }

                        if(it.zone === false) $('#zone_id'+ temp).parent().parent().hide()
                        else {
                            $('#zone_id'+ temp).parent().parent().show();
                            $('#zone_id'+ temp).val(fun != 0 && fun.pivot.zone_id != null? fun.pivot.zone_id: null).change();
                        }

                        if(it.section === false) $('#section_id'+ temp).parent().parent().hide()
                        else {
                            $('#section_id'+ temp).parent().parent().show();
                            $('#section_id'+ temp).val(fun != 0 && fun.pivot.section_id != null? fun.pivot.section_id: null).change();
                        }

                        if(it.block === false) $('#block_id'+ temp).parent().parent().hide()
                        else {
                            $('#block_id'+ temp).parent().parent().show();
                            $('#block_id'+ temp).val(fun != 0 && fun.pivot.block_id != null? fun.pivot.block_id: null).change();
                        }
                    }
                });
                $('.geografic_association').change();

                $('#fed_entity_id_territorial').change(function() {
                    update_select('municipality_id_territorial','municipality_name', 'fed_entity_id', $(this).val() , {!! $municipalitys !!});
                });
                $('#municipality_id_territorial').change(function() {
                    update_select('section_id_territorial','section_key', 'municipality_id', $(this).val() , {!! $sections !!});
                });
                $('#loc_district_id_territorial').change(function() {
                    update_select('area_id_territorial','area_key', 'loc_district_id', $(this).val() , {!! $area !!});
                });
                $('#area_id_territorial').change(function() {
                    update_select('zone_id_territorial','zone_key', 'area_id', $(this).val() , {!! $zone !!});
                });
                $('#section_id_territorial').change(function() {
                    update_select('block_id_territorial','block_key', 'section_id', $(this).val() , {!! $block !!});
                });

        //----------------------political-------------------------

                $('#fed_entity_id_political').change(function() {
                    update_select('municipality_id_political','municipality_name', 'fed_entity_id', $(this).val() , {!! $municipalitys !!});
                });
                $('#municipality_id_political').change(function() {
                    update_select('section_id_political','section_key', 'municipality_id', $(this).val() , {!! $sections !!});
                });
                $('#loc_district_id_political').change(function() {
                    update_select('area_id_political','area_key', 'loc_district_id', $(this).val() , {!! $area !!});
                });
                $('#area_id_political').change(function() {
                    update_select('zone_id_political','zone_key', 'area_id', $(this).val() , {!! $zone !!});
                });
                $('#section_id_political').change(function() {
                    update_select('block_id_political','block_key', 'section_id', $(this).val() , {!! $block !!});
                });
                

                //$('.change_loader').trigger('change');
            });
        })(jQuery);
    </script>
@endsection