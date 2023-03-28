<!--begin::Form-->
<form id="tms_assignment_tab_form"
      v-on:submit.prevent="submitAssignmentDetails"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('api.vehicle.new')}}">
    <input type="hidden" name="doctype" value="AssignmentDetails"/>
    <input type="hidden" name="headerId" v-model="vehicleHeaderId"/>
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
                                <input type="text"
                                       class="input-with-feedback form-control bold"
                                       maxlength="140"
                                       id="businessArea"
                                       name="businessArea"
                                       v-model="assignmentDetails.businessArea"
                                       placeholder=""
                                       data-doctype="AssignmentDetails"/>
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
                                <input type="text"
                                       class="input-with-feedback form-control bold"
                                       id="directorate"
                                       name="directorate"
                                       list="directorates_list"
                                       v-model="assignmentDetails.directorate"
                                       placeholder="Directorate"/>

                                <datalist id="directorates_list">
                                    <option v-for="directorate in directorates"
                                            :value="directorate.id+' : '+directorate.name"/>
                                </datalist>
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
                                       class="input-with-feedback form-control bold"
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
                                       class="input-with-feedback form-control bold"
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
                            <label class="form-check-inline"> <input type="radio"
                                                                     class="list-row-checkbox bold"
                                                                     name="isPoolVehicle"
                                                                     value="Y"
                                                                     checked
                                                                     v-model="assignmentDetails.isOperationsVehicle"
                                                                     placeholder=""
                                                                     data-target="Company">
                                Yes</label>
                            <label class="form-check-inline"> <input type="radio"
                                                                     class="list-row-checkbox bold"
                                                                     name="isPoolVehicle"
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

        <tr>
            <td class="frappe-control ">
                <label for="operatorSupervisorStaffNumber" class="control-label reqd"
                       style="padding-right: 0px;">
                    Supervisor:
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
                                            <div class="input-group">
                                                <input type="text"
                                                       class="input-with-feedback form-control bold"
                                                       required
                                                       title="Enter Staff number in previous input and name will auto populate"
                                                       id="operatorSupervisorStaffNumber"
                                                       name="operatorSupervisorStaffNumber"
                                                       v-model="assignmentDetails.superVisorStaffNumber"
                                                       placeholder=""
                                                       list="employee_list"
                                                       data-emp="staff_number"
                                                       data-doctype="AssignmentDetails"
                                                       autocomplete="off"/>
                                                <div class="input-group-btn align-self-center">
                                                    <button type="button" class="btn btn-sm btn-primary" style="border-radius: 0; height: 31px;">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="width:70%">
                            <div class="control-input">
                                <div class="link-field ui-front"
                                     style="position: relative;">
                                    <div>
                                        <input type="text"
                                               class="input-with-feedback form-control bold"
                                               required
                                               id="superVisorName"

                                               title="Enter Staff number in previous input and name will auto populate"
                                               name="superVisorName"
                                               v-model="assignmentDetails.superVisorName"
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
            <td class="frappe-control"></td>
            <td></td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="operator" class="control-label reqd"
                       style="padding-right: 0px;">
                    Operator:
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
                                                   id="operatorStaffNumber"
                                                   name="operatorStaffNumber"
                                                   v-model="assignmentDetails.operatorStaffNumber"
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
                                               maxlength="200"
                                               id="operatorName"

                                               required
                                               title="Enter Staff number in previous input and name will auto populate"
                                               name="operatorName"
                                               v-model="assignmentDetails.operatorName"
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

        <tr>
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
                            <label class="form-check-inline"> <input type="radio"
                                                                     class="list-row-checkbox bold"
                                                                     name="isMileageExempt"
                                                                     value="Y"
                                                                     disabled
                                                                     :checked="assignmentDetails.isOperationsVehicle == 'N'"
                                                                     v-model="assignmentDetails.mileageExempt"
                                                                     placeholder=""
                                                                     data-target="Company">
                                Yes</label>
                            <label class="form-check-inline"> <input type="radio"
                                                                     class="list-row-checkbox bold"
                                                                     name="isMileageExempt"
                                                                     value="N"
                                                                     :checked="assignmentDetails.isOperationsVehicle == 'Y'"
                                                                     disabled
                                                                     v-model="assignmentDetails.mileageExempt"
                                                                     placeholder=""
                                                                     data-target="Company">
                                No</label>
                        </div>
                    </div>
                </div>
            </td>

            <td></td>
            <td></td>
        </tr>

        </tbody>
    </table>


    <datalist id="employee_list">
        <option v-for="employee in searchedEmployeesList"
                :value="employee.staff_number"> @{{ employee.staff_number}}:@{{
            employee.name}}
        </option>
    </datalist>

    <div>
        <button type="submit" id="tms_save_assignment" class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane"></i>
            <span class="indicator-label">Save</span>
            <span class="indicator-progress">Please wait...<span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
        </button>
    </div>
</form>
