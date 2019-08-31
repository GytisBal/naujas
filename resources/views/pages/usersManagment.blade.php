@extends('layouts.dashboard')

@section('content')

    @hasrole('super-admin')
    @if($user->hasRole('admin'))
        <a href="/users" class="btn btn-default">Go Back</a>
    @endif
    @endhasrole
    <div class="container col-lg-9 usersManagement">
        <div class="row">
            <div>
                @include('inc.messages')
            </div>
            @include("inc.modal")
            <div class="col-sm-8">
                <h2 class="header">Users Management</h2>
            </div>
            @if($user->hasRole('super-admin'))
                {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST',]) !!}
            @else
                {!! Form::open(['route' => ['users.createChild', $user->id], 'method' => 'POST']) !!}
            @endif
            <table class="table table-bordered table-form">
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

            <table class="table table-bordered table-list">
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
                            <td>
                                @hasrole('super-admin')
                                @if($user->hasRole('admin'))
                                    <a href="/users/{{$user->id}}">{{$user->name}} </a>
                                @else
                                    <a href="/users/{{$user->id}}/devices">{{$user->name}}</a>
                                @endif
                                @else
                                    <a href="/users/{{$user->id}}/devices">{{$user->name}}</a>
                                    @endhasrole
                            </td>
                            <td>{{$user->email}}</td>
                            <td>
                                <a type="button" class="delete" data-toggle="modal" data-target="#deleteModal"
                                   data-id={{$user->id}}><i
                                        class="material-icons">&#xE872;</i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
