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

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('role.update', $role) }}">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 283px">
                <h4 class="roboto">{{trans('admin.data')}}</h4>
                <div class="rcorners" style="height: 393px; overflow-y: auto">
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
                        <div class="panel-title roboto">{!! trans('admin.role') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.role') !!}">
                                    <a href="#role_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.permissions') !!}">
                                    <a href="#permissions_tab" data-toggle="tab"><i class="entypo-map"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="role_tab" name="{{trans('admin.role')}}">
                                <div class="rcorners" style="margin-top: 10px; height:312px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="name" class="col-sm-3 control-label">{{trans('admin.name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="name" id="name" value="{{$role->name}}" required autofocus/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="type" class="col-sm-3 control-label">{{trans('admin.type')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control make_user recorder" name="type" id="type" data-allow-clear="true" required autofocus>
                                                    @foreach($role_type as $key => $item)
                                                        <option value="{{ $key }}" {{ ( $key == $role->type) ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <label for="description" class="col-sm-3 control-label">{{trans('admin.name')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control make_user recorder" style="resize:none; height: 100px" id="description" name="description">{{$role->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="permissions_tab" name="{{trans('admin.permissions')}}">
                                <div class="rcorners" style="margin-top: 10px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="fed_entity_id" class="col-sm-3 control-label">{{trans('admin.fed_entity')}}</label>
                                            <div class="col-sm-9">
                                                <select id="fed_entity_id" name="fed_entity_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($fed_entitys as $f)
                                                        <option value="{{ $f->id }}" @if($role->fed_entity_id == $f->id ) selected @endif>{{ $f->entity_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 5px">
                                            <label for="municipality_id" class="col-sm-3 control-label">{{trans('admin.municipality')}}</label>
                                            <div class="col-sm-9">
                                                <select id="municipality_id" name="municipality_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($municipalitys as $m)
                                                        <option value="{{ $m->id }}" @if($role->municipality_id == $m->id ) selected @endif>{{$m->municipality_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="fed_district_id" class="col-sm-3 control-label">{{trans('admin.fed_district')}}</label>
                                            <div class="col-sm-9">
                                                <select id="fed_district_id" name="fed_district_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($fed_district as $fd)
                                                        <option value="{{ $fd->id }}" @if($role->fed_district_id == $fd->id ) selected @endif>{{ $fd->district_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="loc_district_id" class="col-sm-3 control-label">{{trans('admin.loc_district')}}</label>
                                            <div class="col-sm-9">
                                                <select id="loc_district_id" name="loc_district_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($loc_district as $ld)
                                                        <option value="{{ $ld->id }}" @if($role->loc_district_id == $ld->id ) selected @endif>{{ $ld->district_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="area_id" class="col-sm-3 control-label">{{trans('admin.area')}}</label>
                                            <div class="col-sm-9">
                                                <select id="area_id" name="area_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($area as $a)
                                                        <option value="{{ $a->id }}" @if($role->area_id == $a->id ) selected @endif>{{ $a->area_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="zone_id" class="col-sm-3 control-label">{{trans('admin.zone')}}</label>
                                            <div class="col-sm-9">
                                                <select id="zone_id" name="zone_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($zone as $z)
                                                        <option value="{{ $z->id }}" @if($role->zone_id == $z->id ) selected @endif>{{ $z->zone_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="section_id" class="col-sm-3 control-label">{{trans('admin.section')}}</label>
                                            <div class="col-sm-9">
                                                <select id="section_id" name="section_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($sections as $s)
                                                        <option value="{{ $s->id }}" @if($role->section_id == $s->id ) selected @endif>{{ $s->section_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="block_id" class="col-sm-3 control-label">{{trans('admin.block')}}</label>
                                            <div class="col-sm-9">
                                                <select id="block_id" name="block_id" class="form-control select2 recorder">
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($block as $b)
                                                        <option value="{{ $b->id }}" @if($role->block_id == $b->id ) selected @endif>{{ $b->block_key }}</option>
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
        (function ($) {
            $(window).on('load', function () {
                $('#fed_entity_id').change(function() {
                    update_select('municipality_id','municipality_name', 'fed_entity_id', $(this).val() , {!! $municipalitys !!});
                });

                $('#loc_district_id').change(function() {
                    update_select('area_id','area_key', 'loc_district_id', $(this).val() , {!! $area !!});
                    update_select('section_id','section_key', 'loc_district_id', $(this).val() , {!! $sections !!});
                });

                $('#fed_district_id').change(function() {
                    update_select('section_id','section_key', 'fed_district_id', $(this).val() , {!! $sections !!});
                });

                $('#municipality_id').change(function() {
                    update_select('section_id','section_key', 'municipality_id', $(this).val() , {!! $sections !!});
                });

                $('#area_id').change(function() {
                    update_select('zone_id','zone_key', 'area_id', $(this).val() , {!! $zone !!});
                });

                $('#section_id').change(function() {
                    update_select('block_id','block_key', 'section_id', $(this).val() , {!! $block !!});
                });
            });
        })(jQuery);

    </script>

@endsection