<section class="content">
{{--    <x-error-view/>--}}
    <x-content-header :pageTitle="'Add Insurance'"
                      :activeCrumb="'Add Insurance'"
                      :link="'insurance.index'"
                      :linkText="'Manage Insurance'"/>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Vehicle Details
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Reg No</label>
                            <input type="text" class="form-control" wire:model="reg_no">
                        </div>

                        <x-button class="btn btn-primary btn-sm mb-5" wire:click="search">Search</x-button>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($vehicle)
                            <hr>

                            <table class="table table-borderless text-uppercase">
                                <tr>
                                    <th class="text-primary">Status</th>
                                    <td>{{$vehicle->statusInfo->name}}</td>
                                </tr>

                                <tr>
                                    <th class="text-primary">Brand</th>
                                    <td>{{$vehicle->brand_name}}</td>
                                </tr>

                                <tr>
                                    <th class="text-primary">Model</th>
                                    <td>{{$vehicle->model_name}}</td>
                                </tr>

                                <tr>
                                    <th class="text-primary">Type</th>
                                    <td>{{$vehicle->body_type_name}}</td>
                                </tr>

                            </table>
                        @endif
                    </div>

                </div>
            </div>

            <div class="col-lg-6">
                <form wire:submit.prevent="save">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                               Insurance information
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Policy Number</label>
                                        <input type="text" class="form-control" wire:model="insurance.policy_number">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Certificate Number</label>
                                        <input type="text" class="form-control" wire:model="insurance.certificate_number">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Period From</label>
                                        <input type="date" class="form-control" wire:model="insurance.period_from">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Period To</label>
                                        <input type="date" class="form-control" wire:model="insurance.period_to">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Insured Amount</label>
                                        <input type="number" class="form-control" wire:model="insurance.insured_amount">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Premium</label>
                                        <input type="number" class="form-control" wire:model="insurance.premium">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Payment Date</label>
                                        <input type="date" class="form-control" wire:model="insurance.payment_date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <x-button type="submit" class="btn btn-success" >Save</x-button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>