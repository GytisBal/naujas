@extends('layouts.dashboard')

@section('content')
    <a href="/users/{{$user->parent_id}}" class="btn btn-default">Go Back</a>
    <div class="container col-lg-9 usersManagement">
        <div class="row">
            <div>
                @include('inc.messages')
            </div>
            <div>
                <h2 class="header">{{$user->name}} Devices</h2>
            </div>
            <div class="device-form">
                {!! Form::open(['route' => ['user.addDevice', $user->id], 'method' => 'POST']) !!}
                {{Form::select('device', $devices)}}
                <div class="input-group date" data-provide="datepicker">
                    {{--                            {{Form::text('date',['class'=>'form-control'])}}--}}
                    <input name="date" autocomplete="off" type="text" class="form-control"
                           placeholder="Expiration date">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
                {{Form::submit('Add New', ['class'=>'btn btn-info add-new'])}}
                {!! Form::close() !!}
            </div>

            <table class="table table-bordered table-list">
                <thead>
                <tr>
                    <th>Device name</th>
                    <th>Device id</th>
                    <th>Expires at</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($userDevices)>0)
                    @foreach ($userDevices as $device )
                        <tr>
                            <td>{{$device->name}}</td>
                            <td>{{$device->device_id}}</td>
                            @if($device->pivot->expires_at !== null)
                            <td>{{date('Y-m-d', strtotime( $device->pivot->expires_at))}}</td>
                                @else
                                <td>Unlimited</td>
                            @endif
                            <td>
                                <a type="button" class="delete" data-toggle="modal" data-target="#deleteModal"
                                   data-device={{$device->id}}><i
                                        class="material-icons">&#xE872;</i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        @include("inc.userDeviceModal")
    </div>
@endsection
