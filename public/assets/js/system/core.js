'use strict';
/**
 *
 */
(function (appInstance, $) {
    appInstance.DECIMAL_PLACES = 2;

    /**
     * formats input to money like format
     * @namespace tmsApp.numberFormat
     * @param num
     * @returns {string}
     */
    appInstance.numberFormat = function (num) {
        let x = appInstance.getFloat(num);
        let numString = x.toFixed(this.DECIMAL_PLACES);
        let numberParts = numString.split('.');
        numberParts[0] = numberParts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        return numberParts.join('.');
    };

    appInstance.getRawNumber = function (numString) {
        let num = numString + "";
        return appInstance.getFloat(num.replace(/,/g, ""));
    };

    appInstance.getProperty = function (obj, key) {
        if (!!obj && !!obj[key]) {
            return obj[key];
        }
        let keys = key.split(".");
        let tmp = obj;
        for (let i in keys) {
            if (keys.hasOwnProperty(i) && !!tmp) {
                tmp = tmp[keys[i]];
            } else {
                return tmp;
            }
        }
        return tmp;
    };

    appInstance.setProperty = function (obj, key, value) {
        if (!obj)
            return undefined;
        let keys = key.split(".");
        let lastKey = keys.pop();
        let currentObj = obj;
        for (let index in keys) {
            if (keys.hasOwnProperty(index) && !!currentObj) {
                let selectedKey = keys[index];
                if (!currentObj[selectedKey]) {
                    currentObj[selectedKey] = {};
                }
                currentObj = currentObj[selectedKey];
            }
        }
        currentObj[lastKey] = value;
        return obj;
    };

    appInstance.getPropertyInArray = function (array, key, needle) {
        if (Array.isArray(array)) {
            for (let i in array) {
                if (array.hasOwnProperty(i) && this.getProperty(array[i], key) === needle) {
                    return array[i];
                }
            }
        }
        return undefined;
    };

    appInstance.getFloat = function (val) {
        if (!val) return 0.00;
        if (val === "0.00") return val;
        let num = val + "";
        val = num.replace(/,/g, "");
        if (isNaN(parseFloat(val))) {
            return 0.00;
        } else {
            return parseFloat(val);
        }
    };

    appInstance.inArray = function (array, key) {
        for (let i in array) {
            if (key === array[i])
                return true;
        }
        return false;
    };

    appInstance.appInstancemakeId = function makeid(length) {
        let result = '';
        let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    };

    appInstance.isEmpty = function (obj) {
        if (!obj)
            return true;
        for (let key in obj) {
            if (obj.hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    };

    appInstance.populateDropDownList = function (selectEl, data, idKey, textKeys, textKeySeparator, defaultMessage) {
        let defaultValue = (!!defaultMessage) ? defaultMessage : "";

        let records = [];

        for (let i in data) {
            let textArray = [];
            for (let j in textKeys) {
                textArray.push(this.getProperty(data[i], textKeys[j]));
            }
            records.push({
                id: this.getProperty(data[i], idKey),
                text: textArray.join(textKeySeparator)
            });
        }

        if (records.length === 1) {
            let onlyOption = new Option(records[0].text, records[0].id, true, true);
            selectEl.empty().select2({
                theme: "classic",
                width: "resolve"
            }).append(onlyOption).trigger('change');

            return;
        }

        let newOption = new Option(defaultValue, "", true, true);
        selectEl.empty().select2({
            theme: "classic",
            width: "resolve",
            data: records
        }).append(newOption).trigger('change');
    };

    /**
     * generates a unique reference number from the client
     * @param length
     * @returns {string}
     */
    appInstance.makeReference = function (length) {
        let result = '';
        let characters = '0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    };

    /**
     * Return Index Of Object in Array
     * @param array
     * @param key
     * @param needle
     * @returns {undefined|*}
     */
    appInstance.getIndexInArray = function (array, key, needle) {
        if (Array.isArray(array)) {
            for (let i in array) {
                if (array.hasOwnProperty(i) && this.getProperty(array[i], key) === needle) {
                    return i;
                }
            }
        }
        return -1;
    };
    /**
     * form value and return currency formatted value
     * @param data
     * @param decimal_places
     * @returns {number|number|string}
     */
    appInstance.formatMoney = function (data, decimal_places) {
        if (!data) {
            data = appInstance.getFloat("0.00");
            console.log('Formatted ', data);
            return data;
        }
        if (!decimal_places) decimal_places = appInstance.DECIMAL_PLACES;
        data = data.toString();
        data = parseFloat(data).toFixed(decimal_places);
        return new Intl.NumberFormat().format(parseFloat(data));
    };
    /**
     * initialises input to accept event originated from a number key on keyboard
     * @namespace tmsApp.numberOnly
     * @param event
     * @returns {boolean}
     */
    appInstance.appInstancenumberOnly = function (event) {
        let key;
        /*if (event.keyCode === 8 || event.keyCode === 46) {
            return true;
        } else return !(key < 48 || key > 57);*/
        // Handle paste
        if (event.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            key = event.keyCode || event.which;
            key = String.fromCharCode(key);
        }
        let regex = /[0-9]|\./;
        if (!regex.test(key)) {
            event.returnValue = false;
            if (event.preventDefault) event.preventDefault();
        }
    };
    /**
     * submit asynchronous call of type post
     * @param url
     * @param requestPayload
     * @param successCallBack
     * @param errorCallBack
     */
    appInstance.asyncPostFormData = function (url, requestPayload, successCallBack, errorCallBack) {
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: requestPayload,
            processData: false,
            contentType: false,
            success: function (response_data) {
                successCallBack(response_data);
            },
            error: function (xhr, settings, errorThrown) {
                if (typeof errorCallBack === 'function') {
                    errorCallBack(xhr, settings, errorThrown);
                }
            }
        });
    };
    /**
     * submit asynchronous call of type get
     * @param url
     * @param requestPayload
     * @param successCallBack
     * @param errorCallBack
     */
    appInstance.asyncGetFormData = function (url, requestPayload, successCallBack, errorCallBack) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            data: requestPayload,
            processData: false,
            contentType: false,
            headers: {},
            success: function (response_data) {
                successCallBack(response_data);
            },
            error: function (xhr, settings, errorThrown) {
                if (typeof errorCallBack === 'function') {
                    errorCallBack(xhr, settings, errorThrown);
                }
            }
        });
    };
    /**
     *
     * @param msg
     */
    appInstance.printErrorMsg = function (msg) {
        let $msgBox = $('.print-error-msg');
        $msgBox.find('ul').html('');
        $msgBox.css('display', 'block');
        $.each(msg, function (key, value) {
            $msgBox.find('ul').append('<li>' + value + '</li>');
        })
    };
    /**
     *
     * @param formSelector
     * @param validationRules
     * @param validationMessages
     */
    appInstance.appFormValidator = function (formSelector, validationRules, validationMessages) {
        $(formSelector).validate({
            // validation rules for registration form
            errorClass: "error-class",
            validClass: "valid-class",
            errorElement: 'div',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            onError: function () {
                $('.input-group.error-class').find('.help-block.form-error').each(function () {
                    $(this).closest('.form-group').addClass('error-class').append($(this));
                });
            },
            rules: validationRules,
            messages: validationMessages,
            ignore: ":hidden"
        });
    };

    /**
     * initialises jquery datatables
     * @param selector
     * @param hasExportOptions
     * @param searchable
     */
    appInstance.initDatatable = function (selector, hasExportOptions, searchable) {
        if (typeof searchable === 'undefined') {
            searchable = false
        }
        if (hasExportOptions) {
            $(selector).DataTable({
                "responsive": true,
                "searchable": searchable,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo(selector + '_wrapper .col-md-6:eq(0)');

        } else {
            $(selector).DataTable({
                "responsive": true,
                "searchable": searchable,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": []
            }).buttons().container().appendTo(selector + '_wrapper .col-md-6:eq(0)');
        }
    };

    /**
     * adds audio to events
     * @param soundSelector
     */
    appInstance.play_alert = function playSound(soundSelector) {
        document.querySelector('#' + soundSelector).play()
    };

    appInstance.processAsyncResponse = function (ajaxResponse) {
        let e, r, f, c;
        try {
            // response is a string
            if (typeof ajaxResponse == "string") {
                if (ajaxResponse.indexOf('<script type="text/plain" id="Error">') > -1) {
                    let o = ajaxResponse.indexOf('<script type="text/plain" id="Error">') + 37;
                    let l = ajaxResponse.indexOf("<\/script>", o);
                    let s = ajaxResponse.substring(o, l);
                    let a = '';//window.top.sysApp.error.getErrorResultFromXml(s);
                    return e = {
                        isResult: true,
                        result: false,
                        message: "Error",
                        errorXml: s,
                        data: null,
                        dataText: null,
                        dataHtml: "",
                        errorResult: a
                    }
                }

                if (ajaxResponse.indexOf("<response><result>") === 0) {
                    return appInstance.processXMLResponse($.parseXML(ajaxResponse));
                }

                if (ajaxResponse.indexOf("<HTML><BODY>") === 0) {
                    let u = ajaxResponse.substring(33);
                    u = u.substring(0, u.length - 20);
                    return appInstance.processXMLResponse($.parseXML(u));
                } else {
                    return appInstance.processXMLResponse($.parseXML(ajaxResponse));
                }

            }
            throw "invalid data received " + typeof ajaxResponse;
        } catch (h) {
            return
            r = appInstance.error.emptyErrorResult(),
                r.generalMessage = "invalid data received",
                //r.moreDetails.extendedSummary = h.message,
                r.errorXml = "<root><errors><generalerror>sysApp.processIFrameResponse received invalid data<\/generalerror><extendedsummary>"
                    + h.message + "<\/extendedsummary><\/errors><\/root>",
                f = $(ajaxResponse),
                c = f.find("data") ? f.find("data").text() : "",
                e = {
                    isHQResult: true,
                    result: false,
                    message: "invalid data received",
                    errorXml: r.errorXml,
                    data: f,
                    dataText: c,
                    dataHtml: ajaxResponse,
                    errorResult: r
                }
        }
    };

    appInstance.processXMLResponse = function (response) {
        let r, u;
        if (typeof response == "string") r = $($.parseXML(response));
        else if ($.isXMLDoc(response)) r = $(response);
        else throw "invalid data received " + typeof response + ". Missing proper XML.";
        if (u = {
            isHQResult: !0,
            result: r.find("response > result").text().toLowerCase() === "true" || r.find("response > result").length === 0,
            message: r.find("message").text(),
            data: r,
            dataText: r.find("data").text(),
            dataHtml: "",
            errorResult: null
        }, u.dataText > "") try {
            u.dataHtml = appInstance.lib.xmlToText(r.find("data").get(0))
        } catch (f) {
            u.dataHTML = "sysApp unable to process data node as xml"
        }
        if (r.find("error > root").length > 0) try {
            //u.errorResult = appInstance.error.getErrorResultFromXml(systemAppInstance.lib.xmlToText(r.find("error > root").get(0)))
        } catch (f) {
            u.errorResult = null
        }
        return u
    };

    appInstance.asyncPostJson = function (url, requestPayload, successCallBack, errorCallBack) {
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            beforeSend: function () {
                //window.top.loaderVisible = false;
                //$('#page-loader').modal('hide');
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: requestPayload,
            success: function (response_data) {
                if (typeof successCallBack == 'function') {
                    successCallBack(response_data);
                }

            },
            error: function (xhr, settings, errorThrown) {
                if (typeof errorCallBack === 'function') {
                    errorCallBack(xhr, settings, errorThrown);
                }
            }
        });
    }

    appInstance.showErrorMessages = function (xhr, title) {
        if ('responseJSON' in xhr) {
            if (xhr.responseJSON.hasOwnProperty('errors')) {
                appInstance.printErrorMsg(xhr.responseJSON.errors);
            }
            if (xhr.responseJSON.hasOwnProperty('message')) {
                appInstance.systemError(
                    title,
                    xhr.responseJSON['message']
                );
            }
            return;
        }
        appInstance.systemError(
            title,
            'We could not complete processing your request, please try again later');


    }

})(window.tmsApp = window.tmsApp || {}, jQuery);

