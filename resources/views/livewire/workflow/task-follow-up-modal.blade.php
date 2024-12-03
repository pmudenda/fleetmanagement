<div class="modal-dialog">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">
                Document Task Tracking
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form wire:submit="search" class="form-inline">
                <div class="form-group">
                    <input wire:model="document_no" class="form-control form-control-lg mr-3">
                </div>
                <x-button class="btn btn-primary" target="search">Find</x-button>
            </form>


            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if($task)
                <dl class="row mt-5">
                    <dt class="col-sm-4">Code Identification</dt>
                    <dd class="col-sm-8 text-end">{{$task->code_identification}}</dd>

                    <dt class="col-sm-4">Date Generated</dt>
                    <dd class="col-sm-8 text-end">{{\Illuminate\Support\Carbon::parse($task->date_generation)}}</dd>

                    <dt class="col-sm-4">Description</dt>
                    <dd class="col-sm-8 text-end">{{$task->description}}</dd>

                    <dt class="col-sm-4">User Responsible</dt>
                    <dd class="col-sm-8 text-end">{{$task->user_responsible}}</dd>

                    <dt class="col-sm-4">Job Title</dt>
                    <dd class="col-sm-8 text-end">{{$task->description_job}}</dd>
                </dl>
            @endif
        </div>

        <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

    </div>

</div>