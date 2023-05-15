<!--begin::Form-->
<form id="tms_costing_valuation_form"
      name="tms_costing_valuation_form"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('vehicle.cost.detail')}}">
    <input type="hidden" name="doctype" value="CostingDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="costAndValuationId" value="{{$vehicle->costAndValuationId ?? 0}}"/>

    <x-error-view/>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5">
                <div class="container-fluid pl-0">
                </div>
            </div>

            {{--<div class="col-xs-12 col-sm-6 col-md-5">
                <div class="container-fluid pl-0">
                    <div class="row">
                        <div class="form-group row">
                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                   for="staff_email"> Last Name:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                <input type="text" class="form-control form-control-sm"
                                       id="last_name"
                                       name="last_name"
                                       required readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}

        </div>
    </div>
    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
        <tbody>
        <tr>
            <td>Spare Tyre</td>
            <td>
                <input type="checkbox">
            </td>

            <td><input type="checkbox"></td>
            <td>hoge@hoge.com</td>
        </tr>

        <tr>

            <td><input type="checkbox"></td>

            <td>foo</td>

            <td><input type="checkbox"></td>
            <td>foo@foo.com</td>

        </tr>

        <tr>
            <td><input type="checkbox" disabled></td>

            <td>bar</td>

            <td><input type="checkbox"></td>
            <td>bar@bar.com</td>

        </tr>

        </tbody>
    </table>
    <div class="create_mode">
        <button type="submit" id="tms_save_costing"
                class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane"></i>
            <span class="indicator-label">
                Save
            </span>
            <span class="indicator-progress">
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
<!--end::Form-->
