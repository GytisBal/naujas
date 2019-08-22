<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Delete admin</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure, you want to delete admin and all his added users ?</p>
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
