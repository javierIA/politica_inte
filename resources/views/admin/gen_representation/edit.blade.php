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

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('gen_representation.update', $genRepresentation) }}" enctype="multipart/form-data">

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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px; height: 350px">
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
                    <div class="col-md-9">
                        <div class="top_rcorners" style="margin-left: -30px; color: #003756; font-family: 'Century Gothic'">
                            {{$title}}
                        </div>
                    </div>
                </div>
                <div class="panel minimal" >
                    <!-- panel head -->
                    <div class="panel-heading">
                        <div class="panel-title roboto">{!! trans('admin.gen_representation') !!}</div>
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
                            <div class="tab-pane active" id="personal_data_tab" name="{{trans('admin.gen_representation')}}">
                                <div class="rcorners" style="margin-top: 20px">
                                    <div class="verticalLine">
                                        <div class="row">
                                            <label for="gen_representation_key" class="col-sm-3 control-label">{{trans('admin.key')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control recorder" name="gen_representation_key" id="gen_representation_key" value="{{$genRepresentation->gen_representation_key}}" required autofocus/>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_fed_district" class="col-sm-3 control-label">{{trans('admin.fed_district')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control recorder" name="id_fed_district" id="id_fed_district" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($fed_district as $f)
                                                        <option value="{{ $f->id }}" @if($genRepresentation->id_fed_district == $f->id) selected @endif>{{ $f->district_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_loc_district" class="col-sm-3 control-label">{{trans('admin.loc_district')}}<span style="color: red">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control recorder" name="id_loc_district" id="id_loc_district" required autofocus>
                                                    <option value="" selected>--- {{trans('admin.select')}} ---</option>
                                                    @foreach($loc_district as $f)
                                                        <option value="{{ $f->id }}" @if($genRepresentation->id_loc_district == $f->id) selected @endif >{{ $f->district_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" data-class="titular" style="margin-top: 5px">
                                            <label for="titular_text" class="col-sm-3 control-label">{{ trans('admin.titular') }}</label>
                                            <div class="col-sm-7">
                                                <input type="hidden" name="titular_person" id="titular_person" value="{{$genRepresentation->titular_person}}"/>
                                                <input type="text"
                                                       class="form-control readonly recorder modal_input_button"
                                                       id="titular_text"
                                                       data-button="titular_text_button"
                                                       data-column='person',
                                                       data-filter = 'filter-menu-person',
                                                       autocomplete="off"
                                                       value="{{!is_null($genRepresentation->titular_person)? $genRepresentation->titular()->get()[0]->get_full_name():'' }}"
                                                       readonly/>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" id="titular_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                        title="{{trans('admin.search_titular')}}" data-toggle="modal"
                                                        data-target="#myModalPerson" data-class="titular">
                                                    <i class="entypo-search"></i>
                                                </button>
                                                <button type="button" name="btnTrash"
                                                        class="btn btn-danger btn-sm clean_button"
                                                        title="{{trans('admin.clean')}}" data-class="titular">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row" data-class="vocal" style="margin-top: 5px">
                                            <label for="vocal_text" class="col-sm-3 control-label recorder">{{ trans('admin.vocal') }}</label>
                                            <div class="col-sm-7">
                                                <input type="hidden" name="vocal_person" id="vocal_person" value="{{$genRepresentation->vocal_person}}"/>
                                                <input type="text"
                                                       class="form-control readonly recorder modal_input_button"
                                                       id="vocal_text"
                                                       data-button="vocal_text_button"
                                                       data-column='person',
                                                       data-filter = 'filter-menu-person',
                                                       autocomplete="off"
                                                       value="{{!is_null($genRepresentation->vocal_person)? $genRepresentation->vocal()->get()[0]->get_full_name():'' }}"
                                                       readonly/>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" id="vocal_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                        title="{{trans('admin.search_titular')}}" data-toggle="modal"
                                                        data-target="#myModalPerson" data-class="vocal">
                                                    <i class="entypo-search"></i>
                                                </button>
                                                <button type="button" name="btnTrash"
                                                        class="btn btn-danger btn-sm clean_button"
                                                        title="{{trans('admin.clean')}}" data-class="vocal">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="file" class="col-sm-3 control-label">{{trans('admin.map')}}</label>
                                            <div class="col-sm-9">
                                                <input class="form-control make_user recorder" type="file" name="file" id="file" accept="application/pdf">
                                                @if(!is_null($genRepresentation->map_pdf))
                                                    <a href="{{ url('pdf/'.$genRepresentation->getTable().'/'.basename($genRepresentation->map_pdf)) }}" add target="_blank" class="d-flex align-items-center justify-content-center login"><span ><i class="fa fa-map fa-lg"> {{basename($genRepresentation->map_pdf)}}</i></span></a>
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
        function fillsearch(columns, menu){
            var keys = Object.keys(columns);
            update_filters(keys, columns, menu, false);
        }

        (function ($) {
            $(window).on('load', function () {

                $(".modal-select-address").click(function () {
                    var item = search_address_table.DataTable().rows({selected: true}).data();
                    var obj = item[0];
                    $('#' + $(this).data("class") + '_text').val(obj.street + ', No.' + obj.external_number + ', {!! trans('admin.neighborhood') !!}:' + obj.neighborhood + ', {!! trans('admin.postal_code') !!}:' + obj.postal_code);

                    $('#' + $(this).data("class")).val(obj.id_address);
                    record($('#' + $(this).data("class") + '_text'));
                    $('.modal-close').click();
                });

                $('.modal_input_button').click(function(){
                    var butt = $(this).data('button');
                    var column = $(this).data('column');
                    var filter = $(this).data('filter');
                    $('#'+butt).click();

                    $('#'+ filter).empty();
                    fillsearch({!! json_encode($columns_person) !!}, filter);
                });

                /*person table*/
                var search_person_table = $("#search-person-table");
                search_person_table.DataTable({
                    "language":{
                        "url": "{{ asset('js/datatable/'.App::getLocale().'/datatable.json') }}",
                        "select": {
                            rows: "{!!trans('admin.rows_selected')!!}"
                        }
                    },
                    select: {
                        style: 'single'
                    },
                    "searching": false,
                    "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                    "stateSave": true,
                    "displayLength": 10,
                    "pageLength": 10,
                    //desde aqui
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                        "url": "{{route('person.filter')}}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d){update_params(d,'filter-form-person')}
                    },
                    "columns": [
                        { "data": "full_name" },
                        { "data": "persons_email" },
                        { "data": "persons_cellphone" },
                        { "data": "elector_key" },
                    ]
                });
                search_person_table.DataTable()
                    .on('select', function (e, dt, type, indexes) {
                        $('.modal-select-person').removeClass('disabled');
                    })
                    .on('deselect', function (e, dt, type, indexes) {
                        $('.modal-select-person').addClass('disabled');
                    });

                search_person_table.closest('.dataTables_wrapper').find('select').select2({
                    minimumResultsForSearch: -1
                });

                $(".search_button").click(function () {
                    $('#modal-form-person').trigger("reset");
                    $(".modal-select-person").data('class', $(this).data("class"));
                });

                $(".modal-select-person").click(function () {
                    var item = search_person_table.DataTable().rows({selected: true}).data();
                    var obj = item[0];

                    $('#' + $(this).data("class") + '_text').val(obj.full_name);
                    $('#' + $(this).data("class")).val(obj.id_persons);
                    record($('#' + $(this).data("class") + '_text'));
                    $('.modal-close').click();
                });

                $(".clean_button").click(function () {
                    var name = $(this).data('class');
                    $('#' + name + '_text').val('');
                    $('#' + name).val('');
                    record($('#' + name + '_text'));
                    fill_icons();
                });

                $( "#filter-form-person" ).submit(function( event ) {
                    event.preventDefault();
                    search_person_table.DataTable().ajax.reload();
                });
            });
        })(jQuery);
    </script>

@endsection