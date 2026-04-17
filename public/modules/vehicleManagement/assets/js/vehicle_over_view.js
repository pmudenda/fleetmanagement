Vue.component('v-select', VueSelect.VueSelect);
window.VehicleModels = [];
window.organizationUnits = [];
window.businessUnits = [];

// Comprehensive Error Handling and Debugging
(function() {
    'use strict';
    
    // Override jQuery AJAX to catch all errors
    $(document).ajaxSend(function(event, xhr, settings) {
        console.log('AJAX Request:', settings.method?.toUpperCase() || 'GET', settings.url, settings.data);
    });
    
    $(document).ajaxSuccess(function(event, xhr, settings) {
        console.log('AJAX Success:', settings.url, xhr.status);
    });
    
    $(document).ajaxError(function(event, xhr, settings, error) {
        const errorInfo = {
            url: settings.url,
            method: settings.method || 'GET',
            data: settings.data,
            status: xhr.status,
            error: error,
            responseText: xhr.responseText?.substring(0, 200)
        };
        
        console.error('AJAX Error:', errorInfo);
        
        // Show user-friendly error message
        const errorMessage = `Connection error for ${settings.url}: ${error} (${xhr.status})`;
        if (typeof toastr !== 'undefined') {
            toastr.error(errorMessage, 'Connection Error');
        } else {
            alert(errorMessage);
        }
    });
    
    // Add window error handling
    window.addEventListener('error', function(event) {
        console.error('JavaScript Error:', event.error);
    });
    
    window.addEventListener('unhandledrejection', function(event) {
        console.error('Unhandled Promise Rejection:', event.reason);
    });
    
    // Add connection test function
    window.testDashboardConnection = function() {
        console.log('Testing dashboard connection...');
        
        const testEndpoints = [
            { url: '/vehicle-management/analytics/kpi', data: { days: 30 } },
            { url: '/vehicle-management/vehicle/models', data: {} }
        ];
        
        testEndpoints.forEach(async function(endpoint) {
            try {
                const response = await fetch(endpoint.url + '?' + new URLSearchParams(endpoint.data));
                if (response.ok) {
                    console.log(`Connection OK: ${endpoint.url}`);
                } else {
                    console.error(`Connection FAILED: ${endpoint.url} (${response.status})`);
                }
            } catch (error) {
                console.error(`Connection ERROR: ${endpoint.url} - ${error.message}`);
            }
        });
    };
    
    console.log('Dashboard error handling initialized');
    
    // Add global image error handling for missing vehicle photos
    window.addEventListener('error', function(e) {
        if (e.target && e.target.tagName === 'IMG') {
            const img = e.target;
            const src = img.src;
            
            // Check if it's a vehicle registration image
            if (src.includes('vehicleRegistration') || src.includes('FRONT_jpg') || src.includes('REAR_jpg') || src.includes('SIDE_jpg')) {
                console.warn('Vehicle image not found:', src);
                
                // Set a fallback image or hide the broken image
                img.style.display = 'none';
                
                // Optionally add a placeholder
                const placeholder = document.createElement('div');
                placeholder.className = 'vehicle-image-placeholder';
                placeholder.innerHTML = '<i class="fas fa-car text-muted"></i><div class="text-muted small">No image available</div>';
                placeholder.style.cssText = 'display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100px; background: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 4px;';
                
                if (img.parentNode) {
                    img.parentNode.appendChild(placeholder);
                }
                
                // Prevent the error from showing in console
                e.preventDefault();
            }
        }
    }, true);
    
    // Add image load error handler specifically for vehicle images
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleImages = document.querySelectorAll('img[src*="vehicleRegistration"]');
        vehicleImages.forEach(function(img) {
            img.onerror = function() {
                console.warn('Vehicle image load failed:', this.src);
                this.style.display = 'none';
                
                // Add placeholder
                const placeholder = document.createElement('div');
                placeholder.className = 'vehicle-image-placeholder';
                placeholder.innerHTML = '<i class="fas fa-car text-muted"></i><div class="text-muted small">No image available</div>';
                placeholder.style.cssText = 'display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100px; background: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 4px;';
                
                if (this.parentNode) {
                    this.parentNode.appendChild(placeholder);
                }
            };
        });
    });
})();

function setSelectedAccessories() {
    $.each(window.selectedAccessories, function (index, element) {
        $("input[name=" + element?.code + "][value=" + element?.is_present + "]").prop('checked', true).attr('readonly', true);
        $("input[name=COMMENT_" + element.code + "]").val(element?.remarks).attr('readonly', true);
    });
}

function displayVehicleDetails(asyncResponse, requestReference) {
    if (!asyncResponse.success) {
        toastr.error(asyncResponse['message']);
        return;
    }

    if (!asyncResponse.hasOwnProperty('payload')) {
        return;
    }

    let data = asyncResponse['payload']['vehicle'];
    if (!data || data.length === 0) {
        return;
    }

    // Vue.set('vehicleHeaderDetails', )

    window.selectedAccessories = asyncResponse.payload['enteredAccessories'];

    if (window['selectedAccessories']) {
        setSelectedAccessories();
    }

    let hasHeaderId = (requestReference != null && requestReference !== 0);
    Vue.set(app['vehicleHeader'], 'id', requestReference);

    Vue.nextTick(function () {
        Vue.set(app['vehicleHeader'], 'isHeaderSaved', hasHeaderId);
    });

    Vue.set(app['vehicleHeader'], 'registration_type', data['registration_type']);
    Vue.set(app['vehicleHeader'], 'brand_guid', data['brand_guid']);

    let description = data['body_type_name']
        + ' : ' + data['year_of_manufacture']
        + ' ' + data['model_name']
        + ' ' + data['model_code']
        + '. ' + data['chassis_number'];

    $('[data-name="model"]').text(data['model_name']);
    $('[data-name="brand"]').text(data['brand_name']);
    $('[data-name="bodyType"]').text(data['body_type_name']);

    $('[data-name="description"]').text(description);

    $('[data-name="vehicleLocation"]').text(data['location_name'] || '');

    $('[data-name="vehicleState"]').text(data['status_name'] || '');

    $('[data-name="vehicleMileage"]').text(accounting.formatNumber(data['mileage']) + ' Km');

    if (data['has_tom_card'] === 'Y') {
        $('#tom_cardRow').removeClass('d-none');
        $('[data-name="tom_card"]').text('Tom Card');
    } else {
        $('#tom_cardRow').addClass('d-none');
        $('[data-name="tom_card"]').text('');
    }

    if (data['barcode'] != null && data['barcode'] !== 'undefined') {
        setInterval(function () {
            $('#barcode').attr('src', '/storage/' + data['barcode']);
        }, 300);

        $('#barcodeContainer').removeClass('d-none');
    }

    Vue.set(app['vehicleHeader'], 'model_code', data['model_code']);

    $('[data-name="registrationNumber"]').text(data['registration_number']?.trim());

    generateBarcode(window.encodeURI($('[name="gatePassUrl"]').val() + '?ref=' + data['registration_number']));

    Vue.set(app['vehicleHeader'], 'on_boarding_status', data['on_boarding_status']);
    Vue.set(app['vehicleHeader'], 'body_type_guid', data['body_type_guid']);

    if (data['business_unit_code']) {
        $('select[name="user_unit"]').val(data['business_unit_code']);
        $('select[name="user_unit"]').attr('data-value', data['business_unit_code']);
        userUnitChanged();
    }

    setTimeout(() => {
        $('select[name="vehicleLocation"]').trigger('change');
    }, 600);

    setTimeout(() => {
        $('select[name="user_unit"]').trigger('change');
    }, 300);

    Vue.set(app['chassisDetails'], 'chassisNumber', data['chassis_number']);
    $('input[name="chassisNumber"]').val(data['chassis_number']);
    Vue.set(app['chassisDetails'], 'engineNumber', data['engine_number']);
    $('input[name="engineNumber"]').val(data['engine_number']);
    Vue.set(app['chassisDetails'], 'whiteBookSerial', data['white_book_serial']);
    $('input[name="whiteBookSerial"]').val(data['white_book_serial']);

    Vue.set(app['chassisDetails'], 'stickerRegistrationNumber', data['sticker_registration_number']);
    Vue.set(app['chassisDetails'], 'yearOfManufacture', data['year_of_manufacture']);

    Vue.set(app['chassisDetails'], 'registrationDate', data['registration_date']);

    $('input[name="registration_date"]').val(data['registration_date']);

    if (data['registration_date']) {
        let dateOI = data['registration_date'].split(' ')[0];
        document.getElementById("registrationDate").value = dateOI;
    }

    Vue.set(app['chassisDetails'], 'chargeOutRate', data['vehicle_charge_out_rate']);

    $('input[name="chargeOutRate"]').val(data['vehicle_charge_out_rate']);
    $('input[name="chargeOutRate"]').trigger('change');

    Vue.set(app['chassisDetails'], 'requiredMinimumDrivingLicense', data['min_req_driving_license']);
    Vue.set(app['chassisDetails'], 'initialOdometerReading', data['initial_odometer_reading']);
    Vue.set(app['chassisDetails'], 'currentOdometerReading', data['current_odometer_reading']);
    Vue.set(app['chassisDetails'], 'odometerReadingLastService', data['lst_service_odometer_reading']);
    Vue.set(app['chassisDetails'], 'nextServiceOdometerReading', data['nxt_service_odometer-reading']);
    Vue.set(app['chassisDetails'], 'inspectionDate', data['inspection_date']);

    Vue.set(app['engineDetails'], 'numberOfCylinders', data['number_of_cylinders']);
    $('input[name="numberOfCylinders"]').val(data['number_of_cylinders']);
    $('input[name="numberOfCylinders"]').trigger('change');

    Vue.set(app['engineDetails'], 'engineCapacity', data['engine_capacity']);

    $('input[name="engineCapacity"]').val(data['engine_capacity']);
    $('input[name="engineCapacity"]').trigger('change');

    Vue.set(app['engineDetails'], 'actualEnginePower', data['actual_engine_power']);
    $('input[name="actualEnginePower"]').val(data['actual_engine_power']);
    $('input[name="actualEnginePower"]').trigger('change');

    Vue.set(app['engineDetails'], 'claimedEnginePower', data['claimed_engine_power']);

    Vue.set(app['engineDetails'], 'engineBrand', data['engine_brand']);
    Vue.set(app['engineDetails'], 'fuelTypes', data['fuel_types']);

    $('select[name="fuelTypes"]').val(data['fuel_types']);
    $('select[name="fuelTypes"]').trigger('change');


    Vue.set(app['engineDetails'], 'engineType', data['engine_type']);
    $('select[name="engineType"]').val(data['engine_type']);
    $('select[name="engineType"]').trigger('change');

    Vue.set(app['engineDetails'], 'transmissionType', data['transmission_type']);
    Vue.set(app['engineDetails'], 'fuelConsumption', data['fuel_consumption']);

    $('input[name="fuelConsumption"]').val(data['fuel_consumption']);
    $('input[name="fuelConsumption"]').trigger('change');

    Vue.set(app['engineDetails'], 'tank_capacity', data['tank_capacity']);
    Vue.set(app['engineDetails'], 'sub_tank_capacity', data['sub_tank_capacity']);
    Vue.set(app['engineDetails'], 'sub_tank_capacity', data['sub_tank_capacity']);

    $('input[name="tank_capacity"]').val(data['tank_capacity']);
    //$('input[name="tank_capacity"]').trigger('change');

    $('input[name="sub_tank_capacity"]').val(data['sub_tank_capacity']);
    //$('input[name="sub_tank_capacity"]').trigger('change');

    Vue.set(app['otherDetails'], 'numberOfTyres', data['number_of_tyres']);

    $('input[name="numberOfTyres"]').val(data['number_of_tyres']);
    //$('input[name="numberOfTyres"]').trigger('change');

    Vue.set(app['otherDetails'], 'tyreBrand', data['tyre_brand']);
    $('input[name="tyreBrand"]').val(data['tyre_brand']);

    console.log('Setting Front Tyre Size', data['front_tyre_size']);
    $('input[name="frontTyreSize"]').val(data['front_tyre_size']).attr('data-value', data['front_tyre_size']);

    console.log('Setting Rear Tyre Size', data['rear_tyre_size']);

    $('input[name="rearTyreSize"]').val(data['rear_tyre_size']).attr('data-value', data['rear_tyre_size'])

    Vue.set(app['otherDetails'], 'batteryBrand', data['battery_brand']);
    $('input[name="batteryBrand"]').val(data['battery_brand']);

    Vue.set(app['otherDetails'], 'batterySize', data['battery_size']);

    $('input[name="batterySize"]').attr('data-value', data['battery_size']);
    $('input[name="batterySize"]').val(data['battery_size']);//.change();


    Vue.set(app['otherDetails'], 'batteryPower', data['battery_power']);
    $('select[name="batteryPower"]').val(data['battery_power']);

    setTimeout(function () {
        $('select[name="batteryPower"]').trigger('change');
        //$('select[name="batterySize"]').trigger('change');
        //$('select[name="frontTyreSize"]').trigger('change');
        //$('select[name="rearTyreSize"]').trigger('change');
    }, 600);

    Vue.set(app['costingAndValuation'], 'supplierName', data['suppliername']);

    $('select[name="supplierName"]').val(data['suppliername']).attr('readonly', 'readonly');

    $('select[name="supplierName"]').attr('data-value', data['suppliername']);

    $('select[name="supplierName"]').trigger('change');


    //$('input[name="costPrice"]').val(data['costPrice']);
    //$('input[name="costPrice"]').trigger('change');
    Vue.set(app['costingAndValuation'], 'costPrice', data['costprice']);

    let price = data['costprice'];

    $('[name="costPrice"]').val(accounting.formatMoney(price, '', 2, ",", ".")).attr('readonly', 'readonly');


    setTimeout(function () {

        $('input[name="yearOfPurchase"]').val(data['yearofpurchase']);
        $('input[name="purchase_order_number"]').val(data['purchase_order_no']);
        //$('input[name="yearOfPurchase"]').trigger('change');
        //Vue.set(app['costingAndValuation'], 'yearOfPurchase', data['yearOfPurchase']);

        $('input[name="bookValue"]').val(accounting.formatMoney(data['bookvalue'], '', 2, ",", "."))
            .attr('readonly', 'readonly');

        $('input[name="isOperationsVehicle"]').val(data['ispoolvehicle']);

        //$('input[name="bookValue"]').trigger('change');
        //Vue.set(app['costingAndValuation'], 'bookValue', data['bookValue']);

    }, 600);

    Vue.set(app['costingAndValuation'], 'assetNumber', data['assetNumber']);
    //
    let assetNumberInput = document.querySelector("#assetNumber");
    if (!data['assetNumber'] && assetNumberInput) {
        const assetNumber = window.removeSpaces(data['registration_number']);
        assetNumberInput.value = assetNumber
        Vue.set(app['costingAndValuation'], 'assetNumber', assetNumber);

        $('input[name="assetNumber"]').val(assetNumber).attr('readonly', 'readonly');

        $('input[name="assetNumber"]').trigger('change');
    }
    Vue.set(app['costingAndValuation'], 'costOfLicense', data['costoflicense']);
    Vue.set(app['costingAndValuation'], 'premium', data['premium']);

    $('input[name="purchase_order_number"]').val(data['purchase_order_no']);

    if (data['costoflicense']) {
        const formatted = accounting.formatMoney(data['costoflicense'], '', 2, ",", ".")
        if (formatted !== '0.00') {
            $('input[name="costOfLicense"]').val(formatted).attr('readonly', 'readonly');
            $('input[name="costOfLicense"]').trigger('change');
        }
    }

    if (data['premium']) {
        $('input[name="premium"]').val(accounting.formatMoney(data['premium'], '', 2, ",", ".")).attr('readonly', 'readonly');
        $('input[name="premium"]').trigger('change');
    }


    Vue.set(app['bodyDetails'], 'height', data['height']);
    Vue.set(app['bodyDetails'], 'length', data['length']);
    Vue.set(app['bodyDetails'], 'width', data['width']);
    Vue.set(app['bodyDetails'], 'seatCapFront', data['numberofseats']);

    Vue.set(app['bodyDetails'], 'distanceAxle1', data['distanceaxle1']);
    Vue.set(app['bodyDetails'], 'distanceAxle2', data['distanceaxle2']);
    Vue.set(app['bodyDetails'], 'distanceAxle3', data['distanceaxle3']);
    Vue.set(app['bodyDetails'], 'distanceAxle4', data['distanceaxle4']);

    Vue.set(app['weightDetails'], 'tareWeight', data['tareweight']);
    Vue.set(app['weightDetails'], 'grossWeight', data['grossweight']);


    Vue.set(app['assignmentDetails'], 'businessArea', data['business_area_code']);
    Vue.set(app['assignmentDetails'], 'directorate', data['directorate']);
    //Vue.set(app['assignmentDetails'], 'businessUnit', data['directorate']);
    Vue.set(app['assignmentDetails'], 'isOperationsVehicle', data['ispoolvehicle']);

    if (data['ispoolvehicle'] == 'Y') {

        $('input[name="responsibleHODId"]').val(data['responsible_head_id']);
        $('input[name="responsibleHOD"]').val(data['responsible_head_name']);
    } else {
        $('input[name="vehicleHolderId"]').val(data['responsible_head_id']);
        $('input[name="vehicleHolder"]').val(data['responsible_head_name']);
    }


    if (asyncResponse['payload'].hasOwnProperty('documents')) {
        let documents = asyncResponse['payload']['documents'];

        Vue.set(app['documents'], 'insurance', window.filterData('Insurance Cover', 'file_type', documents));
        Vue.set(app['documents'], 'certificate', window.filterData('Motor Vehicle Certificate', 'file_type', documents));
        Vue.set(app['images'], 'leftView', window.filterData("Left View", 'file_type', documents));
        Vue.set(app['images'], 'rightView', window.filterData("Right View", 'file_type', documents));
        Vue.set(app['images'], 'rearView', window.filterData("Back View", 'file_type', documents));
        Vue.set(app['images'], 'frontView', window.filterData("Front View", 'file_type', documents));
        Vue.set(app['documents'], 'purchase_order', window.filterData("Purchase Order", 'file_type', documents));
    }


    function displayFuelCostGraph() {
        let fuelCost = asyncResponse['payload']['cost_by_year'] || [];
        let sparesCost = asyncResponse['payload']['spares_cost_by_year'] || [];

        // Calculate totals
        let totalFuelCost = fuelCost.reduce((sum, item) => sum + parseFloat(item.cost || 0), 0);
        let totalSparesCost = sparesCost.reduce((sum, item) => sum + parseFloat(item.cost || 0), 0);
        let totalCost = totalFuelCost + totalSparesCost;

        // Update total costs display
        $("#totalOwnershipCosts").text(accounting.formatMoney(totalCost, 'ZMW '));

        // Initialize main chart
        let myChart = echarts.init(document.getElementById('main'));
        
        // Prepare monthly trend data
        const monthlyData = prepareMonthlyTrendData(fuelCost, sparesCost);
        
        // Enhanced main view option
        const option = {
            title: {
                text: 'Vehicle Cost Analysis',
                subtext: 'Click to view monthly trends',
                left: 'center'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
                formatter: function(params) {
                    let result = params[0].name + '<br/>';
                    params.forEach(param => {
                        result += param.marker + param.seriesName + ': ' + accounting.formatMoney(param.value, 'ZMW ') + '<br/>';
                    });
                    return result;
                }
            },
            legend: {
                data: ['Fuel', 'Maintenance'],
                top: 30
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {show: true, type: ['line', 'bar', 'stack']},
                    restore: {show: true},
                    saveAsImage: {show: true},
                },
                right: 20
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [{
                type: 'category',
                data: ['Total Costs'],
                axisPointer: {
                    type: 'shadow'
                }
            }],
            yAxis: [{
                type: 'value',
                name: 'Cost (ZMW)',
                axisLabel: {
                    formatter: function(value) {
                        return accounting.formatMoney(value, 'ZMW ');
                    }
                }
            }],
            series: [
                {
                    name: 'Fuel',
                    type: 'bar',
                    data: [totalFuelCost],
                    itemStyle: {
                        color: '#91cc75'
                    }
                },
                {
                    name: 'Maintenance',
                    type: 'bar',
                    data: [totalSparesCost],
                    itemStyle: {
                        color: '#fac858'
                    }
                }
            ]
        };

        myChart.setOption(option);

        // Create pie chart for cost distribution
        createCostDistributionPie(totalFuelCost, totalSparesCost);

        // Add click event for drill-down to monthly trends
        myChart.on('click', function (event) {
            showMonthlyTrends(myChart, monthlyData, option);
        });

        // Create performance scorecards
        createPerformanceScorecards(fuelCost, sparesCost, totalCost);
    }

    function prepareMonthlyTrendData(fuelCost, sparesCost) {
        const allMonths = new Set();
        
        // Collect all unique months
        fuelCost.forEach(item => {
            if (item.period) allMonths.add(item.period);
        });
        sparesCost.forEach(item => {
            if (item.period) allMonths.add(item.period);
        });

        const sortedMonths = Array.from(allMonths).sort();
        
        const fuelData = [];
        const sparesData = [];
        
        sortedMonths.forEach(month => {
            const fuelItem = fuelCost.find(item => item.period === month);
            const sparesItem = sparesCost.find(item => item.period === month);
            
            fuelData.push(fuelItem ? parseFloat(fuelItem.cost) : 0);
            sparesData.push(sparesItem ? parseFloat(sparesItem.cost) : 0);
        });

        return {
            months: sortedMonths,
            fuelData: fuelData,
            sparesData: sparesData
        };
    }

    function showMonthlyTrends(chart, monthlyData, originalOption) {
        const trendOption = {
            title: {
                text: 'Monthly Cost Trends',
                subtext: 'Vehicle Performance Over Time',
                left: 'center'
            },
            tooltip: {
                trigger: 'axis',
                formatter: function(params) {
                    let result = params[0].name + '<br/>';
                    params.forEach(param => {
                        result += param.marker + param.seriesName + ': ' + accounting.formatMoney(param.value, 'ZMW ') + '<br/>';
                    });
                    const total = params.reduce((sum, param) => sum + param.value, 0);
                    result += '<hr/>Total: ' + accounting.formatMoney(total, 'ZMW ');
                    return result;
                }
            },
            legend: {
                data: ['Fuel', 'Maintenance', 'Total'],
                top: 30
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {show: true, type: ['line', 'bar', 'stack']},
                    restore: {show: true},
                    saveAsImage: {show: true},
                },
                right: 20
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: monthlyData.months,
                axisLabel: {
                    rotate: 45
                }
            },
            yAxis: {
                type: 'value',
                name: 'Cost (ZMW)',
                axisLabel: {
                    formatter: function(value) {
                        return accounting.formatMoney(value, 'ZMW ');
                    }
                }
            },
            series: [
                {
                    name: 'Fuel',
                    type: 'line',
                    data: monthlyData.fuelData,
                    smooth: true,
                    itemStyle: {
                        color: '#91cc75'
                    },
                    areaStyle: {
                        opacity: 0.3
                    }
                },
                {
                    name: 'Maintenance',
                    type: 'line',
                    data: monthlyData.sparesData,
                    smooth: true,
                    itemStyle: {
                        color: '#fac858'
                    },
                    areaStyle: {
                        opacity: 0.3
                    }
                },
                {
                    name: 'Total',
                    type: 'line',
                    data: monthlyData.fuelData.map((fuel, index) => fuel + monthlyData.sparesData[index]),
                    smooth: true,
                    itemStyle: {
                        color: '#ee6666'
                    },
                    lineStyle: {
                        width: 3,
                        type: 'dashed'
                    }
                }
            ]
        };

        chart.setOption(trendOption);

        // Add back button
        chart.setOption({
            graphic: [
                {
                    type: 'text',
                    left: 10,
                    top: 20,
                    style: {
                        text: 'Back to Summary',
                        fontSize: 14,
                        fill: '#1890ff'
                    },
                    onclick: function () {
                        chart.setOption(originalOption);
                    }
                }
            ]
        });
    }

    function createCostDistributionPie(fuelCost, sparesCost) {
        const pieChart = echarts.init(document.getElementById('pie'));
        
        const pieOption = {
            title: {
                text: 'Cost Distribution',
                subtext: 'Fuel vs Maintenance',
                left: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: {c} ({d}%)'
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['Fuel', 'Maintenance']
            },
            series: [
                {
                    name: 'Cost Breakdown',
                    type: 'pie',
                    radius: '50%',
                    data: [
                        {value: fuelCost, name: 'Fuel', itemStyle: {color: '#91cc75'}},
                        {value: sparesCost, name: 'Maintenance', itemStyle: {color: '#fac858'}}
                    ],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                    label: {
                        formatter: '{b}: ' + accounting.formatMoney('{c}', 'ZMW ') + ' ({d}%)'
                    }
                }
            ]
        };

        pieChart.setOption(pieOption);
    }

    function createPerformanceScorecards(fuelCost, sparesCost, totalCost) {
        // Calculate performance metrics
        const avgMonthlyFuel = fuelCost.length > 0 ? totalCost / fuelCost.length : 0;
        const avgMonthlyMaintenance = sparesCost.length > 0 ? totalCost / sparesCost.length : 0;
        const highestFuelMonth = fuelCost.reduce((max, item) => 
            parseFloat(item.cost) > (max?.cost || 0) ? item : max, null);
        const highestMaintenanceMonth = sparesCost.reduce((max, item) => 
            parseFloat(item.cost) > (max?.cost || 0) ? item : max, null);

        // Create scorecards HTML
        const scorecardsHtml = `
            <div class="col-4">
                <div class="card mt-5">
                    <div class="card-header">
                        <h6>Performance Metrics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="text-primary">${accounting.formatMoney(avgMonthlyFuel, 'ZMW ')}</h4>
                                    <small>Avg Monthly Fuel</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="text-warning">${accounting.formatMoney(avgMonthlyMaintenance, 'ZMW ')}</h4>
                                    <small>Avg Monthly Maintenance</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">Peak Fuel Month:</small><br>
                                <strong>${highestFuelMonth ? highestFuelMonth.period + ': ' + accounting.formatMoney(highestFuelMonth.cost, 'ZMW ') : 'N/A'}</strong>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted">Peak Maintenance Month:</small><br>
                                <strong>${highestMaintenanceMonth ? highestMaintenanceMonth.period + ': ' + accounting.formatMoney(highestMaintenanceMonth.cost, 'ZMW ') : 'N/A'}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Replace the empty third column with scorecards
        $('.row .col-4').eq(2).html(scorecardsHtml);
    }

    loadExecutiveDashboard();
    }

    // Executive Dashboard Loading Functions
    function loadExecutiveDashboard() {
        loadExecutiveKpi();
        loadMonthlyTrends();
        loadCostDistribution();
        loadTopVehiclesByMetric('total_cost');
        loadCostByOrgUnit();
        loadFleetExceptions();
        loadVehicleDetailsTable();
        
        // Set up event listeners for tabs and filters
        setupDashboardEventListeners();
    }

    function loadExecutiveKpi() {
        $.ajax({
            url: '/vehicle-management/analytics/kpi',
            method: 'GET',
            data: { days: 30 },
            dataType: 'json',
            beforeSend: function() {
                // Show loading state
                $('#activeVehiclesCount').text('Loading...');
                $('#totalFuelCost').text('Loading...');
                $('#totalMaintenanceCost').text('Loading...');
                $('#avgCostPerVehicle').text('Loading...');
                $('#totalOperatingCost').text('Loading...');
                $('#highestCostVehicle').text('Loading...');
                $('#highestCostAmount').text('Loading...');
                $('#maintenanceEvents').text('Loading...');
                $('#fuelEvents').text('Loading...');
            },
            success: function(response) {
                console.log('KPI Response:', response);
                if (response.success && response.data) {
                    updateExecutiveKpiCards(response.data);
                } else {
                    console.error('KPI API returned error:', response.message);
                    showConnectionError('KPI data unavailable');
                }
            },
            error: function(xhr, status, error) {
                console.error('KPI AJAX Error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                showConnectionError('Failed to load KPI data');
            }
        });
    }

    function updateExecutiveKpiCards(kpiData) {
        // Update KPI values with proper formatting
        $('#activeVehiclesCount').text(kpiData.active_vehicles || 0);
        $('#totalFuelCost').text(accounting.formatMoney(kpiData.total_fuel_cost || 0, 'ZMW '));
        $('#totalMaintenanceCost').text(accounting.formatMoney(kpiData.total_maintenance_cost || 0, 'ZMW '));
        $('#avgCostPerVehicle').text(accounting.formatMoney(kpiData.avg_cost_per_vehicle || 0, 'ZMW '));
        
        $('#totalOperatingCost').text(accounting.formatMoney(kpiData.total_operating_cost || 0, 'ZMW '));
        $('#highestCostVehicle').text(kpiData.highest_cost_vehicle || 'N/A');
        $('#highestCostAmount').text(accounting.formatMoney(kpiData.highest_cost || 0, 'ZMW '));
        $('#maintenanceEvents').text(kpiData.vehicles_with_maintenance || 0);
        $('#fuelEvents').text(kpiData.vehicles_with_fuel || 0);
        
        // Update trend indicators with proper color coding
        const trendPercentage = kpiData.cost_trend_percentage || 0;
        const trendClass = trendPercentage > 0 ? 'text-danger' : trendPercentage < 0 ? 'text-success' : 'text-secondary';
        const trendSymbol = trendPercentage > 0 ? '+' : '';
        
        $('#fuelCostTrend').html(`<span class="${trendClass}">${trendSymbol}${trendPercentage}%</span> vs previous period`);
        $('#maintenanceCostTrend').html(`<span class="${trendClass}">${trendSymbol}${trendPercentage}%</span> vs previous period`);
        
        // Update vehicle counts
        $('#avgCostVehiclesCount').text(`${kpiData.active_vehicles || 0} vehicles`);
        $('#maintenanceVehiclesCount').text(`${kpiData.vehicles_with_maintenance || 0} vehicles`);
        $('#fuelVehiclesCount').text(`${kpiData.vehicles_with_fuel || 0} vehicles`);
    }

    function loadMonthlyTrends() {
        $.ajax({
            url: '/vehicle-management/analytics/trends',
            method: 'GET',
            data: { months: 12 },
            dataType: 'json',
            beforeSend: function() {
                $('#monthlyTrendsChart').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><div class="mt-2">Loading trends...</div></div>');
            },
            success: function(response) {
                console.log('Trends Response:', response);
                if (response.success && response.data) {
                    renderMonthlyTrendsChart(response.data);
                } else {
                    console.error('Trends API returned error:', response.message);
                    $('#monthlyTrendsChart').html('<div class="text-center text-danger p-4">Error loading trends: ' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Trends AJAX Error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                $('#monthlyTrendsChart').html('<div class="text-center text-danger p-4">Failed to load trends data</div>');
            }
        });
    }

    function renderMonthlyTrendsChart(trendData) {
        const chartDom = document.getElementById('monthlyTrendsChart');
        const myChart = echarts.init(chartDom);
        
        const periods = trendData.map(d => d.period);
        const fuelCosts = trendData.map(d => parseFloat(d.fuel_cost || 0));
        const maintenanceCosts = trendData.map(d => parseFloat(d.maintenance_cost || 0));
        const totalCosts = trendData.map(d => parseFloat(d.total_operating_cost || 0));
        
        const option = {
            title: {
                text: 'Monthly Cost Trends',
                left: 'center'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross'
                },
                formatter: function(params) {
                    let result = params[0].name + '<br/>';
                    params.forEach(param => {
                        result += param.marker + param.seriesName + ': ' + accounting.formatMoney(param.value, 'ZMW ') + '<br/>';
                    });
                    return result;
                }
            },
            legend: {
                data: ['Fuel Cost', 'Maintenance Cost', 'Total Cost'],
                top: 30
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: periods
            },
            yAxis: {
                type: 'value',
                name: 'Cost (ZMW)',
                axisLabel: {
                    formatter: function(value) {
                        return accounting.formatMoney(value, 'ZMW ');
                    }
                }
            },
            series: [
                {
                    name: 'Fuel Cost',
                    type: 'line',
                    data: fuelCosts,
                    itemStyle: { color: '#91cc75' }
                },
                {
                    name: 'Maintenance Cost',
                    type: 'line',
                    data: maintenanceCosts,
                    itemStyle: { color: '#fac858' }
                },
                {
                    name: 'Total Cost',
                    type: 'line',
                    data: totalCosts,
                    itemStyle: { color: '#ee6666' }
                }
            ]
        };
        
        myChart.setOption(option);
    }

    function loadCostDistribution() {
        $.ajax({
            url: '/vehicle-management/analytics/cost-distribution',
            method: 'GET',
            data: { days: 30 },
            success: function(response) {
                if (response.success && response.data) {
                    renderCostDistributionChart(response.data);
                }
            },
            error: function(xhr, status, error) {
                // Handle authentication redirects gracefully
                if (xhr.status === 302 || xhr.status === 401) {
                    console.warn('Cost distribution requires authentication - skipping chart');
                    return;
                }
                
                // Handle 500 errors that might be authentication issues
                if (xhr.status === 500) {
                    const responseText = xhr.responseText || '';
                    if (responseText.includes('login') || responseText.includes('redirect')) {
                        console.warn('Cost distribution requires authentication - skipping chart');
                        return;
                    }
                }
                
                console.error('Failed to load cost distribution:', {xhr, status, error});
            }
        });
    }

    function renderCostDistributionChart(distributionData) {
        const chartDom = document.getElementById('costDistributionChart');
        const myChart = echarts.init(chartDom);
        
        const totalFuelCost = distributionData.reduce((sum, d) => sum + parseFloat(d.fuel_cost || 0), 0);
        const totalMaintenanceCost = distributionData.reduce((sum, d) => sum + parseFloat(d.maintenance_cost || 0), 0);
        
        const option = {
            title: {
                text: 'Cost Distribution',
                left: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: {c} ({d}%)'
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['Fuel', 'Maintenance']
            },
            series: [
                {
                    name: 'Cost Breakdown',
                    type: 'pie',
                    radius: '50%',
                    data: [
                        { value: totalFuelCost, name: 'Fuel', itemStyle: { color: '#91cc75' } },
                        { value: totalMaintenanceCost, name: 'Maintenance', itemStyle: { color: '#fac858' } }
                    ],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                    label: {
                        formatter: '{b}: ' + accounting.formatMoney('{c}', 'ZMW ') + ' ({d}%)'
                    }
                }
            ]
        };
        
        myChart.setOption(option);
    }

    function loadTopVehiclesByMetric(metric = 'total_cost') {
        $.ajax({
            url: '/vehicle-management/analytics/top-vehicles-metric',
            method: 'GET',
            data: { metric: metric, limit: 10, days: 30 },
            dataType: 'json',
            beforeSend: function() {
                const chartId = metric === 'total_cost' ? 'topVehiclesTotalCostChart' :
                              metric === 'fuel_cost' ? 'topVehiclesFuelCostChart' :
                              metric === 'maintenance_cost' ? 'topVehiclesMaintenanceCostChart' :
                              'topVehiclesMaintenanceEventsChart';
                $('#' + chartId).html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><div class="mt-2">Loading vehicles...</div></div>');
            },
            success: function(response) {
                console.log('Top Vehicles Response:', response);
                if (response.success && response.data) {
                    renderTopVehiclesChart(response.data, metric);
                    updateTopVehiclesList(response.data, metric);
                } else {
                    console.error('Top Vehicles API returned error:', response.message);
                    const chartId = metric === 'total_cost' ? 'topVehiclesTotalCostChart' :
                                  metric === 'fuel_cost' ? 'topVehiclesFuelCostChart' :
                                  metric === 'maintenance_cost' ? 'topVehiclesMaintenanceCostChart' :
                                  'topVehiclesMaintenanceEventsChart';
                    $('#' + chartId).html('<div class="text-center text-danger p-4">Error loading vehicles: ' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Top Vehicles AJAX Error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                const chartId = metric === 'total_cost' ? 'topVehiclesTotalCostChart' :
                              metric === 'fuel_cost' ? 'topVehiclesFuelCostChart' :
                              metric === 'maintenance_cost' ? 'topVehiclesMaintenanceCostChart' :
                              'topVehiclesMaintenanceEventsChart';
                $('#' + chartId).html('<div class="text-center text-danger p-4">Failed to load vehicles data</div>');
            }
        });
    }

    function renderTopVehiclesChart(vehicleData, metric) {
        const chartId = metric === 'total_cost' ? 'topVehiclesTotalCostChart' :
                      metric === 'fuel_cost' ? 'topVehiclesFuelCostChart' :
                      metric === 'maintenance_cost' ? 'topVehiclesMaintenanceCostChart' :
                      'topVehiclesMaintenanceEventsChart';
        
        const chartDom = document.getElementById(chartId);
        if (!chartDom) return;
        
        const myChart = echarts.init(chartDom);
        
        const vehicles = vehicleData.map(d => d.reg_no);
        const costs = metric === 'maintenance_events' ? 
                      vehicleData.map(d => parseInt(d.maintenance_events || 0)) :
                      vehicleData.map(d => parseFloat(d[metric] || 0));
        
        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
                formatter: function(params) {
                    const data = params[0];
                    const value = metric === 'maintenance_events' ? data.value : accounting.formatMoney(data.value, 'ZMW ');
                    return data.name + '<br/>' + (metric.replace('_', ' ').charAt(0).toUpperCase() + metric.replace('_', ' ').slice(1)) + ': ' + value;
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'value'
            },
            yAxis: {
                type: 'category',
                data: vehicles
            },
            series: [
                {
                    name: metric.replace('_', ' ').charAt(0).toUpperCase() + metric.replace('_', ' ').slice(1),
                    type: 'bar',
                    data: costs,
                    itemStyle: { color: '#5470c6' }
                }
            ]
        };
        
        myChart.setOption(option);
    }

    function updateTopVehiclesList(vehicleData, metric) {
        const listId = metric === 'total_cost' ? 'topVehiclesTotalCostList' :
                      metric === 'fuel_cost' ? 'topVehiclesFuelCostList' :
                      metric === 'maintenance_cost' ? 'topVehiclesMaintenanceCostList' :
                      'topVehiclesMaintenanceEventsList';
        
        const listElement = document.getElementById(listId);
        if (!listElement) return;
        
        let listHtml = '';
        vehicleData.forEach((vehicle, index) => {
            const value = metric === 'maintenance_events' ? 
                        vehicle.maintenance_events || 0 :
                        accounting.formatMoney(vehicle[metric] || 0, 'ZMW ');
            const vehicleInfo = `${vehicle.brand_name || 'N/A'} ${vehicle.model_name || 'N/A'}`;
            
            listHtml += `
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${vehicle.reg_no}</h6>
                        <small>#${index + 1}</small>
                    </div>
                    <p class="mb-1">${vehicleInfo}</p>
                    <small class="text-muted">${value}</small>
                </div>
            `;
        });
        
        listElement.innerHTML = listHtml;
    }

    function loadCostByOrgUnit() {
        $.ajax({
            url: '/vehicle-management/analytics/cost-distribution',
            method: 'GET',
            data: { days: 30 },
            success: function(response) {
                if (response.success && response.data) {
                    renderCostByOrgUnitChart(response.data);
                    updateCostByOrgUnitList(response.data);
                }
            },
            error: function() {
                console.error('Failed to load cost by org unit');
            }
        });
    }

    function renderCostByOrgUnitChart(orgData) {
        const chartDom = document.getElementById('costByOrgUnitChart');
        const myChart = echarts.init(chartDom);
        
        const orgUnits = orgData.map(d => d.org_unit || 'Unknown');
        const totalCosts = orgData.map(d => parseFloat(d.total_cost || 0));
        
        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
                formatter: function(params) {
                    const data = params[0];
                    return data.name + '<br/>Total Cost: ' + accounting.formatMoney(data.value, 'ZMW ');
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'value'
            },
            yAxis: {
                type: 'category',
                data: orgUnits
            },
            series: [
                {
                    name: 'Total Cost',
                    type: 'bar',
                    data: totalCosts,
                    itemStyle: { color: '#91cc75' }
                }
            ]
        };
        
        myChart.setOption(option);
    }

    function updateCostByOrgUnitList(orgData) {
        const listElement = document.getElementById('costByOrgUnitList');
        let listHtml = '';
        
        orgData.forEach((org, index) => {
            listHtml += `
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${org.org_unit || 'Unknown'}</h6>
                        <small>#${index + 1}</small>
                    </div>
                    <small class="text-muted">
                        Fuel: ${accounting.formatMoney(org.fuel_cost || 0, 'ZMW ')} | 
                        Maintenance: ${accounting.formatMoney(org.maintenance_cost || 0, 'ZMW ')}
                    </small>
                    <div class="mt-1">
                        <strong>Total: ${accounting.formatMoney(org.total_cost || 0, 'ZMW ')}</strong>
                    </div>
                </div>
            `;
        });
        
        listElement.innerHTML = listHtml;
    }

    function loadFleetExceptions() {
        $.ajax({
            url: '/vehicle-management/analytics/exceptions',
            method: 'GET',
            data: { days: 30 },
            success: function(response) {
                if (response.success && response.data) {
                    updateFleetExceptions(response.data);
                }
            },
            error: function() {
                console.error('Failed to load fleet exceptions');
            }
        });
    }

    function updateFleetExceptions(exceptionData) {
        // Update no maintenance alerts
        const noMaintenanceElement = document.getElementById('noMaintenanceAlerts');
        let noMaintenanceHtml = '';
        
        if (exceptionData.no_maintenance && exceptionData.no_maintenance.length > 0) {
            exceptionData.no_maintenance.forEach(vehicle => {
                noMaintenanceHtml += `
                    <div class="alert alert-warning alert-sm">
                        <strong>${vehicle.REGISTRATION_NUMBER}</strong> - ${vehicle.brand_name || 'N/A'} ${vehicle.model_name || 'N/A'}
                        <br><small class="text-muted">No maintenance in last 6 months</small>
                    </div>
                `;
            });
        } else {
            noMaintenanceHtml = '<div class="text-center text-muted">No vehicles requiring maintenance attention</div>';
        }
        
        noMaintenanceElement.innerHTML = noMaintenanceHtml;
        
        // Update high maintenance alerts
        const highMaintenanceElement = document.getElementById('highMaintenanceAlerts');
        let highMaintenanceHtml = '';
        
        if (exceptionData.high_maintenance && exceptionData.high_maintenance.length > 0) {
            exceptionData.high_maintenance.forEach(vehicle => {
                highMaintenanceHtml += `
                    <div class="alert alert-danger alert-sm">
                        <strong>${vehicle.reg_no}</strong> - ${vehicle.brand_name || 'N/A'} ${vehicle.model_name || 'N/A'}
                        <br><small class="text-muted">
                            Cost: ${accounting.formatMoney(vehicle.maintenance_cost || 0, 'ZMW ')} | 
                            Events: ${vehicle.maintenance_events || 0}
                        </small>
                    </div>
                `;
            });
        } else {
            highMaintenanceHtml = '<div class="text-center text-muted">No vehicles with unusually high maintenance costs</div>';
        }
        
        highMaintenanceElement.innerHTML = highMaintenanceHtml;
    }

    function loadVehicleDetailsTable() {
        // This would load unified monthly summary data for the detailed table
        // Implementation depends on the specific requirements for the table data
        const tableBody = document.getElementById('vehicleDetailsBody');
        tableBody.innerHTML = '<tr><td colspan="10" class="text-center text-muted">Detailed vehicle data will be loaded here</td></tr>';
    }

    function setupDashboardEventListeners() {
        // Tab change listeners
        $('#topVehiclesTabs a').on('shown.bs.tab', function (e) {
            const target = $(e.target).attr('href').replace('#', '');
            const metric = target === 'total-cost' ? 'total_cost' :
                          target === 'fuel-cost' ? 'fuel_cost' :
                          target === 'maintenance-cost' ? 'maintenance_cost' :
                          'maintenance_events';
            loadTopVehiclesByMetric(metric);
        });
        
        // Filter listeners
        $('#dashboardPeriodFilter, #dashboardMetricFilter, #dashboardSearchFilter').on('change', function() {
            loadVehicleDetailsTable();
        });
    }

    function exportDashboardData() {
        // Export functionality for dashboard data
        toastr.info('Export functionality will be implemented');
    }

    function refreshDashboardData() {
        loadExecutiveDashboard();
        toastr.success('Dashboard data refreshed');
    }

    function showConnectionError(message) {
        // Update all dashboard elements with error state
        $('#activeVehiclesCount').text('Error');
        $('#totalFuelCost').text('Error');
        $('#totalMaintenanceCost').text('Error');
        $('#avgCostPerVehicle').text('Error');
        $('#totalOperatingCost').text('Error');
        $('#highestCostVehicle').text('Error');
        $('#highestCostAmount').text('Error');
        $('#maintenanceEvents').text('Error');
        $('#fuelEvents').text('Error');
        
        // Show error message in charts
        $('#monthlyTrendsChart').html('<div class="text-center text-danger p-4">Connection Error: ' + message + '</div>');
        $('#costDistributionChart').html('<div class="text-center text-danger p-4">Connection Error: ' + message + '</div>');
        $('#topVehiclesTotalCostChart').html('<div class="text-center text-danger p-4">Connection Error: ' + message + '</div>');
        $('#costByOrgUnitChart').html('<div class="text-center text-danger p-4">Connection Error: ' + message + '</div>');
        
        // Show error in alerts
        $('#noMaintenanceAlerts').html('<div class="text-center text-danger">Connection Error: ' + message + '</div>');
        $('#highMaintenanceAlerts').html('<div class="text-center text-danger">Connection Error: ' + message + '</div>');
        
        // Show toast notification
        if (typeof toastr !== 'undefined') {
            toastr.error('Connection Error: ' + message, 'Dashboard Error');
        } else {
            console.error('Connection Error:', message);
        }
    }

    function testConnection() {
        // Test basic connectivity
        $.ajax({
            url: '/vehicle-management/analytics/kpi',
            method: 'GET',
            data: { days: 30 },
            timeout: 5000,
            success: function(response) {
                console.log('Connection test successful:', response);
                if (response.success) {
                    toastr.success('Dashboard connection restored');
                    loadExecutiveDashboard();
                } else {
                    showConnectionError('API returned error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Connection test failed:', {status, error, xhr});
                showConnectionError('Cannot connect to dashboard API');
            }
        });
    }

    // Comprehensive Maintenance Tracking Functions
    function loadMaintenanceDetails() {
        // Try multiple sources for registration number
        let registrationNumber = window.vehicle?.registration_number || 
                               window.vehicleHeader?.registration_number ||
                               $('#registrationNumber').text() ||
                               $('[data-name="registrationNumber"]').text();
        
        // Clean up the registration number
        registrationNumber = registrationNumber?.toString().trim();
        
        const period = $('#maintenancePeriod').val();
        
        if (!registrationNumber || registrationNumber === '' || registrationNumber === 'N/A') {
            console.warn('Vehicle registration number not available for maintenance details');
            // Don't show error to user, just log it and continue
            return;
        }

        $.ajax({
            url: '/vehicle-management/maintenance-details',
            method: 'GET',
            data: {
                registration_number: registrationNumber,
                months: period
            },
            beforeSend: function() {
                $('#maintenanceDetailsBody').html(`
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="mt-2">Loading maintenance details...</div>
                        </td>
                    </tr>
                `);
            },
            success: function(response) {
                if (response.success && response.data) {
                    displayMaintenanceDetails(response.data);
                    updateMaintenanceStatistics(response.data);
                    toastr.success('Maintenance details loaded successfully');
                } else {
                    $('#maintenanceDetailsBody').html(`
                        <tr>
                            <td colspan="10" class="text-center text-danger">
                                No maintenance data found for the selected period
                            </td>
                        </tr>
                    `);
                    toastr.warning('No maintenance data found');
                }
            },
            error: function(xhr, status, error) {
                // Handle authentication redirects gracefully
                if (xhr.status === 302 || xhr.status === 401) {
                    console.warn('Maintenance details requires authentication - skipping load');
                    $('#maintenanceDetailsBody').html(`
                        <tr>
                            <td colspan="10" class="text-center text-warning">
                                <i class="fas fa-lock me-2"></i>
                                Please login to view maintenance details
                            </td>
                        </tr>
                    `);
                    return;
                }
                
                // Handle 500 errors that might be authentication issues
                if (xhr.status === 500) {
                    const responseText = xhr.responseText || '';
                    if (responseText.includes('login') || responseText.includes('redirect')) {
                        console.warn('Maintenance details requires authentication - skipping load');
                        $('#maintenanceDetailsBody').html(`
                            <tr>
                                <td colspan="10" class="text-center text-warning">
                                    <i class="fas fa-lock me-2"></i>
                                    Please login to view maintenance details
                                </td>
                            </tr>
                        `);
                        return;
                    }
                }
                
                $('#maintenanceDetailsBody').html(`
                    <tr>
                        <td colspan="10" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading maintenance details: ${error}
                        </td>
                    </tr>
                `);
                console.error('Failed to load maintenance details:', {xhr, status, error});
            }
        });
    }

    function displayMaintenanceDetails(maintenanceData) {
        let tableHtml = '';
        
        if (maintenanceData.length === 0) {
            tableHtml = `
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        No maintenance records found for the selected period
                    </td>
                </tr>
            `;
        } else {
            maintenanceData.forEach(function(record) {
                const formattedDate = record.document_date ? new Date(record.document_date).toLocaleDateString() : 'N/A';
                const formattedCost = record.value_amount ? accounting.formatMoney(record.value_amount, 'ZMW ') : 'ZMW 0.00';
                
                tableHtml += `
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${record.job_card_no || 'N/A'}</td>
                        <td>${record.requi_number || 'N/A'}</td>
                        <td>${record.issue_no || 'N/A'}</td>
                        <td>${record.article_code || 'N/A'}</td>
                        <td>${record.article_description || 'N/A'}</td>
                        <td>${record.vehicle_assignment || 'N/A'}</td>
                        <td>${record.ORGANIZATIONALUNIT || 'N/A'}</td>
                        <td class="text-right font-weight-bold">${formattedCost}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewMaintenanceDetail('${record.job_card_no}')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#maintenanceDetailsBody').html(tableHtml);
    }

    function updateMaintenanceStatistics(maintenanceData) {
        if (maintenanceData.length === 0) {
            $('#totalMaintenanceCostDetail').text('ZMW 0.00');
            $('#maintenanceEventCount').text('0');
            $('#avgMaintenanceCost').text('ZMW 0.00');
            $('#lastMaintenanceDate').text('N/A');
            return;
        }

        // Calculate statistics
        const totalCost = maintenanceData.reduce((sum, record) => sum + parseFloat(record.value_amount || 0), 0);
        const eventCount = maintenanceData.length;
        const avgCost = eventCount > 0 ? totalCost / eventCount : 0;
        
        // Find the most recent maintenance date
        const sortedByDate = maintenanceData.sort((a, b) => new Date(b.document_date) - new Date(a.document_date));
        const lastMaintenance = sortedByDate[0]?.document_date;
        const formattedLastDate = lastMaintenance ? new Date(lastMaintenance).toLocaleDateString() : 'N/A';

        // Update the statistics cards
        $('#totalMaintenanceCostDetail').text(accounting.formatMoney(totalCost, 'ZMW '));
        $('#maintenanceEventCount').text(eventCount);
        $('#avgMaintenanceCost').text(accounting.formatMoney(avgCost, 'ZMW '));
        $('#lastMaintenanceDate').text(formattedLastDate);
    }

    function viewMaintenanceDetail(jobCardNo) {
        // Placeholder function for viewing detailed maintenance information
        toastr.info(`Viewing details for Job Card: ${jobCardNo}`);
        // You can implement a modal or redirect to a detailed view here
    }

    function exportMaintenanceData() {
        // Try multiple sources for registration number
        let registrationNumber = window.vehicle?.registration_number || 
                               window.vehicleHeader?.registration_number ||
                               $('#registrationNumber').text() ||
                               $('[data-name="registrationNumber"]').text();
        
        // Clean up the registration number
        registrationNumber = registrationNumber?.toString().trim();
        
        const period = $('#maintenancePeriod').val();
        
        if (!registrationNumber || registrationNumber === '' || registrationNumber === 'N/A') {
            console.warn('Vehicle registration number not available for maintenance export');
            // Don't show error to user, just log it and continue
            return;
        }

        // Create a CSV export of the maintenance data
        $.ajax({
            url: '/vehicle-management/maintenance-details',
            method: 'GET',
            data: {
                registration_number: registrationNumber,
                months: period
            },
            success: function(response) {
                if (response.success && response.data) {
                    exportToCSV(response.data, `maintenance_${registrationNumber}_${period}months.csv`);
                    toastr.success('Maintenance data exported successfully');
                } else {
                    toastr.warning('No data to export');
                }
            },
            error: function() {
                toastr.error('Failed to export maintenance data');
            }
        });
    }

    function exportToCSV(data, filename) {
        if (data.length === 0) return;

        // Define CSV headers
        const headers = [
            'Document Date', 'Job Card No', 'Requisition No', 'Issue No',
            'Article Code', 'Description', 'Vehicle Assignment', 'Org Unit', 'Cost (ZMW)'
        ];

        // Convert data to CSV format
        const csvContent = [
            headers.join(','),
            ...data.map(record => [
                record.document_date || '',
                record.job_card_no || '',
                record.requi_number || '',
                record.issue_no || '',
                record.article_code || '',
                record.article_description || '',
                record.vehicle_assignment || '',
                record.ORGANIZATIONALUNIT || '',
                record.value_amount || 0
            ].join(','))
        ].join('\n');

        // Create and download the CSV file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function loadFleetSummary() {
        $.ajax({
            url: '/vehicle-management/analytics/fleet-summary',
            method: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    updateFleetSummaryCards(response.data);
                }
            },
            error: function(xhr) {
                console.error('Error loading fleet summary:', xhr);
            }
        });
    }

    function updateFleetSummaryCards(data) {
        const fuelSummary = data.fuel_summary || {};
        const maintenanceSummary = data.maintenance_summary || {};

        $('#activeVehiclesCount').text(fuelSummary.active_vehicles || '0');
        $('#totalFuelCost').text(accounting.formatMoney(fuelSummary.total_fuel_cost || 0, 'ZMW '));
        $('#totalMaintenanceCost').text(accounting.formatMoney(maintenanceSummary.total_maintenance_cost || 0, 'ZMW '));
        
        const totalCost = (parseFloat(fuelSummary.total_fuel_cost || 0) + parseFloat(maintenanceSummary.total_maintenance_cost || 0));
        const avgCost = fuelSummary.active_vehicles > 0 ? totalCost / fuelSummary.active_vehicles : 0;
        $('#avgCostPerVehicle').text(accounting.formatMoney(avgCost, 'ZMW '));
    }

    function loadTopVehiclesAnalytics() {
        const metric = $('#topVehiclesMetric').val();
        
        $.ajax({
            url: '/vehicle-management/analytics/top-vehicles',
            method: 'GET',
            data: {
                metric: metric,
                limit: 10
            },
            success: function(response) {
                if (response.success && response.data) {
                    displayTopVehiclesChart(response.data, response.metric);
                }
            },
            error: function(xhr) {
                console.error('Error loading top vehicles analytics:', xhr);
            }
        });
    }

    function displayTopVehiclesChart(data, metric) {
        const chart = echarts.init(document.getElementById('topVehiclesChart'));
        
        const vehicles = data.map(item => item.reg_no);
        const costs = data.map(item => parseFloat(item.total_cost || item.total_fuel_cost || item.total_maintenance_cost || 0));
        
        const option = {
            title: {
                text: `Top 10 Vehicles by ${metric.replace('_', ' ').toUpperCase()}`,
                left: 'center'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
                formatter: function(params) {
                    const data = params[0];
                    return `Vehicle: ${data.name}<br/>Cost: ${accounting.formatMoney(data.value, 'ZMW ')}`;
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '15%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: vehicles,
                axisLabel: {
                    rotate: 45,
                    fontSize: 10
                }
            },
            yAxis: {
                type: 'value',
                name: 'Cost (ZMW)',
                axisLabel: {
                    formatter: function(value) {
                        return accounting.formatMoney(value, 'ZMW ');
                    }
                }
            },
            series: [{
                name: 'Cost',
                type: 'bar',
                data: costs,
                itemStyle: {
                    color: function(params) {
                        const colors = ['#5470c6', '#91cc75', '#fac858', '#ee6666', '#73c0de', '#3ba272', '#fc8452', '#9a60b4', '#ea7ccc'];
                        return colors[params.dataIndex % colors.length];
                    }
                },
                label: {
                    show: true,
                    position: 'top',
                    formatter: function(params) {
                        return accounting.formatMoney(params.value, 'ZMW ');
                    }
                }
            }]
        };

        chart.setOption(option);
    }

    // Handle metric change for top vehicles
    $(document).on('change', '#topVehiclesMetric', function() {
        loadTopVehiclesAnalytics();
    });

function formatBookValueAsMoney(el) {
    setTimeout(function () {
        document.querySelector('[name="bookValue"]').value = accounting.formatMoney(el.value, '');
    }, 300);
}

function formatCostPriceAsMoney(el) {
    setTimeout(function () {
        document.querySelector('[name="costPrice"]').value = accounting.formatMoney(el.value, '');
        //app['costingAndValuation'].costPrice = formatted;
    }, 300);
}

window.getRegistrationDetails = function (requestReference) {
    console.log("attempting to get vehicle details")
    if (!requestReference || typeof requestReference === 'undefined') {
        //console.log('Returning')
        return;
    }
    const vehicleDetailsElement = document.querySelector('[name="vehicle_details"]');
    console.log(vehicleDetailsElement ? vehicleDetailsElement.value : 'Vehicle details element not found');
    $.ajax({
        type: "GET",
        url: document.querySelector('[name="vehicle_details"]').value,
        data: {reference: requestReference},
        dataType: 'json',
        success: function (asyncResponse) {
            console.log(asyncResponse);
            displayVehicleDetails(asyncResponse, requestReference);
        },
        error: function (xhr, settings, errorThrown) {
            console.log(errorThrown || 'Unknown error occurred');
            toastr.error('Vehicle details could not be retrieved due to connection error', 'Vehicle Details')
        }
    })
}

window.filterData = function (niddle, key, hayStack) {
    let result = hayStack.filter(function (document) {
        return document[key] === niddle;
    })

    if (result.length > 0) return result[0]; else return null;
}

window.removeSpaces = function (value) {
    if (!value) return;
    return value.replace(/\s/g, '');
}

let app = new Vue({
    'el': '#kt_app_main', components: {}, data() {
        return {
            isHeaderSaved: true,
            assignmentDetails: {},
            assignmentDetailsForm: null,
            bodyDetails: {
                numberOfSeats: 0, volumeOfBootTanker: 0, seatCapRear: 0
            },
            bodyDetailsForm: null,
            bodyTypes: [],
            businessAreas: [],
            businessUnits: [],
            chassisDetails: {
                stickerRegistrationNumber: null, status: 'active'
            },
            chassisDetailsForm: null,
            chassisDetailsFormValidator: null,
            configuredModels: [],
            costCenters: [],
            costingAndValuation: {},
            costingDetailsForm: null,
            dataStatus: 0,
            directorates: [],
            document_validity: {
                state: null, message: null
            },
            documents: {},
            engineBrands: [],
            engineDetails: {},
            engineDetailsForm: null,
            engineDetailsFormValidator: null,
            fuelTypes: [],
            images: {
                frontView: null, rearView: null, leftView: null, rightView: null,
            },
            licenseTypes: [],
            organizationalUnits: [],
            otherDetails: {}, /*  regNumberValidity: {
                  state: null,
                  message: null
              },*/
            registrationTypes: [],
            searchedEmployeesList: [],
            selectedBrandModels: [], // forms
            selectedModelCodes: [],
            supplierList: [],
            transmissionTypes: [],
            validators: [],
            vehicleBrands: [],
            vehicleHeader: {
                model: {}, isHeaderSaved: false, registration_type: 'MV'
            }, // validators
            vehicleHeaderForm: null,
            vehicleHeaderFormValidator: null,
            vehicleHeaderId: null,
            vehicle_brand_placeholder: 'Select Vehicle Brand',
            vehicle_model_placeholder: 'Select Model',
            weightDetails: {
                trailerWeight2: 0
            }
        }
    },

    created() {
        this.getBusinessUnits();
        this.getDirectorates();
        this.getBusinessAreas();
        this.getFuelTypes();
        this.loadRegistrationTypes();
        this.loadLicenceClasses();
        this.getTransmissionTypes();
    },

    filters: {
        trimSpaces: function (val) {
            if (!val) return "";
            if (typeof val === 'number') return val;
            return val?.trim();
        }, formatStatus: function (value) {
            if (!value) return 'Saved';
            if (value == '100') {
                return 'Pending General Data Entry';
            } else if (value == '101') {
                return 'Pending Technical Data Entry';
            } else if (value == "102") {
                return 'Pending Accessories Checkin';
            } else if (value == "103") {
                return 'Pending Costing Data Entry';
            } else if (value == "104") {
                return 'Pending Assignment';
            }
        }
    },

    mounted() {
        console.log("%c✔ ZESCO Fleet Master Running", "color: #148f32");
        console.log("%c✔ Vehicle OnBoarding Process", "color: #148f32");

        this.vehicleHeaderForm = document.querySelector('#tms_vehicle_header_form');
        this.chassisDetailsForm = document.querySelector('#tms_chassis_details_form');
        this.engineDetailsForm = document.querySelector('#tms_engine_details_form');
        this.costingDetailsForm = document.querySelector('#tms_costing_valuation_form');
        this.bodyDetailsForm = document.querySelector('#tms_body_weight_form');
        this.assignmentDetailsForm = document.querySelector('#tms_assignment_tab_form');

        let input = document.getElementById("userUnit");

        if (this.vehicleHeader && this.vehicleHeader.id) {
            this.vehicleHeader.isHeaderSaved = true;
        }

        $(document).on('keyup paste', '#chassisNumber', function () {
            this.value = this.value.toLocaleUpperCase();
        });

        $(document).on('keyup paste', '[name="whiteBookSerial"]', function () {
            this.value = this.value.toLocaleUpperCase();
        });
        $(document).on('keyup paste', '[name="engineType"]', function () {
            this.value = this.value.toLocaleUpperCase();
        });


        $(document).on('keyup paste', '#tyreBrand', function () {
            this.value = this.value.toLocaleUpperCase();
        });

        $(document).on('keyup paste', '#batteryBrand', function () {
            this.value = this.value.toLocaleUpperCase();
        });

        $(document).on('keyup paste', '#engineNumber', function () {
            this.value = this.value.toLocaleUpperCase();
        });


        Inputmask({
            "mask": "A{2,3} 9{1,4}"
        }).mask("#registrationNumber");

        Inputmask({
            "mask": "9999"
        }).mask("#yearOfManufacture");

        /* Inputmask({
             "mask": "999/99/A99"
         }).mask(".tyre-size");*/

        /*Inputmask({
            "mask": "99.9"
        }).mask("#fuelConsumption");*/

        /*Inputmask("decimal", {
            "rightAlignNumerics": false
        }).mask("#chargeOutRate");*/

        $(document).on('click', '[data-select="file"]', function () {
            let fileInput = $(this).closest('p').find('input[type="file"]');
            $(fileInput).trigger('click');
        });

        let fileSelects = [].slice.call(document.querySelectorAll('.fileElem'));
        fileSelects.map(function (fileSelect) {
            fileSelect.addEventListener("change", (e) => {
                app.preview(e);
            }, false);
        });

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
                    $(btn).parent().parent().find('p').removeClass('d-none');
                }
            });

        });

        $(document).on('change', '[data-emp="staff_number"]', function (e) {
            let input = e.target;
            let value = input.value;

            let names = app['searchedEmployeesList'].filter(function (user) {
                return user['staff_number'] === value;
            });

            if (names.length === 0) return;

            $(input).closest('tr').find('input[data-emp="name"]').val(names[0].name)

        });

        $("#myPdf").on("change", function (e) {
            var file = e.target.files[0]
            if (file.type == "application/pdf") {
                var fileReader = new FileReader();
                fileReader.onload = function () {
                    var pdfData = new Uint8Array(this.result);
                    // Using DocumentInitParameters object to load binary data.
                    var loadingTask = pdfjsLib.getDocument({data: pdfData});
                    loadingTask.promise.then(function (pdf) {


                        // Fetch the first page
                        var pageNumber = 1;
                        pdf.getPage(pageNumber).then(function (page) {

                            var scale = 1.5;
                            var viewport = page.getViewport({scale: scale});

                            // Prepare canvas using PDF page dimensions
                            var canvas = $("#pdfViewer")[0];
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            // Render PDF page into canvas context
                            var renderContext = {
                                canvasContext: context, viewport: viewport
                            };
                            var renderTask = page.render(renderContext);
                            renderTask.promise.then(function () {

                            });
                        });
                    }, function (reason) {
                        // PDF loading error
                        console.error(reason);
                    });
                };
                fileReader.readAsArrayBuffer(file);
            }
        });
    },

    methods: {

        bodyTypeChanged: function (selectedBody) {
            app['vehicleHeader'].body_type_guid = selectedBody?.guid;
            document.querySelector('#bodyType').value = selectedBody?.guid;
        },

        checkChassisNumberValidity: function () {
            fetch(document.querySelector('#documentValidationUrl').value + '?method=chassis&key=' + app['chassisDetails']['chassisNumber'])
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Chassis validity not verified', 'Connection error');
                        return;
                    }

                    app['document_validity'].state = response['payload'].validity;
                    app['document_validity'].message = response['payload'].message;
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error('Could not retrieve data, some feature might not work.', 'Connection error')
                });
        },

        checkValueChange(element) {
        },

        formatBookValueAsMoney: function (event) {
            setTimeout(function () {
                let formatted = accounting.formatMoney(event.target.value, '');
                console.log('%c' + formatted, "color: #148f32");
                app['costingAndValuation'].bookValue = formatted;
            }, 300);
        },

        formatCostPriceAsMoney: function (event) {
            setTimeout(function () {
                let formatted = accounting.formatMoney(event.target.value, '');
                app['costingAndValuation'].costPrice = formatted;
            }, 300);
        },

        // web UI event
        formatMoney: function (event) {
            setTimeout(function () {
                //ZMW
                let formatted = accounting.formatMoney(event.target.value, '');
                app['chassisDetails'].chargeOutRate = formatted;
            }, 300);
        },

        getBusinessAreas: function () {
            fetch(document.querySelector('#businessAreaEndpoint').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.businessAreas = response['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getBusinessUnits: function () {
            fetch(document.querySelector('#businessUnitsEndpoint').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    window.businessUnits = response['payload'];
                    app.businessUnits = response['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getDirectorates: function () {
            fetch(document.querySelector('#directoratesEndpoint').value)
                .then(response => response.json())
                .then(function (response) {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.directorates = response['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getFuelTypes: function () {
            fetch(document.querySelector('#fuelTypesUrl').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }
                    app.fuelTypes = response.payload;
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getModelLabel: function (val) {
            if (typeof val === 'object' && !Array.isArray(val)) {
                return val['model_name'] + '=>' + val['model_code'];
            }
        },

        getTransmissionTypes: function () {
            fetch(document.querySelector('#transmissionTypeUrl').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }
                    app.transmissionTypes = response.payload;
                })
                .catch(function (error) {
                    toastr.error('Connection error. Could not retrieve VEHICLE TRANSMISSION  data, some feature might not work.')
                });
        },

        /*getVehicleBrands: function () {
            fetch(document.querySelector('#brands-api').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.vehicleBrands = response['payload'];
                    app.engineBrands = response['payload'];
                }).catch(function (error) {
                // notify of error
                toastr.error(
                    'Connection error. Could not retrieve data, some feature might not work.')
            });
        },*/

        /*loadRegistrationTypes: function () {
            this.registrationTypes = [
                {
                    "label": 'Motor Vehicle',
                    'code': 'MV'
                },
                 {
                     "label": 'Boat',
                     'code': 'BT'
                 },
                 {
                     "label": 'Trailer',
                     'code': 'TR'
                 },
            ]
        },*/

        // web UI event
        /*bodyTypeChanged: function (selectedBody) {
            app['vehicleHeader'].body_type_guid = selectedBody?.id;
            document.querySelector('#bodyType').value = selectedBody?.id;
        },*/

        /*formatMoney: function (event) {
            setTimeout(function () {
                let formatted = accounting.formatMoney(event.target.value, 'ZMW ');
                //tmsApp.formatMoney(event.target.value);

                app['chassisDetails'].chargeOutRate = formatted;
                //document.querySelector('#'+event.target.id).value = formatted;
            }, 300);
        },*/

        /*,*/

        /*getBusinessUnits: function () {
            fetch(document.querySelector('#businessUnitsEndpoint').value)
                .then(response => response.json())
                .then(function (response) {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.businessUnits = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },*/

        /* getOrganizationalUnits: function () {

         },*/

        getUserUnitLabel: function (val) {
            if (typeof val === 'object') {
                return val['code_unit'] + '=>' + val.description;
            }
        },

        loadLicenceClasses: function () {
            fetch(document.querySelector('#licenseClassEndpoint').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.licenseTypes = response['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve license category data, some feature might not work.')
                });
        },

        loadRegistrationTypes: function () {
            this.registrationTypes = [
                {
                    "label": 'Motor Vehicle', 'code': 'MV'
                },
                /*{
                    "label": 'Plant Equipment', 'code': 'PE'
                }*/
                /*{
                    "label": 'Boat',
                    'code': 'BT'
                },
                {
                    "label": 'Trailer',
                    'code': 'TR'
                },*/]
        },

        postRequest(data, url, successCallBack, errorCallBack) {
            axios.post(url, data, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'content-type': 'text/json'
                }
            }).then(function (response) {
                successCallBack(response);
            }).catch(function (error) {
                errorCallBack(error);
            });
        },

        postVehicleHeaderData() {
            if (!this.validators) {
                return alert('No Validator Configured');
            }
            this.vehicleHeaderFormValidator.validate().then(function (status) {
                console.log('validated!');
                if (status !== 'Valid') {
                    toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
                    return;
                }

                let el = document.querySelector('#tms_save_vehicle');
                el.setAttribute('data-kt-indicator', 'on');
                el.disabled = true;

                app.postRequest(new FormData($(app.vehicleHeaderForm)[0]), app.vehicleHeaderForm.action, function (response) {
                    let el = document.querySelector('#tms_save_vehicle');
                    let label = el.querySelector(".indicator-label");

                    setTimeout(function () {
                        el.removeAttribute('data-kt-indicator');
                        el.disabled = false;
                    }, 300)

                    if (response.data.state != 'success') {
                        toastr.error(response.data.message);
                        return;
                    }

                    app.vehicleHeaderId = response.data.payload.id;
                    toastr.success(response.data.message);

                    setTimeout(function () {
                        app['vehicleHeader'].isHeaderSaved = true;
                    }, 500)

                    if (el.classList.contains("btn-light-primary")) {
                        el.classList.remove("btn-light-primary");
                        el.classList.add("btn-light");
                        label.innerHTML = "Saved";
                    } else { // follow
                        el.classList.add("btn-light-primary");
                        el.classList.remove("btn-light");
                        app['vehicleHeader'].isHeaderSaved = true;
                        label.innerHTML = "Saved";
                    }

                }, function (error) {
                    let el = document.querySelector('#tms_save_vehicle');
                    let label = el.querySelector(".indicator-label");
                    label.innerHTML = "Submit";
                    el.removeAttribute('data-kt-indicator');
                    el.disabled = false;

                    toastr.error(error.message);

                });
            });

        },

        postVehicleImages() {
            let completionForm = $('#completeRegistrationForm');
            $.ajax({
                'url': $(completionForm).attr('action'),
                'type': 'POST',
                data: new FormData($(completionForm)[0]),
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'content-type': 'text/json'
                }
            }).done(function (response) {

                Swal.fire({
                    text: "Vehicle Registration Completed Successfully," + "You will be refreshed",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                }).then(function () {
                    window.location.reload();
                });

            })
        },

        preview(event) {
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
                        "background-image": "url(" + this.result + ")", 'display': 'block'
                    });
                }

                $(uploadFile).closest('div').find('p').addClass('d-none');
            } else {

                toastr.error('only image (.jpg, .jpeg, .png, .bmp) file types are allowed', 'Invalid File Format Selected')
            }
        },

        registrationTypeChanged(selectedType) {
            console.log(selectedType || 'No type selected')
        },

        switchTabs() {
            let tabs = document.querySelectorAll('a[role="tab"]');
            let activeIndex = 0;
            $.each(tabs, function (index, element) {
                if ($(element).hasClass('active')) {
                    activeIndex = index;
                    return;
                }
            });
            let nextIndex = activeIndex < tabs.length - 1 ? activeIndex + 1 : activeIndex;

            if (nextIndex === activeIndex) {
                return;
            }
            $(tabs[activeIndex]).removeClass('active');
            $(tabs[nextIndex]).addClass('active');

            // switch visible content
            let tabContent = document.querySelector('#myTabContent').children;
            $(tabContent[nextIndex]).addClass('active').addClass('show');
            $(tabContent[activeIndex]).removeClass('active').removeClass('show')
        },

        transmissionTypeChanged: function (transmissionType) {
            document.querySelector('#transmission_type').value = transmissionType?.code + ':' + transmissionType?.name;
        },

        vehicleBrandChanged(selectedValue) {
            this.vehicleHeader.brand_code = selectedValue?.id?.toString().trim();
            this.selectedBrandModels = [];

            app.selectedBrandModels = app['configuredModels'].filter(function (model) {
                return model.brand_code?.toString()?.trim() === app?.vehicleHeader.brand_code?.toString().trim();
            });
        },
    }
});

function userUnitChanged() {
    setTimeout(function () {
        const user_unit = $('#user_unit').val();

        let user_units = (window.organizationUnits || []).filter(function (userUnit) {
            return userUnit['code_unit'].trim() === user_unit.trim();
        });

        let cost_center_code = user_units[0]?.cc_code;
        let business_unit_code = user_units[0]?.bu_code;


        let filteredCostCenters = (window.costCenters || []).filter(function (cost_center) {
            return cost_center['code_cost_center']?.trim() === cost_center_code?.trim();
        });


        if (filteredCostCenters.length !== 0) {
            let costCentreOfInterest = filteredCostCenters[0];
            const costCenterDescription = costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description'];
            $('[name="costCenter"]').val(costCenterDescription);
        }

        let filteredBusinessUnits = (window.businessUnits || []).filter(function (bu) {
            return bu?.code_bu?.trim() === business_unit_code?.trim();
        });


        if (filteredBusinessUnits.length === 0) return;

        let businessUnitOfInterest = filteredBusinessUnits[0];

        const val = businessUnitOfInterest['code_bu'] + ':' + businessUnitOfInterest['description'];
        $('[name="businessUnit"]').val(val);
    }, 1000);
    return;
}

function checkOnboardingHeaderStatus() {
    const headerId = $('[name="headerId"]').val();
    if (!headerId) return;

    if (headerId && parseInt(headerId) > 0) {
        // hide submit and cancel button
        $('.card-header').addClass('view_mode');
    }
}

(function (tmsApp, $) {

    // web UI event
    function formatMoney(event) {
        setTimeout(function () {
            //ZMW
            let formatted = accounting.formatMoney(event.target.value, '');
            //app['chassisDetails'].chargeOutRate = formatted;
        }, 300);
    }

    function nativeUserUnitChanged(user_unit) {

        //Vue.set(app['vehicleHeader'], 'user_unit_code', user_unit);
        document.querySelector('[name="user_unit"]').value = user_unit;

        let filteredUserUnits = (window.organizationUnits || []).filter(function (userUnit) {
            return userUnit['code_unit']?.trim() === user_unit?.trim();
        });

        let cost_center_code = '';
        let business_unit_code = '';
        if (filteredUserUnits.length !== 0) {
            let userUnit = filteredUserUnits[0];
            cost_center_code = userUnit?.cc_code
            business_unit_code = userUnit?.bu_code
        }

        if (cost_center_code === '' || business_unit_code === '') {
            return;
        }

        let filteredCostCenters = (window.costCenters || []).filter(function (cost_center) {
            return cost_center['code_cost_center']?.trim() === cost_center_code?.trim();
        });

        if (filteredCostCenters.length !== 0) {
            let costCentreOfInterest = filteredCostCenters[0];

            //this.assignmentDetails.costCenter = costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description'];
            $('[name="costCenter"]').val(costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description']);
        }

        let filteredBusinessUnits = (window.businessUnits || []).filter(function (bu) {
            return bu.code_bu?.trim() === business_unit_code?.trim();
        });

        if (filteredBusinessUnits.length === 0) return;

        let businessUnitOfInterest = filteredBusinessUnits[0];

        const val = businessUnitOfInterest['code_bu'] + ':' + businessUnitOfInterest['description'];
        $('[name="businessUnit"]').val(val);
        Vue.set(app['assignmentDetails'], 'businessUnit,', val);
    }

    function submitChassisDetails($form) {
        $('.print-error-msg').css('display', 'none');

        if (document.querySelector('[name="front_view"]').files.length == 0) {
            toastr.error('You have not attached the vehicle Front View Image', 'Validation Failure')
            return;
        }
        if (document.querySelector('[name="rear_view"]').files.length == 0) {
            toastr.error('You have not attached the vehicle Back View Image', 'Validation Failure')
            return;
        }
        if (document.querySelector('[name="right_view"]').files.length == 0) {
            toastr.error('You have not attached the vehicle Right View Image', 'Validation Failure')
            return;
        }
        if (document.querySelector('[name="left_view"]').files.length == 0) {
            toastr.error('You have not attached the vehicle Left View Image', 'Validation Failure')
            return;
        }


        $form = document.forms['tmsChassisDetailsForm'];

        if (!$($form).valid()) {
            toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
            return;
        }

        tmsApp.play_alert('sound-submit');
        let formData = new FormData($form);
        formData.set('chargeOutRate', tmsApp.getRawNumber(formData.get('chargeOutRate')).toString());

        tmsApp.asyncPostFormData($form.action, formData, function (asyncResponse) {
            if ('state' in asyncResponse && asyncResponse.state != 'success') {
                if (asyncResponse.hasOwnProperty('errors')) {
                    tmsApp.printErrorMsg(asyncResponse.errors);
                    return
                }

                setTimeout(function () {
                    tmsApp.systemError('Vehicle On-Boarding - General Data', asyncResponse['message'], function () {
                    }, 'error');
                }, 300);
                toastr.error(asyncResponse.message);
                return;
            }

            tmsApp.showSystemMessage('Vehicle On-Boarding - General Data', asyncResponse.message, function () {
                setTimeout(function () {
                    window.location.href = asyncResponse['redirectUrl']
                }, 500);
            }, 'success');
        }, function (xhr, settings, errorThrown) {
            console.log(errorThrown || 'Unknown error occurred')
            setTimeout(function () {
                tmsApp.showErrorMessages(xhr, 'Vehicle On-Boarding');
            }, 300)
        });

    }

    function getSuppliers() {
        fetch(document.querySelector('#suppliersList').value)
            .then(response => response.json())
            .then(function (response) {
                let selectElem = $('select[name="supplierName"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Failed to retrieve Supplier Records', 'Connection Error');
                    return;
                }

                app.supplierList = response['payload'];

                let suppliers = response['payload'];
                tmsApp.populateDropDownList(selectElem, suppliers, "code_supplier", ["code_supplier", "name_of_supplier"], " ==> ", '--Select Supplier--');

                let supplier = selectElem.attr('data-value');
                if (supplier) {
                    selectElem.val(supplier);
                    selectElem.trigger('change');
                }
            }).catch(function (error) {
            toastr.error('Could not Retrieve Data, some feature might not work.', 'Connection error');
        });
    }

    function submitCostValuationDetails() {
        $('.print-error-msg').css('display', 'none');

        if (!$(document.forms['tms_costing_valuation_form']).valid()) {
            toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
            return;
        }

        let form = document.forms['tms_costing_valuation_form'];
        let formData = new FormData(form);
        formData.set('bookValue', tmsApp.getFloat(formData.get('bookValue')).toString());
        formData.set('costPrice', tmsApp.getFloat(formData.get('costPrice')).toString());
        formData.set('costOfLicense', tmsApp.getFloat(formData.get('costOfLicense')).toString());
        formData.set('premium', tmsApp.getFloat(formData.get('premium')).toString());
        tmsApp.play_alert('sound-submit');
        tmsApp.asyncPostFormData(form.action, formData, function (asyncResponse) {
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
                if (asyncResponse.hasOwnProperty('errors')) {
                    tmsApp.printErrorMsg(asyncResponse.errors);
                    return
                }

                setTimeout(function () {
                    tmsApp.systemError('Vehicle On-Boarding - Chassis Details', asyncResponse['message'], function () {
                    }, 'error');
                }, 300);
                toastr.error(asyncResponse.message);
                return;
            }

            tmsApp.showSystemMessage('Vehicle On-Boarding - Cost & Valuation Details', asyncResponse.message, function () {
                setTimeout(function () {
                    window.location.href = asyncResponse['redirectUrl']
                }, 500);
            }, 'success');
        }, function (xhr, settings, errorThrown) {
            console.log(errorThrown || 'Unknown error occurred')
            setTimeout(function () {
                tmsApp.showErrorMessages(xhr, 'Vehicle On-Boarding');
            }, 300)
        });

    }

    function submitEngineDetails() {
        $('.print-error-msg').css('display', 'none');

        let $form = document.forms['engineDetailsForm'];
        const isValid = $($form).valid();

        if (!isValid) {
            toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
            return;
        }

        let formData = new FormData($form);
        formData.set('engineCapacity', tmsApp.getRawNumber(formData.get('engineCapacity')).toString());
        formData.set('tank_capacity', tmsApp.getRawNumber(formData.get('tank_capacity')).toString());

        tmsApp.asyncPostFormData($form.action, formData, function (asyncResponse) {
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
                if (asyncResponse.hasOwnProperty('errors')) {
                    tmsApp.printErrorMsg(asyncResponse.errors);
                    return
                }

                setTimeout(function () {
                    tmsApp.systemError('Vehicle On-Boarding - Technical Data', asyncResponse['message'], function () {
                    }, 'error');
                }, 300);
                toastr.error(asyncResponse.message);
                return;
            }

            tmsApp.showSystemMessage('Vehicle On-Boarding - Technical Data', asyncResponse.message, function () {
                setTimeout(function () {
                    window.location.href = asyncResponse['redirectUrl']
                }, 500);
            }, 'success');
        }, function (xhr, settings, errorThrown) {
            console.log(errorThrown || 'Unknown error occurred')
            setTimeout(function () {
                tmsApp.showErrorMessages(xhr, 'Vehicle On-Boarding');
            }, 300)
        });
    }

    function submitBodyDetails() {
        $('.print-error-msg').css('display', 'none');

        let $form = document.forms['tms_body_weight_form'];
        const isValid = $($form).valid();

        if (!isValid) {
            toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
            return;
        }

        let formData = new FormData($form);
        formData.set('grossWeight', tmsApp.getFloat(formData.get('grossWeight')).toString());
        formData.set('tareWeight', tmsApp.getFloat(formData.get('tareWeight')).toString());

        tmsApp.asyncPostFormData($form.action, formData, function (asyncResponse) {
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
                if (asyncResponse.hasOwnProperty('errors')) {
                    tmsApp.printErrorMsg(asyncResponse.errors);
                    return
                }

                setTimeout(function () {
                    tmsApp.systemError('Vehicle On-Boarding - Body Details', asyncResponse['message'], function () {
                    }, 'error');
                }, 300);
                toastr.error(asyncResponse.message);
                return;
            }

            tmsApp.showSystemMessage('Vehicle On-Boarding - Body Details', asyncResponse.message, function () {
                setTimeout(function () {
                    window.location.href = asyncResponse['redirectUrl']
                }, 500);
            }, 'success');
        }, function (xhr, settings, errorThrown) {
            console.log(errorThrown || 'Unknown error occurred')
            setTimeout(function () {
                tmsApp.showErrorMessages(xhr, 'Vehicle On-Boarding');
            }, 300)
        });
    }

    function submitAssignmentDetails() {
        $('.print-error-msg').css('display', 'none');

        let $form = document.forms['tms_assignment_form'];
        const isValid = $($form).valid();

        if (!isValid) {
            toastr.warning("Sorry, the data did not pass validation check, check the data and try again.", 'Validation');
            return;
        }

        tmsApp.confirm('Completion Of Onboarding', "Are you sure you would like to complete the onboarding of this vehicle?", 'Yes', 'No', function () {
            tmsApp.asyncPostFormData($form.action, new FormData($form), function (asyncResponse) {
                if ('state' in asyncResponse && asyncResponse.state !== 'success') {
                    if (asyncResponse.hasOwnProperty('errors')) {
                        tmsApp.printErrorMsg(asyncResponse.errors);
                        return
                    }

                    setTimeout(function () {
                        tmsApp.systemError('Vehicle On-Boarding - Assignment', asyncResponse['message'], function () {
                        }, 'error');
                    }, 300);
                    toastr.error(asyncResponse.message);
                    return;
                }

                tmsApp.showSystemMessage('Vehicle On-Boarding - Assignment', asyncResponse.message, function () {
                    setTimeout(function () {
                        window.location.href = asyncResponse['redirectUrl']
                    }, 500);
                }, 'success');
            }, function (xhr, settings, errorThrown) {
                console.log(errorThrown || 'Unknown error occurred')
                setTimeout(function () {
                    tmsApp.showErrorMessages(xhr, 'On-Boarding Completion');
                }, 300)
            });
        }, function () {

        });
    }

    function submitAccessoriesDetails() {
        $('.print-error-msg').css('display', 'none');

        let $form = document.forms['tms_accessories_form'];
        const isValid = $($form).valid();

        if (!isValid) {
            toastr.warning("Sorry, the data did not pass validation check, for details, check the indicated fields", 'Validation');
            return;
        }

        tmsApp.asyncPostFormData($form.action, new FormData($form), function (asyncResponse) {
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
                if (asyncResponse.hasOwnProperty('errors')) {
                    tmsApp.printErrorMsg(asyncResponse.errors);
                    return
                }

                setTimeout(function () {
                    tmsApp.systemError('Vehicle On-Boarding - Assignment', asyncResponse['message'], function () {
                    }, 'error');
                }, 300);
                toastr.error(asyncResponse.message);
                return;
            }

            tmsApp.showSystemMessage('Vehicle On-Boarding - Assignment', asyncResponse.message, function () {
                setTimeout(function () {
                    window.location.href = asyncResponse['redirectUrl']
                }, 500);
            }, 'success');
        }, function (xhr, settings, errorThrown) {
            console.log(errorThrown || 'Unknown error occurred')
            setTimeout(function () {
                tmsApp.showErrorMessages(xhr, 'On-Boarding Completion');
            }, 300)
        });
    }

    function getPurchaseOrderDetails() {
        const purchaseOrder = document.querySelector('#purchase_order_number').value;

        if (purchaseOrder.substring(0, 3) != "C02") {
            let message = 'The purchase order number provided is not related to Vehicle purchase, Please contact system Administrator on';
            message += 'Please Contact Fleet Master\n' +
                ' System Administrator on 3309,3350,3351,3306,3307, fleetmaster@zesco.co.zm'
            tmsApp.showSystemMessage('Purchase Order', message, function () {
            }, 'error');
            return;
        }

        let formData = new FormData();
        formData.append('purchase_order_number', purchaseOrder);

        tmsApp.asyncGetFormData(
            $('#purchase_order_number').attr('data-action')
            + '?document_number=' + purchaseOrder,
            formData,
            function (response_data) {
                if (response_data.state !== 'success') {
                    const message = response_data['message'] ? response_data['message']
                        : 'Purchase Order with number ' + purchaseOrder + ' Could not be found';
                    tmsApp.showToast(message, 'error');
                    return;
                }

                let payload = response_data['payload']

                if (!payload || payload.length === 0) {
                    tmsApp.showToast('The purchase order number you provided did not match any record')
                    return
                }

                const supplierData = payload[0];

                if (!supplierData) {
                    return;
                }

                if (['CLOSED', 'ISSUED'].indexOf(supplierData?.po_status_description) < 0) {
                    let message = 'The Purchase Order '
                        + supplierData['document_no']
                        + ' for supplier '
                        + supplierData['name_of_supplier']
                        + ' can not be used as it is in '
                        + supplierData['po_status_description'] +
                        ' State';
                    tmsApp.showSystemMessage('Purchase Order', message, function () {
                    }, 'error');

                    document.querySelector('#purchase_order_number').value = '';
                }

                let selectElem = $('[name="supplierName"]');
                selectElem.val(supplierData['code_supplier']);
                selectElem.trigger('change');
                selectElem.attr('readonly', true).trigger('change');

                let price = supplierData['price'];
                $('[name="costPrice"]').val(tmsApp.formatMoney(price, 2)).attr('readonly', true)
                //costPriceInput.value = ;
                //costPriceInput.setAttribute('readonly', 'readonly');

                $('[name="bookValue"]').val(tmsApp.formatMoney(price, 2)).attr('readonly', true);
                //bookValueInput.value = ;
                //bookValueInput.setAttribute('readonly', 'readonly');

                document.querySelector('#purchase_order_number').value = supplierData['document_no'];

                calculateInsurancePremium(price);

            }, function (xhr) {
                tmsApp.showToast('We could not complete processing your request, please try again later')
            })
    }

    function vehicleWeightValidations(element) {
        const grossWeightCtl = document.querySelector('[name="grossWeight"]');
        const tareWeightCtl = document.querySelector('[name="tareWeight"]');
        switch (element.name) {
            case "tareWeight":
                let grossWeight = grossWeightCtl.value;
                if (grossWeight && typeof parseInt(tmsApp.getFloat(grossWeight)) === 'number') {
                    // if net-weight is a greater than gross weight
                    if (element.value > grossWeight) {
                        tmsApp.showToast('Vehicle net weight can not be more than the gross weight', 'error', 'Validation Error');
                        document.querySelector('#tms_save_body').setAttribute('disabled', 'disabled');
                    } else {
                        document.querySelector('#tms_save_body').removeAttribute('disabled');
                    }
                }
                break;

            case "grossWeight":
                let tareWeight = tareWeightCtl.value;
                if (tareWeight && typeof parseInt(tmsApp.getFloat(tareWeight)) === 'number') {
                    // if net-weight is a greater than gross weight
                    if (element.value < tareWeight) {
                        tmsApp.showToast('Vehicle gross weight can not be less than the net weight', 'error', 'Validation Error');
                        document.querySelector('#tms_save_body').setAttribute('disabled', 'disabled');
                    } else {
                        document.querySelector('#tms_save_body').removeAttribute('disabled');
                    }
                }
                break;
            default:
                break;
        }
    }

    function getVehicleBrands() {
        const brandsApiUrl = $('#brands-api').val() || '/v1/en/vehicle/brands';
        
        if (!brandsApiUrl) {
            console.warn('Brands API URL not found, skipping brands load');
            return;
        }
        
        fetch(brandsApiUrl)
            .then(response => response.json())
            .then(response => {
                let selectElem = $('select[name="brand"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, no data found')
                    return;
                }

                //app.vehicleBrands = response['payload'];
                //app.engineBrands = response['payload'];
                let vehicleBrands = response['payload'];
                tmsApp.populateDropDownList(selectElem, vehicleBrands, "id", ["name"], "");

                let brand_id = selectElem.attr('data-value');
                if (brand_id) {
                    console.log('Brand ID:', brand_id);
                    selectElem.val(brand_id);
                    selectElem.trigger('change');
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    function nativeVehicleBrandChanged() {
        const brandCode = $('select[name="brand"]').val()?.toString().trim();
        if (!brandCode) {
            return;
        }

        let filteredResults = window.VehicleModels.filter(function (model) {
            return model.brand_code?.toString().trim() === brandCode?.toString().trim();
        });

        if (filteredResults.length === 0) {
            //toastr.warning('No Models Found for the selected models', 'Models')
            getConfiguredModels();
        }

        let selectElem = $('select[name="model"]');
        tmsApp.populateDropDownList(selectElem, filteredResults, "id", ["model_name", "model_code"], " => ");

        let model = selectElem.attr('data-value');
        if (model) {
            selectElem.val(model);
            selectElem.trigger('change');
        }
    }

    function postVehicleHeaderData() {
        $('.print-error-msg').css('display', 'none');
        // validate all required information
        if (!$('form[name="vehicleHeaderForm"]').valid()) {
            toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
            return;
        }

        let $form = document.forms['vehicleHeaderForm'];

        tmsApp.asyncPostFormData($form.action, new FormData($form), function (asyncResponse) {
            if (asyncResponse.hasOwnProperty('state') && asyncResponse.state != 'success') {
                if (asyncResponse.hasOwnProperty('errors')) {
                    tmsApp.printErrorMsg(asyncResponse.errors);
                    return
                }

                setTimeout(function () {
                    tmsApp.systemError('Vehicle On-Boarding', asyncResponse['message'], function () {
                    }, 'error');
                }, 300);
                toastr.error(asyncResponse.message);
                return;
            }

            tmsApp.showSystemMessage('Vehicle OnBoarding', asyncResponse.message, function () {
                setTimeout(function () {
                    window.location.href = asyncResponse['redirectUrl']
                }, 500);
            }, 'success');
        }, function (xhr, settings, errorThrown) {
            setTimeout(function () {
                tmsApp.showErrorMessages(xhr, 'Vehicle On-Boarding');
            }, 300)
        });
    }

    function calculateInsurancePremium(currentValue) {
        let insurancePremium = tmsApp.formatMoney(((10 / 100) * currentValue), 2);
        $('#premium').val(insurancePremium).attr('readonly', true);
    }

    function getCostCenters() {
        const $urlCtrl = document.querySelector('#costCenterEndpoint');
        if (!$urlCtrl) return;
        let url = $urlCtrl.value

        if (!url) return;

        fetch(url)
            .then(response => {
                // Handle authentication redirects
                if (response.status === 302 || response.status === 401) {
                    console.warn('Cost centers requires authentication - skipping load');
                    window.costCenters = [];
                    return null;
                }
                
                // Handle 500 errors that might be authentication issues
                if (response.status === 500) {
                    console.warn('Cost centers endpoint error - skipping load');
                    window.costCenters = [];
                    return null;
                }
                
                return response.json();
            })
            .then(function (response) {
                if (!response) return;
                
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    console.warn('Cost centers connection error');
                    window.costCenters = [];
                    return;
                }

                window.costCenters = response['payload'] || [];
                //app.costCenters = response['payload'];
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    function validateRegistrationNumber() {
        let ref = document.querySelector('#registrationNumber').value
        fetch(document.querySelector('#documentValidationUrl').value + '?method=registration_number&key=' + ref)
            .then(response => response.json())
            .then(response => {
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Vehicle registration number could not be verified', 'Connection error')
                    return;
                }

                if (response['payload'].validity) {

                    toastr.success(response['payload'].message, 'Registration Number Validation')
                    let assetNumberInput = document.querySelector("#assetNumber");
                    if (assetNumberInput) {
                        assetNumberInput.value = window.removeSpaces(document.querySelector('#registrationNumber').value);
                    }
                    document.querySelector("#submitBtn").removeAttribute('disabled')
                } else {
                    document.querySelector("#submitBtn").setAttribute('disabled', 'disabled')
                    tmsApp.systemError('Registration Number Validation', 'Duplicate registration number, vehicle already with registration number ' + ref + ' already exists');
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. Could not retrieve data, ' +
                    'some feature might not work.', 'Invalid Registration')
            });
    }

    function getLocations() {
        fetch($('#locationUrl').val())
            .then(response => response.json())
            .then(response => {
                let selectElem = $('select[name="vehicleLocation"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, no data found',
                        'Failed to fetch Locations')
                    return;
                }

                let locations = response['payload'];
                tmsApp.populateDropDownList(selectElem, locations,
                    "location",
                    ["location"],
                    "");

                let location = selectElem.attr('data-value');

                if (location) {
                    selectElem.val(location);
                    selectElem.trigger('change');
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. ' +
                    'Could not retrieve data, some feature might not work.',
                    'Failed to fetch Locations')
            });
    }

    function getConfiguredModels() {
        let url = $('#modelEndpoint').val();
        fetch(url)
            .then(response => response.json())
            .then(response => {
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, no data found',
                        'Vehicle Models')
                    return;
                }
                window.VehicleModels = response['payload'];
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. Could not retrieve data, ' +
                    'some feature might not work.',
                    'Vehicle Models')
            });
    }

    function getChargeOutRate() {
        fetch(document.querySelector('#suppliersList').value)
            .then(response => response.json())
            .then(function (response) {
                let selectElem = $('select[name="supplierName"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Failed to retrieve Supplier Records',
                        'Connection Error');
                    return;
                }

                app.supplierList = response['payload'];

                let suppliers = response['payload'];
                tmsApp.populateDropDownList(selectElem, suppliers, "code_supplier", ["code_supplier", "name_of_supplier"], " ==> ", '--Select Supplier--');

                let supplier = selectElem.attr('data-value');
                if (supplier) {
                    selectElem.val(supplier);
                    selectElem.trigger('change');
                }
            }).catch(function (error) {
            toastr.error('Could not Retrieve Data, some feature might not work.', 'Connection error');
        });
    }

    /*   function getTyresBrands() {
           fetch(document.querySelector('#tyreUrl').value)
               .then(response => response.json())
               .then(response => {

                   let frontTyreElem = $('input[name="frontTyreSize"]');
                   let rearTyreSizeElem = $('input[name="rearTyreSize"]');
                   // Populate results
                   if (response.state === 'failure') {
                       //show errors
                       toastr.error('Connection error, no tyre brand information found')
                       return;
                   }

                   let tyreSizes = response['payload'];

                   //tmsApp.populateDropDownList(frontTyreElem, tyreSizes, "description", ["description"], "");

                   //tmsApp.populateDropDownList(rearTyreSizeElem, tyreSizes, "description", ["description"], "");

                   let frontSize = frontTyreElem.attr('data-value');

                   if (frontSize) {
                       frontTyreElem.val(frontSize);
                       frontTyreElem.trigger('change');
                   }

                   let rearTyreSize = rearTyreSizeElem.attr('data-value');

                   if (rearTyreSize) {
                       rearTyreSizeElem.val(rearTyreSize);
                       rearTyreSizeElem.trigger('change');
                   }
               })
               .catch(function (error) {
                   // notify of error
                   toastr.error('Connection error. Could not retrieve tyre information, some feature might not work.')
               });
       }*/

    /* function getBatterySizes() {
         fetch(document.querySelector('#batteryUrl').value)
             .then(response => response.json())
             .then(response => {

                 let selectElem = $('select[name="batterySize"]');
                 // Populate results
                 if (response.state === 'failure') {
                     //show errors
                     toastr.error('Connection error, no battery size information found')
                     return;
                 }

                 let tyreBrands = response['payload'];
                 tmsApp.populateDropDownList(selectElem, tyreBrands, "description", ["description"], "");

                 let location = selectElem.attr('data-value');

                 if (location) {
                     selectElem.val(location);
                     selectElem.trigger('change');
                 }
             })
             .catch(function (error) {
                 // notify of error
                 toastr.error('Connection error. Could not retrieve battery size information.', 'Connection error')
             });
     }*/

    function checkWhiteBookSerialValidity() {
        let ref = document.querySelector('#whiteBookSerial').value
        fetch(document.querySelector('#documentValidationUrl').value + '?method=motorVehicleCertificate&key=' + ref)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                return response.json();
            })
            .then(response => {
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, white book number could not be verified')
                    return;
                }

                if (response['payload'].validity) {

                    document.querySelector("#tms_save_chassis").removeAttribute('disabled');
                    toastr.success('White Book number valid', 'White Book Number Validation');
                } else {
                    toastr.error('Duplicate White book Serial Number', 'Invalid White book serial')
                    document.querySelector("#tms_save_chassis").setAttribute('disabled', 'disabled')
                    return;
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    function checkChassisNumberValidity() {
        let chassisNumber = document.querySelector('[name="chassisNumber"]').value;
        fetch(document.querySelector('#documentValidationUrl').value + '?method=chassis&key=' + chassisNumber)
            .then(response => response.json())
            .then(response => {
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Vehicle Identification number verification failed', 'Connection error');
                    tmsApp.systemError('Chassis Number Validation', 'Duplicate Chassis number, vehicle already with chassis number ' + chassisNumber + ' already exists');
                    document.querySelector("#tms_save_chassis").setAttribute('disabled', 'disabled')
                    return;
                } else {
                    document.querySelector("#tms_save_chassis").removeAttribute('disabled');
                    toastr.success('Chassis number valid', 'Chassis Number Validation');
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Could not retrieve data, some feature might not work.', 'Connection error')
            });
    }

    function checkEngineNumberValidity() {
        let engineNumber = document.querySelector('[name="engineNumber"]').value;
        fetch(document.querySelector('#documentValidationUrl').value + '?method=engine_number&key=' + engineNumber)
            .then(response => response.json())
            .then(response => {
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Vehicle Engine number verification failed', 'Connection error');
                    tmsApp.systemError('Chassis Number Validation', 'Duplicate Engine number, vehicle already with Engine number number ' + engineNumber + ' already exists');
                    document.querySelector("#tms_save_chassis").setAttribute('disabled', 'disabled')
                    return;
                } else {
                    document.querySelector("#tms_save_chassis").removeAttribute('disabled');
                    toastr.success('Engine Number number valid', 'Engine Number Validation');
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Could not retrieve data, some feature might not work.', 'Connection error')
            });
    }

    function getBodyTypes() {
        fetch(document.querySelector('#bodyTypesEndpoint').value)
            .then(response => response.json())
            .then(response => {

                let selectElem = $('select[name="bodyType"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Failed to get Vehicle Body Types', 'Connection error');
                    return;
                }

                let bodyTypes = response['payload'];
                tmsApp.populateDropDownList(selectElem, bodyTypes, "id", ["body_type_name"], "");

                let bodyTypeId = selectElem.attr('data-value');
                if (bodyTypeId) {
                    selectElem.val(bodyTypeId);
                    selectElem.trigger('change');
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    function getOrganizationalUnits() {
        fetch(document.querySelector('#orgUnitsEndpoint').value)
            .then(response => response.json())
            .then(response => {
                // Populate results
                let selectElem = $('select[name="user_unit"]');

                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, no data found')
                    return;
                }

                let userUnits = response['payload'];
                window.organizationUnits = userUnits;
                tmsApp.populateDropDownList(selectElem, userUnits, "code_unit", ['code_unit', "description"], " => ");

                let userUnitId = selectElem.attr('data-value');
                if (userUnitId) {
                    selectElem.val(userUnitId);
                    selectElem.trigger('change');
                }
            })
            .catch(function (error) {
                // notify of error
                toastr.error('Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    getCostCenters();

    tmsApp.appFormValidator('form[name="vehicleHeaderForm"]', {
        'brand': {
            required: true
        }, 'registrationNumber': {
            required: true
        }, 'model': {
            required: true
        }, 'vehicleLocation': {
            required: true
        }, 'model_code': {
            required: true
        }, 'bodyType': {
            required: true
        }, 'userUnit': {
            required: true
        }
    }, {
        'brand': {
            required: "Vehicle brand is required"
        }, 'registrationNumber': {
            required: "Registration number is required"
        }, 'model': {
            required: "You must declare vehicle model"
        }, 'vehicleLocation': {
            required: "Vehicle location is mandatory"
        }, 'model_code': {
            required: "Vehicle Model code is required"
        }, 'bodyType': {
            required: "Body type is required"
        }, 'userUnit': {
            required: "Select the user unit responsible for the vehicle"
        }
    });

    $("#submitBtn").on('click', function () {
        postVehicleHeaderData();
    });

    $('#registrationNumber').on('keyup paste enter', function () {

        if (!this.value || this.value.replace('_', '').length < 4) {
            return;
        }
        setTimeout(function () {
            validateRegistrationNumber();
        }, 300);
    });

    let saveVehicleHeaderInformation = function (e) {
        e.preventDefault();
        this.postVehicleHeaderData();
    }

    tmsApp.appFormValidator('form[name="tmsChassisDetailsForm"]', {
        'chassisNumber': {
            required: true
        }, 'engineNumber': {
            required: true
        }, 'whiteBookSerial': {
            required: true
        }, 'yearOfManufacture': {
            required: true
        }, 'registrationDate': {
            required: true
        }, 'chargeOutRate': {
            required: true
        }, 'requiredMinimumDrivingLicense': {
            required: true
        }, 'initialOdometerReading': {
            required: true
        }, 'currentOdometerReading': {
            required: true
        }, 'odometerReadingLastService': {
            required: true
        }, /* 'nextServiceOdometerReading': {
                 required: true
             },*/
        'inspectionDate': {
            required: true
        },

        motor_vehicle_certificate: {
            required: true
        }, insurance_cover_note: {
            required: true
        }, front_view: {
            required: true
        }, rear_view: {
            required: true
        }, right_view: {
            required: true
        }, left_view: {
            required: true
        }
    }, {
        chassisNumber: {
            required: "Chassis number is required"
        }, 'engineNumber': {
            required: "Engine number is required"
        }, 'whiteBookSerial': {
            required: "Provide White Book Serial number"
        }, 'yearOfManufacture': {
            required: "Year vehicle was manufactured is required"
        }, 'registrationDate': {
            required: "Indicate when vehicle was registered with the authority"
        }, 'chargeOutRate': {
            required: "You have not provided charge-out rate"
        }, 'requiredMinimumDrivingLicense': {
            required: "Specify the minimum driver's license class required"
        }, 'initialOdometerReading': {
            required: "provide the vehicles initial odometer value"
        }, 'currentOdometerReading': {
            required: "Provide current odometer reading"
        }, 'odometerReadingLastService': {
            required: "Odometer reading at last service is required"
        }, 'nextServiceOdometerReading': {
            required: "Your must provide the odometer reading when vehicle is next due for service"
        }, inspectionDate: {
            required: "Your have not provided the date the vehicle was inspected"
        }, motor_vehicle_certificate: {
            required: "Motor Vehicle Certificate is required"
        }, insurance_cover_note: {
            required: "Insurance Cover Note must be attached"
        }
    });

    $('[name="tmsChassisDetailsForm"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitChassisDetails(e.currentTarget);
    });

    $('[name="supplierName"]').on('change', function (e) {
        document.querySelector("#purchase_order_number").value = '';
    });


    $('[name="chassisNumber"]').on('change paste', function () {
        checkChassisNumberValidity();
    });

    $('[name="engineNumber"]').on('change paste', function () {
        checkEngineNumberValidity();
    });

    tmsApp.appFormValidator('form[name="engineDetailsForm"]', {
        'numberOfCylinders': {
            required: true
        }, 'engineCapacity': {
            required: true
        }, 'fuelTypes': {
            required: true
        }, 'fuelConsumption': {
            required: true
        }, 'engineType': {
            required: true
        },


        'claimedEnginePower': {
            required: true
        }, 'actualEnginePower': {
            required: true
        }, 'engineBrand': {
            required: true
        },

        'transmission_type': {
            required: true
        },

        'tank_capacity': {
            required: true
        },

        'numberOfTyres': {
            required: true
        },

        'tyreBrand': {
            required: true
        },

        'frontTyreSize': {
            required: true
        },

        'rearTyreSize': {
            required: true
        },

        'batteryBrand': {
            required: true
        }, 'batterySize': {
            required: true
        },

        'batteryPower': {
            required: true
        },

    }, {
        'numberOfCylinders': {
            required: 'Number of cylinders is required'
        }, 'engineCapacity': {
            required: 'Engine capacity is required'
        }, 'fuelTypes': {
            required: 'Fuel Type is required'
        }, 'fuelConsumption': {
            required: 'Fuel Consumption is required'
        }, 'engineType': {
            required: 'Engine Code is required'
        },
    });

    $('[name="engineDetailsForm"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitEngineDetails(e.currentTarget);
    });


    tmsApp.appFormValidator('form[name="tms_costing_valuation_form"]', {
        'supplierName': {
            required: true
        }, 'costPrice': {
            required: true
        }, 'yearOfPurchase': {
            required: true
        }, 'bookValue': {
            required: true
        }, 'assetNumber': {
            required: true
        }, 'costOfLicense': {
            required: true
        }, 'premium': {
            required: true
        }, purchaseOrderDocument: {
            required: true
        }
    }, {
        'supplierName': {
            required: "Vehicle Supplier is required"
        }, 'costPrice': {
            required: "Cost is required"
        }, 'yearOfPurchase': {
            required: "You must declare the year vehicle was purchased"
        }, 'bookValue': {
            required: "Item current book value must be declared"
        }, 'assetNumber': {
            required: "Asset number is mandatory for asset management"
        }, 'costOfLicense': {
            required: "Cost of Road Tax & Fitness"
        }, 'premium': {
            required: "You must declare the insurance premium being paid"
        }, 'purchaseOrderDocument': {
            required: 'You must attach the purchase order before submitting'
        }
    });

    $('[name="tms_costing_valuation_form"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitCostValuationDetails();
    });

    $(document).on('keyup', '#chargeOutRate', function () {
        setTimeout(function () {
            let rawValue = accounting.unformat(this.value);
            this.value = accounting.formatMoney(rawValue, 'ZMW ');
        }, 300);
    });


    tmsApp.appFormValidator('form[name="tms_body_weight_form"]', {
        'height': {
            required: true
        }, 'length': {
            required: true
        }, 'width': {
            required: true
        }, 'seatCapFront': {
            required: true
        }, 'tareWeight': {
            required: true
        }, 'grossWeight': {
            required: true
        },
    }, {
        'height': {
            required: "required"
        }, 'length': {
            required: "required"
        }, 'width': {
            required: "required"
        }, 'seatCapFront': {
            required: "required"
        }, 'tareWeight': {
            required: "required"
        }, 'grossWeight': {
            required: "Vehicle Weight is required"
        },
    });

    $('[name="tms_body_weight_form"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitBodyDetails();
    });

    tmsApp.appFormValidator('form[name="tms_assignment_form"]', {
        businessArea: {
            required: true
        }, isPoolVehicle: {
            required: true
        }, directorate: {
            required: true
        }, businessUnit: {
            required: true
        }, costCenter: {
            required: true
        }, isMileageExempt: {
            required: true
        }, responsibleHOD: {
            required: $("#isPoolVehicle:checked")
        },

        responsibleHODId: {
            required: $("#isPoolVehicle:checked")
        }, vehicleHolder: {
            required: $("#isNotPoolVehicle:checked")
        },

        vehicleHolderId: {
            required: $("#isNotPoolVehicle:checked")
        },
    }, {
        businessArea: {
            required: "You must declare the business area"
        }, isPoolVehicle: {
            required: "You must declare if the vehicle is operational or personal to holder"
        }, directorate: {
            required: 'Directorate is required'
        }, businessUnit: {
            required: "Business Unit is required"
        }, costCenter: {
            required: "Cost Center is required"
        }, isMileageExempt: {
            required: "Required"
        }, responsibleHOD: {
            required: "Personnel responsible for vehicles must be declared"
        },

        responsibleHODId: {
            required: "Personnel responsible for vehicles must be declared"
        }, vehicleHolder: {
            required: "Declare the officer assigned the vehicle"
        },

        vehicleHolderId: {
            required: "Declare the officer assigned the vehicle"
        },
    });

    $('[name="tms_assignment_form"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitAssignmentDetails();
    });

    $('[name="tms_accessories_form"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitAccessoriesDetails(e.currentTarget);
    });

    $('[name="poSearchBtn"]').on('click', function (e) {
        let poNumber = $('#purchase_order_number').val();
        if (!poNumber) {
            toastr.error('No Purchase Order number provided');
            return;
        }

        if (poNumber.length < 12) {
            toastr.warning('Invalid Purchase Order number, Please check the number and try again');
            return;
        }
        getPurchaseOrderDetails();
    });

    $('#purchase_order_number').on('keyup paste', function () {
        this.value = this.value.toLocaleUpperCase();
        if (!this.value || this.value.length < 12) {
            return;
        }
        getPurchaseOrderDetails();
    });

    /*$(document).on('change', 'select[name="frontTyreSize"]', function () {
        const frontTyreSize = $('[name="frontTyreSize"]').val()

        $('select[name="rearTyreSize"]').val(frontTyreSize);
        $('select[name="rearTyreSize"]').trigger('change');
    });*/

    // vehicleWeightValidations
    $(document).on('change', 'select[name="brand"]', function () {
        nativeVehicleBrandChanged();
    });

    $(document).on('change', 'select[name="user_unit"]', function () {
        let user_unit = $(this).val();
        nativeUserUnitChanged(user_unit);
    });

    $(document).on('change', 'select[name="model"]', function () {
        const modelId = $(this).val()?.toString().trim();
        if (!modelId) {
            return;
        }

        let filteredResults = window.VehicleModels.filter(function (model) {
            return model.id?.toString().trim() === modelId;
        });

        if (filteredResults.length > 0) {
            document.querySelector('#model_code').value = filteredResults[0]?.model_code;
        }

    });

    $(document).on('change', '.weight_control', function () {
        vehicleWeightValidations(this)
    });

    $(document).on('change paste', '[name="whiteBookSerial"]', function () {
        checkWhiteBookSerialValidity()
    });

    checkOnboardingHeaderStatus();

    $(document).on('click', 'button[data-zfm-view-file]', function () {

        $("#documentView").attr('src', $(this).attr('data-document-url'));
        let fileViewModal = bootstrap.Modal.getOrCreateInstance(document.querySelector('#fileViewModal'))
        fileViewModal.show();
    });

    $(document).on('click', '.card-toolbar .btn', function () {

        switch (this.id) {
            case "editRecordBtn":
                $('.card-header').removeClass('view_mode').addClass('edit_mode')
                document.querySelector('#model_holder').style.display = 'none';
                let $locationHolder = document.querySelector('#locationHolder');
                $locationHolder.style.display = 'none';
                //$('#vehicleLocation').val($locationHolder.value);
                //$('#model_holder').addClass('d-none');
                //$('#model').removeClass('d-none');
                //$('#vehicleLocation').removeClass('d-none');
                //$('#brand').change();
                break;
            case 'cancelEditLink':
                $('.card-header').removeClass('edit_mode').addClass('view_mode')
                document.querySelector('#model_holder').style.display = null;
                document.querySelector('#locationHolder').style.display = null;

                $('#model').addClass('d-none');
                $('#vehicleLocation').addClass('d-none');
                break;
            case "submitBtn":
                break;
            case "resetFormBtn":
                document.forms['tms_vehicle_header_form'].reset();
                break;
            case "printDisk":

                break;
            default:
                break;
        }
    });

    $(document).on('click', '#print', function () {
        let prtContent = document.getElementById("diskArea");
        let WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    });


    getConfiguredModels();

    getVehicleBrands();

    getOrganizationalUnits();

    getBodyTypes();

    getLocations();

    // getTyresBrands();
    // getBatterySizes();

    getSuppliers();

    new tmsApp.fileUploader().makeSingleFileUploader();

    console.log('this has run')
    if (window.reference) {
        window.getRegistrationDetails(window.reference);
    }
})(window.tmsApp || {}, jQuery);

