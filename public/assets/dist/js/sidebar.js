(function($) {
    "use strict";
    var bdAdmin = {
        initialize: function() {
            this.navbarClock();
            this.inputSearch();
            this.scrollBar();
            this.iconBarMenu();
            this.sideBar();
            this.materialRipple();
            this.toTop();
            this.pageloader();
        },
        navbarClock: function() {
            if ($(".nav-clock")[0]) {
                var a = new Date;
                a.setDate(a.getDate()),
                    setInterval(function() {
                        var a = (new Date).getSeconds();
                        $(".time-sec").html((a < 10 ? "0" : "") + a);
                    }, 1e3),
                    setInterval(function() {
                        var a = (new Date).getMinutes();
                        $(".time-min").html((a < 10 ? "0" : "") + a);
                    }, 1e3),
                    setInterval(function() {
                        var a = (new Date).getHours();
                        $(".time-hours").html((a < 10 ? "0" : "") + a);
                    }, 1e3);
            }
        },
        inputSearch: function() {
            $("body").on("focus", ".search__text", function() {
                $(this).closest(".search").addClass("search--focus");
            }),
                $("body").on("blur", ".search__text", function() {
                    $(this).val(""),
                        $(this).closest(".search").removeClass("search--focus");
                });
        },
        scrollBar: function() {
            new PerfectScrollbar('.sidebar-body',{
                suppressScrollX: true
            });
        },
        iconBarMenu: function() {
            $('.iconbar .nav-link').on('click', function(e) {
                e.preventDefault();
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                $('.iconbar-aside').addClass('show');
                var targ = $(this).attr('href');
                $(targ).addClass('show');
                $(targ).siblings().removeClass('show');
            });
            $('.iconbar-toggle-menu').on('click', function(e) {
                e.preventDefault();
                if (window.matchMedia('(min-width: 992px)').matches) {
                    $('.iconbar .nav-link.active').removeClass('active');
                    $('.iconbar-aside').removeClass('show');
                } else {
                    $('body').removeClass('iconbar-show');
                }
            });
            $('#iconbarCollapse').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('iconbar-show');
                var targ = $('.iconbar .nav-link.active').attr('href');
                $(targ).addClass('show');
            });
            $(document).bind('click touchstart', function(e) {
                e.stopPropagation();
                var content = $(e.target).closest('.main-content').length;
                var iconBarMenu = $(e.target).closest('.sidebar-toggle-icon').length;
                if (content) {
                    $('.iconbar-aside').removeClass('show');
                    if (!iconBarMenu) {
                        $('body').removeClass('iconbar-show');
                    }
                }
            });
        },
        sideBar: function() {
            $(".sidebar-toggle-icon").on('click', function() {
                $(this).toggleClass("open");
            });
            $('#sidebarCollapse').on('click', function() {
                $('.sidebar, .navbar').toggleClass('active');
            });
            $('.overlay').on('click', function() {
                $('.sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });
            $('#sidebarCollapse').on('click', function preventDefault(x) {
                if (x.matches) {
                    $('.overlay').addClass('active');
                } else {
                    $('.overlay').removeClass('active');
                }
                var x = window.matchMedia("(max-width: 700px)");
                preventDefault(x);
                x.addListener(preventDefault)
            });
            $('.sidebar .with-sub').on('click', function(e) {
                e.preventDefault();
                $(this).parent().toggleClass('show');
                $(this).parent().siblings().removeClass('show');
            });
        },
        materialRipple: function() {
            $(".material-ripple").on('click', function(event) {
                var surface = $(this);
                if (surface.find(".material-ink").length === 0) {
                    surface.prepend("<div class='material-ink'></div>");
                }
                var ink = surface.find(".material-ink");
                ink.removeClass("animate");
                if (!ink.height() && !ink.width()) {
                    var diameter = Math.max(surface.outerWidth(), surface.outerHeight());
                    ink.css({
                        height: diameter,
                        width: diameter
                    });
                }
                var xPos = event.pageX - surface.offset().left - (ink.width() / 2);
                var yPos = event.pageY - surface.offset().top - (ink.height() / 2);
                var rippleColor = surface.data("ripple-color");
                ink.css({
                    top: yPos + 'px',
                    left: xPos + 'px',
                    background: rippleColor
                }).addClass("animate");
            });
        },
        toTop: function() {
            $('body').append('<div id="toTop" class="btn-top"><i class="ti-upload"></i></div>');
            $(window).scroll(function() {
                if ($(this).scrollTop() !== 0) {
                    $('#toTop').fadeIn();
                } else {
                    $('#toTop').fadeOut();
                }
            });
            $('#toTop').on('click', function() {
                $("html, body").animate({
                    scrollTop: 0
                }, 600);
                return false;
            });
        },
        pageloader: function() {
            setTimeout(function() {
                $('.page-loader-wrapper').fadeOut();
            }, 50);
        }
    };
    $(document).ready(function() {
        "use strict";
        bdAdmin.initialize();
        $('.metismenu').metisMenu();
    });
    $(window).on("load", function() {
        bdAdmin.pageloader();
    });
}(jQuery));
function editinfo(id) {
    var geturl = $("#url_" + id).val();
    var myurl = geturl + '/' + id;
    var dataString = "id=" + id;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('.editinfo').html(data);
            $('#edit').modal('show');
            $('.datetimepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                maxYear: parseInt(moment().format('YYYY'), 10)
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
            $(".basic-single").select2();
            $("#demo").gs_multiselect();
        }
    });
}
