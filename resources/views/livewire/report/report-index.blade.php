<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Reports'"
                      :activeCrumb="'Reports'"
                      :link="'report.index'"
                      :linkText="'Reports'"/>

<div class="container-fluid">
    <div class="row">
        @foreach($reports as $category => $rs)
            <h3 class="my-3">{{$category}}</h3>
            @foreach($rs as  $report)
                <div class="col-12 col-sm-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text  text-primary">{{$report->name}}</span>
                            <a href="{{route('report.view', $report)}}" class="text-warning"><i class="fa fa-eye"></i> VIEW</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
</section>