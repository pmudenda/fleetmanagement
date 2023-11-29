<div class="row">
    <div class="col-lg-12">
        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-workshop-add">Add Workshop</button>
    </div>

    <div class="col-lg-12">
        <table class="table table-bordered table-condensed">
            <thead>
            <th>Name</th>
            <th>Is Supervisor</th>
            <th>Date Added</th>
            <th>Actions</th>
            </thead>
            <tbody>
            @forelse($workshops as $workshop)
                <tr>
                    <td>{{$workshop->workshop_name}}</td>
                    <td>{{$workshop->pivot->is_supervisor->description}}</td>
                    <td>{{$workshop->pivot->created_at->toformattedDateString()}}</td>
                    <td>
                      <div class="btn-toolbar">
                          <x-button class="btn btn-link btn-sm text-danger" wire:click="remove({{$workshop->id}})" wire:target="remove({{$workshop->id}})">remove </x-button>
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
            <tfoot>
            {{$workshops->links()}}
            </tfoot>
        </table>
    </div>

    <div class="modal fade" id="modal-workshop-add" tabindex="-1" role="dialog" aria-labelledby="modal-workshop-add"
         aria-hidden="true">
        <livewire:mechanic.workshop-add :mechanic="$mechanic"/>
    </div>
</div>
