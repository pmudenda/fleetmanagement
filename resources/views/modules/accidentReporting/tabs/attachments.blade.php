<section class="second-section">
    <div class="row">
        <div class="row">
            <div class="row">
                <div class="col pl-0">
                    <label for="inspectionDate" class="fs-6 fw-semibold form-label reqd col-md-5"
                           style="padding-right: 0px;">
                        Motor Vehicle Certificate (White Book):
                        <small class="text-danger">.PDF, JPG, JPEG, PNG, BMP</small>
                    </label>
                    <div class="col-md-7 fv-row">
                        <div class="col-md-9 pl-0">
                            <input type="file" accept="image/*,.pdf"
                                   required
                                   id="motor_vehicle_certificate"
                                   class="filer_input"
                                   name="motor_vehicle_certificate"/>
                        </div>
                    </div>

                    <canvas style="display: none;" id="motor_vehicle_certificatePdfViewer"></canvas>
                </div>
                <div class="col">
                    <label for="inspectionDate" class="fs-6 fw-semibold form-label reqd col-md-5"
                           style="padding-right: 0px;">
                        Insurance Cover Note:
                        <small class="text-danger">.PDF, JPG, JPEG, PNG, BMP</small>
                    </label>

                    <div class="col-md-7 fv-row">
                        <div class="col-md-9 pl-0">
                            <input type="file" accept="image/*,.pdf"
                                   required
                                   id="insurance_cover_note"
                                   class="filer_input"
                                   name="insurance_cover_note"/>
                        </div>
                    </div>

                    <canvas style="display: none;" id="insurance_cover_notePdfViewer"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5 mb-10 create_mode">
        <div class="col-md-3">
            <div class="card text-center py-5 my-2 pt-0">
                <h2 class="fs-2x fw-bold mb-5">Front View</h2>
                <small class="text-danger">JPG, JPEG, PNG, BMP</small>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                data-input="selectFrontViewFile"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select Image
                        </button>
                        <input type="file" accept="image/*"
                               style="display: none;"
                               class="fileElem"
                               name="front_view"/>
                    </p>
                    <div class="imagePreview" style="display: none;">
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
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-px text-center py-5 my-2 pt-0">
                <h2 class="fs-2x fw-bold mb-5">Rear View</h2>
                <small class="text-danger">JPG, JPEG, PNG, BMP</small>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select Image
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
            <div class="card text-center py-5 my-2 pt-0">
                <h2 class="fs-2x fw-bold mb-5">Right View</h2>
                <small class="text-danger">JPG, JPEG, PNG, BMP</small>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                data-input="selectFrontViewFile"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select Image
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
            <div class="card text-center py-5 my-2 pt-0">
                <h2 class="fs-2x fw-bold mb-5">Left View</h2>
                <small class="text-danger">JPG, JPEG, PNG, BMP</small>
                <div class="form-group">
                    <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                       class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                        <button type="button"
                                data-select="file"
                                data-input="selectFrontViewFile"
                                class="upload-file btn btn-sm btn-primary me-2">
                            <i class="fas fa-cloud-arrow-up"></i> Select Image
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
</section>
