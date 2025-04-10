<div class="card">
    <div class="card-header">
        <h3 class="card-title">Document Audit</h3>

        <div class="card-tools">
            <form class="form-inline" wire:submit.prevent="search">
                <input type="text" class="form-control" placeholder="Search" wire:model="document_no">

                <x-button type="submit" wire:target="search" wire:click="search"
                          class="btn btn-primary btn-sm ml-2">Find
                </x-button>


            </form>

        </div>


    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed text-nowrap table-bordered">
            <thead>
            <tr>
                <th>User</th>
                <th>Date Act</th>
                <th>Document Type</th>
                <th>Document No</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Surname</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($documents as $document)
                <tr>
                    <td>{{$document->user_act}}</td>
                    <td>{{$document->date_act}}</td>
                    <td>{{$document->type_document}}</td>
                    <td>{{$document->document_no}}</td>
                    <td>{{$document->status}}</td>
                    <td>{{$document->amount}}</td>
                    <td>{{$document->surname}}</td>
                    <td>{{$document->first_name}}</td>
                    <td>{{$document->middle_name}}</td>
                    <td>{{$document->description}}</td>


                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{--            <div class="card-footer">--}}
    {{--                {{$vehicles->links()}}--}}
    {{--            </div>--}}


</div>