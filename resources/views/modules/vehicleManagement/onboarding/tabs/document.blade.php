<div v-show="isHeaderSaved" class="col-md-12 col-sm-12 mb-5">

    <div class="card ">
        <div class="card-header" style="min-height: 45px;">
            <div class="card-title">
                <h4>Vehicle Images</h4>
            </div>

            <div class="card-toolbar">
                <button type="button" v-on:click="completeVehicleRegistration"
                        class="btn btn-primary btn-sm">
                            <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor">
                                    </path>
                                    <path
                                        d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM16 11.6L12.7 8.29999C12.3 7.89999 11.7 7.89999 11.3 8.29999L8 11.6H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H16Z"
                                        fill="currentColor">
                                    </path>
                                    <path opacity="0.3"
                                          d="M11 11.6V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H11Z"
                                          fill="currentColor">
                                    </path>
                                    </svg>
                                    </span>
                    Upload Files And Complete Registration
                </button>
            </div>
        </div>

        <form name="completeRegistrationForm" id="completeRegistrationForm"
              action="{{route('api.vehicle.new')}}">
            <input type="hidden" name="doctype" value="CompletionDetails"/>
            <input type="hidden" name="headerId" v-model="vehicleHeaderId"/>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-center py-10 my-5">
                            <h2 class="fs-2x fw-bold mb-10">Front View</h2>
                            <div class="form-group">
                                <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                                   class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                    <button type="button"
                                            data-select="file"
                                            data-input="selectFrontViewFile"
                                            :disabled="dataStatus < 5"
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
                    <div class="col-md-12">
                        <div class="card-px text-center py-10 my-5">
                            <h2 class="fs-2x fw-bold mb-10">Rear View</h2>
                            <div class="form-group">
                                <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                                   class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                    <button type="button"
                                            data-select="file"
                                            :disabled="dataStatus < 5"
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
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-center py-5 my-2">
                            <h2 class="fs-2x fw-bold mb-10">Right View</h2>
                            <div class="form-group">
                                <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                                   class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                    <button type="button"
                                            data-select="file"
                                            data-input="selectFrontViewFile"
                                            :disabled="dataStatus < 5"
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
                    <div class="col-md-12">
                        <div class="card text-center py-5 my-2">
                            <h2 class="fs-2x fw-bold mb-10">Left View</h2>
                            <div class="form-group">
                                <p :title="[dataStatus < 5 ? 'You must complete data entry on tabs before uploading images':'','']"
                                   class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                    <button type="button"
                                            data-select="file"
                                            data-input="selectFrontViewFile"
                                            :disabled="dataStatus < 5"
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

                <div class="text-center px-4"></div>
            </div>
        </form>
        <div class="card-footer">
            <span class="form-text fs-6 text-muted">Max file size is 1MB per file.</span>
        </div>
    </div>

</div>
