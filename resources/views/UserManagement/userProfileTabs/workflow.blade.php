<!-- Post -->
<div class="post">

    <div class="row">
        <div class="col-2">
            <button class="btn btn-sm btn-outline-success  mb-3"
                    onclick="getMyWorkflow('{{ $user->user_unit_code }}')" required
                    style="width: 100%;">Search
            </button>

        </div>
        <div class="col-4">
            <div id="loader_c_2" style="display: none;">
                <img src=" {{ asset('dashboard/dist/gif/Eclipse_loading.gif')}} "
                     width="100px"
                     alt="Loader"
                     height="100px"/>
            </div>
        </div>
        {{--@if (Auth::user()->type_id == config('constants.user_types.developer') ||
            Auth::user()->type_id == config('constants.user_types.mgt'))
            <div class="col-6">
                <label>Sync Workflow for : </label>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('search-form123').submit();">
                    {{ $user->user_unit->user_unit_description ?? '' }} </a>
                <form id="search-form123"
                      action=""
                      method="post" class="d-none">
                    @csrf
                </form>
            </div>
        @endif--}}
        <div class="col-12">
            <div id="table_body_div">
                <br> <label class="text-green">Director Approval</label>
                <hr>
                <div id="directors_div">
                </div>
                <br> <label class="text-green">Snr Manager Approval</label>
                <hr>
                <div id="divisional_div">
                </div>
                <br> <label class="text-green">Chief Accountant Approval</label>
                <hr>
                <div id="ca_div">
                </div>


                <br> <label class="text-green">HRM Approval</label>
                <hr>
                <div id="hrm_div">
                </div>


                <br> <label class="text-green">HOD Approval</label>
                <hr>
                <div id="hod_div">
                </div>


                <br> <label class="text-green">Audit Approval</label>
                <hr>
                <div id="audit_div">
                </div>


                <br> <label class="text-green">Expenditure Approval</label>
                <hr>
                <div id="expenditure_div">
                </div>

                <br> <label class="text-green">Management Accountants
                    Approval</label>
                <hr>
                <div id="ma_div">
                </div>

                <br> <label class="text-green">Security Approval</label>
                <hr>
                <div id="security_div">
                </div>

                <br> <label class="text-green">Sheq Approval</label>
                <hr>
                <div id="sheq_div">
                </div>

                <br> <label class="text-green">Transport Approval</label>
                <hr>
                <div id="transport_div">
                </div>

                <br> <label class="text-green">Payroll Approval</label>
                <hr>
                <div id="payroll_div">
                </div>

                <br> <label class="text-green">PSA Approval</label>
                <hr>
                <div id="psa_div">
                </div>

                <br> <label class="text-green">PHRO Approval</label>
                <hr>
                <div id="phro_div">
                </div>

                <br> <label class="text-green">Area Manager Approval</label>
                <hr>
                <div id="arm_div">
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.post -->
