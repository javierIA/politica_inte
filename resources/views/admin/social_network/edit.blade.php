@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('social_network.index') }}">{{ trans('admin.social_network') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('social_network.update', $socialNetwork) }}">

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
                        <div class="panel-title roboto">{!! trans('admin.social_network') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.social_network') !!}">
                                    <a href="#social_network_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="social_network_tab" name="{{trans('admin.social_network')}}">
                                <div class="rcorners" style="margin-top: 20px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="name_social_network" class="col-sm-3 control-label">{{trans('admin.name_social_network')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control make_user recorder" name="name_social_network" id="name_social_network" value="{{$socialNetwork->name_social_network}}" required autofocus/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="icon" class="col-sm-3 control-label" style="margin-top: 20px;">{{trans('admin.icon')}}</label>
                                            <div class="col-sm-9" style="margin-left: -5px; height: 115px">
                                                <div class="icon-picker" data-pickerid="icon">
                                                    <input type="hidden" value="" name="icon" id="icon" class="recorder"/>
                                                </div>
                                                <div class="fa-set icon-set">
                                                    <ul>
                                                        <li data-class="fa fa-chrome" class="fa fa-chrome"></li>
                                                        <li data-class="fa fa-facebook" class="fa fa-facebook"></li>
                                                        <li data-class="fa fa-twitter" class="fa fa-twitter"></li>
                                                        <li data-class="fa fa-whatsapp" class="fa fa-whatsapp"></li>
                                                        <li data-class="fa fa-pinterest" class="fa fa-pinterest"></li>
                                                        <li data-class="fa fa-youtube" class="fa fa-youtube"></li>
                                                        <li data-class="fa fa-instagram" class="fa fa-instagram"></li>
                                                        <li data-class="fa fa-linkedin" class="fa fa-linkedin"></li>
                                                    </ul>
                                                </div>
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
                'defaultIcon': '{!! $socialNetwork->icon !!}',
                'iconSets' : {               // used to specify which launchers will be created
                    'fa' : '{!! trans('admin.select') !!}'       // create a launcher to pick fontawesome icons
                }
            });

            $('.remove-icon').remove();
        });
    </script>

@endsection