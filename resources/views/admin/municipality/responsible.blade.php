@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}"> <i class="fa fa-home"></i>{{ trans('admin.home') }} </a>
    </li>
    <li>
        <a href="{{ route('municipality.index') }}">{{ trans('admin.municipality') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <form role="form" class="form-horizontal form-groups-bordered" method="post" action="{{ route('municipality.saveResponsible', $municipality->id) }}">

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
            <div style="margin-left: 200px; margin-right: 100px; margin-bottom: 50px;  margin-top: 10px;">
                <h2 style="margin-left: 90px;">{{trans('admin.territorial')}}</h2>
                <hr >
            </div>

            <div class="row" data-class="titular_person">
                <label for="area_key" class="col-sm-4 control-label">{{ trans('admin.titular_person') }}</label>
                <div class="col-sm-4">
                    <input type="hidden" name="titular_person"  id="titular_person"/>
                    <input type="text" class="form-control input-lg" id="titular_person_text" value="{{ is_null($municipality->titular_person)? '': $municipality->titular_person()->get_full_name() }}" required readonly autofocus/>
                </div>
                <button type="button" style="margin-top: 5px;" class="btn btn-blue btn-sm search_button" title="{{trans('admin.search_person')}}" data-toggle="modal" data-target="#myModal">
                    <i class="entypo-search"></i>
                </button>
                <button type="button" style="margin-top: 5px;" name="btnTrash" class="btn btn-danger btn-sm clean_button" title="{{trans('admin.clean')}}">
                    <i class="entypo-trash"></i>
                </button>
            </div>
            <br/>
            <div class="row" data-class="vocal_person">
                <label for="area_key" class="col-sm-4 control-label">{{ trans('admin.vocal_person') }}</label>
                <div class="col-sm-4">
                    <input type="hidden" name="vocal_person"  id="vocal_person"/>
                    <input type="text" class="form-control input-lg" id="vocal_person_text" value="{{ is_null($municipality->vocal_person)? '': $municipality->vocal_person()->get_full_name() }}" required readonly autofocus/>
                </div>
                <button type="button" style="margin-top: 5px;" class="btn btn-blue btn-sm search_button" title="{{trans('admin.search_person')}}" data-toggle="modal" data-target="#myModal">
                    <i class="entypo-search"></i>
                </button>
                <button type="button" style="margin-top: 5px;" name="btnTrash" class="btn btn-danger btn-sm clean_button" title="{{trans('admin.clean')}}">
                    <i class="entypo-trash"></i>
                </button>
            </div>
            <br/>

            <div style="margin-left: 200px; margin-right: 100px; margin-bottom: 50px;  margin-top: 10px;">
                <h2 style="margin-left: 90px;">{{trans('admin.electoral')}}</h2>
                <hr >
            </div>
            <div class="row" data-class="representative">
                <label for="area_key" class="col-sm-4 control-label">{{ trans('admin.representative') }}</label>
                <div class="col-sm-4">
                    <input type="hidden" name="representative"  id="representative"/>
                    <input type="text" class="form-control input-lg" id="representative_text" value="{{ is_null($municipality->representative)? '': $municipality->representative()->get_full_name() }}" required readonly autofocus/>
                </div>
                <button type="button" style="margin-top: 5px;" class="btn btn-blue btn-sm search_button" title="{{trans('admin.search_person')}}" data-toggle="modal" data-target="#myModal">
                    <i class="entypo-search"></i>
                </button>
                <button type="button" style="margin-top: 5px;" name="btnTrash" class="btn btn-danger btn-sm clean_button" title="{{trans('admin.clean')}}">
                    <i class="entypo-trash"></i>
                </button>
            </div>
            <br/>
            <div class="row" data-class="alternate">
                <label for="area_key" class="col-sm-4 control-label">{{ trans('admin.alternate') }}</label>
                <div class="col-sm-4">
                    <input type="hidden" name="alternate"  id="alternate"/>
                    <input type="text" class="form-control input-lg" id="alternate_text" value="{{ is_null($municipality->alternate)? '': $municipality->alternate()->get_full_name() }}" required readonly autofocus/>
                </div>
                <button type="button" style="margin-top: 5px;" class="btn btn-blue btn-sm search_button" title="{{trans('admin.search_person')}}" data-toggle="modal" data-target="#myModal">
                    <i class="entypo-search"></i>
                </button>
                <button type="button" style="margin-top: 5px;" name="btnTrash" class="btn btn-danger btn-sm clean_button" title="{{trans('admin.clean')}}">
                    <i class="entypo-trash"></i>
                </button>
            </div>
            <br/>
            <br/>
            <div class="row">
                <div class="col-sm-offset-8 col-sm-2 text-right">
                    <button type="submit" class="btn btn-default">{{ trans('admin.send') }}</button>
                </div>
            </div>
        </div>

    </form>

    <!-- Person modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{trans('admin.filter') . ' '. trans('admin.of'). ' '. trans('admin.person')}}</h4>
                </div>
                <div class="modal-body" id="modalBody">
                    <form role="form" id="modal-form" class="form-horizontal form-groups-bordered" method="post">
                        {{ csrf_field() }}
                        <div class="control-group col-sm-4">
                            <label for="municipality" class="control-label">{{ trans('admin.municipality') }}</label>
                            <select id="id_municipality" name="id_municipality" class="form-control select2-single person_filter">
                                <option value="" selected>---{{trans('admin.select')}} ---</option>
                                @foreach($municipalities as $m)
                                    <option value="{{ $m->id }}" @if($m->id == $municipality->id) selected @endif>{{ ' ('. trans('admin.municipality_key').': '.$m->municipality_key . ') '.$m->municipality_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="control-group col-sm-8"></div>
                    </form>

                    <br/><br/><br/><br/><br/>
                    <table class="table table-bordered table-striped datatable" id="search-person-table" style="width: 100%">
                        <thead>
                        <tr>
                            <th hidden>{{trans('admin.id')}}</th>
                            <th style="width: 50%;">{{trans('admin.name')}}</th>
                            <th style="width: 40%;">{{trans('admin.elector_key')}}</th>
                            <th style="width: 10%;">{{trans('admin.card_pdf')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close" data-dismiss="modal">{{trans('admin.close')}}</button>
                    <button type="button" class="btn btn-primary modal-select disabled" >{{trans('admin.select')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Person modal end -->

@endsection

@section('script_down')
    <script type="text/javascript">

        (function($){
            $(window).on('load',function(){
                var search_person_table = $("#search-person-table");
                search_person_table.DataTable( {
                    "language":{
                        "url": "{{ asset('js/datatable/'.App::getLocale().'/datatable.json') }}",
                        "select": {
                            rows: "{!!trans('admin.rows_selected')!!}"
                        }
                    },
                    select: {
                        style: 'single'
                    },
                    "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                    "stateSave": true,
                    "displayLength": 10,
                    "pageLength": 10,
                    "aoColumns" : [
                        { aTargets: [ 0 ], sClass: "hidden" },
                        { Sortable: true, aTargets: [ 1 ] },
                        { Sortable: true, aTargets: [ 2 ] },
                        { Sortable: true, aTargets: [ 3 ] },
                    ],
                    "columnDefs ": [
                        { "targets": [0], "visible": false},
                        { "targets": [1], "orderable": false, "searchable": false},
                        { "targets": [2], "orderable": false, "searchable": false},
                        { "targets": [3], "orderable": false, "searchable": false},
                    ],
                    "processing": true,
                });

                search_person_table.DataTable()
                    .on( 'select', function ( e, dt, type, indexes ) {
                        $('.modal-select').removeClass('disabled');
                    } )
                    .on( 'deselect', function ( e, dt, type, indexes ) {
                        $('.modal-select').addClass('disabled');
                    } );

                search_person_table.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
                    minimumResultsForSearch: -1
                });

                $("#modal-body").on("shown.bs.modal", function() {
                    $(".select2-single").select2();
                });

                $(".modal-select").click(function() {
                    $('#'+$(this).data("class")+'_text').val(search_person_table.DataTable().rows( { selected: true } ).data()[0][1]);
                    $('#'+$(this).data("class")).val(search_person_table.DataTable().rows( { selected: true } ).data()[0][0]);
                    $('.modal-close').click();
                });

                $(".search_button").click(function() {
                    $(".modal-select").data('class', $(this).parent().data("class"));
                });

                $(".clean_button").click(function() {
                    var name = $(this).parent().data('class');
                    $('#'+name+'_text').val('');
                    $('#'+name).val('-1');
                });

                $('.person_filter').on('change', function (e) {
                    search();
                });

                function search(){
                    $.post("{{route('person.filter')}}",
                        $('#modal-form').serialize(),
                        function (data, status) {
                            //var t = $('#search-person-table').DataTable();
                            search_person_table.DataTable().row().remove().draw();
                            var decoded_data = jQuery.parseJSON(data);
                            decoded_data.forEach(function(person, index) {
                                search_person_table.DataTable().row.add( [
                                    person.id,
                                    person.person_name + ' ' + person.father_lastname + ' ' + person.mother_lastname,
                                    person.elector_key,
                                    person.elector_key
                                ] ).draw( false );
                            });
                        });
                };
                search();
            });
        })(jQuery);
    </script>
@endsection