<section class="content mt-10">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <select class="form-control" wire:model="year">
                                <option value="">--Year--</option>
                                @for($y = now()->year - 5; $y <= now()->year; $y++)
                                    <option value="{{$y}}">{{$y}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <select class="form-control" wire:model="month">
                                <option value="">--Month--</option>
                                @foreach($this->months as $m)
                                    <option value="{{$m['id']}}">{{$m['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                       <button class="btn btn-primary btn-sm" wire:click="search">Search</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <livewire:reports.fuel.cost-by-unit-chart :month="$month" :year="$year" />
            </div>

            <div class="col-lg-6">
                <livewire:reports.fuel.fuel-requisition-trend-chart :year="$year"/>
            </div>
        </div>
    </div>
</section>