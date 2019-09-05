<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @isset($user)
                    @if($user->hasRole('super-admin') )
                        <h4 class="modal-title">Delete admin</h4>
            </div>

            <div class="modal-body">
                <p>Are you sure, you want to delete admin and all his users ?</p>
            </div>
            @elseif($user->hasRole('admin'))
                <h4 class="modal-title">Delete user</h4>
        </div>

        <div class="modal-body">
            <p>Are you sure, you want to delete user ?</p>
        </div>
        @else
            <h4 class="modal-title">Remove device</h4>
    </div>
    <div class="modal-body">
        <p>Are you sure, you want to remove device ?</p>
    </div>
    @endif
    @else
        <h4 class="modal-title">Delete device</h4>
</div>
<div class="modal-body">
    <p>Are you sure, you want to delete device</p>
</div>
@endisset
<div class="modal-footer">
    @isset($user)
        @if($user->hasRole('admin') || $user->hasRole('super-admin') )
            {!!Form::open(['action' => ['UsersController@destroy', "delete"], 'method' => 'POST'])!!}
        @else
            {!!Form::open(['route' => ['user.removeDevice', 'user_id'=>$user->id ], 'method' => 'POST'])!!}
        @endif
    @else
        {!!Form::open(['action' => ['DevicesController@destroy', "delete" ], 'method' => 'POST'])!!}
    @endisset
    {!!Form::open(['action' => ['UsersController@destroy', "delete"], 'method' => 'POST'])!!}
    {{Form::hidden('_method', 'DELETE')}}
    {{Form::hidden('id', '', ['id' => 'id'])}}
    {{ Form::button('<a  class="btn btn-primary"  data-dismiss="modal">No, Close</a>', ['type' => 'button'] )  }}
    {{ Form::button('<a  class="btn btn-danger" >Yes, Delete</a>', ['type' => 'submit'] )  }}
    {!!Form::close()!!}
</div>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget) // Button that triggered the modal
        const recipient = button.data('id')
        console.log(recipient);
        const modal = $(this)
        modal.find('.modal-footer #id').val(recipient)
    })
</script>
</div>
