<section class="content mt-10">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Spares report</h3>
                <div class="card-tools">
                    <form class="form-inline" wire:submit.prevent="search">
                        <input type="date" class="form-control" placeholder="Search" wire:model="from">
                        <span class="ml-2 mr-2">To</span>
                        <input type="date" class="form-control " placeholder="Search" wire:model="to">

                        <select class="form-control ml-3" wire:model="fuel_type">
                            <option value="">--Fuel Type--</option>
                            <option value="PETROL">PETROL</option>
                            <option value="DIESEL">DIESEL</option>
                        </select>

                        <x-button type="submit" wire:target="search" wire:click="search" class="btn btn-primary btn-sm ml-2">filter</x-button>
                    </form>

                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap table-bordered">
                    <thead>
                    <tr>
                        <th>REG NO</th>
                        <th>Fuel Type</th>
                        <th>Requesting Unit</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($spares as $spare)
                        <tr>
                            <td>{{$spare->reg_no}}</td>
                            <td>{{$spare->fuel_type}}</td>
                            <td>{{$spare->fuel_req_unit}}</td>
                            <td>{{number_format($spare->qty)}} Litres</td>
                            <td>K{{number_format($spare->ttl)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>