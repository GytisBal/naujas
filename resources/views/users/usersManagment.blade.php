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
        .modal-footer button{
            border: none;
            background-color: transparent;
        }
        .btn-default{
            margin: 2rem;
        }
    </style>

</head>

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
</body>
</html>
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Delete user</h4>
            </div>

            <div class="modal-body">
                <p>Are you sure, you want to delete user ?</p>
            </div>

            <div class="modal-footer">
                {!!Form::open(['action' => ['UsersController@destroy', "test" ], 'method' => 'POST'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::hidden('userId', '', ['id' => 'userId'])}}
                {{ Form::button('<a  class="btn btn-primary"  data-dismiss="modal">No, Close</a>', ['type' => 'button'] )  }}
                {{ Form::button('<a  class="btn btn-danger" >Yes, Delete</a>', ['type' => 'submit'] )  }}
                {!!Form::close()!!}
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget) // Button that triggered the modal
        const recipient = button.data('userid')

        console.log(recipient)

        const modal = $(this)

        modal.find('.modal-footer #userId').val(recipient)
    })
</script>
@endsection
