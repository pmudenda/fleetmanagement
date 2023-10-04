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
                <x-error-view/>
                <div class="form-group">
                    <label class="field-required col-form-label  p-0 mb-2">Workshop</label>
                    <select class="form-control form-control-solid" wire:model="workshop_code">
                        <option value="">--</option>
                        @foreach($workshops as $workshop)
                            <option value="{{$workshop->id}}">{{$workshop->workshop_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="field-required col-form-label  p-0 mb-2">Is Supervisor</label>
                    <select class="form-control form-control-solid" wire:model="is_supervisor">
                        <option value="">--</option>
                        @foreach($supervisors as $id => $name)
                            <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <x-button type="submit" class="btn btn-primary" wire:target="save">Save changes</x-button>
            </div>
        </div>
    </div>
</form>
