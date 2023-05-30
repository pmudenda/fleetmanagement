@php use Carbon\Carbon; @endphp
<div class="card-header bg-success">
    <h4 class="card-title text-white">Approval Routing History</h4>
    <span class="badge badge-secondary right ml-2">
        @if($approvals != null)
            {{$approvals->count()}}
        @else
            0
        @endif
    </span>
    <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
        </button>
    </div>
</div>
<div class="card-body">

    <table id="dataTable" class="table table-hover table">
        <thead class="">
        <tr>
            <th></th>
            <th>User</th>
            <th>Signature</th>
            <th>Activity</th>
            <th>Status</th>
            <th>Comments</th>
            <th>Date</th>
            <th>SLA</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($approvals))
            @foreach($approvals as $key => $item)
                <tr>
                    <td>
                        @if(!empty($item->name) && $item->name === 'System')
                            <i class="fas fa-cog" style="font-size: 35px;"></i>
                        @else
                            <img style="height:35px;"
                                 src="{{asset('assets/media/avatars/avatar.png')}}"
                                 class="img-circle elevation-2"
                                 alt="User Image"/>
                            {{--@if()
                                 <img style="height:50px;"
                                      src="{{asset('storage/user_avatar/'.Auth::user()->avatar)}}"
                                      class="img-circle elevation-2"
                                      alt="User Image"
                                      onerror="this.src='{{asset('dashboard/dist/img/avatar.png')}}';"
                                 >
                             @else
                                 <img style="height:50px;"
                                      src="{{asset('dashboard/dist/img/avatar.png')}}"
                                      class="img-circle elevation-2"
                                      alt="User Image"/>
                             @endif--}}
                        @endif
                    </td>
                    <td style="text-transform: capitalize;">
                        {{strtolower($item->name)}}
                    </td>
                    <td>{{$item->actioning_officer}}</td>
                    <td style="text-transform: capitalize;">
                        @if($key == 0)
                            Create Document
                        @else
                            @if(str_contains(strtolower($item->action), 'approved'))
                                Approve
                            @elseif(str_contains(strtolower($item->action), 'subscribe'))
                                Subscribe
                            @elseif(str_contains(strtolower($item->action), 'rejected'))
                                Reject
                            @elseif(str_contains(strtolower($item->action), 'cancelled'))
                                Cancel
                            @elseif(str_contains(strtolower($item->action), 'queried'))
                                Query
                            @elseif(str_contains(strtolower($item->action), 'resolve'))
                                Resolve
                            @else
                                {{strtolower($item->action) ?? ""}}
                            @endif
                        @endif
                    </td>
                    <td style="text-transform: capitalize;">
                        @if($key == 0)
                            New Application
                        @else
                            {{strtolower($item->status) ?? ""}}
                        @endif
                    </td>
                    <td style="text-transform: capitalize; display: none;">
                        {{--                        @if($key == 0)--}}
                        {{strtolower($item->status) ?? ""}}
                        {{--                        @else--}}
                        {{--                            {{strtolower($item->to_status->name) ?? ""}}--}}
                        {{--                        @endif--}}
                    </td>
                    <td>{{$item->remarks}}</td>
                    <td>{{Carbon::parse($item->created_at)->format('d/m/Y')}}</td>
                    <td>
                        {{Carbon::parse($item->created_at)->diffAsCarbonInterval($item->action_date)}}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">No Data Found</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
