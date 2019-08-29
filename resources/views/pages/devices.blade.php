@extends('layouts.dashboard')

@section('content')
    <div class="container col-lg-9 usersManagement">
        <div class="row">
            <div>
                @include('inc.messages')
            </div>
            <div class="col-sm-8">
                <h2 class="header">Devices Management</h2>
            </div>
            {!! Form::open(['route' => ['devices.store'], 'method' => 'POST']) !!}
            <table class="table table-bordered table-form">
                <tbody>
                <tr>
                    <td>
                        {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
                    </td>
                    <td>
                        {{Form::text('device_id', '', ['class' => 'form-control', 'placeholder' => 'Device id'])}}
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
                @if(count($devices)>0)
                    @foreach ($devices as $device )
                        <tr>
                            <td>{{$device->name}}</td>
                            <td>{{$device->device_id}}</td>
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
            @include("inc.devicesModal")
        </div>
    </div>
@endsection
