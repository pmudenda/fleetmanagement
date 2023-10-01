/**
 * Author: Daka Lovemore
 *
 */
"use strict";
// DateFormatter
window.DateFormatter = {
    ISO: "YYYY-MM-DD",
    STANDARD: "DD/MM/YYYY",
    format: function (date, format) {
        let retDate = [];
        if (!!date && typeof date == "object") {
            if (date instanceof Date) {
                retDate.push(this._prefixZero(date.getDate()));
                retDate.push(this._prefixZero(date.getMonth() + 1));
                retDate.push(date.getFullYear());
            } else {
                retDate.push((!!date.dayOfMonth) ? date.dayOfMonth : "00");
                retDate.push((!!date.monthValue) ? this._prefixZero(date.monthValue) : "00");
                retDate.push((!!date.year) ? date.year : "0000");
            }
            return this._format(retDate, format);
        } else {
            //throw new Error("Invalid date");
            let dateParts = date.split('-');
            return dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];
        }
    },
    _format: function (dateArray, format) {
        let retValue = "";
        switch (format) {
            case this.ISO:
                retValue = dateArray.reverse().join("-");
                break;
            case this.STANDARD:
                retValue = dateArray.join("/");
                break;
            default:
                retValue = dateArray.join("/");
        }
        return retValue;
    },
    _prefixZero: function (month) {
        return (month < 10) ? "0" + month : month;
    }
};

// Table Util
window.Table = {
    addRow: function (tableSelector, rowCount, skipBottom) {
        rowCount = rowCount || 1;
        skipBottom = skipBottom || 0;
        let tableLastRow = tableSelector.find('tbody tr').eq((skipBottom + 1) * -1);

        if (!tableLastRow.get(0)) {
            return null;
        }
        let rowText = tableLastRow.get(0).outerHTML;
        let rows = [];
        for (let a = 0; a < rowCount; a++) {
            rows.push(rowText);
        }
        tableLastRow.after(rows.join(','));
        return tableSelector.find('tbody tr').eq((skipBottom + 1) * -1);
    },
    deleteRow: function (tableRow, skipBottom) {
        let hasDeleted = false;
        skipBottom = skipBottom || 0;
        if (!!tableRow && !!tableRow.find('input,select')) {
            tableRow.find('input,select').val('');
        }
        let rowParent = $(tableRow).parent();
        if (rowParent.children().length > (1 + skipBottom)) {
            $(tableRow).remove();
            hasDeleted = true;
        }
        return hasDeleted;
    },
    clearRows: function (tableSelector, skipBottom) {
        skipBottom = skipBottom || 0;
        let rowCount = tableSelector.find('tbody tr').length;
        tableSelector.find('tbody tr').slice(1, (rowCount - skipBottom)).remove();
        if (!!tableSelector && !!tableSelector.find('tbody tr').find('input,select')) {
            tableSelector.find('tbody tr').find('input,select').val('');
        }
        return tableSelector.find('tbody tr').eq((skipBottom + 1) * -1);
    },

    addRequiredAttClass: function (tableElement) {
        //":disabled,:hidden"
        // make all field mandatory
        $(tableElement).find("tbody").children().map(function (index, row) {
            $(row).find('input[name], select[name]').each(function (i, item) {
                let val = item.value.replace(/,/g, '');
                $(item).addClass('required');
                $(item).attr('required', true);
            });
        });

        return false;
    },

    removeRequiredAttClass: function (tableId, tableElement) {
        tableElement.find("tbody").children().map(function (index, row) {
            $(row).find('input[name], select[name]').each(function (i, item) {
                let val = item.value.replace(/,/g, '');
                $(item).removeClass('required error');
                $(item).closest('label.error').remove();
                $(item).removeAttr('required');
            });
        });

        return false;
    }
};

// Util
window.Util = {
    DECIMAL_PLACES: 2,
    numberFormat: function (num) {
        let x = Util.getFloat(num);
        let numString = x.toFixed(this.DECIMAL_PLACES);
        let numberParts = numString.split('.');
        numberParts[0] = numberParts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        return numberParts.join('.');
    },
    getRawNumber: function (numString) {
        let num = numString + "";
        return Util.getFloat(num.replace(/,/g, ""));
    },
    getProperty: function (obj, key) {
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
    },
    setProperty: function (obj, key, value) {
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
    },
    getPropertyInArray: function (array, key, needle) {
        if (Array.isArray(array)) {
            for (let i in array) {
                if (array.hasOwnProperty(i) && this.getProperty(array[i], key) === needle) {
                    return array[i];
                }
            }
        }
        return undefined;
    },
    getFloat: function (val) {
        let num = val + "";
        val = num.replace(/,/g, "");
        if (isNaN(parseFloat(val))) {
            return 0.00;
        } else {
            return parseFloat(val);
        }
    },
    inArray: function (array, key) {
        for (let i in array) {
            if (key === array[i])
                return true;
        }
        return false;
    },
    isEmpty: function (obj) {
        if (!obj)
            return true;
        for (let key in obj) {
            if (obj.hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    },
    populateDropDownList: function (selectEl, data, idKey, textKeys, textKeySeparator, defaultMessage) {
        let defaultValue = (!!defaultMessage) ? defaultMessage : "";

        let records = [];

        for (let i in data) {
            let textArray = [];
            for (let j in textKeys) {
                textArray.push(Util.getProperty(data[i], textKeys[j]));
            }
            records.push({
                id: Util.getProperty(data[i], idKey),
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
    },
    /**
     * Return Index Of Object in Array
     * @param array
     * @param key
     * @param needle
     * @returns {undefined|*}
     */
    getIndexInArray: function (array, key, needle) {
        if (Array.isArray(array)) {
            for (let i in array) {
                if (array.hasOwnProperty(i) && this.getProperty(array[i], key) === needle) {
                    return i;
                }
            }
        }
        return -1;
    }
};

$(document).ready(function () {
    let $window = $(window);

    window.urlParams = {};

    (function () {
        let match,
            pl = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) {
                return decodeURIComponent(s.replace(pl, " "));
            },
            query = window.location.search.substring(1);

        window.urlParams = {};
        while (match = search.exec(query))
            window.urlParams[decode(match[1])] = decode(match[2]);
    })();

    //add id to main menu for mobile menu start
    let getBody = $("body");
    let bodyClass = getBody[0].className;
    $(".main-menu").attr('id', bodyClass);
    //add id to main menu for mobile menu end

    //loader start
    window.onload = function () {
        $('.theme-loader').fadeOut(1000);
    }
    //loader end

    String.prototype.capitalize = function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
    };
});

$(document).on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});

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

    /**
     * @namespace core
     * @param selectEl
     * @param data
     * @param idKey to be used as value
     * @param textKeys array of the properties to be used as the text
     * @param textKeySeparator
     * @param defaultMessage
     * @param controlDisabled
     * @param dropDownParentSelector
     */
    appInstance.populateDropDownList = function (selectEl, data, idKey, textKeys, textKeySeparator,
                                                 defaultMessage, controlDisabled,
                                                 dropDownParentSelector) {
        let defaultValue = (!!defaultMessage) ? defaultMessage : "";
        let defaultControlState = (!!controlDisabled) ? controlDisabled : false;
        let dropDownListParent = !dropDownParentSelector ? $(document.body) : $(dropDownParentSelector);
        let records = [];

        for (let i in data) {
            let textArray = [];
            for (let j in textKeys) {
                textArray.push(this.getProperty(data[i], textKeys[j]));
            }
            records.push({
                id: this.getProperty(data[i], idKey?.trim()),
                text: textArray.join(textKeySeparator)
            });
        }

        if (records.length === 1) {
            let onlyOption = new Option(records[0].text, records[0].id, true, true);
            selectEl.empty().select2({
                disabled: defaultControlState,
                theme: "bootstrap4",
                width: "resolve",
                dropdownParent: dropDownListParent
            }).append(onlyOption).trigger('change');

            return;
        }

        let newOption = new Option(defaultValue, "", true, true);
        selectEl.empty().select2({
            disabled: defaultControlState,
            theme: "bootstrap4",
            width: "resolve",
            data: records,
            dropdownParent: dropDownListParent
        }).append(newOption).trigger('change');
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
    appInstance.numberOnly = function (event) {
        let key;
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
    appInstance.asyncPostFormData = function (url,
                                              requestPayload,
                                              successCallBack,
                                              errorCallBack,
                                              method = 'POST') {
        $.ajax({
            type: method,
            url: url,
            dataType: 'json',
            data: requestPayload,
            processData: false,
            contentType: false,
            success: function (response_data) {
                if (typeof successCallBack === 'function') {
                    successCallBack(response_data);
                }
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
        let settings = {
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
        }
        $.ajax(settings);
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
    appInstance.initDatatable = function (selector, hasExportOptions, searchable, orderColumns = []) {
        if (typeof searchable === 'undefined') {
            searchable = false
        }
        $(selector).DataTable({
            /*"info": false,*/
            dom: 'Qlfrtip',
            stateSave: true,
            colReorder: true,
            'order': [],
            "pageLength": 10,
            "responsive": true,
            "searching": searchable,
            "lengthChange": false,
            "autoWidth": false,
            'columnDefs': orderColumns,
            "buttons": hasExportOptions ? ["copy", "csv", "excel", "pdf", "print"] : []
        }).buttons().container().appendTo(selector + '_wrapper .col-md-6:eq(0)');


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
            'We could not complete processing your request, please try again later'
        );
    }

})(window.tmsApp = window.tmsApp || {}, jQuery);

