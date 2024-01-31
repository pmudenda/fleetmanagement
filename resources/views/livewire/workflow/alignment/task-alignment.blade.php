<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Task Re-Alignment'"
                      :activeCrumb="'Task Re-Alignment'"
                      :link="'general.town.index'"
                      :linkText="'Towns'"/>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-4">
                <div class="col-lg-12">
                    <div class="card ">
                        <div class="card-header">
                            <div class="card-title">
                                Move Tasks From:
                            </div>
                            <div class="card-toolbar justify-content-end">

                            </div>
                        </div>

                        <div class="card-body">


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">

                                        <div class="input-group" wire:ignore>
                                            <input id="staff_number_from" class="form-control"
                                                   wire:model="staff_number_from" placeholder="Staff Number"/>
                                            <div class="input-group-append">
                                                <x-button type="button" class="btn btn-primary btn-sm"
                                                          wire:target="search_from"
                                                          wire:click="search_from">Search
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">

                                </div>
                            </div>

                            <dl class="row">
                                <dt class="col-lg-3">Staff No</dt>
                                <dd class="col-lg-9 text-right">{{$userFrom->staff_no ?? '--'}}</dd>

                                <dt class="col-lg-3">Name</dt>
                                <dd class="col-lg-9 text-right">{{$userFrom->name ?? '--'}}</dd>

                                <dt class="col-lg-3">Job Title</dt>
                                <dd class="col-lg-9 text-right">{{$userFrom->job_title ?? '--'}}</dd>
                            </dl>

                        </div>


                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card ">
                        <div class="card-header">
                            <div class="card-title">
                                Move Tasks To:
                            </div>
                            <div class="card-toolbar justify-content-end">

                            </div>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="input-group" wire:ignore>
                                            <input id="staff_number_to" class="form-control"
                                                   wire:model="staff_number_to" placeholder="Staff No"/>
                                            <div class="input-group-append">
                                                <x-button type="button" class="btn btn-primary btn-sm"
                                                          wire:target="search_to"
                                                          wire:click="search_to">Search
                                                </x-button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <dl class="row">
                                <dt class="col-lg-3">Staff No</dt>
                                <dd class="col-lg-9 text-right">{{$userTo->staff_no ?? '--'}}</dd>

                                <dt class="col-lg-3">Name</dt>
                                <dd class="col-lg-9 text-right">{{$userTo->name ?? '--'}}</dd>

                                <dt class="col-lg-3">Job Title</dt>
                                <dd class="col-lg-9 text-right">{{$userTo->job_title ?? '--'}}</dd>
                            </dl>

                        </div>


                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <form wire:submit.prevent="assign">
                    <div class="card ">
                        <div class="card-header">
                            <div class="card-title">
                                Tasks
                            </div>
                            <div class="card-toolbar justify-content-end">
                                @if(!empty($tasks))
                                    <form class="form-inline">
                                        <x-button type="button" class="btn btn-light-primary btn-sm ml-3"
                                                  wire:target="selectAll" wire:click="selectAll">
                                            Select All
                                        </x-button>

                                        <x-button type="button" class="btn btn-light-danger btn-sm ml-3"
                                                  wire:target="deselect" wire:click="deselect">
                                            De-Select All
                                        </x-button>

                                        <x-button type="submit" class="btn btn-primary btn-sm  ml-3" wire:target="assign">
                                            Assign
                                        </x-button>


                                    </form>
                                @endif

                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <table class="table table-bordered">
                                <thead>
                                <th></th>
                                <th>Reference</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Created_at</th>
                                </thead>

                                <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       value="{{$task->reference}}" wire:model="selected_tasks"/>
                                            </div>
                                        </td>
                                        <td>{{$task->reference}}</td>
                                        <td>{{$task->subject}}</td>
                                        <td>{{$task->priority}}</td>
                                        <td>{{$task->created_at->toFormattedDateString()}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
