<form wire:submit="save">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Workshop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="field-required col-form-label  p-0 mb-2">Workshop</label>
                    <select class="form-control form-control-solid" wire:model="workshop_code">
                        <option value="">--</option>
                        @foreach($workshops as $workshop)
                            <option value="{{$workshop->workshop_code}}">{{$workshop->workshop_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="field-required col-form-label  p-0 mb-2">Workshop</label>
                    <select class="form-control form-control-solid" wire:model="workshop_code">
                        <option value="">--</option>
                        @foreach($workshops as $workshop)
                            <option value="{{$workshop->workshop_code}}">{{$workshop->workshop_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</form>