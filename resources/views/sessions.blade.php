@extends('layouts/default')

@section('title')
    Sessions
    @parent
@stop

{{-- Page CSS --}}
@section('header_styles')

@stop

{{-- Page content --}}
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            @if(empty($session))
            <div class="card-header">
                <h4 class="card-title">Please select a session</h4>
            </div>
            @endif
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped verticle-middle table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Device</th>
                            <th scope="col">Name</th>
                            <th scope="col">Start</th>
                            <th scope="col">End</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($sessions as $session)
                            <tr>
                                <td>
                                    <a
                                        href="{{route("dashboard")}}?select={{$session->session_id}}" data-toggle="tooltip"
                                        data-placement="top" title="select">
                                        {{$session->session_id}}
                                    </a>
                                </td>
                                <td>
                                    @if (isset($devices[$session->device_id]))
                                        {{$devices[$session->device_id]}}
                                    @else
                                        {{$session->device_id}}
                                    @endif
                                </td>
                                <td>
                                    {{$session->name}}
                                    <span><a href="javascript:void()" class="mr-4" data-toggle="tooltip"
                                     data-placement="top" title="(re)name"><i
                                        class="fa fa-pencil color-muted"></i> </a></span>
                                </td>
                                <td>{{$session->startDate()}}</td>
                                <td>{{$session->endDate()}}</td>
                                <td><span><a
                                    href="{{route("dashboard")}}?select={{$session->session_id}}" data-toggle="tooltip"
                                    data-placement="top" title="select"><i
                                        class="fa fa-check-circle color-success"></i></a></span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

{{-- Page JS --}}
@section('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@stop
