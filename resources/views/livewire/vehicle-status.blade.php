<div class="row">
    <div class="col-md-8">
        <div class="w-100">
            <select class="form-select form-select-sm"
                    wire:model="verified">
                <option>--</option>
                <option value="Y">Yes</option>
                <option value="N">No</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <x-button type="button" class="btn btn-sm btn-primary" wire:click="save" wire:target="save">Save</x-button>
    </div>
</div>
