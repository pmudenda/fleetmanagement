<table id="listTable" class="table table-bordered">
    <thead>
    <tr>
        <th>Reference #</th>
        <th>Document No.</th>
        {{-- <th>Registration</th>--}}
        <th>Date In</th>
        <th>Date Expected Out</th>
        <th>Originator</th>
        {{--<th>Qty. Requested</th>--}}
        {{--<th>Qty. Issued</th>--}}
        <th>Status</th>
        <th>Remarks</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($requisitions as $rec)
        <tr>
            <td>
                <a href="{{URL::signedRoute('show.workshop.requisition', ['ref'=>  $rec->req_no])}}">
                    {{$rec->req_no ?? ''}}
                </a>
            </td>

            <td>
                {{$rec->st_pur ?? ''}}
            </td>


            {{--  <td>
                  {{$rec->veh_reg_no ?? ''}}
              </td>--}}
            <td>
                {{Carbon::parse($rec->valid_date_from)->format('d/m/Y')}}
            </td>
            <td>
                {{Carbon::parse($rec->valid_date_to)->format('d/m/Y')}}
            </td>
            <td>
                {{$rec->originator?? '--'}}
            </td>

            {{-- <td>
                 {{$rec->quantity}}
             </td>--}}

            {{-- <td>
                 {{$rec->quantity_issued ?? 0}}
             </td>
             --}}

            <td>
                {{$rec->status_name ?? ''}}
            </td>
            <td>
                {{$rec->comments ?? ''}}
            </td>

            <td>
                <a href="{{URL::signedRoute('show.workshop.requisition', ['ref'=>  $rec->req_no])}}">
                    <i class="fas fa-eye"></i>
                    Open
                </a>
            </td>

        </tr>
    @endforeach
    </tbody>
</table>
