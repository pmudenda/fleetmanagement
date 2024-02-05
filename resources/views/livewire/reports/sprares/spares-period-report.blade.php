<section class="content mt-10">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Consolidated Spares report</h2>
                <x-button type="button" wire:target="download" wire:click="download"
                          class="btn btn-light-primary btn-sm ml-2">Export
                </x-button>
                <div class="card-tools">
                    <form class="form-inline" wire:submit.prevent="search">
                        <input type="date" class="form-control" placeholder="Search" wire:model="from">
                        <span class="ml-2 mr-2">To</span>
                        <input type="date" class="form-control " placeholder="Search" wire:model="to">

                        <x-button type="submit" wire:target="search" wire:click="search"
                                  class="btn btn-primary btn-sm ml-2">filter
                        </x-button>


                    </form>

                </div>
            </div>

            <div class="card-body table-responsive">
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
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-success">
{{--                            <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>--}}
                            <div class="info-box-content">
                                <span class="info-box-text">Total Amount</span>
                                <span class="info-box-number">K{{number_format($total_amount)}}</span>
                            </div>

                        </div>

                    </div>

                </div>


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