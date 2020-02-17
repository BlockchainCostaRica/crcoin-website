@extends('Admin.master',['menu'=>'dashboard'])
@section('title', 'Dashboard')
@section('style')
@endsection
@section('content')
    <div class="dashbord-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page active" >{{__('Dashboard')}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-inner bg-transparent">
                    <div class="card-lsit">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="single-card card-one">
                                    <div class="card-cotnetn">
                                        <h4>{{__('Company Profit')}}</h4>
                                        <h2>{!! clean('$'.number_format($total_doller,2)) !!}</h2>
                                    </div>
                                    <div class="icon">
                                        <img src="{{asset('landing-page')}}/assets/images/dashbord/1.png" alt="mony">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="single-card card-three">
                                    <div class="card-cotnetn">
                                        <h4>{{__('Total Coin Sale')}}</h4>
                                        <h2>{!! clean(number_format($total_coin,2)) !!}</h2>

                                    </div>
                                    <div class="icon">
                                        <img src="{{asset('landing-page')}}/assets/images/dashbord/2.png" alt="mony">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="single-card card-two">
                                    <div class="card-cotnetn">
                                        <h4>{{__('Total User')}}</h4>
                                        <h2>{!! clean($users) !!}</h2>
                                    </div>
                                    <div class="icon">
                                        <img src="{{asset('landing-page')}}/assets/images/dashbord/3.png" alt="mony">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-details">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="single-history ">
                                    <div class="section-title">
                                        <h4>{{__('User')}}</h4>
                                    </div>
                                    <div class="user-chart">
                                        <p class="subtitle">{{__('Current Year')}}</p>
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-details">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="single-history ">
                                    <div class="section-title">
                                        <h4>{{__('Active user')}}</h4>
                                    </div>
                                    <div class="progress-circular">
                                        <input type="text" class="knob" value="0" data-rel="{{($active_users*100)/$users}}" data-bgcolor="#E9F7FF" data-fgcolor="#5986F0" data-thickness=".3" data-readonly="true" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="single-history mb-0">
                                    <div class="section-title">
                                        <h4>{{__('Deleted user')}}</h4>
                                    </div>
                                    <div class="progress-circular">
                                        <input type="text" class="knob" value="0" data-rel="{{($deleted_users*100)/$users}}" data-bgcolor="#FEF5DD" data-fgcolor="#ED720F" data-thickness=".3" data-readonly="true" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-details">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="single-history ">
                                    <div class="section-title">
                                        <h4>{{__('Deposit')}}</h4>
                                    </div>
                                    <div class="user-chart">
                                        <p class="subtitle">{{__('Current Year')}}</p>
                                        <canvas id="depositChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="user-details">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="single-history ">
                                    <div class="section-title">
                                        <h4>{{__('Withdrawal')}}</h4>
                                    </div>
                                    <div class="user-chart">
                                        <p class="subtitle">{{__('Current Year')}}</p>
                                        <canvas id="withdrawalChart"></canvas>
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
    <script>
        $(document.body).on('click','.tab_map',function () {
            $('.tab_map').removeClass('active');
            $(this).addClass('active');
            var str = $(this).data('id');
            loadChart(str);
        });
        function loadChart(str) {
            $.ajax({
                url: "{{route('AdminDashboard')}}?t="+str,
                cache: false,
                success: function(data){

                    console.log(data.label);
                    var config = {
                        type: 'line',
                        data: {
                            labels: data.label,
                            datasets: [{
                                label: 'Deposit',
                                backgroundColor: 'blue',
                                borderColor: 'red',
                                data:data.Deposite,
                                fill: false,
                            }, {
                                label: 'Withdraw',
                                fill: false,
                                backgroundColor: 'blue',
                                borderColor: 'red',
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

    <script src="{{asset('assets/chart/revenue-chart.js')}}"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext("2d")
        var myChart = new Chart(ctx, {
            type: 'line',
            yaxisname: "Monthly Register User",

            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Register User",
                    borderColor: "#e3a62d",
                    pointBorderColor: "transparent",
                    pointBackgroundColor: "transparent",
                    pointHoverBackgroundColor: "#e3a62d",
                    pointHoverBorderColor: "#D1D1D1",
                    pointBorderWidth: 0,
                    pointHoverRadius: 2,
                    pointHoverBorderWidth: 1,
                    pointRadius: 3,
                    fill: false,
                    borderWidth: 4,
                    data: {!! json_encode($monthly_user) !!}
                }]
            },
            options: {
                legend: {
                    position: "bottom",
                    display: true,
                    labels: {
                        fontColor: '#928F8F'
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor: "#928F8F",
                            fontStyle: "bold",
                            beginAtZero: true,
                            // maxTicksLimit: 5,
                            padding: 20
                        },
                        gridLines: {
                            drawTicks: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#928F8F",
                            fontStyle: "bold"
                        }
                    }]
                }
            }
        });
    </script>
    <script>
        var ctx = document.getElementById('depositChart').getContext("2d")
        var depositChart = new Chart(ctx, {
            type: 'line',
            yaxisname: "Monthly Deposit",

            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Monthly Deposit",
                    borderColor: "#1cf676",
                    pointBorderColor: "#1cf676",
                    pointBackgroundColor: "#1cf676",
                    pointHoverBackgroundColor: "#1cf676",
                    pointHoverBorderColor: "#D1D1D1",
                    pointBorderWidth: 4,
                    pointHoverRadius: 2,
                    pointHoverBorderWidth: 1,
                    pointRadius: 3,
                    fill: false,
                    borderWidth: 3,
                    data: {!! json_encode($monthly_deposit) !!}
                }]
            },
            options: {
                legend: {
                    position: "bottom",
                    display: true,
                    labels: {
                        fontColor: '#928F8F'
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor: "#928F8F",
                            fontStyle: "bold",
                            beginAtZero: true,
                            // maxTicksLimit: 5,
                            padding: 20
                        },
                        gridLines: {
                            drawTicks: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#928F8F",
                            fontStyle: "bold"
                        }
                    }]
                }
            }
        });
    </script>
    <script>
        var ctx = document.getElementById('withdrawalChart').getContext("2d");
        var withdrawalChart = new Chart(ctx, {
            type: 'line',
            yaxisname: "Monthly Withdrawal",

            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Monthly Withdrawal",
                    borderColor: "#f691be",
                    pointBorderColor: "#f691be",
                    pointBackgroundColor: "#f691be",
                    pointHoverBackgroundColor: "#f691be",
                    pointHoverBorderColor: "#D1D1D1",
                    pointBorderWidth: 4,
                    pointHoverRadius: 2,
                    pointHoverBorderWidth: 1,
                    pointRadius: 3,
                    fill: false,
                    borderWidth: 3,
                    data: {!! json_encode($monthly_withdrawal) !!}
                }]
            },
            options: {
                legend: {
                    position: "bottom",
                    display: true,
                    labels: {
                        fontColor: '#928F8F'
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor: "#928F8F",
                            fontStyle: "bold",
                            beginAtZero: false,
                            // maxTicksLimit: 5,
                            // padding: 20,
                            // max: 1000
                        },
                        gridLines: {
                            drawTicks: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: true,
                            display: false
                        },
                        ticks: {
                            // padding: 20,
                            fontColor: "#928F8F",
                            fontStyle: "bold",
                            // max: 10000,
                            autoSkip: false
                        }
                    }]
                }
            }
        });
    </script>
@endsection