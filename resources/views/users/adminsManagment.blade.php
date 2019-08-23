@extends('layouts.dashboard')

@section('content')
    <div class="container col-lg-9 usersManagement">
        <div class="row">
            <div>
                @include('inc.messages')
            </div>
            <div class="col-sm-8">
                <h2 class="header">Admins Management</h2>
            </div>
            {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <table class="table table-bordered table-form">
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
            <table class="table table-bordered table-list">
                <thead>
                <tr>
                    <th>Admin name</th>
                    <th>Admin email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($admins)>0)
                    @foreach ($admins as $admin )
                        <tr>
                            <td><a href="/users/{{$admin->id}}">{{$admin->name}}</td>
                            <td>{{$admin->email}}</td>
                            <td>
                                <a type="button" class="delete" data-toggle="modal" data-target="#deleteModal"
                                   data-userid={{$admin->id}}><i
                                        class="material-icons">&#xE872;</i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        @include("inc.adminModal")
    </div>
@endsection
