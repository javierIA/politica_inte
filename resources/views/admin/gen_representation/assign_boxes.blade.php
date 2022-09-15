@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('gen_representation.index') }}">{{ trans('admin.gen_representation') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('script_up')
    <script src="{{ asset('js/icon-picker/icon-picker.js') }}"></script>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{route('gen_representation.saveAssignBoxes',$gen_representation->id)}}" enctype="multipart/form-data">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 313px">
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
                        <div class="panel-title roboto">{!! trans('admin.boxes') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.gen_representation') !!}">
                                    <a href="#group_tab" data-toggle="tab"><i class="fa fa-cog"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="street_tab" name="{{trans('admin.boxes')}}">
                                <div class="rcorners">
                                    <div class="row" style="margin-bottom: 15px">
                                        <label for="sections" class="col-sm-3 control-label">{{ trans('admin.gen_representation') }}</label>
                                        <div class="col-sm-9" >
                                            <div >
                                                <select id="sections" class="form-control select2" >
                                                    <option value="" selected>---{{trans('admin.select')}} ---</option>
                                                    @foreach($sections as $s)
                                                        <option value="{{ $s->id }}">{{ $s->section_key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 15px">
                                        <label for="boxes" class="col-sm-3 control-label">{{ trans('admin.box') }}</label>
                                        <div class="col-sm-9" >
                                            <div >
                                                <select class="select2 recorder" name="boxes[]" id="boxes" style="max-height: 150px;height: 150px; line-height: 150px" data-allow-clear="true" multiple autofocus>
                                                    @foreach($boxes as $b)
                                                        <option class="recorder testing" value="{{ $b->id }}" style="color: #f9f8ba" data-item="{{json_encode($b)}}" @if($b->id_gen_representation == $gen_representation->id) selected @endif>{{ $b->name }} </option>
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
        var gen_representation = {!! $gen_representation !!};
        function updateOptions(){
            $( "#boxes option:selected" ).each( function( i, v ) {
                var data = $(this).data('item');
                if(data != undefined) {                    ;
                    $('.select2-search-choice').each(function (i, v) {
                        if (jQuery.trim(v.textContent) == jQuery.trim(data.name) && data.id_gen_representation != null) {
                            if (data.id_gen_representation != gen_representation.id) {
                                $(this).attr("title", "{{trans('admin.gen_representation') . ': '}}" + data.gen_representation_key);
                                $(this).css("background-color", "#f1ee7d");
                            }
                        }
                    });
                }
            });

/*
            var last = $( "#boxes option:selected:last" ).data('item');
            console.info(last);
            console.info(gen_representation.id);
            console.info(last.id_gen_representation != null)
            console.info(last.id_gen_representation != gen_representation.id);
            console.info(last.id_gen_representation != null && last.id_gen_representation != gen_representation.id);
            if( last.id_gen_representation != gen_representation.id)
                $.notify(["{{trans('admin.box')}}", last.name, "{{trans('admin.assigned_to')}}", "{{trans('admin.gen_representation')}}", last.gen_representation_key].reduce(_r, '%s: %s %s %s: %s '), 'warn');
*/

        };

        $(document).ready(function() {
            $( "#boxes" ).change(function(evt, params) {
                updateOptions();
                try{
                    var data = $( '#boxes option[value="'+ evt.added.element[0].value +'"]' ).data('item');
                    if( data.id_gen_representation != null && data.id_gen_representation != gen_representation.id)
                        $.notify(["{{trans('admin.box')}}", data.name, "{{trans('admin.assigned_to')}}", "{{trans('admin.gen_representation')}}", data.gen_representation_key].reduce(_r, '%s: %s %s %s: %s '), 'warn');
                }
                catch(error){}
            });
            updateOptions();

            $('#sections').change(function() {
                update_select('boxes','name', 'id_section', $(this).val() , {!! $boxes !!}, false);
            });
        });
    </script>
@endsection