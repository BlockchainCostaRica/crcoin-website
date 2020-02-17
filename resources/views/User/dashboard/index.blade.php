@extends('User.master',['menu'=>'dashboard'])
@section('title', $title)
@section('style')
@endsection
@section('content')

    <div class="dashbord-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="dashbord-inner ">
                        <div class="history-area">
                            <div class="row">
                                <div class="col-lg-4 col-md-12">
                                    <div class="single-history text-center">
                                        <div class="section-title">
                                            <h4>{{__('Withdrawal Status')}}</h4>
                                        </div>
                                        <div class="progress-circular">
                                              <div id="circle"></div>
                                            <p>{{__('Withdraw')}} :  <span>{{$completed_withdraw+$pending_withdraw}}</span>	</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-12">
                                    <div class="history-right">
                                        <div class="section-title">
                                            <h4>{{__('Last 6 month history')}}</h4>
                                        </div>
                                        <div id="container"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="withdrawal-shit">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="section-title">
                                        <h4>{{__('Deposit & Withdrawal')}}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="tabe-menu">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active tab_map" data-id="week-tab" id="week-tab"  href="javascript:" role="tab" aria-controls="week" aria-selected="true"> This Week</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link tab_map" data-id="month-tab" id="month-tab" href="javascript:" role="tab" aria-controls="month" aria-selected="false">  This month</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link tab_map" data-id="year-tab" id=""  href="javascript:" role="tab" aria-controls="year" aria-selected="false">  This Year</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade active in" id="week" role="tabpanel" aria-labelledby="week-tab">
                                            <div class="line-chart-area">
                                                <div class="dash-chart" >
                                                    <canvas id="canvas_week"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="week-tab">
                                            <div class="line-chart-area">
                                                <div class="dash-chart">
                                                    <canvas id="canvas_month"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="year" role="tabpanel" aria-labelledby="week-tab">
                                            <div class="line-chart-area">
                                                <div class="dash-chart">
                                                    <canvas id="canvas_year"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="deposite-list-area">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="activity-area">
                                        <div class="activity-top-area">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="section-title">
                                                        <h4 id="list_title">{{__('All Deposit List')}}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="tabe-menu">
                                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="deposit-tab" data-toggle="tab" onclick="$('#list_title').html('All Deposit List')" href="#deposit" role="tab" aria-controls="deposit" aria-selected="true"> Deposit</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link profile" id="withdraw-tab" data-toggle="tab" onclick="$('#list_title').html('All Withdrawal List')"  href="#withdraw" role="tab" aria-controls="withdraw" aria-selected="false">  Withdraw</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="activity-list">
                                                    <div class="tab-content">
                                                        <div id="deposit" class="tab-pane fade in active">

                                                            <div class="table-responsive">
                                                                <table class="table" id="diposite_table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>{{__('Address')}}</th>
                                                                        <th>{{__('Amount')}}</th>
                                                                        <th>{{__('Transaction Hash')}}</th>
                                                                        <th>{{__('Status')}}</th>
                                                                        <th>{{__('Created At')}}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div id="withdraw" class="tab-pane fade in ">

                                                            <div class="table-responsive">
                                                                <table class="table" id="withdraw_table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>{{__('Address')}}</th>
                                                                        <th>{{__('Amount')}}</th>
                                                                        <th>{{__('Transaction Hash')}}</th>
                                                                        <th>{{__('Status')}}</th>
                                                                        <th>{{__('Created A')}}t</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/chart/chart.min.js')}}"></script>
    <script src="{{asset('assets/chart/anychart-base.min.js')}}"></script>
    <!-- Resources -->
    <script src="{{asset('assets/chart/amchart.core.js')}}"></script>
    <script src="{{asset('assets/chart/amchart.charts.js')}}"></script>
    <script src="{{asset('assets/chart/amchart.animated.js')}}"></script>
    <script>
        anychart.onDocumentReady(function () {
            var chart = anychart.pie([
                {x: "Complete", value: {!! $completed_withdraw !!}},
                {x: "Pending", value: {!! $pending_withdraw !!}},

            ]);

            chart.innerRadius("60%");

            var label = anychart.standalones.label();
            label.text({!! json_encode($pending_withdraw) !!});
            label.width("100%");
            label.height("100%");
            label.adjustFontSize(true);
            label.fontColor("#60727b");
            label.hAlign("center");
            label.vAlign("middle");

            // set the label as the center content
            chart.center().content(label);

          //  chart.title("Donut Chart: Label in the center");
            chart.container('circle');
            chart.draw();
        });
    </script>
    <script>
        am4core.ready(function() {

// Themes begin
            am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
            var chart = am4core.create("container", am4charts.XYChart);

// Add percent sign to all numbers
            //chart.numberFormatter.numberFormat = "#.3";

// Add data
            chart.data = {!! json_encode($sixmonth_diposites) !!};

// Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "country";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = "Deposit and withdraw ";
            valueAxis.title.fontWeight = 800;

// Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "year2004";
            series.dataFields.categoryX = "country";
            series.clustered = false;
            series.tooltipText = "Deposit {categoryX}: [bold]{valueY}[/]";

            var series2 = chart.series.push(new am4charts.ColumnSeries());
            series2.dataFields.valueY = "year2005";
            series2.dataFields.categoryX = "country";
            series2.clustered = false;
            series2.columns.template.width = am4core.percent(50);
            series2.tooltipText = "Withdraw {categoryX}: [bold]{valueY}[/]";

            chart.cursor = new am4charts.XYCursor();
            chart.cursor.lineX.disabled = true;
            chart.cursor.lineY.disabled = true;

        }); // end am4core.ready()
    </script>

    <script>
        $(document.body).on('click','.tab_map',function () {
            $('.tab_map').removeClass('active');
            $(this).addClass('active');
            var str = $(this).data('id');
            loadChart(str);
        });
        function loadChart(str) {
            $.ajax({
                url: "{{route('UserDashboard')}}?t="+str,
                cache: false,
                success: function(data){

                    var config = {
                        type: 'line',
                        data: {
                            labels: data.label,
                            datasets: [{
                                label: 'Deposit',
                                backgroundColor: window.chartColors.red,
                                borderColor: window.chartColors.red,
                                data:data.Deposite,
                                fill: false,
                            }, {
                                label: 'Withdraw',
                                fill: false,
                                backgroundColor: window.chartColors.blue,
                                borderColor: window.chartColors.blue,
                                data: data.Withdraw,
                            }]
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: ''
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        // the data minimum used for determining the ticks is Math.min(dataMin, suggestedMin)
                                        suggestedMin: 10,

                                        // the data maximum used for determining the ticks is Math.max(dataMax, suggestedMax)
                                        suggestedMax: 30
                                    }
                                }]
                            }
                        }
                    };
                    var ctx = document.getElementById('canvas_week').getContext('2d');
                    window.myLine = new Chart(ctx, config);
                }
            });
        }
        $(window).on('load', function() {
            var str = 'week-tab';
            loadChart(str);
        });

    </script>

    <script>
        $(document).ready(function() {
            $('#diposite_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                bLengthChange: true,
                responsive: true,
                ajax: '{{route('transactionHistories')}}?type=deposit',
                order: [4, 'desc'],
                autoWidth: false,
                language: {
                    paginate: {
                        next: 'Next &#8250;',
                        previous: '&#8249; Previous'
                    }
                },
                columns: [
                    {"data": "address","orderable": false},
                    {"data": "amount","orderable": false},
                    {"data": "hashKey","orderable": false},
                    {"data": "status","orderable": false},
                    {"data": "created_at","orderable": false}
                ],
            });
            $('#withdraw_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                bLengthChange: true,
                responsive: true,
                ajax: '{{route('transactionHistories')}}?type=withdraw',
                order: [4, 'desc'],
                autoWidth: false,
                language: {
                    paginate: {
                        next: 'Next &#8250;',
                        previous: '&#8249; Previous'
                    }
                },
                columns: [
                    {"data": "address","orderable": false},
                    {"data": "amount","orderable": false},
                    {"data": "hashKey","orderable": false},
                    {"data": "status","orderable": false},
                    {"data": "created_at","orderable": false}
                ],
            });
        });
    </script>

@endsection