<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="strtoupper($report->title) . ' REPORT'"
                      :activeCrumb="$report->title"
                      :link="'report.index'"
                      :linkText="'Reports'"/>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="d-flex flex-column gap-2">
                           <span class="h3 text-uppercase ">Total Records: {{$results->total()}}</span>
                        </div>
                    </div>
                    <div class="card-tools mr-2">
                        <x-button class="btn  btn-primary" wire:click="export">Export</x-button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                           <div class="table-responsive">
                               <table class="table table-bordered">
                                   <thead>
                                   <tr>
                                       @foreach($columns as $column)
                                           <th>{{ strtoupper(str_replace('_', ' ', $column)) }}</th>
                                       @endforeach
                                   </tr>
                                   </thead>
                                   <tbody>
                                   @foreach($results as $result)
                                       <tr>
                                           @foreach($columns as $column)
                                               <td>{{ $result->$column ?? '' }}</td>
                                           @endforeach
                                       </tr>
                                   @endforeach
                                   </tbody>
                               </table>
                           </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    {{$results->links()}}
                </div>

            </div>

        </div>
    </div>
</div>
</section>