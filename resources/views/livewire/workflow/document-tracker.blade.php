<div class="modal-dialog">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">
                FMS Payment GRN Tracker
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form wire:submit="search" >
                <div class="row">
                    <div class="col-lg-12">
                            <input wire:model="document_no" class="form-control form-control-lg w-100" placeholder="GRN Number">
                    </div>
                </div>
                <div class="col-lg-12">
                    <x-button class="btn btn-primary w-100 mt-3" target="search">Find</x-button>
                </div>
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
                <dl class="row mt-3">
                    <dt class="col-sm-6">Document No</dt>
                    <dd class="col-sm-6 text-end">{{$task->document_no}}</dd>

                    <dt class="col-sm-6">Generation Date</dt>
                    <dd class="col-sm-6 text-end">{{\Illuminate\Support\Carbon::parse($task->grn_gen_date)->toFormattedDateString()}}</dd>

                    <dt class="col-sm-6">Invoice</dt>
                    <dd class="col-sm-6 text-end">{{$task->invoice_num}}</dd>

                    <dt class="col-sm-6">Amount Paid</dt>
                    <dd class="col-sm-6 text-end">K{{number_format($task->amount_paid, 2)}}</dd>

                    <dt class="col-sm-6">Status</dt>
                    <dd class="col-sm-6 text-end"><span
                                class="badge badge-lg badge-{{$task->payment_status_flag == 'Y'? "success" : "warning"}}">{{$task->payment_status_flag == 'Y'? "Paid" : "Not Paid"}}</span>
                    </dd>

                </dl>
            @endif
        </div>

        <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

    </div>

</div>