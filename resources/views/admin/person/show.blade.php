@extends('admin.layout')

@section('script_up')
    {{--<script defer src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API','')}}&callback=initMap"></script>--}}
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
    <form role="form" class="form-horizontal form-groups-bordered person_form">

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
                            @foreach($person->social_networks()->get() as $net)
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
                                @if(!is_null($p_function))
                                    <li data-name="{!! trans('admin.political_responsabilities') !!}">
                                        <a href="#presponsabilities_tab" data-toggle="tab"><i
                                                    class="entypo-flow-cascade"></i></a>
                                    </li>
                                @endif
                                @if(!is_null($t_function))
                                    <li data-name="{!! trans('admin.territorial_responsabilities') !!}">
                                        <a href="#tresponsabilities_tab" data-toggle="tab"><i
                                                    class="entypo-flow-cascade"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="tab-content">

                            <div class="tab-pane active" id="promotor_tab" name="{{trans('admin.promotor')}}">
                                <div class="rcorners" style="height: 451px">
                                    <div class="verticalLine">
                                        <div class="row" data-class="promoter">
                                            <label for="promoter_text"
                                                   class="col-sm-3 control-label recording">{{ trans('admin.promoter') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control readonly recording personal_data modal_input_button"
                                                       id="promoter_text"
                                                       value="{{is_null($promoter)?'':$promoter->get_full_name()}}"
                                                       autocomplete="off"  readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="personal_data_tab" name="{{trans('admin.personal_data')}}">
                                <div class="rcorners" style="height: 451px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="person_name" class="col-sm-3 control-label">{{trans('admin.name').'(s)'}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control  recording " name="person_name" id="person_name" value="{{$person->person_name}}" readonly/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="father_lastname" class="col-sm-3 control-label">{{trans('admin.father_lastname')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control  recording " name="father_lastname" id="father_lastname" value="{{$person->father_lastname}}" readonly/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="mother_lastname" class="col-sm-3 control-label">{{trans('admin.mother_lastname')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control  recording " name="mother_lastname" id="mother_lastname" value="{{$person->mother_lastname}}" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="verticalLine" style="margin-top: 20px">
                                        <div class="row">
                                            <label for="birth_date" class="col-sm-3 control-label">{{trans('admin.birth_date')}}</label>
                                            <div class='input-group date col-sm-9 datetimepicker'  style="padding-right: 300px; padding-left: 15px;">
                                                <input type='text' class="form-control recording personal_data" name="birth_date" id="birth_date" value="{{$person->birth_date}}" readonly/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <div class="col-sm-6">
                                                <label for="person_sex" class="col-sm-6 control-label">{{trans('admin.person_sex')}}</label>
                                                <div class="col-sm-6">
                                                    <input type='text' class="form-control recording" name="birth_date" id="person_sex" value="{{ $person->person_sex == 'm'? trans('admin.female'): trans('admin.male')}}" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="id_fed_entity_born" class="col-sm-2 control-label">{{trans('admin.fed_entity')}}</label>
                                                <div class="col-sm-10">
                                                    <input type='text' class="form-control recording" id="id_fed_entity_born" value="{{ isset($person->id_fed_entity_born)? \App\FedEntity::findOrFail($person->id_fed_entity_born)->entity_name: '' }}" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_municipality_born" class="col-sm-3 control-label">{{trans('admin.municipality')}}</label>
                                            <div class="col-sm-9">
                                                <input type='text' class="form-control recording" name="id_municipality_born" id="id_municipality_born" value="{{ isset($person->id_municipality_born)? \App\Municipality::findOrFail($person->id_municipality_born)->municipality_name: '' }}" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="verticalLine" style="margin-top: 20px">
                                        <div class="row col-sm-offset-3">
                                            <label class="checkbox-inline col-sm-4" for="is_studying">
                                                <input type="checkbox" value="1" class="recording" id="is_studying" name="is_studying" @if($person->is_studying) checked @endif disabled>{{trans('admin.studies')}}
                                            </label>
                                            <label class="checkbox-inline" for="is_working">
                                                <input type="checkbox" value="1" class="recording" id="is_working" name="is_working" @if($person->is_working) checked @endif disabled>{{trans('admin.works')}}
                                            </label>
                                        </div>

                                        <div class="row" style="margin-top: 15px">
                                            <label for="educ_level" class="col-sm-3 control-label">{{ trans('admin.educ_level') }}</label>
                                            <div class="col-sm-9">
                                                <input type='text' class="form-control recording" name="educ_level" id="educ_level" value="{{ isset($person->educ_level)? trans(\App\Person::$educ_levels[$person->educ_level]): '' }}" readonly/>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="occupation" class="col-sm-3 control-label">{{ trans('admin.occupation') }}</label>
                                            <div class="col-sm-9">
                                                <input type='text' class="form-control recording" name="occupation" id="occupation" value="{{ isset($person->occupation)? \App\Ocupation::findOrFail($person->occupation)->occupation_name: '' }}" readonly/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="verticalLine" style="margin-top: 25px">
                                        <div class="row" >
                                            <label for="territory_volunteer" class="col-sm-7 control-label">{{trans('admin.territory_volunteer')}}</label>
                                            <label class="radio-container col-sm-offset-1 col-sm-1">{{trans('admin.yes')}}
                                                <input type="radio" @if($person->territory_volunteer) checked @endif name="territory_volunteer" value="1" class="recording">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio-container col-sm-2">{{trans('admin.no')}}
                                                <input type="radio" name="territory_volunteer" value="0" class="recording" @if(!$person->territory_volunteer) checked @endif>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label for="electoral_volunteer" class="col-sm-7 control-label">{{trans('admin.electoral_volunteer')}}</label>
                                            <label class="radio-container col-sm-offset-1 col-sm-1">{{trans('admin.yes')}}
                                                <input type="radio" @if($person->electoral_volunteer) checked @endif name="electoral_volunteer" value="1" class="recording" disabled>
                                                <span class="checkmark" disabled></span>
                                            </label>
                                            <label class="radio-container col-sm-2">{{trans('admin.no')}}
                                                <input type="radio" @if(!$person->electoral_volunteer) checked @endif name="electoral_volunteer" value="0" class="recording" disabled>
                                                <span class="checkmark" disabled></span>
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
                                                </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control readonly recording"
                                                       id="id_oficial_address_text"
                                                       value="{{$oficial_address->street.', No.'.$oficial_address->external_number.', '.trans('admin.neighborhood').': '. $oficial_address->neighborhood.', '.trans('admin.postal_code').$oficial_address->postal_code}}"
                                                       readonly/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-offset-3" style="padding-left: 15px">
                                                <label class="checkbox-inline" for="i_live_check">
                                                    <input type="checkbox" value="1" class="recording" id="i_live_check"
                                                           name="i_live_check"
                                                           style="margin-top:2px"
                                                    @if(is_null($person->id_real_address)) checked @endif disabled>{{trans('admin.i_live_at_my_official_address')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rcorners " style="margin-top: 20px; height: 354px">
                                    <div class="verticalLine">
                                        @if(!is_null($user))
                                            <div class="row">
                                                <label for="username" class="col-sm-3 control-label">{{trans('admin.user')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control recording" name="username" id="username" value="{{is_null($user)?'': $user->name}}" readonly/>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row" style="margin-top: 20px">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="person_phone" class="col-sm-3 control-label">{{trans('admin.person_phone')}}</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" name="phone_phone_code_id" id="phone_phone_code_id" value="{{!is_null($phone)? \App\PhoneCode::findOrFail($phone->phone_code_id)->phone_code: ''}}" readonly />
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="text" class="form-control " name="lada_phone" id="lada_phone" value="{{!is_null($phone)? $phone->lada: ''}}" readonly />
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="tel" class="form-control "  id="person_phone" name="person_phone" value="{{!is_null($phone)? $phone->info: ''}}" readonly/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-offset-3 col-sm-3">
                                                        <label class="checkbox-inline" for="phone_is_smartphone">
                                                            <input type="checkbox" value="1" id="phone_is_smartphone" name="phone_is_smartphone" style="margin-top:2px" disabled>{{trans('admin.is_smartphone')}}
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
                                                        <input type="tel" class="form-control "  id="cellphone_phone_code_id" name="cellphone_phone_code_id" value="{{!is_null($cellphone)? \App\PhoneCode::findOrFail($cellphone->phone_code_id)->phone_code: ''}}" readonly/>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="tel" class="form-control "  id="lada_cellphone" name="lada_cellphone" value="{{!is_null($cellphone)? $cellphone->lada: ''}}" readonly/>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="tel" class="form-control "  id="person_cellphone" name="person_cellphone" value="{{!is_null($cellphone)? $cellphone->info: ''}}" readonly/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-offset-3 col-sm-3">
                                                        <label class="checkbox-inline" for="cellphone_is_propietary">
                                                            <input type="checkbox" value="1" id="cellphone_is_propietary" name="cellphone_is_propietary" style="margin-top:2px" @if(!is_null($cellphone) && $cellphone->is_propietary) checked @endif disabled>{{trans('admin.is_propietary')}}
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="checkbox-inline" for="cellphone_is_smartphone">
                                                            <input type="checkbox" value="1" id="cellphone_is_smartphone" name="cellphone_is_smartphone" style="margin-top:2px" @if(!is_null($cellphone) && $cellphone->is_smartphone) checked @endif disabled>{{trans('admin.is_smartphone')}}
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="checkbox-inline" for="cellphone_exclusive_use">
                                                            <input type="checkbox" value="1" id="cellphone_exclusive_use" name="cellphone_exclusive_use" style="margin-top:2px" @if(!is_null($cellphone) && $cellphone->is_exclusive) checked @endif disabled>{{trans('admin.exclusive_use')}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 20px">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <label for="person_email" class="col-sm-3 control-label">{{trans('admin.email')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control recording" name="person_email" id="person_email" value="@if(!is_null($email)) {{$email->info}} @endif" readonly/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-offset-3 col-sm-3">
                                                        <label class="checkbox checked" for="person_email_is_propietary">
                                                            <input type="checkbox" value="1" id="person_email_is_propietary" name="person_email_is_propietary" style="margin-top:2px" @if(!is_null($email) && $email->is_propietary) checked @endif disabled>{{trans('admin.is_propietary')}}
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="checkbox" for="person_email_exclusive_use">
                                                            <input type="checkbox" value="1" id="person_email_exclusive_use" name="person_email_exclusive_use" style="margin-top:2px" @if(!is_null($email) && $email->is_exclusive) checked @endif disabled>{{trans('admin.exclusive_use')}}
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
                                            <label for="elector_key" class="col-sm-3 control-label">{{trans('admin.elector_key')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control recording" name="elector_key" id="elector_key" value="{{$person->elector_key}}" readonly/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="file" class="col-sm-3 control-label">{{ trans('admin.card_pdf_front') }}</label>
                                            <div class="col-sm-9" style="margin-top: 5px">
                                                <a href="{{$imgs['front']}}" target="_blank" class="d-flex align-items-center justify-content-center"><span><i class="entypo-vcard fa-lg">&nbsp;</i> {{trans('admin.card_pdf_front')}}</span></a>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="file1" class="col-sm-3 control-label">{{ trans('admin.card_pdf_back') }}</label>
                                            <div class="col-sm-9" style="margin-top: 5px">
                                                <a href="{{$imgs['back']}}" target="_blank" class="d-flex align-items-center justify-content-center"><span><i class="entypo-vcard fa-lg">&nbsp;</i> {{trans('admin.card_pdf_back')}}</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rcorners" style="margin-top: 20px; height: 320px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="credential_date" class="col-sm-3 control-label">{{trans('admin.credential_date')}}</label>
                                            <div class='input-group date col-sm-9 datetimepicker'  style="padding-right: 300px; padding-left: 15px;">
                                                <input type='text' class="form-control recording" name="credential_date" id="credential_date" value="{{$person->credential_date}}" readonly/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="verticalLine" style="margin-top: 20px">
                                        <div class="row">
                                            <label for="id_fed_entity_text" class="col-sm-3 control-label">{{ trans('admin.fed_entity') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control " id="id_fed_entity_text" value="{{ !is_null($oficial_address->id_fed_entity)? \App\FedEntity::findOrFail($oficial_address->id_fed_entity)->entity_name: '' }}" readonly/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_municipality_text" class="col-sm-3 control-label">{{ trans('admin.municipality') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control " id="id_municipality_text" value="{{ !is_null($oficial_address->id_municipality)? \App\Municipality::findOrFail($oficial_address->id_municipality)->municipality_name: '' }}" readonly/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_section" class="col-sm-3 control-label">{{ trans('admin.section') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control " id="id_section_text" value="{{ !is_null($oficial_address->id_section)? \App\Section::findOrFail($oficial_address->id_section)->section_key: '' }}" readonly/>
                                            </div>
                                        </div>
                                        <div class="row" data-class="id_real_address" id="id_real_address_div"  style="margin-top: 10px" @if(is_null($person->id_real_address)) hidden @endif>
                                            <label for="id_real_address_text"
                                                   class="col-sm-3 control-label">{{ trans('admin.real_address') }}<span
                                                        style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control readonly recording modal_input_button"
                                                       id="id_real_address_text"
                                                       value="{{is_null($real_address)?'': $real_address->street.', No.'.$real_address->external_number.', '.trans('admin.neighborhood').': '. $real_address->neighborhood.', '.trans('admin.postal_code').$real_address->postal_code}}" required
                                                       readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="networks_tab" name="{{trans('admin.networks')}}">
                                <div class="rcorners" style="height: 451px; overflow-y:auto; overflow-x:hidden;">
                                    <div class="verticalLine">
                                       @foreach($person->social_networks()->get() as $net)
                                            <div class="row" style="margin-top: 5px">
                                                <label for="{{$net->name_social_network}}" class="col-sm-3 control-label">{{$net->name_social_network }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control network_user recording" name="{{$net->name_social_network}}" id="{{$net->name_social_network}}" value="{{$net->pivot->account}}" readonly/>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="groups_tab" name="{{trans('admin.groups')}}">
                                <div class="rcorners" style="height: 451px">
                                    <div class="form-group">
                                        <div class=" col-sm-offset-3 col-sm-7">
                                            <table class="table table-striped" style="text-align: center">
                                                <thead >
                                                    <tr >
                                                        <th style="text-align: center">{{trans('admin.group')}}</th>
                                                        <th style="text-align: center">{{trans('admin.default')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($person->groups()->get() as $g)
                                                        <tr>
                                                            <td>{{$g->group_name}}</td>
                                                            <td><i @if($g->default) style="color:green;" class="entypo-check" @else style="color:red;" class="entypo-cancel" @endif</i></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(!is_null($p_function))
                                <div class="tab-pane" id="presponsabilities_tab" name="{{trans('admin.political_responsabilities')}}">
                                    <div class="rcorners">
                                        <div class="verticalLine">
                                            <div class="row">
                                                <label for="id_political" class="col-sm-3 control-label">{{trans('admin.responsibility')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control recording" name="id_political" id="id_political" value="{{!is_null($p_function)? $p_function->name: '' }}" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rcorners" style="margin-top: 10px; height:388px">
                                        <div class="verticalLine">
                                            @if(!is_null($p_function->pivot->fed_entity_id))
                                                <div class="row">
                                                    <label for="id_fed_entitys_political" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_fed_entitys_political" id="id_fed_entitys_political" value="{{ \App\FedEntity::findOrFail($p_function->pivot->fed_entity_id)->entity_name}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($p_function->pivot->municipality_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_municipality_political" class="col-sm-3 control-label">{{trans('admin.municipality')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_municipality_political" id="id_municipality_political" value="{{ \App\Municipality::findOrFail($p_function->pivot->municipality_id)->municipality_name}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($p_function->pivot->fed_district_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_fed_district_political" class="col-sm-3 control-label">{{trans('admin.fed_district')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_fed_district_political" id="id_fed_district_political" value="{{ \App\FedDistrict::findOrFail($p_function->pivot->fed_district_id)->district_number}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($p_function->pivot->loc_district_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_loc_district_political" class="col-sm-3 control-label">{{trans('admin.loc_district')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_loc_district_political" id="id_loc_district_political" value="{{ \App\LocDistrict::findOrFail($p_function->pivot->loc_district_id)->district_number}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($p_function->pivot->area_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_area_political" class="col-sm-3 control-label">{{trans('admin.area')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_area_political" id="id_area_political" value="{{ \App\Area::findOrFail($p_function->pivot->area_id)->area_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($p_function->pivot->zone_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_zone_political" class="col-sm-3 control-label">{{trans('admin.zone')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_zone_political" id="id_zone_political" value="{{ \App\Zone::findOrFail($p_function->pivot->zone_id)->zone_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($p_function->pivot->section_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_section_political" class="col-sm-3 control-label">{{trans('admin.section')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_section_political" id="id_section_political" value="{{ \App\Section::findOrFail($p_function->pivot->section_id)->section_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($p_function->pivot->block_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_block_political" class="col-sm-3 control-label">{{trans('admin.block')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_block_political" id="id_block_political" value="{{ \App\Block::findOrFail($p_function->pivot->block_id)->block_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(!is_null($t_function))
                                <div class="tab-pane" id="tresponsabilities_tab" name="{{trans('admin.territorial_responsabilities')}}">
                                    <div class="rcorners">
                                        <div class="verticalLine">
                                            <div class="row">
                                                <label for="id_territorial" class="col-sm-3 control-label">{{trans('admin.responsibility')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control recording" name="id_territorial" id="id_territorial" value="{{!is_null($t_function)? $t_function->name: '' }}" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rcorners" style="margin-top: 10px; height:388px">
                                        <div class="verticalLine">
                                            @if(!is_null($t_function->pivot->fed_entity_id))
                                                <div class="row">
                                                    <label for="id_fed_entitys_territorial" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_fed_entitys_territorial" id="id_fed_entitys_territorial" value="{{ \App\FedEntity::findOrFail($t_function->pivot->fed_entity_id)->entity_name}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($t_function->pivot->municipality_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_municipality_territorial" class="col-sm-3 control-label">{{trans('admin.municipality')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_municipality_territorial" id="id_municipality_territorial" value="{{ \App\Municipality::findOrFail($t_function->pivot->municipality_id)->municipality_name}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($t_function->pivot->fed_district_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_fed_district_territorial" class="col-sm-3 control-label">{{trans('admin.fed_district')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_fed_district_territorial" id="id_fed_district_territorial" value="{{ \App\FedDistrict::findOrFail($t_function->pivot->fed_district_id)->district_number}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($t_function->pivot->loc_district_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_loc_district_territorial" class="col-sm-3 control-label">{{trans('admin.loc_district')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_loc_district_territorial" id="id_loc_district_territorial" value="{{ \App\LocDistrict::findOrFail($t_function->pivot->loc_district_id)->district_number}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($t_function->pivot->area_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_area_territorial" class="col-sm-3 control-label">{{trans('admin.area')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_area_territorial" id="id_area_territorial" value="{{ \App\Area::findOrFail($t_function->pivot->area_id)->area_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($t_function->pivot->zone_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_zone_territorial" class="col-sm-3 control-label">{{trans('admin.zone')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_zone_territorial" id="id_zone_territorial" value="{{ \App\Zone::findOrFail($t_function->pivot->zone_id)->zone_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($t_function->pivot->section_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_section_territorial" class="col-sm-3 control-label">{{trans('admin.section')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_section_territorial" id="id_section_territorial" value="{{ \App\Section::findOrFail($t_function->pivot->section_id)->section_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!is_null($t_function->pivot->block_id))
                                                <div class="row" style="margin-top: 5px">
                                                    <label for="id_block_territorial" class="col-sm-3 control-label">{{trans('admin.block')}}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="id_block_territorial" id="id_block_territorial" value="{{ \App\Block::findOrFail($t_function->pivot->block_id)->block_key}}" readonly/>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </form>
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

        (function($){
            $('.recording').each(function(t,it){
                console.info($(this).val());
                record($(this), $(this).val());
            });

        })(jQuery);
    </script>
@endsection
