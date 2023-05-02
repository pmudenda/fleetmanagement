'use strict';
$(function() {
    /*date*/
    $(".date").Inputmask({ mask: "99/99/9999"});
    $(".date2").Inputmask({ mask: "99-99-9999"});
    /*time*/
    $(".hour").Inputmask({ mask: "99:99:99"});
    $(".dateHour").Inputmask({ mask: "99/99/9999 99:99:99"});

    /*phone no*/
    $(".mob_no").Inputmask({ mask: "9999-999-999"});
    $(".phone").Inputmask({ mask: "9999-9999"});
    $(".telphone_with_code").Inputmask({ mask: "(99) 9999-9999"});
    $(".us_telephone").Inputmask({ mask: "(999) 999-9999"});
    $(".ip").Inputmask({ mask: "999.999.999.999"});
    $(".isbn1").Inputmask({ mask: "999-99-999-9999-9"});
    $(".isbn2").Inputmask({ mask: "999 99 999 9999 9"});
    $(".isbn3").Inputmask({ mask: "999/99/999/9999/9"});
    $(".ipv4").Inputmask({ mask: "999.999.999.9999"});
    $(".ipv6").Inputmask({ mask: "9999:9999:9999:9:999:9999:9999:9999"});

    /*numbers*/
    $('.autonumber').autoNumeric('init');
});
