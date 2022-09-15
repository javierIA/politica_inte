@extends('admin.layout')

@section('breadcrumb')
    <li>
        <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i>{{ trans('admin.home') }}</a>
    </li>
    <li class="active">
        <strong>{{$title}}</strong>
    </li>
@endsection

@section('content')
    <div class="rcorners table-download" style="margin-top: 10px">
        <div class="icon" style="top: 15px; right: 20px;">
            @if(auth()->user()->get_Permission('SettingController','exportData'))
                <a href="{{ route('setting.exportData', 'xls') }}" class="exportData" style="margin-left: 25px;" title="{{trans('admin.export_excel')}}"> <i class="fa fa-file-excel-o"></i></a>
                <a href="{{ route('setting.exportData', 'pdf') }}" class="exportData" style="margin-left: 10px; color: red" title="{{trans('admin.export_csv')}}"> <i class="fa fa-file-pdf-o"></i></a>
            @endif
        </div>
        <table class="table table-bordered table-striped datatable" id="table-2" style="width: 100%">
            <thead>
            <tr>
                <th>{{ trans('admin.address') }}</th>
                <th>{{ trans('admin.email') }}</th>
                <th>{{ trans('admin.phone') }}</th>
                <th>Fax</th>
                <th>{{ trans('admin.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($repo as $item)
                <tr>
                    <td>{{ $item->address }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->fax }}</td>
                    <td style="width: 15%;">
                        @if(auth()->user()->get_Permission('SettingController','edit'))
                            <div class="col-sm-4">
                                <a href="{{ route('setting.edit', $item->id) }}" class="btn btn-default btn-sm" title="{{trans('admin.edit_setting')}}">
                                    <i class="entypo-pencil"></i>
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script_down')
    <script type="text/javascript">
        (function($){
            $(window).on('load',function(){
                var $table2 = $("#table-2");

                // Initialize DataTable
                $table2.DataTable( {
                    "language":{
                        "url": "{{ asset('js/datatable/'.App::getLocale().'/datatable.lang') }}"
                    },
                    "searching": false,
                    "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                    "stateSave": true,
                    "displayLength": 10,
                    "pageLength": 10,
                    "columnDefs ": [
                        { "targets": [0], "orderable": false},
                        null,
                        null,
                        null,
                        { "targets": [0], "orderable": false}
                    ],
                });

                $table2.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
                    minimumResultsForSearch: -1
                });
            });
        })(jQuery);
    </script>
@endsection
