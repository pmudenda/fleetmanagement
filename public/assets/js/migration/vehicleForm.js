

const testList2 = {
    "insuranceCompanies": "Insurance Companies",
    "accidentTypes": "Accident Types",
    "accidentNature": "Accident Nature",
    "insuraceTypes": "Insurace Types",
    "businessAreas": "Business Areas",
    "vehicleStatus": "Vehicle Status",
    "fuelTypes": "Fuel Types",
    "statusGeneral": "Status General",
    "movementTypes": "Movement Types",
    "insuranceSubtypes": "Insurance Subtypes"

}

function testChange(id){
    document.getElementById("cardTitle").innerText = testList2[id]
    document.getElementById("modalTitle").innerHTML = testList2[id]

}



