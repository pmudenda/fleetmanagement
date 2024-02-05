<section class="content mt-10">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Spares report</h3>
                <div class="card-tools">
                    <form class="form-inline" wire:submit.prevent="search">
                        <input type="date" class="form-control" placeholder="Search" wire:model="from">
                        <span class="ml-2 mr-2">To</span>
                        <input type="date" class="form-control " placeholder="Search" wire:model="to">

{{--                        <select class="form-control ml-3" wire:model="fuel_type">--}}
{{--                            <option value="">--Fuel Type--</option>--}}
{{--                            <option value="PETROL">PETROL</option>--}}
{{--                            <option value="DIESEL">DIESEL</option>--}}
{{--                        </select>--}}

                        <x-button type="submit" wire:target="search" wire:click="search"
                                  class="btn btn-primary btn-sm ml-2">filter
                        </x-button>

                        <x-button type="button" wire:target="download" wire:click="download"
                                  class="btn btn-light-primary btn-sm ml-2">Export
                        </x-button>
                    </form>

                </div>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-head-fixed text-nowrap table-bordered">
                    <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th>{{strtoupper(str_replace('_',' ',$column))}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{$cell}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{$rows->links()}}
            </div>

        </div>
    </div>
</section>