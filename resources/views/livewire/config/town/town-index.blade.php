<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Manage Towns'"
                      :activeCrumb="'Towns'"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3>Towns</h3>
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
                    <a href="{{route('general.town.create')}}"
                       class="btn btn-sm btn-success float-right">
                        <i class="fas fa-user-plus"></i>
                        Add Town
                    </a>
                    {{--                    @endcan--}}
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control" wire:model.live="search" placeholder="Search Town">
                            <span class="text-primary"></span>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <table class="table table-bordered table-condensed mt-5">
                            <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th></th>
                            </thead>
                            <tbody>
                            @forelse($towns as $town)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{$town->town_name}}</td>
                                    <td class="align-content-end float-right">
                                        <div class="btn-toolbar">

                                            <a href="{{route('general.town.distance.index',$town)}}"
                                               class="btn btn-link btn-sm text-primary mr-3">Distances</a>


                                            <a href="{{route('general.town.edit',$town)}}"
                                               class="btn btn-link btn-sm text-primary mr-3">Edit</a>


                                            <x-button class="btn btn-link btn-sm text-danger"
                                                      wire:click="remove({{$town->id}})"
                                                      wire:target="remove({{$town->id}})">Delete
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
                {{$towns->links()}}

            </div>
        </div>
    </div>
    <script type="text/javascript">
        // $(document).ready(function (){
        document.addEventListener('livewire:init', () => {
            $('#search').on('select2:select', function (e) {
                var data = e.params.data;
                console.log(data)
                $wire.search = data.id;
            });
        })

        // });
    </script>
</section>

