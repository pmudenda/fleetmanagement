<!--begin::Form-->
<form id="tms_body_weight_form"
      v-on:submit.prevent="submitBodyDetails"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('api.vehicle.new')}}">
    <input type="hidden" name="doctype" value="BodyDetails"/>
    <input type="hidden" name="headerId" v-model="vehicleHeaderId"/>
    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
        <tbody>

        <tr>
            <td colspan="2">
                <h4>Dimensions</h4>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="vehicleHeight" class="control-label reqd"
                       style="padding-right: 0px;">
                    Height (m):
                </label>
            </td>
            <td colspan="1">
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback number_input form-control bold"
                                       maxlength="4"
                                       data-fieldtype="Link"
                                       data-fieldname="company"
                                       id="vehicleHeight"
                                       name="height"
                                       v-model="bodyDetails.height"
                                       placeholder=""
                                       data-doctype="BodyDetails"/>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">
                <label for="length" class="control-label reqd"
                       style="padding-right: 0px;">
                    Length (m):
                </label>
            </td>
            <td>
                <input type="text"
                       class="input-with-feedback number_input form-control bold"
                       maxlength="140"
                       required
                       data-fieldtype="Link"
                       data-fieldname="company"
                       id="length"
                       name="length"
                       v-model="bodyDetails.length"
                       placeholder=""
                       data-doctype="BodyDetails"/>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="width" class="control-label reqd"
                       style="padding-right: 0px;">
                    Width:
                </label>
            </td>
            <td colspan="1">
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback number_input form-control bold"
                                       maxlength="140"
                                       required
                                       data-fieldtype="Link"
                                       data-fieldname="company"
                                       id="width"
                                       name="width"
                                       v-model="bodyDetails.width"
                                       placeholder=""
                                       data-doctype="BodyDetails"/>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control"></td>
            <td></td>
        </tr>

        <tr>
            <td colspan="2">
                <h4>Interior</h4>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="vehicleWidth" class="control-label reqd"
                       style="padding-right: 0px;">
                    Seat Capacity Front:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback form-control bold"
                                       maxlength="15"
                                       id="seatCapFront"
                                       name="seatCapFront"
                                       v-model="bodyDetails.seatCapFront"
                                       placeholder=""
                                       data-doctype="BodyDetails"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">
                <div class="clearfix">
                    <label for="seatCapRear" class="control-label reqd"
                           style="padding-right: 0px;">
                        Seat Cap/Rear:
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
                                       maxlength="15"
                                       placeholder=""
                                       v-model="bodyDetails.seatCapRear"
                                       name="seatCapRear"
                                       id="seatCapRear"
                                       data-doctype="BodyDetails"
                                       autocomplete="off">
                            </div>

                        </div>
                    </div>
                    <p class="help-box small text-muted"></p>
                </div>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="volumeOfBootTanker" class="control-label reqd"
                       style="padding-right: 0px;">
                    Vol. Boot/Tanker:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       v-model="bodyDetails.volumeOfBootTanker"
                                       class="input-with-feedback form-control bold"
                                       maxlength="140"
                                       id="volumeOfBootTanker"
                                       name="volumeOfBootTanker"
                                       placeholder=""
                                       data-doctype="BodyDetails"
                                       autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">
                <div class="clearfix">
                    <label for="numberOfSeats" class="control-label reqd"
                           style="padding-right: 0px;">
                        No. Of Seats :
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
                                       maxlength="140"
                                       data-fieldtype="Link"
                                       data-fieldname="company"
                                       id="numberOfSeats"
                                       name="numberOfSeats"
                                       data-doctype="BodyDetails"
                                       v-model="bodyDetails.numberOfSeats"
                                       placeholder=""/>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <h4>Exterior</h4>
            </td>
        </tr>
        <tr>
            <td class="frappe-control ">
                <label for="distanceAxle1" class="control-label reqd"
                       style="padding-right: 0px;">
                    Dist Axle 1:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <input type="text"
                                   class="input-with-feedback form-control bold"
                                   maxlength="140"
                                   id="distanceAxle1"
                                   name="distanceAxle1"
                                   data-doctype="BodyDetails"
                                   v-model="bodyDetails.distanceAxle1"
                                   placeholder=""
                                   data-target="Company">
                        </div>
                    </div>
                </div>
            </td>

            <td class="frappe-control ">
                <label for="distanceAxle2" class="control-label reqd"
                       style="padding-right: 0px;">
                    Dist Axle 2:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <input type="text"
                                   class="input-with-feedback form-control bold"
                                   maxlength="140"
                                   id="distanceAxle2"
                                   name="distanceAxle2"
                                   data-doctype="BodyDetails"
                                   v-model="bodyDetails.distanceAxle2"
                                   placeholder=""/>
                        </div>
                    </div>
                </div>
            </td>

        </tr>
        <tr>
            <td class="frappe-control ">
                <label for="distanceAxle3" class="control-label reqd"
                       style="padding-right: 0px;">
                    Dist Axle 3:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <input type="text"
                                   class="input-with-feedback form-control bold"
                                   maxlength="140"
                                   id="distanceAxle3"
                                   name="distanceAxle3"
                                   v-model="bodyDetails.distanceAxle3"
                                   placeholder=""
                                   data-doctype="BodyDetails"
                                   data-target="Company">
                        </div>
                    </div>
                </div>
            </td>

            <td class="frappe-control ">
                <label for="distanceAxle5" class="control-label reqd"
                       style="padding-right: 0px;">
                    Dist Axle 5 Rda/Ult:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <input type="text"
                                   class="input-with-feedback form-control bold"
                                   maxlength="140"
                                   id="distanceAxle4"
                                   name="distanceAxle4"
                                   v-model="bodyDetails.distanceAxle4"
                                   placeholder=""/>
                        </div>
                    </div>
                </div>
            </td>

        </tr>

        <tr>
            <td colspan="2">
                <h4>Weight</h4>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="tareWeight" class="control-label reqd"
                       style="padding-right: 0px;">
                    Tare Weight:
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
                                       data-fieldtype="Link"
                                       data-fieldname="company"
                                       id="tareWeight"
                                       name="tareWeight"
                                       v-model="weightDetails.tareWeight"
                                       placeholder=""
                                       data-doctype="WeightDetails"/>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">
                <label for="grossWeight" class="control-label reqd"
                       style="padding-right: 0px;">
                    Gross Weight:
                </label>
            </td>
            <td>
                <input type="text"
                       class="input-with-feedback form-control bold"
                       maxlength="140"
                       data-fieldtype="Link"
                       data-fieldname="company"
                       id="grossWeight"
                       name="grossWeight"
                       v-model="weightDetails.grossWeight"
                       placeholder=""
                       data-doctype="WeightDetails"/>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="trailerWeight2" class="control-label reqd"
                       style="padding-right: 0px;">
                    Trailer Weight 2:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback form-control bold"
                                       maxlength="15"
                                       id="trailerWeight2"
                                       name="trailerWeight2"
                                       v-model="weightDetails.trailerWeight2"
                                       placeholder=""
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">
                <div class="clearfix">
                    <label for="trailerWeight3" class="control-label reqd"
                           style="padding-right: 0px;">
                        Trailer Weight 3:
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
                                       maxlength="15"
                                       placeholder=""
                                       v-model="weightDetails.trailerWeight3"
                                       name="trailerWeight3"
                                       id="trailerWeight3"
                                       data-doctype="WeightDetails"
                                       autocomplete="off">
                            </div>

                        </div>
                    </div>
                    <p class="help-box small text-muted"></p>
                </div>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="trailerWeight4" class="control-label reqd"
                       style="padding-right: 0px;">
                    Trailer Weight 4:
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
                                       data-fieldtype="Link"
                                       data-fieldname="company"
                                       id="trailerWeight4"
                                       name="trailerWeight4"
                                       v-model="weightDetails.trailerWeight4"
                                       placeholder=""
                                       data-doctype="WeightDetails"/>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control"></td>
            <td></td>
        </tr>

        </tbody>
    </table>

    <div>
        <button type="submit" id="tms_save_body" class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane"></i>
            <span class="indicator-label">Save</span>
            <span class="indicator-progress">Please wait...<span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
        </button>
    </div>
</form>
<!--end::Form-->
