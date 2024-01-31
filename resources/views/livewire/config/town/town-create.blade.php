<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Add Town'"
                      :activeCrumb="'Add Town'"
                      :link="'general.town.index'"
                      :linkText="'Towns'"/>
    <div class="container-fluid">

        <div class="card w-50">
            <div class="card-header">
                <div class="card-title">
                    Add Town
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
                                    <label>Name</label>
                                    <input type="text" class="form-control" wire:model.defer="town.town_name" style="text-transform:uppercase">
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