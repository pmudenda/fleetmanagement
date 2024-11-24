<section class="content mt-10">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vehicle in workshop Over 90 days</h3>

               <div class="card-tools">
                   <x-button type="button" wire:target="download" wire:click="download"
                             class="btn btn-light-primary btn-sm ml-2">Export
                   </x-button>
               </div>


            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap table-bordered">
                    <thead>
                    <tr>
                        <th>REG NO</th>
                        <th>Brand</th>
                        <th>Workshop Act Code</th>
                        <th>Workshop</th>
                        <th>Date In</th>
                        <th>Expected Date Out</th>
                        <th>Driver Man No</th>
                        <th>Driver Name</th>
                        <th>Days Overdue</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{$vehicle->reg_no}}</td>
                            <td>{{$vehicle->brand_name}}</td>
                            <td>{{$vehicle->wshp_act_code}}</td>
                            <td>{{$vehicle->workshop_name}}</td>
                            <td>{{$vehicle->date_in}}</td>
                            <td>{{$vehicle->expected_date_out}}</td>
                            <td>{{$vehicle->driver_in}}</td>
                            <td>{{$vehicle->driver_name}}</td>
                            <td>{{number_format($vehicle->days_overdue,0)}}</td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

{{--            <div class="card-footer">--}}
{{--                {{$vehicles->links()}}--}}
{{--            </div>--}}


        </div>
    </div>
</section>