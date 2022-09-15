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
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('user.store') }}" enctype="multipart/form-data">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 267px">
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
                        <div class="panel-title roboto">{!! trans('admin.user') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.uer') !!}">
                                    <a href="#personal_data_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="personal_data_tab" name="{{trans('admin.user')}}">
                                <div class="rcorners" style="margin-top: 20px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="name" class="col-sm-3 control-label">{{trans('admin.name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="name" id="name" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="role" class="col-sm-3 control-label">{{trans('admin.role')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">

                                                <select class="select2 form-control make_user recorder" name="role" id="role" required autofocus>
                                                    @foreach($roles as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="person_id" class="col-sm-3 control-label">{{trans('admin.person_name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">

                                                <select class="select2 form-control make_user recorder" name="person_id" id="person_id" data-allow-clear="true" required autofocus>
                                                    @foreach($personas as $item)
                                                        <option  value="{{ $item->id }}">{{ $item->person_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="email" class="col-sm-3 control-label">{{trans('admin.email')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="email" id="email" required autofocus/>

                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="password" class="col-sm-3 control-label">{{trans('admin.password')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password" id="password" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="password-confirm" class="col-sm-3 control-label">{{trans('admin.confirm_password')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">

                                                <input type="password"
                                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                       name="password_confirmation" id="password-confirm"
                                                       required/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="avatar" class="col-sm-3 control-label">{{trans('admin.image')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" name="avatar" id="field-file" placeholder="{{trans('admin.image')}}" type="file" accept="image/*">
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

@endsection