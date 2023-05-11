<form
    id="tms_chassis_details_form"
    name="tmsChassisDetailsForm"
    class="form"
    action="{{route('vehicle.chassis.detail')}}">
    <input type="hidden" name="doctype" value="ChassisDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="chassisDetailsId" value="{{$vehicle->chassisDetailsId ?? 0}}"/>
    <x-error-view/>
    <div class="row">
        <div class="col-8">
            <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                <tbody>
                <tr>
                    <td class="frappe-control ">
                        <label for="chassisNumber" class="control-label reqd"
                               style="padding-right: 0px;">
                            Chassis #:
                        </label>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <input type="text"
                                               required
                                               id="chassisNumber"
                                               name="chassisNumber"
                                               v-model="chassisDetails.chassisNumber"
                                               class="input-with-feedback form-control bold view_mode"
                                               maxlength="140"
                                               @change="checkChassisNumberValidity"
                                               data-fieldtype="Link"
                                               data-fieldname="company"
                                               placeholder=""
                                               data-doctype="ChassisDetails"
                                               data-target="Company"
                                               autocomplete="off" role="combobox"/>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="frappe-control">
                        <div class="clearfix">
                            <label for="engineNumber" class="control-label reqd"
                                   style="padding-right: 0px;">Engine #:</label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <input type="text"
                                               required
                                               class="input-with-feedback form-control view_mode"
                                               maxlength="140" data-fieldtype="Link"
                                               data-fieldname="company" id="engineNumber"
                                               name="engineNumber"
                                               v-model="chassisDetails.engineNumber" placeholder=""
                                               data-doctype="ChassisDetails"
                                               autocomplete="off"/>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="frappe-control ">
                        <label for="whiteBookSerial" class="control-label reqd"
                               style="padding-right: 0px;">
                            White Book Serial #:
                        </label>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <input type="text"
                                               class="input-with-feedback form-control view_mode"
                                               maxlength="50"
                                               required
                                               data-fieldname="company"
                                               id="whiteBookSerial"
                                               name="whiteBookSerial"
                                               v-model="chassisDetails.whiteBookSerial"
                                               placeholder=""
                                               autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="frappe-control">
                        <div class="clearfix" style="display: none;">
                            <label for="stickerRegistrationNumber" class="control-label"
                                   style="padding-right: 0px;">
                                Sticker #:
                            </label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        <div class="control-input-wrapper" style="display: none;">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <input type="text"
                                               class="input-with-feedback form-control view_mode"
                                               maxlength="140"
                                               v-model="chassisDetails.stickerRegistrationNumber"
                                               name="stickerRegistrationNumber"
                                               id="stickerRegistrationNumber"
                                               placeholder=""
                                               data-doctype="ChassisDetails"
                                               autocomplete="off"/>
                                    </div>

                                </div>
                            </div>
                            <p class="help-box small text-muted"></p>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="frappe-control ">
                        <label for="yearOfManufacture" class="control-label reqd"
                               style="padding-right: 0px;">
                            Year Manufactured:
                        </label>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <input
                                            date-format="YYYY"
                                            class="input-with-feedback form-control bold number_input view_mode"
                                            type="number" min="1990" max="{{date('Y')}}" step="1"
                                            required
                                            id="yearOfManufacture"
                                            name="yearOfManufacture"
                                            v-model="chassisDetails.yearOfManufacture"
                                            placeholder=""
                                            data-doctype="ChassisDetails"/>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="frappe-control">
                        <div class="clearfix">
                            <label for="registrationDate" class="control-label reqd"
                                   style="padding-right: 0px;">Reg. Date:</label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        {{--min="{{ date('Y-m-d', strtotime($form->trip->date_to)) }}"--}}
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <input type="date"
                                               max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                               required
                                               class="input-with-feedback form-control view_mode"
                                               data-fieldname="registrationDate"
                                               name="registrationDate"
                                               id="registrationDate"
                                               v-model="chassisDetails.registrationDate"
                                               placeholder=""
                                               data-doctype="ChassisDetails"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr class="d-none">
                    <td>
                        <div class="clearfix">
                            <label for="dateOnRoad" class="control-label"
                                   style="padding-right: 0px;">
                                Date on road :
                            </label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <input type="date" name="dateOnRoad" id="dateOnRoad"
                                       disabled
                                       autocomplete="off"
                                       class="input-with-feedback form-control view_mode"
                                       data-fieldtype="Datetime"
                                       data-fieldname="first_date_on_road"
                                       placeholder=""
                                       data-doctype="ChassisDetails"/>
                            </div>

                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="frappe-control ">
                        <label for="chargeOutRate" class="control-label reqd"
                               style="padding-right: 0px;">
                            Charge-Out Rate:
                        </label>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="input-group bg-gray-300">

                                        <input type="text"
                                               name="chargeOutRate"
                                               id="chargeOutRate"
                                               class="input-with-feedback form-control view_mode"
                                               required
                                               v-model="chassisDetails.chargeOutRate"
                                               placeholder=""
                                               v-on:change="formatMoney($event)"
                                               data-doctype="ChassisDetails"
                                               autocomplete="off"/>
                                        <div
                                            class="input-group-append align-self-center pl-3 pr-2">
                                            /Km
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </td>
                    <td class="frappe-control" colspan="1">
                        <div class="clearfix">
                            <label for="requiredMinimumDrivingLicense" class="control-label reqd"
                                   style="padding-right: 0px;">Minimum Driving License:</label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <select class="form-select form-select-sm view_mode"
                                                required
                                                name="requiredMinimumDrivingLicense"
                                                id="requiredMinimumDrivingLicense"
                                                v-model="chassisDetails.requiredMinimumDrivingLicense"
                                                data-doctype="ChassisDetails"
                                                :placeholder="'License Class'"
                                        >
                                            <option>--Select Licence Class--</option>
                                            <option v-for="licenseClass in licenseTypes" :value="licenseClass.code">
                                                @{{ licenseClass.name}}
                                            </option>
                                        </select>

                                        {{-- <input type="hidden"
                                                class="input-with-feedback form-control bold"
                                                required
                                                data-fieldtype="Link"
                                                :value="chassisDetails.requiredMinimumDrivingLicense"
                                                placeholder=""
                                                autocomplete="licenseTypes"/>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="frappe-control ">
                        <label for="initialOdometerReading" class="control-label reqd"
                               style="padding-right: 0px;">
                            Initial Odometer Reading:
                        </label>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <input type="text"
                                           v-model="chassisDetails.initialOdometerReading"
                                           name="initialOdometerReading"
                                           id="initialOdometerReading"
                                           class="input-with-feedback number_input form-control view_mode"
                                           placeholder=""
                                           required
                                           data-doctype="ChassisDetails"
                                           autocomplete="off"/>
                                </div>
                            </div>

                        </div>
                    </td>
                    <td class="frappe-control" colspan="1">
                        <div class="clearfix" style="display: none;">
                            <label for="currentOdometerReading" class="control-label reqd"
                                   style="padding-right: 0px;">Km Done:</label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        <div class="control-input-wrapper" style="display: none;">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <div class="">
                                        <input type="text"
                                               class="input-with-feedback number_input form-control view_mode"
                                               required
                                               name="currentOdometerReading"
                                               id="currentOdometerReading"
                                               value="0"
                                               {{--v-model="chassisDetails.currentOdometerReading"--}}
                                               placeholder=""
                                               data-doctype="ChassisDetails"
                                               autocomplete="off"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr style="display: none;">
                    <td class="frappe-control ">
                        <label for="odometerReadingLastService" class="control-label reqd"
                               style="padding-right: 0px;">
                            Odometer Reading Last Service
                        </label>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <input type="text"
                                           name="odometerReadingLastService"
                                           id="odometerReadingLastService"
                                           value="0"
                                           {{--v-model="chassisDetails.odometerReadingLastService"--}}
                                           class="input-with-feedback number_input form-control bold view_mode"
                                           required
                                           placeholder=""
                                           data-doctype="ChassisDetails"
                                           autocomplete="off">
                                </div>
                            </div>

                        </div>
                    </td>
                    <td class="frappe-control" colspan="1">
                        <div class="clearfix">
                            <label for="nextServiceOdometerReading" class="control-label reqd"
                                   style="padding-right: 0px;">Next Service Odometer
                                Reading:</label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">

                                    <input type="text"
                                           class="input-with-feedback number_input form-control bold"
                                           required
                                           name="nextServiceOdometerReading"
                                           id="nextServiceOdometerReading"
                                           value="0"
                                           {{--v-model="chassisDetails.nextServiceOdometerReading"--}}
                                           placeholder="" data-doctype="ChassisDetails"
                                           autocomplete="off"/>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr style="display: none;">
                    <td class="frappe-control ">
                        <label for="inspectionDate" class="control-label reqd"
                               style="padding-right: 0px;">
                            Inspection Date:
                        </label>
                    </td>
                    <td>
                        <div class="control-input-wrapper">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <input type="date"
                                           max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                           v-model="chassisDetails.inspectionDate"
                                           name="inspectionDate"
                                           id="inspectionDate"
                                           value="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                           required
                                           class="input-with-feedback form-control bold view_mode"
                                           placeholder=""
                                           data-doctype="ChassisDetails"
                                           autocomplete="off">
                                </div>
                            </div>

                        </div>
                    </td>
                    <td class="frappe-control" colspan="1">
                        <div class="clearfix" style="display: none;">
                            <label for="odometerReset" class="control-label"
                                   style="padding-right: 0px;">
                                Odometer Reset:</label>
                            <span class="help"></span>
                        </div>
                    </td>
                    <td>
                        <div class="control-input-wrapper" style="display: none;">
                            <div class="control-input">
                                <div class="link-field ui-front" style="position: relative;">
                                    <input type="checkbox"
                                           class="input-with-feedback form-check-input bold"
                                           disabled
                                           name="odometerReset"
                                           id="odometerReset"
                                           v-model="chassisDetails.odometerReset"
                                           placeholder=""
                                           data-doctype="ChassisDetails"/>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="col-4 create_mode">
            <div class="row">
                <div class="form-group row">
                    <label for="inspectionDate" class="fs-6 fw-semibold form-label reqd col-md-5"
                           style="padding-right: 0px;">
                        Motor Vehicle Certificate:
                    </label>
                    <div class="col-md-7 fv-row">
                        <div class="col-md-9">
                            <input type="file" accept="image/*,.pdf"
                                   required
                                   name="motor_vehicle_certificate"/>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inspectionDate" class="fs-6 fw-semibold form-label reqd col-md-5"
                           style="padding-right: 0px;">
                        Insurance Cover Note:
                    </label>
                    <div class="col-md-7 fv-row">
                        <div class="col-md-9">
                            <input type="file" accept="image/*,.pdf"
                                   required
                                   name="insurance_cover_note"/>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row mt-10" v-if="documents && documents.insurance && documents.certificate">
        <table class="table align-middle table-row-dashed dataTable no-footer">
            <thead>
            <tr class="bg-dark">
                <th>Document Type</th>
                <th>File Name</th>
                <th></th>
            </tr>
            </thead>
            <tr>
                <td>Motor Vehicle Certificate</td>
                <td>@{{ documents.certificate?.originalDocumentName }}</td>
                <td>
                    <button data-zfm-view-file="certificate"
                            type="button" :data-document-url="'/storage'+documents.certificate?.path"
                            class="btn btn-sm btn-success">View File
                    </button>
                </td>
            </tr>
            <tr>
                <td>Insurance Cover Note</td>
                <td>@{{ documents.insurance?.originalDocumentName }}</td>
                <td>
                    <button data-zfm-view-file="insurance"
                            type="button" :data-document-url="'/storage'+documents.insurance?.path"
                            class="btn btn-sm btn-success">View File
                    </button>
                </td>
            </tr>
        </table>
    </div>

    <div class="row mt-10">
        <div class="col-md-3" v-if="images && images.frontView">
            <div class="card text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Front View</h2>

                <div class="form-group">
                    <div class="imagePreview" :style='{backgroundImage: "url(/storage" + images.frontView.path + ")",}'>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3" v-if="images && images.rearView">
            <div class="card-px text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Rear View</h2>
                <div class="form-group">
                    <div class="imagePreview" :style='{backgroundImage: "url(/storage" + images.rearView.path + ")",}'>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" v-if="images && images.rightView">
            <div class="card text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Right View</h2>
                <div class="form-group">
                    <div class="imagePreview" :style='{backgroundImage: "url(/storage" + images.rightView.path + ")",}'>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3" v-if="images && images.leftView">
            <div class="card text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Left View</h2>
                <div class="form-group">
                    <div class="imagePreview" :style='{backgroundImage: "url(/storage" + images.leftView.path + ")",}'>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-10 mb-10 create_mode">
        <div class="col-md-3">
            <div class="card text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Front View</h2>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                data-input="selectFrontViewFile"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select file
                        </button>
                        <input type="file" accept="image/*"
                               style="display: none;"
                               class="fileElem"
                               name="front_view"/>
                    </p>
                    <div class="imagePreview" style="display: none;">
                        <button type="button"
                                class="btn btn-xs clearImage" style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-px text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Rear View</h2>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select file
                        </button>
                        <input type="file" accept="image/*"
                               style="display: none;"
                               class="fileElem"
                               name="rear_view"/>
                    </p>

                    <div class="imagePreview" style="display: none;">
                        <button type="button"
                                class="btn btn-xs clearImage" style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Right View</h2>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                data-input="selectFrontViewFile"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select file
                        </button>
                        <input type="file" accept="image/*"
                               style="display: none;"
                               class="fileElem"
                               name="right_view"/>
                    </p>
                    <div class="imagePreview" style="display: none;">
                        <button type="button"
                                class="btn btn-xs clearImage" style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center py-5 my-2">
                <h2 class="fs-2x fw-bold mb-10">Left View</h2>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                data-input="selectFrontViewFile"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select file
                        </button>
                        <input type="file" accept="image/*"
                               style="display: none;"
                               class="fileElem"
                               name="left_view"/>
                    </p>
                    <div class="imagePreview" style="display: none;">
                        <button type="button"
                                class="btn btn-xs clearImage" style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <div class="form-group create_mode">
            <button type="submit" id="tms_save_chassis" class="btn btn-success btn-sm">
                <i class="fas fa-paper-plane"></i> Save
            </button>
        </div>
    </div>
</form>
