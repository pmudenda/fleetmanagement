(function (appInstance, $, i) {
        appInstance.confirmWithInput = async function (title, labelText, confirmButtonText, cancelButtonText,
                                                       confirmCallBack, cancelCallBack,
                                                       inputMaxLength, isMultilineInput) {

            const {value: dataVal} = await Swal.fire({
                title: title !== "" && title !== i && title !== null ? title : " Prompt",
                input: isMultilineInput ? 'textarea' : 'text',
                confirmButtonText: confirmButtonText,
                inputLabel: labelText ?? 'Message',
                showCancelButton: true,
                inputPlaceholder: 'Enter your ' + labelText,
                inputAttributes: isMultilineInput ? {
                        'aria-label': 'Type your message here'
                    } :
                    {
                        maxlength: 10,
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
            })

            if (dataVal) {
                // Swal.fire(`Entered email: ${dataVal}`)
                if (typeof confirmCallBack === 'function') {
                    confirmCallBack.call(null, dataVal)
                }
            }
        };


        /**
         *
         * @param title
         * @param text
         * @param description
         * @param confirmButtonText
         * @param cancelButtonText
         * @param confirmCallback
         * @param cancelCallback
         * @param inputMaxLength
         */
        appInstance.confirmWithCheckBox = async function (title, text, description, confirmButtonText, cancelButtonText, confirmCallback, cancelCallback, inputMaxLength) {

            const {value: accept} = await Swal.fire({
                title: title !== "" && title !== i && title !== null ? title : "Prompt",
                showCancelButton: true,
                cancelButtonText: cancelButtonText,
                input: 'checkbox',
                inputValue: 1,
                inputPlaceholder:
                description,
                confirmButtonText:
                    confirmButtonText + ' <i class="fa fa-arrow-right"></i>',
                inputValidator: (result) => {
                    return !result && 'You need to agree with T&C'
                }
            })

            if (accept) {
                //confirmCallback.call(null, n, i)
                //cancelCallback.call()
            }
        };

        /**
         *
         * @param title
         * @param text
         * @param confirmButtonText
         * @param cancelButtonText
         * @param confirmCallback
         * @param cancelCallback
         * @param height
         * @param width
         * @param containerClass
         * @param cssStyle
         */
        appInstance.confirm = function (
            title,
            text,
            confirmButtonText,
            cancelButtonText,
            confirmCallback,
            cancelCallback,
            height,
            width,
            containerClass,
            cssStyle
        ) {
            Swal.fire({
                title: title === "" || title === null ? "Confirm" : title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59d31',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText === "" || confirmButtonText == null ? 'Yes, Proceed' : confirmButtonText,
                cancelButtonText: cancelButtonText === "" || cancelButtonText == null ? 'No, cancel' : cancelButtonText,
            }).then((result) => {
                console.log('ConfirmationResult', result);
                if (result.value) {
                    setTimeout(function () {
                        if (typeof confirmCallback !== 'function') {
                            return;
                        }

                        confirmCallback.call();
                    }, 300);
                } else {
                    if (typeof cancelCallback !== 'function') {
                        return;
                    }
                    cancelCallback.call()
                }
            });
        };

        /**
         *
         * @param title
         * @param content
         * @param closeCallback
         */
        appInstance.systemError = function (title, content, closeCallback) {
            Swal.fire({
                title: title == null || title === "" ? 'Message' : title,
                text: content == null || content === "" ? 'Content!' : content,
                icon: 'error',
                showDenyButton: false,
                showCancelButton: false,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof closeCallback == 'function') {
                        closeCallback.call()
                    }
                }
            });
        };

        /**
         *
         * @param title
         * @param content
         * @param cancelCallback
         */
        appInstance.showSystemMessage = function (title, content, cancelCallback, type = 'error') {
            if (!content) {
                return;
            }
            Swal.fire(
                title,
                content,
                type
            )
        };

        /**
         *
         * @param t
         * @param r
         * @param u
         */
        appInstance.detailDialog = function (t, r, u) {

        };

        /**
         * toast system message
         * @param content
         * @param type
         */
        appInstance.showToast = function (content, type) {
            switch (type) {
                case 'success':
                    toastr.success(content);
                    break;
                case 'warning':
                    toastr.warning(content);
                    break;
                case 'info':
                    toastr.info(content);
                    break;
                case 'error':
                    toastr.error(content);
                    break;
                default:
                    break;
            }
        }

        /**
         *
         * @param title
         * @param description
         * @param successMessage
         * @param postUrl
         * @param passwordFieldName
         * @param o
         */
        appInstance.passwordChange = async function (title, description, successMessage, postUrl, passwordFieldName, o) {

            const {value: formValues} = await Swal.fire({
                title: '<i class="fa fa-key"><\/i> ' + title,
                html:
                    '<form><p style="text-align:left;">New Password: <\/p><input type="password" required autocomplete="password" autocapitalize="off" auto-correct="false" class="form-control" id="password"/><p style="text-align:left; margin-top: 10px;">' +
                    'Confirm: <\/p><input type="password" class="form-control" id="confirm_password"/>' +
                    '<p className="errorMessage" style="display:none;"></p></form>',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('password').value,
                        document.getElementById('confirm_password').value
                    ]
                }
            });

            if (formValues) {
                let newUserPassword = $("#password").val();
                let confirmUserPassword = $("#confirm_password").val();
                if (newUserPassword == "" || newUserPassword !== confirmUserPassword) {
                    $(document).find(".errorMessage").text('The passwords do not match. Please re-enter the new password.');
                    return;
                } else {
                    $(document).find(".errorMessage").text('');
                }

                console.log(formValues);

                let newPassword = $("#password").val();
                let newConfirmPassword = $("#confirm_password2").val();
                let postData;
                if (newPassword !== i && newPassword !== null && newPassword !== "") {
                    if (newPassword === newConfirmPassword) {
                        postData = {};
                        postData[passwordFieldName] = newPassword;
                        postData.validate = o;
                        $.ajax({
                            url: postUrl,
                            data: postData,
                            success: function () {
                                window.top.sysApp.showToast(successMessage, 'success');
                            }
                        })
                    } else {
                        document.find(".errorMessage").show();
                        $("#password").trigger("focus");
                    }
                } else {
                    alert("Please enter and verify a newPassword or cancel this dialog.");
                    $("#password").trigger("focus");
                    return
                }
            }
        };

        /**
         * @namespace dialogs.errorWindow
         * @param title
         * @param message
         * @param error
         * @param closeCallback
         */
        appInstance.errorWindow = function (title, message, error, closeCallback) {
            Swal.fire({
                title: title !== "" && title !== i && title !== null ? title : "System Error",
                icon: 'error',
                html: message +
                    '<br>' +
                    error
            });
        }
    }
)
(window.tmsApp = window.tmsApp || {}, jQuery);
