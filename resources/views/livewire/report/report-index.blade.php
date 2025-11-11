<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reports</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @foreach($reports as $id => $report)
                            <a href="{{route('report.view', $id)}}">{{$report['title']}}</a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
