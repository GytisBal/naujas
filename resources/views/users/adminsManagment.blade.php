@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <link href="{{ asset('css/userManagement.css') }}" rel="stylesheet">
        <div class="table-wrapper">
            <div>
                @include('inc.messages')
            </div>
            <div class="col-sm-8">
                <h2>Admins Management</h2>
            </div>
            <div class="table-title">
                <div class="row">
                    {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
