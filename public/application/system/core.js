'use strict';
/**
 * @namespace tmsUtility
 * @type {{makeId: (function(*): string), populateDropDownList: tmsUtility.populateDropDownList, getProperty: ((function(*, *): (*))|*), getRawNumber: (function(*): number|number), isEmpty: ((function(*): (boolean))|*), numberOnly: ((function(*): boolean)|*), makeReference: (function(*): string), getFloat: ((function(*): (number|number))|*), getIndexInArray: ((function(*, *, *): (*|undefined))|*), numberFormat: (function(*): string), setProperty: ((function(*, *, *): (undefined))|*), getPropertyInArray: ((function(*, *, *): (*))|*), inArray: ((function(*, *): (boolean))|*), formatMoney: ((function(*, *): (*))|*), DECIMAL_PLACES: number}}
 */
let tmsUtility = {
    DECIMAL_PLACES: 2,
    /**
     * formats input to money like format
     * @namespace tmsUtility.numberFormat
     * @param num
     * @returns {string}
     */
    numberFormat: function (num) {
        let x = tmsUtility.getFloat(num);
        let numString = x.toFixed(this.DECIMAL_PLACES);
        let numberParts = numString.split('.');
        numberParts[0] = numberParts[0].replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        return numberParts.join('.');
    },
    getRawNumber: function (numString) {
        let num = numString + "";
        return tmsUtility.getFloat(num.replace(/,/g, ""));
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
        if (!val) return 0.00;
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
    makeId: function makeid(length) {
        let result = '';
        let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
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
                textArray.push(tmsUtility.getProperty(data[i], textKeys[j]));
            }
            records.push({
                id: tmsUtility.getProperty(data[i], idKey),
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
    makeReference: function (length) {
        let result = '';
        let characters = '0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
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
    },
    formatMoney: function (data, decimal_places) {
        if (!data) return this.getFloat("0.00");
        if (!decimal_places) decimal_places = this.DECIMAL_PLACES;
        data = data.toString();
        data = parseFloat(data).toFixed(decimal_places);
        return new Intl.NumberFormat().format(parseFloat(data));
    },
    /**
     * checks if event originated from a number key on keyboard
     * @namespace tmsUtility.numberOnly
     * @param event
     * @returns {boolean}
     */
    numberOnly: function (event) {
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
    },
    /**
     * submit asynchronous call of type post
     * @param url
     * @param requestPayload
     * @param successCallBack
     * @param errorCallBack
     */
    asyncPostFormData: function (url, requestPayload, successCallBack, errorCallBack) {
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
    },
    /**
     * submit asynchronous call of type get
     * @param url
     * @param requestPayload
     * @param successCallBack
     * @param errorCallBack
     */
    asyncGetFormData: function (url, requestPayload, successCallBack, errorCallBack) {
        $.get({
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
    }
};
tmsApp.tmsUtility = tmsUtility;
