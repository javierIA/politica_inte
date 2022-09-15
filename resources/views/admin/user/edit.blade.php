@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('user.index') }}">{{ trans('admin.user') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div align="center">
            <img src="{{asset($user->avatar )}}" class="img-responsive img-circle"
                 width="100px">
        </div>
    </div>
    <br/>
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('user.update', $user) }}" enctype="multipart/form-data">
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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 347px">
                <h4 class="roboto">{{trans('admin.network_data')}}</h4>
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
                        <div class="panel-title roboto">{!! trans('admin.user') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.user') !!}">
                                    <a href="#group_tab" data-toggle="tab"><i class="entypo-network"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="group_tab" name="{{trans('admin.user')}}">
                                <div class="rcorners" style="margin-top: 10px; height: 258px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="name" class="col-sm-3 control-label">{{trans('admin.name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="name" id="name" value="{{ $user->name }}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="role" class="col-sm-3 control-label">{{trans('admin.role')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="select2 form-control make_user recorder" name="role" id="role" required autofocus>
                                                    @foreach($roles as $item)
                                                        <option value="{{ $item->id }}" {{ ( $item->id == $user_role) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="person_id" class="col-sm-3 control-label">{{trans('admin.person_name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ $user->get_full_name() }}" readonly autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="email" class="col-sm-3 control-label">{{trans('admin.email')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="email" value="{{ $user->email }}" readonly autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="avatar" class="col-sm-3 control-label">{{trans('admin.image')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control make_user recorder" name="avatar" id="field-file" placeholder="{{trans('admin.image')}}" type="file" accept="image/*">
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
            $('.remove-icon').remove();
        });
    </script>
@endsection