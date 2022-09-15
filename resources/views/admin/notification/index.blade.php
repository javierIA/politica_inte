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
    {{-- end filter--}}
    <div class="rcorners table-download" style="margin-top: 10px">
        <div class="icon" style="top: 15px; right: 20px;">
            @if(auth()->user()->get_Permission('NotificationController','exportData'))
                <a href="#" data-type="xls" class="exportData" style="margin-left: 25px;" title="{{trans('admin.export_excel')}}"> <i class="fa fa-file-excel-o"></i></a>
                <a href="#" data-type="pdf" class="exportData" style="margin-left: 10px; color: red" title="{{trans('admin.export_csv')}}"> <i class="fa fa-file-pdf-o"></i></a>
            @endif
        </div>
        <table class="table table-bordered table-striped datatable" id="table-2" style="width: 100%">
            <thead>
            <tr>
                <th style="width: 15px">{{trans('admin.select')}}</th>
                <th>{{trans('admin.date')}}</th>
                <th>{{trans('admin.time')}}</th>
                <th>{{trans('admin.from')}}</th>
                <th>{{trans('admin.importance')}}</th>
                <th>{{trans('admin.message')}}</th>
                <th style="width: 20%;">{{trans('admin.actions')}}</th>
            </tr>
            </thead>
        </table>
    </div>

    <!-- Notification modal -->
    <div class="modal fade" id="ModalNotification">
        <div class="modal-dialog" role="document" style="width: 600px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{trans('admin.notification')}}</h4>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="rcorners" style="height: 300px">
                        <div class="verticalLine">
                            <div class="row">
                                <label for="date" class="col-sm-3 control-label">{{trans('admin.date')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" id="date" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="time" class="col-sm-3 control-label">{{trans('admin.time')}}</label>
                                <div class='col-sm-9' >
                                    <input type='text' id="time" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="importance" class="col-sm-3 control-label">{{trans('admin.importance')}}</label>
                                <div class='col-sm-9'>
                                    <input type='text' id="importance" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px ">
                                <label for="user_to" class="col-sm-3 control-label">{{trans('admin.to')}}</label>
                                <div class='col-sm-9'>
                                    <input type='text' id="user_to" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="user_from" class="col-sm-3 control-label">{{trans('admin.from')}}</label>
                                <div class='col-sm-9'>
                                    <input type='text' id="user_from" class="form-control" value="" readonly autofocus/>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="message" class="col-sm-3 control-label">{{trans('admin.message')}}</label>
                                <div class='col-sm-9'>
                                    <textarea class="form-control" style="resize:none; height: 80px" readonly id="message"></textarea>
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
    <!-- Notification modal end -->
@endsection

@section('script_down')
    <script type="text/javascript">
        function acceptNotification(id){
            var route = [id].reduce(_r, "{!! route('notification.acceptNotification','%s') !!}");

            $.post(route,
                { "_token": "{{ csrf_token() }}"},
                function (data, status) {
                    var decoded_data = jQuery.parseJSON(data);
                    var message = decoded_data == false? "{{trans('admin.error_accepting_notification')}}":(decoded_data == true? "{{trans('admin.accepted_notification')}}": decoded_data);
                    var type = decoded_data == false? 'error':(decoded_data == true? 'success': 'warn');
                    $.notify(message, type);
                    if(type == 'success')
                        $("#table-2").DataTable().ajax.reload();
                });
        }

        function seeNotification(id){
            var filter = {'id_notifications': id};
            $.post("{{route('notification.filter')}}",
                { "_token": "{{ csrf_token() }}", filter, 'autocomplete':true, 'allparams':false},
                function (data, status) {
                    var decoded_data = jQuery.parseJSON(data)[0];
                    var color = decoded_data.type == 1? "#efc2be": (decoded_data.type == 2? "#f9f8ba": "#c0e7c4");
                    $('.modal-header').css('background',color);
                    $('#date').val(decoded_data.date);
                    $('#time').val(decoded_data.time);
                    $('#importance').val(decoded_data.importance);
                    $('#user_to').val(decoded_data.user_to);
                    $('#user_from').val(decoded_data.user_from);
                    $('#message').val(decoded_data.message);
                });
        }

        (function($){
            $(window).on('load',function(){
                var notification_table = $("#table-2");
                var obj = {'id_user_to':"{{auth()->user()->id}}"};

                // Initialize DataTable
                notification_table.DataTable( {
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
                        "url": "{{route('notification.filter')}}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d){update_params(d,'filter-form',obj)}
                    },
                    "columns": [
                        { "data": "selected" },
                        { "data": "date" },
                        { "data": "time" },
                        { "data": "user_from" },
                        { "data": "importance" },
                        { "data": "message" },
                        { "data": "options" }
                    ],
                    "createdRow": function( row, data, dataIndex){
                        switch(data['type']){
                            case 1: if(data['acepted_time'] == null) $(row).css({"background-color":"#efc2be"})
                                break;
                            case 2: if(data['acepted_time'] == null) $(row).css({"background-color":"#f9f8ba"})
                                break;
                            case 3: if(data['acepted_time'] == null) $(row).css({"background-color": "#c0e7c4"})
                                break;
                        }
                    },
                    columnDefs: [ {
                        orderable: false,
                        className: 'select-checkbox',
                        targets:   0
                    } ],
                    select: {
                        style:    'multi',
                        selector: 'td:first-child'
                    },
                    //hasta aqui
                });

                notification_table.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
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
                    notification_table.DataTable().ajax.reload();
                });

                $('.exportData').click(function(e){
                    e.preventDefault();
                    var its = $("#table-2").DataTable().rows('.selected').count() == 0? $("#table-2").DataTable().rows().data(): $("#table-2").DataTable().rows('.selected').data();

                    var items = new Array();
                    $.each(its, function( i, v ) {
                        items.push(v.id);
                    });
                    var type = $(this).data('type');
                    var url = '{{ route('notification.exportData', ['type', 'items']) }}'.replace('type', type);
                    if (items.length > 0)
                        url = url.replace('items',JSON.stringify(items));
                    window.open(url, '_blank');
                });
                // end filter part
            });
        })(jQuery);
    </script>
@endsection
