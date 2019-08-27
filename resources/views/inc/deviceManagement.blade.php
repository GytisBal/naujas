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
                        <div class="input-group date" data-provide="datepicker">
{{--                            {{Form::text('date',['class'=>'form-control'])}}--}}
                            <input name="date" type="text" class="form-control"
                                   placeholder="Expiration date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
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
                                   data-device={{$device->id}}><i
                                        class="material-icons">&#xE872;</i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
{{--        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">--}}

{{--        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>--}}
{{--        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>--}}
{{--        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>--}}
{{--        <script--}}
{{--            src="https://code.jquery.com/jquery-3.4.1.js"--}}
{{--            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="--}}
{{--            crossorigin="anonymous"></script>--}}

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
{{--        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
{{--        <script>--}}
{{--                $('.datepicker').datepicker();--}}
{{--        </script>--}}
        @include("inc.userDeviceModal")
    </div>
@endsection
