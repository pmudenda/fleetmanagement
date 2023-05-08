const make = document.getElementById("make");
const model = document.getElementById("modelNo");


//////////////////////////////////////////////  Station Details ////////////////////////////////////////////////


const options = {
    'Select Make': [

    ],
  'Benz': [
    'C200',
    'E300',
    '5921'
  ],
  'Toyota': [
    '1652',
    '65372',
    'gdha'
  ],
  'Honda': [
    '6821',
    '6371',
    'gajd'
  ],
  'Kaya': [
    'Yellow'
  ]
};

// Populate make dropdown
Object.keys(options).forEach((key) => {
  const option = document.createElement('option');
  option.value = key;
  option.textContent = key;
  make.appendChild(option);
});

make.addEventListener("change", () => {
    model.removeAttribute("disabled")
  const selectedMake = make.value;
  const modelOptions = options[selectedMake];

  // Clear previous model options
  model.innerHTML = "";

  // Add new model options
  modelOptions.forEach((modelOption) => {
    const option = document.createElement('option');
    option.value = modelOption;
    option.textContent = modelOption;
    model.appendChild(option);
  });

  if(selectedMake === "Select Make"){
    const sub = document.createElement('option')
    sub.value = "Select Model No"
    sub.textContent = "Select Model"
    model.appendChild(sub)
    model.setAttribute("disabled", true)
  }


});


//////////////////////////////////////////////  Station Details //////////////////////////////////////////////////////

//////////////////////////////////////////////  Assignment Details  //////////////////////////////////////////////////





const dummyOptions = ["One", "Two", "Three"]

const optionCreation = (options, name) => {

    const optionElement = document.getElementById(name)

    options.forEach((item) => {
      const option = document.createElement("option")
      option.value = item
      option.textContent = item;
      optionElement.appendChild(option)
    })
}

optionCreation(dummyOptions, "businessUnit")
optionCreation(dummyOptions, "costCenter")
optionCreation(dummyOptions, "directorate")










//////////////////////////////////////////////  Assignment Details  //////////////////////////////////////////////////


//////////////////////////////////////////////  Vehicle Images  //////////////////////////////////////////////////////


//////////////////////////////////////////////  Vehicle Images  //////////////////////////////////////////////////////



const loadModelByMaker = (el, url, type) => {
    // console.log(el.value)
    // console.log(url)
    // console.log(type)
}


////////////////////////////////////////////// Create Items //////////////////////////////////////////////////////////

const getVehicleRadioYes = document.getElementById("poolVehicle-yes")
const getVehicleRadioNo = document.getElementById("poolVehicle-no")
const responsibleUserName = document.getElementById("responsibleUserName")
const responsibleUserNumber = document.getElementById("responsibleUserNumber")
const operatorName = document.getElementById("operatorName")
const operatorNumber = document.getElementById("operatorNumber")
const supervisorName = document.getElementById("supervisorName")
const supervisorNumber = document.getElementById("supervisorNumber")

const assignedToName = document.getElementById("assignedToName")
const assignedToNumber = document.getElementById("assignedToNumber")


getVehicleRadioYes.addEventListener("change", function (){
    if (getVehicleRadioYes.checked){
        responsibleUserName.style.display = "block"
        responsibleUserNumber.style.display = "block"
        operatorName.style.display = "block"
        operatorNumber.style.display = "block"
        supervisorName.style.display = "block"
        supervisorNumber.style.display = "block"
    }
})

getVehicleRadioNo.addEventListener("change", function (){
    if (getVehicleRadioNo.checked){

        responsibleUserName.style.display = "none"
        responsibleUserNumber.style.display = "none"
        operatorName.style.display = "none"
        operatorNumber.style.display = "none"
        supervisorName.style.display = "none"
        supervisorNumber.style.display = "none"

        assignedToName.style.display = "block"
        assignedToNumber.style.display = "block"






    }

})






