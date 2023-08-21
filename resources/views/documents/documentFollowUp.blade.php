<table id="listTable" class="table table-bordered">
    <thead>
    <tr>
        <th>User</th>
        <th>Date</th>
        <th>Document Type</th>
        <th>Document No.</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Process No.</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $rec)
        <tr>
            <td>{{$rec->surname}} {{$rec->middle_name ??''}} {{$rec->first_name ??''}}</td>
            <td>{{$rec->date_act}}</td>
            <td>{{$rec->type_document}}</td>
            <td>{{$rec->document_no}}</td>
            <td>{{$rec->amount}}</td>
            <td>{{$rec->status}}</td>
            <td>{{$rec->status}}</td>
            <td>
                {{--<a href="{{URL::signedRoute('show.workshop.requisition', ['ref'=>  $rec->document_no])}}">
                    <i class="fas fa-eye"></i>
                    Open
                </a>--}}
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
