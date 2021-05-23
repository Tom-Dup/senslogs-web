<?php /* @var \App\Models\Session $session */ ?>
<?php /* @var \App\Models\Device $device */ ?>
<?php /* @var \App\Models\Record $last_record */ ?>
@extends('layouts/default')

@section('title')
    Overview
    @parent
@stop

{{-- Page CSS --}}
@section('header_styles')
    <link rel="stylesheet" href="{{ asset("vendor/leaflet/leaflet.css") }}" />
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-xl-9 col-xxl-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-md-flex d-block pb-0 border-0">
                            <div class="mr-2 mb-md-0 mb-3">
                                <h4 class="text-black fs-20">Map</h4>
                            </div>
                            <div class="d-flex flex-wrap align-items-center">
                                &nbsp;
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mapouter">
                                <div class="gmap_canvas" id="main_map" style="min-height: 350px;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-sm-flex d-block pb-0 border-0">
                            <div class="mr-auto pr-3">
                                <h4 class="text-black fs-20">Charts</h4>
                            </div>
                            <div class="card-action card-tabs style-1 mt-2 mb-sm-0 mb-3 mt-sm-0">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#Running" role="tab">
                                            Altitude
                                            <span class="bg-secondary"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#Cycling" role="tab">
                                            Speed
                                            <span class="bg-danger"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="Running" role="tabpanel">
                                    <canvas id="chartAltitude"></canvas>
                                </div>
                                <div class="tab-pane fade" id="Cycling" role="tabpanel">
                                    <canvas id="chartSpeed"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4">
            <div class="row">
                <div class="col-xl-12 col-md-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0 p-4">
                            <div>
                                <h4 class="text-black fs-20">Data</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap p-4">
                                <div class="mr-5 mb-3">
                                    <p class="fs-14 mb-2">Total Distance</p>
                                    <span class="fs-20 font-w500 text-black">{{$session->distance()}} km</span>
                                </div>
                                <div class="mr-5 mb-3">
                                    <p class="fs-14 mb-2">Duration</p>
                                    <span class="fs-20 font-w500 text-black">{{$session->duration()}} mins</span>
                                </div>
                                <div class="mr-5 mb-3">
                                    <p class="fs-14 mb-2">Start Time</p>
                                    <span class="fs-20 font-w500 text-black">{{$session->startDate()->format("H:i:s")}}</span>
                                </div>
                                <div class="mr-5 mb-3">
                                    <p class="fs-14 mb-2">End Time</p>
                                    <span class="fs-20 font-w500 text-black">{{$session->endDate()->format("H:i:s")}}</span>
                                </div>
                                <div class="mr-5 mb-3">
                                    <p class="fs-14 mb-2">Last Position</p>
                                    <span class="fs-20 font-w500 text-black">
                                        {{$last_record->latitude}}, {{$last_record->longitude}}
                                    </span>
                                </div>
                                <div class="mr-5 mb-3">
                                    <p class="fs-14 mb-2">Battery</p>
                                    <span class="fs-20 font-w500 text-black">
                                        {{$last_record->battery_level}}% / {{$last_record->battery_temp}}°
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-6">
                    <div class="card">
                        <div class="card-header d-sm-flex d-block border-0">
                            <div class="mr-auto pr-3">
                                <h4 class="text-black fs-20">Files</h4>
                            </div>
                        </div>
                        <div class="card-body loadmore-content height340 dz-scroll height pb-4 pt-0" id="recentActivitiesContent">
                            WIP
                        </div>
                        <div class="card-footer style-1 text-center border-0 pt-0 pb-4">
                            <a class="text-primary dz-load-more fa fa-chevron-down" id="recentActivities" href="javascript:void(0);" rel="ajax/recent-activities.html">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- Page JS --}}
@section('footer_scripts')
    <script src="{{ asset("vendor/peity/jquery.peity.min.js") }}"></script>
    <script src="{{ asset("vendor/leaflet/leaflet.js") }}"></script>
    <script src="{{ asset("vendor/chart.js/Chart.bundle.min.js") }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var main_map = L.map('main_map').setView([{{ $last_record->latitude }}, {{ $last_record->longitude }}], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(main_map);
            L.marker([{!! $last_record->latitude !!}, {!! $last_record->longitude !!}]).addTo(main_map);
            var latlngs = [
                    @foreach ($points as $point)
                [{{$point['latitude']}}, {{$point['longitude']}}],
                    @endforeach
                [{{$last_record->latitude}}, {{$last_record->longitude}}]
            ];
            var polyline = L.polyline(latlngs, {color: 'red'}).addTo(main_map);
            main_map.fitBounds(polyline.getBounds());

            let draw = Chart.controllers.line.__super__.draw; //draw shadow

            var screenWidth = $(window).width();

            if(jQuery('#chartAltitude').length > 0 ){
                const lineChart_1 = document.getElementById("chartAltitude").getContext('2d');
                Chart.controllers.line = Chart.controllers.line.extend({
                    draw: function () {
                        draw.apply(this, arguments);
                        let nk = this.chart.chart.ctx;
                        let _stroke = nk.stroke;
                        nk.stroke = function () {
                            nk.save();
                            nk.shadowColor = 'rgba(255, 0, 0, .2)';
                            nk.shadowBlur = 10;
                            nk.shadowOffsetX = 0;
                            nk.shadowOffsetY = 10;
                            _stroke.apply(this, arguments)
                            nk.restore();
                        }
                    }
                });

                lineChart_1.height = 150;

                new Chart(lineChart_1, {
                    type: 'line',
                    data: {
                        defaultFontFamily: 'Poppins',
                        labels: {!! $altitudes['keys'] !!},
                        datasets: [
                            {
                                label: "Altitude",
                                data: {!! $altitudes['values'] !!},
                                borderColor: 'rgba(11, 42, 151, 1)',
                                borderWidth: "2",
                                backgroundColor: 'transparent',
                                pointBackgroundColor: 'rgba(11, 42, 151, 1)'
                            }
                        ]
                    },
                    options: {
                        legend: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: {!! $altitudes['max'] !!},
                                    min: {!! $altitudes['min'] !!}
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    padding: 5
                                }
                            }]
                        }
                    }
                });
            }

            if(jQuery('#chartSpeed').length > 0 ){
                const lineChart_1 = document.getElementById("chartSpeed").getContext('2d');
                Chart.controllers.line = Chart.controllers.line.extend({
                    draw: function () {
                        draw.apply(this, arguments);
                        let nk = this.chart.chart.ctx;
                        let _stroke = nk.stroke;
                        nk.stroke = function () {
                            nk.save();
                            nk.shadowColor = 'rgba(255, 0, 0, .2)';
                            nk.shadowBlur = 10;
                            nk.shadowOffsetX = 0;
                            nk.shadowOffsetY = 10;
                            _stroke.apply(this, arguments)
                            nk.restore();
                        }
                    }
                });

                lineChart_1.height = 150;

                new Chart(lineChart_1, {
                    type: 'line',
                    data: {
                        defaultFontFamily: 'Poppins',
                        labels: {!! $speed['keys'] !!},
                        datasets: [
                            {
                                label: "Altitude",
                                data: {!! $speed['values'] !!},
                                borderColor: 'rgba(11, 42, 151, 1)',
                                borderWidth: "2",
                                backgroundColor: 'transparent',
                                pointBackgroundColor: 'rgba(11, 42, 151, 1)'
                            }
                        ]
                    },
                    options: {
                        legend: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: {!! $speed['max'] !!},
                                    min: {!! $speed['min'] !!}
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    padding: 5
                                }
                            }]
                        }
                    }
                });
            }
        });
    </script>
@stop
