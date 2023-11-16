<section class="content mt-10">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-6">
{{--                <livewire:reports.fuel.cost-by-unit-chart :month="$month" :year="$year" />--}}
                <livewire:reports.fuel.status.fuel-status-by-amount-chart/>
{{--                <livewire:reports.fuel.status.fuel-status-by-amount-chart/>--}}
            </div>

            <div class="col-lg-6">
                <livewire:reports.fuel.status.fuel-status-by-qty-chart/>
            </div>
        </div>
    </div>
</section>