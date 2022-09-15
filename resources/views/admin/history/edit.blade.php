@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('history.index') }}">{{ trans('admin.history') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" >

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
        <div class="col-md-12">
            <div class="col-md-12" style="margin-bottom: 15px">
                <div class="col-md-8">
                    <div class="top_rcorners" style="margin-left: -30px; color: #003756; font-family: 'Century Gothic'">
                        {{$title}}
                    </div>
                </div>
            </div>

            <div class="panel minimal" >

                <!-- panel head -->
                <div class="panel-heading">
                    <div class="panel-title roboto">{!! trans('admin.history') !!}</div>
                    <div class="panel-options">
                        <ul class="nav nav-tabs">
                            <li class="active" data-name="{!! trans('admin.history') !!}">
                                <a href="#history_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- panel body -->
                <div class="panel-body">

                    <div class="tab-content">
                        <div class="tab-pane active" id="history_tab" name="{{trans('admin.history')}}">
                            <div class="rcorners" style="margin-top: 20px">
                                <div class="verticalLine">
                                    <div class="row">
                                        <label for="group_name" class="col-sm-3 control-label">{{trans('admin.action') }}<span style="color: red"></span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-lg" name="action" id="action" value="{{$history->action}}" readonly autofocus/>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <label for="group_name" class="col-sm-3 control-label">{{trans('admin.table') }}<span style="color: red"></span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-lg" name="action" id="action" value="{{$history->table}}" readonly autofocus/>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <label for="group_name" class="col-sm-3 control-label">{{trans('admin.user') }}<span style="color: red"></span></label>
                                        <div class="col-sm-9">
<textarea class="form-control" style="resize:none; height: 100px"  name="user" id="field-ta" readonly>
{{trans('admin.name').': '.json_decode($history->user)->name}}
{{trans('admin.email').': '.json_decode($history->user)->email}}
{{trans('admin.created_date').': '. date( "d/m/Y", strtotime(json_decode($history->user)->created_at) )  }}
{{trans('admin.created_time').': '. date( "h:i:s A", strtotime(json_decode($history->user)->created_at) )  }}
</textarea>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <label for="group_name" class="col-sm-3 control-label">{{trans('admin.role') }}<span style="color: red"></span></label>
                                        <div class="col-sm-9">
<textarea class="form-control" style="resize:none; height: 100px"  name="role" id="field-ta" readonly>
{{trans('admin.name').': '.json_decode($history->role)->name}}
{{trans('admin.created_date').': '. date( "d/m/Y", strtotime(json_decode($history->role)->created_at) )  }}
{{trans('admin.created_time').': '. date( "h:i:s A", strtotime(json_decode($history->role)->created_at) )  }}
</textarea>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <label for="group_name" class="col-sm-3 control-label">{{trans('admin.description') }}<span style="color: red"></span></label>
                                        <div class="col-sm-9">
<textarea class="form-control" style="resize:none; height: 100px"  name="description" id="field-ta" readonly>
{{$keys}}
</textarea>
                                        </div>
                                    </div>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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