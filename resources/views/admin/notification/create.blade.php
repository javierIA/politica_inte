@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('zone.index') }}">{{ trans('admin.zone') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('zone.store') }}" enctype="multipart/form-data">

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
            <div class="col-md-3 " style="margin-top: -10px; padding-top: 10px; height: 249px">
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
                        <div class="panel-title roboto">{!! trans('admin.zone') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.zone') !!}">
                                    <a href="#personal_data_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="personal_data_tab" name="{{trans('admin.zone')}}">
                                <div class="rcorners" style="margin-top: 20px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="zone_key" class="col-sm-3 control-label">{{trans('admin.zone_key')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" max="9999" min="0" name="zone_key" id="zone_key" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="area_id" class="col-sm-3 control-label">{{trans('admin.area')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select id="area_id" name="area_id" class="form-control select2 recorder personal_data" >
                                                    <option value="" selected>---{{trans('admin.select')}} ---</option>
                                                    @foreach($areas as $a)
                                                        <option value="{{ $a->id }}" >{{ $a->area_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="file" class="col-sm-3 control-label">{{trans('admin.map')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control make_user recorder" type="file" name="file" id="file" accept="application/pdf">
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
    </script>

@endsection