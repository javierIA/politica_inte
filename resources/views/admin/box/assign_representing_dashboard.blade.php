@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-home"></i>{{ trans('admin.home') }}
        </a>
    </li>
    <li>
        <a href="{{ route('dashboard.assignRepresentingTable') }}">{{ trans('admin.assignRepresentingTable') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered methods-form" method="POST" action="{{route('dashboard.saveRepresenting', $box->id)}}">
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
            <div class="col-md-3" style="margin-top: -10px; padding-top: 10px">
                <h4 class="roboto">{{trans('admin.data')}}</h4>
                <div class="rcorners" style="height: 483px">
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
                        <div class="panel-title roboto">{!! trans('admin.box') !!}</div>
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active" data-name="{!! trans('admin.box') !!}">
                                    <a href="#box_tab" data-toggle="tab"><i class="fa fa-building"></i></a>
                                </li>
                                <li data-name="{!! trans('admin.representatives') !!}">
                                    <a href="#representatives_tab" data-toggle="tab"><i class="entypo-users"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="box_tab" name="{{trans('admin.person')}}">
                                <div class="rcorners" style="height: 414px">
                                    <div class="verticalLine">
                                        <div class="row" style="margin-top: 5px">
                                            <label class="col-sm-3 control-label">{{trans('admin.section')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control recorder" value="{{$box->section()->get()[0]->section_key . ' ('. trans('admin.'.$box->section()->get()[0]->section_type).')'}}" readonly autofocus/>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label class="col-sm-3 control-label">{{trans('admin.box_type')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control recorder" value="{{$box->box_type()->get()[0]->box_type_name}}" readonly autofocus/>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label class="col-sm-3 control-label">{{trans('admin.address')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control recorder" value="{{!is_null($box->id_address)? $box->get_address()->street.', No.'.$box->get_address()->external_number.', '.trans('admin.neighborhood').': '. $box->get_address()->neighborhood.', '.trans('admin.postal_code').' '.$box->get_address()->postal_code:''}}" readonly autofocus/>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="id_box_type" class="col-sm-3 control-label">{{trans('admin.owner')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control recorder" value="{{!is_null($box->owner)? $box->owner()->get()[0]->get_full_name():''}}" readonly autofocus/>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 5px">
                                            <label for="description" class="col-sm-3 control-label">{{trans('admin.description')}}</label>
                                            <div class="col-sm-9" >
                                                <textarea style="resize:none; height: 91px" class="form-control make_user recorder" name="description" id="description" readonly>{{$box->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="representatives_tab" name="{{trans('admin.representatives')}}" >
                                    <div class="rcorners" style="height:414px">
                                        <div class="verticalLine">
                                            <div class="row" data-class="president">
                                                <label for="president_text" class="col-sm-3 control-label recorder">{{ trans('admin.president') }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="president" id="president" value="{{$box->president}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="president_text"
                                                           data-button="president_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->president)? \App\Person::findOrFail($box->president)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="president_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_president')}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="president">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="president">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row" data-class="secretary" style="margin-top: 5px">
                                                <label for="secretary_text" class="col-sm-3 control-label recorder">{{ trans('admin.secretary') }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="secretary" id="secretary" value="{{$box->secretary}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="secretary_text"
                                                           data-button="secretary_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->secretary)? \App\Person::findOrFail($box->secretary)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="secretary_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_president')}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="secretary">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="secretary">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="verticalLine" style="margin-top: 10px">
                                            <div class="row" data-class="substitute1" style="margin-top: 5px">
                                                <label for="substitute1_text" class="col-sm-3 control-label recorder">{{ trans('admin.substitute').' 1' }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="substitute1" id="substitute1" value="{{$box->substitute1}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="substitute1_text"
                                                           data-button="substitute1_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->substitute1)? \App\Person::findOrFail($box->substitute1)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="substitute1_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_substitute'). ' 1'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="substitute1">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="substitute1">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row" data-class="substitute2" style="margin-top: 5px">
                                                <label for="substitute2_text" class="col-sm-3 control-label recorder">{{ trans('admin.substitute').' 2' }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="substitute2" id="substitute2" value="{{$box->substitute2}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="substitute2_text"
                                                           data-button="substitute2_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->substitute2)? \App\Person::findOrFail($box->substitute2)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="substitute2_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_substitute'). ' 2'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="substitute2">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="substitute2">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row" data-class="substitute3" style="margin-top: 5px">
                                                <label for="substitute3_text" class="col-sm-3 control-label recorder">{{ trans('admin.substitute').' 3' }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="substitute3" id="substitute3" value="{{$box->substitute3}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="substitute3_text"
                                                           data-button="substitute3_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->substitute3)? \App\Person::findOrFail($box->substitute3)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="substitute3_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_substitute'). ' 3'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="substitute3">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="substitute3">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="verticalLine" style="margin-top: 10px">
                                            <div class="row" data-class="teller1" style="margin-top: 5px">
                                                <label for="teller1_text" class="col-sm-3 control-label recorder">{{ trans('admin.teller').' 1' }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="teller1" id="teller1" value="{{$box->teller1}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="teller1_text"
                                                           data-button="teller1_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->teller1)? \App\Person::findOrFail($box->teller1)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="teller1_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_teller'). ' 1'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="teller1">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="teller1">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row" data-class="teller2" style="margin-top: 5px">
                                                <label for="teller1_text" class="col-sm-3 control-label recorder">{{ trans('admin.teller').' 2' }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="teller2" id="teller2" value="{{$box->teller2}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="teller2_text"
                                                           data-button="teller2_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->teller2)? \App\Person::findOrFail($box->teller2)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="teller2_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_teller'). ' 2'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="teller2">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="teller2">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="verticalLine" style="margin-top: 10px">
                                            <div class="row" data-class="titular_person1" style="margin-top: 5px">
                                                <label for="titular_person1_text" class="col-sm-3 control-label recorder">{{ trans('admin.titular_person').' 1' }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="titular_person1" id="titular_person1" value="{{$box->titular_person1}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="titular_person1_text"
                                                           data-button="titular_person1_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->titular_person1)? \App\Person::findOrFail($box->titular_person1)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="titular_person1_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_titular_person'). ' 1'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="titular_person1">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="titular_person1">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row" data-class="titular_person2" style="margin-top: 5px">
                                                <label for="titular_person2_text" class="col-sm-3 control-label recorder">{{ trans('admin.titular_person').' 2' }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="titular_person2" id="titular_person2" value="{{$box->titular_person2}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="titular_person2_text"
                                                           data-button="titular_person2_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->titular_person2)? \App\Person::findOrFail($box->titular_person2)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="titular_person2_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_titular_person'). ' 2'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="titular_person2">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="titular_person2">
                                                        <i class="entypo-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="verticalLine" style="margin-top: 10px">
                                            <div class="row" data-class="vocal_person" style="margin-top: 5px">
                                                <label for="vocal_person_text" class="col-sm-3 control-label recorder">{{ trans('admin.vocal_person') }}</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="vocal_person" id="vocal_person" value="{{$box->vocal_person}}"/>
                                                    <input type="text"
                                                           class="form-control readonly recorder modal_input_button"
                                                           id="vocal_person_text"
                                                           data-button="vocal_person_text_button"
                                                           data-column='person',
                                                           data-filter = 'filter-menu-person',
                                                           autocomplete="off"
                                                           value="{{!is_null($box->vocal_person)? \App\Person::findOrFail($box->vocal_person)->get_full_name(): ''}}"
                                                           readonly/>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" id="vocal_person_text_button" class="btn btn-blue btn-sm search_button hidden_button"
                                                            title="{{trans('admin.search_vocal_person'). ' 1'}}" data-toggle="modal"
                                                            data-target="#myModalPerson" data-class="vocal_person">
                                                        <i class="entypo-search"></i>
                                                    </button>
                                                    <button type="button" name="btnTrash"
                                                            class="btn btn-danger btn-sm clean_button"
                                                            title="{{trans('admin.clean')}}" data-class="vocal_person">
                                                        <i class="entypo-trash"></i>
                                                    </button>
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


    <!-- Person modal -->
    <div class="modal fade" id="myModalPerson">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{trans('admin.filter') . ' '. trans('admin.of'). ' '. trans('admin.person')}}</h4>
                </div>
                <div class="modal-body" id="modalBody">
                    {{--start filter--}}
                    <div class="rcorners">
                        <form role="form" id="filter-form-person" class="form-horizontal form-groups-bordered" method="post">
                            {{ csrf_field() }}
                            <div class="row" style="padding-left: 30px">
                                <div class="col-sm-offset-11 col-sm-1 filter_submit" >
                                    <button type="submit" class="btn btn-blue btn-icon pull-right" style="margin-top: -5px;">
                                        {{trans('admin.search')}}
                                        <i class="entypo-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="filter-menu-person" hidden style="margin-bottom: 10px"></div>
                        </form>
                    </div>
                    {{--end filter--}}
                    <table class="table table-bordered table-striped datatable" id="search-person-table" style="width: 100%">
                        <thead>
                        <tr>
                            <th>{{trans('admin.name')}}</th>
                            <th>{{trans('admin.email')}}</th>
                            <th>{{trans('admin.person_cellphone')}}</th>
                            <th>{{trans('admin.elector_key')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close"
                            data-dismiss="modal">{{trans('admin.close')}}</button>
                    <button type="button"
                            class="btn btn-primary modal-select-person disabled">{{trans('admin.select')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Person modal end -->
@endsection

@section('script_down')
    <script type="text/javascript">

        function fillsearch(columns, menu){
            var keys = Object.keys(columns);
            update_filters(keys, columns, menu, false);
        }

        (function ($) {
            $(window).on('load', function () {

                $('.modal_input_button').click(function(){
                    var butt = $(this).data('button');
                    var column = $(this).data('column');
                    var filter = $(this).data('filter');
                    $('#'+butt).click();

                    $('#'+ filter).empty();
                    fillsearch({!! json_encode($columns_person) !!}, filter);
                });

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
                    $('#modal-form-address').trigger("reset");
                    $(".modal-select-person").data('class', $(this).data("class"));
                    $(".modal-select-address").data('class', $(this).data("class"));
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