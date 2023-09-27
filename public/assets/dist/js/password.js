/*!
(c) 2023 Lovemore Daka
*/
/*! *****************************************************************************
Login screen password visibility toggle
Copyright (c) 2023 Zambia Electricity Supply Company
***************************************************************************** */

function passShow() {
    "use strict";
    var x = document.getElementById("password");
    if (x.type === "password") {
        $("i").removeClass("fa fa-eye-slash");
        $("i").addClass("fa fa-eye");
        x.type = "text";
    } else {
        $("i").removeClass("fa fa-eye");
        $("i").addClass("fa fa-eye-slash");
        x.type = "password";
    }
}
