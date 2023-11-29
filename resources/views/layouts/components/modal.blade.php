<div class="modal fade" id="kt_modal_upload" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="none" id="kt_modal_upload_form">
                <div class="modal-header">
                    <h2 class="fw-bold">Upload files</h2>

                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                          transform="rotate(-45 6 17.3137)"
                                          fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                          transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                    </svg>

                                </span>
                    </div>
                </div>

                <div class="modal-body pt-10 pb-15 px-lg-17">
                    <div class="form-group">
                        <div class="dropzone dropzone-queue mb-2" id="kt_modal_upload_dropzone">
                            <div class="dropzone-panel mb-4">
                                <a class="dropzone-select btn btn-sm btn-primary me-2 dz-clickable">Attach
                                    files</a>
                                <a class="dropzone-upload btn btn-sm btn-light-primary me-2">Upload All</a>
                                <a class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
                            </div>

                            <div class="dropzone-items wm-200px">

                            </div>
                            <div class="dz-default dz-message">
                                <button class="dz-button" type="button">Drop files here to upload</button>
                            </div>
                        </div>

                        <span class="form-text fs-6 text-muted">Max file size is 1MB per file.</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
