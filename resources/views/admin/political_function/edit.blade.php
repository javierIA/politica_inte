@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('political_function.index') }}">{{ trans('admin.political_function') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('political_function.update', $politicalFunction) }}">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 370px">
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
                        <div class="panel-title roboto">{!! trans('admin.political_function') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.political_function') !!}">
                                    <a href="#political_function_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.association') !!}">
                                    <a href="#association_tab" data-toggle="tab"><i class="entypo-map "></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="political_function_tab" name="{{trans('admin.political_function')}}">
                                <div class="rcorners" style="margin-top: 10px; height:280px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="name" class="col-sm-3 control-label">{{trans('admin.name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="name" id="name" value="{{$politicalFunction->name}}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="type" class="col-sm-3 control-label">{{trans('admin.type')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control make_user recorder" name="type" id="type" data-allow-clear="true" required autofocus>
                                                    @foreach($function_type as $key => $item)
                                                        <option value="{{ $key }}" {{ ( $key == $politicalFunction->type) ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="position" class="col-sm-3 control-label">{{trans('admin.order')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" min="0" max="500" class="form-control recorder" name="position" id="position" value="{{$politicalFunction->position}}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="description" class="col-sm-3 control-label">{{trans('admin.description')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control make_user recorder" style="resize:none; height: 100px" id="description" name="description">{{$politicalFunction->description}}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="association_tab" name="{{trans('admin.association')}}">
                                <div class="rcorners" style="margin-top: 10px; height:280px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="fed_entity" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="fed_entity" name="fed_entity" style="margin-top: 10px" @if($politicalFunction->fed_entity) checked @endif>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="municipality" class="col-sm-3 control-label">{{trans('admin.municipality')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="municipality" name="municipality" style="margin-top: 10px" @if($politicalFunction->municipality) checked @endif>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="fed_district" class="col-sm-3 control-label">{{trans('admin.fed_district')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="fed_district" name="fed_district" style="margin-top: 10px" @if($politicalFunction->fed_district) checked @endif>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="loc_district" class="col-sm-3 control-label">{{trans('admin.loc_district')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="loc_district" name="loc_district" style="margin-top: 10px" @if($politicalFunction->loc_district) checked @endif>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="area" class="col-sm-3 control-label">{{trans('admin.area')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="area" name="area" style="margin-top: 10px" @if($politicalFunction->area) checked @endif>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="zone" class="col-sm-3 control-label">{{trans('admin.zone')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="zone" name="zone" style="margin-top: 10px" @if($politicalFunction->zone) checked @endif>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="section" class="col-sm-3 control-label">{{trans('admin.section')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="section" name="section" style="margin-top: 10px" @if($politicalFunction->section) checked @endif>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="block" class="col-sm-3 control-label">{{trans('admin.block')}}</label>
                                            <div class="col-sm-9">
                                                <input type="checkbox" value="1" class="recorder" id="block" name="block" style="margin-top: 10px" @if($politicalFunction->block) checked @endif>
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