@extends('layouts.dashboard');

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Table with Add and Delete Row Feature</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        body {
            color: #404E67;
            background: #F5F7FA;
            font-family: 'Open Sans', sans-serif;
        }

        .table-wrapper {
            width: 70%;
            background: #fff;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }
        h2{
            font-size: 22px;
            margin-bottom: 2rem;
        }
        .table-title {
            padding-bottom: 10px;
            margin: 0 0 10px;
        }

        .table-title h2 {
            margin: 6px 0 0;
            font-size: 22px;
        }

        .table-title .add-new {
            float: right;
            height: 30px;
            font-weight: bold;
            font-size: 12px;
            text-shadow: none;
            min-width: 100px;
            border-radius: 50px;
            line-height: 13px;
        }

        .table-title .add-new i {
            margin-right: 4px;
        }

        table.table {
            table-layout: fixed;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table th:last-child {
            width: 100px;
        }

        table.table td a {
            cursor: pointer;
            display: inline-block;
            margin: 0 5px;
            min-width: 24px;
        }

        table.table td a.add {
            color: #27C46B;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #E34724;
        }

        table.table td i {
            font-size: 19px;
        }

        table.table td a.add i {
            font-size: 24px;
            margin-right: -1px;
            position: relative;
            top: 3px;
        }

        table.table .form-control {
            height: 32px;
            line-height: 32px;
            box-shadow: none;
            border-radius: 2px;
        }

        table.table .form-control.error {
            border-color: #f50000;
        }

        table.table td .add {
            display: none;
        }
        table button{
            border: none;
            background-color: transparent;
        }
    </style>
    
</head>

<body>
    <div class="container">
        <div class="table-wrapper">
                <div class="col-sm-8">
                        <h2>Admins Managment</h2>
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
                {!!Form::open(['action' => ['UsersController@destroy', $admin->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{ Form::button('<a class="delete" title="Delete" data-toggle="tooltip"><i
                class="material-icons">&#xE872;</i></a>', ['type' => 'submit'] )  }}
            {!!Form::close()!!}
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
</body>

</html>
@endsection