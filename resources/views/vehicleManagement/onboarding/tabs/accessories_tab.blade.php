<!--begin::Form-->
<form id="tms_accessories_form"
      name="tms_accessories_form"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('vehicle.accessories.save')}}">
    <input type="hidden" name="doctype" value="CostingDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="accessoryHeaderId" value="{{$vehicle->accessoryHeaderId ?? 0}}"/>

    <x-error-view/>
    <div class="d-flex justify-content-end">
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
    </div>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="row">
                    <div class="col">
                        <table class="table table-row-dashed align-middle gs-0 table-bordered">
                            <thead>
                            <tr class="bg-dark">
                                <th class="pl-2">Item</th>
                                <th>Present</th>
                                <th class="pr-2">Not Present</th>
                                <th class="pr-2">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accessories as $key => $accessory)
                                @if(($key%2) == 0)
                                    <tr>
                                        <td class="pl-2" style="width: 35%;">{{$accessory->name}}</td>
                                        <td><input type="radio" value="yes" name="{{$accessory->name}}"></td>
                                        <td><input type="radio" value="no" name="{{$accessory->name}}"></td>
                                        <td style="width: 45%;">
                                            <input typeof="text" name="" class="form-control form-control-sm" />
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col">
                        <table class="table table-row-dashed align-middle gs-0 table-bordered">
                            <thead>
                            <tr class="bg-dark">
                                <th class="pl-2">Item</th>
                                <th>Present</th>
                                <th class="pr-2">Not Present</th>
                                <th class="pr-2">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accessories as $key => $accessory)
                                @if(($key%2) != 0)
                                    <tr>
                                        <td class="pl-2" style="width: 35%;">{{$accessory->name}}</td>
                                        <td><input type="radio" value="yes" name="{{$accessory->name}}"></td>
                                        <td><input type="radio" value="no" name="{{$accessory->name}}"></td>
                                        <td style="width: 45%;">
                                            <input typeof="text" class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end::Form-->
