<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Manage Insurance'"
                      :activeCrumb="'Insurance'"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                </div>
                <div class="card-toolbar justify-content-end">
                    <!--begin::Filter-->
                    <button style="display: none;" type="button" class="btn btn-sm btn-primary me-3"
                            data-menu-trigger="click"
                            data-menu-placement="bottom-end">
                                        <span class="svg-icon svg-icon-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                        d="M19.0759 3H4.72777C3.95892 3
                                                    3.47768 3.83148 3.86067 4.49814L8.56967
                                                    12.6949C9.17923 13.7559 9.5 14.9582 9.5
                                                    16.1819V19.5072C9.5 20.2189 10.2223 20.7028
                                                    10.8805 20.432L13.8805 19.1977C14.2553 19.0435
                                                    14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089
                                                    14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596
                                                    3.912 19.8856 3 19.0759 3Z"
                                                        fill="currentColor"></path>
                                            </svg>
                                        </span>
                        Filter
                    </button>
{{--                    @can(config('rights.insurance_create'))--}}
                        <a href="{{route('insurance.create')}}"
                           class="btn btn-sm btn-success float-right">
                            <i class="fas fa-user-plus"></i>
                            Add Insurance
                        </a>
{{--                    @endcan--}}
                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        <table class="table table-bordered table-condensed">
                            <thead>
                            <th>Reg #</th>
                            <th>Policy No</th>
                            <th>Period From</th>
                            <th>Period To</th>
                            <th>Amount Insured</th>
                            <th>Premium</th>
                            <th>Payment Date</th>
                            </thead>
                            <tbody>
                            @forelse($insurances as $insurance)
                                <tr>
                                    <td>{{$insurance->reg_no}}</td>
                                    <td>{{$insurance->policy_no}}</td>
                                    <td>{{$insurance->period_from->toformattedDateString()}}</td>
                                    <td>{{$insurance->period_to->toformattedDateString()}}</td>
                                    <td>K{{number_format($insurance->insured_amount,2)}}</td>
                                    <td>K{{number_format($insurance->premium,2)}}</td>
                                    <td>{{$insurance->payment_date->toformattedDateString()}}</td>
                                    <td>
                                        <div class="btn-toolbar">
                                            <x-button class="btn btn-link btn-sm text-danger"
                                                      wire:click="remove({{$insurance->id}})"
                                                      wire:target="remove({{$insurance->id}})">remove
                                            </x-button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <p class="lead">No workshops have been added</p>
                                    </td>
                                </tr>

                            @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                {{$insurances->links()}}

            </div>
        </div>
    </div>
</section>