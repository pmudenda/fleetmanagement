<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="$gatePass->reference_number"
                      :activeCrumb="$gatePass->reference_number"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title"><h3>Gate Pass Details</h3></div>
                                <div class="card-tools p-3 ">
                                    {!! $gatePass->status->badge() !!}
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Type</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$gatePass->type->label()}}"/>

                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Expiry Date </label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$gatePass->expires_at->toFormattedDateString()}}"/>
                                        </div>
                                    </div>

                                    @if($gatePass->type ==  \App\Enums\GatePassType::AUTHORITY_TO_TRAVEL)
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="inputName">Departure Date</label>
                                                <input type="text" class="form-control-plaintext"
                                                       value="{{$gatePass->departure_at->toDateTimeString()}}"/>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="inputName">Departure Town</label>
                                                <input type="text" class="form-control-plaintext"
                                                       value="{{$gatePass->departure_town}}"/>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="inputName">Destination Town</label>
                                                <input type="text" class="form-control-plaintext"
                                                       value="{{$gatePass->destination_town}}"/>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="inputName">Purpose of Travel</label>
                                            <p>{{$gatePass->purpose}}</p>
                                        </div>
                                    </div>

                                    <hr/>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Authorised By</label>
                                            <input class="form-control-plaintext" value="{{$gatePass->authorisedBy->name ?? '--'}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Title</label>
                                            <input class="form-control-plaintext" value="{{$gatePass->authorisedBy->job_title ?? '--'}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Authorised At</label>
                                            <input class="form-control-plaintext" value="{{$gatePass->authorised_at? $gatePass->authorised_at->toFormattedDateString() : '--'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Reason</label>
                                            <p>{{$gatePass->authorised_reason ?? '--'}}</p>
                                        </div>
                                    </div>

                                    <hr/>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Checked By</label>
                                            <input class="form-control-plaintext" value="{{$gatePass->checkedBy->name ?? '--'}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Title</label>
                                            <input class="form-control-plaintext" value="{{$gatePass->checkedBy->job_title ?? '--'}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Checked At</label>
                                            <input class="form-control-plaintext" value="{{$gatePass->checked_at? $gatePass->checked_at->toFormattedDateString() : '--'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="inputName">Reason</label>
                                            <p>{{$gatePass->checked_reason ?? '--'}}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @can('authorise', $gatePass)
                                    <div class="form-group">
                                        <label for="inputName">Reason</label>
                                        <textarea class="form-control text-uppercase" wire:model="reason"></textarea>
                                        @error('reason') <span class="text-danger">{{$message}}</span> @enderror
                                    </div>

                                    <x-button class="btn btn-light-success" wire:target="approve" wire:click="approve"
                                              wire:confirm="Are you sure you want to approve">
                                        Authorize
                                    </x-button>

                                    <x-button class="btn btn-light-danger" wire:target="reject" wire:click="reject"
                                              wire:confirm="Are you sure you want to approve">
                                        Reject
                                    </x-button>
                                @endcan

                                @can('check', $gatePass)

                                    <div class="form-group">
                                        <label for="inputName">Reason</label>
                                        <textarea class="form-control text-uppercase" wire:model="reason"></textarea>
                                        @error('reason') <span class="text-danger">{{$message}}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="confirmation">
                                            <label class="form-check-label text-bold text-uppercase">I certify that I
                                                have searched and checked the vehicle before leaving the yard</label>
                                        </div>
                                        @error('confirmation') <span class="text-danger">{{$message}}</span> @enderror

                                    </div>

                                    <x-button class="btn btn-light-success" wire:target="confirm" wire:click="confirm"
                                              wire:confirm="Are you sure you want to approve">
                                        Confirm
                                    </x-button>

                                    <x-button class="btn btn-light-danger" wire:target="reject_confirmation" wire:click="reject_confirmation"
                                              wire:confirm="Are you sure you want to approve">
                                        Reject
                                    </x-button>
                                @endcan
                            </div>
                        </div>
                    </div>

                    @if($gatePass->$status == \App\Enums\GatePassStatus::NEWm)
                        <div class="col-lg-12">
                            <div class="card card-flush">
                                <div class="card-header">
                                    <div class="card-title"><h3>Authorization</h3></div>
                                    <div class="card-tools p-3 ">
                                        {{--                                    {!! $gatePass->status->badge() !!}--}}
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group">
                                        @foreach($authorisers as $authoriser)
                                            <div class="list-group-item" aria-current="true">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{$authoriser->staff_no}} - {{$authoriser->name}}</h5>
                                                    @if($authoriser->id == $gatePass->authorised_by)
                                                        <span class="badge badge-{{$is_rejected? 'danger' : 'warning' }}">{{$is_rejected? 'Rejected' : 'Authorised' }}
                                                            on {{$gatePass->authorised_at->toFormattedDateString()}}</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1">{{$authoriser->job_title}}</p>

                                                @if($gatePass->authorised_reason && $authoriser->id == $gatePass->authorised_by)
                                                    <span class="text-muted">REASON</span>
                                                    <p class="text-muted mt-2">{{$gatePass->authorised_reason}}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title"><h3>Vehicle Details</h3></div>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Reg #</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$vehicle->registration_number?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Brand</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$vehicle->brand_name?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Model</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$vehicle->model_name?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Fuel Type</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$vehicle->engine->fuelType->description?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Business Unit</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$vehicle->business_unit_name ?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Status</label>
                                            <input type="text" class="form-control-plaintext" value="{{$status}}"/>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title"><h3>Requester Details</h3></div>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Man #</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->staff_no?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Name</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->name?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Mobile #</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->phone?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">E-mail</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->email?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Job Title</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->job_title?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Location</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->location?? 'N/A'}}"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Functional Section</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->functional_section?? 'N/A'}}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="inputName">Business Unit</label>
                                            <input type="text" class="form-control-plaintext"
                                                   value="{{$user->user_unit?? 'N/A'}}"/>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>