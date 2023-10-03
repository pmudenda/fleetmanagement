@php use App\Helpers\StatusHelper; @endphp
<div>
    <style>
        .processed {
            background-color: #1C9955 !important;
            color: #ffffff !important;
        }

        .pending {
            background-color: #FFDAAF !important;
        }

        .next {
            background-color: #F7801D !important;
        }

        table {
            /* table-layout:fixed; */
            width: 100%;

        }

        . table td {
            border-color: #f78322;
        }

        .arrow {
            border-style: dashed;
            border-color: transparent;
            border-width: 0.20em;
            display: -moz-inline-box;
            display: inline-block;
            /* Use font-size to control the size of the arrow. */
            font-size: 100px;
            height: 0;
            line-height: 0;
            position: static;
            vertical-align: middle;
            width: 0;
            margin-top: 0.25em;
            background-color: #fff;
            /* change background color acc to bg color */
            border-left-width: 0.2em;
            border-left-style: solid;
            border-left-color: #f78322;
            left: 0.25em;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="card-title text-bold text-orange">Workflow</h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-3">
                    <div class="row">
                        <div class="col-11">
                            <div class="card card-body processed"
                                 style="border-radius: 2em;">
                            <span class="font-weight-bold">
                                1. CLAIMANT
                            </span>
                                <table class="table table-sm ">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input type="checkbox" checked="checked">
                                        </td>
                                        <td>
                                            <div class="text-sm">
                                                <h5 class="text-white">{{$claimant->name ?? ''}}</h5>
                                            </div>
                                            <small>
                                                {{$claimant->job_title ?? '-'}}
                                            </small>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-1  ">
                            <span class="arrow"></span>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="row">
                        <div class="col-11">
                            <div class="card card-body
                            @if ($documentStatus == StatusHelper::new())
                                next
                            @elseif ($documentStatus != StatusHelper::new() || in_array($documentStatus,
                                    [StatusHelper::partiallyAuthorised(), StatusHelper::authorised()]))
                                processed
                            @else
                                pending
                            @endif
                            " style="border-radius: 2em;">
                                @if( auth()->user()->can(config('rights.final_authoriser')))
                                    <span class="font-weight-bold">
                                        2. FINAL LEVEL APPROVAL
                                     </span>
                                @else
                                    <span class="font-weight-bold">
                                        2. FIRST LEVEL APPROVAL
                                     </span>
                                @endif

                                <table class="table table-sm ">
                                    <tbody>
                                    <tr>
                                        <td>
                                            @if ($documentStatus != StatusHelper::new()
                                            || in_array($documentStatus,
                                             [StatusHelper::partiallyAuthorised(), StatusHelper::authorised()]))
                                                <input type="checkbox" checked="checked">
                                            @else
                                                <input type="checkbox" disabled>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-sm">
                                                <h5 class="text-white">{{ $supervisor->name ?? ''}}</h5>
                                            </div>
                                            <small>
                                                {{$supervisor->job_title ?? '-'}}
                                            </small>
                                        </td>
                                    </tr>
                                    @if (empty($supervisor))
                                        <tr class="text-danger">
                                            <td><input type="checkbox" disabled></td>
                                            <td> Not Aligned</td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($documentStatus != StatusHelper::new() || in_array($documentStatus,
                            [StatusHelper::partiallyAuthorised(), StatusHelper::authorised()]))
                            <div class="col-1">
                                <span class="arrow"></span>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!empty($manager))
                    )
                    <div class="col-3">
                        <div class="row">
                            <div class="col-11">
                                <div class="card card-body
                            @if ($documentStatus != StatusHelper::new() && !in_array('03', $steps))
                                next
                            @elseif (in_array($documentStatus, [StatusHelper::partiallyAuthorised(),
                            StatusHelper::authorised()]) && in_array('03', $steps))
                                processed
                            @else
                                pending
                            @endif"
                                     style="border-radius: 2em;">
                                <span class="font-weight-bold">
                                        3. SECOND LEVEL APPROVAL
                                </span>
                                    <table class="table table-sm ">
                                        <tbody>
                                        <tr>
                                            <td>
                                                @if ($documentStatus != StatusHelper::new() && in_array('03', $steps))
                                                    <input type="checkbox" checked="checked">
                                                @else
                                                    <input type="checkbox" disabled>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    <h5 class="text-white">{{$manager->name ?? '' }}</h5>
                                                </div>
                                                <small>
                                                    {{$manager->job_title ?? '-'}}
                                                </small>
                                            </td>
                                        </tr>
                                        @if (empty($manager))
                                            <tr class="text-danger">
                                                <td><input type="checkbox" disabled></td>
                                                <td>Not Aligned</td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-1  ">
                                <span class="arrow"></span>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($snrManager))
                    <div class="col-3 d-none">
                        <div class="row">
                            <div class="col-11">
                                <div class="card card-body
                                @if ($documentStatus != StatusHelper::new() && !in_array('03', $steps))
                                next
                            @elseif (in_array($documentStatus, [StatusHelper::partiallyAuthorised(),
                                StatusHelper::authorised()]) && in_array('03', $steps))
                                processed
                            @else
                                pending
                            @endif"
                                     style="border-radius: 2em;">
                                <span class="font-weight-bold">
                                    4. THIRD LEVEL APPROVAL
                                </span>

                                    <table class="table table-sm ">

                                        <tbody>
                                        {{--  @foreach ($dm_unit_users as $item)
                                              <tr>
                                                  <td>
                                                      @if (in_array(config('constants.subsistence_status.station_mgr_approved'),
                                                      $approvals_array))
                                                          <input type="checkbox" checked="checked">
                                                      @else
                                                          <input type="checkbox" disabled>
                                                      @endif
                                                  </td>
                                                  <td> <span class="text-sm"> {{ $item->name }}
                                                          ({{ $item->position->code ?? '-' }} )
                                                      </span>
                                                  </td>
                                              </tr>
                                          @endforeach
                                          @if (sizeOf($dm_unit_users) == 0)
                                              <tr class="text-danger">
                                                  <td><input type="checkbox" disabled></td>
                                                  <td> Not Aligned</td>
                                              </tr>
                                          @endif--}}

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="col-1  ">
                                <span class="arrow"></span>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($deputyDirector))
                    <div class="col-3 d-none">
                        <div class="row">
                            <div class="col-11">
                                <div class="card card-body
                                @if ($documentStatus != StatusHelper::new() && !in_array('03', $steps))
                                next
                            @elseif (in_array($documentStatus, [StatusHelper::partiallyAuthorised(),
                                StatusHelper::authorised()]) && in_array('03', $steps))
                                processed
                            @else
                                pending
                            @endif"
                                     style="border-radius: 2em;">
                            <span class="font-weight-bold">
                                5. FOURTH LEVEL APPROVAL
                            </span>
                                    <table class="table table-sm ">

                                        <tbody>
                                        {{--   @foreach ($hrm_unit_users as $item)
                                               <tr>
                                                   <td>
                                                       @if (in_array(config('constants.subsistence_status.hr_approved'),
                                                        $approvals_array))
                                                           <input type="checkbox" checked="checked">
                                                       @else
                                                           <input type="checkbox" disabled>
                                                       @endif
                                                   </td>
                                                   <td> <span class="text-sm"> {{ $item->name }}
                                                           ({{ $item->position->code ?? '-' }})
                                                       </span>
                                                   </td>
                                               </tr>
                                           @endforeach
                                           @if (sizeOf($hrm_unit_users) == 0)
                                               <tr class="text-danger">
                                                   <td><input type="checkbox" disabled></td>
                                                   <td> Not Aligned</td>
                                               </tr>
                                           @endif--}}

                                        </tbody>
                                    </table>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-1  ">
                                <span class="arrow"></span>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($director))
                    <div class="col-3">
                        <div class="row">
                            <div class="col-11">
                                <div class="card card-body
                                @if ($documentStatus != StatusHelper::new() && !in_array('03', $steps))
                                next
                            @elseif (in_array($documentStatus, [StatusHelper::partiallyAuthorised(),
                                StatusHelper::authorised()]) && in_array('03', $steps))
                                processed
                            @else
                                pending
                            @endif"
                                     style="border-radius: 2em;">
                            <span class="font-weight-bold">
                                5. FIFTH LEVEL APPROVAL
                            </span>
                                    <table class="table table-sm ">
                                        <tbody>
                                        {{--     @foreach ($ca_unit_users as $item)
                                                 <tr>
                                                     <td>
                                                         @if (in_array(
                                                                config('constants.subsistence_status.chief_accountant'),
                                                                $approvals_array))
                                                             <input type="checkbox" checked="checked">
                                                         @else
                                                             <input type="checkbox" disabled>
                                                         @endif
                                                     </td>
                                                     <td> <span class="text-sm"> {{ $item->name }}
                                                             ({{ $item->position->code ?? '-' }})
                                                     </span>
                                                     </td>
                                                 </tr>
                                             @endforeach
                                             @if (sizeOf($ca_unit_users) == 0)
                                                 <tr class="text-danger">
                                                     <td><input type="checkbox" disabled></td>
                                                     <td> Not Aligned</td>
                                                 </tr>
                                             @endif--}}
                                        </tbody>
                                    </table>
                                    {{--<small class="text-info">Based On Trip Cost Center</small>--}}
                                </div>
                            </div>
                            <div class="col-1  ">
                                <span class="arrow"></span>
                            </div>
                        </div>

                    </div>
                @endif

                @if(!empty($managingDirector))
                    <div class="col-3">
                        <div class="row">
                            <div class="col-11">
                                <div class="card card-body
                                 @if ($documentStatus != StatusHelper::new() && !in_array('03', $steps))
                                next
                            @elseif (in_array($documentStatus, [StatusHelper::partiallyAuthorised(),
                                    StatusHelper::authorised()]) && in_array('03', $steps))
                                processed
                            @else
                                pending
                            @endif"
                                     style="border-radius: 2em;">
                            <span class="font-weight-bold">
                                6. SIXTH LEVEL APPROVAL
                            </span>
                                    <table class="table table-sm ">

                                        <tbody>
                                        {{--@foreach ($expenditure_unit_users as $item)
                                            <tr>
                                                <td>
                                                    @if (in_array(config('constants.subsistence_status.chief_accountant')
                                                    , $approvals_array))
                                                        <input type="checkbox" checked="checked">
                                                    @else
                                                        <input type="checkbox" disabled>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-sm"> {{ $item->name }}
                                                        ({{ $item->position->code ?? '-' }})
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (sizeOf($expenditure_unit_users) == 0)
                                            <tr class="text-danger">
                                                <td><input type="checkbox" disabled></td>
                                                <td> Not Aligned</td>
                                            </tr>
                                        @endif--}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <hr/>
            <div class="row">
                <div class="col-3">
                    <p><strong>Key</strong></p>
                    <table>
                        <tr>
                            <td style="width: 5%;">
                                <div style="background-color:#1C9955;width: 15px; height: 15px; margin-bottom: -10px;">
                                </div>
                            </td>
                            <td>
                                <span style="margin-left: 15px;">Processed</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 5%;">
                                <div style="background-color:#F7801D; width: 15px; height: 15px;">
                                </div>
                            </td>
                            <td>
                                <span style="margin-left: 15px;">Next</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 5%;">
                                <div style="background-color:#FFDAAF; width: 15px; height: 15px;">
                                </div>
                            </td>
                            <td>
                                <span style="margin-left: 15px;">Pending</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
