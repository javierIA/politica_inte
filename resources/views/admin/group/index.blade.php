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
            @if(auth()->user()->get_Permission('GroupController','importData'))
                <a href="#" data-href="{{ route('group.importData') }}" class="importData" data-toggle="modal" data-target="#myModalImportData" style="color: grey" title="{{trans('admin.importData')}}"> <i class="fa fa-upload"></i></a>
            @endif
            @if(auth()->user()->get_Permission('GroupController','exportData'))
                <a href="#" data-type="xls" class="exportData" style="margin-left: 25px;" title="{{trans('admin.export_excel')}}"> <i class="fa fa-file-excel-o"></i></a>
                <a href="#" data-type="pdf" class="exportData" style="margin-left: 10px; color: red" title="{{trans('admin.export_csv')}}"> <i class="fa fa-file-pdf-o"></i></a>
            @endif
        </div>
        <table class="table table-bordered table-striped datatable" id="table-2" style="width: 100%">
        <thead>
        <tr>
            <th style="width: 15px">{{trans('admin.select')}}</th>
            <th>{{trans('admin.group_name')}}</th>
            <th>{{trans('admin.description')}}</th>
            <th>{{trans('admin.default')}}</th>
            <th style="width: 20%;">{{trans('admin.actions')}}</th>
        </tr>
        </thead>
    </table>
    </div>
    <br/>
    @if(auth()->user()->get_Permission('GroupController','create'))
        <a href="{{ route('group.create') }}" class="btn btn-primary btn-sm" title="{{trans('admin.add_group')}}">
            <i class="entypo-plus"></i>
        </a>
    @endif
@endsection

@section('script_down')
    <script type="text/javascript">

        (function($){
            $(window).on('load',function(){

                var group_table = $("#table-2");

                // Initialize DataTable
                group_table.DataTable( {
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
                        "url": "{{route('group.filter')}}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d){update_params(d)}
                    },
                    "columns": [
                        { "data": "selected" },
                        { "data": "group_name" },
                        { "data": "description" },
                        { "data": "default" },
                        { "data": "options" }
                    ],

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

                group_table.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
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
                    group_table.DataTable().ajax.reload();
                });

                $('.exportData').click(function(e){
                    e.preventDefault();
                    var its = $("#table-2").DataTable().rows('.selected').count() == 0? $("#table-2").DataTable().rows().data(): $("#table-2").DataTable().rows('.selected').data();
                    var items = new Array();
                    $.each(its, function( i, v ) {
                        items.push(v.id);
                    });
                    var type = $(this).data('type');
                    var url = '{{ route('group.exportData', ['type', 'items']) }}'.replace('type', type);
                    if (items.length > 0)
                        url = url.replace('items',JSON.stringify(items));
                    window.open(url, '_blank');
                });
                // end filter part
            });

        })(jQuery);
    </script>
@endsection
