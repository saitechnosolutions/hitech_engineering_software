<div class="modal fade" id="updateTaskStatusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createFormSubmit" class="form-horizontal m-t-10" method="POST" action="{{ route('tasks.updatetaskstatus') }}">
                            @csrf
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Task Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

                                        <div class="form-group">
                                            <label>Task Status</label>
                                            <div>
                                                <select class="form-control" name="task_status">
                                                    <option value="">-- Choose Status --</option>
                                                    <option value="completed" selected>Completed</option>
                                                    <option value="pending">Pending</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                        <label>Upload Image</label>
                                                             <div>
                                            <input type="file" class="form-control" required
                                                    parsley-type="text" name="upload_image" placeholder=""/>
                                        </div>
                                    </div>

                                    <input type="text" name="task_id" id="task_id">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </form>
  </div>
</div>
