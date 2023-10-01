/**
 * @namespace CSVFileUploader
 * @param mapper - mapper object
 * @constructor
 */
window.MAX_TABLE_VIEW = 500;

function CSVFileUploader(mapper) {
    window.xlsxData = window.xlsxData || [];
    //
    window.isUploaded = false;
    window.isUploadComplete = false;
    this.controlNames = [];
    this.extraData = [];
    this.data = [];
    this.tableId = '';
    this._mapper = mapper || {};
    this.dataToSend = null;
    this.errorList = {};
    this._viewFileTemplate = `<div class="mb-4 download-file">
                                    <p style="color:red;font-size:1em;"><strong>You can only view a maximum of ` + MAX_TABLE_VIEW + ` records.
                                    To view all the records, click the link below</strong></p>
                                    <span style="color:green"><i class="fa fa-file-excel-o"></i></span>&nbsp;
                                    <span style="text-decoration:underline;color:blue;cursor: pointer;"
                                     data-view-file="{tableId}">
                                    Download complete Excel / CSV file ({rows} rows)</span>
                                </div>`;

    this.init = function () {
        let self = this;
        const fileChosen = document.getElementById('file-chosen');
        $('button[value="clearUpload"][data-table-id]').off('click').on('click', function () {
            self.tableId = $(this).data('tableId');
            $('#' + self.tableId + ' input, #' + self.tableId + ' button, #'
                + self.tableId + ' select,[data-table-id="'
                + self.tableId + '"][value="addRow"]').attr('disabled', false);
            $('#' + self.tableId).parent().find('.download-file').remove();
        });

        /**
         * @namespace CSVFileUploader
         * Runs to collect information on the form elements available in table identified by data-table-id
         * custom attribute
         */
        $('button[value="bulkUpload"][data-table-id]').off('click').on('click', function () {
            self.tableId = $(this).data('tableId');
            $("#csv-upload").data('tableId', self.tableId).modal('show');
            // set table id on modal

            self.controlNames = [];
            self.extraData = [];

            $('table#' + self.tableId + ' tbody tr:last')
                .find('input[type!=hidden],select[type!=hidden]')
                .each(function (i, it) {
                    if (!$(it).hasClass('readonly')) {
                        self.controlNames.push(it.name);
                    }
                    if (it.nodeName.toLowerCase() === 'select') {
                        $(it).children().each(function (ind, val) {
                            if (val.value !== '') {
                                self.extraData.push(val.value);
                            }
                        });
                    }
                });
        });

        $('.template-file').off('click').on('click', function () {
            let modelName = $('table#' + $("#csv-upload").data('tableId')).data('modelName');

            // get table id from modal and get attr('data-model-name')
            let json = {};
            let products = {};
            self.controlNames.forEach(function (item, i) {
                //json[item] = item["value"];
                json[item] = '';
            });

            products['products'] = self.extraData.join;
            //let templateWS = XLSX.utils.table_to_sheet ([json]);
            let templateWS = XLSX.utils.json_to_sheet([json]);
            let productTemplateWS = XLSX.utils.json_to_sheet([products]);
            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, templateWS, modelName);
            //XLSX.utils.book_append_sheet(wb, productTemplateWS, "Products");
            XLSX.writeFile(wb, modelName + '_' + $('.template-file span').text());
        });
        /**
         * @namespace CSVFileUploader
         * attaches event handler for the button to download correct form
         */
        $('.template-file').off('click').on('click', function () {
            let modelName = $('table#' + $("#csv-upload").data('tableId')).data('modelName');

            //get table id from modal and get attr('data-model-name')
            let json = {};
            let products = {};
            self.controlNames.forEach(function (item, i) {
                json[item] = '';
            });

            products['products'] = self.extraData.joi;
            //let templateWS = XLSX.utils.table_to_sheet ([json]);
            let templateWS = XLSX.utils.json_to_sheet([json]);
            let productTemplateWS = XLSX.utils.json_to_sheet([products]);
            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, templateWS, modelName);
            //XLSX.utils.book_append_sheet(wb, productTemplateWS, "Products");
            XLSX.writeFile(wb, modelName + '_' + $('.template-file span').text());
        });

        /***
         * @namespace CSVFileUploader
         * Runs everytime the 'Choose File' input is changed.
         */
        $('#csv-upload input[name="csvUpload"]').off('click').on('change', function (event) {
            let files = event.target.files, file;
            if (!files || files.length === 0) {
                $('.csv-upload button[value="csvUpload"]').attr("disabled", true).html('<i class="fa fa-upload"></i> Choose File');
                return;
            }
            file = files[0];
            window.csvUploadFile = file;
            fileChosen.textContent = files[0].name
            let reader = new FileReader();

            reader.onload = function (e) {
                $('#csv-upload button[value="csvUpload"]').attr("disabled", true).html('<i class="fa fa-spin fa-spinner"></i> Processing...');
                let binary = "";
                let content = new Uint8Array(e.target.result);
                let length = content.byteLength;
                for (let i = 0; i < length; i++) {
                    binary += String.fromCharCode(content[i]);
                }
                let xlsxObj = XLSX.read(binary, {type: 'binary'});

                self.dataToSend = dataObj = XLSX.utils.sheet_to_json(xlsxObj.Sheets[xlsxObj.SheetNames[0]], {header: 0});

                self.buildDataArray(self.dataToSend);
            };

            reader.readAsArrayBuffer(file);
        });

        // upload the csv
        $('#csv-upload button[value="csvUpload"]').off('click').on('click', function () {
            if (self.data.length <= 0) {
                alert("No data to upload");
                return;
            }

            $('#csv-upload button[value="csvUpload"]').attr("disabled", true).html('<i class="fa fa-spin fa-spinner"></i> Processing...Please wait');
            setTimeout(function () {
                //self.renderTableView();
                //$(this).attr("disabled", true).html('<i class="fa fa-spin fa-spinner"></i> Uploading...');
                self.renderTableView();
                $(this).text('Upload').attr('disabled', false);
                $("#csv-upload").modal('hide');
                $('.csv-upload input[name="csvUpload"]').val("");
                window.isUploaded = true;
                window.isUploadComplete = true;
            }, 200);
        });

        $('body').off('click').on('click', '.download-file', function (event) {
            let modelName = $('#' + $(this).find('[data-view-file]').data('viewFile')).data('modelName');
            let worksheet = XLSX.utils.json_to_sheet((!self.dataToSend) ? Object.values(tempReturnData[modelName]) : self.dataToSend);
            let workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, modelName);
            XLSX.writeFile(workbook, modelName + '_uploaded.xlsx');
        });

        const fileSelect = document.getElementById("fileSelect"),
            fileElem = document.getElementById("csvUploadFile");

        fileSelect.addEventListener("click", function (e) {
            if (fileElem) {
                fileElem.click();
            }
        }, false);
    };

    this.renderTableView = function () {
        let self = this;
        window.xlsxData[$('#' + self.tableId + '').data('modelName')] = undefined;
        $('#' + self.tableId + ' input, #' + self.tableId + ' button, #' + self.tableId + ' select,[data-table-id="' + self.tableId + '"][value="addRow"]')
            .attr('disabled', false);
        $('#' + self.tableId).parent()
            .find('.download-file')
            .remove();

        if (Object.keys(self.dataToSend).length >= MAX_TABLE_VIEW) {
            // disable all form input, button and select controls
            $('#' + self.tableId + ' input, #' + self.tableId + ' button, #' + self.tableId + ' select,[data-table-id="' + self.tableId + '"][value="addRow"]').attr('disabled', true);
            // enable button to clear imported data
            $('[data-table-id="' + self.tableId + '"][value="bulkUpload"],[data-table-id="' + self.tableId + '"][value="clearUpload"]').attr('disabled', false);

            $('#' + self.tableId)
                .parent()
                .prepend(self._viewFileTemplate.replace('{tableId}', self.tableId).replace('{rows}', Object.keys(self.dataToSend).length));

            let count = 0;
            self.data = [];
            for (let i in self.dataToSend) {
                if (count++ >= MAX_TABLE_VIEW) break;
                self.data.push(self.dataToSend[i]);
            }
            self.uploadCsvFile();
        }

        let tableSelector = $('#' + self.tableId);
        self.controlNames = [];

        $('tbody tr:last', tableSelector).find('input[type!=hidden],select[type!=hidden]').each(function (i, it) {
            self.controlNames.push(it.name);
        });

        if (self.data.length <= MAX_TABLE_VIEW) {

            if (self.data.length > 0) {
                Table.clearRows(tableSelector);
                if (self.data.length > 1) {
                    Table.addRow(tableSelector, self.data.length - 1);
                }
            }

            for (let j in self.controlNames) {
                const controlName = self.controlNames[j];

                tableSelector.find('input[name="' + controlName + '"]').each(function (i, element) {
                    if ($(element).attr("readonly") !== "readonly") {
                        element.value = self.data[i][controlName];
                        $(element).change();
                    } else {
                        let $quantityCtrl = $(element).closest("tr").find("input[name=quantity]");
                        $quantityCtrl.change();
                        //$(element).closest("tr").find("input[name=quantity]").val(quantity).change();
                        //let total = $(element).closest("tr").find("input[name=quantity]").val();
                        $(element).closest("tr").find("input[name=total]").val($quantityCtrl.val()).change();
                    }
                });
                tableSelector.find('select[name="' + controlName + '"]').each(function (i, element) {
                    let value_provided = self.data[i][controlName];
                    if (!value_provided) {
                        return;
                    }
                    $('option', $(element)).each(function (index, option) {
                        if (option.value && option.value.toLowerCase() === (value_provided).toLowerCase()) {
                            $(element).val(option.value).change();
                        }
                    });
                });
            }


        }

        $('button[value="csvUpload"]').html('<i class="fa fa-upload"></i> Upload').attr('disabled', false);
        $('input[name="csvUpload"]').val("");
        $("#csv-upload").modal('hide');

        // once data has been provided, remove the skip button
        $('ul[aria-label="Pagination"]').find('a[data-action="skip"]').addClass('d-none');

        const bulkUploadComplete = new CustomEvent('bulkUploadComplete', {
            detail: {
                modelName: $('#' + self.tableId).data('modelName'),
                tableId: self.tableId,
                controls: self.controlNames
            }
        });
        window.dispatchEvent(bulkUploadComplete);
    };

    this.showErrors = function (errorList, modelName) {
        $('.error-text').remove();
        let errorModal = $('#csv-upload-errors');
        let table = errorModal.find('table');
        Table.clearRows(table);
        let self = this;

        let newRow = null;
        for (let rowNumber in errorList) {
            if (errorList.hasOwnProperty(rowNumber)) {
                for (let i in errorList[rowNumber]) {
                    newRow = Table.addRow(table);
                    let currentRow = newRow.prev();
                    let column = !!(errorList[rowNumber][i]["field"]) ? errorList[rowNumber][i]["field"] : "-";
                    if (!self._mapper[column]) {
                        for (let i in self._mapper) {
                            if ((self._mapper[i]["column"] + "").indexOf(column) >= 0) {
                                column = i;
                                break;
                            }
                        }
                    }
                    let errorMessage = Util.getProperty(errorList[rowNumber][i], "defaultMessage");
                    if (Util.getProperty(errorList[rowNumber][i], "code") === "typeMismatch") {
                        errorMessage = "The value entered is invalid";
                    }
                    if (!!modelName) {
                        $($('[data-model-name="' + modelName + '"] tbody tr').get(rowNumber))
                            .find('input[name="' + column + '"],select[name="' + column + '"]').addClass('error')
                            .after('<code style="color: #bd4147;font-size:.8em;" class="error-text">' + errorMessage + '</code>');
                    }
                    $(currentRow.find('td').get(0)).text((parseInt(rowNumber)));
                    $(currentRow.find('td').get(1)).text(column);
                    $(currentRow.find('td').get(2)).text(errorMessage);
                }
            }
        }
        if (!!newRow) {
            Table.deleteRow(newRow);
        }
        errorModal.modal('show');
        let modalHeight = $(window).height() * .7;
        errorModal.find('.modal-body').attr('style', 'max-height:' + modalHeight + 'px; overflow-x:hidden; overflow-y:scroll;');
    };

    this.buildDataArray = function (rows) {
        console.log('Building Data');
        this.dataToSend = rows;
        this.errorList = {};
        this.data = [];
        let errorEl = $('.error[data-field="file"]');
        errorEl.text('');

        let counter = 0;
        for (let i in rows) {
            if (counter++ > MAX_TABLE_VIEW) {
                break;
            }
            for (let j in this.controlNames) {
                const fieldName = this.controlNames[j];
                const value = rows[i][fieldName];

                if (typeof value == 'undefined') {
                    errorEl.text("Error on line "
                        + (parseInt(i) + 1)
                        + ": Column "
                        + this.controlNames[j]
                        + " invalid value"
                    );
                    this.data = [];
                    if (!this.errorList[(parseInt(i) + 1)]) {
                        this.errorList[(parseInt(i) + 1)] = [{
                            field: this.controlNames[j],
                            defaultMessage: " Column " + this.controlNames[j] + " has invalid value "
                        }];
                    } else {
                        this.errorList[(parseInt(i) + 1)].push({
                            field: this.controlNames[j],
                            defaultMessage: " Column " + this.controlNames[j] + "  invalid value "
                        });
                    }
                }
            }
        }
        if (!Util.isEmpty(this.errorList)) {
            $('.csv-upload button[value="csvUpload"]').attr('disabled', true);
            this.showErrors(this.errorList);
        } else {
            //$('.csv-upload button[value="csvUpload"]').attr('disabled', false);
            $('#csv-upload button[value="csvUpload"]').attr("disabled", false).html('<i class="fa fa-upload"></i> Upload');
        }

        if (Util.isEmpty(this.errorList)) {
            this.data = rows.slice(0, MAX_TABLE_VIEW);
        }
        return this.data;
    };

    /******** Bulk upload end *********/

    this.uploadCsvFile = function () {
        /*    let tmp = [];
         for (let i in this.dataToSend) {
             if (this.dataToSend.hasOwnProperty(i)) {
                 tmp.push(this.dataToSend[i]);
             }
         }
         window.xlsxData[$('#' + this.tableId + '').data('modelName')] = tmp;*/

        let self = this;
        let tmp = [];
        for (let index in this.dataToSend) {
            if (this.dataToSend.hasOwnProperty(index)) {
                let entity = this.dataToSend[index];
                let newEntity = {};
                for (let i in self.controlNames) {
                    if (self.controlNames.hasOwnProperty(i)) {
                        let key = self.controlNames[i];
                        newEntity[key] = Util.getProperty(entity, key);
                    }
                }
                tmp.push(newEntity);
            }
        }
        const modelName = $('#' + this.tableId + '').data('modelName');
        window.xlsxData[modelName] = tmp;
        if (!!window.xlsxDataLoaded) {
            setTimeout(function () {
                window.xlsxDataLoaded(window.xlsxData, modelName);
            }, 1000);
        }
    };


    this._getMapperValue = function (obj, value) {
        let list = window[obj.list];
        for (let i in list) {
            if (Util.getProperty(list[i], obj.searchKey) === value) {
                return {key: obj.column, value: Util.getProperty(list[i], obj.returnKey)};
            }
        }
    };

    this.getMapperValue = function (columnName, value) {
        if (!!this._mapper[columnName]) {
            let map = this._getMapperValue(this._mapper[columnName], value);
            if (!map) {
                return {errors: {column: columnName, value: value}};
            }
            return map;
        } else {
            return {key: columnName, value: value};
        }
    };

    this.buildPostData = function () {
        let postData = {};
        for (let i in this.data) {
            for (let j in this.controlNames) {
                let k = this.controlNames[j];
                let map = this.getMapperValue(k, this.data[i][j]);
                if (!!map && !!map.errors) {
                    swal({
                        title: "CSV Validation Failure",
                        text: ["Invalid value: '", map.errors.value, "' for '", map.errors.column, "' on row ", (i + 1)].join(""),
                        type: "error"
                    }, function () {
                        //
                    })
                } else {
                    postData[map.key + "_" + i] = map.value;
                }
            }
        }
        return postData;
    };
}
