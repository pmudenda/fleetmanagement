/*!
(c) 2023 Lovemore Daka
*/
/*! *****************************************************************************
Shows & Hides the page loader
Copyright (c) 2023 Zambia Electricity Supply Company
***************************************************************************** */

"use strict";
$.ajaxSetup({
    global: true, headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


(function (systemInstance, $) {
    /**
     * Displays a loader when the user should wait for a certain action to complete
     * @param displayModal - true/false
     */
    window.loaderVisible = false;
    window.showLoaderModal = function (displayModal) {
        window.loaderMessage = (!window.loaderMessage) ? window.loaderMessage : "Please wait...";
        $("#loading-message").html(window.loaderMessage);
        let modal = ''
        if (displayModal) {
            if (!window.loaderVisible) {
                let myModalEl = document.querySelector('#page-loader')
                if (myModalEl) {
                    if (bootstrap) {
                        modal = new bootstrap.Modal(myModalEl,
                            {
                                backdrop: 'static', keyboard: false
                            });
                    }
                }

                if(modal){
                    modal.show();
                }
                window.loaderVisible = true;
            }
        } else {
            let myModalEl = document.querySelector('#page-loader')
            if (myModalEl) {
                if (bootstrap) {
                    modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
                }
            }
            if(modal){
                modal.hide();
                window.loaderVisible = false;
            }

            setTimeout(function () {
                window.loaderVisible = false;

                if(modal){
                    modal.hide();
                }
            }, 1000);
        }
    }

    // Show/hide loader
    window._requestsLoading = [];
    window.addEventListener('beforeunload', function (event) {
        window._requestsLoading.push(1);
        window.showLoaderModal(window['showLoader'] !== false);
    });

    $(document).ajaxStart(function () {
        window._requestsLoading.push(1);
        window.showLoaderModal(window['showLoader'] !== false);
    });

    $(document).ajaxComplete(function () {
        window._requestsLoading.pop();
        if (window._requestsLoading.length <= 0) {
            window.showLoaderModal(false);
        }
    });

    $(document).ajaxSuccess(function (event, xhr, settings) {
        if (typeof xhr.responseText === "string" && xhr.responseText.indexOf('</html>') > 0) {
            window.location.reload();
        }
    });

    $(document).ajaxError(function (e, a, b) {
        window._requestsLoading.pop();
        window.showLoaderModal(false);
    });

    window.loader = {
        show: function () {
            window.showLoaderModal(true);
        },
        hide: function () {
            window.showLoaderModal(false);
        }
    };

    /**
     * loading message dialog
     */
    systemInstance.asyncLoader = {
        show: function (loadingMessage) {

            if ($("#app_loading_message").length > 0) {
                $("#app_loading_message").html(`<p>
                    <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">
                    Loading...</span>
                    </div> ${loadingMessage} ...
                    </p>`
                );
            } else {
                $(document.body)
                    .append(`<div id="app_loading_message" class="sys-overlay">
                                    <p><div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                            ${loadingMessage} ...
                                    </p>
                                </div>`);
                $("#app_loading_message")
                    .append($(`<div style="float:right"><i class="fa fa-times"></i></div>`))
                    .on("click", function () {
                        $(this).parent().remove()
                    });
            }
        },
        hide: function () {
            $("#app_loading_message").remove()
        }
    };

})(window.tmsApp || {}, jQuery);



