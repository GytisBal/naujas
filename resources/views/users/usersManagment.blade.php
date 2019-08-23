@extends('layouts.dashboard')

@section('content')

    @hasrole('super-admin')
    <a href="/users" class="btn btn-default">Go Back</a>
    @endhasrole
    <div class="container col-lg-9 usersManagement">
        <div class="row">
            <div>
                @include('inc.messages')
            </div>
            <div class="col-sm-8">
                <h2 class="header">{{$admin->name}} Users Management</h2>
            </div>

            {!! Form::open(['route' => ['users.createChild', $admin->id], 'method' => 'POST']) !!}
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td>
                        {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
                    </td>
                    <td>
                        {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email'])}}
                    </td>
                    <td>
                        {{Form::submit('Add New', ['class'=>'btn btn-info add-new'])}}
                    </td>
                </tr>
                {!! Form::close() !!}
                </tbody>
            </table>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>User name</th>
                <th>User email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if(count($users)>0)
                @foreach ($users as $user )
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a type="button" class="delete" data-toggle="modal" data-target="#deleteModal"
                               data-userid={{$user->id}}><i
                                    class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        @include("inc.modal")
    </div>
    </div>
@endsection
