@extends('admin.layout')

@section('script_up')
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API','')}}&callback=initMap"></script>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}"><i class="fa fa-home"></i>{{ trans('admin.home') }}</a>
    </li>
    <li>
        <a href="{{ route('person.index') }}">{{trans('admin.person')}}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered person_form" method="post" action="{{ route('person.update', $person) }}" enctype="multipart/form-data">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px">
                <div class="rcorners" style="height: 160px; text-align: center;">
                    <a href="#" data-toggle="modal" data-target="#myModalViewCard" class="viewcard"><img id="card" src="{{url('pdf/persons/'.$person->elector_key.'/front.jpeg')}}" style="height: 100%; max-width: 210px"></a>
                </div>

                <h4 class="roboto" style="margin-top: 15px;">{{trans('admin.person_information')}}</h4>
                <div class="rcorners" style="height: 524px; overflow-y: auto">
                    <table style="border: none; font-family: 'Open Sans Light'" id="data_recorded">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-9">
                <div class="col-md-12" style="      margin-bottom: 15px">
                    <div class="col-md-12">
                        <div class="col-md-7" >
                            <div class="top_rcorners" style="margin-left: -45px; color: #003756; font-family: 'Century Gothic'; width: 350px">
                                {{$title}}
                            </div>
                        </div>
                        <div class="col-md-5" style="margin-left: -45px; color: #003756; font-family: 'Century Gothic'; width: 280px">
                            <div class="top_rcorners" style="font-size: 15px;">
                                {{ count(auth()->user()->person()->get())>0? (auth()->user()->person()->get()[0])->get_full_name(): auth()->user()->name}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7" style="margin-top: 10px; height: 60px">
                        <div class="rcorners" style="margin-left: -30px;">
                            <div class="col-md-offset-1 col-md-2 icon" id="personal_data_icon" @if($person->getValidity('id')) style="color: green" @else style="color: yellow" @endif>
                                <i class="entypo-user" title="{{trans('admin.personal_data')}}"></i>
                            </div>
                            <div class="col-md-2 icon" id="file_icon" @if($person->getValidity('id')) style="color: green" @else style="color: yellow" @endif>
                                <i class="entypo-vcard" title="{{trans('admin.card_pdf')}}"></i>
                            </div>
                            <div class=" col-md-2 icon" id="person_email_icon" @if($person->getValidity('email')) style="color: green" @else style="color: yellow" @endif>
                                <i class="entypo-mail" title="{{trans('admin.email')}}"></i>
                            </div>
                            <div class="col-md-2 icon" id="person_cellphone_icon" @if($person->getValidity('cellphone')) style="color: green" @else style="color: yellow" @endif>
                                <i class="fa fa-mobile-phone" title="{{trans('admin.person_cellphone')}}" style="margin-top: 5px"></i>
                            </div>
                            <div class="col-md-2 icon" id="person_phone_icon"  @if($person->getValidity('phone')) style="color: green" @else style="color: yellow" @endif>
                                <i class="entypo-phone" title="{{trans('admin.person_phone')}}"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8" style="margin-top: 10px; margin-left: -45px; height: 60px; width: 310px">
                        <div class="rcorners" style="color: #003756; padding-top: 5px">
                            @foreach($networks as $net)
                                <div class="col-md-2" id="{{$net->name_social_network.'_icon'}}" style="font-size: 20px; @if(in_array($net->id, $person->social_networks->pluck('id')->toArray())) color: yellow @else color: #D4D4D5 @endif " >
                                    <i class="{{empty($net->icon)? 'fa fa-cog': $net->icon}}" title="{{$net->name_social_network}}"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="rcorners" style="margin-left: -15px; margin-bottom: 10px; color: #003756;">
                        <div>
                            <strong>{{trans('admin.responsibility')}}</strong>
                        </div>
                        <div>
                            {!! $text !!}
                        </div>
                    </div>
                </div>
                <div class="panel minimal" >
                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title roboto">{!! trans('admin.promoter') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.promoter') !!}">
                                    <a href="#promotor_tab" data-toggle="tab"><i class="entypo-users"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.personal_data') !!}">
                                    <a href="#personal_data_tab" data-toggle="tab"><i class="entypo-user"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.contact_data') !!}">
                                    <a href="#contact_data_tab" data-toggle="tab"><i class="entypo-mail"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.electoral_data') !!}">
                                    <a href="#electoral_data_tab" data-toggle="tab"><i class="entypo-box"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.networks') !!}">
                                    <a href="#networks_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.group') !!}">
                                    <a href="#groups_tab" data-toggle="tab"><i class="entypo-users"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.political_responsabilities') !!}">
                                    <a href="#presponsabilities_tab" data-toggle="tab"><i
                                                class="entypo-flow-cascade"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.territorial_responsabilities') !!}">
                                    <a href="#tresponsabilities_tab" data-toggle="tab"><i
                                                class="entypo-flow-cascade"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="tab-content">

                            <div class="tab-pane active" id="promotor_tab" name="{{trans('admin.promotor')}}">
                                <div class="rcorners" style="height: 468px">
                                    <div class="verticalLine">
                                        <div class="row" data-class="promoter">
                                            <label for="promoter_text"
                                                   class="col-sm-3 control-label recorder">{{ trans('admin.promoter') }}</label>
                                            <div class="col-sm-6">
                                                <input type="hidden" name="promoter" id="promoter" value="{{is_null($promoter)?'':$promoter->id}}"/>
                                                <input type="text" class="form-control readonly recorder personal_data modal_input_button"
                                                       id="promoter_text"
                                                       value="{{is_null($promoter)?'':$promoter->get_full_name()}}"
                                                       data-button="promoter_text_button"
                                                       data-column='person',
                                                       data-filter = 'filter-menu-person',
                                                       autocomplete="off" value="" data-class="promoter" readonly/>
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="button" id="promoter_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                        title="{{trans('admin.search_promoter')}}" data-toggle="modal"
                                                        data-target="#myModalPerson" data-class="promoter">
                                                    <i class="entypo-search"></i>
                                                </button>
                                                <button type="button" name="btnTrash"
                                                        class="btn btn-danger btn-sm clean_button"
                                                        title="{{trans('admin.clean')}}" data-class="promoter">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="personal_data_tab" name="{{trans('admin.personal_data')}}">
                                <div class="rcorners" style="height: 468px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="person_name" class="col-sm-3 control-label">{{trans('admin.name').'(s)'}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder personal_data" name="person_name" id="person_name" value="{{$person->person_name}}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="father_lastname" class="col-sm-3 control-label">{{trans('admin.father_lastname')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder personal_data" name="father_lastname" id="father_lastname" value="{{$person->father_lastname}}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="mother_lastname" class="col-sm-3 control-label">{{trans('admin.mother_lastname')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder personal_data" name="mother_lastname" id="mother_lastname" value="{{$person->mother_lastname}}" required autofocus/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="verticalLine" style="margin-top: 20px">
                                        <div class="row">
                                            <label for="birth_date" class="col-sm-3 control-label">{{trans('admin.birth_date')}}<span style="color: red">*</span></label>
                                            <div class='input-group date col-sm-9 datetimepicker'  style="padding-right: 300px; padding-left: 15px;">
                                                <input type='text' class="form-control recorder personal_data" name="birth_date" id="birth_date" value="{{$person->birth_date}}" required autofocus/>
                                                <span class="input-group-addon"> <span class="fa fa-calendar"></span></span>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <div class="col-sm-6">
                                                <label for="person_sex" class="col-sm-6 control-label">{{trans('admin.person_sex')}}<span style="color: red">*</span></label>
                                                <div class="col-sm-6    ">
                                                    <select id="person_sex" name="person_sex" class="form-control select2 recorder personal_data" style="width: 150px">
                                                        <option value="" selected>---{{trans('admin.select')}} ---</option>
                                                        @foreach($person_sex as $key =>$val)
                                                            <option value="{{ $key }}" @if($person->person_sex == $key) selected @endif >{{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="id_fed_entity_born" class="col-sm-2 control-label">{{trans('admin.fed_entity')}}<span style="color: red">*</span></label>
                                                <div class="col-sm-10">
                                                    <select id="id_fed_entity_born" name="id_fed_entity_born" class="form-control select2 recorder personal_data" style="width: 235px">
                                                        <option value="" selected>---{{trans('admin.select')}} ---</option>
                                                        @foreach($fed_entitys as $m)
                                                            <option value="{{ $m->id }}" @if($person->id_fed_entity_born == $m->id) selected @endif>{{ ' ('. trans('admin.entity_key').': '.$m->entity_key . ') '.$m->entity_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_municipality_born" class="col-sm-3 control-label">{{trans('admin.municipality')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select id="id_municipality_born" name="id_municipality_born" class="form-control select2 recorder personal_data">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($municipalitys as $m)
                                                        <option value="{{ $m->id }}" @if($person->id_municipality_born == $m->id) selected @endif>{{ ' ('. trans('admin.municipality_key').': '.$m->municipality_key . ') '.$m->municipality_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="verticalLine" style="margin-top: 20px">
                                        <div class="row col-sm-offset-3">
                                            <label class="checkbox-inline col-sm-4" for="is_studying">
                                                <input type="checkbox" value="1" class="recorder" id="is_studying" name="is_studying" @if($person->is_studying) checked @endif>{{trans('admin.studies')}}
                                            </label>
                                            <label class="checkbox-inline" for="is_working">
                                                <input type="checkbox" value="1" class="recorder" id="is_working" name="is_working" @if($person->is_working) checked @endif>{{trans('admin.works')}}
                                            </label>
                                        </div>

                                        <div class="row" style="margin-top: 15px">
                                            <label for="educ_level" class="col-sm-3 control-label">{{ trans('admin.educ_level') }}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control select2 recorder personal_data" name="educ_level" id="educ_level" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($educ_levels as $key => $value)
                                                        <option value="{{ $key }}" @if($person->educ_level == $key) selected @endif>{{ trans($value) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="occupation" class="col-sm-3 control-label">{{ trans('admin.occupation') }}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control select2" name="occupation" id="occupation" disabled required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($occupations as $o)
                                                        <option value="{{ $o->id }}" @if($person->occupation == $o->id) selected @endif>{{ $o->occupation_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="verticalLine" style="margin-top: 25px">
                                        <div class="row" >
                                            <label for="territory_volunteer" class="col-sm-7 control-label">{{trans('admin.territory_volunteer')}}</label>
                                            <label class="radio-container col-sm-offset-1 col-sm-1">{{trans('admin.yes')}}
                                                <input type="radio" @if($person->territory_volunteer) checked @endif name="territory_volunteer" value="1" class="recorder">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio-container col-sm-2">{{trans('admin.no')}}
                                                <input type="radio" name="territory_volunteer" value="0" class="recorder" @if(!$person->territory_volunteer) checked @endif>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label for="electoral_volunteer" class="col-sm-7 control-label">{{trans('admin.electoral_volunteer')}}</label>
                                            <label class="radio-container col-sm-offset-1 col-sm-1">{{trans('admin.yes')}}
                                                <input type="radio" @if($person->electoral_volunteer) checked @endif name="electoral_volunteer" value="1" class="recorder">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio-container col-sm-2">{{trans('admin.no')}}
                                                <input type="radio" @if(!$person->electoral_volunteer) checked @endif name="electoral_volunteer" value="0" class="recorder">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane" id="contact_data_tab" name="{{trans('admin.contact_data')}}">
                                <div class="rcorners">
                                    <div class="verticalLine">
                                        <div class="row" data-class="id_oficial_address">
                                            <label for="id_oficial_address_text"
                                                   class="col-sm-3 control-label">{{ trans('admin.oficial_address') }}
                                                <span style="color: red">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="hidden" name="id_oficial_address" id="id_oficial_address" value="{{$oficial_address->id}}"/>
                                                <input type="text" class="form-control readonly recorder modal_input_button"
                                                       id="id_oficial_address_text"
                                                       data-button="oficial_address_text_button"
                                                       data-column='address',
                                                       value="{{$oficial_address->street.', No.'.$oficial_address->external_number.', '.trans('admin.neighborhood').': '. $oficial_address->neighborhood.', '.trans('admin.postal_code').$oficial_address->postal_code}}"
                                                       data-filter = 'filter-menu-address',
                                                       autocomplete="off" value="" required
                                                       readonly/>
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="button" class="btn btn-primary btn-sm add_address_button"
                                                        title="{{trans('admin.add_address')}}" data-toggle="modal"
                                                        id="myModalAddAddress_button"
                                                        data-target="#myModalAddAddress"
                                                        data-class="id_oficial_address">
                                                    <i class="entypo-plus"></i>
                                                </button>
                                                <button type="button" id="oficial_address_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                        title="{{trans('admin.search_address')}}" data-toggle="modal"
                                                        data-target="#myModalAddress" data-class="id_oficial_address">
                                                </button>
                                                <button type="button" name="btnTrash"
                                                        class="btn btn-danger btn-sm clean_button"
                                                        title="{{trans('admin.clean')}}"
                                                        data-class="id_oficial_address">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-offset-3" style="padding-left: 15px">
                                                <label class="checkbox-inline" for="i_live_check">
                                                    <input type="checkbox" value="1" class="recorder" id="i_live_check"
                                                           name="i_live_check"
                                                           style="margin-top:2px"
                                                    @if(is_null($person->id_real_address)) checked @endif>{{trans('admin.i_live_at_my_official_address')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rcorners " style="margin-top: 20px; height: 371px">
                                    <div class="verticalLine">
                                        @if(!is_null($user))
                                            <div class="row">
                                                <label for="username" class="col-sm-3 control-label">{{trans('admin.user')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control recorder" name="username" id="username" value="{{is_null($user)?'': $user->name}}" readonly autofocus/>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row" style="margin-top: 20px">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="person_phone" class="col-sm-3 control-label">{{trans('admin.person_phone')}}</label>
                                                    <div class="col-sm-3">
                                                        <select class="form-control select2" name="phone_phone_code_id" id="phone_phone_code_id" required autofocus>
                                                            @foreach($phone_codes as $pc)
                                                                <option value="{{ $pc->id }}" @if(!is_null($phone) && $phone->phone_code_id == $pc->id) selected @endif>{{ $pc->phone_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="tel" class="form-control check_data data_user"  id="lada_phone" name="lada_phone" placeholder="Lada" autofocus/>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="tel" class="form-control check_data recorder data_user"  id="person_phone" name="person_phone" pattern="[0-9]{7}" autofocus/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-offset-3 col-sm-3">
                                                        <label class="checkbox-inline" for="phone_is_smartphone">
                                                            <input type="checkbox" value="1" id="phone_is_smartphone" name="phone_is_smartphone" style="margin-top:2px" >{{trans('admin.is_smartphone')}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="person_cellphone" class="col-sm-3 control-label">{{trans('admin.person_cellphone')}}</label>
                                                    <div class="col-sm-3">
                                                        <select class="form-control select2" name="cellphone_phone_code_id" id="cellphone_phone_code_id" required autofocus>
                                                            @foreach($phone_codes as $pc)
                                                                <option value="{{ $pc->id }}" @if(!is_null($cellphone) && $cellphone->phone_code_id == $pc->id) selected @endif>{{ $pc->phone_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="tel" class="form-control check_data data_user"  id="lada_cellphone" name="lada_cellphone" placeholder="Lada" value="@if(!is_null($cellphone)) {{$cellphone->lada}} @endif" autofocus/>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="tel" class="form-control check_data recorder data_user"  id="person_cellphone" name="person_cellphone" value="@if(!is_null($cellphone)) {{$cellphone->info}} @endif" pattern="[0-9]{7}" autofocus/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-offset-3 col-sm-3">
                                                        <label class="checkbox-inline" for="cellphone_is_propietary">
                                                            <input type="checkbox" value="1" id="cellphone_is_propietary" name="cellphone_is_propietary" style="margin-top:2px" @if(!is_null($cellphone) && $cellphone->is_propietary) checked @endif>{{trans('admin.is_propietary')}}
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="checkbox-inline" for="cellphone_is_smartphone">
                                                            <input type="checkbox" value="1" id="cellphone_is_smartphone" name="cellphone_is_smartphone" style="margin-top:2px" @if(!is_null($cellphone) && $cellphone->is_smartphone) checked @endif>{{trans('admin.is_smartphone')}}
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="checkbox-inline" for="cellphone_exclusive_use">
                                                            <input type="checkbox" value="1" id="cellphone_exclusive_use" name="cellphone_exclusive_use" style="margin-top:2px" @if(!is_null($cellphone) && $cellphone->is_exclusive) checked @endif>{{trans('admin.exclusive_use')}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 20px">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <label for="person_email" class="col-sm-3 control-label">{{trans('admin.email')}}<span style="color: red">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control recorder data_user check_data" name="person_email" id="person_email" value="@if(!is_null($email)) {{$email->info}} @endif" required autofocus/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-offset-3 col-sm-3">
                                                        <label class="checkbox checked" for="person_email_is_propietary">
                                                            <input type="checkbox" value="1" id="person_email_is_propietary" name="person_email_is_propietary" style="margin-top:2px" @if(!is_null($email) && $email->is_propietary) checked @endif>{{trans('admin.is_propietary')}}
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="checkbox" for="person_email_exclusive_use">
                                                            <input type="checkbox" value="1" id="person_email_exclusive_use" name="person_email_exclusive_use" style="margin-top:2px" @if(!is_null($email) && $email->is_exclusive) checked @endif>{{trans('admin.exclusive_use')}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="electoral_data_tab" name="{{trans('admin.contact_data')}}">
                                <div class="rcorners " >
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="elector_key" class="col-sm-3 control-label">{{trans('admin.elector_key')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control recorder" name="elector_key" id="elector_key" value="{{$person->elector_key}}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="file" class="col-sm-3 control-label">{{ trans('admin.card_pdf_front') }}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control ignore recorder data_user" type="file" name="file[]" id="file" accept="png,jpeg" onchange="PreviewImage();" @if(is_null($person->card_pdf)) required @endif>
                                                <a href="{{$imgs['front']}}" target="_blank" class="d-flex align-items-center justify-content-center"><span><i class="entypo-vcard fa-lg">&nbsp;</i> {{trans('admin.card_pdf_front')}}</span></a>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="file1" class="col-sm-3 control-label">{{ trans('admin.card_pdf_back') }}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control ignore recorder data_user" type="file" name="file[]" id="file1" accept="png,jpeg" @if(is_null($person->card_pdf)) required @endif>
                                                <a href="{{$imgs['back']}}" target="_blank" class="d-flex align-items-center justify-content-center"><span><i class="entypo-vcard fa-lg">&nbsp;</i> {{trans('admin.card_pdf_back')}}</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rcorners" style="margin-top: 20px; height: 289px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="credential_date" class="col-sm-3 control-label">{{trans('admin.credential_date')}}</label>
                                            <div class="col-sm-9">
                                                <select id="credential_date" name="credential_date" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @for($i = date('Y')-10;$i<date('Y')+10;$i++)
                                                        <option value="{{ $i }}" >{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="verticalLine" style="margin-top: 20px">
                                        <div class="row">
                                            <label for="id_fed_entity_text" class="col-sm-3 control-label">{{ trans('admin.fed_entity') }}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="hidden" name="id_fed_entity"  id="id_fed_entity" value="{{$oficial_address->id_fed_entity}}"/>
                                                <input type="text" class="form-control " id="id_fed_entity_text" value="{{ ' ('. trans('admin.entity_key').': '.$fed_entitys->find($oficial_address->id_fed_entity)->entity_key . ') '.$fed_entitys->find($oficial_address->id_fed_entity)->entity_name }}" disabled autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_municipality_text" class="col-sm-3 control-label">{{ trans('admin.municipality') }}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="hidden" name="id_municipality"  id="id_municipality" value="{{$oficial_address->id_municipality}}"/>
                                                <input type="text" class="form-control recorder" id="id_municipality_text" value="{{ ' ('. trans('admin.entity_key').': '.$municipalitys->find($oficial_address->id_municipality)->municipality_key . ') '.$municipalitys->find($oficial_address->id_municipality)->municipality_name }}"disabled autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_section" class="col-sm-3 control-label">{{ trans('admin.section') }}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="hidden" name="id_section"  id="id_section" value="{{$oficial_address->id_section}}"/>
                                                <input type="text" class="form-control recorder" id="id_section_text" value="{{ ' ('. trans('admin.section_type').': '.$sections->find($oficial_address->id_section)->section_type . ') '.$sections->find($oficial_address->id_section)->section_key }}" disabled  autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" data-class="id_real_address" id="id_real_address_div"  style="margin-top: 10px" @if(is_null($person->id_real_address)) hidden @endif>
                                            <label for="id_real_address_text"
                                                   class="col-sm-3 control-label">{{ trans('admin.real_address') }}<span
                                                        style="color: red">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="hidden" name="id_real_address" id="id_real_address" value="{{is_null($real_address)? '': $real_address->id}}"/>
                                                <input type="text" class="form-control readonly recorder modal_input_button"
                                                       id="id_real_address_text"
                                                       data-button="id_real_address_text_button"
                                                       data-column='address',
                                                       data-filter = 'filter-menu-address',
                                                       autocomplete="off" value="{{is_null($real_address)?'': $real_address->street.', No.'.$real_address->external_number.', '.trans('admin.neighborhood').': '. $real_address->neighborhood.', '.trans('admin.postal_code').$real_address->postal_code}}" required
                                                       readonly/>
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="button" class="btn btn-primary btn-sm add_address_button"
                                                        title="{{trans('admin.add_address')}}" data-toggle="modal"
                                                        data-target="#myModalAddAddress" data-class="id_real_address">
                                                    <i class="entypo-plus"></i>
                                                </button>
                                                <button type="button" id="id_real_address_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                        title="{{trans('admin.search_address')}}" data-toggle="modal"
                                                        data-target="#myModalAddress" data-class="id_real_address">
                                                    {{--<i class="entypo-search" ></i>--}}
                                                </button>
                                                <button type="button" name="btnTrash"
                                                        class="btn btn-danger btn-sm clean_button"
                                                        title="{{trans('admin.clean')}}" data-class="id_real_address">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="networks_tab" name="{{trans('admin.networks')}}">
                                <div class="rcorners" style="height: 468px; overflow-y:auto; overflow-x:hidden;">
                                    <div class="verticalLine">
                                        @foreach($networks as $net)
                                            <div class="row" style="margin-top: 5px">
                                                <label for="{{$net->name_social_network}}" class="col-sm-3 control-label">{{$net->name_social_network }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control network_user recorder" name="{{$net->name_social_network}}" id="{{$net->name_social_network}}" placeholder="{{trans('admin.insert_profile')}}" value="{{is_null($person->social_networks()->find($net->id))? '': $person->social_networks()->find($net->id)->pivot->account}}" autofocus/>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="groups_tab" name="{{trans('admin.groups')}}">
                                <div class="rcorners" style="height: 468px">
                                    <div class="form-group">
                                        <div class=" col-sm-offset-3 col-sm-7">
                                            <select multiple="multiple" name="my-select[]" id='my-select' class="form-control multi-select">
                                                @foreach($groups as $g)
                                                    <option value="{{$g->id}}" @if(!is_null($person->groups()->find($g->id))) selected @endif>{{$g->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="presponsabilities_tab" name="{{trans('admin.political_responsabilities')}}">
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
                                <div class="rcorners" style="margin-top: 10px; height:404px">
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

                            <div class="tab-pane" id="tresponsabilities_tab" name="{{trans('admin.territorial_responsabilities')}}">
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
                                <div class="rcorners" style="margin-top: 10px; height:404px">
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
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-offset-6 col-sm-3 text-center">
                <button type="submit" class="btn btn-default submit_form">{{ trans('admin.send') }}</button>
            </div>

        </div>

    </form>

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
                    {{-- start filter--}}
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
                    {{-- end filter--}}
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
                          data-class="id_oficial_address">
                        {{ csrf_field() }}
                        <div class="tab-content">
                            <div class="tab-pane active" id="personal_data_tab" name="{{trans('admin.address')}}">
                                <div class="rcorners" style="margin-top: 10px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="postal_code"
                                                   class="col-sm-3 control-label">{{trans('admin.postal_code')}}<span
                                                        style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control postal_code"
                                                       name="postal_code"
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
                                                       name="neighborhood" id="neighborhood" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="internal_number"
                                                   class="col-sm-3 control-label">{{trans('admin.internal_number')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="internal_number"
                                                       id="internal_number" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="external_number"
                                                   class="col-sm-3 control-label">{{trans('admin.external_number')}}
                                                <span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="external_number"
                                                       id="external_number" required autofocus/>
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

    <!-- Card modal -->
    <div class="modal fade" id="myModalViewCard">
        <div class="modal-dialog" role="document" style="max-width: 600px; max-height: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{trans('admin.card_pdf_front')}}</h4>
                </div>
                <div class="modal-body" id="modalBody" style="text-align: center">
                    <img id="card_modal" src="{{url('images/avatar/default.png')}}" style="max-width: 550px; max-height: 350px">
                </div>
            </div>
        </div>
    </div>
    <!-- Card modal end -->
@endsection

@section('script_down')
    <script type="text/javascript">
        var infoWindow = null;
        var map = null;

        function PreviewImage() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("file").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("card").src = oFREvent.target.result;
                document.getElementById("card_modal").src = oFREvent.target.result;
            };
        };

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

        (function($){
            $(window).on('load',function(){

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

                $('.viewcard').click(function() {
                    var src = $('#card').attr('src');
                    $('#card_modal').attr('src',src);
                });

                function fill_icons() {
                    var active = true;
                    $('.personal_data').each(function (index) {
                        if ($(this).val() == '' && !$(this).hasClass("select2-container"))
                            active = false;
                    });
                    $('#personal_data_icon').css("color", active ? "yellow" : '#D4D4D5');
                }
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
                $('.autocomplete').keyup(function(){
                    autocomplete($(this), $(this).data('url'), $(this).data('data'));
                });
                $('#id_fed_entity_born').change(function() {
                    update_select('id_municipality_born','municipality_name', 'fed_entity_id', $(this).val() , {!! $municipalitys !!});
                });
                $('#id_fed_entity_address').change(function() {
                    update_select('id_municipality_address','municipality_name', 'fed_entity_id', $(this).val() , {!! $municipalitys !!});
                });
                $('#id_municipality_address').change(function() {
                    update_select('id_section_address','section_key', 'municipality_id', $(this).val() , {!! $sections !!});
                });

                //jerarquia de seleccion para permisos a cargos politicos
                $('#id_fed_entitys_political').change(function() {
                    update_select('id_municipality_political','municipality_name', 'fed_entity_id', $(this).val() , {!! $municipalitys !!});
                });
                $('#id_municipality_political').change(function() {
                    update_select('id_section_political','section_key', 'municipality_id', $(this).val() , {!! $sections !!});
                    update_select('id_loc_district_political','district_number', 'municipality_id', $(this).val() , {!! $loc_district !!});
                    update_select('id_fed_district_political','district_number', 'municipality_id', $(this).val() , {!! $fed_district !!});
                });
                $('#id_loc_district_political').change(function() {
                    update_select('id_area_political','area_key', 'loc_district_id', $(this).val() , {!! $area !!});
                });
                $('#id_area_political').change(function() {
                    update_select('id_zone_political','zone_key', 'area_id', $(this).val() , {!! $zone !!});
                });
                $('#id_section_political').change(function() {
                    update_select('id_block_political','block_key', 'section_id', $(this).val() , {!! $block !!});
                });

                //jerarquia de seleccion para permisos a cargos territoriales
                $('#id_fed_entitys_territorial').change(function() {
                    update_select('id_municipality_territorial','municipality_name', 'fed_entity_id', $(this).val() , {!! $municipalitys !!});
                });
                $('#id_municipality_territorial').change(function() {
                    update_select('id_section_territorial','section_key', 'municipality_id', $(this).val() , {!! $sections !!});
                    update_select('id_loc_district_territorial','district_number', 'municipality_id', $(this).val() , {!! $loc_district !!});
                    update_select('id_fed_district_territorial','district_number', 'municipality_id', $(this).val() , {!! $fed_district !!});
                });
                $('#id_loc_district_territorial').change(function() {
                    update_select('id_area_territorial','area_key', 'loc_district_id', $(this).val() , {!! $area !!});
                });
                $('#id_area_territorial').change(function() {
                    update_select('id_zone_territorial','zone_key', 'area_id', $(this).val() , {!! $zone !!});
                });
                $('#id_section_territorial').change(function() {
                    update_select('id_block_territorial','block_key', 'section_id', $(this).val() , {!! $block !!});
                });

                $('input[type=radio]:checked').each(function (index) {
                    record($(this));
                });
                $('.personal_data').focusout(function () {
                    fill_icons();
                });
                $('.data_user').focusout(function () {
                    var icon = $('#' + $(this).attr('id') + '_icon');
                    $(icon).css("color", $(this).val() != '' ? "yellow" : '#D4D4D5');
                });
                $('.network_user').focusout(function () {
                    var icon = $('#' + $(this).attr('id') + '_icon');
                    var url = $(this).val();
                    $(icon).css("color", $(this).val() != '' ? "yellow" : '#D4D4D5');
                });
                $('input.icheck-11').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-yellow'
                });
                $('.datetimepicker').datetimepicker({
                    format: 'YYYY/MM/DD' //'DD/MM/YYYY'
                });

                $(".modal-form-address").submit(function (event) {
                    event.preventDefault();
                    var clase = $(this).data("class");
                    $.post("{{route('address.post_add')}}",
                        $('.modal-form-address').serialize(),
                        function (data, status) {
                            //$('.modal-form-address').trigger("reset");
                            if (status) {
                                var decoded_data = jQuery.parseJSON(data);
                                var obj = decoded_data.object;

                                if (decoded_data.status) {
                                    $('#' + clase + '_text').val(obj.street + ', No.' + obj.external_number + ', {!! trans('admin.neighborhood') !!}:' + obj.neighborhood + ', {!! trans('admin.postal_code') !!}:' + obj.postal_code);
                                    $('#' + clase).val(obj.id);
                                    if (clase == 'id_oficial_address')
                                        setMunicipioEstado(obj);
                                    record($('#' + clase + '_text'));
                                } else
                                    $.notify(obj.message, "error");

                                if (decoded_data.exist) {
                                    $('#' + clase + '_text').val(obj.street + ', No.' + obj.external_number + ', {!! trans('admin.neighborhood') !!}:' + obj.neighborhood + ', {!! trans('admin.postal_code') !!}:' + obj.postal_code);
                                    $.notify('{!! trans('admin.address_existent') !!}', "info");
                                    record($('#' + clase + '_text'));
                                }
                            } else
                                $.notify("{!! trans('admin.error_message') !!}", "error");
                        });
                    $('.close').click();
                });
                $('.check_data').focusout(function () {
                    if ($(this).val() == '')
                        return;
                    var attr_name = null;
                    switch ($(this).attr('name')) {
                        case 'person_email':
                            attr_name = '{!! mb_strtolower(trans('admin.email'), 'UTF-8') !!}';
                            break;
                        case 'person_cellphone':
                            attr_name = '{!! mb_strtolower(trans('admin.person_cellphone'), 'UTF-8') !!}';
                            break;
                        case 'person_phone':
                            attr_name = '{!! mb_strtolower(trans('admin.person_phone'), 'UTF-8') !!}';
                            break;
                    }

                    $.post("{{route('communication.filter')}}",
                        {
                            "_token": "{{ csrf_token() }}",
                            'info': $(this).val()
                        },
                        function (data, status) {
                            var decoded_data = jQuery.parseJSON(data);
                            if (decoded_data.length > 0) {
                                //var _r= function(p,c){return p.replace(/%s/,c)};
                                $.notify([attr_name, decoded_data.length].reduce(_r, '{!! trans('admin.existence_detected') !!}'), "warning");
                            }

                        });
                });

                $(".make_user").focusout(function () {
                    var name = $('#person_name').val();
                    var lastname1 = $('#father_lastname').val();
                    var lastname2 = $('#mother_lastname').val();
                    var user = '', temp = '';

                    if (name != '') {
                        var ns = name.split(' ');
                        ns.forEach(function (item, index) {
                            temp += item.charAt(0);
                        });
                    }
                    user += (lastname1 != '') ? lastname1 : lastname2;
                    user = (user != '') ? temp + user : name;

                    $('#username').val(user.replace(" ", "").toLowerCase());
                });
                $(".readonly").keydown(function (e) {
                    e.preventDefault();
                });

                $("#is_working").on('change', function () {
                    if (this.checked)
                        $("#occupation").prop("disabled", false);
                    else
                        $("#occupation").prop("disabled", true);
                });

                $('#i_live_check').on('change', function () {
                    if (!this.checked)
                        $('#id_real_address_div').show();
                    else {
                        $('#id_real_address_div').hide();
                        $('#id_real_address').val('');
                        $('#id_real_address_text').val('');
                    }
                });

               // $("input[type='checkbox']").attr('checked', false);

                $(".person_form").validate({
                    lang: 'es',
                    ignore: [],//"input[type='file']",
                    invalidHandler: function (event, validator) {
                        var errors = validator.numberOfInvalids();
                        if (errors)
                            $.notify('{!! trans('admin.have_errors') !!}', "error");
                    },
                });

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

                $( "#filter-form-address" ).submit(function( event ) {
                    event.preventDefault();
                    search_address_table.DataTable().ajax.reload();
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

                $(".modal-select-address").click(function () {
                    var item = search_address_table.DataTable().rows({selected: true}).data();
                    var obj = item[0];
                    $('#' + $(this).data("class") + '_text').val(obj.street + ', No.' + obj.external_number + ', {!! trans('admin.neighborhood') !!}:' + obj.neighborhood + ', {!! trans('admin.postal_code') !!}:' + obj.postal_code);

                    $('#' + $(this).data("class")).val(obj.id_address);
                    if ($(this).data("class") == 'id_oficial_address')
                        setMunicipioEstado(obj);
                    record($('#' + $(this).data("class") + '_text'));
                    $('.modal-close').click();
                });

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

                $( "#filter-form-person" ).submit(function( event ) {
                    event.preventDefault();
                    search_person_table.DataTable().ajax.reload();
                });

                $(".modal-body").on("shown.bs.modal", function () {
                    $(".select2-single").select2();
                });

                $('#id_fed_entity_born').change(function () {
                    change_select($(this), $('#id_municipality_born'), {!! $municipalitys !!})
                });

                $(".modal-select-person").click(function () {
                    var item = search_person_table.DataTable().rows({selected: true}).data();
                    var obj = item[0];

                    $('#' + $(this).data("class") + '_text').val(obj.full_name);
                    $('#' + $(this).data("class")).val(obj.id_persons);
                    record($('#' + $(this).data("class") + '_text'));
                    fill_icons();
                    $('.modal-close').click();
                });

                $(".search_button").click(function () {
                    $('#modal-form-person').trigger("reset");
                    $('#modal-form-address').trigger("reset");
                    $(".modal-select-person").data('class', $(this).data("class"));
                    $(".modal-select-address").data('class', $(this).data("class"));
                });

                $(".add_address_button").click(function () {
                    $(".modal-form-address").data('class', $(this).parent().data("class"));
                });

                $(".clean_button").click(function () {
                    var name = $(this).data('class');
                    $('#' + name + '_text').val('');
                    $('#' + name).val('-1');
                    record($('#' + name + '_text'));
                    fill_icons();
                });

                $("input:radio[name='electoral_volunteer']").change(function() {
                    $("#presponsabilities_tab :input").attr("disabled", $(this).val()==0);
                    $("#presponsabilities_tab :input").val('').change();
                });

                $("input:radio[name='territory_volunteer']").change(function() {
                    $("#tresponsabilities_tab :input").attr("disabled", $(this).val()==0);
                    $("#tresponsabilities_tab :input").val('').change();
                });

                //llenando iconos segun validity

            });

        })(jQuery);
    </script>
@endsection
