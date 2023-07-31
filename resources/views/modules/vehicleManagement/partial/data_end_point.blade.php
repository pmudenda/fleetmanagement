<input type="hidden"
       id="businessAreaEndpoint"
       name="businessAreaEndpoint"
       value="{{ route('business.areas') }}">
<input type="hidden"
       id="brands-api"
       value="{{ route('brands.get') }}">
<input type="hidden"
       id="modelEndpoint"
       name="modelEndpoint"
       value="{{ route('models.get') }}">
<input type="hidden"
       id="bodyTypesEndpoint"
       name="bodyTypesEndpoint"
       value="{{ route('body_type.get') }}">
<input type="hidden"
       id="orgUnitsEndpoint"
       name="orgUnitsEndpoint"
       value="{{ route('organizational.units',['cache'=> true, 'include_nulls'=> false]) }}">
<input type="hidden"
       id="directoratesEndpoint"
       name="directoratesEndpoint"
       value="{{ route('directorates') }}"/>

<input type="hidden"
       id="costCenterEndpoint"
       name="costCenterEndpoint"
       value="{{ route('cost.centers') }}"/>
<input type="hidden"
       id="businessUnitsEndpoint"
       name="businessUnitsEndpoint"
       value="{{ route('business.units') }}"/>

<input type="hidden"
       id="registeredVehicles"
       name="registeredVehicles"
       value="{{ route('vehicles.list') }}"/>

<input type="hidden"
       id="documentValidationUrl"
       name="documentValidationUrl"
       value="{{ route('document.number.validation') }}">/

<input type="hidden"
       id="transmissionTypeUrl"
       name="transmissionTypeUrl"
       value="{{route('transmission.types')}}"/>
<input type="hidden"
       id="fuelTypesUrl"
       value="{{route('fuel.types')}}"/>

<input type="hidden"
       id="suppliersList"
       value="{{route('suppliers.list')}}"/>

<input type="hidden" id="locationUrl" name="locationUrl"
       value="{{ route('locations') }}">

<input type="hidden" id="tyreUrl" name="locationUrl"
       value="{{ route('tyres.get') }}">

<input type="hidden" id="batteryUrl" name="locationUrl"
       value="{{ route('battery.get') }}">

<input type="hidden"
       id="licenseClassEndpoint"
       name="licenseClassEndpoint"
       value="{{ route('vehicle.licence.classes') }}">

