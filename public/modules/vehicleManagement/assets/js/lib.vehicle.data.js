function getVehicleBrands() {
    fetch($('#brands-api').val())
        .then(response => response.json())
        .then(response => {
            let selectElem = $('select[name="brand"]');
            // Populate results
            if (response.state === 'failure') {
                //show errors
                toastr.error('Connection error, Vehicle Brands Could Not Be Retrieved')
                return;
            }

            let vehicleBrands = response['payload'];
            window.vehicleBrands = vehicleBrands;
            window.tmsApp.populateDropDownList(selectElem,
                vehicleBrands, "code",
                ["name"],
                "");

            let brandCode = selectElem.attr('data-value');

            if (brandCode) {
                selectElem.val(brandCode);
                selectElem.trigger('change');
            }
        })
        .catch(function (error) {
            toastr.error(error, 'Connection error')
        });
}
