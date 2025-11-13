<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Reports'"
                      :activeCrumb="'Reports'"
                      :link="'report.index'"
                      :linkText="'Reports'"/>

<div class="container-fluid">
    <div class="row">
        @foreach($reports as  $report)
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-primary">{{$report->name}}</span>
                        <a href="{{route('report.view', $report)}}" class="text-center">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</section>