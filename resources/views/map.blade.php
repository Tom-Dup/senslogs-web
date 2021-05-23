@extends('layouts/default')

@section('title')
    Map
    @parent
@stop

{{-- Page CSS --}}
@section('header_styles')
    <link rel="stylesheet" href="{{ asset("vendor/leaflet/leaflet.css") }}" />
@stop

{{-- Page content --}}
@section('content')
<div class="row">
    {{--<div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Device</th>
                    <th scope="col">Session</th>
                    <th scope="col">Date</th>
                    <th scope="col">Lat.</th>
                    <th scope="col">Long.</th>
                    <th scope="col">Alt.</th>
                    <th scope="col">Speed</th>
                    <th scope="col">Battery</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ $last_record->device_id }}</th>
                    <td>{{ $last_record->session_id }}</td>
                    <td>{{ $last_record->datetime() }}</td>
                    <td>{{ $last_record->latitude }}</td>
                    <td>{{ $last_record->longitude }}</td>
                    <td>{{ $last_record->altitude }}</td>
                    <td>{{ $last_record->speed }}</td>
                    <td>{{ $last_record->battery_temp }}° / {{ $last_record->battery_level }}%</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>--}}
    <div class="col-12" id="main_map" style="width: 100%; min-height: 450px;"></div>
</div>
@stop

{{-- Page JS --}}
@section('footer_scripts')
    <script src="{{ asset("vendor/leaflet/leaflet.js") }}"></script>
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
        });
    </script>
@stop
