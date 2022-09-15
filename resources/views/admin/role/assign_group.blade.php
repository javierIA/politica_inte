@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('role.index') }}">{{ trans('admin.role') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="POST" action="{{ route('role.saveGroupRole', $role->id) }}">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 235px">
                <h4 class="roboto">{{trans('admin.data')}}</h4>
                <div class="rcorners">
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
                        <div class="panel-title roboto">{!! trans('admin.political_function') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.political_function') !!}">
                                    <a href="#group_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group{{ $errors->any() ? ' has-error' : '' }}">
                            <div class="rcorners" style="margin-top: 10px; height: 146px">
                                <div class="verticalLine">
                                    <div class="row" style="margin-bottom: 15px">
                                        <label for="group" class="col-sm-4 control-label">{{ trans('admin.group') }}</label>
                                        <div class="col-sm-8" >
                                            <div >
                                                <select class="select2 recorder" name="group[]" id="group" style="max-height: 100px;" data-allow-clear="true" multiple required autofocus>
                                                    @foreach($items as $item)
                                                        <option class="form-control make_user recorder"  value="{{ $item['id'] }}" @if($item['active']) selected @endif>{{ $item['group_name'] }} </option>
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