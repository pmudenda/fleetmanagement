'use strict';
let currentIndex = 0;
let numSteps = 0;
let tryValid;



$(document).ready(function () {
    initFormWizard()
});

function initFormWizard() {
    const stepsList = document.getElementsByClassName("steps")[0];
    numSteps = stepsList.children.length;

    $('.nav-tabs > li a[title]').tooltip();

    //Wizard
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        var target = $(e.target);

        if (target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").on('click', function (e) {
        let active = $('.wizard .nav-tabs li.active');
        let indexOfActiveElement = 0;

        $.each(stepsList.children, function (index, li) {
            if($(li).hasClass('active')){
                indexOfActiveElement = index;
            }
        })

        console.log('Current tab ', indexOfActiveElement);
        currentIndex = indexOfActiveElement+1;

        if (indexOfActiveElement) {
        }

        active.next().removeClass('disabled');
        active.addClass('done');
        active.removeClass('active');
        nextTab(active);
    });
    $(".prev-step").click(function (e) {

        var active = $('.wizard .nav-tabs li.active');
        prevTab(active);

    });
}


function nextTab(elem) {
    let isValid = false;
    if (true){
        $(elem).next().find('a[data-toggle="tab"]').click();
    }


    if(currentIndex == numSteps-1){
        $('.skip-btn').addClass('d-none')
    }else {
        $('.skip-btn').removeClass('d-none')
    }



}

function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
    const stepsList = document.getElementsByClassName("steps")[0];
    let numSteps = stepsList.children.length;

    if (currentIndex > 0 || currentIndex < numSteps-1){
        $('.skip-btn').removeClass('d-none')
    }
}

$('.nav-tabs').on('click', 'li', function () {
    $('.nav-tabs li.active').removeClass('active');
    $(this).addClass('active');
});





function validateStep(stepIndex){
    let isValid;
    // const requiredValue = document.querySelectorAll("input, textarea")
    const steps = document.querySelectorAll(".step")


    return isValid
}

validateStep(1)




//////// Try Functions ///////////

function doSomething(){
    tryValid = validateStep(1)
}
