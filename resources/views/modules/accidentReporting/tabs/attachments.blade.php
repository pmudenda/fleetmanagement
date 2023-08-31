<section class="second-section">
    <div class="row">
        <div class="row">
            <div class="row">
                <div class="col pl-0">
                    <label for="inspectionDate"
                           class="fs-6 fw-semibold form-label field-required col-md-5"
                           style="padding-right: 0px;">
                        Police Report:
                        <small class="text-danger">.PDF, JPG, JPEG, PNG, BMP</small>
                    </label>
                    <div class="col-md-7 fv-row">
                        <div class="col-md-9 pl-0">
                            <input type="file" accept="image/*,.pdf"
                                   required
                                   id="police_report"
                                   class="filer_input"
                                   name="police_report"/>
                        </div>
                    </div>

                    <canvas style="display: none;" id="motor_vehicle_certificatePdfViewer"></canvas>
                </div>
                <div class="col">
                    <label for="inspectionDate"
                           class="fs-6 fw-semibold form-label reqd col-md-5"
                           style="padding-right: 0px;">
                        Insurance Report:
                        <small class="text-danger">.PDF, JPG, JPEG, PNG, BMP</small>
                    </label>

                    <div class="col-md-7 fv-row">
                        <div class="col-md-9 pl-0">
                            <input type="file" accept="image/*,.pdf"
                                   id="insurance_report"
                                   class="filer_input"
                                   name="insurance_report"/>
                        </div>
                    </div>

                    <canvas style="display: none;" id="insurance_cover_notePdfViewer"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="table-responsive" style="max-height:500px;">
                <table data-model-name="Observations"
                       aria-label="accident attachments"
                       role="table"
                       class="table table-striped table-bordered"
                       id="observations">
                    <thead>
                    <tr class="bg-success">
                        <th scope="row">Attachment</th>
                        {{--<th scope="row">Remarks(Description)</th>--}}
                        <th scope="row"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <p>
                                <button type="button" title="Select Image"
                                        data-toggle="tooltip"
                                        data-select="file"
                                        class="btn btn-primary btn-sm selectAttachment">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <input type="file"
                                       accept="image/*"
                                       style="display: none;"
                                       class="fileElem"
                                       id="attachment"
                                       name="attachment[]"/>
                            </p>
                            <div class="imagePreview"
                                 style="display: none; min-height: 250px !important;">
                                <button type="button"
                                        class="btn btn-xs clearImage"
                                        style="top: 1px;
                                        position: relative;
                                        right: 1px;
                                        float: right;
                                        padding: 2px;">
                                    <i class="fa fa-window-close" style="font-size: 20px;"></i>
                                </button>
                            </div>
                        </td>
                       {{-- <td>
                            <input type="text" name="observation[]" class="form-control">
                        </td>--}}
                        <td>
                            <button type="button"
                                    data-table-id="observations"
                                    class="btn btn-sm btn-danger"
                                    value="deleteRow">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button type="button"
                        data-table-id="observations"
                        class="btn btn-sm btn-primary add pull-right"
                        value="insertRow">
                    <i class="fa fa-plus"></i> Add Row
                </button>
            </div>
        </div>
    </div>
</section>
