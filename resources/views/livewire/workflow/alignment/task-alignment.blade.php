<section class="content">
    <x-error-view/>
    <x-content-header :pageTitle="'Task Re-Alignment'"
                      :activeCrumb="'Task Re-Alignment'"
                      :link="'general.town.index'"
                      :linkText="'Towns'"/>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-4">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">
                            Employee Search
                        </div>
                        <div class="card-toolbar justify-content-end">

                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">

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
                                    <div class="form-group" wire:ignore>
                                        <label>Staff Number</label>
                                        <select id="staff_number_from" class="form-select mb-3 select2"  data-placeholder="Search Vehicle">
                                            <option>--</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->staff_no}}">{{$user->staff_no}} - {{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <x-button type="button" class="btn btn-primary btn-sm" wire:target="search"
                                      wire:click="search">Search
                            </x-button>

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
                              <form class="form-inline">
                                  <input type="text" class="form-control w-50 " id="inlineFormInputName2" placeholder="Assign to">

                                  <button type="submit" class="btn btn-primary btn-sm ml-3">Assign</button>
                              </form>
                          </div>
                      </div>

                      <div class="card-body">
                          <table class="table table-bordered">
                              <thead>
                              <th></th>
                              <th>Reference</th>
                              <th>Subject</th>
                              <th>Priority</th>
                              <th>Created_at</th>
                              </thead>

                              <tbody>
                              @forelse($tasks as $task)
                                  <tr>
                                      <td>
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox">
                                          </div>
                                      </td>
                                      <td>{{$task->reference}}</td>
                                      <td>{{$task->subject}}</td>
                                      <td>{{$task->priority}}</td>
                                      <td>{{$task->created_at->toFormattedDateString()}}</td>
                                  </tr>
                              @empty

                              @endforelse
                              </tbody>
                          </table>
                      </div>
                  </div>
              </form>
            </div>
        </div>
    </div>
</section>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('#staff_number_from').on('select2:select', function (e) {
                var data = e.params.data;
                $wire.staff_number_from = data.id;
            });
        });
    </script>
@endpush