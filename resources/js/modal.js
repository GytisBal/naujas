$('#deleteModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget) // Button that triggered the modal
    const recipient = button.data('userid')

    console.log(recipient)

    const modal = $(this)

    modal.find('.modal-footer #userId').val(recipient)
})
