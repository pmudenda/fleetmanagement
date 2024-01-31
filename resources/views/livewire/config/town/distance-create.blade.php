<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Add Town Distance'"
                      :activeCrumb="'Add Town Distance'"
                      :link="'general.town.index'"
                      :linkText="'Towns'"/>
    <div class="container-fluid">

        <div class="card w-50">
            <div class="card-header">
                <div class="card-title">
                    <h3>Add {{$town->town_name}} to Other Town Distance</h3>

                </div>
                <div class="card-toolbar justify-content-end">

                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <form wire:submit.prevent="save">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Town to </label>
                                   <select class="form-control" wire:model.defer="distance.town_to">
                                       <option value="">--</option>
                                       @foreach($towns as $town)
                                           <option value="{{$town->town_name}}">{{$town->town_name}}</option>
                                       @endforeach
                                   </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Distance</label>
                                    <input type="text" class="form-control" wire:model.defer="distance.distance">
                                </div>
                            </div>
                        </div>

                        <x-button type="submit" class="btn btn-primary" wire:target="save">Save</x-button>
                    </form>

                </div>
            </div>


        </div>
    </div>
</section>