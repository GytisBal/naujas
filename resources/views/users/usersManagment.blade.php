@extends('layouts.dashboard')

@section('content')
    <body>
    @hasrole('super-admin')
    <a href="/users" class="btn btn-default">Go Back</a>
    @endhasrole
    <div class="container">
        <div class="table-wrapper">
            <div>
                @include('inc.messages')
            </div>
            <div class="col-sm-8">
                <h2>{{$admin->name}} Users Managment</h2>
            </div>
            <div class="table-title">

                <div class="row">

                    {!! Form::open(['route' => ['users.createChild', $admin->id], 'method' => 'POST']) !!}
                    <table class="table table-bordered">
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
                    </table>
                </div>
            </div>
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
        </div>
        @include("inc.modal")
    </div>
    </body>
@endsection
