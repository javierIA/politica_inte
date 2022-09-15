@extends('admin.layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(!empty(auth()->user()->person()->first()) && auth()->user()->get_Permission('PersonController','myData'))
                <a href="{{ route('person.mydata', auth()->user()->person()->first()->id) }}">
                    <div class="col-md-3 col-sm-9" style="text-align: center">
                        <div class="background_button">
                            <img src="{{ asset('images/political/elements/orange_buton.png') }}">
                            <div>
                                <i class="fa fa-users"></i>
                            </div>
                            <h3>{{trans('admin.my_data')}}</h3>
                        </div>
                    </div>
                </a>
            @endif
            @if(auth()->user()->get_Permission('DashBoardController','managePerson'))
                <a href="{{ route('dashboard.managePerson') }}">
                    <div class="col-md-3 col-sm-9" style="text-align: center">
                        <div class="background_button">
                            <img src="{{ asset('images/political/elements/blue_buton.png') }}">
                            <div>
                                <i class="fa fa-users"></i>
                            </div>
                            <h3>{{trans('admin.managePerson')}}</h3>
                        </div>
                    </div>
                </a>
            @endif
            @if(auth()->user()->get_Permission('DashBoardController','assignRepresentingTable'))
                <a href="{{ route('dashboard.assignRepresentingTable') }}">
                    <div class="col-md-3 col-sm-9" style="text-align: center">
                        <div class="background_button">
                            <img src="{{ asset('images/political/elements/orange_buton.png') }}">
                            <div>
                                <i class="fa fa-table"></i>
                            </div>
                            <h3>{{trans('admin.assignRepresentingTable')}}</h3>
                        </div>
                    </div>
                </a>
            @endif
                {{--
            <a href="{{ route('dashboard.managePerson') }}">
                <div class="col-md-3 col-sm-9" style="text-align: center">
                    <div class="background_button">
                        <img src="{{ asset('images/political/elements/grey_buton.png') }}">
                        <div>
                            <i class="fa fa-users"></i>
                        </div>
                        <h3>{{trans('admin.managePerson')}}</h3>
                    </div>
                </div>
            </a>--}}
        </div>

        <div style="padding-right: 10px; padding-left: 10px">
            <div class="row">
                <div class="col-md-8">
                    <canvas id="myChart" height="140"></canvas>
                </div>
                <div class="col-md-4" style="margin-top: 50px">
                    <canvas id="chart-area"></canvas>
                </div>
            </div>
        </div>

    </div>

    <br />
@endsection

@section('script_down')
    <script type="text/javascript">
        var ctx = $('#myChart');
        var myChart = null;
        function updatechart(keys,values){
            if(myChart != null)
                myChart.destroy();

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: keys,
                    datasets: [{
                        label: {!! json_encode(trans('admin.quantity')) !!},
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }

        var keys = {!! json_encode($graphic_data['chart_keys']) !!};
        var values = {!! json_encode($graphic_data['chart_values']) !!};
        updatechart(keys, values);

        function updatedoughnut(data, color, labels){
            var config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: data,
                        backgroundColor: color,
                    }],
                    labels: labels
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: '{!! trans('admin.validated_info') !!}'
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            };

            if(myDoughnut != null)
                myDoughnut.destroy();
            myDoughnut = new Chart(ctx, config);
        }

        var ctx = $('#chart-area');
        var myDoughnut = null;
        updatedoughnut({!! json_encode($graphic_data['doughnut_data']) !!}, {!! json_encode($graphic_data['doughnut_color']) !!}, {!! json_encode($graphic_data['doughnut_labels']) !!});
    </script>
@endsection
