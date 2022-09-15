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
    <form role="form" class="form-horizontal form-groups-bordered methods-form" method="POST" action="{{ route('role.saveFunctionRole', $role->id) }}">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 927px">
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
                        <div class="panel-title roboto">{!! trans('admin.role_functions') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.role_functions') !!}">
                                    <a href="#group_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group{{ $errors->any() ? ' has-error' : '' }}">
                            <div class="rcorners" style="margin-top: 20px">
                                <div class="verticalLine">
                                    @foreach($items as $i)
                                        <div class="row" style="margin-bottom: 15px">
                                            <label for="methods_{{$i['id']}}" class="col-sm-offset-1 col-sm-3 control-label">{{ $i['system_function_name'] }}</label>
                                            <div class="col-sm-8" >
                                                <div >
                                                    <select class="select2 system_function_methods form-control make_user recorder" name='methods_{{$i['id']}}' id='methods_{{$i['id']}}' style="max-height: 100px;" data-controller="{{$i['id']}}" data-allow-clear="true" multiple  autofocus>
                                                        @foreach($i['methods'] as $key => $val)
                                                            <option value="{{ $key }}" @if(in_array($key, $i['method_active'])) selected @endif>{{ trans('admin.'.$key) }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <br/>
            <div class="row">
                <div class="col-sm-offset-8 col-sm-2 text-right">
                    <button type="submit" class="btn btn-default">{{ trans('admin.send') }}</button>
                </div>
            </div>
        </div>

    </form>
@endsection

@section('script_down')
    <script type="text/javascript">
        (function($){
            $(window).on('load',function(){
                $( ".methods-form" ).submit(function( event ) {
                    event.preventDefault();
                    var results = new Array();
                    $('.system_function_methods').each(function( index ) {
                        var controller = $(this).data('controller');
                        if(controller != undefined)
                            results.push({controller: controller, methods: $(this).val()});
                    });
                    $.post("{{route('role.saveFunctionRole',$role->id)}}",
                        {
                            "_token": "{{ csrf_token() }}",
                            'value': results
                        },
                        function (data, status) {
                            if(status){
                                var decoded_data = jQuery.parseJSON(data);
                                if(decoded_data.status)
                                    window.location = decoded_data.url;
                                else
                                    $.notify([decoded_data.error].reduce(_r, '{!! trans('admin.error_detected') !!}'), "error");
                            }

                        });
                });
            });
        })(jQuery);
    </script>
@endsection