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
