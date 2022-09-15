@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('municipality.index') }}">{{ trans('admin.municipality') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('municipality.update', $municipality) }}" enctype="multipart/form-data">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 282px">
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
                        <div class="panel-title roboto">{!! trans('admin.municipality') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.municipality') !!}">
                                    <a href="#group_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="municipality_tab" name="{{trans('admin.municipality')}}">
                                <div class="rcorners" style="margin-top: 20px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="municipality_key" class="col-sm-3 control-label">{{trans('admin.municipality_key')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" max="9999" min="0" name="municipality_key" id="municipality_key" value="{{$municipality->municipality_key}}" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="municipality_name" class="col-sm-3 control-label">{{trans('admin.municipality_name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="municipality_name" id="municipality_name" value="{{$municipality->municipality_name}}"required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="fed_entity_id" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select id="fed_entity_id" name="fed_entity_id" class="form-control select2 recorder personal_data" >
                                                    <option value="" selected>---{{trans('admin.select')}} ---</option>
                                                    @foreach($fed_entitys as $m)
                                                        <option value="{{ $m->id }}" @if($municipality->fed_entity_id == $m->id) selected @endif>{{ $m->entity_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="file" class="col-sm-3 control-label">{{trans('admin.map')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control make_user recorder" type="file" name="file" id="file" accept="application/pdf">
                                                <br>
                                                @if(!is_null($municipality->map_pdf))
                                                    <a href="{{ url('pdf/'.$municipality->getTable().'/'.basename($municipality->map_pdf)) }}" add target="_blank" class="d-flex align-items-center justify-content-center login"><span ><i class="fa fa-map fa-lg"> {{basename($municipality->map_pdf)}}</i></span></a>
                                                @endif
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