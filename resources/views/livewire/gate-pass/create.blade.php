<section class="content">
    <x-error-view/>
    <x-content-header pageTitle="New Gate Pass"
                      :activeCrumb="'Gate Pass'"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">
        <form wire:submit="save">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="inputName">Type</label>
                                        <select class="form-control" wire:model.live="type">
                                            <option>--</option>
                                            @foreach($types as $t)
                                                <option value="{{$t->value}}">{{$t->label()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="inputName">Vehicle Reg No</label>
                                        <input type="text" class="form-control" wire:model="reg_no" placeholder="ABC 1234"/>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="inputName">Expiry Date </label>
                                        <input type="date" class="form-control" min="{{$minDate}}" max="{{$maxDate}}" wire:model="expires_at" />
                                    </div>
                                </div>

                                @if($type ==  \App\Enums\GatePassType::AUTHORITY_TO_TRAVEL->value)
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="inputName">Departure Date</label>
                                            <input class="form-control" wire:model="departure_at" min="{{$minDate}}" type="date">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="inputName">Departure Town</label>
                                            <select class="form-control" wire:model.live="departure_town">
                                                <option>--</option>
                                                @foreach($towns as $tw)
                                                    <option value="{{$tw->town_name}}">{{$tw->town_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="inputName">Destination Town</label>
                                            <select class="form-control" wire:model.live="destination_town">
                                                <option>--</option>
                                                @foreach($dtowns as $dt)
                                                    <option value="{{$dt->town_to}}">{{$dt->town_to}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="inputName">Purpose of Travel</label>
                                        <textarea  class="form-control text-uppercase" wire:model="purpose" ></textarea>
                                    </div>
                                </div>

                                @if($type ==  \App\Enums\GatePassType::STAND_BY->value)
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="inputName">Attachment</label>
                                            <input class="form-control" wire:model="attachment" type="file">
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-12">
                                    <x-button class="btn-primary" type="submit" wire:target="save">Save</x-button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</section>