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
                                                {{$claimant->name}}
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
                            @elseif (in_array($documentStatus, [StatusHelper::partiallyAuthorised(), StatusHelper::authorised()]))
                                processed
                            @else
                                pending
                            @endif
                            "
                                 style="border-radius: 2em;">
                            <span class="font-weight-bold">
                                2. SUPERVISOR APPROVAL
                            </span>

                                <table class="table table-sm ">

                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="text-sm">
                                                {{ $supervisor->name }}
                                                <small>
                                                    {{$supervisor->job_title ?? '-'}}
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                    {{--   @foreach ($hod_unit_users as $item)
                                           <tr>
                                               <td>
                                                   @if (in_array(config('constants.subsistence_status.hod_approved'), $approvals_array))
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
                                       @endforeach--}}
                                    {{----}}
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
                        <div class="col-1">
                            <span class="arrow"></span>
                        </div>
                    </div>
                </div>


                <div class="col-3 d-none">
                    <div class="row">
                        <div class="col-11">
                            <div class="card card-body"
                                 style="border-radius: 2em;
                 {{--@if ($form_status == config('constants.subsistence_status.hod_approved'))
                    background-color:#F7801D
                    @elseif (in_array(config('constants.subsistence_status.station_mgr_approved'), $approvals_array))
                    background-color:#4E944F
                    @else
                    background-color:#FFDAAF @endif--}}
                ">
                                <span class="font-weight-bold">
                                    3. MANAGER APPROVAL
                                </span>
                                <table class="table table-sm ">

                                    <tbody>
                                    {{--@foreach ($dr_unit_users as $item)
                                        <tr>
                                            <td>
                                                @if (in_array(config('constants.subsistence_status.station_mgr_approved'),
                                                     $approvals_array))
                                                    <input type="checkbox" checked="checked"/>
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
                                    @if (sizeOf($dr_unit_users) == 0)
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


                <div class="col-3 d-none">
                    <div class="row">
                        <div class="col-11">
                            <div class="card card-body"
                                 style="border-radius: 2em;
                 {{--@if ($form_status == config('constants.subsistence_status.hod_approved'))
                    background-color:#F7801D
                    @elseif (in_array(config('constants.subsistence_status.station_mgr_approved'), $approvals_array))
                    background-color:#4E944F
                    @else
                    background-color:#FFDAAF @endif--}}
                ">
                                <span class="font-weight-bold">
                                    4. SNR MGR APPROVAL
                                </span>

                                <table class="table table-sm ">

                                    <tbody>
                                    {{--  @foreach ($dm_unit_users as $item)
                                          <tr>
                                              <td>
                                                  @if (in_array(config('constants.subsistence_status.station_mgr_approved'), $approvals_array))
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


                <div class="col-3 d-none">
                    <div class="row">
                        <div class="col-11">
                            <div class="card card-body"
                                 style="border-radius: 2em;
                 {{--@if ( ($form_status == config('constants.subsistence_status.station_mgr_approved'))
                  || ($form_status == config('constants.subsistence_status.dr_approved'))
                  || ($form_status == config('eform_status.director_approved'))
                  )
                    background-color:#F7801D
                    @elseif (in_array(config('constants.subsistence_status.hr_approved'), $approvals_array))
                    background-color:#4E944F
                    @else
                    background-color:#FFDAAF @endif--}}
                ">
                            <span class="font-weight-bold">
                                5. DEPUTY DIRECTOR APPROVAL
                            </span>
                                <table class="table table-sm ">

                                    <tbody>
                                    {{--   @foreach ($hrm_unit_users as $item)
                                           <tr>
                                               <td>
                                                   @if (in_array(config('constants.subsistence_status.hr_approved'), $approvals_array))
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


                <div class="col-3 d-none">
                    <div class="row">
                        <div class="col-11">
                            <div class="card card-body"
                                 style="border-radius: 2em;
                {{-- @if ($form_status == config('constants.subsistence_status.hr_approved'))
                    background-color:#F7801D
                    @elseif (in_array(config('constants.subsistence_status.chief_accountant'), $approvals_array))
                    background-color:#4E944F
                    @else
                    background-color:#FFDAAF @endif--}}
                ">
                            <span class="font-weight-bold">
                                5. DIRECTOR APPROVAL
                            </span>
                                <table class="table table-sm ">
                                    <tbody>
                                    {{--     @foreach ($ca_unit_users as $item)
                                             <tr>
                                                 <td>
                                                     @if (in_array(config('constants.subsistence_status.chief_accountant'), $approvals_array))
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


                <div class="col-3 d-none">
                    <div class="row">
                        <div class="col-11">
                            <div class="card card-body"
                                 style="border-radius: 2em;
                 {{--@if ($form_status == config('constants.subsistence_status.chief_accountant'))
                    background-color:#F7801D
                    @elseif (in_array(config('constants.subsistence_status.chief_accountant'), $approvals_array))
                     background-color:#4E944F
                    @else
                    background-color:#FFDAAF @endif--}}
                ">
                            <span class="font-weight-bold">
                                6. MANAGING DIRECTOR
                            </span>
                                <table class="table table-sm ">

                                    <tbody>
                                    {{--@foreach ($expenditure_unit_users as $item)
                                        <tr>
                                            <td>
                                                @if (in_array(config('constants.subsistence_status.chief_accountant'), $approvals_array))
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
                                {{--   <small class="text-info">Based On Trip Cost Center</small>--}}
                            </div>
                        </div>
                    </div>
                </div>

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
                {{--   <div class="col-4">

                       @if (
                             \App\Helpers\Authorise::hasDeveloperUserType(auth()->user())
                             ||
                             \App\Helpers\Authorise::hasChiefAccountantRole(auth()->user())
                             )
                           <a href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                                          document.getElementById('search-form123-24').submit();">
                               <p><strong>Departmental Approvals</strong></p></a>
                           <form id="search-form123-24"
                                 action="{{ route('main.user.unit.search.profile', $form_details->claimantUserUnit->id) }}"
                                 method="post" class="d-none">
                               @csrf
                           </form>

                       @else
                           <p><strong>Departmental Approvals</strong></p>
                       @endif

                       <table>
                           <tr>
                               <td>
                                   <span class="text-bold text-sm">HOD  |  SNR MGR  |  HC</span>
                               </td>
                           </tr>
                       </table>
                       <table>
                           <tr>
                               <td>
                               <span
                                       class=" text-sm"> {{ $form_details->claimantUserUnit->user_unit_description ?? '-' }}</span>
                               </td>
                               <td>
                               <span
                                       class=" text-sm">Code : {{ $form_details->claimantUserUnit->user_unit_code ?? '-' }}</span>
                               </td>
                               <td>
                               <span
                                       class=" text-sm">BU : {{ $form_details->claimantUserUnit->user_unit_bc_code ?? '-' }}</span>
                               </td>
                               <td>
                               <span
                                       class=" text-sm">CC : {{ $form_details->claimantUserUnit->user_unit_cc_code ?? '-' }}</span>
                               </td>
                           </tr>
                       </table>
                   </div>--}}
                {{--<div class="col-3">

                    @if (
                          \App\Helpers\Authorise::hasDeveloperUserType(auth()->user())
                          ||
                          \App\Helpers\Authorise::hasChiefAccountantRole(auth()->user())
                          )
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                       document.getElementById('search-form123-23').submit();">
                            <p><strong>Payment Approvals</strong></p></a>
                        <form id="search-form123-23"
                              action="{{ route('main.user.unit.search.profile', $form_details->user_unit->id) }}"
                              method="post" class="d-none">
                            @csrf
                        </form>

                    @else
                        <p><strong>Payment Approvals</strong></p>
                    @endif

                    <table>
                        <tr>
                            <td>
                                <span class="text-bold  text-sm">Chief Accountant  |  Expenditure</span>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td>
                                <span class=" text-sm"> {{ $form_details->user_unit->user_unit_description ?? '-' }}</span>
                            </td>
                            <td>
                                <span class=" text-sm">Code : {{ $form_details->user_unit->user_unit_code ?? '-' }}</span>
                            </td>
                            <td>
                                <span class=" text-sm">BU : {{ $form_details->user_unit->user_unit_bc_code ?? '-' }}</span>
                            </td>
                            <td>
                            <span
                                    class=" text-sm">CC  : {{ $form_details->claimantUserUnit->user_unit_cc_code ?? '-' }}</span>
                            </td>
                            <td>
                            <span
                                    class=" text-sm">OU  : {{ $form_details->claimantUserUnit->operating->org_id ?? '--' }}</span>
                            </td>

                        </tr>
                    </table>
                </div>--}}
            </div>
        </div>
    </div>
</div>
