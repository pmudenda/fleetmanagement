<div class="row">
    <div class="col-lg-12">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-workshop-add">Add Workshop</button>
    </div>

    <div class="modal fade" id="modal-workshop-add" tabindex="-1" role="dialog" aria-labelledby="modal-workshop-add"
         aria-hidden="true">
        <livewire:mechanic.workshop-add :mechanic="$mechanic"/>
    </div>
</div>
