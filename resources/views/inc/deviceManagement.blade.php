@extends('layouts.dashboard')

@section('content')
    <a href="/users" class="btn btn-default">Go Back</a>
    <div class="container col-lg-9 usersManagement">
        <div class="row">
            <div>
                @include('inc.messages')
            </div>
            <div class="col-sm-8">
                <h2 class="header">Device Management</h2>
            </div>
            {!! Form::open(['route' => ['user.addDevice', $user->id], 'method' => 'POST']) !!}
            <table class="table table-bordered table-form">
                <tbody>
                <tr>
                    <td>
                        {{Form::select('device', $devices)}}
                    </td>
                    <td>
                        {{Form::text('device_id', '', ['class' => 'form-control', 'placeholder' => 'expiration date'])}}
                    </td>
                    <td>
                        {{Form::submit('Add New', ['class'=>'btn btn-info add-new'])}}
                    </td>
                </tr>
                {!! Form::close() !!}
                </tbody>
            </table>

            <table class="table table-bordered table-list">
                <thead>
                <tr>
                    <th>Device name</th>
                    <th>Device id</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($userDevices)>0)
                    @foreach ($userDevices as $device )
                        <tr>
                            <td>{{$device->name}}</td>
                            <td>{{$device->device_id}}</td>
                            <td>
                                <a type="button" class="delete" data-toggle="modal" data-target="#deleteModal"
                                   data-userid={{$device->id}}><i
                                        class="material-icons">&#xE872;</i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        @include("inc.devicesModal")
    </div>
@endsection
