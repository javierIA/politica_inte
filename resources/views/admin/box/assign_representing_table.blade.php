@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('home') }}"><i class="fa fa-home"></i>{{ trans('admin.home') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    {{-- start filter--}}
    <div class="rcorners">
        <form role="form" id="filter-form" class="form-horizontal form-groups-bordered" method="post">
            {{ csrf_field() }}
            <div class="row" style="padding-left: 30px">
                <div class="col-sm-offset-11 col-sm-1 filter_submit" >
                    <button type="submit" class="btn btn-blue btn-icon pull-right" style="margin-top: -5px;">
                        {{trans('admin.search')}}
                        <i class="entypo-search"></i>
                    </button>
                </div>
                <div class="form-group col-sm-12" style="margin-top: -40px;" >
                    <h2>{{trans('admin.filter_parameters')}}</h2>
                    <select class="select2 filter_data" placeholder="{{trans('admin.filter')}}" multiple>
                        @foreach($columns as $key => $val)
                            <option value="{{$key}}">{{$val['text']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="filter-menu" hidden style="margin-bottom: 10px"></div>
        </form>
    </div>
    <div class="rcorners" style="margin-top: 10px; height: 120px">
        <div class="col-md-12" style="font-size: 16px">
             {{trans('admin.legend')}}
        </div>
        <div class="col-md-2" style="font-size: 16px">
            <i class="fa fa-user-secret" style="font-size: 25px; color: green"></i> {{trans('admin.president')}}
        </div>
        <div class="col-md-2" style="font-size: 16px">
            <i class="fa fa-edit" style="font-size: 25px; color: blue"></i> {{trans('admin.secretary')}}
        </div>
        <div class="col-md-2" style="font-size: 16px">
            <i class="fa fa-volume-up" style="font-size: 25px; color: #7F0055"></i> {{trans('admin.vocal_person')}}
        </div>
        <div class="col-md-2" style="font-size: 16px">
            <i class="fa fa-user" style="font-size: 25px; color: orange"></i> {{trans('admin.titular_person').' 1'}}
        </div>
        <div class="col-md-2" style="font-size: 16px">
            <i class="fa fa-user" style="font-size: 25px; color: orangered"></i> {{trans('admin.titular_person').' 2'}}
        </div>
        <div class="col-md-2" style="font-size: 16px">
            <i class="fa fa-user-md" style="font-size: 25px; color: #00a65a"></i> {{trans('admin.teller').' 1'}}
        </div>
        <div class="col-md-2" style="font-size: 16px; margin-top: 5px">
            <i class="fa fa-user-md" style="font-size: 25px; color: #00a65a"></i> {{trans('admin.teller').' 2'}}
        </div>
        <div class="col-md-2" style="font-size: 16px; margin-top: 5px">
            <i class="fa fa-user-times" style="font-size: 25px; color: #4A3600"></i> {{trans('admin.substitute').' 1'}}
        </div>
        <div class="col-md-2" style="font-size: 16px; margin-top: 5px">
            <i class="fa fa-user-times" style="font-size: 25px; color: #4a5262"></i> {{trans('admin.substitute').' 2'}}
        </div>
        <div class="col-md-2" style="font-size: 16px; margin-top: 5px">
            <i class="fa fa-user-times" style="font-size: 25px; color: #2b303a"></i> {{trans('admin.substitute').' 3'}}
        </div>

    </div>
    {{-- end filter--}}
    <div class="rcorners table-download" style="margin-top: 10px">
        <div class="icon" style="top: 15px; right: 20px;">
            @if(auth()->user()->get_Permission('BoxController','exportData'))
                <a href="#" data-type="xls" class="exportData" style="margin-left: 25px;" title="{{trans('admin.export_excel')}}"> <i class="fa fa-file-excel-o"></i></a>
                <a href="#" data-type="pdf" class="exportData" style="margin-left: 10px; color: red" title="{{trans('admin.export_csv')}}"> <i class="fa fa-file-pdf-o"></i></a>
            @endif
        </div>
        <table class="table table-bordered table-striped datatable" id="table-2" style="width: 100%">
            <thead>
            <tr>
                <th style="width: 15px">{{trans('admin.select')}}</th>
                <th title="{{trans('admin.box')}}">{{trans('admin.box')}}</th>
                <th title="{{trans('admin.president')}}"><i class="fa fa-user-secret" style="font-size: 25px; color: green"></i></th>
                <th title="{{trans('admin.secretary')}}"><i class="fa fa-edit" style="font-size: 25px; color: blue"></i></th>
                <th title="{{trans('admin.titular_person').' 1'}}"><i class="fa fa-user" style="font-size: 25px; color: orange"></i> </th>
                <th title="{{trans('admin.titular_person').' 2'}}"><i class="fa fa-user" style="font-size: 25px; color: orangered"></i></th>
                <th title="{{trans('admin.vocal_person')}}"><i class="fa fa-volume-up" style="font-size: 25px; color: #7F0055"></i></th>
                <th title="{{trans('admin.teller').' 1'}}">{<i class="fa fa-user-md" style="font-size: 25px; color: #00a65a"></i></th>
                <th title="{{trans('admin.teller').' 2'}}"><i class="fa fa-user-md" style="font-size: 25px; color: #00a65a"></i></th>
                <th title="{{trans('admin.substitute').' 1'}}"><i class="fa fa-user-times" style="font-size: 25px; color: #4A3600"></i> </th>
                <th title="{{trans('admin.substitute').' 2'}}"><i class="fa fa-user-times" style="font-size: 25px; color: #4a5262"></i></th>
                <th title="{{trans('admin.substitute').' 3'}}"><i class="fa fa-user-times" style="font-size: 25px; color: #2b303a"></i></th>
                <th style="width: 20px;">{{trans('admin.actions')}}</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- Person modal -->
    <div class="modal fade" id="ModalPerson">
        <div class="modal-dialog" role="document" style="width: 600px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="rcorners" style="height: 360px">
                        <div class="verticalLine">
                            <div class="row">
                                <label for="person_name" class="col-sm-3 control-label">{{trans('admin.name').'(s)'}}</label>
                                <div class="col-sm-9">
                                    <input type="text" id="person_name" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="birth_date" class="col-sm-3 control-label">{{trans('admin.birth_date')}}</label>
                                <div class='col-sm-9' >
                                    <input type='text' id="birth_date" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="person_sex" class="col-sm-3 control-label">{{trans('admin.person_sex')}}</label>
                                <div class='col-sm-9'>
                                    <input type='text' id="person_sex" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px " id="phone_input">
                                <label for="person_phone" class="col-sm-3 control-label">{{trans('admin.person_phone')}}</label>
                                <div class='col-sm-9'>
                                    <input type='text' id="person_phone" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px" id="cellphone_input">
                                <label for="person_cellphone" class="col-sm-3 control-label">{{trans('admin.person_cellphone')}}</label>
                                <div class='col-sm-9'>
                                    <input type='text' id="person_cellphone" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px" id="email_input">
                                <label for="person_email" class="col-sm-3 control-label">{{trans('admin.email')}}</label>
                                <div class='col-sm-9'>
                                    <input type='text' id="person_email" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="credential_date" class="col-sm-3 control-label">{{trans('admin.credential_date')}}</label>
                                <div class='col-sm-9 ' >
                                    <input type='text' id="credential_date"class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px" id="address_input">
                                <label for="person_address" class="col-sm-3 control-label">{{trans('admin.address')}}</label>
                                <div class='col-sm-9'>
                                    <textarea class="form-control" style="resize:none; height: 50px" readonly id="person_address"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close"
                            data-dismiss="modal">{{trans('admin.close')}}
                    </button>
            </div>
        </div>
    </div>
    <!-- Person modal end -->
@endsection

@section('script_down')
    <script type="text/javascript">
        function showRepresenting(id){
            var route = [id].reduce(_r, "{!! route('dashboard.assignRepresenting','%s') !!}");
            window.location.replace(route);
        }

        function showperson(id, type){
            var filter = {'id_persons': id};
            $.post("{{route('person.filter')}}",
                { "_token": "{{ csrf_token() }}", filter, 'autocomplete':true, 'allparams':false},
                function (data, status) {
                    var decoded_data = jQuery.parseJSON(data)[0];
                    $('h4.modal-title').text(type);
                    $('#person_name').val(decoded_data.full_name);
                    $('#birth_date').val(decoded_data.birth_date);
                    $('#person_sex').val(decoded_data.person_sex_text);
                    $('#person_phone').val(decoded_data.persons_phone);
                    $('#person_cellphone').val(decoded_data.persons_cellphone);
                    $('#person_email').val(decoded_data.persons_email);
                    $('#credential_date').val(decoded_data.credential_date);
                    $('#person_address').val(decoded_data.oficial_address);
                });
        }

        (function($){
            $(window).on('load',function(){
                var box_table = $("#table-2");

                // Initialize DataTable
                box_table.DataTable( {
                    "language":{
                        "url": "{{ asset('js/datatable/'.App::getLocale().'/datatable.json') }}"
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
                        "url": "{{route('representing_table.filter')}}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d){update_params(d)}
                    },
                    "columns": [
                        { "data": "selected" },
                        { "data": "box_name" },
                        { "data": "president_name" },
                        { "data": "secretary_name" },
                        { "data": "tp_1_name" },
                        { "data": "tp_2_name" },
                        { "data": "vp_name" },
                        { "data": "t_1_name" },
                        { "data": "t_2_name" },
                        { "data": "s_1_name" },
                        { "data": "s_2_name" },
                        { "data": "s_3_name" },
                        { "data": "options" }
                    ],
                    columnDefs: [ {
                        orderable: false,
                        className: 'select-checkbox',
                        targets:   0
                    },
                    {
                        "orderable": false,
                        "render": function ( data, type, row, meta ) {
                            if( data !== null){
                                id = null;
                                type = null;
                                switch (meta.col) {
                                    case 2: id = row.president; type = "'{{trans('admin.president')}}'"; break;
                                    case 3: id = row.secretary; type = "'{{trans('admin.secretary')}}'"; break;
                                    case 4: id = row.titular_person1; type = "'{{trans('admin.titular_person').' 1'}}'"; break;
                                    case 5: id = row.titular_person2; type = "'{{trans('admin.titular_person').' 2'}}'"; break;
                                    case 6: id = row.vocal_person; type = "'{{trans('admin.vocal_person')}}'"; break;
                                    case 7: id = row.teller1; type = "'{{trans('admin.teller').' 1'}}'"; break;
                                    case 8: id = row.teller2; type = "'{{trans('admin.teller').' 2'}}'"; break;
                                    case 9: id = row.substitute1; type = "'{{trans('admin.substitute').' 1'}}'"; break;
                                    case 10: id = row.substitute2; type = "'{{trans('admin.substitute').' 2'}}'"; break;
                                    case 11: id = row.substitute3; type = "'{{trans('admin.substitute').' 3'}}'"; break;
                                }
                                return '<div align="center" style="margin-top: 5px"><a href="#" data-toggle="modal" data-target="#ModalPerson" onclick="showperson('+id+','+type+')"><i class="fa fa-check" style="color: green; font-size: 18px" title="'+ data +'"></i></a></div>';
                            }
                            return '';
                        },
                        "targets": [2,3,4,5,6,7,8,9,10,11]
                    }],
                    select: {
                        style:    'multi',
                        selector: 'td:first-child'
                    },
                    //hasta aqui
                });

                box_table.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
                    minimumResultsForSearch: -1
                });
                // start filter part
                $('.filter_data').change(function(){
                    var value = $(this).val() == null? new Array(): $(this).val();
                    var columns = {!! json_encode($columns) !!};
                    update_filters(value,columns);
                });

                $( "#filter-form" ).submit(function( event ) {
                    event.preventDefault();
                    box_table.DataTable().ajax.reload();
                });

                $('.exportData').click(function(e){
                    e.preventDefault();
                    var its = $("#table-2").DataTable().rows('.selected').count() == 0? $("#table-2").DataTable().rows().data(): $("#table-2").DataTable().rows('.selected').data();

                    var items = new Array();
                    $.each(its, function( i, v ) {
                        items.push(v.id);
                    });
                    var type = $(this).data('type');
                    var url = '{{ route('box.exportData', ['type', 'items']) }}'.replace('type', type);
                    if (items.length > 0)
                        url = url.replace('items',JSON.stringify(items));
                    window.open(url, '_blank');
                });
                // end filter part
            });
        })(jQuery);
    </script>
@endsection
