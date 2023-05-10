<!--begin::Form-->
<form
    id="tms_assignment_tab_form"
    name="tms_assignment_form"
    class="form fv-plugins-bootstrap5 fv-plugins-framework"
    action="{{route('vehicle.assignment.detail')}}">
    <input type="hidden" name="doctype" value="AssignmentDetails"/>
    <input type="hidden" name="headerId" value="{{$reference ?? 0}}"/>
    <input type="hidden" name="assignmentId" value="{{$vehicle->assignmentId ?? 0}}"/>
    <x-error-view/>
    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
        <tbody>

        <tr>
            <td class="frappe-control ">
                <label for="businessArea" class="control-label reqd"
                       style="padding-right: 0px;">
                    Business Area:
                </label>
            </td>
            <td colspan="1">
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <select
                                    class="form-select form-select-sm view_mode"
                                    maxlength="140"
                                    id="businessArea"
                                    name="businessArea"
                                    v-model="assignmentDetails.businessArea"
                                    data-doctype="AssignmentDetails">
                                    <option value=""></option>
                                    <option v-for="businessArea in businessAreas" :key="businessArea.code"
                                            :value="businessArea.code">
                                        @{{ businessArea.name }}
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">

            </td>
            <td></td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="directorate" class="control-label reqd"
                       style="padding-right: 0px;">
                    Directorate:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <select
                                    class="form-select form-select-sm view_mode"
                                    id="directorate"
                                    name="directorate"
                                    v-model="assignmentDetails.directorate">
                                    <option value>--Directorate--</option>
                                    <option v-for="directorate in directorates" :value="directorate.id">
                                        @{{ directorate.name }}
                                    </option>
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">

            </td>
            <td>

            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="businessUnit" class="control-label reqd"
                       style="padding-right: 0px;">
                    Business Unit:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback form-control bold view_mode"
                                       id="businessUnit"
                                       required
                                       readonly
                                       name="businessUnit"
                                       v-model="assignmentDetails.businessUnit"
                                       placeholder="Business Unit"/>

                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td class="frappe-control">
                <div class="clearfix">
                    <label for="costCenter" class="control-label reqd"
                           style="padding-right: 0px;">
                        Cost Center:
                    </label>
                    <span class="help"></span>
                </div>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback form-control bold view_mode"
                                       placeholder="Cost Center"
                                       readonly
                                       v-model="assignmentDetails.costCenter"
                                       name="costCenter"
                                       id="costCenter"
                                       data-doctype="AssignmentDetails"/>
                            </div>

                        </div>
                    </div>
                    <p class="help-box small text-muted"></p>
                </div>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label class="control-label reqd"
                       style="padding-right: 0px;">
                    Is this an Operational Vehicle:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <label class="form-check-inline">
                                <input type="radio"
                                       class="list-row-checkbox bold view_mode"
                                       id="isPoolVehicle"
                                       name="isPoolVehicle"
                                       value="Y"
                                       v-model="assignmentDetails.isOperationsVehicle"
                                       placeholder=""
                                       data-target="Company">
                                Yes
                            </label>
                            <label class="form-check-inline"> <input type="radio"
                                                                     class="list-row-checkbox bold view_mode"
                                                                     name="isPoolVehicle"
                                                                     id="isNotPoolVehicle"
                                                                     value="N"
                                                                     v-model="assignmentDetails.isOperationsVehicle"
                                                                     placeholder=""
                                                                     data-target="Company">
                                No</label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr v-show="assignmentDetails.isOperationsVehicle==='Y'">
            <td class="frappe-control ">
                <label for="operatorSupervisorStaffNumber" class="control-label reqd"
                       style="padding-right: 0px;">
                    Responsible Head:
                </label>
            </td>
            <td>
                <div class="input-group">
                    <input type="text"
                           id="responsibleHOD"
                           data-bs-toggle="modal"
                           autocomplete="off"
                           data-bs-target="#searchEmployeeModal"
                           data-assignmenttype="single"
                           data-inputfield="responsibleHOD"
                           name="responsibleHOD"
                           class="form-control view_mode"
                           value="{{$vehicle->responsible_head_name ?? ''}}"
                           data-emp="staff_number"
                           data-doctype="AssignmentDetails"
                    />

                    <input type="hidden"
                           data-assignmenttype="single"
                           data-inputfield="responsibleHODId"
                           id="responsibleHODId"
                           value="{{$vehicle->responsible_head_id ?? ''}}"
                           name="responsibleHODId"/>

                    <div class="input-group-append input-group-sm">
                        <button type="button"
                                data-assignmenttype="single"
                                data-inputfield="responsibleHOD"
                                data-field="userSelection"
                                class="input-group-text view_mode">
                            <i class="fa fa-user"></i>
                        </button>
                        <button type="button"
                                data-action="clearUsers"
                                class="input-group-text view_mode">
                            <i class="fa fa-eraser"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td class="frappe-control"></td>
            <td></td>
        </tr>

        <tr v-show="assignmentDetails.isOperationsVehicle==='N'">
            <td class="frappe-control ">
                <label for="operator" class="control-label reqd"
                       style="padding-right: 0px;">
                    Assigned To:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front"
                             style="position: relative;">
                            <div class="input-group">
                                <input type="text"
                                       id="vehicleHolder"
                                       data-bs-toggle="modal"
                                       data-bs-target="#searchEmployeeModal"
                                       data-assignmenttype="single"
                                       data-inputfield="vehicleHolder"
                                       name="vehicleHolder"
                                       value="{{$vehicle->responsible_head_name ?? ''}}"
                                       class="form-control view_mode"
                                       data-emp="staff_number"
                                       data-doctype="AssignmentDetails"
                                       autocomplete="off"
                                />

                                <input type="hidden"
                                       data-assignmenttype="single"
                                       data-inputfield="vehicleHolderId"
                                       value="{{$vehicle->responsible_head_id ?? ''}}"
                                       id="vehicleHolderId"
                                       name="vehicleHolderId"/>

                                <div class="input-group-append input-group-sm">
                                    <button type="button"
                                            data-assignmenttype="single"
                                            data-inputfield="vehicleHolder"
                                            data-field="userSelection"
                                            class="input-group-text view_mode">
                                        <i class="fa fa-user"></i>
                                    </button>
                                    <button type="button"
                                            data-action="clearUsers"
                                            class="input-group-text view_mode">
                                        <i class="fa fa-eraser"></i>
                                    </button>
                                </div>
                            </div>
                            {{--<div>
                                <input type="text"
                                       class="input-with-feedback form-control bold"
                                       required
                                       title="Enter Staff number in previous input and name will auto populate"
                                       id="operatorStaffNumber"
                                       name="operatorStaffNumber"
                                       v-model="assignmentDetails.operatorStaffNumber"
                                       placeholder=""
                                       list="employee_list"
                                       data-emp="staff_number"
                                       data-doctype="AssignmentDetails"
                                       autocomplete="off"/>

                            </div>--}}
                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control"></td>
            <td>
            </td>
        </tr>

        <tr v-if="assignmentDetails.isOperationsVehicle==='Y'">
            <td class="frappe-control ">
                <label class="control-label reqd"
                       style="padding-right: 0px;">
                    Mileage Exempt:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <label class="form-check-inline">
                                <input type="radio"
                                       checked
                                       class="list-row-checkbox bold view_mode"
                                       name="isMileageExempt"
                                       readonly
                                       value="N"
                                       placeholder=""
                                       data-target="Company">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </td>

            <td></td>
            <td></td>
        </tr>

        <tr v-if="assignmentDetails.isOperationsVehicle==='N'">
            <td class="frappe-control ">
                <label class="control-label reqd"
                       style="padding-right: 0px;">
                    Mileage Exempt:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <label class="form-check-inline">
                                <input type="radio"
                                       class="list-row-checkbox bold view_mode"
                                       name="isMileageExempt"
                                       value="Y"
                                       checked
                                       placeholder=""
                                       data-target="Company">
                                Yes</label>
                        </div>
                    </div>
                </div>
            </td>

            <td></td>
            <td></td>
        </tr>


        {{--      <tr>
                  <td class="frappe-control ">
                      <label for="operator" class="control-label reqd"
                             style="padding-right: 0px;">
                          Casual Operator:
                      </label>
                  </td>
                  <td>
                      <table>
                          <tr>
                              <td>
                                  <div class="control-input-wrapper">
                                      <div class="control-input">
                                          <div class="link-field ui-front"
                                               style="position: relative;">
                                              <div>
                                                  <input type="text"
                                                         class="input-with-feedback form-control bold"
                                                         required
                                                         title="Enter Staff number in previous input and name will auto populate"
                                                         id="casual_staff_number"
                                                         name="casual_staff_number"
                                                         v-model="assignmentDetails.casualStaffNumber"
                                                         placeholder=""
                                                         list="employee_list"
                                                         data-emp="staff_number"
                                                         data-doctype="AssignmentDetails"
                                                         autocomplete="off"/>

                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </td>
                              <td style="width:80%">
                                  <div class="control-input">
                                      <div class="link-field ui-front"
                                           style="position: relative;">
                                          <div>
                                              <input type="text"
                                                     class="input-with-feedback form-control bold"
                                                     required

                                                     id="casual_staff_name"
                                                     title="Enter Staff number in previous input and name will auto populate"
                                                     name="casual_staff_name"
                                                     v-model="assignmentDetails.casualStaffName"
                                                     placeholder=""
                                                     data-emp="name"
                                                     data-doctype="AssignmentDetails"
                                                     autocomplete="off"/>

                                          </div>
                                      </div>
                                  </div>
                              </td>
                          </tr>
                      </table>
                  </td>
                  <td class="frappe-control">

                  </td>
                  <td>
                  </td>
              </tr>--}}

        {{--  <tr>
              <td class="frappe-control ">
                  <label for="assignedToTeam" class="control-label reqd"
                         style="padding-right: 0px;">
                      Team:
                  </label>
              </td>
              <td>
                  <div class="control-input-wrapper">
                      <div class="control-input">
                          <div class="link-field ui-front" style="position: relative;">
                              <label class="form-check-inline"> <input type="radio"
                                                                       class="list-row-checkbox bold"
                                                                       name="isTeamAssigned"
                                                                       value="Y"
                                                                       v-model="assignmentDetails.isTeamAssigned"
                                                                       placeholder=""
                                                                       data-target="Company">
                                  Yes</label>
                              <label class="form-check-inline"> <input type="radio"
                                                                       class="list-row-checkbox bold"
                                                                       name="isTeamAssigned"
                                                                       value="N"
                                                                       checked
                                                                       v-model="assignmentDetails.isTeamAssigned"
                                                                       placeholder=""
                                                                       data-target="Company">
                                  No</label>
                          </div>
                      </div>
                  </div>
              </td>

          </tr>--}}
        </tbody>
    </table>

    <datalist id="employee_list">
        <option v-for="employee in searchedEmployeesList"
                :value="employee.staff_number"> @{{ employee.staff_number}}:@{{
            employee.name}}
        </option>
    </datalist>

    <div class="create_mode">
        <button type="submit" id="tms_save_assignment" class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane"></i> Save
        </button>
    </div>
</form>
