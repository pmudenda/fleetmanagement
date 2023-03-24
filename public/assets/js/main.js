let KTApp = (function () {
    let initialized = false;
    let select2FocusFixInitialized = false;
    let countUpInitialized = false;

    let createBootstrapTooltip = function (el, options) {
        if (el.getAttribute("data-kt-initialized") === "1") {
            return;
        }

        let delay = {};

        // Handle delay options
        if (el.hasAttribute("data-bs-delay-hide")) {
            delay["hide"] = el.getAttribute("data-bs-delay-hide");
        }

        if (el.hasAttribute("data-bs-delay-show")) {
            delay["show"] = el.getAttribute("data-bs-delay-show");
        }

        if (delay) {
            options["delay"] = delay;
        }

        // Check dismiss options
        if (
            el.hasAttribute("data-bs-dismiss") &&
            el.getAttribute("data-bs-dismiss") === "click"
        ) {
            options["dismiss"] = "click";
        }

        // Initialize popover
        let toolTip = new bootstrap.Tooltip(el, options);

        // Handle dismiss
        if (options["dismiss"] && options["dismiss"] === "click") {
            // Hide popover on element click
            el.addEventListener("click", function (e) {
                // toolTip.hide();
            });
        }

        el.setAttribute("data-kt-initialized", "1");

    };

    let createBootstrapTooltips = function () {
        let tooltipTriggerList = [].slice.call(
            document.querySelectorAll("[data-bs-toggle=\"tooltip\"]")
        );

        let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            createBootstrapTooltip(tooltipTriggerEl, {});
        });
    };

    let createBootstrapPopover = function (el, options) {
        if (el.getAttribute("data-kt-initialized") === "1") {
            return;
        }

        let delay = {};

        // Handle delay options
        if (el.hasAttribute("data-bs-delay-hide")) {
            delay["hide"] = el.getAttribute("data-bs-delay-hide");
        }

        if (el.hasAttribute("data-bs-delay-show")) {
            delay["show"] = el.getAttribute("data-bs-delay-show");
        }

        if (delay) {
            options["delay"] = delay;
        }

        // Handle dismiss option
        if (el.getAttribute("data-bs-dismiss") === "true") {
            options["dismiss"] = true;
        }

        if (options["dismiss"] === true) {
            options["template"] =
                "<div class=\"popover\" role=\"tooltip\"><div class=\"popover-arrow\"></div><span class=\"popover-dismiss btn btn-icon\"></span><h3 class=\"popover-header\"></h3><div class=\"popover-body\"></div></div>";
        }

        // Initialize popover
        var popover = new bootstrap.Popover(el, options);

        // Handle dismiss click
        if (options["dismiss"] === true) {
            var dismissHandler = function (e) {
                popover.hide();
            };

            el.addEventListener("shown.bs.popover", function () {
                var dismissEl = document.getElementById(el.getAttribute("aria-describedby"));
                dismissEl.addEventListener("click", dismissHandler);
            });

            el.addEventListener("hide.bs.popover", function () {
                var dismissEl = document.getElementById(el.getAttribute("aria-describedby"));
                dismissEl.removeEventListener("click", dismissHandler);
            });
        }

        el.setAttribute("data-kt-initialized", "1");

        return popover;
    };

    let createBootstrapPopovers = function () {
        let popoverTriggerList = [].slice.call(
            document.querySelectorAll("[data-bs-toggle=\"popover\"]")
        );

        //let popoverList =
        popoverTriggerList.map(function (popoverTriggerEl) {
            createBootstrapPopover(popoverTriggerEl, {});
        });
    };

    let createBootstrapToasts = function () {
        let toastElList = [].slice.call(document.querySelectorAll(".toast"));
        //var toastList =
        toastElList.map(function (toastEl) {
            if (toastEl.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            toastEl.setAttribute("data-kt-initialized", "1");

            return new bootstrap.Toast(toastEl, {});
        });
    };

    let createButtons = function () {
        let buttonsGroup = [].slice.call(
            document.querySelectorAll("[data-kt-buttons=\"true\"]")
        );

        buttonsGroup.map(function (group) {
            if (group.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            let selector = group.hasAttribute("data-kt-buttons-target")
                ? group.getAttribute("data-kt-buttons-target")
                : ".btn";
            let activeButtons = [].slice.call(group.querySelectorAll(selector));

            // Toggle Handler
            KtUtil.on(group, selector, "click", function () {
                activeButtons.map(function (button) {
                    button.classList.remove("active");
                });

                this.classList.add("active");
            });

            group.setAttribute("data-kt-initialized", "1");
        });
    };

    /*
      var createDateRangePickers = function() {
          // Check if jQuery included
          if (typeof jQuery == 'undefined') {
              return;
          }

          // Check if daterangepicker included
          if (typeof $.fn.daterangepicker === 'undefined') {
              return;
          }

          var elements = [].slice.call(document.querySelectorAll('[data-kt-daterangepicker="true"]'));
          var start = moment().subtract(29, 'days');
          var end = moment();

          elements.map(function (element) {
              if (element.getAttribute("data-kt-initialized") === "1") {
                  return;
              }

              var display = element.querySelector('div');
              var attrOpens  = element.hasAttribute('data-kt-daterangepicker-opens') ? element.getAttribute('data-kt-daterangepicker-opens') : 'left';
              var range = element.getAttribute('data-kt-daterangepicker-range');

              var cb = function(start, end) {
                  var current = moment();

                  if (display) {
                      if ( current.isSame(start, "day") && current.isSame(end, "day") ) {
                          display.innerHTML = start.format('D MMM YYYY');
                      } else {
                          display.innerHTML = start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY');
                      }
                  }
              }

              if ( range === "today" ) {
                  start = moment();
                  end = moment();
              }

              $(element).daterangepicker({
                  startDate: start,
                  endDate: end,
                  opens: attrOpens,
                  ranges: {
                  'Today': [moment(), moment()],
                  'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                  'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                  'This Month': [moment().startOf('month'), moment().endOf('month')],
                  'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                  }
              }, cb);

              cb(start, end);

              element.setAttribute("data-kt-initialized", "1");
          });
      }
  */
    /*  var createSelect2 = function () {
            // Check if jQuery included
            if (typeof jQuery == 'undefined') {
                return;
            }

            // Check if select2 included
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }

            var elements = [].slice.call(document.querySelectorAll('[data-control="select2"], [data-kt-select2="true"]'));

            elements.map(function (element) {
                if (element.getAttribute("data-kt-initialized") === "1") {
                    return;
                }

                var options = {
                    dir: document.body.getAttribute('direction')
                };

                if (element.getAttribute('data-hide-search') == 'true') {
                    options.minimumResultsForSearch = Infinity;
                }

                $(element).select2(options);

                element.setAttribute("data-kt-initialized", "1");
            });


            //
            // Hacky fix for a bug in select2 with jQuery 3.6.0's new nested-focus "protection"
            // see: https://github.com/select2/select2/issues/5993
            // see: https://github.com/jquery/jquery/issues/4382
            //
            // TODO: Recheck with the select2 GH issue and remove once this is fixed on their side


            if (select2FocusFixInitialized === false) {
                select2FocusFixInitialized = true;

                $(document).on('select2:open', function(e) {
                    var elements = document.querySelectorAll('.select2-container--open .select2-search__field');
                    if (elements.length > 0) {
                        elements[elements.length - 1].focus();
                    }
                });
            }
        }

        var createAutosize = function () {
            if (typeof autosize === 'undefined') {
                return;
            }

            var inputs = [].slice.call(document.querySelectorAll('[data-kt-autosize="true"]'));

            inputs.map(function (input) {
                if (input.getAttribute("data-kt-initialized") === "1") {
                    return;
                }

                autosize(input);

                input.setAttribute("data-kt-initialized", "1");
            });
        }

        var createCountUp = function () {
            if (typeof countUp === 'undefined') {
                return;
            }

            var elements = [].slice.call(document.querySelectorAll('[data-kt-countup="true"]:not(.counted)'));

            elements.map(function (element) {
                if (KTUtil.isInViewport(element) && KTUtil.visible(element)) {
                    if (element.getAttribute("data-kt-initialized") === "1") {
                        return;
                    }

                    var options = {};

                    var value = element.getAttribute('data-kt-countup-value');
                    value = parseFloat(value.replace(/,/g, ""));

                    if (element.hasAttribute('data-kt-countup-start-val')) {
                        options.startVal = parseFloat(element.getAttribute('data-kt-countup-start-val'));
                    }

                    if (element.hasAttribute('data-kt-countup-duration')) {
                        options.duration = parseInt(element.getAttribute('data-kt-countup-duration'));
                    }

                    if (element.hasAttribute('data-kt-countup-decimal-places')) {
                        options.decimalPlaces = parseInt(element.getAttribute('data-kt-countup-decimal-places'));
                    }

                    if (element.hasAttribute('data-kt-countup-prefix')) {
                        options.prefix = element.getAttribute('data-kt-countup-prefix');
                    }

                    if (element.hasAttribute('data-kt-countup-separator')) {
                        options.separator = element.getAttribute('data-kt-countup-separator');
                    }

                    if (element.hasAttribute('data-kt-countup-suffix')) {
                        options.suffix = element.getAttribute('data-kt-countup-suffix');
                    }

                    var count = new countUp.CountUp(element, value, options);

                    count.start();

                    element.classList.add('counted');

                    element.setAttribute("data-kt-initialized", "1");
                }
            });
        }

        var createCountUpTabs = function () {
            if (typeof countUp === 'undefined') {
                return;
            }

            if (countUpInitialized === false) {
                // Initial call
                createCountUp();

                // Window scroll event handler
                window.addEventListener('scroll', createCountUp);
            }

            // Tabs shown event handler
            var tabs = [].slice.call(document.querySelectorAll('[data-kt-countup-tabs="true"][data-bs-toggle="tab"]'));
            tabs.map(function (tab) {
                if (tab.getAttribute("data-kt-initialized") === "1") {
                    return;
                }

                tab.addEventListener('shown.bs.tab', createCountUp);

                tab.setAttribute("data-kt-initialized", "1");
            });

            countUpInitialized = true;
        }*/

    let createTinySliders = function () {
        if (typeof tns === "undefined") {
            return;
        }

        // Init Slider
        let initSlider = function (el) {
            if (!el) {
                return;
            }

            const tnsOptions = {};

            // Convert string boolean
            const checkBool = function (val) {
                if (val === "true") {
                    return true;
                }
                if (val === "false") {
                    return false;
                }
                return val;
            };

            // get extra options via data attributes
            el.getAttributeNames().forEach(function (attrName) {
                // more options; https://github.com/ganlanyuan/tiny-slider#options
                if (/^data-tns-.*/g.test(attrName)) {
                    let optionName = attrName
                        .replace("data-tns-", "")
                        .toLowerCase()
                        .replace(/(?:[\s-])\w/g, function (match) {
                            return match.replace("-", "").toUpperCase();
                        });

                    if (attrName === "data-tns-responsive") {
                        // fix string with a valid json
                        const jsonStr = el
                            .getAttribute(attrName)
                            .replace(/(\w+:)|(\w+ :)/g, function (matched) {
                                return "\"" + matched.substring(0, matched.length - 1) + "\":";
                            });
                        try {
                            // convert json string to object
                            tnsOptions[optionName] = JSON.parse(jsonStr);
                        } catch (e) {
                            //ignored
                        }
                    } else {
                        tnsOptions[optionName] = checkBool(el.getAttribute(attrName));
                    }
                }
            });

            const opt = Object.assign(
                {},
                {
                    container: el,
                    slideBy: "page",
                    autoplay: true,
                    autoplayButtonOutput: false
                },
                tnsOptions
            );

            if (el.closest(".tns")) {
                KtUtil.addClass(el.closest(".tns"), "tns-initiazlied");
            }

            return opt; // tns(opt);
        };

        // Sliders
        const elements = Array.prototype.slice.call(
            document.querySelectorAll("[data-tns=\"true\"]"),
            0
        );

        if (!elements && elements.length === 0) {
            return;
        }

        elements.forEach(function (el) {
            if (el.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            initSlider(el);

            el.setAttribute("data-kt-initialized", "1");
        });
    };

    /*var initSmoothScroll = function () {
          if (initialized === true) {
              return;
          }

          if (typeof SmoothScroll === 'undefined') {
              return;
          }

          new SmoothScroll('a[data-kt-scroll-toggle][href*="#"]', {
              speed: 1000,
              speedAsDuration: true,
              offset: function (anchor, toggle) {
                  // Integer or Function returning an integer. How far to offset the scrolling anchor location in pixels
                  // This example is a function, but you could do something as simple as `offset: 25`

                  // An example returning different values based on whether the clicked link was in the header nav or not
                  if (anchor.hasAttribute('data-kt-scroll-offset')) {
                      var val = KTUtil.getResponsiveValue(anchor.getAttribute('data-kt-scroll-offset'));

                      return val;
                  } else {
                      return 0;
                  }
              }
          });
      }*/

    let initCard = function () {
        // Toggle Handler
        KtUtil.on(document.body, "[data-kt-card-action=\"remove\"]", "click", function (e) {
            e.preventDefault();

            const card = this.closest(".card");

            if (!card) {
                return;
            }

            //const confirmMessage = this.getAttribute("data-kt-card-confirm-message");
            const confirm = this.getAttribute("data-kt-card-confirm") === "true";

            if (confirm) {
                // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                /*Swal.fire({
                      text: confirmMessage ? confirmMessage : "Are you sure to remove ?",
                      icon: "warning",
                      buttonsStyling: false,
                      confirmButtonText: "Confirm",
                      denyButtonText: "Cancel",
                      customClass: {
                          confirmButton: "btn btn-primary",
                          denyButton: "btn btn-danger"
                      }
                  }).then(function (result) {
                      if (result.isConfirmed) {
                          card.remove();
                      }
                  });*/
            } else {
                card.remove();
            }
        });
    };

    let initModal = function () {
        var elements = Array.prototype.slice.call(
            document.querySelectorAll("[data-bs-stacked-modal]")
        );

        if (elements && elements.length > 0) {
            elements.forEach((element) => {
                if (element.getAttribute("data-kt-initialized") === "1") {
                    return;
                }

                element.setAttribute("data-kt-initialized", "1");

                element.addEventListener("click", function (e) {
                    e.preventDefault();

                    const modalEl = document.querySelector(
                        this.getAttribute("data-bs-stacked-modal")
                    );

                    if (modalEl) {
                        //const modal = new bootstrap.Modal(modalEl);
                        //modal.show();
                        //ignored
                    }
                });
            });
        }
    };

    let initCheck = function () {
        if (initialized === true) {
            return;
        }

        // Toggle Handler
        KtUtil.on(document.body, "[data-kt-check=\"true\"]", "change", function () {
            var check = this;
            var targets = document.querySelectorAll(
                check.getAttribute("data-kt-check-target")
            );

            KtUtil.each(targets, function (target) {
                if (target.type == "checkbox") {
                    target.checked = check.checked;
                } else {
                    target.classList.toggle("active");
                }
            });
        });
    };

    let initBootstrapCollapse = function () {
        if (initialized === true) {
            return;
        }

        KtUtil.on(
            document.body,
            ".collapsible[data-bs-toggle=\"collapse\"]",
            "click",
            function (e) {
                if (this.classList.contains("collapsed")) {
                    this.classList.remove("active");
                    this.blur();
                } else {
                    this.classList.add("active");
                }

                if (this.hasAttribute("data-kt-toggle-text")) {
                    var text = this.getAttribute("data-kt-toggle-text");
                    var target = this.querySelector("[data-kt-toggle-text-target=\"true\"]");
                    //var target = target ? target : this;

                    this.setAttribute("data-kt-toggle-text", target.innerText);
                    target.innerText = text;
                }
            }
        );
    };

    let initBootstrapRotate = function () {
        if (initialized === true) {
            return;
        }

        KtUtil.on(document.body, "[data-kt-rotate=\"true\"]", "click", function () {
            if (this.classList.contains("active")) {
                this.classList.remove("active");
                this.blur();
            } else {
                this.classList.add("active");
            }
        });
    };

    /*var initLozad = function() {
          // Check if lozad included
          if (typeof lozad === 'undefined') {
              return;
          }

            const observer = lozad(); // lazy loads elements with default selector as '.lozad'
          o bserver.observe();
        }*/

    let showPageLoading = function () {
        document.body.classList.add("page-loading");
        document.body.setAttribute("data-kt-app-page-loading", "on");
    };

    let hidePageLoading = function () {
        // CSS3 Transitions only after page load(.page-loading or .app-page-loading class added to body tag and remove with JS on page load)
        document.body.classList.remove("page-loading");
        document.body.removeAttribute("data-kt-app-page-loading");
    };

    return {
        init: function () {
            //initLozad();

            //initSmoothScroll();

            initCard();

            initModal();

            initCheck();

            initBootstrapCollapse();

            initBootstrapRotate();

            createBootstrapTooltips();

            createBootstrapPopovers();

            //createBootstrapToasts();

            //createDateRangePickers();

            createButtons();

            //createSelect2();

            //createCountUp();

            //createCountUpTabs();

            //createAutosize();

            createTinySliders();

            initialized = true;
        },

        showPageLoading: function () {
            showPageLoading();
        },

        hidePageLoading: function () {
            hidePageLoading();
        },

        createBootstrapPopover: function (el, options) {
            return createBootstrapPopover(el, options);
        },

        createBootstrapTooltip: function (el, options) {
            return createBootstrapTooltip(el, options);
        }
    };
})();

// Declare KTApp for Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTApp;
}


// DOCS: https://javascript.info/cookie

// Class definition
var KTCookie = (function () {
    return {
        // returns the cookie with the given name,
        // or undefined if not found
        get: function (name) {
            var matches = document.cookie.match(
                new RegExp(
                    "(?:^|; )" +
                    name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
                    "=([^;]*)"
                )
            );

            return matches ? decodeURIComponent(matches[1]) : null;
        },

        // Please note that a cookie value is encoded,
        // so getCookie uses a built-in decodeURIComponent function to decode it.
        set: function (name, value, options) {
            if (typeof options === "undefined" || options === null) {
                options = {};
            }

            options = Object.assign(
                {},
                {
                    path: "/"
                },
                options
            );

            if (options.expires instanceof Date) {
                options.expires = options.expires.toUTCString();
            }

            var updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

            for (var optionKey in options) {
                /* eslint-disable-next-line  no-prototype-builtins */
                if (options.hasOwnProperty(optionKey) === false) {
                    continue;
                }

                updatedCookie += "; " + optionKey;
                var optionValue = options[optionKey];

                if (optionValue !== true) {
                    updatedCookie += "=" + optionValue;
                }
            }

            document.cookie = updatedCookie;
        },

        // To remove a cookie, we can call it with a negative expiration date:
        remove: function (name) {
            this.set(name, "", {
                "max-age": -1
            });
        }
    };
})();

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTCookie;
}

// Class definition
var KTDialer = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    var the = this;

    if (!element) {
        return;
    }

    // Default options
    var defaultOptions = {
        min: null,
        max: null,
        step: 1,
        decimals: 0,
        prefix: "",
        suffix: ""
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    // Constructor
    var _construct = function () {
        if (KtUtil.data(element).has("dialer") === true) {
            the = KtUtil.data(element).get("dialer");
        } else {
            _init();
        }
    };

    // Initialize
    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);

        // Elements
        the.element = element;
        the.incElement = the.element.querySelector("[data-kt-dialer-control=\"increase\"]");
        the.decElement = the.element.querySelector("[data-kt-dialer-control=\"decrease\"]");
        the.inputElement = the.element.querySelector("input[type]");

        // Set Values
        if (_getOption("decimals")) {
            the.options.decimals = parseInt(_getOption("decimals"));
        }

        if (_getOption("prefix")) {
            the.options.prefix = _getOption("prefix");
        }

        if (_getOption("suffix")) {
            the.options.suffix = _getOption("suffix");
        }

        if (_getOption("step")) {
            the.options.step = parseFloat(_getOption("step"));
        }

        if (_getOption("min")) {
            the.options.min = parseFloat(_getOption("min"));
        }

        if (_getOption("max")) {
            the.options.max = parseFloat(_getOption("max"));
        }

        the.value = parseFloat(the.inputElement.value.replace(/[^\d.]/g, ""));

        _setValue();

        // Event Handlers
        _handlers();

        // Bind Instance
        KtUtil.data(the.element).set("dialer", the);
    };

    // Handlers
    var _handlers = function () {
        KtUtil.addEvent(the.incElement, "click", function (e) {
            e.preventDefault();

            _increase();
        });

        KtUtil.addEvent(the.decElement, "click", function (e) {
            e.preventDefault();

            _decrease();
        });

        KtUtil.addEvent(the.inputElement, "input", function (e) {
            e.preventDefault();

            _setValue();
        });
    };

    // Event handlers
    var _increase = function () {
        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, "kt.dialer.increase", the);

        the.inputElement.value = the.value + the.options.step;
        _setValue();

        // Trigger "before.dialer" event
        KTEventHandler.trigger(the.element, "kt.dialer.increased", the);

        return the;
    };

    var _decrease = function () {
        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, "kt.dialer.decrease", the);

        the.inputElement.value = the.value - the.options.step;

        _setValue();

        // Trigger "before.dialer" event
        KTEventHandler.trigger(the.element, "kt.dialer.decreased", the);

        return the;
    };

    // Set Input Value
    var _setValue = function (value) {
        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, "kt.dialer.change", the);

        if (value !== undefined) {
            the.value = value;
        } else {
            the.value = _parse(the.inputElement.value);
        }

        if (the.options.min !== null && the.value < the.options.min) {
            the.value = the.options.min;
        }

        if (the.options.max !== null && the.value > the.options.max) {
            the.value = the.options.max;
        }

        the.inputElement.value = _format(the.value);

        // Trigger input change event
        the.inputElement.dispatchEvent(new Event("change"));

        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, "kt.dialer.changed", the);
    };

    var _parse = function (val) {
        val = val
            .replace(/[^0-9.-]/g, "") // remove chars except number, hyphen, point.
            .replace(/(\..*)\./g, "$1") // remove multiple points.
            .replace(/(?!^)-/g, "") // remove middle hyphen.
            .replace(/^0+(\d)/gm, "$1"); // remove multiple leading zeros. <-- I added this.

        val = parseFloat(val);

        if (isNaN(val)) {
            val = 0;
        }

        return val;
    };

    // Format
    var _format = function (val) {
        return (
            the.options.prefix +
            parseFloat(val).toFixed(the.options.decimals) +
            the.options.suffix
        );
    };

    // Get option
    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-dialer-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-dialer-" + name);
            var value = attr;

            return value;
        } else {
            return null;
        }
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("dialer");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.setMinValue = function (value) {
        the.options.min = value;
    };

    the.setMaxValue = function (value) {
        the.options.max = value;
    };

    the.setValue = function (value) {
        _setValue(value);
    };

    the.getValue = function () {
        return the.inputElement.value;
    };

    the.update = function () {
        _setValue();
    };

    the.increase = function () {
        return _increase();
    };

    the.decrease = function () {
        return _decrease();
    };

    the.getElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Static methods
KTDialer.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("dialer")) {
        return KtUtil.data(element).get("dialer");
    } else {
        return null;
    }
};

// Create instances
KTDialer.createInstances = function (selector = "[data-kt-dialer=\"true\"]") {
    // Get instances
    var elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new KTDialer(elements[i]);
        }
    }
};

// Global initialization
KTDialer.init = function () {
    KTDialer.createInstances();
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTDialer;
}

var KTDrawerHandlersInitialized = false;

// Class definition
let KTDrawer = function (element, options) {
    //////////////////////////////
    // ** Private variables  ** //
    //////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default options
    var defaultOptions = {
        overlay: true,
        direction: "end",
        baseClass: "drawer",
        overlayClass: "drawer-overlay"
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("drawer")) {
            the = KtUtil.data(element).get("drawer");
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.uid = KtUtil.getUniqueId("drawer");
        the.element = element;
        the.overlayElement = null;
        the.name = the.element.getAttribute("data-kt-drawer-name");
        the.shown = false;
        the.lastWidth;
        the.toggleElement = null;

        // Set initialized
        the.element.setAttribute("data-kt-drawer", "true");

        // Event Handlers
        _handlers();

        // Update Instance
        _update();

        // Bind Instance
        KtUtil.data(the.element).set("drawer", the);
    };

    var _handlers = function () {
        var togglers = _getOption("toggle");
        var closers = _getOption("close");

        if (togglers !== null && togglers.length > 0) {
            KtUtil.on(document.body, togglers, "click", function (e) {
                e.preventDefault();

                the.toggleElement = this;
                _toggle();
            });
        }

        if (closers !== null && closers.length > 0) {
            KtUtil.on(document.body, closers, "click", function (e) {
                e.preventDefault();

                the.closeElement = this;
                _hide();
            });
        }
    };

    var _toggle = function () {
        if (KTEventHandler.trigger(the.element, "kt.drawer.toggle", the) === false) {
            return;
        }

        if (the.shown === true) {
            _hide();
        } else {
            _show();
        }

        KTEventHandler.trigger(the.element, "kt.drawer.toggled", the);
    };

    var _hide = function () {
        if (KTEventHandler.trigger(the.element, "kt.drawer.hide", the) === false) {
            return;
        }

        the.shown = false;

        _deleteOverlay();

        document.body.removeAttribute("data-kt-drawer-" + the.name, "on");
        document.body.removeAttribute("data-kt-drawer");

        KtUtil.removeClass(the.element, the.options.baseClass + "-on");

        if (the.toggleElement !== null) {
            KtUtil.removeClass(the.toggleElement, "active");
        }

        KTEventHandler.trigger(the.element, "kt.drawer.after.hidden", the) === false;
    };

    var _show = function () {
        if (KTEventHandler.trigger(the.element, "kt.drawer.show", the) === false) {
            return;
        }

        the.shown = true;

        _createOverlay();
        document.body.setAttribute("data-kt-drawer-" + the.name, "on");
        document.body.setAttribute("data-kt-drawer", "on");

        KtUtil.addClass(the.element, the.options.baseClass + "-on");

        if (the.toggleElement !== null) {
            KtUtil.addClass(the.toggleElement, "active");
        }

        KTEventHandler.trigger(the.element, "kt.drawer.shown", the);
    };

    var _update = function () {
        var width = _getWidth();
        var direction = _getOption("direction");

        var top = _getOption("top");
        var bottom = _getOption("bottom");
        var start = _getOption("start");
        var end = _getOption("end");

        // Reset state
        if (
            KtUtil.hasClass(the.element, the.options.baseClass + "-on") === true &&
            String(document.body.getAttribute("data-kt-drawer-" + the.name + "-")) === "on"
        ) {
            the.shown = true;
        } else {
            the.shown = false;
        }

        // Activate/deactivate
        if (_getOption("activate") === true) {
            KtUtil.addClass(the.element, the.options.baseClass);
            KtUtil.addClass(the.element, the.options.baseClass + "-" + direction);

            KtUtil.css(the.element, "width", width, true);
            the.lastWidth = width;

            if (top) {
                KtUtil.css(the.element, "top", top);
            }

            if (bottom) {
                KtUtil.css(the.element, "bottom", bottom);
            }

            if (start) {
                if (KtUtil.isRTL()) {
                    KtUtil.css(the.element, "right", start);
                } else {
                    KtUtil.css(the.element, "left", start);
                }
            }

            if (end) {
                if (KtUtil.isRTL()) {
                    KtUtil.css(the.element, "left", end);
                } else {
                    KtUtil.css(the.element, "right", end);
                }
            }
        } else {
            KtUtil.removeClass(the.element, the.options.baseClass);
            KtUtil.removeClass(the.element, the.options.baseClass + "-" + direction);

            KtUtil.css(the.element, "width", "");

            if (top) {
                KtUtil.css(the.element, "top", "");
            }

            if (bottom) {
                KtUtil.css(the.element, "bottom", "");
            }

            if (start) {
                if (KtUtil.isRTL()) {
                    KtUtil.css(the.element, "right", "");
                } else {
                    KtUtil.css(the.element, "left", "");
                }
            }

            if (end) {
                if (KtUtil.isRTL()) {
                    KtUtil.css(the.element, "left", "");
                } else {
                    KtUtil.css(the.element, "right", "");
                }
            }

            _hide();
        }
    };

    var _createOverlay = function () {
        if (_getOption("overlay") === true) {
            the.overlayElement = document.createElement("DIV");

            KtUtil.css(
                the.overlayElement,
                "z-index",
                KtUtil.css(the.element, "z-index") - 1
            ); // update

            document.body.append(the.overlayElement);

            KtUtil.addClass(the.overlayElement, _getOption("overlay-class"));

            KtUtil.addEvent(the.overlayElement, "click", function (e) {
                e.preventDefault();

                if (_getOption("permanent") !== true) {
                    _hide();
                }
            });
        }
    };

    var _deleteOverlay = function () {
        if (the.overlayElement !== null) {
            KtUtil.remove(the.overlayElement);
        }
    };

    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-drawer-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-drawer-" + name);
            var value = KtUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }

            return value;
        } else {
            var optionName = KtUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KtUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    };

    var _getWidth = function () {
        var width = _getOption("width");

        if (width === "auto") {
            width = KtUtil.css(the.element, "width");
        }

        return width;
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("drawer");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.toggle = function () {
        return _toggle();
    };

    the.show = function () {
        return _show();
    };

    the.hide = function () {
        return _hide();
    };

    the.isShown = function () {
        return the.shown;
    };

    the.update = function () {
        _update();
    };

    the.goElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Static methods
KTDrawer.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("drawer")) {
        return KtUtil.data(element).get("drawer");
    } else {
        return null;
    }
};

// Hide all drawers and skip one if provided
KTDrawer.hideAll = function (skip = null, selector = "[data-kt-drawer=\"true\"]") {
    var items = document.querySelectorAll(selector);

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var item = items[i];
            var drawer = KTDrawer.getInstance(item);

            if (!drawer) {
                continue;
            }

            if (skip) {
                if (item !== skip) {
                    drawer.hide();
                }
            } else {
                drawer.hide();
            }
        }
    }
};

// Update all drawers
KTDrawer.updateAll = function (selector = "[data-kt-drawer=\"true\"]") {
    var items = document.querySelectorAll(selector);

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var drawer = KTDrawer.getInstance(items[i]);

            if (drawer) {
                drawer.update();
            }
        }
    }
};

// Create instances
KTDrawer.createInstances = function (selector = "[data-kt-drawer=\"true\"]") {
    // Initialize Menus
    var elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new KTDrawer(elements[i]);
        }
    }
};

// Toggle instances
KTDrawer.handleShow = function () {
    // External drawer toggle handler
    KtUtil.on(
        document.body,
        "[data-kt-drawer-show=\"true\"][data-kt-drawer-target]",
        "click",
        function (e) {
            e.preventDefault();

            var element = document.querySelector(
                this.getAttribute("data-kt-drawer-target")
            );

            if (element) {
                KTDrawer.getInstance(element).show();
            }
        }
    );
};

// Dismiss instances
KTDrawer.handleDismiss = function () {
    // External drawer toggle handler
    KtUtil.on(document.body, "[data-kt-drawer-dismiss=\"true\"]", "click", function (e) {
        var element = this.closest("[data-kt-drawer=\"true\"]");

        if (element) {
            var drawer = KTDrawer.getInstance(element);
            if (drawer.isShown()) {
                drawer.hide();
            }
        }
    });
};

// Handle resize
KTDrawer.handleResize = function () {
    // Window resize Handling
    window.addEventListener("resize", function () {
        var timer;

        KtUtil.throttle(
            timer,
            function () {
                // Locate and update drawer instances on window resize
                var elements = document.querySelectorAll("[data-kt-drawer=\"true\"]");

                if (elements && elements.length > 0) {
                    for (var i = 0, len = elements.length; i < len; i++) {
                        var drawer = KTDrawer.getInstance(elements[i]);
                        if (drawer) {
                            drawer.update();
                        }
                    }
                }
            },
            200
        );
    });
};

// Global initialization
KTDrawer.init = function () {
    KTDrawer.createInstances();

    if (KTDrawerHandlersInitialized === false) {
        KTDrawer.handleResize();
        KTDrawer.handleShow();
        KTDrawer.handleDismiss();

        KTDrawerHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTDrawer;
}


// Class definition
var KTFeedback = function (options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    // Default options
    var defaultOptions = {
        width: 100,
        placement: "top-center",
        content: "",
        type: "popup"
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        _init();
    };

    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.uid = KtUtil.getUniqueId("feedback");
        the.element;
        the.shown = false;

        // Event Handlers
        _handlers();

        // Bind Instance
        KtUtil.data(the.element).set("feedback", the);
    };

    var _handlers = function () {
        KtUtil.addEvent(the.element, "click", function (e) {
            e.preventDefault();

            //_go();
        });
    };

    var _show = function () {
        if (KTEventHandler.trigger(the.element, "kt.feedback.show", the) === false) {
            return;
        }

        if (the.options.type === "popup") {
            _showPopup();
        }

        KTEventHandler.trigger(the.element, "kt.feedback.shown", the);

        return the;
    };

    var _hide = function () {
        if (KTEventHandler.trigger(the.element, "kt.feedback.hide", the) === false) {
            return;
        }

        if (the.options.type === "popup") {
            _hidePopup();
        }

        the.shown = false;

        KTEventHandler.trigger(the.element, "kt.feedback.hidden", the);

        return the;
    };

    var _showPopup = function () {
        the.element = document.createElement("DIV");

        KtUtil.addClass(the.element, "feedback feedback-popup");
        KtUtil.setHTML(the.element, the.options.content);

        if (the.options.placement == "top-center") {
            _setPopupTopCenterPosition();
        }

        document.body.appendChild(the.element);

        KtUtil.addClass(the.element, "feedback-shown");

        the.shown = true;
    };

    var _setPopupTopCenterPosition = function () {
        var width = KtUtil.getResponsiveValue(the.options.width);
        var height = KtUtil.css(the.element, "height");

        KtUtil.addClass(the.element, "feedback-top-center");

        KtUtil.css(the.element, "width", width);
        KtUtil.css(the.element, "left", "50%");
        KtUtil.css(the.element, "top", "-" + height);
    };

    var _hidePopup = function () {
        the.element.remove();
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("feedback");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.show = function () {
        return _show();
    };

    the.hide = function () {
        return _hide();
    };

    the.isShown = function () {
        return the.shown;
    };

    the.getElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTFeedback;
}

("use strict");

// Class definition
var KTImageInput = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {};

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("image-input") === true) {
            the = KtUtil.data(element).get("image-input");
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.uid = KtUtil.getUniqueId("image-input");

        // Elements
        the.element = element;
        the.inputElement = KtUtil.find(element, "input[type=\"file\"]");
        the.wrapperElement = KtUtil.find(element, ".image-input-wrapper");
        the.cancelElement = KtUtil.find(element, "[data-kt-image-input-action=\"cancel\"]");
        the.removeElement = KtUtil.find(element, "[data-kt-image-input-action=\"remove\"]");
        the.hiddenElement = KtUtil.find(element, "input[type=\"hidden\"]");
        the.src = KtUtil.css(the.wrapperElement, "backgroundImage");

        // Set initialized
        the.element.setAttribute("data-kt-image-input", "true");

        // Event Handlers
        _handlers();

        // Bind Instance
        KtUtil.data(the.element).set("image-input", the);
    };

    // Init Event Handlers
    var _handlers = function () {
        KtUtil.addEvent(the.inputElement, "change", _change);
        KtUtil.addEvent(the.cancelElement, "click", _cancel);
        KtUtil.addEvent(the.removeElement, "click", _remove);
    };

    // Event Handlers
    var _change = function (e) {
        e.preventDefault();

        if (
            the.inputElement !== null &&
            the.inputElement.files &&
            the.inputElement.files[0]
        ) {
            // Fire change event
            if (
                KTEventHandler.trigger(the.element, "kt.imageinput.change", the) === false
            ) {
                return;
            }

            var reader = new FileReader();

            reader.onload = function (e) {
                KtUtil.css(
                    the.wrapperElement,
                    "background-image",
                    "url(" + e.target.result + ")"
                );
            };

            reader.readAsDataURL(the.inputElement.files[0]);

            the.element.classList.add("image-input-changed");
            the.element.classList.remove("image-input-empty");

            // Fire removed event
            KTEventHandler.trigger(the.element, "kt.imageinput.changed", the);
        }
    };

    var _cancel = function (e) {
        e.preventDefault();

        // Fire cancel event
        if (KTEventHandler.trigger(the.element, "kt.imageinput.cancel", the) === false) {
            return;
        }

        the.element.classList.remove("image-input-changed");
        the.element.classList.remove("image-input-empty");

        if (the.src === "none") {
            KtUtil.css(the.wrapperElement, "background-image", "");
            the.element.classList.add("image-input-empty");
        } else {
            KtUtil.css(the.wrapperElement, "background-image", the.src);
        }

        the.inputElement.value = "";

        if (the.hiddenElement !== null) {
            the.hiddenElement.value = "0";
        }

        // Fire canceled event
        KTEventHandler.trigger(the.element, "kt.imageinput.canceled", the);
    };

    var _remove = function (e) {
        e.preventDefault();

        // Fire remove event
        if (KTEventHandler.trigger(the.element, "kt.imageinput.remove", the) === false) {
            return;
        }

        the.element.classList.remove("image-input-changed");
        the.element.classList.add("image-input-empty");

        KtUtil.css(the.wrapperElement, "background-image", "none");
        the.inputElement.value = "";

        if (the.hiddenElement !== null) {
            the.hiddenElement.value = "1";
        }

        // Fire removed event
        KTEventHandler.trigger(the.element, "kt.imageinput.removed", the);
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("image-input");
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.getInputElement = function () {
        return the.inputElement;
    };

    the.getElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Static methods
KTImageInput.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("image-input")) {
        return KtUtil.data(element).get("image-input");
    } else {
        return null;
    }
};

// Create instances
KTImageInput.createInstances = function (selector = "[data-kt-image-input]") {
    // Initialize Menus
    var elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new KTImageInput(elements[i]);
        }
    }
};

// Global initialization
KTImageInput.init = function () {
    KTImageInput.createInstances();
};

// Webpack Support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTImageInput;
}

var KTMenuHandlersInitialized = false;

// Class definition
var KTMenu = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {
        dropdown: {
            hoverTimeout: 200,
            zindex: 107
        },

        accordion: {
            slideSpeed: 250,
            expand: false
        }
    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("menu") === true) {
            the = KtUtil.data(element).get("menu");
        } else {
            _init();
        }
    };

    var _init = function () {
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.uid = KtUtil.getUniqueId("menu");
        the.element = element;
        the.triggerElement;
        the.disabled = false;

        // Set initialized
        the.element.setAttribute("data-kt-menu", "true");

        _setTriggerElement();
        _update();

        KtUtil.data(the.element).set("menu", the);
    };

    // Event Handlers
    // Toggle handler
    var _click = function (element, e) {
        e.preventDefault();

        if (the.disabled === true) {
            return;
        }

        var item = _getItemElement(element);

        if (_getOptionFromElementAttribute(item, "trigger") !== "click") {
            return;
        }

        if (_getOptionFromElementAttribute(item, "toggle") === false) {
            _show(item);
        } else {
            _toggle(item);
        }
    };

    // Link handler
    var _link = function (element, e) {
        if (the.disabled === true) {
            return;
        }

        if (
            KTEventHandler.trigger(the.element, "kt.menu.link.click", element) === false
        ) {
            return;
        }

        // Dismiss all shown dropdowns
        KTMenu.hideDropdowns();

        KTEventHandler.trigger(the.element, "kt.menu.link.clicked", element);
    };

    // Dismiss handler
    var _dismiss = function (element, e) {
        var item = _getItemElement(element);
        var items = _getItemChildElements(item);

        if (item !== null && _getItemSubType(item) === "dropdown") {
            _hide(item); // hide items dropdown
            // Hide all child elements as well

            if (items.length > 0) {
                for (var i = 0, len = items.length; i < len; i++) {
                    if (items[i] !== null && _getItemSubType(items[i]) === "dropdown") {
                        //_hide(tems[i]);
                    }
                }
            }
        }
    };

    // Mouseover handle
    var _mouseover = function (element, e) {
        var item = _getItemElement(element);

        if (the.disabled === true) {
            return;
        }

        if (item === null) {
            return;
        }

        if (_getOptionFromElementAttribute(item, "trigger") !== "hover") {
            return;
        }

        if (KtUtil.data(item).get("hover") === "1") {
            clearTimeout(KtUtil.data(item).get("timeout"));
            KtUtil.data(item).remove("hover");
            KtUtil.data(item).remove("timeout");
        }

        _show(item);
    };

    // Mouseout handle
    var _mouseout = function (element, e) {
        var item = _getItemElement(element);

        if (the.disabled === true) {
            return;
        }

        if (item === null) {
            return;
        }

        if (_getOptionFromElementAttribute(item, "trigger") !== "hover") {
            return;
        }

        var timeout = setTimeout(function () {
            if (KtUtil.data(item).get("hover") === "1") {
                _hide(item);
            }
        }, the.options.dropdown.hoverTimeout);

        KtUtil.data(item).set("hover", "1");
        KtUtil.data(item).set("timeout", timeout);
    };

    // Toggle item sub
    var _toggle = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === true) {
            _hide(item);
        } else {
            _show(item);
        }
    };

    // Show item sub
    var _show = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === true) {
            return;
        }

        if (_getItemSubType(item) === "dropdown") {
            _showDropdown(item); // // show current dropdown
        } else if (_getItemSubType(item) === "accordion") {
            _showAccordion(item);
        }

        // Remember last submenu type
        KtUtil.data(item).set("type", _getItemSubType(item)); // updated
    };

    // Hide item sub
    var _hide = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === false) {
            return;
        }

        if (_getItemSubType(item) === "dropdown") {
            _hideDropdown(item);
        } else if (_getItemSubType(item) === "accordion") {
            _hideAccordion(item);
        }
    };

    // Reset item state classes if item sub type changed
    var _reset = function (item) {
        if (_hasItemSub(item) === false) {
            return;
        }

        var sub = _getItemSubElement(item);

        // Reset sub state if sub type is changed during the window resize
        if (
            KtUtil.data(item).has("type") &&
            KtUtil.data(item).get("type") !== _getItemSubType(item)
        ) {
            // updated
            KtUtil.removeClass(item, "hover");
            KtUtil.removeClass(item, "show");
            KtUtil.removeClass(sub, "show");
        } // updated
    };

    // Update all item state classes if item sub type changed
    var _update = function () {
        var items = the.element.querySelectorAll(".menu-item[data-kt-menu-trigger]");

        if (items && items.length > 0) {
            for (var i = 0, len = items.length; i < len; i++) {
                _reset(items[i]);
            }
        }
    };

    // Set external trigger element
    var _setTriggerElement = function () {
        var target = document.querySelector(
            "[data-kt-menu-target=\"# " + the.element.getAttribute("id") + "\"]"
        );

        if (target !== null) {
            the.triggerElement = target;
        } else if (the.element.closest("[data-kt-menu-trigger]")) {
            the.triggerElement = the.element.closest("[data-kt-menu-trigger]");
        } else if (
            the.element.parentNode &&
            KtUtil.child(the.element.parentNode, "[data-kt-menu-trigger]")
        ) {
            the.triggerElement = KtUtil.child(
                the.element.parentNode,
                "[data-kt-menu-trigger]"
            );
        }

        if (the.triggerElement) {
            KtUtil.data(the.triggerElement).set("menu", the);
        }
    };

    // Test if menu has external trigger element
    var _isTriggerElement = function (item) {
        return the.triggerElement === item ? true : false;
    };

    // Test if item's sub is shown
    var _isItemSubShown = function (item) {
        var sub = _getItemSubElement(item);

        if (sub !== null) {
            if (_getItemSubType(item) === "dropdown") {
                if (
                    KtUtil.hasClass(sub, "show") === true &&
                    sub.hasAttribute("data-popper-placement") === true
                ) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return KtUtil.hasClass(item, "show");
            }
        } else {
            return false;
        }
    };

    // Test if item dropdown is permanent
    var _isItemDropdownPermanent = function (item) {
        return _getOptionFromElementAttribute(item, "permanent") === true ? true : false;
    };

    // Test if item's parent is shown
    var _isItemParentShown = function (item) {
        return KtUtil.parents(item, ".menu-item.show").length > 0;
    };

    // Test of it is item sub element
    var _isItemSubElement = function (item) {
        return KtUtil.hasClass(item, "menu-sub");
    };

    // Test if item has sub
    var _hasItemSub = function (item) {
        return (
            KtUtil.hasClass(item, "menu-item") && item.hasAttribute("data-kt-menu-trigger")
        );
    };

    // Get link element
    var _getItemLinkElement = function (item) {
        return KtUtil.child(item, ".menu-link");
    };

    // Get toggle element
    var _getItemToggleElement = function (item) {
        if (the.triggerElement) {
            return the.triggerElement;
        } else {
            return _getItemLinkElement(item);
        }
    };

    // Get item sub element
    var _getItemSubElement = function (item) {
        if (_isTriggerElement(item) === true) {
            return the.element;
        }
        if (item.classList.contains("menu-sub") === true) {
            return item;
        } else if (KtUtil.data(item).has("sub")) {
            return KtUtil.data(item).get("sub");
        } else {
            return KtUtil.child(item, ".menu-sub");
        }
    };

    // Get item sub type
    var _getItemSubType = function (element) {
        var sub = _getItemSubElement(element);

        if (sub && parseInt(KtUtil.css(sub, "z-index")) > 0) {
            return "dropdown";
        } else {
            return "accordion";
        }
    };

    // Get item element
    var _getItemElement = function (element) {
        var item, sub;

        // Element is the external trigger element
        if (_isTriggerElement(element)) {
            return element;
        }

        // Element has item toggler attribute
        if (element.hasAttribute("data-kt-menu-trigger")) {
            return element;
        }

        // Element has item DOM reference in it's data storage
        if (KtUtil.data(element).has("item")) {
            return KtUtil.data(element).get("item");
        }

        // Item is parent of element
        if ((item = element.closest(".menu-item[data-kt-menu-trigger]"))) {
            return item;
        }

        // Element's parent has item DOM reference in it's data storage
        if ((sub = element.closest(".menu-sub"))) {
            if (KtUtil.data(sub).has("item") === true) {
                return KtUtil.data(sub).get("item");
            }
        }
    };

    // Get item parent element
    var _getItemParentElement = function (item) {
        var sub = item.closest(".menu-sub");
        var parentItem;

        if (KtUtil.data(sub).has("item")) {
            return KtUtil.data(sub).get("item");
        }

        if (sub && (parentItem = sub.closest(".menu-item[data-kt-menu-trigger]"))) {
            return parentItem;
        }

        return null;
    };

    // Get item parent elements
    var _getItemParentElements = function (item) {
        var parents = [];
        var parent;
        var i = 0;

        do {
            parent = _getItemParentElement(item);

            if (parent) {
                parents.push(parent);
                item = parent;
            }

            i++;
        } while (parent !== null && i < 20);

        if (the.triggerElement) {
            parents.unshift(the.triggerElement);
        }

        return parents;
    };

    // Get item child element
    var _getItemChildElement = function (item) {
        var selector = item;
        var element;

        if (KtUtil.data(item).get("sub")) {
            selector = KtUtil.data(item).get("sub");
        }

        if (selector !== null) {
            //element = selector.querySelector('.show.menu-item[data-kt-menu-trigger]');
            element = selector.querySelector(".menu-item[data-kt-menu-trigger]");

            if (element) {
                return element;
            } else {
                return null;
            }
        } else {
            return null;
        }
    };

    // Get item child elements
    var _getItemChildElements = function (item) {
        var children = [];
        var child;
        var i = 0;

        do {
            child = _getItemChildElement(item);

            if (child) {
                children.push(child);
                item = child;
            }

            i++;
        } while (child !== null && i < 20);

        return children;
    };

    // Show item dropdown
    var _showDropdown = function (item) {
        // Handle dropdown show event
        if (
            KTEventHandler.trigger(the.element, "kt.menu.dropdown.show", item) === false
        ) {
            return;
        }

        // Hide all currently shown dropdowns except current one
        KTMenu.hideDropdowns(item);

        var toggle = _isTriggerElement(item) ? item : _getItemLinkElement(item);
        var sub = _getItemSubElement(item);

        var width = _getOptionFromElementAttribute(item, "width");
        var height = _getOptionFromElementAttribute(item, "height");

        var zindex = the.options.dropdown.zindex; // update
        var parentZindex = KtUtil.getHighestZindex(item); // update

        // Apply a new z-index if dropdown's toggle element or it's parent has greater z-index // update
        if (parentZindex !== null && parentZindex >= zindex) {
            zindex = parentZindex + 1;
        }

        if (zindex > 0) {
            KtUtil.css(sub, "z-index", zindex);
        }

        if (width !== null) {
            KtUtil.css(sub, "width", width);
        }

        if (height !== null) {
            KtUtil.css(sub, "height", height);
        }

        KtUtil.css(sub, "display", "");
        KtUtil.css(sub, "overflow", "");

        // Init popper(new)
        _initDropdownPopper(item, sub);

        KtUtil.addClass(item, "show");
        KtUtil.addClass(item, "menu-dropdown");
        KtUtil.addClass(sub, "show");

        // Append the sub the the root of the menu
        if (_getOptionFromElementAttribute(item, "overflow") === true) {
            document.body.appendChild(sub);
            KtUtil.data(item).set("sub", sub);
            KtUtil.data(sub).set("item", item);
            KtUtil.data(sub).set("menu", the);
        } else {
            KtUtil.data(sub).set("item", item);
        }

        // Handle dropdown shown event
        KTEventHandler.trigger(the.element, "kt.menu.dropdown.shown", item);
    };

    // Hide item dropdown
    var _hideDropdown = function (item) {
        // Handle dropdown hide event
        if (
            KTEventHandler.trigger(the.element, "kt.menu.dropdown.hide", item) === false
        ) {
            return;
        }

        var sub = _getItemSubElement(item);

        KtUtil.css(sub, "z-index", "");
        KtUtil.css(sub, "width", "");
        KtUtil.css(sub, "height", "");

        KtUtil.removeClass(item, "show");
        KtUtil.removeClass(item, "menu-dropdown");
        KtUtil.removeClass(sub, "show");

        // Append the sub back to it's parent
        if (_getOptionFromElementAttribute(item, "overflow") === true) {
            if (item.classList.contains("menu-item")) {
                item.appendChild(sub);
            } else {
                KtUtil.insertAfter(the.element, item);
            }

            KtUtil.data(item).remove("sub");
            KtUtil.data(sub).remove("item");
            KtUtil.data(sub).remove("menu");
        }

        // Destroy popper(new)
        _destroyDropdownPopper(item);

        // Handle dropdown hidden event
        KTEventHandler.trigger(the.element, "kt.menu.dropdown.hidden", item);
    };

    // Init dropdown popper(new)
    var _initDropdownPopper = function (item, sub) {
        // Setup popper instance
        var reference;
        var attach = _getOptionFromElementAttribute(item, "attach");

        if (attach) {
            if (attach === "parent") {
                reference = item.parentNode;
            } else {
                reference = document.querySelector(attach);
            }
        } else {
            reference = item;
        }

        if (Popper) {
            var popper = Popper.createPopper(reference, sub, _getDropdownPopperConfig(item));
            KtUtil.data(item).set("popper", popper);
        } else {
            _destroyDropdownPopper();
        }

    };

    // Destroy dropdown popper(new)
    var _destroyDropdownPopper = function (item) {
        if (KtUtil.data(item).has("popper") === true) {
            KtUtil.data(item).get("popper").destroy();
            KtUtil.data(item).remove("popper");
        }
    };

    // Prepare popper config for dropdown(see: https://popper.js.org/docs/v2/)
    var _getDropdownPopperConfig = function (item) {
        // Placement
        var placement = _getOptionFromElementAttribute(item, "placement");
        if (!placement) {
            placement = "right";
        }

        // Offset
        var offsetValue = _getOptionFromElementAttribute(item, "offset");
        var offset = offsetValue ? offsetValue.split(",") : [];

        if (offset.length === 2) {
            offset[0] = parseInt(offset[0]);
            offset[1] = parseInt(offset[1]);
        }

        // Strategy
        var strategy =
            _getOptionFromElementAttribute(item, "overflow") === true
                ? "absolute"
                : "fixed";

        var altAxis =
            _getOptionFromElementAttribute(item, "flip") !== false ? true : false;

        var popperConfig = {
            placement: placement,
            strategy: strategy,
            modifiers: [
                {
                    name: "offset",
                    options: {
                        offset: offset
                    }
                },
                {
                    name: "preventOverflow",
                    options: {
                        altAxis: altAxis
                    }
                },
                {
                    name: "flip",
                    options: {
                        flipVariations: false
                    }
                }
            ]
        };

        return popperConfig;
    };

    // Show item accordion
    var _showAccordion = function (item) {
        if (
            KTEventHandler.trigger(the.element, "kt.menu.accordion.show", item) === false
        ) {
            return;
        }

        var sub = _getItemSubElement(item);
        var expand = the.options.accordion.expand;

        if (_getOptionFromElementAttribute(item, "expand") === true) {
            expand = true;
        } else if (_getOptionFromElementAttribute(item, "expand") === false) {
            expand = false;
        } else if (_getOptionFromElementAttribute(the.element, "expand") === true) {
            expand = true;
        }

        if (expand === false) {
            _hideAccordions(item);
        }

        if (KtUtil.data(item).has("popper") === true) {
            _hideDropdown(item);
        }

        KtUtil.addClass(item, "hover");

        KtUtil.addClass(item, "showing");

        KtUtil.slideDown(sub, the.options.accordion.slideSpeed, function () {
            KtUtil.removeClass(item, "showing");
            KtUtil.addClass(item, "show");
            KtUtil.addClass(sub, "show");

            KTEventHandler.trigger(the.element, "kt.menu.accordion.shown", item);
        });
    };

    // Hide item accordion
    var _hideAccordion = function (item) {
        if (
            KTEventHandler.trigger(the.element, "kt.menu.accordion.hide", item) === false
        ) {
            return;
        }

        var sub = _getItemSubElement(item);

        KtUtil.addClass(item, "hiding");

        KtUtil.slideUp(sub, the.options.accordion.slideSpeed, function () {
            KtUtil.removeClass(item, "hiding");
            KtUtil.removeClass(item, "show");
            KtUtil.removeClass(sub, "show");

            KtUtil.removeClass(item, "hover"); // update

            KTEventHandler.trigger(the.element, "kt.menu.accordion.hidden", item);
        });
    };

    var _setActiveLink = function (link) {
        var item = _getItemElement(link);
        var parentItems = _getItemParentElements(item);
        var parentTabPane = link.closest(".tab-pane");

        var activeLinks = [].slice.call(
            the.element.querySelectorAll(".menu-link.active")
        );
        var activeParentItems = [].slice.call(
            the.element.querySelectorAll(".menu-item.here, .menu-item.show")
        );

        if (_getItemSubType(item) === "accordion") {
            _showAccordion(item);
        } else {
            item.classList.add("here");
        }

        if (parentItems && parentItems.length > 0) {
            for (var i = 0, len = parentItems.length; i < len; i++) {
                var parentItem = parentItems[i];

                if (_getItemSubType(parentItem) === "accordion") {
                    _showAccordion(parentItem);
                } else {
                    parentItem.classList.add("here");
                }
            }
        }

        activeLinks.map(function (activeLink) {
            activeLink.classList.remove("active");
        });

        activeParentItems.map(function (activeParentItem) {
            if (activeParentItem.contains(item) === false) {
                activeParentItem.classList.remove("here");
                activeParentItem.classList.remove("show");
            }
        });

        // Handle tab
        if (parentTabPane && bootstrap.Tab) {
            var tabEl = the.element.querySelector(
                "[data-bs-target=\"#" + parentTabPane.getAttribute("id") + "\"]"
            );
            var tab = new bootstrap.Tab(tabEl);

            if (tab) {
                tab.show();
            }
        }

        link.classList.add("active");
    };

    var _getLinkByAttribute = function (value, name = "href") {
        var link = the.element.querySelector("a[" + name + "=\"" + value + "\"]");

        if (link) {
            return link;
        } else {
            null;
        }
    };

    // Hide all shown accordions of item
    var _hideAccordions = function (item) {
        var itemsToHide = KtUtil.findAll(the.element, ".show[data-kt-menu-trigger]");
        var itemToHide;

        if (itemsToHide && itemsToHide.length > 0) {
            for (var i = 0, len = itemsToHide.length; i < len; i++) {
                itemToHide = itemsToHide[i];

                if (
                    _getItemSubType(itemToHide) === "accordion" &&
                    itemToHide !== item &&
                    item.contains(itemToHide) === false &&
                    itemToHide.contains(item) === false
                ) {
                    _hideAccordion(itemToHide);
                }
            }
        }
    };

    // Get item option(through html attributes)
    var _getOptionFromElementAttribute = function (item, name) {
        var attr;
        var value = null;

        if (item && item.hasAttribute("data-kt-menu-" + name)) {
            attr = item.getAttribute("data-kt-menu-" + name);
            value = KtUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }
        }

        return value;
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("menu");
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Event Handlers
    the.click = function (element, e) {
        return _click(element, e);
    };

    the.link = function (element, e) {
        return _link(element, e);
    };

    the.dismiss = function (element, e) {
        return _dismiss(element, e);
    };

    the.mouseover = function (element, e) {
        return _mouseover(element, e);
    };

    the.mouseout = function (element, e) {
        return _mouseout(element, e);
    };

    // General Methods
    the.getItemTriggerType = function (item) {
        return _getOptionFromElementAttribute(item, "trigger");
    };

    the.getItemSubType = function (element) {
        return _getItemSubType(element);
    };

    the.show = function (item) {
        return _show(item);
    };

    the.hide = function (item) {
        return _hide(item);
    };

    the.toggle = function (item) {
        return _toggle(item);
    };

    the.reset = function (item) {
        return _reset(item);
    };

    the.update = function () {
        return _update();
    };

    the.getElement = function () {
        return the.element;
    };

    the.setActiveLink = function (link) {
        return _setActiveLink(link);
    };

    the.getLinkByAttribute = function (value, name = "href") {
        return _getLinkByAttribute(value, name);
    };

    the.getItemLinkElement = function (item) {
        return _getItemLinkElement(item);
    };

    the.getItemToggleElement = function (item) {
        return _getItemToggleElement(item);
    };

    the.getItemSubElement = function (item) {
        return _getItemSubElement(item);
    };

    the.getItemParentElements = function (item) {
        return _getItemParentElements(item);
    };

    the.isItemSubShown = function (item) {
        return _isItemSubShown(item);
    };

    the.isItemParentShown = function (item) {
        return _isItemParentShown(item);
    };

    the.getTriggerElement = function () {
        return the.triggerElement;
    };

    the.isItemDropdownPermanent = function (item) {
        return _isItemDropdownPermanent(item);
    };

    the.destroy = function () {
        return _destroy();
    };

    the.disable = function () {
        the.disabled = true;
    };

    the.enable = function () {
        the.disabled = false;
    };

    // Accordion Mode Methods
    the.hideAccordions = function (item) {
        return _hideAccordions(item);
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };
};

// Get KTMenu instance by element
KTMenu.getInstance = function (element) {
    var menu;
    var item;

    if (!element) {
        return null;
    }

    // Element has menu DOM reference in it's DATA storage
    if (KtUtil.data(element).has("menu")) {
        return KtUtil.data(element).get("menu");
    }

    // Element has .menu parent
    menu = element.closest(".menu");
    if (menu) {
        if (KtUtil.data(menu).has("menu")) {
            return KtUtil.data(menu).get("menu");
        }
    }

    // Element has a parent with DOM reference to .menu in it's DATA storage
    if (KtUtil.hasClass(element, "menu-link")) {
        var sub = element.closest(".menu-sub");

        if (KtUtil.data(sub).has("menu")) {
            return KtUtil.data(sub).get("menu");
        }
    }

    return null;
};

// Hide all dropdowns and skip one if provided
KTMenu.hideDropdowns = function (skip) {
    var items = document.querySelectorAll(".show.menu-dropdown[data-kt-menu-trigger]");

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var item = items[i];
            var menu = KTMenu.getInstance(item);

            if (menu && menu.getItemSubType(item) === "dropdown") {
                if (skip) {
                    if (
                        menu.getItemSubElement(item).contains(skip) === false &&
                        item.contains(skip) === false &&
                        item !== skip
                    ) {
                        menu.hide(item);
                    }
                } else {
                    menu.hide(item);
                }
            }
        }
    }
};

// Update all dropdowns popover instances
KTMenu.updateDropdowns = function () {
    var items = document.querySelectorAll(".show.menu-dropdown[data-kt-menu-trigger]");

    if (items && items.length > 0) {
        for (var i = 0, len = items.length; i < len; i++) {
            var item = items[i];

            if (KtUtil.data(item).has("popper")) {
                KtUtil.data(item).get("popper").forceUpdate();
            }
        }
    }
};

// Global handlers
KTMenu.initHandlers = function () {
    // Dropdown handler
    document.addEventListener("click", function (e) {
        var items = document.querySelectorAll(
            ".show.menu-dropdown[data-kt-menu-trigger]:not([data-kt-menu-static=\"true\"])"
        );
        var menu;
        var item;
        var sub;
        var menuObj;

        if (items && items.length > 0) {
            for (var i = 0, len = items.length; i < len; i++) {
                item = items[i];
                menuObj = KTMenu.getInstance(item);

                if (menuObj && menuObj.getItemSubType(item) === "dropdown") {
                    menu = menuObj.getElement();
                    sub = menuObj.getItemSubElement(item);

                    if (item === e.target || item.contains(e.target)) {
                        continue;
                    }

                    if (sub === e.target || sub.contains(e.target)) {
                        continue;
                    }

                    menuObj.hide(item);
                }
            }
        }
    });

    // Sub toggle handler(updated)
    KtUtil.on(
        document.body,
        ".menu-item[data-kt-menu-trigger] > .menu-link, [data-kt-menu-trigger]:not(.menu-item):not([data-kt-menu-trigger=\"auto\"])",
        "click",
        function (e) {
            var menu = KTMenu.getInstance(this);

            if (menu !== null) {
                return menu.click(this, e);
            }
        }
    );

    // Link handler
    KtUtil.on(
        document.body,
        ".menu-item:not([data-kt-menu-trigger]) > .menu-link",
        "click",
        function (e) {
            var menu = KTMenu.getInstance(this);

            if (menu !== null) {
                return menu.link(this, e);
            }
        }
    );

    // Dismiss handler
    KtUtil.on(document.body, "[data-kt-menu-dismiss=\"true\"]", "click", function (e) {
        var menu = KTMenu.getInstance(this);

        if (menu !== null) {
            return menu.dismiss(this, e);
        }
    });

    // Mouseover handler
    KtUtil.on(
        document.body,
        "[data-kt-menu-trigger], .menu-sub",
        "mouseover",
        function (e) {
            var menu = KTMenu.getInstance(this);

            if (menu !== null && menu.getItemSubType(this) === "dropdown") {
                return menu.mouseover(this, e);
            }
        }
    );

    // Mouseout handler
    KtUtil.on(
        document.body,
        "[data-kt-menu-trigger], .menu-sub",
        "mouseout",
        function (e) {
            var menu = KTMenu.getInstance(this);

            if (menu !== null && menu.getItemSubType(this) === "dropdown") {
                return menu.mouseout(this, e);
            }
        }
    );

    // Resize handler
    window.addEventListener("resize", function () {
        var menu;
        var timer;

        KtUtil.throttle(
            timer,
            function () {
                // Locate and update Offcanvas instances on window resize
                var elements = document.querySelectorAll("[data-kt-menu=\"true\"]");

                if (elements && elements.length > 0) {
                    for (var i = 0, len = elements.length; i < len; i++) {
                        menu = KTMenu.getInstance(elements[i]);
                        if (menu) {
                            menu.update();
                        }
                    }
                }
            },
            200
        );
    });
};

// Render menus by url
KTMenu.updateByLinkAttribute = function (value, name = "href") {
    // Locate and update Offcanvas instances on window resize
    var elements = document.querySelectorAll("[data-kt-menu=\"true\"]");

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            var menu = KTMenu.getInstance(elements[i]);

            if (menu) {
                var link = menu.getLinkByAttribute(value, name);
                if (link) {
                    menu.setActiveLink(link);
                }
            }
        }
    }
};

// Global instances
KTMenu.createInstances = function (selector = "[data-kt-menu=\"true\"]") {
    // Initialize menus
    var elements = document.querySelectorAll(selector);
    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new KTMenu(elements[i]);
        }
    }
};

// Global initialization
KTMenu.init = function () {
    KTMenu.createInstances();

    if (KTMenuHandlersInitialized === false) {
        KTMenu.initHandlers();

        KTMenuHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTMenu;
}

// Class definition
var KTPasswordMeter = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    var the = this;

    if (!element) {
        return;
    }

    // Default Options
    var defaultOptions = {
        minLength: 8,
        checkUppercase: true,
        checkLowercase: true,
        checkDigit: true,
        checkChar: true,
        scoreHighlightClass: "active"
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    // Constructor
    var _construct = function () {
        if (KtUtil.data(element).has("password-meter") === true) {
            the = KtUtil.data(element).get("password-meter");
        } else {
            _init();
        }
    };

    // Initialize
    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.score = 0;
        the.checkSteps = 5;

        // Elements
        the.element = element;
        the.inputElement = the.element.querySelector("input[type]");
        the.visibilityElement = the.element.querySelector(
            "[data-kt-password-meter-control=\"visibility\"]"
        );
        the.highlightElement = the.element.querySelector(
            "[data-kt-password-meter-control=\"highlight\"]"
        );

        // Set initialized
        the.element.setAttribute("data-kt-password-meter", "true");

        // Event Handlers
        _handlers();

        // Bind Instance
        KtUtil.data(the.element).set("password-meter", the);
    };

    // Handlers
    var _handlers = function () {
        if (the.highlightElement) {
            the.inputElement.addEventListener("input", function () {
                _check();
            });
        }

        if (the.visibilityElement) {
            the.visibilityElement.addEventListener("click", function () {
                _visibility();
            });
        }
    };

    // Event handlers
    var _check = function () {
        var score = 0;
        var checkScore = _getCheckScore();

        if (_checkLength() === true) {
            score = score + checkScore;
        }

        if (the.options.checkUppercase === true && _checkLowercase() === true) {
            score = score + checkScore;
        }

        if (the.options.checkLowercase === true && _checkUppercase() === true) {
            score = score + checkScore;
        }

        if (the.options.checkDigit === true && _checkDigit() === true) {
            score = score + checkScore;
        }

        if (the.options.checkChar === true && _checkChar() === true) {
            score = score + checkScore;
        }

        the.score = score;

        _highlight();
    };

    var _checkLength = function () {
        return the.inputElement.value.length >= the.options.minLength; // 20 score
    };

    var _checkLowercase = function () {
        return /[a-z]/.test(the.inputElement.value); // 20 score
    };

    var _checkUppercase = function () {
        return /[A-Z]/.test(the.inputElement.value); // 20 score
    };

    var _checkDigit = function () {
        return /[0-9]/.test(the.inputElement.value); // 20 score
    };

    var _checkChar = function () {
        return /[~`!#@$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(the.inputElement.value); // 20 score
    };

    var _getCheckScore = function () {
        var count = 1;

        if (the.options.checkUppercase === true) {
            count++;
        }

        if (the.options.checkLowercase === true) {
            count++;
        }

        if (the.options.checkDigit === true) {
            count++;
        }

        if (the.options.checkChar === true) {
            count++;
        }

        the.checkSteps = count;

        return 100 / the.checkSteps;
    };

    var _highlight = function () {
        var items = [].slice.call(the.highlightElement.querySelectorAll("div"));
        var total = items.length;
        var index = 0;
        var checkScore = _getCheckScore();
        var score = _getScore();

        items.map(function (item) {
            index++;

            if (checkScore * index * (the.checkSteps / total) <= score) {
                item.classList.add("active");
            } else {
                item.classList.remove("active");
            }
        });
    };

    var _visibility = function () {
        var visibleIcon = the.visibilityElement.querySelector(
            "i:not(.d-none), .svg-icon:not(.d-none)"
        );
        var hiddenIcon = the.visibilityElement.querySelector(
            "i.d-none, .svg-icon.d-none"
        );

        if (the.inputElement.getAttribute("type").toLowerCase() === "password") {
            the.inputElement.setAttribute("type", "text");
        } else {
            the.inputElement.setAttribute("type", "password");
        }

        visibleIcon.classList.add("d-none");
        hiddenIcon.classList.remove("d-none");

        the.inputElement.focus();
    };

    var _reset = function () {
        the.score = 0;

        _highlight();
    };

    // Gets current password score
    var _getScore = function () {
        return the.score;
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("password-meter");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.check = function () {
        return _check();
    };

    the.getScore = function () {
        return _getScore();
    };

    the.reset = function () {
        return _reset();
    };

    the.destroy = function () {
        return _destroy();
    };
};

// Static methods
KTPasswordMeter.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("password-meter")) {
        return KtUtil.data(element).get("password-meter");
    } else {
        return null;
    }
};

// Create instances
KTPasswordMeter.createInstances = function (selector = "[data-kt-password-meter]") {
    // Get instances
    var elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            // Initialize instances
            new KTPasswordMeter(elements[i]);
        }
    }
};

// Global initialization
KTPasswordMeter.init = function () {
    KTPasswordMeter.createInstances();
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTPasswordMeter;
}

var KTScrollHandlersInitialized = false;

// Class definition
var KTScroll = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (!element) {
        return;
    }

    // Default options
    var defaultOptions = {
        saveState: true
    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("scroll")) {
            the = KtUtil.data(element).get("scroll");
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);

        // Elements
        the.element = element;
        the.id = the.element.getAttribute("id");

        // Set initialized
        the.element.setAttribute("data-kt-scroll", "true");

        // Update
        _update();

        // Bind Instance
        KtUtil.data(the.element).set("scroll", the);
    };

    var _setupHeight = function () {
        var heightType = _getHeightType();
        var height = _getHeight();

        // Set height
        if (height !== null && height.length > 0) {
            KtUtil.css(the.element, heightType, height);
        } else {
            KtUtil.css(the.element, heightType, "");
        }
    };

    var _setupState = function () {
        var namespace = _getStorageNamespace();

        if (_getOption("save-state") === true && the.id) {
            if (localStorage.getItem(namespace + the.id + "st")) {
                var pos = parseInt(localStorage.getItem(namespace + the.id + "st"));

                if (pos > 0) {
                    the.element.scroll({
                        top: pos,
                        behavior: "instant"
                    });
                }
            }
        }
    };

    var _getStorageNamespace = function (postfix) {
        return document.body.hasAttribute("data-kt-name")
            ? document.body.getAttribute("data-kt-name") + "_"
            : "";
    };

    var _setupScrollHandler = function () {
        if (_getOption("save-state") === true && the.id) {
            the.element.addEventListener("scroll", _scrollHandler);
        } else {
            the.element.removeEventListener("scroll", _scrollHandler);
        }
    };

    var _destroyScrollHandler = function () {
        the.element.removeEventListener("scroll", _scrollHandler);
    };

    var _resetHeight = function () {
        KtUtil.css(the.element, _getHeightType(), "");
    };

    var _scrollHandler = function () {
        var namespace = _getStorageNamespace();
        localStorage.setItem(namespace + the.id + "st", the.element.scrollTop);
    };

    var _update = function () {
        // Activate/deactivate
        if (
            _getOption("activate") === true ||
            the.element.hasAttribute("data-kt-scroll-activate") === false
        ) {
            _setupHeight();
            _setupStretchHeight();
            _setupScrollHandler();
            _setupState();
        } else {
            _resetHeight();
            _destroyScrollHandler();
        }
    };

    var _setupStretchHeight = function () {
        var stretch = _getOption("stretch");

        // Stretch
        if (stretch !== null) {
            var elements = document.querySelectorAll(stretch);

            if (elements && elements.length == 2) {
                var element1 = elements[0];
                var element2 = elements[1];
                var diff = _getElementHeight(element2) - _getElementHeight(element1);

                if (diff > 0) {
                    var height = parseInt(KtUtil.css(the.element, _getHeightType())) + diff;

                    KtUtil.css(the.element, _getHeightType(), String(height) + "px");
                }
            }
        }
    };

    var _getHeight = function () {
        var height = _getOption(_getHeightType());

        if (height instanceof Function) {
            return height.call();
        } else if (
            height !== null &&
            typeof height === "string" &&
            height.toLowerCase() === "auto"
        ) {
            return _getAutoHeight();
        } else {
            return height;
        }
    };

    var _getAutoHeight = function () {
        var height = KtUtil.getViewPort().height;
        var dependencies = _getOption("dependencies");
        var wrappers = _getOption("wrappers");
        var offset = _getOption("offset");

        // Spacings
        height = height - _getElementSpacing(the.element);

        // Height dependencies
        //console.log('Q:' + JSON.stringify(dependencies));

        if (dependencies !== null) {
            let elements = document.querySelectorAll(dependencies);

            if (elements && elements.length > 0) {
                for (var i = 0, len = elements.length; i < len; i++) {
                    if (KtUtil.visible(elements[i]) === false) {
                        continue;
                    }

                    height = height - _getElementHeight(elements[i]);
                }
            }
        }

        // Wrappers
        if (wrappers !== null) {
            var elements = document.querySelectorAll(wrappers);
            if (elements && elements.length > 0) {
                for (let i = 0, len = elements.length; i < len; i++) {
                    if (KtUtil.visible(elements[i]) === false) {
                        continue;
                    }

                    height = height - _getElementSpacing(elements[i]);
                }
            }
        }

        // Custom offset
        if (offset !== null && typeof offset !== "object") {
            height = height - parseInt(offset);
        }

        return String(height) + "px";
    };

    var _getElementHeight = function (element) {
        var height = 0;

        if (element !== null) {
            height = height + parseInt(KtUtil.css(element, "height"));
            height = height + parseInt(KtUtil.css(element, "margin-top"));
            height = height + parseInt(KtUtil.css(element, "margin-bottom"));

            if (KtUtil.css(element, "border-top")) {
                height = height + parseInt(KtUtil.css(element, "border-top"));
            }

            if (KtUtil.css(element, "border-bottom")) {
                height = height + parseInt(KtUtil.css(element, "border-bottom"));
            }
        }

        return height;
    };

    var _getElementSpacing = function (element) {
        var spacing = 0;

        if (element !== null) {
            spacing = spacing + parseInt(KtUtil.css(element, "margin-top"));
            spacing = spacing + parseInt(KtUtil.css(element, "margin-bottom"));
            spacing = spacing + parseInt(KtUtil.css(element, "padding-top"));
            spacing = spacing + parseInt(KtUtil.css(element, "padding-bottom"));

            if (KtUtil.css(element, "border-top")) {
                spacing = spacing + parseInt(KtUtil.css(element, "border-top"));
            }

            if (KtUtil.css(element, "border-bottom")) {
                spacing = spacing + parseInt(KtUtil.css(element, "border-bottom"));
            }
        }

        return spacing;
    };

    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-scroll-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-scroll-" + name);

            var value = KtUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }

            return value;
        } else {
            var optionName = KtUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KtUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    };

    var _getHeightType = function () {
        if (_getOption("height")) {
            return "height";
        }
        if (_getOption("min-height")) {
            return "min-height";
        }
        if (_getOption("max-height")) {
            return "max-height";
        }
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("scroll");
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    the.update = function () {
        return _update();
    };

    the.getHeight = function () {
        return _getHeight();
    };

    the.getElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };
};

// Static methods
KTScroll.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("scroll")) {
        return KtUtil.data(element).get("scroll");
    } else {
        return null;
    }
};

// Create instances
KTScroll.createInstances = function (selector = "[data-kt-scroll=\"true\"]") {
    // Initialize Menus
    var elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new KTScroll(elements[i]);
        }
    }
};

// Window resize handling
KTScroll.handleResize = function () {
    window.addEventListener("resize", function () {
        var timer;

        KtUtil.throttle(
            timer,
            function () {
                // Locate and update Offcanvas instances on window resize
                var elements = document.body.querySelectorAll("[data-kt-scroll=\"true\"]");

                if (elements && elements.length > 0) {
                    for (var i = 0, len = elements.length; i < len; i++) {
                        var scroll = KTScroll.getInstance(elements[i]);
                        if (scroll) {
                            scroll.update();
                        }
                    }
                }
            },
            200
        );
    });
};

// Global initialization
KTScroll.init = function () {
    KTScroll.createInstances();

    if (KTScrollHandlersInitialized === false) {
        KTScroll.handleResize();

        KTScrollHandlersInitialized = true;
    }
};

// Webpack Support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTScroll;
}

("use strict");

// Class definition
var KTScrolltop = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default options
    var defaultOptions = {
        offset: 300,
        speed: 600
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("scrolltop")) {
            the = KtUtil.data(element).get("scrolltop");
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.uid = KtUtil.getUniqueId("scrolltop");
        the.element = element;

        // Set initialized
        the.element.setAttribute("data-kt-scrolltop", "true");

        // Event Handlers
        _handlers();

        // Bind Instance
        KtUtil.data(the.element).set("scrolltop", the);
    };

    var _handlers = function () {
        var timer;

        window.addEventListener("scroll", function () {
            KtUtil.throttle(
                timer,
                function () {
                    _scroll();
                },
                200
            );
        });

        KtUtil.addEvent(the.element, "click", function (e) {
            e.preventDefault();

            _go();
        });
    };

    var _scroll = function () {
        var offset = parseInt(_getOption("offset"));

        var pos = KtUtil.getScrollTop(); // current vertical position

        if (pos > offset) {
            if (document.body.hasAttribute("data-kt-scrolltop") === false) {
                document.body.setAttribute("data-kt-scrolltop", "on");
            }
        } else {
            if (document.body.hasAttribute("data-kt-scrolltop") === true) {
                document.body.removeAttribute("data-kt-scrolltop");
            }
        }
    };

    var _go = function () {
        var speed = parseInt(_getOption("speed"));

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
        //KTUtil.scrollTop(0, speed);
    };

    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-scrolltop-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-scrolltop-" + name);
            var value = KtUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }

            return value;
        } else {
            var optionName = KtUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KtUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("scrolltop");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.go = function () {
        return _go();
    };

    the.getElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };
};

// Static methods
KTScrolltop.getInstance = function (element) {
    if (element && KtUtil.data(element).has("scrolltop")) {
        return KtUtil.data(element).get("scrolltop");
    } else {
        return null;
    }
};

// Create instances
KTScrolltop.createInstances = function (selector = "[data-kt-scrolltop=\"true\"]") {
    // Initialize Menus
    var elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            new KTScrolltop(elements[i]);
        }
    }
};

// Global initialization
KTScrolltop.init = function () {
    KTScrolltop.createInstances();
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTScrolltop;
}

("use strict");

// Class definition
var KTSearch = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    var the = this;

    if (!element) {
        return;
    }

    // Default Options
    var defaultOptions = {
        minLength: 2, // Miniam text lenght to query search
        keypress: true, // Enable search on keypress
        enter: true, // Enable search on enter key press
        layout: "menu", // Use 'menu' or 'inline' layout options to display search results
        responsive: null, // Pass integer value or bootstrap compatible breakpoint key(sm,md,lg,xl,xxl) to enable reponsive form mode for device width below the breakpoint value
        showOnFocus: true // Always show menu on input focus
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    // Construct
    var _construct = function () {
        if (KtUtil.data(element).has("search") === true) {
            the = KtUtil.data(element).get("search");
        } else {
            _init();
        }
    };

    // Init
    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.processing = false;

        // Elements
        the.element = element;
        the.contentElement = _getElement("content");
        the.formElement = _getElement("form");
        the.inputElement = _getElement("input");
        the.spinnerElement = _getElement("spinner");
        the.clearElement = _getElement("clear");
        the.toggleElement = _getElement("toggle");
        the.submitElement = _getElement("submit");
        the.toolbarElement = _getElement("toolbar");

        the.resultsElement = _getElement("results");
        the.suggestionElement = _getElement("suggestion");
        the.emptyElement = _getElement("empty");

        // Set initialized
        the.element.setAttribute("data-kt-search", "true");

        // Layout
        the.layout = _getOption("layout");

        // Menu
        if (the.layout === "menu") {
            the.menuObject = new KTMenu(the.contentElement);
        } else {
            the.menuObject = null;
        }

        // Update
        _update();

        // Event Handlers
        _handlers();

        // Bind Instance
        KtUtil.data(the.element).set("search", the);
    };

    // Handlera
    var _handlers = function () {
        // Focus
        the.inputElement.addEventListener("focus", _focus);

        // Blur
        the.inputElement.addEventListener("blur", _blur);

        // Keypress
        if (_getOption("keypress") === true) {
            the.inputElement.addEventListener("input", _input);
        }

        // Submit
        if (the.submitElement) {
            the.submitElement.addEventListener("click", _search);
        }

        // Enter
        if (_getOption("enter") === true) {
            the.inputElement.addEventListener("keypress", _enter);
        }

        // Clear
        if (the.clearElement) {
            the.clearElement.addEventListener("click", _clear);
        }

        // Menu
        if (the.menuObject) {
            // Toggle menu
            if (the.toggleElement) {
                the.toggleElement.addEventListener("click", _show);

                the.menuObject.on("kt.menu.dropdown.show", function (item) {
                    if (KtUtil.visible(the.toggleElement)) {
                        the.toggleElement.classList.add("active");
                        the.toggleElement.classList.add("show");
                    }
                });

                the.menuObject.on("kt.menu.dropdown.hide", function (item) {
                    if (KtUtil.visible(the.toggleElement)) {
                        the.toggleElement.classList.remove("active");
                        the.toggleElement.classList.remove("show");
                    }
                });
            }

            the.menuObject.on("kt.menu.dropdown.shown", function () {
                the.inputElement.focus();
            });
        }

        // Window resize handling
        window.addEventListener("resize", function () {
            var timer;

            KtUtil.throttle(
                timer,
                function () {
                    _update();
                },
                200
            );
        });
    };

    // Focus
    var _focus = function () {
        the.element.classList.add("focus");
        let minLength = 0;
        if (
            _getOption("show-on-focus") === true ||
            the.inputElement.value.length >= minLength
        ) {
            _show();
        }
    };

    // Blur
    var _blur = function () {
        the.element.classList.remove("focus");
    };

    // Enter
    var _enter = function (e) {
        var key = e.charCode || e.keyCode || 0;

        if (key == 13) {
            e.preventDefault();

            _search();
        }
    };

    // Input
    var _input = function () {
        if (_getOption("min-length")) {
            var minLength = parseInt(_getOption("min-length"));

            if (the.inputElement.value.length >= minLength) {
                _search();
            } else if (the.inputElement.value.length === 0) {
                _clear();
            }
        }
    };

    // Search
    var _search = function () {
        if (the.processing === false) {
            // Show search spinner
            if (the.spinnerElement) {
                the.spinnerElement.classList.remove("d-none");
            }

            // Hide search clear button
            if (the.clearElement) {
                the.clearElement.classList.add("d-none");
            }

            // Hide search toolbar
            if (the.toolbarElement && the.formElement.contains(the.toolbarElement)) {
                the.toolbarElement.classList.add("d-none");
            }

            // Focus input
            the.inputElement.focus();

            the.processing = true;
            KTEventHandler.trigger(the.element, "kt.search.process", the);
        }
    };

    // Complete
    var _complete = function () {
        if (the.spinnerElement) {
            the.spinnerElement.classList.add("d-none");
        }

        // Show search toolbar
        if (the.clearElement) {
            the.clearElement.classList.remove("d-none");
        }

        if (the.inputElement.value.length === 0) {
            _clear();
        }

        // Focus input
        the.inputElement.focus();

        _show();

        the.processing = false;
    };

    // Clear
    var _clear = function () {
        if (KTEventHandler.trigger(the.element, "kt.search.clear", the) === false) {
            return;
        }

        // Clear and focus input
        the.inputElement.value = "";
        the.inputElement.focus();

        // Hide clear icon
        if (the.clearElement) {
            the.clearElement.classList.add("d-none");
        }

        // Show search toolbar
        if (the.toolbarElement && the.formElement.contains(the.toolbarElement)) {
            the.toolbarElement.classList.remove("d-none");
        }

        // Hide menu
        if (_getOption("show-on-focus") === false) {
            _hide();
        }

        KTEventHandler.trigger(the.element, "kt.search.cleared", the);
    };

    // Update
    var _update = function () {
        // Handle responsive form
        if (the.layout === "menu") {
            var responsiveFormMode = _getResponsiveFormMode();

            if (
                responsiveFormMode === "on" &&
                the.contentElement.contains(the.formElement) === false
            ) {
                the.contentElement.prepend(the.formElement);
                the.formElement.classList.remove("d-none");
            } else if (
                responsiveFormMode === "off" &&
                the.contentElement.contains(the.formElement) === true
            ) {
                the.element.prepend(the.formElement);
                the.formElement.classList.add("d-none");
            }
        }
    };

    // Show menu
    var _show = function () {
        if (the.menuObject) {
            _update();

            the.menuObject.show(the.element);
        }
    };

    // Hide menu
    var _hide = function () {
        if (the.menuObject) {
            _update();

            the.menuObject.hide(the.element);
        }
    };

    // Get option
    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-search-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-search-" + name);
            var value = KtUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }

            return value;
        } else {
            var optionName = KtUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KtUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    };

    // Get element
    var _getElement = function (name) {
        return the.element.querySelector("[data-kt-search-element=\"" + name + "\"]");
    };

    // Check if responsive form mode is enabled
    var _getResponsiveFormMode = function () {
        var responsive = _getOption("responsive");
        var width = KtUtil.getViewPort().width;

        if (!responsive) {
            return null;
        }

        var breakpoint = KtUtil.getBreakpoint(responsive);

        if (!breakpoint) {
            breakpoint = parseInt(responsive);
        }

        if (width < breakpoint) {
            return "on";
        } else {
            return "off";
        }
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("search");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.show = function () {
        return _show();
    };

    the.hide = function () {
        return _hide();
    };

    the.update = function () {
        return _update();
    };

    the.search = function () {
        return _search();
    };

    the.complete = function () {
        return _complete();
    };

    the.clear = function () {
        return _clear();
    };

    the.isProcessing = function () {
        return the.processing;
    };

    the.getQuery = function () {
        return the.inputElement.value;
    };

    the.getMenu = function () {
        return the.menuObject;
    };

    the.getFormElement = function () {
        return the.formElement;
    };

    the.getInputElement = function () {
        return the.inputElement;
    };

    the.getContentElement = function () {
        return the.contentElement;
    };

    the.getElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };
};

// Static methods
KTSearch.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("search")) {
        return KtUtil.data(element).get("search");
    } else {
        return null;
    }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTSearch;
}


var KTStickyHandlersInitialized = false;

// Class definition
var KTSticky = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {
        offset: 200,
        reverse: false,
        release: null,
        animation: true,
        animationSpeed: "0.3s",
        animationClass: "animation-slide-in-down"
    };
    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("sticky") === true) {
            the = KtUtil.data(element).get("sticky");
        } else {
            _init();
        }
    };

    var _init = function () {
        the.element = element;
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.uid = KtUtil.getUniqueId("sticky");
        the.name = the.element.getAttribute("data-kt-sticky-name");
        the.attributeName = "data-kt-sticky-" + the.name;
        the.attributeName2 = "data-kt-" + the.name;
        the.eventTriggerState = true;
        the.lastScrollTop = 0;
        the.scrollHandler;

        // Set initialized
        the.element.setAttribute("data-kt-sticky", "true");

        // Event Handlers
        window.addEventListener("scroll", _scroll);

        // Initial Launch
        _scroll();

        // Bind Instance
        KtUtil.data(the.element).set("sticky", the);
    };

    var _scroll = function (e) {
        var offset = _getOption("offset");
        var release = _getOption("release");
        var reverse = _getOption("reverse");
        var st;
        var attrName;
        var diff;

        // Exit if false
        if (offset === false) {
            return;
        }

        offset = parseInt(offset);
        release = release ? document.querySelector(release) : null;

        st = KtUtil.getScrollTop();
        diff =
            document.documentElement.scrollHeight -
            window.innerHeight -
            KtUtil.getScrollTop();

        var proceed = !release || release.offsetTop - release.clientHeight > st;

        if (reverse === true) {
            // Release on reverse scroll mode
            if (st > offset && proceed) {
                if (document.body.hasAttribute(the.attributeName) === false) {
                    if (_enable() === false) {
                        return;
                    }

                    document.body.setAttribute(the.attributeName, "on");
                    document.body.setAttribute(the.attributeName2, "on");
                    the.element.setAttribute("data-kt-sticky-enabled", "true");
                }

                if (the.eventTriggerState === true) {
                    KTEventHandler.trigger(the.element, "kt.sticky.on", the);
                    KTEventHandler.trigger(the.element, "kt.sticky.change", the);

                    the.eventTriggerState = false;
                }
            } else {
                // Back scroll mode
                if (document.body.hasAttribute(the.attributeName) === true) {
                    _disable();
                    document.body.removeAttribute(the.attributeName);
                    document.body.removeAttribute(the.attributeName2);
                    the.element.removeAttribute("data-kt-sticky-enabled");
                }

                if (the.eventTriggerState === false) {
                    KTEventHandler.trigger(the.element, "kt.sticky.off", the);
                    KTEventHandler.trigger(the.element, "kt.sticky.change", the);
                    the.eventTriggerState = true;
                }
            }

            the.lastScrollTop = st;
        } else {
            // Classic scroll mode
            if (st > offset && proceed) {
                if (document.body.hasAttribute(the.attributeName) === false) {
                    if (_enable() === false) {
                        return;
                    }

                    document.body.setAttribute(the.attributeName, "on");
                    document.body.setAttribute(the.attributeName2, "on");
                    the.element.setAttribute("data-kt-sticky-enabled", "true");
                }

                if (the.eventTriggerState === true) {
                    KTEventHandler.trigger(the.element, "kt.sticky.on", the);
                    KTEventHandler.trigger(the.element, "kt.sticky.change", the);
                    the.eventTriggerState = false;
                }
            } else {
                // back scroll mode
                if (document.body.hasAttribute(the.attributeName) === true) {
                    _disable();
                    document.body.removeAttribute(the.attributeName);
                    document.body.removeAttribute(the.attributeName2);
                    the.element.removeAttribute("data-kt-sticky-enabled");
                }

                if (the.eventTriggerState === false) {
                    KTEventHandler.trigger(the.element, "kt.sticky.off", the);
                    KTEventHandler.trigger(the.element, "kt.sticky.change", the);
                    the.eventTriggerState = true;
                }
            }
        }

        if (release) {
            if (release.offsetTop - release.clientHeight > st) {
                the.element.setAttribute("data-kt-sticky-released", "true");
            } else {
                the.element.removeAttribute("data-kt-sticky-released");
            }
        }
    };

    var _enable = function (update) {
        var top = _getOption("top");
        top = top ? parseInt(top) : 0;

        var left = _getOption("left");
        var right = _getOption("right");
        var width = _getOption("width");
        var zindex = _getOption("zindex");
        var dependencies = _getOption("dependencies");
        var classes = _getOption("class");

        var height = _calculateHeight();
        var heightOffset = _getOption("height-offset");
        heightOffset = heightOffset ? parseInt(heightOffset) : 0;

        if (height + heightOffset + top > KtUtil.getViewPort().height) {
            return false;
        }

        if (update !== true && _getOption("animation") === true) {
            KtUtil.css(the.element, "animationDuration", _getOption("animationSpeed"));
            KtUtil.animateClass(the.element, "animation " + _getOption("animationClass"));
        }

        if (classes !== null) {
            KtUtil.addClass(the.element, classes);
        }

        if (zindex !== null) {
            KtUtil.css(the.element, "z-index", zindex);
            KtUtil.css(the.element, "position", "fixed");
        }

        if (top >= 0) {
            KtUtil.css(the.element, "top", String(top) + "px");
        }

        if (width !== null) {
            if (width["target"]) {
                var targetElement = document.querySelector(width["target"]);
                if (targetElement) {
                    width = KtUtil.css(targetElement, "width");
                }
            }

            KtUtil.css(the.element, "width", width);
        }

        if (left !== null) {
            if (String(left).toLowerCase() === "auto") {
                var offsetLeft = KtUtil.offset(the.element).left;

                if (offsetLeft >= 0) {
                    KtUtil.css(the.element, "left", String(offsetLeft) + "px");
                }
            } else {
                KtUtil.css(the.element, "left", left);
            }
        }

        if (right !== null) {
            KtUtil.css(the.element, "right", right);
        }

        // Height dependencies
        if (dependencies !== null) {
            var dependencyElements = document.querySelectorAll(dependencies);

            if (dependencyElements && dependencyElements.length > 0) {
                for (var i = 0, len = dependencyElements.length; i < len; i++) {
                    KtUtil.css(dependencyElements[i], "padding-top", String(height) + "px");
                }
            }
        }
    };

    var _disable = function () {
        KtUtil.css(the.element, "top", "");
        KtUtil.css(the.element, "width", "");
        KtUtil.css(the.element, "left", "");
        KtUtil.css(the.element, "right", "");
        KtUtil.css(the.element, "z-index", "");
        KtUtil.css(the.element, "position", "");

        var dependencies = _getOption("dependencies");
        var classes = _getOption("class");

        if (classes !== null) {
            KtUtil.removeClass(the.element, classes);
        }

        // Height dependencies
        if (dependencies !== null) {
            var dependencyElements = document.querySelectorAll(dependencies);

            if (dependencyElements && dependencyElements.length > 0) {
                for (var i = 0, len = dependencyElements.length; i < len; i++) {
                    KtUtil.css(dependencyElements[i], "padding-top", "");
                }
            }
        }
    };

    var _check = function () {
    };

    var _calculateHeight = function () {
        var height = parseFloat(KtUtil.css(the.element, "height"));

        height = height + parseFloat(KtUtil.css(the.element, "margin-top"));
        height = height + parseFloat(KtUtil.css(the.element, "margin-bottom"));

        if (KtUtil.css(element, "border-top")) {
            height = height + parseFloat(KtUtil.css(the.element, "border-top"));
        }

        if (KtUtil.css(element, "border-bottom")) {
            height = height + parseFloat(KtUtil.css(the.element, "border-bottom"));
        }

        return height;
    };

    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-sticky-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-sticky-" + name);
            var value = KtUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }

            return value;
        } else {
            var optionName = KtUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KtUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    };

    var _destroy = function () {
        window.removeEventListener("scroll", _scroll);
        KtUtil.data(the.element).remove("sticky");
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Methods
    the.update = function () {
        if (document.body.hasAttribute(the.attributeName) === true) {
            _disable();
            document.body.removeAttribute(the.attributeName);
            document.body.removeAttribute(the.attributeName2);
            _enable(true);
            document.body.setAttribute(the.attributeName, "on");
            document.body.setAttribute(the.attributeName2, "on");
        }
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Static methods
KTSticky.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("sticky")) {
        return KtUtil.data(element).get("sticky");
    } else {
        return null;
    }
};

// Create instances
KTSticky.createInstances = function (selector = "[data-kt-sticky=\"true\"]") {
    // Initialize Menus
    var elements = document.body.querySelectorAll(selector);
    var sticky;

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            sticky = new KTSticky(elements[i]);
        }
    }
};

// Window resize handler
KTSticky.handleResize = function () {
    window.addEventListener("resize", function () {
        var timer;

        KtUtil.throttle(
            timer,
            function () {
                // Locate and update Offcanvas instances on window resize
                var elements = document.body.querySelectorAll("[data-kt-sticky=\"true\"]");

                if (elements && elements.length > 0) {
                    for (var i = 0, len = elements.length; i < len; i++) {
                        var sticky = KTSticky.getInstance(elements[i]);
                        if (sticky) {
                            sticky.update();
                        }
                    }
                }
            },
            200
        );
    });
};

// Global initialization
KTSticky.init = function () {
    KTSticky.createInstances();

    if (KTStickyHandlersInitialized === false) {
        KTSticky.handleResize();
        KTStickyHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTSticky;
}


var KTSwapperHandlersInitialized = false;

// Class definition
var KTSwapper = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    var the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    var defaultOptions = {
        mode: "append"
    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("swapper") === true) {
            the = KtUtil.data(element).get("swapper");
        } else {
            _init();
        }
    };

    var _init = function () {
        the.element = element;
        the.options = KtUtil.deepExtend({}, defaultOptions, options);

        // Set initialized
        the.element.setAttribute("data-kt-swapper", "true");

        // Initial update
        _update();

        // Bind Instance
        KtUtil.data(the.element).set("swapper", the);
    };

    var _update = function (e) {
        var parentSelector = _getOption("parent");

        var mode = _getOption("mode");
        var parentElement = parentSelector
            ? document.querySelector(parentSelector)
            : null;

        if (parentElement && element.parentNode !== parentElement) {
            if (mode === "prepend") {
                parentElement.prepend(element);
            } else if (mode === "append") {
                parentElement.append(element);
            }
        }
    };

    var _getOption = function (name) {
        if (the.element.hasAttribute("data-kt-swapper-" + name) === true) {
            var attr = the.element.getAttribute("data-kt-swapper-" + name);
            var value = KtUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === "true") {
                value = true;
            } else if (value !== null && String(value) === "false") {
                value = false;
            }

            return value;
        } else {
            var optionName = KtUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KtUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("swapper");
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Methods
    the.update = function () {
        _update();
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Static methods
KTSwapper.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("swapper")) {
        return KtUtil.data(element).get("swapper");
    } else {
        return null;
    }
};

// Create instances
KTSwapper.createInstances = function (selector = "[data-kt-swapper=\"true\"]") {
    // Initialize Menus
    var elements = document.querySelectorAll(selector);
    var swapper;

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            swapper = new KTSwapper(elements[i]);
        }
    }
};

// Window resize handler
KTSwapper.handleResize = function () {
    window.addEventListener("resize", function () {
        var timer;

        KtUtil.throttle(
            timer,
            function () {
                // Locate and update Offcanvas instances on window resize
                var elements = document.querySelectorAll("[data-kt-swapper=\"true\"]");

                if (elements && elements.length > 0) {
                    for (var i = 0, len = elements.length; i < len; i++) {
                        var swapper = KTSwapper.getInstance(elements[i]);
                        if (swapper) {
                            swapper.update();
                        }
                    }
                }
            },
            200
        );
    });
};

// Global initialization
KTSwapper.init = function () {
    KTSwapper.createInstances();

    if (KTSwapperHandlersInitialized === false) {
        KTSwapper.handleResize();
        KTSwapperHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTSwapper;
}


// Class definition
var KTToggle = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    var the = this;

    if (!element) {
        return;
    }

    // Default Options
    var defaultOptions = {
        saveState: true
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    var _construct = function () {
        if (KtUtil.data(element).has("toggle") === true) {
            the = KtUtil.data(element).get("toggle");
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KtUtil.deepExtend({}, defaultOptions, options);
        the.uid = KtUtil.getUniqueId("toggle");

        // Elements
        the.element = element;

        the.target = document.querySelector(
            the.element.getAttribute("data-kt-toggle-target")
        )
            ? document.querySelector(the.element.getAttribute("data-kt-toggle-target"))
            : the.element;
        the.state = the.element.hasAttribute("data-kt-toggle-state")
            ? the.element.getAttribute("data-kt-toggle-state")
            : "";
        the.mode = the.element.hasAttribute("data-kt-toggle-mode")
            ? the.element.getAttribute("data-kt-toggle-mode")
            : "";
        the.attribute = "data-kt-" + the.element.getAttribute("data-kt-toggle-name");

        // Event Handlers
        _handlers();

        // Bind Instance
        KtUtil.data(the.element).set("toggle", the);
    };

    var _handlers = function () {
        KtUtil.addEvent(the.element, "click", function (e) {
            e.preventDefault();

            if (the.mode !== "") {
                if (the.mode === "off" && _isEnabled() === false) {
                    _toggle();
                } else if (the.mode === "on" && _isEnabled() === true) {
                    _toggle();
                }
            } else {
                _toggle();
            }
        });
    };

    // Event handlers
    var _toggle = function () {
        // Trigger "after.toggle" event
        KTEventHandler.trigger(the.element, "kt.toggle.change", the);

        if (_isEnabled()) {
            _disable();
        } else {
            _enable();
        }

        // Trigger "before.toggle" event
        KTEventHandler.trigger(the.element, "kt.toggle.changed", the);

        return the;
    };

    var _enable = function () {
        if (_isEnabled() === true) {
            return;
        }

        KTEventHandler.trigger(the.element, "kt.toggle.enable", the);

        the.target.setAttribute(the.attribute, "on");

        if (the.state.length > 0) {
            the.element.classList.add(the.state);
        }

        if (typeof KTCookie !== "undefined" && the.options.saveState === true) {
            KTCookie.set(the.attribute, "on");
        }

        KTEventHandler.trigger(the.element, "kt.toggle.enabled", the);

        return the;
    };

    var _disable = function () {
        if (_isEnabled() === false) {
            return;
        }

        KTEventHandler.trigger(the.element, "kt.toggle.disable", the);

        the.target.removeAttribute(the.attribute);

        if (the.state.length > 0) {
            the.element.classList.remove(the.state);
        }

        if (typeof KTCookie !== "undefined" && the.options.saveState === true) {
            KTCookie.remove(the.attribute);
        }

        KTEventHandler.trigger(the.element, "kt.toggle.disabled", the);

        return the;
    };

    var _isEnabled = function () {
        return String(the.target.getAttribute(the.attribute)).toLowerCase() === "on";
    };

    var _destroy = function () {
        KtUtil.data(the.element).remove("toggle");
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.toggle = function () {
        return _toggle();
    };

    the.enable = function () {
        return _enable();
    };

    the.disable = function () {
        return _disable();
    };

    the.isEnabled = function () {
        return _isEnabled();
    };

    the.goElement = function () {
        return the.element;
    };

    the.destroy = function () {
        return _destroy();
    };

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    };

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    };

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    };

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    };
};

// Static methods
KTToggle.getInstance = function (element) {
    if (element !== null && KtUtil.data(element).has("toggle")) {
        return KtUtil.data(element).get("toggle");
    } else {
        return null;
    }
};

// Create instances
KTToggle.createInstances = function (selector = "[data-kt-toggle]") {
    // Get instances
    var elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        for (var i = 0, len = elements.length; i < len; i++) {
            // Initialize instances
            new KTToggle(elements[i]);
        }
    }
};

// Global initialization
KTToggle.init = function () {
    KTToggle.createInstances();
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTToggle;
}

/**
 * @class KTUtil  base utilize class that privides helper functions
 */

// Polyfills

// Element.matches() polyfill
if (!Element.prototype.matches) {
    Element.prototype.matches = function (s) {
        var matches = (this.document || this.ownerDocument).querySelectorAll(s),
            i = matches.length;
        while (--i >= 0 && matches.item(i) !== this) {
            //ignored
        }
        return i > -1;
    };
}

/**
 * Element.closest() polyfill
 * https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
 */
if (!Element.prototype.closest) {
    Element.prototype.closest = function (s) {
        var el = this;
        var ancestor = this;
        if (!document.documentElement.contains(el)) return null;
        do {
            if (ancestor.matches(s)) return ancestor;
            ancestor = ancestor.parentElement;
        } while (ancestor !== null);
        return null;
    };
}

/**
 * ChildNode.remove() polyfill
 * https://gomakethings.com/removing-an-element-from-the-dom-the-es6-way/
 * @author Chris Ferdinandi
 * @license MIT
 */
(function (elem) {
    for (var i = 0; i < elem.length; i++) {
        if (!window[elem[i]] || "remove" in window[elem[i]].prototype) continue;
        window[elem[i]].prototype.remove = function () {
            this.parentNode.removeChild(this);
        };
    }
})(["Element", "CharacterData", "DocumentType"]);

//
// requestAnimationFrame polyfill by Erik Möller.
//  With fixes from Paul Irish and Tino Zijdel
//
//  http://paulirish.com/2011/requestanimationframe-for-smart-animating/
//  http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating
//
//  MIT license
//
(function () {
    var lastTime = 0;
    var vendors = ["webkit", "moz"];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"];
        window.cancelAnimationFrame =
            window[vendors[x] + "CancelAnimationFrame"] ||
            window[vendors[x] + "CancelRequestAnimationFrame"];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function (callback) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function () {
                callback(currTime + timeToCall);
            }, timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function (id) {
            clearTimeout(id);
        };
})();

// Source: https://github.com/jserz/js_piece/blob/master/DOM/ParentNode/prepend()/prepend().md
(function (arr) {
    arr.forEach(function (item) {
        if (item.hasOwnProperty("prepend")) {
            return;
        }
        Object.defineProperty(item, "prepend", {
            configurable: true,
            enumerable: true,
            writable: true,
            value: function prepend() {
                var argArr = Array.prototype.slice.call(arguments),
                    docFrag = document.createDocumentFragment();

                argArr.forEach(function (argItem) {
                    var isNode = argItem instanceof Node;
                    docFrag.appendChild(
                        isNode ? argItem : document.createTextNode(String(argItem))
                    );
                });

                this.insertBefore(docFrag, this.firstChild);
            }
        });
    });
})([Element.prototype, Document.prototype, DocumentFragment.prototype]);

// getAttributeNames
if (Element.prototype.getAttributeNames == undefined) {
    Element.prototype.getAttributeNames = function () {
        var attributes = this.attributes;
        var length = attributes.length;
        var result = new Array(length);
        for (var i = 0; i < length; i++) {
            result[i] = attributes[i].name;
        }
        return result;
    };
}

// Global variables
window.KTUtilElementDataStore = {};
window.KTUtilElementDataStoreID = 0;
window.KTUtilDelegatedEventHandlers = {};


("use strict");

// Class definition
var KTAppLayoutBuilder = (function () {
    var form;
    var actionInput;
    var url;
    var previewButton;
    var exportButton;
    var resetButton;

    var engage;
    var engageToggleOff;
    var engageToggleOn;
    var engagePrebuiltsModal;

    var handleEngagePrebuilts = function () {
        if (engagePrebuiltsModal === null) {
            return;
        }

        if (KTCookie.get("app_engage_prebuilts_modal_displayed") !== "1") {
            setTimeout(function () {
                const modal = new bootstrap.Modal(engagePrebuiltsModal);
                modal.show();

                const date = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000); // 30 days from now
                KTCookie.set("app_engage_prebuilts_modal_displayed", "1", {
                    expires: date
                });
            }, 3000);
        }
    };

    var handleEngagePrebuiltsViewMenu = function () {
        const selected = engagePrebuiltsModal.querySelector(
            "[data-kt-element=\"selected\"]"
        );
        const selectedTitle = engagePrebuiltsModal.querySelector(
            "[data-kt-element=\"title\"]"
        );
        const menu = engagePrebuiltsModal.querySelector("[data-kt-menu=\"true\"]");

        // Toggle Handler
        KtUtil.on(engagePrebuiltsModal, "[data-kt-mode]", "click", function (e) {
            const title = this.innerText;
            const mode = this.getAttribute("data-kt-mode");
            const selectedLink = menu.querySelector(".menu-link.active");
            const viewImage = document.querySelector("#kt_app_engage_prebuilts_view_image");
            const viewText = document.querySelector("#kt_app_engage_prebuilts_view_text");
            selectedTitle.innerText = title;

            if (selectedLink) {
                selectedLink.classList.remove("active");
            }

            this.classList.add("active");

            if (mode === "image") {
                viewImage.classList.remove("d-none");
                viewImage.classList.add("d-block");
                viewText.classList.remove("d-block");
                viewText.classList.add("d-none");
            } else {
                viewText.classList.remove("d-none");
                viewText.classList.add("d-block");
                viewImage.classList.remove("d-block");
                viewImage.classList.add("d-none");
            }
        });
    };

    var handleEngageToggle = function () {
        engageToggleOff.addEventListener("click", function (e) {
            e.preventDefault();

            const date = new Date(Date.now() + 1 * 24 * 60 * 60 * 1000); // 1 days from now
            KTCookie.set("app_engage_hide", "1", {
                expires: date
            });
            engage.classList.add("app-engage-hide");
        });

        engageToggleOn.addEventListener("click", function (e) {
            e.preventDefault();

            KTCookie.remove("app_engage_hide");
            engage.classList.remove("app-engage-hide");
        });
    };

    var handlePreview = function () {
        previewButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Set form action value
            actionInput.value = "preview";

            // Show progress
            previewButton.setAttribute("data-kt-indicator", "on");

            // Prepare form data
            let data = $(form).serialize();

            // Submit
            $.ajax({
                type: "POST",
                dataType: "html",
                url: url,
                data: data,
                success: function (response) {
                    if (history.scrollRestoration) {
                        history.scrollRestoration = "manual";
                    }
                    location.reload();
                    //return;

                    toastr.success(
                        "Preview has been updated with current configured layout.",
                        "Preview updated!",
                        {
                            timeOut: 0,
                            extendedTimeOut: 0,
                            closeButton: true,
                            closeDuration: 0
                        }
                    );

                    setTimeout(function () {
                        location.reload(); // reload page
                    }, 1500);
                },
                error: function (response) {
                    toastr.error("Please try it again later.", "Something went wrong!", {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        closeButton: true,
                        closeDuration: 0
                    });
                },
                complete: function () {
                    previewButton.removeAttribute("data-kt-indicator");
                }
            });
        });
    };

    var handleExport = function () {
        exportButton.addEventListener("click", function (e) {
            e.preventDefault();

            toastr.success(
                "Process has been started and it may take a while.",
                "Generating HTML!",
                {
                    timeOut: 0,
                    extendedTimeOut: 0,
                    closeButton: true,
                    closeDuration: 0
                }
            );

            // Show progress
            exportButton.setAttribute("data-kt-indicator", "on");

            // Set form action value
            actionInput.value = "export";

            // Prepare form data
            let data = $(form).serialize();

            $.ajax({
                type: "POST",
                dataType: "html",
                url: url,
                data: data,
                success: function (response) {
                    let timer = setInterval(function () {
                        $("<iframe/>")
                            .attr({
                                src:
                                    url +
                                    "?layout-builder[action]=export&download=1&output=" +
                                    response,
                                style: "visibility:hidden;display:none"
                            })
                            .ready(function () {
                                // Stop the timer
                                clearInterval(timer);

                                exportButton.removeAttribute("data-kt-indicator");
                            })
                            .appendTo("body");
                    }, 3000);
                },
                error: function () {
                    toastr.error("Please try it again later.", "Something went wrong!", {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        closeButton: true,
                        closeDuration: 0
                    });

                    exportButton.removeAttribute("data-kt-indicator");
                }
            });
        });
    };

    var handleReset = function () {
        resetButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Show progress
            resetButton.setAttribute("data-kt-indicator", "on");

            // Set form action value
            actionInput.value = "reset";

            // Prepare form data
            var data = $(form).serialize();

            $.ajax({
                type: "POST",
                dataType: "html",
                url: url,
                data: data,
                success: function (response) {
                    if (history.scrollRestoration) {
                        history.scrollRestoration = "manual";
                    }

                    location.reload();
                    //return;

                    toastr.success(
                        "Preview has been successfully reset and the page will be reloaded.",
                        "Reset Preview!",
                        {
                            timeOut: 0,
                            extendedTimeOut: 0,
                            closeButton: true,
                            closeDuration: 0
                        }
                    );

                    setTimeout(function () {
                        location.reload(); // reload page
                    }, 1500);
                },
                error: function () {
                    toastr.error("Please try it again later.", "Something went wrong!", {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        closeButton: true,
                        closeDuration: 0
                    });
                },
                complete: function () {
                    resetButton.removeAttribute("data-kt-indicator");
                }
            });
        });
    };

    var handleThemeMode = function () {
        var checkLight = document.querySelector("#kt_layout_builder_theme_mode_light");
        var checkDark = document.querySelector("#kt_layout_builder_theme_mode_dark");
        var check = document.querySelector(
            "#kt_layout_builder_theme_mode_" + KTThemeMode.getMode()
        );

        if (checkLight) {
            checkLight.addEventListener("click", function () {
                this.checked = true;
                this.closest("[data-kt-buttons=\"true\"]")
                    .querySelector(".form-check-image.active")
                    .classList.remove("active");
                this.closest(".form-check-image").classList.add("active");
                KTThemeMode.setMode("light");
            });
        }

        if (checkDark) {
            checkDark.addEventListener("click", function () {
                this.checked = true;
                this.closest("[data-kt-buttons=\"true\"]")
                    .querySelector(".form-check-image.active")
                    .classList.remove("active");
                this.closest(".form-check-image").classList.add("active");
                KTThemeMode.setMode("dark");
            });
        }

        if (check) {
            check.closest(".form-check-image").classList.add("active");
            check.checked = true;
        }
    };

    return {
        // Public functions
        init: function () {
            engage = document.querySelector("#kt_app_engage");
            engageToggleOn = document.querySelector("#kt_app_engage_toggle_on");
            engageToggleOff = document.querySelector("#kt_app_engage_toggle_off");
            engagePrebuiltsModal = document.querySelector("#kt_app_engage_prebuilts_modal");

            if (engage && engagePrebuiltsModal) {
                //handleEngagePrebuilts();
                //handleEngagePrebuiltsViewMenu();
            }

            if (engage && engageToggleOn && engageToggleOff) {
                handleEngageToggle();
            }

            form = document.querySelector("#kt_app_layout_builder_form");

            if (!form) {
                return;
            }

            url = form.getAttribute("action");
            actionInput = document.querySelector("#kt_app_layout_builder_action");
            previewButton = document.querySelector("#kt_app_layout_builder_preview");
            exportButton = document.querySelector("#kt_app_layout_builder_export");
            resetButton = document.querySelector("#kt_app_layout_builder_reset");

            if (previewButton) {
                handlePreview();
            }

            if (exportButton) {
                handleExport();
            }

            if (resetButton) {
                handleReset();
            }

            handleThemeMode();
        }
    };
})();

// On document ready
KtUtil.onDOMContentLoaded(function () {
    KTAppLayoutBuilder.init();
});
("use strict");

// Class definition
var KTLayoutSearch = (function () {
    // Private variables
    var element;
    var formElement;
    var mainElement;
    var resultsElement;
    var wrapperElement;
    var emptyElement;

    var preferencesElement;
    var preferencesShowElement;
    var preferencesDismissElement;

    var advancedOptionsFormElement;
    var advancedOptionsFormShowElement;
    var advancedOptionsFormCancelElement;
    var advancedOptionsFormSearchElement;

    var searchObject;

    // Private functions
    var processs = function (search) {
        var timeout = setTimeout(function () {
            var number = KtUtil.getRandomInt(1, 3);

            // Hide recently viewed
            mainElement.classList.add("d-none");

            if (number === 3) {
                // Hide results
                resultsElement.classList.add("d-none");
                // Show empty message
                emptyElement.classList.remove("d-none");
            } else {
                // Show results
                resultsElement.classList.remove("d-none");
                // Hide empty message
                emptyElement.classList.add("d-none");
            }

            // Complete search
            search.complete();
        }, 1500);
    };

    var processsAjax = function (search) {
        // Hide recently viewed
        mainElement.classList.add("d-none");

        // Learn more: https://axios-http.com/docs/intro
        axios
            .post("/search.php", {
                query: searchObject.getQuery()
            })
            .then(function (response) {
                // Populate results
                resultsElement.innerHTML = response;
                // Show results
                resultsElement.classList.remove("d-none");
                // Hide empty message
                emptyElement.classList.add("d-none");

                // Complete search
                search.complete();
            })
            .catch(function (error) {
                // Hide results
                resultsElement.classList.add("d-none");
                // Show empty message
                emptyElement.classList.remove("d-none");

                // Complete search
                search.complete();
            });
    };

    var clear = function (search) {
        // Show recently viewed
        mainElement.classList.remove("d-none");
        // Hide results
        resultsElement.classList.add("d-none");
        // Hide empty message
        emptyElement.classList.add("d-none");
    };

    var handlePreferences = function () {
        // Preference show handler
        preferencesShowElement.addEventListener("click", function () {
            wrapperElement.classList.add("d-none");
            preferencesElement.classList.remove("d-none");
        });

        // Preference dismiss handler
        preferencesDismissElement.addEventListener("click", function () {
            wrapperElement.classList.remove("d-none");
            preferencesElement.classList.add("d-none");
        });
    };

    var handleAdvancedOptionsForm = function () {
        // Show
        advancedOptionsFormShowElement.addEventListener("click", function () {
            wrapperElement.classList.add("d-none");
            advancedOptionsFormElement.classList.remove("d-none");
        });

        // Cancel
        advancedOptionsFormCancelElement.addEventListener("click", function () {
            wrapperElement.classList.remove("d-none");
            advancedOptionsFormElement.classList.add("d-none");
        });

        // Search
        advancedOptionsFormSearchElement.addEventListener("click", function () {
        });
    };

    // Public methods
    return {
        init: function () {
            // Elements
            element = document.querySelector("#kt_header_search");

            if (!element) {
                return;
            }

            wrapperElement = element.querySelector("[data-kt-search-element=\"wrapper\"]");
            formElement = element.querySelector("[data-kt-search-element=\"form\"]");
            mainElement = element.querySelector("[data-kt-search-element=\"main\"]");
            resultsElement = element.querySelector("[data-kt-search-element=\"results\"]");
            emptyElement = element.querySelector("[data-kt-search-element=\"empty\"]");

            preferencesElement = element.querySelector(
                "[data-kt-search-element=\"preferences\"]"
            );
            preferencesShowElement = element.querySelector(
                "[data-kt-search-element=\"preferences-show\"]"
            );
            preferencesDismissElement = element.querySelector(
                "[data-kt-search-element=\"preferences-dismiss\"]"
            );

            advancedOptionsFormElement = element.querySelector(
                "[data-kt-search-element=\"advanced-options-form\"]"
            );
            advancedOptionsFormShowElement = element.querySelector(
                "[data-kt-search-element=\"advanced-options-form-show\"]"
            );
            advancedOptionsFormCancelElement = element.querySelector(
                "[data-kt-search-element=\"advanced-options-form-cancel\"]"
            );
            advancedOptionsFormSearchElement = element.querySelector(
                "[data-kt-search-element=\"advanced-options-form-search\"]"
            );

            // Initialize search handler
            searchObject = new KTSearch(element);

            // Demo search handler
            searchObject.on("kt.search.process", processs);

            // Ajax search handler
            //searchObject.on('kt.search.process', processsAjax);

            // Clear handler
            searchObject.on("kt.search.clear", clear);

            // Custom handlers
            handlePreferences();
            handleAdvancedOptionsForm();
        }
    };
})();

// On document ready
KtUtil.onDOMContentLoaded(function () {
    KTLayoutSearch.init();
});

// Class definition
let KTThemeModeUser = (function () {
    let handleSubmit = function () {
        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            let menuMode = KTThemeMode.getMenuMode();
            let mode = KTThemeMode.getMode();
            console.log("user selected theme mode:" + menuMode);
            console.log("theme mode:" + mode);

            // Submit selected theme mode menu option via ajax and
            // store it in user profile and set the user opted theme mode via HTML attribute
            // <html data-theme-mode="light"> .... </html>
        });
    };

    return {
        init: function () {
            handleSubmit();
        }
    };
})();

// Initialize app on document ready
KtUtil.onDOMContentLoaded(function () {
    KTThemeModeUser.init();
});

// Declare KTThemeModeUser for Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTThemeModeUser;
}

("use strict");

// Class definition
var KTAppSidebar = (function () {
    // Private variables
    var toggle;
    var sidebar;
    var headerMenu;
    var menuDashboardsCollapse;
    var menuWrapper;

    // Private functions
    // Handle sidebar minimize mode toggle
    var handleToggle = function () {
        var toggleObj = KTToggle.getInstance(toggle);
        var headerMenuObj = KTMenu.getInstance(headerMenu);

        if (toggleObj === null) {
            return;
        }

        // Add a class to prevent sidebar hover effect after toggle click
        toggleObj.on("kt.toggle.change", function () {
            // Set animation state
            sidebar.classList.add("animating");

            // Wait till animation finishes
            setTimeout(function () {
                // Remove animation state
                sidebar.classList.remove("animating");
            }, 300);

            // Prevent header menu dropdown display on hover
            if (headerMenuObj) {
                headerMenuObj.disable();

                // Timeout to enable header menu
                setTimeout(function () {
                    headerMenuObj.enable();
                }, 1000);
            }
        });

        // Store sidebar minimize state in cookie
        toggleObj.on("kt.toggle.changed", function () {
            // In server side check sidebar_minimize_state cookie
            // value and add data-kt-app-sidebar-minimize="on"
            // attribute to Body tag and "active" class to the toggle button
            var date = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000); // 30 days from now

            KTCookie.set("sidebar_minimize_state", toggleObj.isEnabled() ? "on" : "off", {
                expires: date
            });
        });
    };

    // Handle dashboards menu items collapse mode
    var handleShowMore = function () {
        menuDashboardsCollapse.addEventListener("hide.bs.collapse", (event) => {
            menuWrapper.scrollTo({
                top: 0,
                behavior: "instant"
            });
        });
    };

    var handleMenuScroll = function () {
        var menuActiveItem = menuWrapper.querySelector(".menu-link.active");

        if (!menuActiveItem) {
            return;
        }

        if (KtUtil.isVisibleInContainer(menuActiveItem, menuWrapper) === true) {
            return;
        }

        menuWrapper.scroll({
            top: KtUtil.getRelativeTopPosition(menuActiveItem, menuWrapper),
            behavior: "smooth"
        });
    };

    // Public methods
    return {
        init: function () {
            // Elements
            sidebar = document.querySelector("#kt_app_sidebar");
            toggle = document.querySelector("#kt_app_sidebar_toggle");
            headerMenu = document.querySelector("#kt_app_header_menu");
            menuDashboardsCollapse = document.querySelector(
                "#kt_app_sidebar_menu_dashboards_collapse"
            );
            menuWrapper = document.querySelector("#kt_app_sidebar_menu_wrapper");

            if (sidebar === null) {
                return;
            }

            if (toggle) {
                handleToggle();
            }

            if (menuWrapper) {
                handleMenuScroll();
            }

            if (menuDashboardsCollapse) {
                handleShowMore();
            }
        }
    };
})();

// On document ready
KtUtil.onDOMContentLoaded(function () {
    KTAppSidebar.init();
});

("use strict");

// Class definition
var KTLayoutToolbar = (function () {
    // Private variables
    var toolbar;

    // Private functions
    var initForm = function () {
        var rangeSlider = document.querySelector("#kt_app_toolbar_slider");
        var rangeSliderValueElement = document.querySelector(
            "#kt_app_toolbar_slider_value"
        );

        if (!rangeSlider) {
            return;
        }

        noUiSlider.create(rangeSlider, {
            start: [5],
            connect: [true, false],
            step: 1,
            format: wNumb({
                decimals: 1
            }),
            range: {
                min: [1],
                max: [10]
            }
        });

        rangeSlider.noUiSlider.on("update", function (values, handle) {
            rangeSliderValueElement.innerHTML = values[handle];
        });

        var handle = rangeSlider.querySelector(".noUi-handle");

        handle.setAttribute("tabindex", 0);

        handle.addEventListener("click", function () {
            this.focus();
        });

        handle.addEventListener("keydown", function (event) {
            var value = Number(rangeSlider.noUiSlider.get());

            switch (event.which) {
                case 37:
                    rangeSlider.noUiSlider.set(value - 1);
                    break;
                case 39:
                    rangeSlider.noUiSlider.set(value + 1);
                    break;
            }
        });
    };

    // Public methods
    return {
        init: function () {
            // Elements
            toolbar = document.querySelector("#kt_app_toolbar");

            if (!toolbar) {
                return;
            }

            initForm();
        }
    };
})();

// On document ready
KtUtil.onDOMContentLoaded(function () {
    KTLayoutToolbar.init();
});

/* user search */

// Class definition
var KTModalUserSearch = (function () {
    // Private variables
    var element;
    var suggestionsElement;
    var resultsElement;
    var wrapperElement;
    var emptyElement;
    var searchObject;

    // Private functions
    var processs = function (search) {
        var timeout = setTimeout(function () {
            var number = KtUtil.getRandomInt(1, 3);

            // Hide recently viewed
            suggestionsElement.classList.add("d-none");

            if (number === 3) {
                // Hide results
                resultsElement.classList.add("d-none");
                // Show empty message
                emptyElement.classList.remove("d-none");
            } else {
                // Show results
                resultsElement.classList.remove("d-none");
                // Hide empty message
                emptyElement.classList.add("d-none");
            }

            // Complete search
            search.complete();
        }, 1500);
    };

    var clear = function (search) {
        // Show recently viewed
        suggestionsElement.classList.remove("d-none");
        // Hide results
        resultsElement.classList.add("d-none");
        // Hide empty message
        emptyElement.classList.add("d-none");
    };

    // Public methods
    return {
        init: function () {
            // Elements
            element = document.querySelector("#kt_modal_users_search_handler");

            if (!element) {
                return;
            }

            wrapperElement = element.querySelector("[data-kt-search-element=\"wrapper\"]");
            suggestionsElement = element.querySelector(
                "[data-kt-search-element=\"suggestions\"]"
            );
            resultsElement = element.querySelector("[data-kt-search-element=\"results\"]");
            emptyElement = element.querySelector("[data-kt-search-element=\"empty\"]");

            // Initialize search handler
            searchObject = new KTSearch(element);

            // Search handler
            searchObject.on("kt.search.process", processs);

            // Clear handler
            searchObject.on("kt.search.clear", clear);
        }
    };
})();

// On document ready
KtUtil.onDOMContentLoaded(function () {
    KTModalUserSearch.init();
});

// Class definition
/* chat */
var KTAppChat = (function () {
    // Private functions
    var handeSend = function (element) {
        if (!element) {
            return;
        }

        // Handle send
        KtUtil.on(element, "[data-kt-element=\"input\"]", "keydown", function (e) {
            if (e.keyCode == 13) {
                handeMessaging(element);
                e.preventDefault();

                return false;
            }
        });

        KtUtil.on(element, "[data-kt-element=\"send\"]", "click", function (e) {
            handeMessaging(element);
        });
    };

    var handeMessaging = function (element) {
        var messages = element.querySelector("[data-kt-element=\"messages\"]");
        var input = element.querySelector("[data-kt-element=\"input\"]");

        if (input.value.length === 0) {
            return;
        }

        var messageOutTemplate = messages.querySelector(
            "[data-kt-element=\"template-out\"]"
        );
        var messageInTemplate = messages.querySelector("[data-kt-element=\"template-in\"]");
        var message;

        // Show example outgoing message
        message = messageOutTemplate.cloneNode(true);
        message.classList.remove("d-none");
        message.querySelector("[data-kt-element=\"message-text\"]").innerText = input.value;
        input.value = "";
        messages.appendChild(message);
        messages.scrollTop = messages.scrollHeight;

        setTimeout(function () {
            // Show example incoming message
            message = messageInTemplate.cloneNode(true);
            message.classList.remove("d-none");
            message.querySelector("[data-kt-element=\"message-text\"]").innerText =
                "Thank you for your awesome support!";
            messages.appendChild(message);
            messages.scrollTop = messages.scrollHeight;
        }, 2000);
    };

    // Public methods
    return {
        init: function (element) {
            handeSend(element);
        }
    };
})();

// On document ready
KtUtil.onDOMContentLoaded(function () {
    // Init inline chat messenger
    KTAppChat.init(document.querySelector("#kt_chat_messenger"));

    // Init drawer chat messenger
    KTAppChat.init(document.querySelector("#kt_drawer_chat_messenger"));
});

// Class definition
/* create app .js */

var KTCreateApp = (function () {
    // Elements
    var modal;
    var modalEl;

    var stepper;
    var form;
    var formSubmitButton;
    var formContinueButton;

    // Variables
    var stepperObj;
    var validations = [];

    // Private Functions
    var initStepper = function () {
        // Initialize Stepper
        stepperObj = new KTStepper(stepper);

        // Stepper change event(handle hiding submit button for the last step)
        stepperObj.on("kt.stepper.changed", function (stepper) {
            if (stepperObj.getCurrentStepIndex() === 4) {
                formSubmitButton.classList.remove("d-none");
                formSubmitButton.classList.add("d-inline-block");
                formContinueButton.classList.add("d-none");
            } else if (stepperObj.getCurrentStepIndex() === 5) {
                formSubmitButton.classList.add("d-none");
                formContinueButton.classList.add("d-none");
            } else {
                formSubmitButton.classList.remove("d-inline-block");
                formSubmitButton.classList.remove("d-none");
                formContinueButton.classList.remove("d-none");
            }
        });

        // Validation before going to next page
        stepperObj.on("kt.stepper.next", function (stepper) {
            console.log("stepper.next");

            // Validate form before change stepper step
            var validator = validations[stepper.getCurrentStepIndex() - 1]; // get validator for currnt step

            if (validator) {
                validator.validate().then(function (status) {
                    console.log("validated!");

                    if (status == "Valid") {
                        stepper.goNext();

                        //KTUtil.scrollTop();
                    } else {
                        // Show error message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        Swal.fire({
                            text:
                                "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-light"
                            }
                        }).then(function () {
                            //KTUtil.scrollTop();
                        });
                    }
                });
            } else {
                stepper.goNext();

                KtUtil.scrollTop();
            }
        });

        // Prev event
        stepperObj.on("kt.stepper.previous", function (stepper) {
            console.log("stepper.previous");

            stepper.goPrevious();
            KtUtil.scrollTop();
        });

        formSubmitButton.addEventListener("click", function (e) {
            // Validate form before change stepper step
            var validator = validations[3]; // get validator for last form

            validator.validate().then(function (status) {
                console.log("validated!");

                if (status == "Valid") {
                    // Prevent default button action
                    e.preventDefault();

                    // Disable button to avoid multiple click
                    formSubmitButton.disabled = true;

                    // Show loading indication
                    formSubmitButton.setAttribute("data-kt-indicator", "on");

                    // Simulate form submission
                    setTimeout(function () {
                        // Hide loading indication
                        formSubmitButton.removeAttribute("data-kt-indicator");

                        // Enable button
                        formSubmitButton.disabled = false;

                        stepperObj.goNext();
                        //KTUtil.scrollTop();
                    }, 2000);
                } else {
                    Swal.fire({
                        text:
                            "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-light"
                        }
                    }).then(function () {
                        KtUtil.scrollTop();
                    });
                }
            });
        });
    };

    // Init form inputs
    var initForm = function () {
        // Expiry month. For more info, plase visit the official plugin site: https://select2.org/
        $(form.querySelector("[name=\"card_expiry_month\"]")).on("change", function () {
            // Revalidate the field when an option is chosen
            validations[3].revalidateField("card_expiry_month");
        });

        // Expiry year. For more info, plase visit the official plugin site: https://select2.org/
        $(form.querySelector("[name=\"card_expiry_year\"]")).on("change", function () {
            // Revalidate the field when an option is chosen
            validations[3].revalidateField("card_expiry_year");
        });
    };

    /*var initValidation = function () {
          // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
          // Step 1
          validations.push(FormValidation.formValidation(
              form, {
                  fields: {
                      name: {
                          validators: {
                              notEmpty: {
                                  message: 'App name is required'
                              }
                          }
                      },
                      category: {
                          validators: {
                              notEmpty: {
                                  message: 'Category is required'
                              }
                          }
                      }
                  },
                  plugins: {
                      trigger: new FormValidation.plugins.Trigger(),
                      bootstrap: new FormValidation.plugins.Bootstrap5({
                          rowSelector: '.fv-row',
                          eleInvalidClass: '',
                          eleValidClass: ''
                      })
                  }
              }
          ));

          // Step 2
          validations.push(FormValidation.formValidation(
              form, {
                  fields: {
                      framework: {
                          validators: {
                              notEmpty: {
                                  message: 'Framework is required'
                              }
                          }
                      }
                  },
                  plugins: {
                      trigger: new FormValidation.plugins.Trigger(),
                      // Bootstrap Framework Integration
                      bootstrap: new FormValidation.plugins.Bootstrap5({
                          rowSelector: '.fv-row',
                          eleInvalidClass: '',
                          eleValidClass: ''
                      })
                  }
              }
          ));

          // Step 3
          validations.push(FormValidation.formValidation(
              form, {
                  fields: {
                      dbname: {
                          validators: {
                              notEmpty: {
                                  message: 'Database name is required'
                              }
                          }
                      },
                      dbengine: {
                          validators: {
                              notEmpty: {
                                  message: 'Database engine is required'
                              }
                          }
                      }
                  },
                  plugins: {
                      trigger: new FormValidation.plugins.Trigger(),
                      // Bootstrap Framework Integration
                      bootstrap: new FormValidation.plugins.Bootstrap5({
                          rowSelector: '.fv-row',
                          eleInvalidClass: '',
                          eleValidClass: ''
                      })
                  }
              }
          ));

          // Step 4
          validations.push(FormValidation.formValidation(
              form, {
                  fields: {
                      'card_name': {
                          validators: {
                              notEmpty: {
                                  message: 'Name on card is required'
                              }
                          }
                      },
                      'card_number': {
                          validators: {
                              notEmpty: {
                                  message: 'Card member is required'
                              },
                              creditCard: {
                                  message: 'Card number is not valid'
                              }
                          }
                      },
                      'card_expiry_month': {
                          validators: {
                              notEmpty: {
                                  message: 'Month is required'
                              }
                          }
                      },
                      'card_expiry_year': {
                          validators: {
                              notEmpty: {
                                  message: 'Year is required'
                              }
                          }
                      },
                      'card_cvv': {
                          validators: {
                              notEmpty: {
                                  message: 'CVV is required'
                              },
                              digits: {
                                  message: 'CVV must contain only digits'
                              },
                              stringLength: {
                                  min: 3,
                                  max: 4,
                                  message: 'CVV must contain 3 to 4 digits only'
                              }
                          }
                      }
                  },

                  plugins: {
                      trigger: new FormValidation.plugins.Trigger(),
                      // Bootstrap Framework Integration
                      bootstrap: new FormValidation.plugins.Bootstrap5({
                          rowSelector: '.fv-row',
                          eleInvalidClass: '',
                          eleValidClass: ''
                      })
                  }
              }
          ));
      }
      */

    return {
        // Public Functions
        init: function () {
            // Elements
            modalEl = document.querySelector("#kt_modal_create_app");

            if (!modalEl) {
                return;
            }

            modal = new bootstrap.Modal(modalEl);

            stepper = document.querySelector("#kt_modal_create_app_stepper");
            form = document.querySelector("#kt_modal_create_app_form");
            formSubmitButton = stepper.querySelector("[data-kt-stepper-action=\"submit\"]");
            formContinueButton = stepper.querySelector("[data-kt-stepper-action=\"next\"]");

            initStepper();
            initForm();
            //initValidation();
        }
    };
})();

// On document ready
KtUtil.onDOMContentLoaded(function () {
    KTCreateApp.init();
});

/* widgets.js */

// Class definition
var KTWidgets = (function () {
    // Statistics widgets
    var initStatisticsWidget3 = function () {
        var charts = document.querySelectorAll(".statistics-widget-3-chart");

        [].slice.call(charts).map(function (element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");

            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var baseColor = KtUtil.getCssVariableValue("--bs-" + color);
            var lightColor = KtUtil.getCssVariableValue("--bs-" + color + "-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 45, 32, 70, 40]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 0.3
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: "#E4E6EF",
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 80,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [baseColor],
                markers: {
                    colors: [baseColor],
                    strokeColor: [lightColor],
                    strokeWidth: 3
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initStatisticsWidget4 = function () {
        var charts = document.querySelectorAll(".statistics-widget-4-chart");

        [].slice.call(charts).map(function (element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");

            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var baseColor = KtUtil.getCssVariableValue("--bs-" + color);
            var lightColor = KtUtil.getCssVariableValue("--bs-" + color + "-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [40, 40, 30, 30, 35, 35, 50]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 0.3
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: "#E4E6EF",
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 60,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [baseColor],
                markers: {
                    colors: [baseColor],
                    strokeColor: [lightColor],
                    strokeWidth: 3
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    // Charts widgets
    var initChartsWidget1 = function () {
        var element = document.getElementById("kt_charts_widget_1_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));
            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
            var baseColor = KtUtil.getCssVariableValue("--bs-primary");
            var secondaryColor = KtUtil.getCssVariableValue("--bs-gray-300");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [44, 55, 57, 56, 61, 58]
                    },
                    {
                        name: "Revenue",
                        data: [76, 85, 101, 98, 87, 105]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "bar",
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ["30%"],
                        borderRadius: [6]
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [baseColor, secondaryColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    var initChartsWidget2 = function () {
        var element = document.getElementById("kt_charts_widget_2_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));
            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
            var baseColor = KtUtil.getCssVariableValue("--bs-warning");
            var secondaryColor = KtUtil.getCssVariableValue("--bs-gray-300");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [44, 55, 57, 56, 61, 58]
                    },
                    {
                        name: "Revenue",
                        data: [76, 85, 101, 98, 87, 105]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "bar",
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ["30%"],
                        borderRadius: 4
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [baseColor, secondaryColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    var initChartsWidget3 = function () {
        var element = document.getElementById("kt_charts_widget_3_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));
            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
            var baseColor = KtUtil.getCssVariableValue("--bs-info");
            var lightColor = KtUtil.getCssVariableValue("--bs-info-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 40, 40, 90, 90, 70, 70]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        position: "front",
                        stroke: {
                            color: baseColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [lightColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    strokeColor: baseColor,
                    strokeWidth: 3
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    var initChartsWidget4 = function () {
        var element = document.getElementById("kt_charts_widget_4_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));
            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");

            var baseColor = KtUtil.getCssVariableValue("--bs-success");
            var baseLightColor = KtUtil.getCssVariableValue("--bs-success-light");
            var secondaryColor = KtUtil.getCssVariableValue("--bs-warning");
            var secondaryLightColor = KtUtil.getCssVariableValue("--bs-warning-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [60, 50, 80, 40, 100, 60]
                    },
                    {
                        name: "Revenue",
                        data: [70, 60, 110, 40, 50, 70]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth"
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        position: "front",
                        stroke: {
                            color: labelColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [baseColor, secondaryColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    colors: [baseLightColor, secondaryLightColor],
                    strokeColor: [baseLightColor, secondaryLightColor],
                    strokeWidth: 3
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    var initChartsWidget5 = function () {
        var element = document.getElementById("kt_charts_widget_5_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));
            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");

            var baseColor = KtUtil.getCssVariableValue("--bs-primary");
            var secondaryColor = KtUtil.getCssVariableValue("--bs-info");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [40, 50, 65, 70, 50, 30]
                    },
                    {
                        name: "Revenue",
                        data: [-30, -40, -55, -60, -40, -20]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "bar",
                    stacked: true,
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ["12%"],
                        borderRadius: [6, 6]
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: -80,
                    max: 80,
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [baseColor, secondaryColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    var initChartsWidget6 = function () {
        var element = document.getElementById("kt_charts_widget_6_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));
            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");

            var baseColor = KtUtil.getCssVariableValue("--bs-primary");
            var baseLightColor = KtUtil.getCssVariableValue("--bs-primary-light");
            var secondaryColor = KtUtil.getCssVariableValue("--bs-info");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        type: "bar",
                        stacked: true,
                        data: [40, 50, 65, 70, 50, 30]
                    },
                    {
                        name: "Revenue",
                        type: "bar",
                        stacked: true,
                        data: [20, 20, 25, 30, 30, 20]
                    },
                    {
                        name: "Expenses",
                        type: "area",
                        data: [50, 80, 60, 90, 50, 70]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    stacked: true,
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        stacked: true,
                        horizontal: false,
                        borderRadius: 4,
                        columnWidth: ["12%"]
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 2,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    max: 120,
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [baseColor, secondaryColor, baseLightColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    var initChartsWidget7 = function () {
        var element = document.getElementById("kt_charts_widget_7_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));

            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
            var strokeColor = KtUtil.getCssVariableValue("--bs-gray-300");

            var color1 = KtUtil.getCssVariableValue("--bs-warning");
            var color1Light = KtUtil.getCssVariableValue("--bs-warning-light");

            var color2 = KtUtil.getCssVariableValue("--bs-success");
            var color2Light = KtUtil.getCssVariableValue("--bs-success-light");

            var color3 = KtUtil.getCssVariableValue("--bs-primary");
            var color3Light = KtUtil.getCssVariableValue("--bs-primary-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 30, 50, 50, 35, 35]
                    },
                    {
                        name: "Revenue",
                        data: [55, 20, 20, 20, 70, 70]
                    },
                    {
                        name: "Expenses",
                        data: [60, 60, 40, 40, 30, 30]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 2,
                    colors: [color1, "transparent", "transparent"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [color1, color2, color3],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    colors: [color1Light, color2Light, color3Light],
                    strokeColor: [color1, color2, color3],
                    strokeWidth: 3
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    var initChartsWidget8 = function () {
        var element = document.getElementById("kt_charts_widget_8_chart");

        if (!element) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function () {
            var height = parseInt(KtUtil.css(element, "height"));

            var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
            var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
            var strokeColor = KtUtil.getCssVariableValue("--bs-gray-300");

            var color1 = KtUtil.getCssVariableValue("--bs-warning");
            var color1Light = KtUtil.getCssVariableValue("--bs-warning-light");

            var color2 = KtUtil.getCssVariableValue("--bs-success");
            var color2Light = KtUtil.getCssVariableValue("--bs-success-light");

            var color3 = KtUtil.getCssVariableValue("--bs-primary");
            var color3Light = KtUtil.getCssVariableValue("--bs-primary-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 30, 50, 50, 35, 35]
                    },
                    {
                        name: "Revenue",
                        data: [55, 20, 20, 20, 70, 70]
                    },
                    {
                        name: "Expenses",
                        data: [60, 60, 40, 40, 30, 30]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 2,
                    colors: [color1, color2, color3]
                },
                xaxis: {
                    x: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0
                    },
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: {
                        left: 0,
                        right: 0
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [color1Light, color2Light, color3Light],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 0,
                        right: 0
                    }
                },
                markers: {
                    colors: [color1, color2, color3],
                    strokeColor: [color1, color2, color3],
                    strokeWidth: 3
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    // Mixed widgets
    var initMixedWidget2 = function () {
        var charts = document.querySelectorAll(".mixed-widget-2-chart");

        var color;
        var strokeColor;
        var height;
        var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
        var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
        var options;
        var chart;

        [].slice.call(charts).map(function (element) {
            height = parseInt(KtUtil.css(element, "height"));
            color = KtUtil.getCssVariableValue(
                "--bs-" + element.getAttribute("data-kt-color")
            );
            strokeColor = KtUtil.colorDarken(color, 15);

            options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 45, 32, 70, 40, 40, 40]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    },
                    dropShadow: {
                        enabled: true,
                        enabledOnSeries: undefined,
                        top: 5,
                        left: 0,
                        blur: 3,
                        color: strokeColor,
                        opacity: 0.5
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 0
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [strokeColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: borderColor,
                            width: 1,
                            dashArray: 3
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 80,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    },
                    marker: {
                        show: false
                    }
                },
                colors: ["transparent"],
                markers: {
                    colors: [color],
                    strokeColor: [strokeColor],
                    strokeWidth: 3
                }
            };

            chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget3 = function () {
        var charts = document.querySelectorAll(".mixed-widget-3-chart");

        [].slice.call(charts).map(function (element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");

            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var strokeColor = KtUtil.getCssVariableValue("--bs-" + "gray-300");
            var baseColor = KtUtil.getCssVariableValue("--bs-" + color);
            var lightColor = KtUtil.getCssVariableValue("--bs-" + color + "-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 25, 45, 30, 55, 55]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 60,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [lightColor],
                markers: {
                    colors: [lightColor],
                    strokeColor: [baseColor],
                    strokeWidth: 3
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget4 = function () {
        var charts = document.querySelectorAll(".mixed-widget-4-chart");

        [].slice.call(charts).map(function (element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");

            var baseColor = KtUtil.getCssVariableValue("--bs-" + color);
            var lightColor = KtUtil.getCssVariableValue("--bs-" + color + "-light");
            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-700");

            var options = {
                series: [74],
                chart: {
                    fontFamily: "inherit",
                    height: height,
                    type: "radialBar"
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: "65%"
                        },
                        dataLabels: {
                            showOn: "always",
                            name: {
                                show: false,
                                fontWeight: "700"
                            },
                            value: {
                                color: labelColor,
                                fontSize: "30px",
                                fontWeight: "700",
                                offsetY: 12,
                                show: true,
                                formatter: function (val) {
                                    return val + "%";
                                }
                            }
                        },
                        track: {
                            background: lightColor,
                            strokeWidth: "100%"
                        }
                    }
                },
                colors: [baseColor],
                stroke: {
                    lineCap: "round"
                },
                labels: ["Progress"]
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget5 = function () {
        var charts = document.querySelectorAll(".mixed-widget-5-chart");

        var initChart = function (chart, element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");
            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var strokeColor = KtUtil.getCssVariableValue("--bs-" + "gray-300");
            var baseColor = KtUtil.getCssVariableValue("--bs-" + color);
            var lightColor = KtUtil.getCssVariableValue("--bs-" + color + "-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 30, 60, 25, 25, 40]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                fill1: {
                    type: "gradient",
                    opacity: 1,
                    gradient: {
                        type: "vertical",
                        shadeIntensity: 0.5,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 0.375,
                        stops: [25, 50, 100],
                        colorStops: []
                    }
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 65,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [lightColor],
                markers: {
                    colors: [lightColor],
                    strokeColor: [baseColor],
                    strokeWidth: 3
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        };

        [].slice.call(charts).map(function (element) {
            var chart = {
                self: null,
                rendered: false
            };

            initChart(chart, element);

            // Update chart on theme mode change
            KTThemeMode.on("kt.thememode.change", function () {
                if (chart.rendered) {
                    chart.self.destroy();
                }

                initChart(chart, element);
            });
        });
    };

    var initMixedWidget6 = function () {
        var charts = document.querySelectorAll(".mixed-widget-6-chart");

        [].slice.call(charts).map(function (element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");

            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var strokeColor = KtUtil.getCssVariableValue("--bs-" + "gray-300");
            var baseColor = KtUtil.getCssVariableValue("--bs-" + color);
            var lightColor = KtUtil.getCssVariableValue("--bs-" + color + "-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 25, 45, 30, 55, 55]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 60,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [lightColor],
                markers: {
                    colors: [lightColor],
                    strokeColor: [baseColor],
                    strokeWidth: 3
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget7 = function () {
        var charts = document.querySelectorAll(".mixed-widget-7-chart");

        [].slice.call(charts).map(function (element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");

            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var strokeColor = KtUtil.getCssVariableValue("--bs-" + "gray-300");
            var baseColor = KtUtil.getCssVariableValue("--bs-" + color);
            var lightColor = KtUtil.getCssVariableValue("--bs-" + color + "-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [15, 25, 15, 40, 20, 50]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [baseColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 60,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [lightColor],
                markers: {
                    colors: [lightColor],
                    strokeColor: [baseColor],
                    strokeWidth: 3
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget10 = function () {
        var charts = document.querySelectorAll(".mixed-widget-10-chart");

        var color;
        var height;
        var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
        var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
        var baseLightColor;
        var secondaryColor = KtUtil.getCssVariableValue("--bs-gray-300");
        var baseColor;
        var options;
        var chart;

        [].slice.call(charts).map(function (element) {
            color = element.getAttribute("data-kt-color");
            height = parseInt(KtUtil.css(element, "height"));
            baseColor = KtUtil.getCssVariableValue("--bs-" + color);

            options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [50, 60, 70, 80, 60, 50, 70, 60]
                    },
                    {
                        name: "Revenue",
                        data: [50, 60, 70, 80, 60, 50, 70, 60]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "bar",
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ["50%"],
                        borderRadius: 4
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                fill: {
                    type: "solid"
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " revenue";
                        }
                    }
                },
                colors: [baseColor, secondaryColor],
                grid: {
                    padding: {
                        top: 10
                    },
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget12 = function () {
        var charts = document.querySelectorAll(".mixed-widget-12-chart");

        var color;
        var strokeColor;
        var height;
        var labelColor = KtUtil.getCssVariableValue("--bs-gray-500");
        var borderColor = KtUtil.getCssVariableValue("--bs-gray-200");
        var options;
        var chart;

        [].slice.call(charts).map(function (element) {
            height = parseInt(KtUtil.css(element, "height"));

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [35, 65, 75, 55, 45, 60, 55]
                    },
                    {
                        name: "Revenue",
                        data: [40, 70, 80, 60, 50, 65, 60]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "bar",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ["30%"],
                        borderRadius: 2
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ["transparent"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                fill: {
                    type: ["solid", "solid"],
                    opacity: [0.25, 1]
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    },
                    marker: {
                        show: false
                    }
                },
                colors: ["#ffffff", "#ffffff"],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        left: 20,
                        right: 20
                    }
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget13 = function () {
        var height;
        var charts = document.querySelectorAll(".mixed-widget-13-chart");

        [].slice.call(charts).map(function (element) {
            height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var strokeColor = KtUtil.getCssVariableValue("--bs-" + "gray-300");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [15, 25, 15, 40, 20, 50]
                    }
                ],
                grid: {
                    show: false,
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 0,
                        right: 0
                    }
                },
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        opacityFrom: 0.4,
                        opacityTo: 0,
                        stops: [20, 120, 120, 120]
                    }
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: ["#FFFFFF"]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 60,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: ["#ffffff"],
                markers: {
                    colors: [labelColor],
                    strokeColor: [strokeColor],
                    strokeWidth: 3
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget14 = function () {
        var charts = document.querySelectorAll(".mixed-widget-14-chart");
        var options;
        var chart;
        var height;

        [].slice.call(charts).map(function (element) {
            height = parseInt(KtUtil.css(element, "height"));
            var labelColor = KtUtil.getCssVariableValue("--bs-gray-800");

            options = {
                series: [
                    {
                        name: "Inflation",
                        data: [1, 2.1, 1, 2.1, 4.1, 6.1, 4.1, 4.1, 2.1, 4.1, 2.1, 3.1, 1, 1, 2.1]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    height: height,
                    type: "bar",
                    toolbar: {
                        show: false
                    }
                },
                grid: {
                    show: false,
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 0,
                        right: 0
                    }
                },
                colors: ["#ffffff"],
                plotOptions: {
                    bar: {
                        borderRadius: 2.5,
                        dataLabels: {
                            position: "top" // top, center, bottom
                        },
                        columnWidth: "20%"
                    }
                },
                dataLabels: {
                    enabled: false,
                    formatter: function (val) {
                        return val + "%";
                    },
                    offsetY: -20,
                    style: {
                        fontSize: "12px",
                        colors: ["#304758"]
                    }
                },
                xaxis: {
                    labels: {
                        show: false
                    },
                    categories: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                        "Jan",
                        "Feb",
                        "Mar"
                    ],
                    position: "top",
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    crosshairs: {
                        show: false
                    },
                    tooltip: {
                        enabled: false
                    }
                },
                yaxis: {
                    show: false,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false,
                        background: labelColor
                    },
                    labels: {
                        show: false,
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            };

            chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget16 = function () {
        var element = document.getElementById("kt_charts_mixed_widget_16_chart");
        var height = parseInt(KtUtil.css(element, "height"));

        if (!element) {
            return;
        }

        var options = {
            labels: ["Total Members"],
            series: [74],
            chart: {
                fontFamily: "inherit",
                height: height,
                type: "radialBar",
                offsetY: 0
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,

                    hollow: {
                        margin: 0,
                        size: "70%"
                    },
                    dataLabels: {
                        showOn: "always",
                        name: {
                            show: true,
                            fontSize: "13px",
                            fontWeight: "700",
                            offsetY: -5,
                            color: KtUtil.getCssVariableValue("--bs-gray-500")
                        },
                        value: {
                            color: KtUtil.getCssVariableValue("--bs-gray-700"),
                            fontSize: "30px",
                            fontWeight: "700",
                            offsetY: -40,
                            show: true
                        }
                    },
                    track: {
                        background: KtUtil.getCssVariableValue("--bs-primary-light"),
                        strokeWidth: "100%"
                    }
                }
            },
            colors: [KtUtil.getCssVariableValue("--bs-primary")],
            stroke: {
                lineCap: "round"
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    };

    var initMixedWidget17 = function () {
        var charts = document.querySelectorAll(".mixed-widget-17-chart");

        [].slice.call(charts).map(function (element) {
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var color = element.getAttribute("data-kt-chart-color");

            var options = {
                labels: ["Total Orders"],
                series: [75],
                chart: {
                    fontFamily: "inherit",
                    height: height,
                    type: "radialBar",
                    offsetY: 0
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        hollow: {
                            margin: 0,
                            size: "55%"
                        },
                        dataLabels: {
                            showOn: "always",
                            name: {
                                show: true,
                                fontSize: "12px",
                                fontWeight: "700",
                                offsetY: -5,
                                color: KtUtil.getCssVariableValue("--bs-gray-500")
                            },
                            value: {
                                color: KtUtil.getCssVariableValue("--bs-gray-900"),
                                fontSize: "24px",
                                fontWeight: "600",
                                offsetY: -40,
                                show: true,
                                formatter: function (value) {
                                    return "8,346";
                                }
                            }
                        },
                        track: {
                            background: KtUtil.getCssVariableValue("--bs-gray-300"),
                            strokeWidth: "100%"
                        }
                    }
                },
                colors: [KtUtil.getCssVariableValue("--bs-" + color)],
                stroke: {
                    lineCap: "round"
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        });
    };

    var initMixedWidget18 = function () {
        var element = document.getElementById("kt_charts_mixed_widget_18_chart");
        var height = parseInt(KtUtil.css(element, "height"));

        if (!element) {
            return;
        }

        var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
        var strokeColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
        var fillColor =
            KTThemeMode.getMode() === "dark"
                ? KtUtil.getCssVariableValue("--bs-gray-200")
                : "#D6D6E0";

        var options = {
            series: [
                {
                    name: "Net Profit",
                    data: [30, 25, 45, 30, 55, 55]
                }
            ],
            chart: {
                fontFamily: "inherit",
                type: "area",
                height: height,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: "solid",
                opacity: 1
            },
            stroke: {
                curve: "smooth",
                show: true,
                width: 3,
                colors: [strokeColor]
            },
            xaxis: {
                categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: labelColor,
                        fontSize: "12px"
                    }
                },
                crosshairs: {
                    show: false,
                    position: "front",
                    stroke: {
                        color: strokeColor,
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: "12px"
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 60,
                labels: {
                    show: false,
                    style: {
                        colors: labelColor,
                        fontSize: "12px"
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: "none",
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: "none",
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: "none",
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: "12px"
                },
                y: {
                    formatter: function (val) {
                        return "$" + val + " thousands";
                    }
                }
            },
            colors: [fillColor],
            markers: {
                colors: [fillColor],
                strokeColor: [strokeColor],
                strokeWidth: 3
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    };

    var initMixedWidget19 = function () {
        var chart = {
            self: null,
            rendered: false
        };

        function initChart() {
            var element = document.getElementById("kt_charts_mixed_widget_19_chart");
            var height = parseInt(KtUtil.css(element, "height"));

            if (!element) {
                return;
            }

            var labelColor = KtUtil.getCssVariableValue("--bs-" + "gray-800");
            var strokeColor = KtUtil.getCssVariableValue("--bs-" + "info");
            var fillColor = KtUtil.getCssVariableValue("--bs-info-light");

            var options = {
                series: [
                    {
                        name: "Net Profit",
                        data: [30, 25, 45, 30, 55, 55]
                    }
                ],
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {},
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "solid",
                    opacity: 1
                },
                stroke: {
                    curve: "smooth",
                    show: true,
                    width: 3,
                    colors: [strokeColor]
                },
                xaxis: {
                    categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: "front",
                        stroke: {
                            color: strokeColor,
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 60,
                    labels: {
                        show: false,
                        style: {
                            colors: labelColor,
                            fontSize: "12px"
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: "none",
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: "none",
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: "12px"
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands";
                        }
                    }
                },
                colors: [fillColor],
                markers: {
                    colors: [fillColor],
                    strokeColor: [strokeColor],
                    strokeWidth: 3
                }
            };

            chart.self = new ApexCharts(element, options);

            // Set timeout to properly get the parent elements width
            setTimeout(function () {
                chart.self.render();
                chart.rendered = true;
            }, 200);
        }

        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    };

    // Feeds Widgets
    var initFeedWidget1 = function () {
        var formEl = document.querySelector("#kt_forms_widget_1_form");
        var editorId = "kt_forms_widget_1_editor";

        if (!formEl) {
            return;
        }

        // init editor
        var options = {
            modules: {
                toolbar: {
                    container: "#kt_forms_widget_1_editor_toolbar"
                }
            },
            placeholder: "What is on your mind ?",
            theme: "snow"
        };

        if (!formEl) {
            return;
        }

        // Init editor
        // var editorObj = new Quill("#" + editorId, options);
    };

    var initFeedsWidget4 = function () {
        var btn = document.querySelector("#kt_widget_5_load_more_btn");
        var widget5 = document.querySelector("#kt_widget_5");

        if (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                btn.setAttribute("data-kt-indicator", "on");

                setTimeout(function () {
                    btn.removeAttribute("data-kt-indicator");
                    widget5.classList.remove("d-none");
                    btn.classList.add("d-none");

                    KtUtil.scrollTo(widget5, 200);
                }, 2000);
            });
        }
    };

    // Calendar
    var initCalendarWidget1 = function () {
        if (
            typeof FullCalendar === "undefined" ||
            !document.querySelector("#kt_calendar_widget_1")
        ) {
            return;
        }

        let todayDate = moment().startOf("day");
        let YM = todayDate.format("YYYY-MM");
        let YESTERDAY = todayDate.clone().subtract(1, "day").format("YYYY-MM-DD");
        let TODAY = todayDate.format("YYYY-MM-DD");
        let TOMORROW = todayDate.clone().add(1, "day").format("YYYY-MM-DD");

        let calendarEl = document.getElementById("kt_calendar_widget_1");
        let calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
            },

            height: 800,
            contentHeight: 780,
            aspectRatio: 3, // see: https://fullcalendar.io/docs/aspectRatio

            nowIndicator: true,
            now: TODAY + "T09:25:00", // just for demo

            views: {
                dayGridMonth: {
                    buttonText: "month"
                },
                timeGridWeek: {
                    buttonText: "week"
                },
                timeGridDay: {
                    buttonText: "day"
                }
            },

            initialView: "dayGridMonth",
            initialDate: TODAY,

            editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            navLinks: true,
            events: [
                {
                    title: "All Day Event",
                    start: YM + "-01",
                    description: "Toto lorem ipsum dolor sit incid idunt ut",
                    className: "fc-event-danger fc-event-solid-warning"
                },
                {
                    title: "Reporting",
                    start: YM + "-14T13:30:00",
                    description: "Lorem ipsum dolor incid idunt ut labore",
                    end: YM + "-14",
                    className: "fc-event-success"
                },
                {
                    title: "Company Trip",
                    start: YM + "-02",
                    description: "Lorem ipsum dolor sit tempor incid",
                    end: YM + "-03",
                    className: "fc-event-primary"
                },
                {
                    title: "ICT Expo 2017 - Product Release",
                    start: YM + "-03",
                    description: "Lorem ipsum dolor sit tempor inci",
                    end: YM + "-05",
                    className: "fc-event-light fc-event-solid-primary"
                },
                {
                    title: "Dinner",
                    start: YM + "-12",
                    description: "Lorem ipsum dolor sit amet, conse ctetur",
                    end: YM + "-10"
                },
                {
                    id: 999,
                    title: "Repeating Event",
                    start: YM + "-09T16:00:00",
                    description: "Lorem ipsum dolor sit ncididunt ut labore",
                    className: "fc-event-danger"
                },
                {
                    id: 1000,
                    title: "Repeating Event",
                    description: "Lorem ipsum dolor sit amet, labore",
                    start: YM + "-16T16:00:00"
                },
                {
                    title: "Conference",
                    start: YESTERDAY,
                    end: TOMORROW,
                    description: "Lorem ipsum dolor eius mod tempor labore",
                    className: "fc-event-primary"
                },
                {
                    title: "Meeting",
                    start: TODAY + "T10:30:00",
                    end: TODAY + "T12:30:00",
                    description: "Lorem ipsum dolor eiu idunt ut labore"
                },
                {
                    title: "Lunch",
                    start: TODAY + "T12:00:00",
                    className: "fc-event-info",
                    description: "Lorem ipsum dolor sit amet, ut labore"
                },
                {
                    title: "Meeting",
                    start: TODAY + "T14:30:00",
                    className: "fc-event-warning",
                    description: "Lorem ipsum conse ctetur adipi scing"
                },
                {
                    title: "Happy Hour",
                    start: TODAY + "T17:30:00",
                    className: "fc-event-info",
                    description: "Lorem ipsum dolor sit amet, conse ctetur"
                },
                {
                    title: "Dinner",
                    start: TOMORROW + "T05:00:00",
                    className: "fc-event-solid-danger fc-event-light",
                    description: "Lorem ipsum dolor sit ctetur adipi scing"
                },
                {
                    title: "Birthday Party",
                    start: TOMORROW + "T07:00:00",
                    className: "fc-event-primary",
                    description: "Lorem ipsum dolor sit amet, scing"
                },
                {
                    title: "Click for Google",
                    url: "http://google.com/",
                    start: YM + "-28",
                    className: "fc-event-solid-info fc-event-light",
                    description: "Lorem ipsum dolor sit amet, labore"
                }
            ]
        });

        calendar.render();
    };

    // Daterangepicker
    var initDaterangepicker = function () {
        if (!document.querySelector("#kt_dashboard_daterangepicker")) {
            return;
        }

        var picker = $("#kt_dashboard_daterangepicker");
        var start = moment();
        var end = moment();

        function cb(start, end, label) {
            var title = "";
            var range = "";

            if (end - start < 100 || label == "Today") {
                title = "Today:";
                range = start.format("MMM D");
            } else if (label == "Yesterday") {
                title = "Yesterday:";
                range = start.format("MMM D");
            } else {
                range = start.format("MMM D") + " - " + end.format("MMM D");
            }

            $("#kt_dashboard_daterangepicker_date").html(range);
            $("#kt_dashboard_daterangepicker_title").html(title);
        }

        picker.daterangepicker(
            {
                direction: KtUtil.isRTL(),
                startDate: start,
                endDate: end,
                opens: "left",
                applyClass: "btn-primary",
                cancelClass: "btn-light-primary",
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month")
                    ]
                }
            },
            cb
        );

        cb(start, end, "");
    };

    // Dark mode toggler
    var initDarkModeToggle = function () {
        var toggle = document.querySelector("#kt_user_menu_dark_mode_toggle");

        if (toggle) {
            toggle.addEventListener("click", function () {
                window.location.href = this.getAttribute("data-kt-url");
            });
        }
    };

    // Public methods
    return {
        init: function () {
            // Daterangepicker
            initDaterangepicker();

            // Dark Mode
            initDarkModeToggle();

            // Statistics widgets
            initStatisticsWidget3();
            initStatisticsWidget4();

            // Charts widgets
            initChartsWidget1();
            initChartsWidget2();
            initChartsWidget3();
            initChartsWidget4();
            initChartsWidget5();
            initChartsWidget6();
            initChartsWidget7();
            initChartsWidget8();

            // Mixed widgets
            initMixedWidget2();
            initMixedWidget3();
            initMixedWidget4();
            initMixedWidget5();
            initMixedWidget6();
            initMixedWidget7();
            initMixedWidget10();
            initMixedWidget12();
            initMixedWidget13();
            initMixedWidget14();
            initMixedWidget16();
            initMixedWidget17();
            initMixedWidget18();
            initMixedWidget19();

            // Feeds
            initFeedWidget1();
            initFeedsWidget4();

            // Calendar
            initCalendarWidget1();
        }
    };
})();

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    module.exports = KTWidgets;
}

// On document ready
KtUtil.onDOMContentLoaded(function () {
    KTWidgets.init();
});

//
// Global init of core components
//

// Init components
var KTComponents = (function () {
    // Public methods
    return {
        init: function () {
            KTApp.init();
            KTDrawer.init();
            KTMenu.init();
            KTScroll.init();
            KTSticky.init();
            KTSwapper.init();
            KTToggle.init();
            KTScrolltop.init();
            KTDialer.init();
            KTImageInput.init();
            KTPasswordMeter.init();
        }
    };
})();

// On document ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
        KTComponents.init();
    });
} else {
    KTComponents.init();
}

// Init page loader
window.addEventListener("load", function () {
    KTApp.hidePageLoading();
});

// Declare KTApp for Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
    window.KTComponents = module.exports = KTComponents;
}
