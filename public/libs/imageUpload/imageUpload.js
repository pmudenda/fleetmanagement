function ImageUpload() {
    const selector = '.fileElem';

    this.init = function () {
        $(document).on('click', '[data-select="file"]', function () {
            let fileInput = $(this).closest('p').find('input[type="file"]');
            $(fileInput).trigger('click');
        });

        let fileSelects = [].slice.call(document.querySelectorAll(selector));
        fileSelects.map(function (fileSelect) {
            fileSelect.addEventListener("change",
                (e) => {
                    preview(e);
                },
                false);
        });

        function preview(event) {
            //$('#frame').src = URL.createObjectURL(event.target.files[0]);
            let uploadFile = $(event.target);
            let self = event.target;
            let files = !!self.files ? self.files : [];
            if (!files.length || !window.FileReader) return;
            // no file selected, or no FileReader support

            if (/^image/.test(files[0].type)) {
                // only image file
                let reader = new FileReader();
                // instance of the FileReader
                reader.readAsDataURL(files[0]);
                // read the local file

                reader.onloadend = function () {
                    // set image data as background of div
                    uploadFile.closest("div").find('.imagePreview').css({
                        "background-image": "url(" + this.result + ")", 'display': 'block', 'background-size': 'cover'
                    });
                }

                $(uploadFile).closest('div').find('p').addClass('d-none');
            } else {
                toastr.error('only image (.jpg, .jpeg, .png, .bmp) file types are allowed', 'Invalid File Format Selected')
            }
        }

        $(document).on('click', '.clearImage', function (event) {
            let btn = this;
            Swal.fire({
                text: "Are you sure you would like to remove the image?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    $(btn).parent().css({
                        "background-image": "", 'display': 'none'
                    });
                    // find the upload btn and make visible
                    $(btn).closest('p').removeClass('d-none');
                }
            });

        });
    }
}
