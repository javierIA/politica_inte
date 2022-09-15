@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('setting.index') }}">{{ trans('admin.setting') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('setting.update', $setting) }}">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 267px">
                <h4 class="roboto">{{trans('admin.data')}}</h4>
                <div class="rcorners" style="height: 320px">
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
                        <div class="panel-title roboto">{{trans('admin.contact_data')}}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{{trans('admin.contact_data')}}">
                                    <a href="#contact_data_tab" data-toggle="tab"><i class="entypo-menu"></i></a>
                                </li>
                                <li data-name="{{trans('admin.config_data')}}">
                                    <a href="#config_data_tab" data-toggle="tab"><i class="entypo-cog"></i></a>
                                </li>
                                <li data-name="{{trans('admin.role')}}">
                                    <a href="#default_role_tab" data-toggle="tab"><i class="entypo-user"></i></a>
                                </li>
                                {{--<li data-name="{{trans('admin.upload_data')}}">
                                    <a href="#upload_data_tab" data-toggle="tab"><i class="entypo-upload"></i></a>
                                </li>--}}
                                <li data-name="{{trans('admin.networks')}}">
                                    <a href="#networks_tab" data-toggle="tab"><i class="fa fa-users"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="contact_data_tab" name="{{trans('admin.contact_data')}}">
                                <div class="rcorners" style="height: 250px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="address" class="col-sm-3 control-label">{{trans('admin.address')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control input-lg recorder" name="address" id="address" value="{{ $setting->address }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="email" class="col-sm-3 control-label">{{ trans('admin.email') }}</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control input-lg recorder" name="email" id="email" value="{{ $setting->email }}" autofocus/>
                                            </div>

                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="phone" class="col-sm-3 control-label">{{trans('admin.phone')}}</label>
                                            <div class="col-sm-9" >
                                                <input type="text" class="form-control input-lg recorder" name="phone" id="phone" value="{{ $setting->phone }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="fax" class="col-sm-3 control-label">Fax</label>
                                            <div class="col-sm-9" >
                                                <input type="text" class="form-control input-lg recorder" name="fax" id="fax" value="{{ $setting->fax }}" autofocus/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="config_data_tab" name="{{trans('admin.config_data')}}">
                                <div class="rcorners" style="height: 250px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="election_year" class="col-sm-3 control-label">{{trans('admin.election_year')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control input-lg recorder" name="election_year" id="election_year" value="{{ $setting->election_year }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="vigency_year" class="col-sm-3 control-label">{{trans('admin.vigency_year')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control input-lg recorder" name="vigency_year" id="vigency_year" value="{{ $setting->vigency_year }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row col-sm-offset-3" style="margin-top: 10px">
                                            <label class="checkbox-inline" for="allow_functions">
                                                <input type="checkbox" value="1" class="recorder" id="allow_functions" name="allow_functions" @if($setting->allow_functions) checked @endif>{{trans('admin.allow_functions')}}
                                            </label>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="max_cellphone" class="col-sm-3 control-label">{{trans('admin.max_allowed_cellphone')}}</label>
                                            <div class="col-sm-9" >
                                                <input type="number" class="form-control input-lg recorder" name="max_cellphone" id="max_cellphone" value="{{ $setting->max_cellphone }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="max_mails" class="col-sm-3 control-label">{{trans('admin.max_allowed_emails')}}</label>
                                            <div class="col-sm-9" >
                                                <input type="number" class="form-control input-lg recorder" name="max_mails" id="max_mails" value="{{ $setting->max_mails }}" autofocus/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="default_role_tab" name="{{trans('admin.role')}}">
                                <div class="rcorners" style="height: 250px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="default_role" class="col-sm-3 control-label">{{trans('admin.default_role')}}</label>
                                            <div class="col-sm-9">
                                                <select class="form-control select2" name="default_role" id="default_role" required autofocus>
                                                    @foreach($roles as $r)
                                                        <option value="{{ $r->id }}" @if($setting->default_role == $r->id) selected @endif>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="networks_tab" name="{{trans('admin.networks')}}">
                                <div class="rcorners" style="height: 250px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="facebook" class="col-sm-3 control-label">Facebook</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control input-lg recorder" name="facebook" id="facebook" value="{{ $setting->facebook }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="instagram" class="col-sm-3 control-label">Instagram</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control input-lg recorder" name="instagram" id="instagram" value="{{ $setting->instagram }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="twitter" class="col-sm-3 control-label">Twitter</label>
                                            <div class="col-sm-9" >
                                                <input type="text" class="form-control input-lg recorder" name="twitter" id="twitter" value="{{ $setting->twitter }}" autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px">
                                            <label for="pinterest" class="col-sm-3 control-label">Pinterest</label>
                                            <div class="col-sm-9" >
                                                <input type="text" class="form-control input-lg recorder" name="pinterest" id="pinterest" value="{{ $setting->pinterest }}" autofocus/>
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