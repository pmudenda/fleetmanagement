<!--begin::Form-->
<form id="tms_accessories_form"
      name="tms_accessories_form"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('vehicle.accessories.save')}}">
    <input type="hidden" name="doctype" value="CostingDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="costAndValuationId" value="{{$vehicle->costAndValuationId ?? 0}}"/>

    <x-error-view/>
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
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-8">
                <div class="row">
                    <div class="col">
                        <table class="table table-row-dashed align-middle gs-0 table-bordered">
                            <thead>
                            <tr>
                                <th class="pl-2">Item</th>
                                <th>Present</th>
                                <th class="pr-2">Not Present</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="pl-2" style="width: 75%;">Spare Tyre</td>
                                <td><input type="radio" value="yes" name="spareTyre"></td>
                                <td><input type="radio" value="no" name="spareTyre"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col">
                        <table class="table table-row-dashed align-middle gs-0 table-bordered">
                            <thead>
                            <tr>
                                <th class="pl-2">Item</th>
                                <th>Present</th>
                                <th class="pr-2">Not Present</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="pl-2" style="width: 75%;">Jack</td>
                                <td><input type="radio" value="yes" name="spareTyre"></td>
                                <td><input type="radio" value="no" name="spareTyre"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end::Form-->
