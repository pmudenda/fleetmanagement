<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Town Distances'"
                      :activeCrumb="'Town Distances'"
                      :link="'home'"
                      :linkText="'Towns'"/>
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
                    {{--                    @can(config('rights.town_create'))--}}
                    <a href="{{route('general.town.distance.create', $town)}}"
                       class="btn btn-sm btn-success float-right">
                        <i class="fas fa-user-plus"></i>
                        Add Town Distance
                    </a>
                    {{--                    @endcan--}}
                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-lg-12">
                        <table class="table table-bordered table-condensed">
                            <thead>
                            <th>Town To</th>
                            <th>Distance</th>
                            <th></th>
                            </thead>
                            <tbody>
                            @forelse($distances as $distance)
                                <tr>
                                    <td>{{$distance->town_to}}</td>
                                    <td>{{$distance->distance}}</td>
                                    <td class="align-content-end float-right">
                                        <div class="btn-toolbar">

                                            <a href="{{route('general.town.distance.edit',compact('town','distance'))}}" class="btn btn-link btn-sm text-primary mr-3">Edit</a>


                                            <x-button class="btn btn-link btn-sm text-danger"
                                                      wire:click="remove({{$distance->id}})"
                                                      wire:target="remove({{$distance->id}})">Delete
                                            </x-button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <p class="lead">No Towns have been added</p>
                                    </td>
                                </tr>

                            @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                {{$distances->links()}}

            </div>
        </div>
    </div>
</section>