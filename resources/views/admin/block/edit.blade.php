@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('block.index') }}">{{ trans('admin.block') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('block.update', $block) }}" enctype="multipart/form-data">

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
                <h4 class="roboto">{{trans('admin.block_data')}}</h4>
                <div class="rcorners" style="height: 180px; overflow-y: auto">
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
                        <div class="panel-title roboto">{!! trans('admin.block') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.block') !!}">
                                    <a href="#group_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="block_tab" name="{{trans('admin.block')}}">
                                <div class="rcorners" style="margin-top: 20px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="block_key" class="col-sm-3 control-label">{{trans('admin.block_key')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control make_user recorder" name="block_key" id="block_key" value="{{$block->block_key}}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="section_id" class="col-sm-3 control-label">{{trans('admin.section')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="select2 form-control recorder" name="section_id" id="section_id" data-allow-clear="true" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($sections as $key => $value)
                                                        <option  value="{{ $value['id'] }}" {{ ( $value['id'] == $block->id_section) ? 'selected' : '' }}>{{ $value['section_key'] }}</option>
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