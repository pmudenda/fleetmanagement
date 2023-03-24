//
// Global init of core components
//

// Init components
let KTComponents = function () {
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
    }
}();

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
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    window.KTComponents = module.exports = KTComponents;
}
"use strict";

// Class definition
let KTApp = function () {
    let initialized = false;
    let select2FocusFixInitialized = false;
    let countUpInitialized = false;

    const createBootstrapTooltip = function (el, options) {
        if (el.getAttribute("data-kt-initialized") === "1") {
            return;
        }

        const delay = {};

        // Handle delay options
        if (el.hasAttribute('data-bs-delay-hide')) {
            delay['hide'] = el.getAttribute('data-bs-delay-hide');
        }

        if (el.hasAttribute('data-bs-delay-show')) {
            delay['show'] = el.getAttribute('data-bs-delay-show');
        }

        if (delay) {
            options['delay'] = delay;
        }

        // Check dismiss options
        if (el.hasAttribute('data-bs-dismiss') && el.getAttribute('data-bs-dismiss') == 'click') {
            options['dismiss'] = 'click';
        }

        // Initialize popover
        const tp = new bootstrap.Tooltip(el, options);

        // Handle dismiss
        if (options['dismiss'] && options['dismiss'] === 'click') {
            // Hide popover on element click
            el.addEventListener("click", function (e) {
                tp.hide();
            });
        }

        el.setAttribute("data-kt-initialized", "1");

        return tp;
    };

    const createBootstrapTooltips = function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            createBootstrapTooltip(tooltipTriggerEl, {});
        });
    };

    const createBootstrapPopover = function (el, options) {
        if (el.getAttribute("data-kt-initialized") === "1") {
            return;
        }

        const delay = {};

        // Handle delay options
        if (el.hasAttribute('data-bs-delay-hide')) {
            delay['hide'] = el.getAttribute('data-bs-delay-hide');
        }

        if (el.hasAttribute('data-bs-delay-show')) {
            delay['show'] = el.getAttribute('data-bs-delay-show');
        }

        if (delay) {
            options['delay'] = delay;
        }

        // Handle dismiss option
        if (el.getAttribute('data-bs-dismiss') == 'true') {
            options['dismiss'] = true;
        }

        if (options['dismiss'] === true) {
            options['template'] = '<div class="popover" role="tooltip"><div class="popover-arrow"></div><span class="popover-dismiss btn btn-icon"></span><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }

        // Initialize popover
        const popover = new bootstrap.Popover(el, options);

        // Handle dismiss click
        if (options['dismiss'] === true) {
            const dismissHandler = function (e) {
                popover.hide();
            };

            el.addEventListener('shown.bs.popover', function () {
                const dismissEl = document.getElementById(el.getAttribute('aria-describedby'));
                dismissEl.addEventListener('click', dismissHandler);
            });

            el.addEventListener('hide.bs.popover', function () {
                const dismissEl = document.getElementById(el.getAttribute('aria-describedby'));
                dismissEl.removeEventListener('click', dismissHandler);
            });
        }

        el.setAttribute("data-kt-initialized", "1");

        return popover;
    };

    const createBootstrapPopovers = function () {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));

        const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            createBootstrapPopover(popoverTriggerEl, {});
        });
    };

    const createBootstrapToasts = function () {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        const toastList = toastElList.map(function (toastEl) {
            if (toastEl.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            toastEl.setAttribute("data-kt-initialized", "1");

            return new bootstrap.Toast(toastEl, {})
        });
    };

    const createButtons = function () {
        const buttonsGroup = [].slice.call(document.querySelectorAll('[data-kt-buttons="true"]'));

        buttonsGroup.map(function (group) {
            if (group.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            const selector = group.hasAttribute('data-kt-buttons-target') ? group.getAttribute('data-kt-buttons-target') : '.btn';
            const activeButtons = [].slice.call(group.querySelectorAll(selector));

            // Toggle Handler
            KTUtil.on(group, selector, 'click', function (e) {
                activeButtons.map(function (button) {
                    button.classList.remove('active');
                });

                this.classList.add('active');
            });

            group.setAttribute("data-kt-initialized", "1");
        });
    };

    const createDateRangePickers = function () {
        // Check if jQuery included
        if (typeof jQuery == 'undefined') {
            return;
        }

        // Check if date range picker included
        if (typeof $.fn.daterangepicker === 'undefined') {
            return;
        }

        const elements = [].slice.call(document.querySelectorAll('[data-kt-daterangepicker="true"]'));
        let start = moment().subtract(29, 'days');
        let end = moment();

        elements.map(function (element) {
            if (element.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            const display = element.querySelector('div');
            const attrOpens = element.hasAttribute('data-kt-daterangepicker-opens') ? element.getAttribute('data-kt-daterangepicker-opens') : 'left';
            const range = element.getAttribute('data-kt-daterangepicker-range');

            const cb = function (start, end) {
                const current = moment();

                if (display) {
                    if (current.isSame(start, "day") && current.isSame(end, "day")) {
                        display.innerHTML = start.format('D MMM YYYY');
                    } else {
                        display.innerHTML = start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY');
                    }
                }
            };

            if (range === "today") {
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
    };

    let createSelect2 = function () {
        // Check if jQuery included
        if (typeof jQuery == 'undefined') {
            return;
        }

        // Check if select2 included
        if (typeof $.fn.select2 === 'undefined') {
            console.log('select2 In not included')
            return;
        }

        let elements = [].slice.call(document.querySelectorAll('[data-control="select2"], [data-kt-select2="true"]'));

        elements.map(function (element) {
            if (element.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            let options = {
                dir: document.body.getAttribute('direction')
            };

            if (element.getAttribute('data-hide-search') == 'true') {
                options.minimumResultsForSearch = Infinity;
            }

            $(element).select2(options);

            element.setAttribute("data-kt-initialized", "1");
        });

        /*
         * Hacky fix for a bug in select2 with jQuery 3.6.0's new nested-focus "protection"
         * see: https://github.com/select2/select2/issues/5993
         * see: https://github.com/jquery/jquery/issues/4382
         *
         * TODO: Recheck with the select2 GH issue and remove once this is fixed on their side
         */

        if (select2FocusFixInitialized === false) {
            select2FocusFixInitialized = true;

            $(document).on('select2:open', function (e) {
                const elements = document.querySelectorAll('.select2-container--open .select2-search__field');
                if (elements.length > 0) {
                    elements[elements.length - 1].focus();
                }
            });
        }
    }

    const createAutosize = function () {
        if (typeof autosize === 'undefined') {
            return;
        }

        const inputs = [].slice.call(document.querySelectorAll('[data-kt-autosize="true"]'));

        inputs.map(function (input) {
            if (input.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            autosize(input);

            input.setAttribute("data-kt-initialized", "1");
        });
    };

    const createCountUp = function () {
        if (typeof countUp === 'undefined') {
            return;
        }

        const elements = [].slice.call(document.querySelectorAll('[data-kt-countup="true"]:not(.counted)'));

        elements.map(function (element) {
            if (KTUtil.isInViewport(element) && KTUtil.visible(element)) {
                if (element.getAttribute("data-kt-initialized") === "1") {
                    return;
                }

                const options = {};

                let value = element.getAttribute('data-kt-countup-value');
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

                const count = new countUp.CountUp(element, value, options);

                count.start();

                element.classList.add('counted');

                element.setAttribute("data-kt-initialized", "1");
            }
        });
    };

    const createCountUpTabs = function () {
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
        const tabs = [].slice.call(document.querySelectorAll('[data-kt-countup-tabs="true"][data-bs-toggle="tab"]'));
        tabs.map(function (tab) {
            if (tab.getAttribute("data-kt-initialized") === "1") {
                return;
            }

            tab.addEventListener('shown.bs.tab', createCountUp);

            tab.setAttribute("data-kt-initialized", "1");
        });

        countUpInitialized = true;
    };

    const createTinySliders = function () {
        if (typeof tns === 'undefined') {
            return;
        }

        // Init Slider
        const initSlider = function (el) {
            if (!el) {
                return;
            }

            const tnsOptions = {};

            // Convert string boolean
            const checkBool = function (val) {
                if (val === 'true') {
                    return true;
                }
                if (val === 'false') {
                    return false;
                }
                return val;
            };

            // get extra options via data attributes
            el.getAttributeNames().forEach(function (attrName) {
                // more options; https://github.com/ganlanyuan/tiny-slider#options
                if ((/^data-tns-.*/g).test(attrName)) {
                    let optionName = attrName.replace('data-tns-', '').toLowerCase().replace(/(?:[\s-])\w/g, function (match) {
                        return match.replace('-', '').toUpperCase();
                    });

                    if (attrName === 'data-tns-responsive') {
                        // fix string with a valid json
                        const jsonStr = el.getAttribute(attrName).replace(/(\w+:)|(\w+ :)/g, function (matched) {
                            return '"' + matched.substring(0, matched.length - 1) + '":';
                        });
                        try {
                            // convert json string to object
                            tnsOptions[optionName] = JSON.parse(jsonStr);
                        } catch (e) {
                        }
                    } else {
                        tnsOptions[optionName] = checkBool(el.getAttribute(attrName));
                    }
                }
            });

            const opt = Object.assign({}, {
                container: el,
                slideBy: 'page',
                autoplay: true,
                autoplayButtonOutput: false,
            }, tnsOptions);

            if (el.closest('.tns')) {
                KTUtil.addClass(el.closest('.tns'), 'tns-initiazlied');
            }

            return tns(opt);
        };

        // Sliders
        const elements = Array.prototype.slice.call(document.querySelectorAll('[data-tns="true"]'), 0);

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

    const initSmoothScroll = function () {
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
                    const val = KTUtil.getResponsiveValue(anchor.getAttribute('data-kt-scroll-offset'));

                    return val;
                } else {
                    return 0;
                }
            }
        });
    };

    const initCard = function () {
        // Toggle Handler
        KTUtil.on(document.body, '[data-kt-card-action="remove"]', 'click', function (e) {
            e.preventDefault();

            const card = this.closest('.card');

            if (!card) {
                return;
            }

            const confirmMessage = this.getAttribute("data-kt-card-confirm-message");
            const confirm = this.getAttribute("data-kt-card-confirm") === "true";

            if (confirm) {
                // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
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
                });
            } else {
                card.remove();
            }
        });
    };

    const initModal = function () {
        const elements = Array.prototype.slice.call(document.querySelectorAll("[data-bs-stacked-modal]"));

        if (elements && elements.length > 0) {
            elements.forEach((element) => {
                if (element.getAttribute("data-kt-initialized") === "1") {
                    return;
                }

                element.setAttribute("data-kt-initialized", "1");

                element.addEventListener("click", function (e) {
                    e.preventDefault();

                    const modalEl = document.querySelector(this.getAttribute("data-bs-stacked-modal"));

                    if (modalEl) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                });
            });
        }
    };

    const initCheck = function () {
        if (initialized === true) {
            return;
        }

        // Toggle Handler
        KTUtil.on(document.body, '[data-kt-check="true"]', 'change', function (e) {
            const check = this;
            const targets = document.querySelectorAll(check.getAttribute('data-kt-check-target'));

            KTUtil.each(targets, function (target) {
                if (target.type == 'checkbox') {
                    target.checked = check.checked;
                } else {
                    target.classList.toggle('active');
                }
            });
        });
    };

    const initBootstrapCollapse = function () {
        if (initialized === true) {
            return;
        }

        KTUtil.on(document.body, '.collapsible[data-bs-toggle="collapse"]', 'click', function (e) {
            if (this.classList.contains('collapsed')) {
                this.classList.remove('active');
                this.blur();
            } else {
                this.classList.add('active');
            }

            if (this.hasAttribute('data-kt-toggle-text')) {
                const text = this.getAttribute('data-kt-toggle-text');
                var target = this.querySelector('[data-kt-toggle-text-target="true"]');
                var target = target ? target : this;

                this.setAttribute('data-kt-toggle-text', target.innerText);
                target.innerText = text;
            }
        });
    };

    let initBootstrapRotate = function () {
        if (initialized === true) {
            return;
        }

        KTUtil.on(document.body, '[data-kt-rotate="true"]', 'click', function (e) {
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                this.blur();
            } else {
                this.classList.add('active');
            }
        });
    }

    let initLozad = function () {
        // Check if lozad included
        if (typeof lozad === 'undefined') {
            return;
        }

        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    }

    let _showPageLoading = function () {
        document.body.classList.add('page-loading');
        document.body.setAttribute('data-kt-app-page-loading', "on");
    }

    let _hidePageLoading = function () {
        // CSS3 Transitions only after page load(.page-loading or .app-page-loading class added to body tag and remove with JS on page load)
        document.body.classList.remove('page-loading');
        document.body.removeAttribute('data-kt-app-page-loading');
    }

    return {
        init: function () {
            initLozad();

            initSmoothScroll();

            initCard();

            initModal();

            initCheck();

            initBootstrapCollapse();

            initBootstrapRotate();

            createBootstrapTooltips();

            createBootstrapPopovers();

            createBootstrapToasts();

            createDateRangePickers();

            createButtons();

            createSelect2();

            createCountUp();

            createCountUpTabs();

            createAutosize();

            createTinySliders();

            initialized = true;
        },

        showPageLoading: function () {
            _showPageLoading();
        },

        hidePageLoading: function () {
            _hidePageLoading();
        },

        createBootstrapPopover: function (el, options) {
            return createBootstrapPopover(el, options);
        },

        createBootstrapTooltip: function (el, options) {
            return createBootstrapTooltip(el, options);
        }
    };
}();

// Declare KTApp for Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTApp;
}
"use strict";

// Class definition
const KTBlockUI = function (element, options) {
    //////////////////////////////
    // ** Private variables  ** //
    //////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default options
    const defaultOptions = {
        zIndex: false,
        overlayClass: '',
        overflow: 'hidden',
        message: '<span class="spinner-border text-primary"></span>'
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('blockui')) {
            the = KTUtil.data(element).get('blockui');
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.element = element;
        the.overlayElement = null;
        the.blocked = false;
        the.positionChanged = false;
        the.overflowChanged = false;

        // Bind Instance
        KTUtil.data(the.element).set('blockui', the);
    }

    const _block = function () {
        if (KTEventHandler.trigger(the.element, 'kt.blockui.block', the) === false) {
            return;
        }

        const isPage = (the.element.tagName === 'BODY');

        const position = KTUtil.css(the.element, 'position');
        const overflow = KTUtil.css(the.element, 'overflow');
        let zIndex = isPage ? 10000 : 1;

        if (the.options.zIndex > 0) {
            zIndex = the.options.zIndex;
        } else {
            if (KTUtil.css(the.element, 'z-index') != 'auto') {
                zIndex = KTUtil.css(the.element, 'z-index');
            }
        }

        the.element.classList.add('blockui');

        if (position === "absolute" || position === "relative" || position === "fixed") {
            KTUtil.css(the.element, 'position', 'relative');
            the.positionChanged = true;
        }

        if (the.options.overflow === 'hidden' && overflow === 'visible') {
            KTUtil.css(the.element, 'overflow', 'hidden');
            the.overflowChanged = true;
        }

        the.overlayElement = document.createElement('DIV');
        the.overlayElement.setAttribute('class', 'blockui-overlay ' + the.options.overlayClass);

        the.overlayElement.innerHTML = the.options.message;

        KTUtil.css(the.overlayElement, 'z-index', zIndex);

        the.element.append(the.overlayElement);
        the.blocked = true;

        KTEventHandler.trigger(the.element, 'kt.blockui.after.blocked', the)
    };

    const _release = function () {
        if (KTEventHandler.trigger(the.element, 'kt.blockui.release', the) === false) {
            return;
        }

        the.element.classList.add('blockui');

        if (the.positionChanged) {
            KTUtil.css(the.element, 'position', '');
        }

        if (the.overflowChanged) {
            KTUtil.css(the.element, 'overflow', '');
        }

        if (the.overlayElement) {
            KTUtil.remove(the.overlayElement);
        }

        the.blocked = false;

        KTEventHandler.trigger(the.element, 'kt.blockui.released', the);
    };

    const _isBlocked = function () {
        return the.blocked;
    };

    const _destroy = function () {
        KTUtil.data(the.element).remove('blockui');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.block = function () {
        _block();
    }

    the.release = function () {
        _release();
    }

    the.isBlocked = function () {
        return _isBlocked();
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTBlockUI.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('blockui')) {
        return KTUtil.data(element).get('blockui');
    } else {
        return null;
    }
}

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTBlockUI;
}
"use strict";
// DOCS: https://javascript.info/cookie

// Class definition
const KTCookie = function () {
    return {
        // returns the cookie with the given name,
        // or undefined if not found
        get: function (name) {
            const matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));

            return matches ? decodeURIComponent(matches[1]) : null;
        },

        // Please note that a cookie value is encoded,
        // so getCookie uses a built-in decodeURIComponent function to decode it.
        set: function (name, value, options) {
            if (typeof options === "undefined" || options === null) {
                options = {};
            }

            options = Object.assign({}, {
                path: '/'
            }, options);

            if (options.expires instanceof Date) {
                options.expires = options.expires.toUTCString();
            }

            let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

            for (let optionKey in options) {
                if (options.hasOwnProperty(optionKey) === false) {
                    continue;
                }

                updatedCookie += "; " + optionKey;
                const optionValue = options[optionKey];

                if (optionValue !== true) {
                    updatedCookie += "=" + optionValue;
                }
            }

            document.cookie = updatedCookie;
        },

        // To remove a cookie, we can call it with a negative expiration date:
        remove: function (name) {
            this.set(name, "", {
                'max-age': -1
            });
        }
    }
}();

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTCookie;
}

"use strict";

// Class definition
var KTDialer = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    const the = this;

    if (!element) {
        return;
    }

    // Default options
    const defaultOptions = {
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
    const _construct = function () {
        if (KTUtil.data(element).has('dialer') === true) {
            the = KTUtil.data(element).get('dialer');
        } else {
            _init();
        }
    };

    // Initialize
    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);

        // Elements
        the.element = element;
        the.incElement = the.element.querySelector('[data-kt-dialer-control="increase"]');
        the.decElement = the.element.querySelector('[data-kt-dialer-control="decrease"]');
        the.inputElement = the.element.querySelector('input[type]');

        // Set Values
        if (_getOption('decimals')) {
            the.options.decimals = parseInt(_getOption('decimals'));
        }

        if (_getOption('prefix')) {
            the.options.prefix = _getOption('prefix');
        }

        if (_getOption('suffix')) {
            the.options.suffix = _getOption('suffix');
        }

        if (_getOption('step')) {
            the.options.step = parseFloat(_getOption('step'));
        }

        if (_getOption('min')) {
            the.options.min = parseFloat(_getOption('min'));
        }

        if (_getOption('max')) {
            the.options.max = parseFloat(_getOption('max'));
        }

        the.value = parseFloat(the.inputElement.value.replace(/[^\d.]/g, ''));

        _setValue();

        // Event Handlers
        _handlers();

        // Bind Instance
        KTUtil.data(the.element).set('dialer', the);
    }

    // Handlers
    var _handlers = function () {
        KTUtil.addEvent(the.incElement, 'click', function (e) {
            e.preventDefault();

            _increase();
        });

        KTUtil.addEvent(the.decElement, 'click', function (e) {
            e.preventDefault();

            _decrease();
        });

        KTUtil.addEvent(the.inputElement, 'input', function (e) {
            e.preventDefault();

            _setValue();
        });
    }

    // Event handlers
    var _increase = function () {
        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, 'kt.dialer.increase', the);

        the.inputElement.value = the.value + the.options.step;
        _setValue();

        // Trigger "before.dialer" event
        KTEventHandler.trigger(the.element, 'kt.dialer.increased', the);

        return the;
    }

    var _decrease = function () {
        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, 'kt.dialer.decrease', the);

        the.inputElement.value = the.value - the.options.step;

        _setValue();

        // Trigger "before.dialer" event
        KTEventHandler.trigger(the.element, 'kt.dialer.decreased', the);

        return the;
    }

    // Set Input Value
    var _setValue = function (value) {
        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, 'kt.dialer.change', the);

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
        the.inputElement.dispatchEvent(new Event('change'));

        // Trigger "after.dialer" event
        KTEventHandler.trigger(the.element, 'kt.dialer.changed', the);
    }

    var _parse = function (val) {
        val = val
            .replace(/[^0-9.-]/g, '') // remove chars except number, hyphen, point.
            .replace(/(\..*)\./g, '$1') // remove multiple points.
            .replace(/(?!^)-/g, '') // remove middle hyphen.
            .replace(/^0+(\d)/gm, '$1'); // remove multiple leading zeros. <-- I added this.

        val = parseFloat(val);

        if (isNaN(val)) {
            val = 0;
        }

        return val;
    }

    // Format
    var _format = function (val) {
        return the.options.prefix + parseFloat(val).toFixed(the.options.decimals) + the.options.suffix;
    }

    // Get option
    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-dialer-' + name) === true) {
            const attr = the.element.getAttribute('data-kt-dialer-' + name);
            const value = attr;

            return value;
        } else {
            return null;
        }
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('dialer');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.setMinValue = function (value) {
        the.options.min = value;
    }

    the.setMaxValue = function (value) {
        the.options.max = value;
    }

    the.setValue = function (value) {
        _setValue(value);
    }

    the.getValue = function () {
        return the.inputElement.value;
    }

    the.update = function () {
        _setValue();
    }

    the.increase = function () {
        return _increase();
    }

    the.decrease = function () {
        return _decrease();
    }

    the.getElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTDialer.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('dialer')) {
        return KTUtil.data(element).get('dialer');
    } else {
        return null;
    }
}

// Create instances
KTDialer.createInstances = function (selector = '[data-kt-dialer="true"]') {
    // Get instances
    const elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            new KTDialer(elements[i]);
        }
    }
}

// Global initialization
KTDialer.init = function () {
    KTDialer.createInstances();
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTDialer;
}
"use strict";

let KTDrawerHandlersInitialized = false;

// Class definition
var KTDrawer = function (element, options) {
    //////////////////////////////
    // ** Private variables  ** //
    //////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default options
    const defaultOptions = {
        overlay: true,
        direction: 'end',
        baseClass: 'drawer',
        overlayClass: 'drawer-overlay'
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('drawer')) {
            the = KTUtil.data(element).get('drawer');
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('drawer');
        the.element = element;
        the.overlayElement = null;
        the.name = the.element.getAttribute('data-kt-drawer-name');
        the.shown = false;
        the.lastWidth;
        the.toggleElement = null;

        // Set initialized
        the.element.setAttribute('data-kt-drawer', 'true');

        // Event Handlers
        _handlers();

        // Update Instance
        _update();

        // Bind Instance
        KTUtil.data(the.element).set('drawer', the);
    }

    var _handlers = function () {
        const togglers = _getOption('toggle');
        const closers = _getOption('close');

        if (togglers !== null && togglers.length > 0) {
            KTUtil.on(document.body, togglers, 'click', function (e) {
                e.preventDefault();

                the.toggleElement = this;
                _toggle();
            });
        }

        if (closers !== null && closers.length > 0) {
            KTUtil.on(document.body, closers, 'click', function (e) {
                e.preventDefault();

                the.closeElement = this;
                _hide();
            });
        }
    }

    var _toggle = function () {
        if (KTEventHandler.trigger(the.element, 'kt.drawer.toggle', the) === false) {
            return;
        }

        if (the.shown === true) {
            _hide();
        } else {
            _show();
        }

        KTEventHandler.trigger(the.element, 'kt.drawer.toggled', the);
    }

    var _hide = function () {
        if (KTEventHandler.trigger(the.element, 'kt.drawer.hide', the) === false) {
            return;
        }

        the.shown = false;

        _deleteOverlay();

        document.body.removeAttribute('data-kt-drawer-' + the.name, 'on');
        document.body.removeAttribute('data-kt-drawer');

        KTUtil.removeClass(the.element, the.options.baseClass + '-on');

        if (the.toggleElement !== null) {
            KTUtil.removeClass(the.toggleElement, 'active');
        }

        KTEventHandler.trigger(the.element, 'kt.drawer.after.hidden', the) === false
    }

    var _show = function () {
        if (KTEventHandler.trigger(the.element, 'kt.drawer.show', the) === false) {
            return;
        }

        the.shown = true;

        _createOverlay();
        document.body.setAttribute('data-kt-drawer-' + the.name, 'on');
        document.body.setAttribute('data-kt-drawer', 'on');

        KTUtil.addClass(the.element, the.options.baseClass + '-on');

        if (the.toggleElement !== null) {
            KTUtil.addClass(the.toggleElement, 'active');
        }

        KTEventHandler.trigger(the.element, 'kt.drawer.shown', the);
    }

    var _update = function () {
        const width = _getWidth();
        const direction = _getOption('direction');

        const top = _getOption('top');
        const bottom = _getOption('bottom');
        const start = _getOption('start');
        const end = _getOption('end');

        // Reset state
        if (KTUtil.hasClass(the.element, the.options.baseClass + '-on') === true && String(document.body.getAttribute('data-kt-drawer-' + the.name + '-')) === 'on') {
            the.shown = true;
        } else {
            the.shown = false;
        }

        // Activate/deactivate
        if (_getOption('activate') === true) {
            KTUtil.addClass(the.element, the.options.baseClass);
            KTUtil.addClass(the.element, the.options.baseClass + '-' + direction);

            KTUtil.css(the.element, 'width', width, true);
            the.lastWidth = width;

            if (top) {
                KTUtil.css(the.element, 'top', top);
            }

            if (bottom) {
                KTUtil.css(the.element, 'bottom', bottom);
            }

            if (start) {
                if (KTUtil.isRTL()) {
                    KTUtil.css(the.element, 'right', start);
                } else {
                    KTUtil.css(the.element, 'left', start);
                }
            }

            if (end) {
                if (KTUtil.isRTL()) {
                    KTUtil.css(the.element, 'left', end);
                } else {
                    KTUtil.css(the.element, 'right', end);
                }
            }
        } else {
            KTUtil.removeClass(the.element, the.options.baseClass);
            KTUtil.removeClass(the.element, the.options.baseClass + '-' + direction);

            KTUtil.css(the.element, 'width', '');

            if (top) {
                KTUtil.css(the.element, 'top', '');
            }

            if (bottom) {
                KTUtil.css(the.element, 'bottom', '');
            }

            if (start) {
                if (KTUtil.isRTL()) {
                    KTUtil.css(the.element, 'right', '');
                } else {
                    KTUtil.css(the.element, 'left', '');
                }
            }

            if (end) {
                if (KTUtil.isRTL()) {
                    KTUtil.css(the.element, 'left', '');
                } else {
                    KTUtil.css(the.element, 'right', '');
                }
            }

            _hide();
        }
    }

    var _createOverlay = function () {
        if (_getOption('overlay') === true) {
            the.overlayElement = document.createElement('DIV');

            KTUtil.css(the.overlayElement, 'z-index', KTUtil.css(the.element, 'z-index') - 1); // update

            document.body.append(the.overlayElement);

            KTUtil.addClass(the.overlayElement, _getOption('overlay-class'));

            KTUtil.addEvent(the.overlayElement, 'click', function (e) {
                e.preventDefault();

                if (_getOption('permanent') !== true) {
                    _hide();
                }
            });
        }
    }

    var _deleteOverlay = function () {
        if (the.overlayElement !== null) {
            KTUtil.remove(the.overlayElement);
        }
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-drawer-' + name) === true) {
            const attr = the.element.getAttribute('data-kt-drawer-' + name);
            let value = KTUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }

            return value;
        } else {
            const optionName = KTUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KTUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    }

    var _getWidth = function () {
        let width = _getOption('width');

        if (width === 'auto') {
            width = KTUtil.css(the.element, 'width');
        }

        return width;
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('drawer');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.toggle = function () {
        return _toggle();
    }

    the.show = function () {
        return _show();
    }

    the.hide = function () {
        return _hide();
    }

    the.isShown = function () {
        return the.shown;
    }

    the.update = function () {
        _update();
    }

    the.goElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTDrawer.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('drawer')) {
        return KTUtil.data(element).get('drawer');
    } else {
        return null;
    }
}

// Hide all drawers and skip one if provided
KTDrawer.hideAll = function (skip = null, selector = '[data-kt-drawer="true"]') {
    const items = document.querySelectorAll(selector);

    if (items && items.length > 0) {
        let i = 0;
        const len = items.length;
        for (; i < len; i++) {
            const item = items[i];
            const drawer = KTDrawer.getInstance(item);

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
}

// Update all drawers
KTDrawer.updateAll = function (selector = '[data-kt-drawer="true"]') {
    const items = document.querySelectorAll(selector);

    if (items && items.length > 0) {
        let i = 0;
        const len = items.length;
        for (; i < len; i++) {
            const drawer = KTDrawer.getInstance(items[i]);

            if (drawer) {
                drawer.update();
            }
        }
    }
}

// Create instances
KTDrawer.createInstances = function (selector = '[data-kt-drawer="true"]') {
    // Initialize Menus
    const elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            new KTDrawer(elements[i]);
        }
    }
}

// Toggle instances
KTDrawer.handleShow = function () {
    // External drawer toggle handler
    KTUtil.on(document.body, '[data-kt-drawer-show="true"][data-kt-drawer-target]', 'click', function (e) {
        e.preventDefault();

        const element = document.querySelector(this.getAttribute('data-kt-drawer-target'));

        if (element) {
            KTDrawer.getInstance(element).show();
        }
    });
}

// Dismiss instances
KTDrawer.handleDismiss = function () {
    // External drawer toggle handler
    KTUtil.on(document.body, '[data-kt-drawer-dismiss="true"]', 'click', function (e) {
        const element = this.closest('[data-kt-drawer="true"]');

        if (element) {
            const drawer = KTDrawer.getInstance(element);
            if (drawer.isShown()) {
                drawer.hide();
            }
        }
    });
}

// Handle resize
KTDrawer.handleResize = function () {
    // Window resize Handling
    window.addEventListener('resize', function () {
        let timer;

        KTUtil.throttle(timer, function () {
            // Locate and update drawer instances on window resize
            const elements = document.querySelectorAll('[data-kt-drawer="true"]');

            if (elements && elements.length > 0) {
                let i = 0;
                const len = elements.length;
                for (; i < len; i++) {
                    const drawer = KTDrawer.getInstance(elements[i]);
                    if (drawer) {
                        drawer.update();
                    }
                }
            }
        }, 200);
    });
}

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
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTDrawer;
}
"use strict";

// Class definition
var KTEventHandler = function () {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    const _handlers = {};

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////
    const _triggerEvent = function (element, name, target) {
        let returnValue = true;
        let eventValue;

        if (KTUtil.data(element).has(name) === true) {
            const handlerIds = KTUtil.data(element).get(name);
            let handlerId;

            for (let i = 0; i < handlerIds.length; i++) {
                handlerId = handlerIds[i];

                if (_handlers[name] && _handlers[name][handlerId]) {
                    const handler = _handlers[name][handlerId];
                    let value;

                    if (handler.name === name) {
                        if (handler.one == true) {
                            if (handler.fired == false) {
                                _handlers[name][handlerId].fired = true;

                                eventValue = handler.callback.call(this, target);
                            }
                        } else {
                            eventValue = handler.callback.call(this, target);
                        }

                        if (eventValue === false) {
                            returnValue = false;
                        }
                    }
                }
            }
        }

        return returnValue;
    };

    const _addEvent = function (element, name, callback, one) {
        const handlerId = KTUtil.getUniqueId('event');
        let handlerIds = KTUtil.data(element).get(name);

        if (!handlerIds) {
            handlerIds = [];
        }

        handlerIds.push(handlerId);

        KTUtil.data(element).set(name, handlerIds);

        if (!_handlers[name]) {
            _handlers[name] = {};
        }

        _handlers[name][handlerId] = {
            name: name,
            callback: callback,
            one: one,
            fired: false
        };

        return handlerId;
    };

    const _removeEvent = function (element, name, handlerId) {
        const handlerIds = KTUtil.data(element).get(name);
        const index = handlerIds && handlerIds.indexOf(handlerId);

        if (index !== -1) {
            handlerIds.splice(index, 1);
            KTUtil.data(element).set(name, handlerIds);
        }

        if (_handlers[name] && _handlers[name][handlerId]) {
            delete _handlers[name][handlerId];
        }
    };

    ////////////////////////////
    // ** Public Methods  ** //
    ////////////////////////////
    return {
        trigger: function (element, name, target) {
            return _triggerEvent(element, name, target);
        },

        on: function (element, name, handler) {
            return _addEvent(element, name, handler);
        },

        one: function (element, name, handler) {
            return _addEvent(element, name, handler, true);
        },

        off: function (element, name, handlerId) {
            return _removeEvent(element, name, handlerId);
        },

        debug: function () {
            for (let b in _handlers) {
                if (_handlers.hasOwnProperty(b)) console.log(b);
            }
        }
    }
}();

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTEventHandler;
}

"use strict";

// Class definition
const KTFeedback = function (options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    const the = this;

    // Default options
    const defaultOptions = {
        'width': 100,
        'placement': 'top-center',
        'content': '',
        'type': 'popup'
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    const _construct = function () {
        _init();
    };

    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('feedback');
        the.element;
        the.shown = false;

        // Event Handlers
        _handlers();

        // Bind Instance
        KTUtil.data(the.element).set('feedback', the);
    }

    var _handlers = function () {
        KTUtil.addEvent(the.element, 'click', function (e) {
            e.preventDefault();

            _go();
        });
    }

    const _show = function () {
        if (KTEventHandler.trigger(the.element, 'kt.feedback.show', the) === false) {
            return;
        }

        if (the.options.type === 'popup') {
            _showPopup();
        }

        KTEventHandler.trigger(the.element, 'kt.feedback.shown', the);

        return the;
    };

    const _hide = function () {
        if (KTEventHandler.trigger(the.element, 'kt.feedback.hide', the) === false) {
            return;
        }

        if (the.options.type === 'popup') {
            _hidePopup();
        }

        the.shown = false;

        KTEventHandler.trigger(the.element, 'kt.feedback.hidden', the);

        return the;
    };

    var _showPopup = function () {
        the.element = document.createElement("DIV");

        KTUtil.addClass(the.element, 'feedback feedback-popup');
        KTUtil.setHTML(the.element, the.options.content);

        if (the.options.placement == 'top-center') {
            _setPopupTopCenterPosition();
        }

        document.body.appendChild(the.element);

        KTUtil.addClass(the.element, 'feedback-shown');

        the.shown = true;
    }

    var _setPopupTopCenterPosition = function () {
        const width = KTUtil.getResponsiveValue(the.options.width);
        const height = KTUtil.css(the.element, 'height');

        KTUtil.addClass(the.element, 'feedback-top-center');

        KTUtil.css(the.element, 'width', width);
        KTUtil.css(the.element, 'left', '50%');
        KTUtil.css(the.element, 'top', '-' + height);
    }

    var _hidePopup = function () {
        the.element.remove();
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('feedback');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.show = function () {
        return _show();
    }

    the.hide = function () {
        return _hide();
    }

    the.isShown = function () {
        return the.shown;
    }

    the.getElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTFeedback;
}

"use strict";

// Class definition
var KTImageInput = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    const defaultOptions = {};

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('image-input') === true) {
            the = KTUtil.data(element).get('image-input');
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('image-input');

        // Elements
        the.element = element;
        the.inputElement = KTUtil.find(element, 'input[type="file"]');
        the.wrapperElement = KTUtil.find(element, '.image-input-wrapper');
        the.cancelElement = KTUtil.find(element, '[data-kt-image-input-action="cancel"]');
        the.removeElement = KTUtil.find(element, '[data-kt-image-input-action="remove"]');
        the.hiddenElement = KTUtil.find(element, 'input[type="hidden"]');
        the.src = KTUtil.css(the.wrapperElement, 'backgroundImage');

        // Set initialized
        the.element.setAttribute('data-kt-image-input', 'true');

        // Event Handlers
        _handlers();

        // Bind Instance
        KTUtil.data(the.element).set('image-input', the);
    }

    // Init Event Handlers
    var _handlers = function () {
        KTUtil.addEvent(the.inputElement, 'change', _change);
        KTUtil.addEvent(the.cancelElement, 'click', _cancel);
        KTUtil.addEvent(the.removeElement, 'click', _remove);
    }

    // Event Handlers
    var _change = function (e) {
        e.preventDefault();

        if (the.inputElement !== null && the.inputElement.files && the.inputElement.files[0]) {
            // Fire change event
            if (KTEventHandler.trigger(the.element, 'kt.imageinput.change', the) === false) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                KTUtil.css(the.wrapperElement, 'background-image', 'url(' + e.target.result + ')');
            }

            reader.readAsDataURL(the.inputElement.files[0]);

            the.element.classList.add('image-input-changed');
            the.element.classList.remove('image-input-empty');

            // Fire removed event
            KTEventHandler.trigger(the.element, 'kt.imageinput.changed', the);
        }
    }

    var _cancel = function (e) {
        e.preventDefault();

        // Fire cancel event
        if (KTEventHandler.trigger(the.element, 'kt.imageinput.cancel', the) === false) {
            return;
        }

        the.element.classList.remove('image-input-changed');
        the.element.classList.remove('image-input-empty');

        if (the.src === 'none') {
            KTUtil.css(the.wrapperElement, 'background-image', '');
            the.element.classList.add('image-input-empty');
        } else {
            KTUtil.css(the.wrapperElement, 'background-image', the.src);
        }

        the.inputElement.value = "";

        if (the.hiddenElement !== null) {
            the.hiddenElement.value = "0";
        }

        // Fire canceled event
        KTEventHandler.trigger(the.element, 'kt.imageinput.canceled', the);
    }

    var _remove = function (e) {
        e.preventDefault();

        // Fire remove event
        if (KTEventHandler.trigger(the.element, 'kt.imageinput.remove', the) === false) {
            return;
        }

        the.element.classList.remove('image-input-changed');
        the.element.classList.add('image-input-empty');

        KTUtil.css(the.wrapperElement, 'background-image', "none");
        the.inputElement.value = "";

        if (the.hiddenElement !== null) {
            the.hiddenElement.value = "1";
        }

        // Fire removed event
        KTEventHandler.trigger(the.element, 'kt.imageinput.removed', the);
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('image-input');
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.getInputElement = function () {
        return the.inputElement;
    }

    the.getElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTImageInput.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('image-input')) {
        return KTUtil.data(element).get('image-input');
    } else {
        return null;
    }
}

// Create instances
KTImageInput.createInstances = function (selector = '[data-kt-image-input]') {
    // Initialize Menus
    const elements = document.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            new KTImageInput(elements[i]);
        }
    }
}

// Global initialization
KTImageInput.init = function () {
    KTImageInput.createInstances();
};

// Webpack Support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTImageInput;
}

"use strict";

let KTMenuHandlersInitialized = false;

// Class definition
var KTMenu = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    const defaultOptions = {
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

    const _construct = function () {
        if (KTUtil.data(element).has('menu') === true) {
            the = KTUtil.data(element).get('menu');
        } else {
            _init();
        }
    };

    var _init = function () {
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('menu');
        the.element = element;
        the.triggerElement;
        the.disabled = false;

        // Set initialized
        the.element.setAttribute('data-kt-menu', 'true');

        _setTriggerElement();
        _update();

        KTUtil.data(the.element).set('menu', the);
    }

    var _destroy = function () { // todo

    }

    // Event Handlers
    // Toggle handler
    const _click = function (element, e) {
        e.preventDefault();

        if (the.disabled === true) {
            return;
        }

        const item = _getItemElement(element);

        if (_getOptionFromElementAttribute(item, 'trigger') !== 'click') {
            return;
        }

        if (_getOptionFromElementAttribute(item, 'toggle') === false) {
            _show(item);
        } else {
            _toggle(item);
        }
    };

    // Link handler
    const _link = function (element, e) {
        if (the.disabled === true) {
            return;
        }

        if (KTEventHandler.trigger(the.element, 'kt.menu.link.click', element) === false) {
            return;
        }

        // Dismiss all shown dropdowns
        KTMenu.hideDropdowns();

        KTEventHandler.trigger(the.element, 'kt.menu.link.clicked', element);
    };

    // Dismiss handler
    const _dismiss = function (element, e) {
        const item = _getItemElement(element);
        const items = _getItemChildElements(item);

        if (item !== null && _getItemSubType(item) === 'dropdown') {
            _hide(item); // hide items dropdown
            // Hide all child elements as well

            if (items.length > 0) {
                let i = 0;
                const len = items.length;
                for (; i < len; i++) {
                    if (items[i] !== null && _getItemSubType(items[i]) === 'dropdown') {
                        _hide(tems[i]);
                    }
                }
            }
        }
    };

    // Mouseover handle
    const _mouseover = function (element, e) {
        const item = _getItemElement(element);

        if (the.disabled === true) {
            return;
        }

        if (item === null) {
            return;
        }

        if (_getOptionFromElementAttribute(item, 'trigger') !== 'hover') {
            return;
        }

        if (KTUtil.data(item).get('hover') === '1') {
            clearTimeout(KTUtil.data(item).get('timeout'));
            KTUtil.data(item).remove('hover');
            KTUtil.data(item).remove('timeout');
        }

        _show(item);
    };

    // Mouseout handle
    const _mouseout = function (element, e) {
        const item = _getItemElement(element);

        if (the.disabled === true) {
            return;
        }

        if (item === null) {
            return;
        }

        if (_getOptionFromElementAttribute(item, 'trigger') !== 'hover') {
            return;
        }

        const timeout = setTimeout(function () {
            if (KTUtil.data(item).get('hover') === '1') {
                _hide(item);
            }
        }, the.options.dropdown.hoverTimeout);

        KTUtil.data(item).set('hover', '1');
        KTUtil.data(item).set('timeout', timeout);
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
    }

    // Show item sub
    var _show = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === true) {
            return;
        }

        if (_getItemSubType(item) === 'dropdown') {
            _showDropdown(item); // // show current dropdown
        } else if (_getItemSubType(item) === 'accordion') {
            _showAccordion(item);
        }

        // Remember last submenu type
        KTUtil.data(item).set('type', _getItemSubType(item)); // updated
    }

    // Hide item sub
    var _hide = function (item) {
        if (!item) {
            item = the.triggerElement;
        }

        if (_isItemSubShown(item) === false) {
            return;
        }

        if (_getItemSubType(item) === 'dropdown') {
            _hideDropdown(item);
        } else if (_getItemSubType(item) === 'accordion') {
            _hideAccordion(item);
        }
    }

    // Reset item state classes if item sub type changed
    const _reset = function (item) {
        if (_hasItemSub(item) === false) {
            return;
        }

        const sub = _getItemSubElement(item);

        // Reset sub state if sub type is changed during the window resize
        if (KTUtil.data(item).has('type') && KTUtil.data(item).get('type') !== _getItemSubType(item)) { // updated
            KTUtil.removeClass(item, 'hover');
            KTUtil.removeClass(item, 'show');
            KTUtil.removeClass(sub, 'show');
        } // updated
    };

    // Update all item state classes if item sub type changed
    var _update = function () {
        const items = the.element.querySelectorAll('.menu-item[data-kt-menu-trigger]');

        if (items && items.length > 0) {
            let i = 0;
            const len = items.length;
            for (; i < len; i++) {
                _reset(items[i]);
            }
        }
    }

    // Set external trigger element
    var _setTriggerElement = function () {
        const target = document.querySelector('[data-kt-menu-target="# ' + the.element.getAttribute('id') + '"]');

        if (target !== null) {
            the.triggerElement = target;
        } else if (the.element.closest('[data-kt-menu-trigger]')) {
            the.triggerElement = the.element.closest('[data-kt-menu-trigger]');
        } else if (the.element.parentNode && KTUtil.child(the.element.parentNode, '[data-kt-menu-trigger]')) {
            the.triggerElement = KTUtil.child(the.element.parentNode, '[data-kt-menu-trigger]');
        }

        if (the.triggerElement) {
            KTUtil.data(the.triggerElement).set('menu', the);
        }
    }

    // Test if menu has external trigger element
    const _isTriggerElement = function (item) {
        return (the.triggerElement === item) ? true : false;
    };

    // Test if item's sub is shown
    var _isItemSubShown = function (item) {
        const sub = _getItemSubElement(item);

        if (sub !== null) {
            if (_getItemSubType(item) === 'dropdown') {
                if (KTUtil.hasClass(sub, 'show') === true && sub.hasAttribute('data-popper-placement') === true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return KTUtil.hasClass(item, 'show');
            }
        } else {
            return false;
        }
    }

    // Test if item dropdown is permanent
    const _isItemDropdownPermanent = function (item) {
        return _getOptionFromElementAttribute(item, 'permanent') === true ? true : false;
    };

    // Test if item's parent is shown
    const _isItemParentShown = function (item) {
        return KTUtil.parents(item, '.menu-item.show').length > 0;
    };

    // Test of it is item sub element
    const _isItemSubElement = function (item) {
        return KTUtil.hasClass(item, 'menu-sub');
    };

    // Test if item has sub
    var _hasItemSub = function (item) {
        return (KTUtil.hasClass(item, 'menu-item') && item.hasAttribute('data-kt-menu-trigger'));
    }

    // Get link element
    const _getItemLinkElement = function (item) {
        return KTUtil.child(item, '.menu-link');
    };

    // Get toggle element
    const _getItemToggleElement = function (item) {
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
        if (item.classList.contains('menu-sub') === true) {
            return item;
        } else if (KTUtil.data(item).has('sub')) {
            return KTUtil.data(item).get('sub');
        } else {
            return KTUtil.child(item, '.menu-sub');
        }
    }

    // Get item sub type
    var _getItemSubType = function (element) {
        const sub = _getItemSubElement(element);

        if (sub && parseInt(KTUtil.css(sub, 'z-index')) > 0) {
            return "dropdown";
        } else {
            return "accordion";
        }
    }

    // Get item element
    var _getItemElement = function (element) {
        let item, sub;

        // Element is the external trigger element
        if (_isTriggerElement(element)) {
            return element;
        }

        // Element has item toggler attribute
        if (element.hasAttribute('data-kt-menu-trigger')) {
            return element;
        }

        // Element has item DOM reference in it's data storage
        if (KTUtil.data(element).has('item')) {
            return KTUtil.data(element).get('item');
        }

        // Item is parent of element
        if ((item = element.closest('.menu-item[data-kt-menu-trigger]'))) {
            return item;
        }

        // Element's parent has item DOM reference in it's data storage
        if ((sub = element.closest('.menu-sub'))) {
            if (KTUtil.data(sub).has('item') === true) {
                return KTUtil.data(sub).get('item')
            }
        }
    }

    // Get item parent element
    const _getItemParentElement = function (item) {
        const sub = item.closest('.menu-sub');
        let parentItem;

        if (KTUtil.data(sub).has('item')) {
            return KTUtil.data(sub).get('item');
        }

        if (sub && (parentItem = sub.closest('.menu-item[data-kt-menu-trigger]'))) {
            return parentItem;
        }

        return null;
    };

    // Get item parent elements
    const _getItemParentElements = function (item) {
        const parents = [];
        let parent;
        let i = 0;

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
    const _getItemChildElement = function (item) {
        let selector = item;
        let element;

        if (KTUtil.data(item).get('sub')) {
            selector = KTUtil.data(item).get('sub');
        }

        if (selector !== null) {
            //element = selector.querySelector('.show.menu-item[data-kt-menu-trigger]');
            element = selector.querySelector('.menu-item[data-kt-menu-trigger]');

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
        const children = [];
        let child;
        let i = 0;

        do {
            child = _getItemChildElement(item);

            if (child) {
                children.push(child);
                item = child;
            }

            i++;
        } while (child !== null && i < 20);

        return children;
    }

    // Show item dropdown
    var _showDropdown = function (item) {
        // Handle dropdown show event
        if (KTEventHandler.trigger(the.element, 'kt.menu.dropdown.show', item) === false) {
            return;
        }

        // Hide all currently shown dropdowns except current one
        KTMenu.hideDropdowns(item);

        const toggle = _isTriggerElement(item) ? item : _getItemLinkElement(item);
        const sub = _getItemSubElement(item);

        const width = _getOptionFromElementAttribute(item, 'width');
        const height = _getOptionFromElementAttribute(item, 'height');

        let zindex = the.options.dropdown.zindex; // update
        const parentZindex = KTUtil.getHighestZindex(item); // update

        // Apply a new z-index if dropdown's toggle element or it's parent has greater z-index // update
        if (parentZindex !== null && parentZindex >= zindex) {
            zindex = parentZindex + 1;
        }

        if (zindex > 0) {
            KTUtil.css(sub, 'z-index', zindex);
        }

        if (width !== null) {
            KTUtil.css(sub, 'width', width);
        }

        if (height !== null) {
            KTUtil.css(sub, 'height', height);
        }

        KTUtil.css(sub, 'display', '');
        KTUtil.css(sub, 'overflow', '');

        // Init popper(new)
        _initDropdownPopper(item, sub);

        KTUtil.addClass(item, 'show');
        KTUtil.addClass(item, 'menu-dropdown');
        KTUtil.addClass(sub, 'show');

        // Append the sub the the root of the menu
        if (_getOptionFromElementAttribute(item, 'overflow') === true) {
            document.body.appendChild(sub);
            KTUtil.data(item).set('sub', sub);
            KTUtil.data(sub).set('item', item);
            KTUtil.data(sub).set('menu', the);
        } else {
            KTUtil.data(sub).set('item', item);
        }

        // Handle dropdown shown event
        KTEventHandler.trigger(the.element, 'kt.menu.dropdown.shown', item);
    }

    // Hide item dropdown
    var _hideDropdown = function (item) {
        // Handle dropdown hide event
        if (KTEventHandler.trigger(the.element, 'kt.menu.dropdown.hide', item) === false) {
            return;
        }

        const sub = _getItemSubElement(item);

        KTUtil.css(sub, 'z-index', '');
        KTUtil.css(sub, 'width', '');
        KTUtil.css(sub, 'height', '');

        KTUtil.removeClass(item, 'show');
        KTUtil.removeClass(item, 'menu-dropdown');
        KTUtil.removeClass(sub, 'show');

        // Append the sub back to it's parent
        if (_getOptionFromElementAttribute(item, 'overflow') === true) {
            if (item.classList.contains('menu-item')) {
                item.appendChild(sub);
            } else {
                KTUtil.insertAfter(the.element, item);
            }

            KTUtil.data(item).remove('sub');
            KTUtil.data(sub).remove('item');
            KTUtil.data(sub).remove('menu');
        }

        // Destroy popper(new)
        _destroyDropdownPopper(item);

        // Handle dropdown hidden event
        KTEventHandler.trigger(the.element, 'kt.menu.dropdown.hidden', item);
    }

    // Init dropdown popper(new)
    var _initDropdownPopper = function (item, sub) {
        // Setup popper instance
        let reference;
        const attach = _getOptionFromElementAttribute(item, 'attach');

        if (attach) {
            if (attach === 'parent') {
                reference = item.parentNode;
            } else {
                reference = document.querySelector(attach);
            }
        } else {
            reference = item;
        }

        const popper = Popper.createPopper(reference, sub, _getDropdownPopperConfig(item));
        KTUtil.data(item).set('popper', popper);
    }

    // Destroy dropdown popper(new)
    var _destroyDropdownPopper = function (item) {
        if (KTUtil.data(item).has('popper') === true) {
            KTUtil.data(item).get('popper').destroy();
            KTUtil.data(item).remove('popper');
        }
    }

    // Prepare popper config for dropdown(see: https://popper.js.org/docs/v2/)
    var _getDropdownPopperConfig = function (item) {
        // Placement
        let placement = _getOptionFromElementAttribute(item, 'placement');
        if (!placement) {
            placement = 'right';
        }

        // Offset
        const offsetValue = _getOptionFromElementAttribute(item, 'offset');
        const offset = offsetValue ? offsetValue.split(",") : [];

        if (offset.length === 2) {
            offset[0] = parseInt(offset[0]);
            offset[1] = parseInt(offset[1]);
        }

        // Strategy
        const strategy = _getOptionFromElementAttribute(item, 'overflow') === true ? 'absolute' : 'fixed';

        const altAxis = _getOptionFromElementAttribute(item, 'flip') !== false ? true : false;

        const popperConfig = {
            placement: placement,
            strategy: strategy,
            modifiers: [{
                name: 'offset',
                options: {
                    offset: offset
                }
            }, {
                name: 'preventOverflow',
                options: {
                    altAxis: altAxis
                }
            }, {
                name: 'flip',
                options: {
                    flipVariations: false
                }
            }]
        };

        return popperConfig;
    }

    // Show item accordion
    var _showAccordion = function (item) {
        if (KTEventHandler.trigger(the.element, 'kt.menu.accordion.show', item) === false) {
            return;
        }

        const sub = _getItemSubElement(item);
        let expand = the.options.accordion.expand;

        if (_getOptionFromElementAttribute(item, 'expand') === true) {
            expand = true;
        } else if (_getOptionFromElementAttribute(item, 'expand') === false) {
            expand = false;
        } else if (_getOptionFromElementAttribute(the.element, 'expand') === true) {
            expand = true;
        }

        if (expand === false) {
            _hideAccordions(item);
        }

        if (KTUtil.data(item).has('popper') === true) {
            _hideDropdown(item);
        }

        KTUtil.addClass(item, 'hover');

        KTUtil.addClass(item, 'showing');

        KTUtil.slideDown(sub, the.options.accordion.slideSpeed, function () {
            KTUtil.removeClass(item, 'showing');
            KTUtil.addClass(item, 'show');
            KTUtil.addClass(sub, 'show');

            KTEventHandler.trigger(the.element, 'kt.menu.accordion.shown', item);
        });
    }

    // Hide item accordion
    var _hideAccordion = function (item) {
        if (KTEventHandler.trigger(the.element, 'kt.menu.accordion.hide', item) === false) {
            return;
        }

        const sub = _getItemSubElement(item);

        KTUtil.addClass(item, 'hiding');

        KTUtil.slideUp(sub, the.options.accordion.slideSpeed, function () {
            KTUtil.removeClass(item, 'hiding');
            KTUtil.removeClass(item, 'show');
            KTUtil.removeClass(sub, 'show');

            KTUtil.removeClass(item, 'hover'); // update

            KTEventHandler.trigger(the.element, 'kt.menu.accordion.hidden', item);
        });
    }

    const _setActiveLink = function (link) {
        const item = _getItemElement(link);
        const parentItems = _getItemParentElements(item);
        const parentTabPane = link.closest('.tab-pane');

        const activeLinks = [].slice.call(the.element.querySelectorAll('.menu-link.active'));
        const activeParentItems = [].slice.call(the.element.querySelectorAll('.menu-item.here, .menu-item.show'));

        if (_getItemSubType(item) === "accordion") {
            _showAccordion(item);
        } else {
            item.classList.add("here");
        }

        if (parentItems && parentItems.length > 0) {
            let i = 0;
            const len = parentItems.length;
            for (; i < len; i++) {
                const parentItem = parentItems[i];

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
            const tabEl = the.element.querySelector('[data-bs-target="#' + parentTabPane.getAttribute("id") + '"]');
            const tab = new bootstrap.Tab(tabEl);

            if (tab) {
                tab.show();
            }
        }

        link.classList.add("active");
    };

    const _getLinkByAttribute = function (value, name = "href") {
        const link = the.element.querySelector('a[' + name + '="' + value + '"]');

        if (link) {
            return link;
        } else {
            null;
        }
    };

    // Hide all shown accordions of item
    var _hideAccordions = function (item) {
        const itemsToHide = KTUtil.findAll(the.element, '.show[data-kt-menu-trigger]');
        let itemToHide;

        if (itemsToHide && itemsToHide.length > 0) {
            let i = 0;
            const len = itemsToHide.length;
            for (; i < len; i++) {
                itemToHide = itemsToHide[i];

                if (_getItemSubType(itemToHide) === 'accordion' && itemToHide !== item && item.contains(itemToHide) === false && itemToHide.contains(item) === false) {
                    _hideAccordion(itemToHide);
                }
            }
        }
    }

    // Get item option(through html attributes)
    var _getOptionFromElementAttribute = function (item, name) {
        let attr;
        let value = null;

        if (item && item.hasAttribute('data-kt-menu-' + name)) {
            attr = item.getAttribute('data-kt-menu-' + name);
            value = KTUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }
        }

        return value;
    }

    var _destroy = function () {
        KTUtil.data(the.element).remove('menu');
    }

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Event Handlers
    the.click = function (element, e) {
        return _click(element, e);
    }

    the.link = function (element, e) {
        return _link(element, e);
    }

    the.dismiss = function (element, e) {
        return _dismiss(element, e);
    }

    the.mouseover = function (element, e) {
        return _mouseover(element, e);
    }

    the.mouseout = function (element, e) {
        return _mouseout(element, e);
    }

    // General Methods
    the.getItemTriggerType = function (item) {
        return _getOptionFromElementAttribute(item, 'trigger');
    }

    the.getItemSubType = function (element) {
        return _getItemSubType(element);
    }

    the.show = function (item) {
        return _show(item);
    }

    the.hide = function (item) {
        return _hide(item);
    }

    the.toggle = function (item) {
        return _toggle(item);
    }

    the.reset = function (item) {
        return _reset(item);
    }

    the.update = function () {
        return _update();
    }

    the.getElement = function () {
        return the.element;
    }

    the.setActiveLink = function (link) {
        return _setActiveLink(link);
    }

    the.getLinkByAttribute = function (value, name = "href") {
        return _getLinkByAttribute(value, name);
    }

    the.getItemLinkElement = function (item) {
        return _getItemLinkElement(item);
    }

    the.getItemToggleElement = function (item) {
        return _getItemToggleElement(item);
    }

    the.getItemSubElement = function (item) {
        return _getItemSubElement(item);
    }

    the.getItemParentElements = function (item) {
        return _getItemParentElements(item);
    }

    the.isItemSubShown = function (item) {
        return _isItemSubShown(item);
    }

    the.isItemParentShown = function (item) {
        return _isItemParentShown(item);
    }

    the.getTriggerElement = function () {
        return the.triggerElement;
    }

    the.isItemDropdownPermanent = function (item) {
        return _isItemDropdownPermanent(item);
    }

    the.destroy = function () {
        return _destroy();
    }

    the.disable = function () {
        the.disabled = true;
    }

    the.enable = function () {
        the.disabled = false;
    }

    // Accordion Mode Methods
    the.hideAccordions = function (item) {
        return _hideAccordions(item);
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }
};

// Get KTMenu instance by element
KTMenu.getInstance = function (element) {
    let menu;
    let item;

    if (!element) {
        return null;
    }

    // Element has menu DOM reference in it's DATA storage
    if (KTUtil.data(element).has('menu')) {
        return KTUtil.data(element).get('menu');
    }

    // Element has .menu parent
    if (menu = element.closest('.menu')) {
        if (KTUtil.data(menu).has('menu')) {
            return KTUtil.data(menu).get('menu');
        }
    }

    // Element has a parent with DOM reference to .menu in it's DATA storage
    if (KTUtil.hasClass(element, 'menu-link')) {
        const sub = element.closest('.menu-sub');

        if (KTUtil.data(sub).has('menu')) {
            return KTUtil.data(sub).get('menu');
        }
    }

    return null;
}

// Hide all dropdowns and skip one if provided
KTMenu.hideDropdowns = function (skip) {
    const items = document.querySelectorAll('.show.menu-dropdown[data-kt-menu-trigger]');

    if (items && items.length > 0) {
        let i = 0;
        const len = items.length;
        for (; i < len; i++) {
            const item = items[i];
            const menu = KTMenu.getInstance(item);

            if (menu && menu.getItemSubType(item) === 'dropdown') {
                if (skip) {
                    if (menu.getItemSubElement(item).contains(skip) === false && item.contains(skip) === false && item !== skip) {
                        menu.hide(item);
                    }
                } else {
                    menu.hide(item);
                }
            }
        }
    }
}

// Update all dropdowns popover instances
KTMenu.updateDropdowns = function () {
    const items = document.querySelectorAll('.show.menu-dropdown[data-kt-menu-trigger]');

    if (items && items.length > 0) {
        let i = 0;
        const len = items.length;
        for (; i < len; i++) {
            const item = items[i];

            if (KTUtil.data(item).has('popper')) {
                KTUtil.data(item).get('popper').forceUpdate();
            }
        }
    }
}

// Global handlers
KTMenu.initHandlers = function () {
    // Dropdown handler
    document.addEventListener("click", function (e) {
        const items = document.querySelectorAll('.show.menu-dropdown[data-kt-menu-trigger]:not([data-kt-menu-static="true"])');
        let menu;
        let item;
        let sub;
        let menuObj;

        if (items && items.length > 0) {
            let i = 0;
            const len = items.length;
            for (; i < len; i++) {
                item = items[i];
                menuObj = KTMenu.getInstance(item);

                if (menuObj && menuObj.getItemSubType(item) === 'dropdown') {
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
    KTUtil.on(document.body, '.menu-item[data-kt-menu-trigger] > .menu-link, [data-kt-menu-trigger]:not(.menu-item):not([data-kt-menu-trigger="auto"])', 'click', function (e) {
        const menu = KTMenu.getInstance(this);

        if (menu !== null) {
            return menu.click(this, e);
        }
    });

    // Link handler
    KTUtil.on(document.body, '.menu-item:not([data-kt-menu-trigger]) > .menu-link', 'click', function (e) {
        const menu = KTMenu.getInstance(this);

        if (menu !== null) {
            return menu.link(this, e);
        }
    });

    // Dismiss handler
    KTUtil.on(document.body, '[data-kt-menu-dismiss="true"]', 'click', function (e) {
        const menu = KTMenu.getInstance(this);

        if (menu !== null) {
            return menu.dismiss(this, e);
        }
    });

    // Mouseover handler
    KTUtil.on(document.body, '[data-kt-menu-trigger], .menu-sub', 'mouseover', function (e) {
        const menu = KTMenu.getInstance(this);

        if (menu !== null && menu.getItemSubType(this) === 'dropdown') {
            return menu.mouseover(this, e);
        }
    });

    // Mouseout handler
    KTUtil.on(document.body, '[data-kt-menu-trigger], .menu-sub', 'mouseout', function (e) {
        const menu = KTMenu.getInstance(this);

        if (menu !== null && menu.getItemSubType(this) === 'dropdown') {
            return menu.mouseout(this, e);
        }
    });

    // Resize handler
    window.addEventListener('resize', function () {
        let menu;
        let timer;

        KTUtil.throttle(timer, function () {
            // Locate and update Offcanvas instances on window resize
            const elements = document.querySelectorAll('[data-kt-menu="true"]');

            if (elements && elements.length > 0) {
                let i = 0;
                const len = elements.length;
                for (; i < len; i++) {
                    menu = KTMenu.getInstance(elements[i]);
                    if (menu) {
                        menu.update();
                    }
                }
            }
        }, 200);
    });
}

// Render menus by url
KTMenu.updateByLinkAttribute = function (value, name = "href") {
    // Locate and update Offcanvas instances on window resize
    const elements = document.querySelectorAll('[data-kt-menu="true"]');

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            const menu = KTMenu.getInstance(elements[i]);

            if (menu) {
                const link = menu.getLinkByAttribute(value, name);
                if (link) {
                    menu.setActiveLink(link);
                }
            }
        }
    }
}

// Global instances
KTMenu.createInstances = function (selector = '[data-kt-menu="true"]') {
    // Initialize menus
    const elements = document.querySelectorAll(selector);
    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            new KTMenu(elements[i]);
        }
    }
}

// Global initialization
KTMenu.init = function () {
    KTMenu.createInstances();

    if (KTMenuHandlersInitialized === false) {
        KTMenu.initHandlers();

        KTMenuHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTMenu;
}

"use strict";

// Class definition
var KTPasswordMeter = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    const the = this;

    if (!element) {
        return;
    }

    // Default Options
    const defaultOptions = {
        minLength: 8,
        checkUppercase: true,
        checkLowercase: true,
        checkDigit: true,
        checkChar: true,
        scoreHighlightClass: 'active'
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    // Constructor
    const _construct = function () {
        if (KTUtil.data(element).has('password-meter') === true) {
            the = KTUtil.data(element).get('password-meter');
        } else {
            _init();
        }
    };

    // Initialize
    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.score = 0;
        the.checkSteps = 5;

        // Elements
        the.element = element;
        the.inputElement = the.element.querySelector('input[type]');
        the.visibilityElement = the.element.querySelector('[data-kt-password-meter-control="visibility"]');
        the.highlightElement = the.element.querySelector('[data-kt-password-meter-control="highlight"]');

        // Set initialized
        the.element.setAttribute('data-kt-password-meter', 'true');

        // Event Handlers
        _handlers();

        // Bind Instance
        KTUtil.data(the.element).set('password-meter', the);
    }

    // Handlers
    var _handlers = function () {
        if (the.highlightElement) {
            the.inputElement.addEventListener('input', function () {
                _check();
            });
        }

        if (the.visibilityElement) {
            the.visibilityElement.addEventListener('click', function () {
                _visibility();
            });
        }
    }

    // Event handlers
    var _check = function () {
        let score = 0;
        const checkScore = _getCheckScore();

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
    }

    var _checkLength = function () {
        return the.inputElement.value.length >= the.options.minLength; // 20 score
    }

    var _checkLowercase = function () {
        return /[a-z]/.test(the.inputElement.value); // 20 score
    }

    var _checkUppercase = function () {
        return /[A-Z]/.test(the.inputElement.value); // 20 score
    }

    var _checkDigit = function () {
        return /[0-9]/.test(the.inputElement.value); // 20 score
    }

    var _checkChar = function () {
        return /[~`!#@$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(the.inputElement.value); // 20 score
    }

    var _getCheckScore = function () {
        let count = 1;

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
    }

    var _highlight = function () {
        const items = [].slice.call(the.highlightElement.querySelectorAll('div'));
        const total = items.length;
        let index = 0;
        const checkScore = _getCheckScore();
        const score = _getScore();

        items.map(function (item) {
            index++;

            if ((checkScore * index * (the.checkSteps / total)) <= score) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    var _visibility = function () {
        const visibleIcon = the.visibilityElement.querySelector('i:not(.d-none), .svg-icon:not(.d-none)');
        const hiddenIcon = the.visibilityElement.querySelector('i.d-none, .svg-icon.d-none');

        if (the.inputElement.getAttribute('type').toLowerCase() === 'password') {
            the.inputElement.setAttribute('type', 'text');
        } else {
            the.inputElement.setAttribute('type', 'password');
        }

        visibleIcon.classList.add('d-none');
        hiddenIcon.classList.remove('d-none');

        the.inputElement.focus();
    }

    const _reset = function () {
        the.score = 0;

        _highlight();
    };

    // Gets current password score
    var _getScore = function () {
        return the.score;
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('password-meter');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.check = function () {
        return _check();
    }

    the.getScore = function () {
        return _getScore();
    }

    the.reset = function () {
        return _reset();
    }

    the.destroy = function () {
        return _destroy();
    }
};

// Static methods
KTPasswordMeter.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('password-meter')) {
        return KTUtil.data(element).get('password-meter');
    } else {
        return null;
    }
}

// Create instances
KTPasswordMeter.createInstances = function (selector = '[data-kt-password-meter]') {
    // Get instances
    const elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            // Initialize instances
            new KTPasswordMeter(elements[i]);
        }
    }
}

// Global initialization
KTPasswordMeter.init = function () {
    KTPasswordMeter.createInstances();
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTPasswordMeter;
}
"use strict";

let KTScrollHandlersInitialized = false;

// Class definition
var KTScroll = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    const the = this;

    if (!element) {
        return;
    }

    // Default options
    const defaultOptions = {
        saveState: true
    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('scroll')) {
            the = KTUtil.data(element).get('scroll');
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);

        // Elements
        the.element = element;
        the.id = the.element.getAttribute('id');

        // Set initialized
        the.element.setAttribute('data-kt-scroll', 'true');

        // Update
        _update();

        // Bind Instance
        KTUtil.data(the.element).set('scroll', the);
    }

    const _setupHeight = function () {
        const heightType = _getHeightType();
        const height = _getHeight();

        // Set height
        if (height !== null && height.length > 0) {
            KTUtil.css(the.element, heightType, height);
        } else {
            KTUtil.css(the.element, heightType, '');
        }
    };

    const _setupState = function () {
        const namespace = _getStorageNamespace();

        if (_getOption('save-state') === true && the.id) {
            if (localStorage.getItem(namespace + the.id + 'st')) {
                const pos = parseInt(localStorage.getItem(namespace + the.id + 'st'));

                if (pos > 0) {
                    the.element.scroll({
                        top: pos,
                        behavior: 'instant'
                    });
                }
            }
        }
    };

    var _getStorageNamespace = function (postfix) {
        return document.body.hasAttribute("data-kt-name") ? document.body.getAttribute("data-kt-name") + "_" : "";
    }

    const _setupScrollHandler = function () {
        if (_getOption('save-state') === true && the.id) {
            the.element.addEventListener('scroll', _scrollHandler);
        } else {
            the.element.removeEventListener('scroll', _scrollHandler);
        }
    };

    const _destroyScrollHandler = function () {
        the.element.removeEventListener('scroll', _scrollHandler);
    };

    const _resetHeight = function () {
        KTUtil.css(the.element, _getHeightType(), '');
    };

    var _scrollHandler = function () {
        const namespace = _getStorageNamespace();
        localStorage.setItem(namespace + the.id + 'st', the.element.scrollTop);
    }

    var _update = function () {
        // Activate/deactivate
        if (_getOption('activate') === true || the.element.hasAttribute('data-kt-scroll-activate') === false) {
            _setupHeight();
            _setupStretchHeight();
            _setupScrollHandler();
            _setupState();
        } else {
            _resetHeight()
            _destroyScrollHandler();
        }
    }

    var _setupStretchHeight = function () {
        const stretch = _getOption('stretch');

        // Stretch
        if (stretch !== null) {
            const elements = document.querySelectorAll(stretch);

            if (elements && elements.length == 2) {
                const element1 = elements[0];
                const element2 = elements[1];
                const diff = _getElementHeight(element2) - _getElementHeight(element1);

                if (diff > 0) {
                    const height = parseInt(KTUtil.css(the.element, _getHeightType())) + diff;

                    KTUtil.css(the.element, _getHeightType(), String(height) + 'px');
                }
            }
        }
    }

    var _getHeight = function () {
        const height = _getOption(_getHeightType());

        if (height instanceof Function) {
            return height.call();
        } else if (height !== null && typeof height === 'string' && height.toLowerCase() === 'auto') {
            return _getAutoHeight();
        } else {
            return height;
        }
    }

    var _getAutoHeight = function () {
        let height = KTUtil.getViewPort().height;
        const dependencies = _getOption('dependencies');
        const wrappers = _getOption('wrappers');
        const offset = _getOption('offset');

        // Spacings
        height = height - _getElementSpacing(the.element);

        // Height dependencies
        //console.log('Q:' + JSON.stringify(dependencies));

        if (dependencies !== null) {
            var elements = document.querySelectorAll(dependencies);

            if (elements && elements.length > 0) {
                for (var i = 0, len = elements.length; i < len; i++) {
                    if (KTUtil.visible(elements[i]) === false) {
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
                for (var i = 0, len = elements.length; i < len; i++) {
                    if (KTUtil.visible(elements[i]) === false) {
                        continue;
                    }

                    height = height - _getElementSpacing(elements[i]);
                }
            }
        }

        // Custom offset
        if (offset !== null && typeof offset !== 'object') {
            height = height - parseInt(offset);
        }

        return String(height) + 'px';
    }

    var _getElementHeight = function (element) {
        let height = 0;

        if (element !== null) {
            height = height + parseInt(KTUtil.css(element, 'height'));
            height = height + parseInt(KTUtil.css(element, 'margin-top'));
            height = height + parseInt(KTUtil.css(element, 'margin-bottom'));

            if (KTUtil.css(element, 'border-top')) {
                height = height + parseInt(KTUtil.css(element, 'border-top'));
            }

            if (KTUtil.css(element, 'border-bottom')) {
                height = height + parseInt(KTUtil.css(element, 'border-bottom'));
            }
        }

        return height;
    }

    var _getElementSpacing = function (element) {
        let spacing = 0;

        if (element !== null) {
            spacing = spacing + parseInt(KTUtil.css(element, 'margin-top'));
            spacing = spacing + parseInt(KTUtil.css(element, 'margin-bottom'));
            spacing = spacing + parseInt(KTUtil.css(element, 'padding-top'));
            spacing = spacing + parseInt(KTUtil.css(element, 'padding-bottom'));

            if (KTUtil.css(element, 'border-top')) {
                spacing = spacing + parseInt(KTUtil.css(element, 'border-top'));
            }

            if (KTUtil.css(element, 'border-bottom')) {
                spacing = spacing + parseInt(KTUtil.css(element, 'border-bottom'));
            }
        }

        return spacing;
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-scroll-' + name) === true) {
            const attr = the.element.getAttribute('data-kt-scroll-' + name);

            let value = KTUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }

            return value;
        } else {
            const optionName = KTUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KTUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    }

    var _getHeightType = function () {
        if (_getOption('height')) {
            return 'height';
        }
        if (_getOption('min-height')) {
            return 'min-height';
        }
        if (_getOption('max-height')) {
            return 'max-height';
        }
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('scroll');
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    the.update = function () {
        return _update();
    }

    the.getHeight = function () {
        return _getHeight();
    }

    the.getElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }
};

// Static methods
KTScroll.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('scroll')) {
        return KTUtil.data(element).get('scroll');
    } else {
        return null;
    }
}

// Create instances
KTScroll.createInstances = function (selector = '[data-kt-scroll="true"]') {
    // Initialize Menus
    const elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            new KTScroll(elements[i]);
        }
    }
}

// Window resize handling
KTScroll.handleResize = function () {
    window.addEventListener('resize', function () {
        let timer;

        KTUtil.throttle(timer, function () {
            // Locate and update Offcanvas instances on window resize
            const elements = document.body.querySelectorAll('[data-kt-scroll="true"]');

            if (elements && elements.length > 0) {
                let i = 0;
                const len = elements.length;
                for (; i < len; i++) {
                    const scroll = KTScroll.getInstance(elements[i]);
                    if (scroll) {
                        scroll.update();
                    }
                }
            }
        }, 200);
    });
}

// Global initialization
KTScroll.init = function () {
    KTScroll.createInstances();

    if (KTScrollHandlersInitialized === false) {
        KTScroll.handleResize();

        KTScrollHandlersInitialized = true;
    }
};

// Webpack Support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTScroll;
}

"use strict";

// Class definition
var KTScrolltop = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default options
    const defaultOptions = {
        offset: 300,
        speed: 600
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('scrolltop')) {
            the = KTUtil.data(element).get('scrolltop');
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('scrolltop');
        the.element = element;

        // Set initialized
        the.element.setAttribute('data-kt-scrolltop', 'true');

        // Event Handlers
        _handlers();

        // Bind Instance
        KTUtil.data(the.element).set('scrolltop', the);
    }

    var _handlers = function () {
        let timer;

        window.addEventListener('scroll', function () {
            KTUtil.throttle(timer, function () {
                _scroll();
            }, 200);
        });

        KTUtil.addEvent(the.element, 'click', function (e) {
            e.preventDefault();

            _go();
        });
    }

    var _scroll = function () {
        const offset = parseInt(_getOption('offset'));

        const pos = KTUtil.getScrollTop(); // current vertical position

        if (pos > offset) {
            if (document.body.hasAttribute('data-kt-scrolltop') === false) {
                document.body.setAttribute('data-kt-scrolltop', 'on');
            }
        } else {
            if (document.body.hasAttribute('data-kt-scrolltop') === true) {
                document.body.removeAttribute('data-kt-scrolltop');
            }
        }
    }

    var _go = function () {
        const speed = parseInt(_getOption('speed'));

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        //KTUtil.scrollTop(0, speed);
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-scrolltop-' + name) === true) {
            const attr = the.element.getAttribute('data-kt-scrolltop-' + name);
            let value = KTUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }

            return value;
        } else {
            const optionName = KTUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KTUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('scrolltop');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.go = function () {
        return _go();
    }

    the.getElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }
};

// Static methods
KTScrolltop.getInstance = function (element) {
    if (element && KTUtil.data(element).has('scrolltop')) {
        return KTUtil.data(element).get('scrolltop');
    } else {
        return null;
    }
}

// Create instances
KTScrolltop.createInstances = function (selector = '[data-kt-scrolltop="true"]') {
    // Initialize Menus
    const elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            new KTScrolltop(elements[i]);
        }
    }
}

// Global initialization
KTScrolltop.init = function () {
    KTScrolltop.createInstances();
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTScrolltop;
}

"use strict";

// Class definition
const KTSearch = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    const the = this;

    if (!element) {
        return;
    }

    // Default Options
    const defaultOptions = {
        minLength: 3, // Miniam text lenght to query search
        keypress: true, // Enable search on keypress
        enter: true, // Enable search on enter key press
        layout: 'menu', // Use 'menu' or 'inline' layout options to display search results
        responsive: null, // Pass integer value or bootstrap compatible breakpoint key(sm,md,lg,xl,xxl) to enable reponsive form mode for device width below the breakpoint value
        showOnFocus: true // Always show menu on input focus
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    // Construct
    const _construct = function () {
        if (KTUtil.data(element).has('search') === true) {
            the = KTUtil.data(element).get('search');
        } else {
            _init();
        }
    };

    // Init
    let _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.processing = false;

        // Elements
        the.element = element;
        the.contentElement = _getElement('content');
        the.formElement = _getElement('form');
        the.inputElement = _getElement('input');
        the.spinnerElement = _getElement('spinner');
        the.clearElement = _getElement('clear');
        the.toggleElement = _getElement('toggle');
        the.submitElement = _getElement('submit');
        the.toolbarElement = _getElement('toolbar');

        the.resultsElement = _getElement('results');
        the.suggestionElement = _getElement('suggestion');
        the.emptyElement = _getElement('empty');

        // Set initialized
        the.element.setAttribute('data-kt-search', 'true');

        // Layout
        the.layout = _getOption('layout');

        // Menu
        if (the.layout === 'menu') {
            the.menuObject = new KTMenu(the.contentElement);
        } else {
            the.menuObject = null;
        }

        // Update
        _update();

        // Event Handlers
        _handlers();

        // Bind Instance
        KTUtil.data(the.element).set('search', the);
    }

    // Handlera
    var _handlers = function () {
        // Focus
        the.inputElement.addEventListener('focus', _focus);

        // Blur
        the.inputElement.addEventListener('blur', _blur);

        // Keypress
        if (_getOption('keypress') === true) {
            the.inputElement.addEventListener('input', _input);
        }

        // Submit
        if (the.submitElement) {
            the.submitElement.addEventListener('click', _search);
        }

        // Enter
        if (_getOption('enter') === true) {
            the.inputElement.addEventListener('keypress', _enter);
        }

        // Clear
        if (the.clearElement) {
            the.clearElement.addEventListener('click', _clear);
        }

        // Menu
        if (the.menuObject) {
            // Toggle menu
            if (the.toggleElement) {
                the.toggleElement.addEventListener('click', _show);

                the.menuObject.on('kt.menu.dropdown.show', function (item) {
                    if (KTUtil.visible(the.toggleElement)) {
                        the.toggleElement.classList.add('active');
                        the.toggleElement.classList.add('show');
                    }
                });

                the.menuObject.on('kt.menu.dropdown.hide', function (item) {
                    if (KTUtil.visible(the.toggleElement)) {
                        the.toggleElement.classList.remove('active');
                        the.toggleElement.classList.remove('show');
                    }
                });
            }

            the.menuObject.on('kt.menu.dropdown.shown', function () {
                the.inputElement.focus();
            });
        }

        // Window resize handling
        window.addEventListener('resize', function () {
            let timer;

            KTUtil.throttle(timer, function () {
                _update();
            }, 200);
        });
    }

    // Focus
    var _focus = function () {
        the.element.classList.add('focus');

        if (_getOption('show-on-focus') === true || the.inputElement.value.length >= minLength) {
            _show();
        }
    }

    // Blur
    var _blur = function () {
        the.element.classList.remove('focus');
    }

    // Enter
    var _enter = function (e) {
        const key = e.charCode || e.keyCode || 0;

        if (key == 13) {
            e.preventDefault();

            _search();
        }
    }

    // Input
    var _input = function () {
        if (_getOption('min-length')) {
            const minLength = parseInt(_getOption('min-length'));

            if (the.inputElement.value.length >= minLength) {
                _search();
            } else if (the.inputElement.value.length === 0) {
                _clear();
            }
        }
    }

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
            KTEventHandler.trigger(the.element, 'kt.search.process', the);
        }
    }

    // Complete
    const _complete = function () {
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
        if (KTEventHandler.trigger(the.element, 'kt.search.clear', the) === false) {
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
        if (_getOption('show-on-focus') === false) {
            _hide();
        }

        KTEventHandler.trigger(the.element, 'kt.search.cleared', the);
    }

    // Update
    var _update = function () {
        // Handle responsive form
        if (the.layout === 'menu') {
            const responsiveFormMode = _getResponsiveFormMode();

            if (responsiveFormMode === 'on' && the.contentElement.contains(the.formElement) === false) {
                the.contentElement.prepend(the.formElement);
                the.formElement.classList.remove('d-none');
            } else if (responsiveFormMode === 'off' && the.contentElement.contains(the.formElement) === true) {
                the.element.prepend(the.formElement);
                the.formElement.classList.add('d-none');
            }
        }
    }

    // Show menu
    var _show = function () {
        if (the.menuObject) {
            _update();

            the.menuObject.show(the.element);
        }
    }

    // Hide menu
    var _hide = function () {
        if (the.menuObject) {
            _update();

            the.menuObject.hide(the.element);
        }
    }

    // Get option
    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-search-' + name) === true) {
            const attr = the.element.getAttribute('data-kt-search-' + name);
            let value = KTUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }

            return value;
        } else {
            const optionName = KTUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KTUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    }

    // Get element
    var _getElement = function (name) {
        return the.element.querySelector('[data-kt-search-element="' + name + '"]');
    }

    // Check if responsive form mode is enabled
    var _getResponsiveFormMode = function () {
        const responsive = _getOption('responsive');
        const width = KTUtil.getViewPort().width;

        if (!responsive) {
            return null;
        }

        let breakpoint = KTUtil.getBreakpoint(responsive);

        if (!breakpoint) {
            breakpoint = parseInt(responsive);
        }

        if (width < breakpoint) {
            return "on";
        } else {
            return "off";
        }
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('search');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.show = function () {
        return _show();
    }

    the.hide = function () {
        return _hide();
    }

    the.update = function () {
        return _update();
    }

    the.search = function () {
        return _search();
    }

    the.complete = function () {
        return _complete();
    }

    the.clear = function () {
        return _clear();
    }

    the.isProcessing = function () {
        return the.processing;
    }

    the.getQuery = function () {
        return the.inputElement.value;
    }

    the.getMenu = function () {
        return the.menuObject;
    }

    the.getFormElement = function () {
        return the.formElement;
    }

    the.getInputElement = function () {
        return the.inputElement;
    }

    the.getContentElement = function () {
        return the.contentElement;
    }

    the.getElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }
};

// Static methods
KTSearch.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('search')) {
        return KTUtil.data(element).get('search');
    } else {
        return null;
    }
}

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTSearch;
}

"use strict";

// Class definition
const KTStepper = function (element, options) {
    //////////////////////////////
    // ** Private variables  ** //
    //////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    const defaultOptions = {
        startIndex: 1,
        animation: false,
        animationSpeed: '0.3s',
        animationNextClass: 'animate__animated animate__slideInRight animate__fast',
        animationPreviousClass: 'animate__animated animate__slideInLeft animate__fast'
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('stepper') === true) {
            the = KTUtil.data(element).get('stepper');
        } else {
            _init();
        }
    };

    var _init = function () {
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('stepper');

        the.element = element;

        // Set initialized
        the.element.setAttribute('data-kt-stepper', 'true');

        // Elements
        the.steps = KTUtil.findAll(the.element, '[data-kt-stepper-element="nav"]');
        the.btnNext = KTUtil.find(the.element, '[data-kt-stepper-action="next"]');
        the.btnPrevious = KTUtil.find(the.element, '[data-kt-stepper-action="previous"]');
        the.btnSubmit = KTUtil.find(the.element, '[data-kt-stepper-action="submit"]');

        // Variables
        the.totalStepsNumber = the.steps.length;
        the.passedStepIndex = 0;
        the.currentStepIndex = 1;
        the.clickedStepIndex = 0;

        // Set Current Step
        if (the.options.startIndex > 1) {
            _goTo(the.options.startIndex);
        }

        // Event listeners
        the.nextListener = function (e) {
            e.preventDefault();

            KTEventHandler.trigger(the.element, 'kt.stepper.next', the);
        };

        the.previousListener = function (e) {
            e.preventDefault();

            KTEventHandler.trigger(the.element, 'kt.stepper.previous', the);
        };

        the.stepListener = function (e) {
            e.preventDefault();

            if (the.steps && the.steps.length > 0) {
                let i = 0;
                const len = the.steps.length;
                for (; i < len; i++) {
                    if (the.steps[i] === this) {
                        the.clickedStepIndex = i + 1;

                        KTEventHandler.trigger(the.element, 'kt.stepper.click', the);

                        return;
                    }
                }
            }
        };

        // Event Handlers
        KTUtil.addEvent(the.btnNext, 'click', the.nextListener);

        KTUtil.addEvent(the.btnPrevious, 'click', the.previousListener);

        the.stepListenerId = KTUtil.on(the.element, '[data-kt-stepper-action="step"]', 'click', the.stepListener);

        // Bind Instance
        KTUtil.data(the.element).set('stepper', the);
    }

    var _goTo = function (index) {
        // Trigger "change" event
        KTEventHandler.trigger(the.element, 'kt.stepper.change', the);

        // Skip if this step is already shown
        if (index === the.currentStepIndex || index > the.totalStepsNumber || index < 0) {
            return;
        }

        // Validate step number
        index = parseInt(index);

        // Set current step
        the.passedStepIndex = the.currentStepIndex;
        the.currentStepIndex = index;

        // Refresh elements
        _refreshUI();

        // Trigger "changed" event
        KTEventHandler.trigger(the.element, 'kt.stepper.changed', the);

        return the;
    }

    const _goNext = function () {
        return _goTo(_getNextStepIndex());
    };

    const _goPrevious = function () {
        return _goTo(_getPreviousStepIndex());
    };

    const _goLast = function () {
        return _goTo(_getLastStepIndex());
    };

    const _goFirst = function () {
        return _goTo(_getFirstStepIndex());
    };

    var _refreshUI = function () {
        let state = '';

        if (_isLastStep()) {
            state = 'last';
        } else if (_isFirstStep()) {
            state = 'first';
        } else {
            state = 'between';
        }

        // Set state class
        KTUtil.removeClass(the.element, 'last');
        KTUtil.removeClass(the.element, 'first');
        KTUtil.removeClass(the.element, 'between');

        KTUtil.addClass(the.element, state);

        // Step Items
        const elements = KTUtil.findAll(the.element, '[data-kt-stepper-element="nav"], [data-kt-stepper-element="content"], [data-kt-stepper-element="info"]');

        if (elements && elements.length > 0) {
            let i = 0;
            const len = elements.length;
            for (; i < len; i++) {
                const element = elements[i];
                const index = KTUtil.index(element) + 1;

                KTUtil.removeClass(element, 'current');
                KTUtil.removeClass(element, 'completed');
                KTUtil.removeClass(element, 'pending');

                if (index == the.currentStepIndex) {
                    KTUtil.addClass(element, 'current');

                    if (the.options.animation !== false && element.getAttribute('data-kt-stepper-element') == 'content') {
                        KTUtil.css(element, 'animationDuration', the.options.animationSpeed);

                        const animation = _getStepDirection(the.passedStepIndex) === 'previous' ? the.options.animationPreviousClass : the.options.animationNextClass;
                        KTUtil.animateClass(element, animation);
                    }
                } else {
                    if (index < the.currentStepIndex) {
                        KTUtil.addClass(element, 'completed');
                    } else {
                        KTUtil.addClass(element, 'pending');
                    }
                }
            }
        }
    }

    var _isLastStep = function () {
        return the.currentStepIndex === the.totalStepsNumber;
    }

    var _isFirstStep = function () {
        return the.currentStepIndex === 1;
    }

    const _isBetweenStep = function () {
        return _isLastStep() === false && _isFirstStep() === false;
    };

    var _getNextStepIndex = function () {
        if (the.totalStepsNumber >= (the.currentStepIndex + 1)) {
            return the.currentStepIndex + 1;
        } else {
            return the.totalStepsNumber;
        }
    }

    var _getPreviousStepIndex = function () {
        if ((the.currentStepIndex - 1) > 1) {
            return the.currentStepIndex - 1;
        } else {
            return 1;
        }
    }

    var _getFirstStepIndex = function () {
        return 1;
    }

    var _getLastStepIndex = function () {
        return the.totalStepsNumber;
    }

    const _getTotalStepsNumber = function () {
        return the.totalStepsNumber;
    };

    var _getStepDirection = function (index) {
        if (index > the.currentStepIndex) {
            return 'next';
        } else {
            return 'previous';
        }
    }

    const _getStepContent = function (index) {
        const content = KTUtil.findAll(the.element, '[data-kt-stepper-element="content"]');

        if (content[index - 1]) {
            return content[index - 1];
        } else {
            return false;
        }
    };

    const _destroy = function () {
        // Event Handlers
        KTUtil.removeEvent(the.btnNext, 'click', the.nextListener);

        KTUtil.removeEvent(the.btnPrevious, 'click', the.previousListener);

        KTUtil.off(the.element, 'click', the.stepListenerId);

        KTUtil.data(the.element).remove('stepper');
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.getElement = function (index) {
        return the.element;
    }

    the.goTo = function (index) {
        return _goTo(index);
    }

    the.goPrevious = function () {
        return _goPrevious();
    }

    the.goNext = function () {
        return _goNext();
    }

    the.goFirst = function () {
        return _goFirst();
    }

    the.goLast = function () {
        return _goLast();
    }

    the.getCurrentStepIndex = function () {
        return the.currentStepIndex;
    }

    the.getNextStepIndex = function () {
        return _getNextStepIndex();
    }

    the.getPassedStepIndex = function () {
        return the.passedStepIndex;
    }

    the.getClickedStepIndex = function () {
        return the.clickedStepIndex;
    }

    the.getPreviousStepIndex = function () {
        return _getPreviousStepIndex();
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTStepper.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('stepper')) {
        return KTUtil.data(element).get('stepper');
    } else {
        return null;
    }
}

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTStepper;
}

"use strict";

let KTStickyHandlersInitialized = false;

// Class definition
var KTSticky = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    const defaultOptions = {
        offset: 200,
        reverse: false,
        release: null,
        animation: true,
        animationSpeed: '0.3s',
        animationClass: 'animation-slide-in-down'
    };
    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('sticky') === true) {
            the = KTUtil.data(element).get('sticky');
        } else {
            _init();
        }
    };

    var _init = function () {
        the.element = element;
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('sticky');
        the.name = the.element.getAttribute('data-kt-sticky-name');
        the.attributeName = 'data-kt-sticky-' + the.name;
        the.attributeName2 = 'data-kt-' + the.name;
        the.eventTriggerState = true;
        the.lastScrollTop = 0;
        the.scrollHandler;

        // Set initialized
        the.element.setAttribute('data-kt-sticky', 'true');

        // Event Handlers
        window.addEventListener('scroll', _scroll);

        // Initial Launch
        _scroll();

        // Bind Instance
        KTUtil.data(the.element).set('sticky', the);
    }

    var _scroll = function (e) {
        let offset = _getOption('offset');
        let release = _getOption('release');
        const reverse = _getOption('reverse');
        let st;
        let attrName;
        let diff;

        // Exit if false
        if (offset === false) {
            return;
        }

        offset = parseInt(offset);
        release = release ? document.querySelector(release) : null;

        st = KTUtil.getScrollTop();
        diff = document.documentElement.scrollHeight - window.innerHeight - KTUtil.getScrollTop();

        const proceed = (!release || (release.offsetTop - release.clientHeight) > st);

        if (reverse === true) { // Release on reverse scroll mode
            if (st > offset && proceed) {
                if (document.body.hasAttribute(the.attributeName) === false) {

                    if (_enable() === false) {
                        return;
                    }

                    document.body.setAttribute(the.attributeName, 'on');
                    document.body.setAttribute(the.attributeName2, 'on');
                    the.element.setAttribute("data-kt-sticky-enabled", "true");
                }

                if (the.eventTriggerState === true) {
                    KTEventHandler.trigger(the.element, 'kt.sticky.on', the);
                    KTEventHandler.trigger(the.element, 'kt.sticky.change', the);

                    the.eventTriggerState = false;
                }
            } else { // Back scroll mode
                if (document.body.hasAttribute(the.attributeName) === true) {
                    _disable();
                    document.body.removeAttribute(the.attributeName);
                    document.body.removeAttribute(the.attributeName2);
                    the.element.removeAttribute("data-kt-sticky-enabled");
                }

                if (the.eventTriggerState === false) {
                    KTEventHandler.trigger(the.element, 'kt.sticky.off', the);
                    KTEventHandler.trigger(the.element, 'kt.sticky.change', the);
                    the.eventTriggerState = true;
                }
            }

            the.lastScrollTop = st;
        } else { // Classic scroll mode
            if (st > offset && proceed) {
                if (document.body.hasAttribute(the.attributeName) === false) {

                    if (_enable() === false) {
                        return;
                    }

                    document.body.setAttribute(the.attributeName, 'on');
                    document.body.setAttribute(the.attributeName2, 'on');
                    the.element.setAttribute("data-kt-sticky-enabled", "true");
                }

                if (the.eventTriggerState === true) {
                    KTEventHandler.trigger(the.element, 'kt.sticky.on', the);
                    KTEventHandler.trigger(the.element, 'kt.sticky.change', the);
                    the.eventTriggerState = false;
                }
            } else { // back scroll mode
                if (document.body.hasAttribute(the.attributeName) === true) {
                    _disable();
                    document.body.removeAttribute(the.attributeName);
                    document.body.removeAttribute(the.attributeName2);
                    the.element.removeAttribute("data-kt-sticky-enabled");
                }

                if (the.eventTriggerState === false) {
                    KTEventHandler.trigger(the.element, 'kt.sticky.off', the);
                    KTEventHandler.trigger(the.element, 'kt.sticky.change', the);
                    the.eventTriggerState = true;
                }
            }
        }

        if (release) {
            if (release.offsetTop - release.clientHeight > st) {
                the.element.setAttribute('data-kt-sticky-released', 'true');
            } else {
                the.element.removeAttribute('data-kt-sticky-released');
            }
        }
    }

    var _enable = function (update) {
        let top = _getOption('top');
        top = top ? parseInt(top) : 0;

        const left = _getOption('left');
        const right = _getOption('right');
        let width = _getOption('width');
        const zindex = _getOption('zindex');
        const dependencies = _getOption('dependencies');
        const classes = _getOption('class');

        const height = _calculateHeight();
        let heightOffset = _getOption('height-offset');
        heightOffset = heightOffset ? parseInt(heightOffset) : 0;

        if (height + heightOffset + top > KTUtil.getViewPort().height) {
            return false;
        }

        if (update !== true && _getOption('animation') === true) {
            KTUtil.css(the.element, 'animationDuration', _getOption('animationSpeed'));
            KTUtil.animateClass(the.element, 'animation ' + _getOption('animationClass'));
        }

        if (classes !== null) {
            KTUtil.addClass(the.element, classes);
        }

        if (zindex !== null) {
            KTUtil.css(the.element, 'z-index', zindex);
            KTUtil.css(the.element, 'position', 'fixed');
        }

        if (top >= 0) {
            KTUtil.css(the.element, 'top', String(top) + 'px');
        }

        if (width !== null) {
            if (width['target']) {
                const targetElement = document.querySelector(width['target']);
                if (targetElement) {
                    width = KTUtil.css(targetElement, 'width');
                }
            }

            KTUtil.css(the.element, 'width', width);
        }

        if (left !== null) {
            if (String(left).toLowerCase() === 'auto') {
                const offsetLeft = KTUtil.offset(the.element).left;

                if (offsetLeft >= 0) {
                    KTUtil.css(the.element, 'left', String(offsetLeft) + 'px');
                }
            } else {
                KTUtil.css(the.element, 'left', left);
            }
        }

        if (right !== null) {
            KTUtil.css(the.element, 'right', right);
        }

        // Height dependencies
        if (dependencies !== null) {
            const dependencyElements = document.querySelectorAll(dependencies);

            if (dependencyElements && dependencyElements.length > 0) {
                let i = 0;
                const len = dependencyElements.length;
                for (; i < len; i++) {
                    KTUtil.css(dependencyElements[i], 'padding-top', String(height) + 'px');
                }
            }
        }
    }

    var _disable = function () {
        KTUtil.css(the.element, 'top', '');
        KTUtil.css(the.element, 'width', '');
        KTUtil.css(the.element, 'left', '');
        KTUtil.css(the.element, 'right', '');
        KTUtil.css(the.element, 'z-index', '');
        KTUtil.css(the.element, 'position', '');

        const dependencies = _getOption('dependencies');
        const classes = _getOption('class');

        if (classes !== null) {
            KTUtil.removeClass(the.element, classes);
        }

        // Height dependencies
        if (dependencies !== null) {
            const dependencyElements = document.querySelectorAll(dependencies);

            if (dependencyElements && dependencyElements.length > 0) {
                let i = 0;
                const len = dependencyElements.length;
                for (; i < len; i++) {
                    KTUtil.css(dependencyElements[i], 'padding-top', '');
                }
            }
        }
    }

    const _check = function () {

    };

    var _calculateHeight = function () {
        let height = parseFloat(KTUtil.css(the.element, 'height'));

        height = height + parseFloat(KTUtil.css(the.element, 'margin-top'));
        height = height + parseFloat(KTUtil.css(the.element, 'margin-bottom'));

        if (KTUtil.css(element, 'border-top')) {
            height = height + parseFloat(KTUtil.css(the.element, 'border-top'));
        }

        if (KTUtil.css(element, 'border-bottom')) {
            height = height + parseFloat(KTUtil.css(the.element, 'border-bottom'));
        }

        return height;
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-sticky-' + name) === true) {
            const attr = the.element.getAttribute('data-kt-sticky-' + name);
            let value = KTUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }

            return value;
        } else {
            const optionName = KTUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KTUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    }

    const _destroy = function () {
        window.removeEventListener('scroll', _scroll);
        KTUtil.data(the.element).remove('sticky');
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
            document.body.setAttribute(the.attributeName, 'on');
            document.body.setAttribute(the.attributeName2, 'on');
        }
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTSticky.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('sticky')) {
        return KTUtil.data(element).get('sticky');
    } else {
        return null;
    }
}

// Create instances
KTSticky.createInstances = function (selector = '[data-kt-sticky="true"]') {
    // Initialize Menus
    const elements = document.body.querySelectorAll(selector);
    let sticky;

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            sticky = new KTSticky(elements[i]);
        }
    }
}

// Window resize handler
KTSticky.handleResize = function () {
    window.addEventListener('resize', function () {
        let timer;

        KTUtil.throttle(timer, function () {
            // Locate and update Offcanvas instances on window resize
            const elements = document.body.querySelectorAll('[data-kt-sticky="true"]');

            if (elements && elements.length > 0) {
                let i = 0;
                const len = elements.length;
                for (; i < len; i++) {
                    const sticky = KTSticky.getInstance(elements[i]);
                    if (sticky) {
                        sticky.update();
                    }
                }
            }
        }, 200);
    });
}

// Global initialization
KTSticky.init = function () {
    KTSticky.createInstances();

    if (KTStickyHandlersInitialized === false) {
        KTSticky.handleResize();
        KTStickyHandlersInitialized = true;
    }
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTSticky;
}

"use strict";

let KTSwapperHandlersInitialized = false;

// Class definition
var KTSwapper = function (element, options) {
    ////////////////////////////
    // ** Private Variables  ** //
    ////////////////////////////
    const the = this;

    if (typeof element === "undefined" || element === null) {
        return;
    }

    // Default Options
    const defaultOptions = {
        mode: 'append'
    };

    ////////////////////////////
    // ** Private Methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('swapper') === true) {
            the = KTUtil.data(element).get('swapper');
        } else {
            _init();
        }
    };

    var _init = function () {
        the.element = element;
        the.options = KTUtil.deepExtend({}, defaultOptions, options);

        // Set initialized
        the.element.setAttribute('data-kt-swapper', 'true');

        // Initial update
        _update();

        // Bind Instance
        KTUtil.data(the.element).set('swapper', the);
    }

    var _update = function (e) {
        const parentSelector = _getOption('parent');

        const mode = _getOption('mode');
        const parentElement = parentSelector ? document.querySelector(parentSelector) : null;


        if (parentElement && element.parentNode !== parentElement) {
            if (mode === 'prepend') {
                parentElement.prepend(element);
            } else if (mode === 'append') {
                parentElement.append(element);
            }
        }
    }

    var _getOption = function (name) {
        if (the.element.hasAttribute('data-kt-swapper-' + name) === true) {
            const attr = the.element.getAttribute('data-kt-swapper-' + name);
            let value = KTUtil.getResponsiveValue(attr);

            if (value !== null && String(value) === 'true') {
                value = true;
            } else if (value !== null && String(value) === 'false') {
                value = false;
            }

            return value;
        } else {
            const optionName = KTUtil.snakeToCamel(name);

            if (the.options[optionName]) {
                return KTUtil.getResponsiveValue(the.options[optionName]);
            } else {
                return null;
            }
        }
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('swapper');
    };

    // Construct Class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Methods
    the.update = function () {
        _update();
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTSwapper.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('swapper')) {
        return KTUtil.data(element).get('swapper');
    } else {
        return null;
    }
}

// Create instances
KTSwapper.createInstances = function (selector = '[data-kt-swapper="true"]') {
    // Initialize Menus
    const elements = document.querySelectorAll(selector);
    let swapper;

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            swapper = new KTSwapper(elements[i]);
        }
    }
}

// Window resize handler
KTSwapper.handleResize = function () {
    window.addEventListener('resize', function () {
        let timer;

        KTUtil.throttle(timer, function () {
            // Locate and update Offcanvas instances on window resize
            const elements = document.querySelectorAll('[data-kt-swapper="true"]');

            if (elements && elements.length > 0) {
                let i = 0;
                const len = elements.length;
                for (; i < len; i++) {
                    const swapper = KTSwapper.getInstance(elements[i]);
                    if (swapper) {
                        swapper.update();
                    }
                }
            }
        }, 200);
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
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTSwapper;
}

"use strict";

// Class definition
var KTToggle = function (element, options) {
    ////////////////////////////
    // ** Private variables  ** //
    ////////////////////////////
    const the = this;

    if (!element) {
        return;
    }

    // Default Options
    const defaultOptions = {
        saveState: true
    };

    ////////////////////////////
    // ** Private methods  ** //
    ////////////////////////////

    const _construct = function () {
        if (KTUtil.data(element).has('toggle') === true) {
            the = KTUtil.data(element).get('toggle');
        } else {
            _init();
        }
    };

    var _init = function () {
        // Variables
        the.options = KTUtil.deepExtend({}, defaultOptions, options);
        the.uid = KTUtil.getUniqueId('toggle');

        // Elements
        the.element = element;

        the.target = document.querySelector(the.element.getAttribute('data-kt-toggle-target')) ? document.querySelector(the.element.getAttribute('data-kt-toggle-target')) : the.element;
        the.state = the.element.hasAttribute('data-kt-toggle-state') ? the.element.getAttribute('data-kt-toggle-state') : '';
        the.mode = the.element.hasAttribute('data-kt-toggle-mode') ? the.element.getAttribute('data-kt-toggle-mode') : '';
        the.attribute = 'data-kt-' + the.element.getAttribute('data-kt-toggle-name');

        // Event Handlers
        _handlers();

        // Bind Instance
        KTUtil.data(the.element).set('toggle', the);
    }

    var _handlers = function () {
        KTUtil.addEvent(the.element, 'click', function (e) {
            e.preventDefault();

            if (the.mode !== '') {
                if (the.mode === 'off' && _isEnabled() === false) {
                    _toggle();
                } else if (the.mode === 'on' && _isEnabled() === true) {
                    _toggle();
                }
            } else {
                _toggle();
            }
        });
    }

    // Event handlers
    var _toggle = function () {
        // Trigger "after.toggle" event
        KTEventHandler.trigger(the.element, 'kt.toggle.change', the);

        if (_isEnabled()) {
            _disable();
        } else {
            _enable();
        }

        // Trigger "before.toggle" event
        KTEventHandler.trigger(the.element, 'kt.toggle.changed', the);

        return the;
    }

    var _enable = function () {
        if (_isEnabled() === true) {
            return;
        }

        KTEventHandler.trigger(the.element, 'kt.toggle.enable', the);

        the.target.setAttribute(the.attribute, 'on');

        if (the.state.length > 0) {
            the.element.classList.add(the.state);
        }

        if (typeof KTCookie !== 'undefined' && the.options.saveState === true) {
            KTCookie.set(the.attribute, 'on');
        }

        KTEventHandler.trigger(the.element, 'kt.toggle.enabled', the);

        return the;
    }

    var _disable = function () {
        if (_isEnabled() === false) {
            return;
        }

        KTEventHandler.trigger(the.element, 'kt.toggle.disable', the);

        the.target.removeAttribute(the.attribute);

        if (the.state.length > 0) {
            the.element.classList.remove(the.state);
        }

        if (typeof KTCookie !== 'undefined' && the.options.saveState === true) {
            KTCookie.remove(the.attribute);
        }

        KTEventHandler.trigger(the.element, 'kt.toggle.disabled', the);

        return the;
    }

    var _isEnabled = function () {
        return (String(the.target.getAttribute(the.attribute)).toLowerCase() === 'on');
    }

    const _destroy = function () {
        KTUtil.data(the.element).remove('toggle');
    };

    // Construct class
    _construct();

    ///////////////////////
    // ** Public API  ** //
    ///////////////////////

    // Plugin API
    the.toggle = function () {
        return _toggle();
    }

    the.enable = function () {
        return _enable();
    }

    the.disable = function () {
        return _disable();
    }

    the.isEnabled = function () {
        return _isEnabled();
    }

    the.goElement = function () {
        return the.element;
    }

    the.destroy = function () {
        return _destroy();
    }

    // Event API
    the.on = function (name, handler) {
        return KTEventHandler.on(the.element, name, handler);
    }

    the.one = function (name, handler) {
        return KTEventHandler.one(the.element, name, handler);
    }

    the.off = function (name, handlerId) {
        return KTEventHandler.off(the.element, name, handlerId);
    }

    the.trigger = function (name, event) {
        return KTEventHandler.trigger(the.element, name, event, the, event);
    }
};

// Static methods
KTToggle.getInstance = function (element) {
    if (element !== null && KTUtil.data(element).has('toggle')) {
        return KTUtil.data(element).get('toggle');
    } else {
        return null;
    }
}

// Create instances
KTToggle.createInstances = function (selector = '[data-kt-toggle]') {
    // Get instances
    const elements = document.body.querySelectorAll(selector);

    if (elements && elements.length > 0) {
        let i = 0;
        const len = elements.length;
        for (; i < len; i++) {
            // Initialize instances
            new KTToggle(elements[i]);
        }
    }
}

// Global initialization
KTToggle.init = function () {
    KTToggle.createInstances();
};

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTToggle;
}
"use strict";

/**
 * @class KTUtil  base utilize class that privides helper functions
 */

// Polyfills

// Element.matches() polyfill
if (!Element.prototype.matches) {
    Element.prototype.matches = function (s) {
        const matches = (this.document || this.ownerDocument).querySelectorAll(s);
        let i = matches.length;
        while (--i >= 0 && matches.item(i) !== this) {}
        return i > -1;
    };
}

/**
 * Element.closest() polyfill
 * https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
 */
if (!Element.prototype.closest) {
    Element.prototype.closest = function (s) {
        const el = this;
        let ancestor = this;
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
    for (let i = 0; i < elem.length; i++) {
        if (!window[elem[i]] || 'remove' in window[elem[i]].prototype) continue;
        window[elem[i]].prototype.remove = function () {
            this.parentNode.removeChild(this);
        };
    }
})(['Element', 'CharacterData', 'DocumentType']);


//
// requestAnimationFrame polyfill by Erik MÃ¶ller.
//  With fixes from Paul Irish and Tino Zijdel
//
//  http://paulirish.com/2011/requestanimationframe-for-smart-animating/
//  http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating
//
//  MIT license
//
(function () {
    let lastTime = 0;
    const vendors = ['webkit', 'moz'];
    for (let x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame =
            window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function (callback) {
            const currTime = new Date().getTime();
            const timeToCall = Math.max(0, 16 - (currTime - lastTime));
            const id = window.setTimeout(function () {
                callback(currTime + timeToCall);
            }, timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function (id) {
            clearTimeout(id);
        };
}());

// Source: https://github.com/jserz/js_piece/blob/master/DOM/ParentNode/prepend()/prepend().md
(function (arr) {
    arr.forEach(function (item) {
        if (item.hasOwnProperty('prepend')) {
            return;
        }
        Object.defineProperty(item, 'prepend', {
            configurable: true,
            enumerable: true,
            writable: true,
            value: function prepend() {
                const argArr = Array.prototype.slice.call(arguments),
                    docFrag = document.createDocumentFragment();

                argArr.forEach(function (argItem) {
                    const isNode = argItem instanceof Node;
                    docFrag.appendChild(isNode ? argItem : document.createTextNode(String(argItem)));
                });

                this.insertBefore(docFrag, this.firstChild);
            }
        });
    });
})([Element.prototype, Document.prototype, DocumentFragment.prototype]);

// getAttributeNames
if (Element.prototype.getAttributeNames == undefined) {
    Element.prototype.getAttributeNames = function () {
        const attributes = this.attributes;
        const length = attributes.length;
        const result = new Array(length);
        for (let i = 0; i < length; i++) {
            result[i] = attributes[i].name;
        }
        return result;
    };
}

// Global variables
window.KTUtilElementDataStore = {};
window.KTUtilElementDataStoreID = 0;
window.KTUtilDelegatedEventHandlers = {};

var KTUtil = function () {
    const resizeHandlers = [];

    /**
     * Handle window resize event with some
     * delay to attach event handlers upon resize complete
     */
    const _windowResizeHandler = function () {
        const _runResizeHandlers = function () {
            // reinitialize other subscribed elements
            for (let i = 0; i < resizeHandlers.length; i++) {
                const each = resizeHandlers[i];
                each.call();
            }
        };

        let timer;

        window.addEventListener('resize', function () {
            KTUtil.throttle(timer, function () {
                _runResizeHandlers();
            }, 200);
        });
    };

    return {
        /**
         * Class main initializer.
         * @param {object} settings.
         * @returns null
         */
        //main function to initiate the theme
        init: function (settings) {
            _windowResizeHandler();
        },

        /**
         * Adds window resize event handler.
         * @param {function} callback function.
         */
        addResizeHandler: function (callback) {
            resizeHandlers.push(callback);
        },

        /**
         * Removes window resize event handler.
         * @param {function} callback function.
         */
        removeResizeHandler: function (callback) {
            for (let i = 0; i < resizeHandlers.length; i++) {
                if (callback === resizeHandlers[i]) {
                    delete resizeHandlers[i];
                }
            }
        },

        /**
         * Trigger window resize handlers.
         */
        runResizeHandlers: function () {
            _runResizeHandlers();
        },

        resize: function () {
            if (typeof (Event) === 'function') {
                // modern browsers
                window.dispatchEvent(new Event('resize'));
            } else {
                // for IE and other old browsers
                // causes deprecation warning on modern browsers
                const evt = window.document.createEvent('UIEvents');
                evt.initUIEvent('resize', true, false, window, 0);
                window.dispatchEvent(evt);
            }
        },

        /**
         * Get GET parameter value from URL.
         * @param {string} paramName Parameter name.
         * @returns {string}
         */
        getURLParam: function (paramName) {
            const searchString = window.location.search.substring(1);
            let i, val;
            const params = searchString.split("&");

            for (i = 0; i < params.length; i++) {
                val = params[i].split("=");
                if (val[0] == paramName) {
                    return unescape(val[1]);
                }
            }

            return null;
        },

        /**
         * Checks whether current device is mobile touch.
         * @returns {boolean}
         */
        isMobileDevice: function () {
            let test = (this.getViewPort().width < this.getBreakpoint('lg') ? true : false);

            if (test === false) {
                // For use within normal web clients
                test = navigator.userAgent.match(/iPad/i) != null;
            }

            return test;
        },

        /**
         * Checks whether current device is desktop.
         * @returns {boolean}
         */
        isDesktopDevice: function () {
            return KTUtil.isMobileDevice() ? false : true;
        },

        /**
         * Gets browser window viewport size. Ref:
         * http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
         * @returns {object}
         */
        getViewPort: function () {
            let e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }

            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        },

        /**
         * Checks whether given device mode is currently activated.
         * @param {string} mode Responsive mode name(e.g: desktop,
         *     desktop-and-tablet, tablet, tablet-and-mobile, mobile)
         * @returns {boolean}
         */
        isBreakpointUp: function (mode) {
            const width = this.getViewPort().width;
            const breakpoint = this.getBreakpoint(mode);

            return (width >= breakpoint);
        },

        isBreakpointDown: function (mode) {
            const width = this.getViewPort().width;
            const breakpoint = this.getBreakpoint(mode);

            return (width < breakpoint);
        },

        getViewportWidth: function () {
            return this.getViewPort().width;
        },

        /**
         * Generates unique ID for give prefix.
         * @param {string} prefix Prefix for generated ID
         * @returns {boolean}
         */
        getUniqueId: function (prefix) {
            return prefix + Math.floor(Math.random() * (new Date()).getTime());
        },

        /**
         * Gets window width for give breakpoint mode.
         * @param {string} mode Responsive mode name(e.g: xl, lg, md, sm)
         * @returns {number}
         */
        getBreakpoint: function (breakpoint) {
            let value = this.getCssVariableValue('--bs-' + breakpoint);

            if (value) {
                value = parseInt(value.trim());
            }

            return value;
        },

        /**
         * Checks whether object has property matchs given key path.
         * @param {object} obj Object contains values paired with given key path
         * @param {string} keys Keys path seperated with dots
         * @returns {object}
         */
        isset: function (obj, keys) {
            let stone;

            keys = keys || '';

            if (keys.indexOf('[') !== -1) {
                throw new Error('Unsupported object path notation.');
            }

            keys = keys.split('.');

            do {
                if (obj === undefined) {
                    return false;
                }

                stone = keys.shift();

                if (!obj.hasOwnProperty(stone)) {
                    return false;
                }

                obj = obj[stone];

            } while (keys.length);

            return true;
        },

        /**
         * Gets highest z-index of the given element parents
         * @param {object} el jQuery element object
         * @returns {number}
         */
        getHighestZindex: function (el) {
            let position, value;

            while (el && el !== document) {
                // Ignore z-index if position is set to a value where z-index is ignored by the browser
                // This makes behavior of this function consistent across browsers
                // WebKit always returns auto if the element is positioned
                position = KTUtil.css(el, 'position');

                if (position === "absolute" || position === "relative" || position === "fixed") {
                    // IE returns 0 when zIndex is not specified
                    // other browsers return a string
                    // we ignore the case of nested elements with an explicit value of 0
                    // <div style="z-index: -10;"><div style="z-index: 0;"></div></div>
                    value = parseInt(KTUtil.css(el, 'z-index'));

                    if (!isNaN(value) && value !== 0) {
                        return value;
                    }
                }

                el = el.parentNode;
            }

            return 1;
        },

        /**
         * Checks whether the element has any parent with fixed positionfreg
         * @param {object} el jQuery element object
         * @returns {boolean}
         */
        hasFixedPositionedParent: function (el) {
            let position;

            while (el && el !== document) {
                position = KTUtil.css(el, 'position');

                if (position === "fixed") {
                    return true;
                }

                el = el.parentNode;
            }

            return false;
        },

        /**
         * Simulates delay
         */
        sleep: function (milliseconds) {
            const start = new Date().getTime();
            for (let i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds) {
                    break;
                }
            }
        },

        /**
         * Gets randomly generated integer value within given min and max range
         * @param {number} min Range start value
         * @param {number} max Range end value
         * @returns {number}
         */
        getRandomInt: function (min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        },

        /**
         * Checks whether Angular library is included
         * @returns {boolean}
         */
        isAngularVersion: function () {
            return window.Zone !== undefined ? true : false;
        },

        // Deep extend:  $.extend(true, {}, objA, objB);
        deepExtend: function (out) {
            out = out || {};

            for (let i = 1; i < arguments.length; i++) {
                const obj = arguments[i];
                if (!obj) continue;

                for (let key in obj) {
                    if (!obj.hasOwnProperty(key)) {
                        continue;
                    }

                    // based on https://javascriptweblog.wordpress.com/2011/08/08/fixing-the-javascript-typeof-operator/
                    if (Object.prototype.toString.call(obj[key]) === '[object Object]') {
                        out[key] = KTUtil.deepExtend(out[key], obj[key]);
                        continue;
                    }

                    out[key] = obj[key];
                }
            }

            return out;
        },

        // extend:  $.extend({}, objA, objB);
        extend: function (out) {
            out = out || {};

            for (let i = 1; i < arguments.length; i++) {
                if (!arguments[i])
                    continue;

                for (let key in arguments[i]) {
                    if (arguments[i].hasOwnProperty(key))
                        out[key] = arguments[i][key];
                }
            }

            return out;
        },

        getBody: function () {
            return document.getElementsByTagName('body')[0];
        },

        /**
         * Checks whether the element has given classes
         * @param {object} el jQuery element object
         * @param {string} Classes string
         * @returns {boolean}
         */
        hasClasses: function (el, classes) {
            if (!el) {
                return;
            }

            const classesArr = classes.split(" ");

            for (let i = 0; i < classesArr.length; i++) {
                if (KTUtil.hasClass(el, KTUtil.trim(classesArr[i])) == false) {
                    return false;
                }
            }

            return true;
        },

        hasClass: function (el, className) {
            if (!el) {
                return;
            }

            return el.classList ? el.classList.contains(className) : new RegExp('\\b' + className + '\\b').test(el.className);
        },

        addClass: function (el, className) {
            if (!el || typeof className === 'undefined') {
                return;
            }

            const classNames = className.split(' ');

            if (el.classList) {
                for (let i = 0; i < classNames.length; i++) {
                    if (classNames[i] && classNames[i].length > 0) {
                        el.classList.add(KTUtil.trim(classNames[i]));
                    }
                }
            } else if (!KTUtil.hasClass(el, className)) {
                for (let x = 0; x < classNames.length; x++) {
                    el.className += ' ' + KTUtil.trim(classNames[x]);
                }
            }
        },

        removeClass: function (el, className) {
            if (!el || typeof className === 'undefined') {
                return;
            }

            const classNames = className.split(' ');

            if (el.classList) {
                for (let i = 0; i < classNames.length; i++) {
                    el.classList.remove(KTUtil.trim(classNames[i]));
                }
            } else if (KTUtil.hasClass(el, className)) {
                for (let x = 0; x < classNames.length; x++) {
                    el.className = el.className.replace(new RegExp('\\b' + KTUtil.trim(classNames[x]) + '\\b', 'g'), '');
                }
            }
        },

        triggerCustomEvent: function (el, eventName, data) {
            let event;
            if (window.CustomEvent) {
                event = new CustomEvent(eventName, {
                    detail: data
                });
            } else {
                event = document.createEvent('CustomEvent');
                event.initCustomEvent(eventName, true, true, data);
            }

            el.dispatchEvent(event);
        },

        triggerEvent: function (node, eventName) {
            // Make sure we use the ownerDocument from the provided node to avoid cross-window problems
            let doc;

            if (node.ownerDocument) {
                doc = node.ownerDocument;
            } else if (node.nodeType == 9) {
                // the node may be the document itself, nodeType 9 = DOCUMENT_NODE
                doc = node;
            } else {
                throw new Error("Invalid node passed to fireEvent: " + node.id);
            }

            if (node.dispatchEvent) {
                // Gecko-style approach (now the standard) takes more work
                let eventClass = "";

                // Different events have different event classes.
                // If this switch statement can't map an eventName to an eventClass,
                // the event firing is going to fail.
                switch (eventName) {
                    case "click": // Dispatching of 'click' appears to not work correctly in Safari. Use 'mousedown' or 'mouseup' instead.
                    case "mouseenter":
                    case "mouseleave":
                    case "mousedown":
                    case "mouseup":
                        eventClass = "MouseEvents";
                        break;

                    case "focus":
                    case "change":
                    case "blur":
                    case "select":
                        eventClass = "HTMLEvents";
                        break;

                    default:
                        throw "fireEvent: Couldn't find an event class for event '" + eventName + "'.";
                        break;
                }
                var event = doc.createEvent(eventClass);

                const bubbles = eventName == "change" ? false : true;
                event.initEvent(eventName, bubbles, true); // All events created as bubbling and cancelable.

                event.synthetic = true; // allow detection of synthetic events
                // The second parameter says go ahead with the default action
                node.dispatchEvent(event, true);
            } else if (node.fireEvent) {
                // IE-old school style
                var event = doc.createEventObject();
                event.synthetic = true; // allow detection of synthetic events
                node.fireEvent("on" + eventName, event);
            }
        },

        index: function (el) {
            const c = el.parentNode.children;
            let i = 0;
            for (; i < c.length; i++)
                if (c[i] == el) return i;
        },

        trim: function (string) {
            return string.trim();
        },

        eventTriggered: function (e) {
            if (e.currentTarget.dataset.triggered) {
                return true;
            } else {
                e.currentTarget.dataset.triggered = true;

                return false;
            }
        },

        remove: function (el) {
            if (el && el.parentNode) {
                el.parentNode.removeChild(el);
            }
        },

        find: function (parent, query) {
            if (parent !== null) {
                return parent.querySelector(query);
            } else {
                return null;
            }
        },

        findAll: function (parent, query) {
            if (parent !== null) {
                return parent.querySelectorAll(query);
            } else {
                return null;
            }
        },

        insertAfter: function (el, referenceNode) {
            return referenceNode.parentNode.insertBefore(el, referenceNode.nextSibling);
        },

        parents: function (elem, selector) {
            // Set up a parent array
            const parents = [];

            // Push each parent element to the array
            for (; elem && elem !== document; elem = elem.parentNode) {
                if (selector) {
                    if (elem.matches(selector)) {
                        parents.push(elem);
                    }
                    continue;
                }
                parents.push(elem);
            }

            // Return our parent array
            return parents;
        },

        children: function (el, selector, log) {
            if (!el || !el.childNodes) {
                return null;
            }

            var result = [],
                i = 0,
                l = el.childNodes.length;

            for (var i; i < l; ++i) {
                if (el.childNodes[i].nodeType == 1 && KTUtil.matches(el.childNodes[i], selector, log)) {
                    result.push(el.childNodes[i]);
                }
            }

            return result;
        },

        child: function (el, selector, log) {
            const children = KTUtil.children(el, selector, log);

            return children ? children[0] : null;
        },

        matches: function (el, selector, log) {
            const p = Element.prototype;
            const f = p.matches || p.webkitMatchesSelector || p.mozMatchesSelector || p.msMatchesSelector || function (s) {
                return [].indexOf.call(document.querySelectorAll(s), this) !== -1;
            };

            if (el && el.tagName) {
                return f.call(el, selector);
            } else {
                return false;
            }
        },

        data: function (el) {
            return {
                set: function (name, data) {
                    if (!el) {
                        return;
                    }

                    if (el.customDataTag === undefined) {
                        window.KTUtilElementDataStoreID++;
                        el.customDataTag = window.KTUtilElementDataStoreID;
                    }

                    if (window.KTUtilElementDataStore[el.customDataTag] === undefined) {
                        window.KTUtilElementDataStore[el.customDataTag] = {};
                    }

                    window.KTUtilElementDataStore[el.customDataTag][name] = data;
                },

                get: function (name) {
                    if (!el) {
                        return;
                    }

                    if (el.customDataTag === undefined) {
                        return null;
                    }

                    return this.has(name) ? window.KTUtilElementDataStore[el.customDataTag][name] : null;
                },

                has: function (name) {
                    if (!el) {
                        return false;
                    }

                    if (el.customDataTag === undefined) {
                        return false;
                    }

                    return (window.KTUtilElementDataStore[el.customDataTag] && window.KTUtilElementDataStore[el.customDataTag][name]) ? true : false;
                },

                remove: function (name) {
                    if (el && this.has(name)) {
                        delete window.KTUtilElementDataStore[el.customDataTag][name];
                    }
                }
            };
        },

        outerWidth: function (el, margin) {
            let width;

            if (margin === true) {
                width = parseFloat(el.offsetWidth);
                width += parseFloat(KTUtil.css(el, 'margin-left')) + parseFloat(KTUtil.css(el, 'margin-right'));

                return parseFloat(width);
            } else {
                width = parseFloat(el.offsetWidth);

                return width;
            }
        },

        offset: function (el) {
            let rect, win;

            if (!el) {
                return;
            }

            // Return zeros for disconnected and hidden (display: none) elements (gh-2310)
            // Support: IE <=11 only
            // Running getBoundingClientRect on a
            // disconnected node in IE throws an error

            if (!el.getClientRects().length) {
                return {
                    top: 0,
                    left: 0
                };
            }

            // Get document-relative position by adding viewport scroll to viewport-relative gBCR
            rect = el.getBoundingClientRect();
            win = el.ownerDocument.defaultView;

            return {
                top: rect.top + win.pageYOffset,
                left: rect.left + win.pageXOffset,
                right: window.innerWidth - (el.offsetLeft + el.offsetWidth)
            };
        },

        height: function (el) {
            return KTUtil.css(el, 'height');
        },

        outerHeight: function (el, withMargin) {
            let height = el.offsetHeight;
            let style;

            if (typeof withMargin !== 'undefined' && withMargin === true) {
                style = getComputedStyle(el);
                height += parseInt(style.marginTop) + parseInt(style.marginBottom);

                return height;
            } else {
                return height;
            }
        },

        visible: function (el) {
            return !(el.offsetWidth === 0 && el.offsetHeight === 0);
        },

        isVisibleInContainer: function (el, container, offset = 0) {
            const eleTop = el.offsetTop;
            const eleBottom = eleTop + el.clientHeight + offset;
            const containerTop = container.scrollTop;
            const containerBottom = containerTop + container.clientHeight;

            // The element is fully visible in the container
            return (
                (eleTop >= containerTop && eleBottom <= containerBottom)
            );
        },

        getRelativeTopPosition: function (el, container) {
            return el.offsetTop - container.offsetTop;
        },

        attr: function (el, name, value) {
            if (el == undefined) {
                return;
            }

            if (value !== undefined) {
                el.setAttribute(name, value);
            } else {
                return el.getAttribute(name);
            }
        },

        hasAttr: function (el, name) {
            if (el == undefined) {
                return;
            }

            return el.getAttribute(name) ? true : false;
        },

        removeAttr: function (el, name) {
            if (el == undefined) {
                return;
            }

            el.removeAttribute(name);
        },

        animate: function (from, to, duration, update, easing, done) {
            /**
             * TinyAnimate.easings
             *  Adapted from jQuery Easing
             */
            const easings = {};
            var easing;

            easings.linear = function (t, b, c, d) {
                return c * t / d + b;
            };

            easing = easings.linear;

            // Early bail out if called incorrectly
            if (typeof from !== 'number' ||
                typeof to !== 'number' ||
                typeof duration !== 'number' ||
                typeof update !== 'function') {
                return;
            }

            // Create mock done() function if necessary
            if (typeof done !== 'function') {
                done = function () {};
            }

            // Pick implementation (requestAnimationFrame | setTimeout)
            const rAF = window.requestAnimationFrame || function (callback) {
                window.setTimeout(callback, 1000 / 50);
            };

            // Animation loop
            const canceled = false;
            const change = to - from;

            function loop(timestamp) {
                const time = (timestamp || +new Date()) - start;

                if (time >= 0) {
                    update(easing(time, from, change, duration));
                }
                if (time >= 0 && time >= duration) {
                    update(to);
                    done();
                } else {
                    rAF(loop);
                }
            }

            update(from);

            // Start animation loop
            var start = window.performance && window.performance.now ? window.performance.now() : +new Date();

            rAF(loop);
        },

        actualCss: function (el, prop, cache) {
            let css = '';

            if (el instanceof HTMLElement === false) {
                return;
            }

            if (!el.getAttribute('kt-hidden-' + prop) || cache === false) {
                let value;

                // the element is hidden so:
                // making the el block so we can meassure its height but still be hidden
                css = el.style.cssText;
                el.style.cssText = 'position: absolute; visibility: hidden; display: block;';

                if (prop == 'width') {
                    value = el.offsetWidth;
                } else if (prop == 'height') {
                    value = el.offsetHeight;
                }

                el.style.cssText = css;

                // store it in cache
                el.setAttribute('kt-hidden-' + prop, value);

                return parseFloat(value);
            } else {
                // store it in cache
                return parseFloat(el.getAttribute('kt-hidden-' + prop));
            }
        },

        actualHeight: function (el, cache) {
            return KTUtil.actualCss(el, 'height', cache);
        },

        actualWidth: function (el, cache) {
            return KTUtil.actualCss(el, 'width', cache);
        },

        getScroll: function (element, method) {
            // The passed in `method` value should be 'Top' or 'Left'
            method = 'scroll' + method;
            return (element == window || element == document) ? (
                self[(method == 'scrollTop') ? 'pageYOffset' : 'pageXOffset'] ||
                (browserSupportsBoxModel && document.documentElement[method]) ||
                document.body[method]
            ) : element[method];
        },

        css: function (el, styleProp, value, important) {
            if (!el) {
                return;
            }

            if (value !== undefined) {
                if (important === true) {
                    el.style.setProperty(styleProp, value, 'important');
                } else {
                    el.style[styleProp] = value;
                }
            } else {
                const defaultView = (el.ownerDocument || document).defaultView;

                // W3C standard way:
                if (defaultView && defaultView.getComputedStyle) {
                    // sanitize property name to css notation
                    // (hyphen separated words eg. font-Size)
                    styleProp = styleProp.replace(/([A-Z])/g, "-$1").toLowerCase();

                    return defaultView.getComputedStyle(el, null).getPropertyValue(styleProp);
                } else if (el.currentStyle) { // IE
                    // sanitize property name to camelCase
                    styleProp = styleProp.replace(/\-(\w)/g, function (str, letter) {
                        return letter.toUpperCase();
                    });

                    value = el.currentStyle[styleProp];

                    // convert other units to pixels on IE
                    if (/^\d+(em|pt|%|ex)?$/i.test(value)) {
                        return (function (value) {
                            const oldLeft = el.style.left,
                                oldRsLeft = el.runtimeStyle.left;

                            el.runtimeStyle.left = el.currentStyle.left;
                            el.style.left = value || 0;
                            value = el.style.pixelLeft + "px";
                            el.style.left = oldLeft;
                            el.runtimeStyle.left = oldRsLeft;

                            return value;
                        })(value);
                    }

                    return value;
                }
            }
        },

        slide: function (el, dir, speed, callback, recalcMaxHeight) {
            if (!el || (dir == 'up' && KTUtil.visible(el) === false) || (dir == 'down' && KTUtil.visible(el) === true)) {
                return;
            }

            speed = (speed ? speed : 600);
            const calcHeight = KTUtil.actualHeight(el);
            let calcPaddingTop = false;
            let calcPaddingBottom = false;

            if (KTUtil.css(el, 'padding-top') && KTUtil.data(el).has('slide-padding-top') !== true) {
                KTUtil.data(el).set('slide-padding-top', KTUtil.css(el, 'padding-top'));
            }

            if (KTUtil.css(el, 'padding-bottom') && KTUtil.data(el).has('slide-padding-bottom') !== true) {
                KTUtil.data(el).set('slide-padding-bottom', KTUtil.css(el, 'padding-bottom'));
            }

            if (KTUtil.data(el).has('slide-padding-top')) {
                calcPaddingTop = parseInt(KTUtil.data(el).get('slide-padding-top'));
            }

            if (KTUtil.data(el).has('slide-padding-bottom')) {
                calcPaddingBottom = parseInt(KTUtil.data(el).get('slide-padding-bottom'));
            }

            if (dir == 'up') { // up
                el.style.cssText = 'display: block; overflow: hidden;';

                if (calcPaddingTop) {
                    KTUtil.animate(0, calcPaddingTop, speed, function (value) {
                        el.style.paddingTop = (calcPaddingTop - value) + 'px';
                    }, 'linear');
                }

                if (calcPaddingBottom) {
                    KTUtil.animate(0, calcPaddingBottom, speed, function (value) {
                        el.style.paddingBottom = (calcPaddingBottom - value) + 'px';
                    }, 'linear');
                }

                KTUtil.animate(0, calcHeight, speed, function (value) {
                    el.style.height = (calcHeight - value) + 'px';
                }, 'linear', function () {
                    el.style.height = '';
                    el.style.display = 'none';

                    if (typeof callback === 'function') {
                        callback();
                    }
                });


            } else if (dir == 'down') { // down
                el.style.cssText = 'display: block; overflow: hidden;';

                if (calcPaddingTop) {
                    KTUtil.animate(0, calcPaddingTop, speed, function (value) { //
                        el.style.paddingTop = value + 'px';
                    }, 'linear', function () {
                        el.style.paddingTop = '';
                    });
                }

                if (calcPaddingBottom) {
                    KTUtil.animate(0, calcPaddingBottom, speed, function (value) {
                        el.style.paddingBottom = value + 'px';
                    }, 'linear', function () {
                        el.style.paddingBottom = '';
                    });
                }

                KTUtil.animate(0, calcHeight, speed, function (value) {
                    el.style.height = value + 'px';
                }, 'linear', function () {
                    el.style.height = '';
                    el.style.display = '';
                    el.style.overflow = '';

                    if (typeof callback === 'function') {
                        callback();
                    }
                });
            }
        },

        slideUp: function (el, speed, callback) {
            KTUtil.slide(el, 'up', speed, callback);
        },

        slideDown: function (el, speed, callback) {
            KTUtil.slide(el, 'down', speed, callback);
        },

        show: function (el, display) {
            if (typeof el !== 'undefined') {
                el.style.display = (display ? display : 'block');
            }
        },

        hide: function (el) {
            if (typeof el !== 'undefined') {
                el.style.display = 'none';
            }
        },

        addEvent: function (el, type, handler, one) {
            if (typeof el !== 'undefined' && el !== null) {
                el.addEventListener(type, handler);
            }
        },

        removeEvent: function (el, type, handler) {
            if (el !== null) {
                el.removeEventListener(type, handler);
            }
        },

        on: function (element, selector, event, handler) {
            if (element === null) {
                return;
            }

            const eventId = KTUtil.getUniqueId('event');

            window.KTUtilDelegatedEventHandlers[eventId] = function (e) {
                const targets = element.querySelectorAll(selector);
                let target = e.target;

                while (target && target !== element) {
                    let i = 0;
                    const j = targets.length;
                    for (; i < j; i++) {
                        if (target === targets[i]) {
                            handler.call(target, e);
                        }
                    }

                    target = target.parentNode;
                }
            }

            KTUtil.addEvent(element, event, window.KTUtilDelegatedEventHandlers[eventId]);

            return eventId;
        },

        off: function (element, event, eventId) {
            if (!element || !window.KTUtilDelegatedEventHandlers[eventId]) {
                return;
            }

            KTUtil.removeEvent(element, event, window.KTUtilDelegatedEventHandlers[eventId]);

            delete window.KTUtilDelegatedEventHandlers[eventId];
        },

        one: function onetime(el, type, callback) {
            el.addEventListener(type, function callee(e) {
                // remove event
                if (e.target && e.target.removeEventListener) {
                    e.target.removeEventListener(e.type, callee);
                }

                // need to verify from https://themeforest.net/author_dashboard#comment_23615588
                if (el && el.removeEventListener) {
                    e.currentTarget.removeEventListener(e.type, callee);
                }

                // call handler
                return callback(e);
            });
        },

        hash: function (str) {
            let hash = 0,
                i, chr;

            if (str.length === 0) return hash;
            for (i = 0; i < str.length; i++) {
                chr = str.charCodeAt(i);
                hash = ((hash << 5) - hash) + chr;
                hash |= 0; // Convert to 32bit integer
            }

            return hash;
        },

        animateClass: function (el, animationName, callback) {
            let animation;
            const animations = {
                animation: 'animationend',
                OAnimation: 'oAnimationEnd',
                MozAnimation: 'mozAnimationEnd',
                WebkitAnimation: 'webkitAnimationEnd',
                msAnimation: 'msAnimationEnd',
            };

            for (let t in animations) {
                if (el.style[t] !== undefined) {
                    animation = animations[t];
                }
            }

            KTUtil.addClass(el, animationName);

            KTUtil.one(el, animation, function () {
                KTUtil.removeClass(el, animationName);
            });

            if (callback) {
                KTUtil.one(el, animation, callback);
            }
        },

        transitionEnd: function (el, callback) {
            let transition;
            const transitions = {
                transition: 'transitionend',
                OTransition: 'oTransitionEnd',
                MozTransition: 'mozTransitionEnd',
                WebkitTransition: 'webkitTransitionEnd',
                msTransition: 'msTransitionEnd'
            };

            for (let t in transitions) {
                if (el.style[t] !== undefined) {
                    transition = transitions[t];
                }
            }

            KTUtil.one(el, transition, callback);
        },

        animationEnd: function (el, callback) {
            let animation;
            const animations = {
                animation: 'animationend',
                OAnimation: 'oAnimationEnd',
                MozAnimation: 'mozAnimationEnd',
                WebkitAnimation: 'webkitAnimationEnd',
                msAnimation: 'msAnimationEnd'
            };

            for (let t in animations) {
                if (el.style[t] !== undefined) {
                    animation = animations[t];
                }
            }

            KTUtil.one(el, animation, callback);
        },

        animateDelay: function (el, value) {
            const vendors = ['webkit-', 'moz-', 'ms-', 'o-', ''];
            for (let i = 0; i < vendors.length; i++) {
                KTUtil.css(el, vendors[i] + 'animation-delay', value);
            }
        },

        animateDuration: function (el, value) {
            const vendors = ['webkit-', 'moz-', 'ms-', 'o-', ''];
            for (let i = 0; i < vendors.length; i++) {
                KTUtil.css(el, vendors[i] + 'animation-duration', value);
            }
        },

        scrollTo: function (target, offset, duration) {
            var duration = duration ? duration : 500;
            let targetPos = target ? KTUtil.offset(target).top : 0;
            const scrollPos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            let from, to;

            if (offset) {
                targetPos = targetPos - offset;
            }

            from = scrollPos;
            to = targetPos;

            KTUtil.animate(from, to, duration, function (value) {
                document.documentElement.scrollTop = value;
                document.body.parentNode.scrollTop = value;
                document.body.scrollTop = value;
            }); //, easing, done
        },

        scrollTop: function (offset, duration) {
            KTUtil.scrollTo(null, offset, duration);
        },

        isArray: function (obj) {
            return obj && Array.isArray(obj);
        },

        isEmpty: function (obj) {
            for (let prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    return false;
                }
            }

            return true;
        },

        numberString: function (nStr) {
            nStr += '';
            const x = nStr.split('.');
            let x1 = x[0];
            const x2 = x.length > 1 ? '.' + x[1] : '';
            const rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        },

        isRTL: function () {
            return (document.querySelector('html').getAttribute("direction") === 'rtl');
        },

        snakeToCamel: function (s) {
            return s.replace(/(\-\w)/g, function (m) {
                return m[1].toUpperCase();
            });
        },

        filterBoolean: function (val) {
            // Convert string boolean
            if (val === true || val === 'true') {
                return true;
            }

            if (val === false || val === 'false') {
                return false;
            }

            return val;
        },

        setHTML: function (el, html) {
            el.innerHTML = html;
        },

        getHTML: function (el) {
            if (el) {
                return el.innerHTML;
            }
        },

        getDocumentHeight: function () {
            const body = document.body;
            const html = document.documentElement;

            return Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
        },

        getScrollTop: function () {
            return (document.scrollingElement || document.documentElement).scrollTop;
        },

        colorLighten: function (color, amount) {
            const addLight = function (color, amount) {
                let cc = parseInt(color, 16) + amount;
                let c = (cc > 255) ? 255 : (cc);
                c = (c.toString(16).length > 1) ? c.toString(16) : `0${c.toString(16)}`;
                return c;
            }

            color = (color.indexOf("#") >= 0) ? color.substring(1, color.length) : color;
            amount = parseInt((255 * amount) / 100);

            return color = `#${addLight(color.substring(0,2), amount)}${addLight(color.substring(2,4), amount)}${addLight(color.substring(4,6), amount)}`;
        },

        colorDarken: function (color, amount) {
            const subtractLight = function (color, amount) {
                let cc = parseInt(color, 16) - amount;
                let c = (cc < 0) ? 0 : (cc);
                c = (c.toString(16).length > 1) ? c.toString(16) : `0${c.toString(16)}`;

                return c;
            }

            color = (color.indexOf("#") >= 0) ? color.substring(1, color.length) : color;
            amount = parseInt((255 * amount) / 100);

            return color = `#${subtractLight(color.substring(0,2), amount)}${subtractLight(color.substring(2,4), amount)}${subtractLight(color.substring(4,6), amount)}`;
        },

        // Throttle function: Input as function which needs to be throttled and delay is the time interval in milliseconds
        throttle: function (timer, func, delay) {
            // If setTimeout is already scheduled, no need to do anything
            if (timer) {
                return;
            }

            // Schedule a setTimeout after delay seconds
            timer = setTimeout(function () {
                func();

                // Once setTimeout function execution is finished, timerId = undefined so that in <br>
                // the next scroll event function execution can be scheduled by the setTimeout
                timer = undefined;
            }, delay);
        },

        // Debounce function: Input as function which needs to be debounced and delay is the debounced time in milliseconds
        debounce: function (timer, func, delay) {
            // Cancels the setTimeout method execution
            clearTimeout(timer)

            // Executes the func after delay time.
            timer = setTimeout(func, delay);
        },

        parseJson: function (value) {
            if (typeof value === 'string') {
                value = value.replace(/'/g, "\"");

                const jsonStr = value.replace(/(\w+:)|(\w+ :)/g, function (matched) {
                    return '"' + matched.substring(0, matched.length - 1) + '":';
                });

                try {
                    value = JSON.parse(jsonStr);
                } catch (e) {}
            }

            return value;
        },

        getResponsiveValue: function (value, defaultValue) {
            const width = this.getViewPort().width;
            let result = null;

            value = KTUtil.parseJson(value);

            if (typeof value === 'object') {
                let resultKey;
                let resultBreakpoint = -1;
                let breakpoint;

                for (let key in value) {
                    if (key === 'default') {
                        breakpoint = 0;
                    } else {
                        breakpoint = this.getBreakpoint(key) ? this.getBreakpoint(key) : parseInt(key);
                    }

                    if (breakpoint <= width && breakpoint > resultBreakpoint) {
                        resultKey = key;
                        resultBreakpoint = breakpoint;
                    }
                }

                if (resultKey) {
                    result = value[resultKey];
                } else {
                    result = value;
                }
            } else {
                result = value;
            }

            return result;
        },

        each: function (array, callback) {
            return [].slice.call(array).map(callback);
        },

        getSelectorMatchValue: function (value) {
            let result = null;
            value = KTUtil.parseJson(value);

            if (typeof value === 'object') {
                // Match condition
                if (value['match'] !== undefined) {
                    const selector = Object.keys(value['match'])[0];
                    value = Object.values(value['match'])[0];

                    if (document.querySelector(selector) !== null) {
                        result = value;
                    }
                }
            } else {
                result = value;
            }

            return result;
        },

        getConditionalValue: function (value) {
            var value = KTUtil.parseJson(value);
            let result = KTUtil.getResponsiveValue(value);

            if (result !== null && result['match'] !== undefined) {
                result = KTUtil.getSelectorMatchValue(result);
            }

            if (result === null && value !== null && value['default'] !== undefined) {
                result = value['default'];
            }

            return result;
        },

        getCssVariableValue: function (variableName) {
            let hex = getComputedStyle(document.documentElement).getPropertyValue(variableName);
            if (hex && hex.length > 0) {
                hex = hex.trim();
            }

            return hex;
        },

        isInViewport: function (element) {
            const rect = element.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        isPartiallyInViewport: function (element) {
            let x = element.getBoundingClientRect().left;
            let y = element.getBoundingClientRect().top;
            let ww = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            let hw = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
            let w = element.clientWidth;
            let h = element.clientHeight;

            return (
                (y < hw &&
                    y + h > 0) &&
                (x < ww &&
                    x + w > 0)
            );
        },

        onDOMContentLoaded: function (callback) {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', callback);
            } else {
                callback();
            }
        },

        inIframe: function () {
            try {
                return window.self !== window.top;
            } catch (e) {
                return true;
            }
        },

        isHexColor(code) {
            return /^#[0-9A-F]{6}$/i.test(code);
        }
    }
}();

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTUtil;
}
"use strict";

// Class definition
const KTAppLayoutBuilder = function () {
    let form;
    let actionInput;
    let url;
    let previewButton;
    let exportButton;
    let resetButton;

    let engage;
    let engageToggleOff;
    let engageToggleOn;
    let engagePrebuiltsModal;

    const handleEngagePrebuilts = function () {
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

    const handleEngagePrebuiltsViewMenu = function () {
        const selected = engagePrebuiltsModal.querySelector('[data-kt-element="selected"]');
        const selectedTitle = engagePrebuiltsModal.querySelector('[data-kt-element="title"]');
        const menu = engagePrebuiltsModal.querySelector('[data-kt-menu="true"]');

        // Toggle Handler
        KTUtil.on(engagePrebuiltsModal, '[data-kt-mode]', 'click', function (e) {
            const title = this.innerText;
            const mode = this.getAttribute("data-kt-mode");
            const selectedLink = menu.querySelector('.menu-link.active');
            const viewImage = document.querySelector('#kt_app_engage_prebuilts_view_image');
            const viewText = document.querySelector('#kt_app_engage_prebuilts_view_text');
            selectedTitle.innerText = title;

            if (selectedLink) {
                selectedLink.classList.remove('active');
            }

            this.classList.add('active');

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

    const handleEngageToggle = function () {
        engageToggleOff.addEventListener("click", function (e) {
            e.preventDefault();

            const date = new Date(Date.now() + 1 * 24 * 60 * 60 * 1000); // 1 days from now
            KTCookie.set("app_engage_hide", "1", {
                expires: date
            });
            engage.classList.add('app-engage-hide');
        });

        engageToggleOn.addEventListener("click", function (e) {
            e.preventDefault();

            KTCookie.remove("app_engage_hide");
            engage.classList.remove('app-engage-hide');
        });
    };

    const handlePreview = function () {
        previewButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Set form action value
            actionInput.value = "preview";

            // Show progress
            previewButton.setAttribute("data-kt-indicator", "on");

            // Prepare form data
            const data = $(form).serialize();

            // Submit
            $.ajax({
                type: "POST",
                dataType: "html",
                url: url,
                data: data,
                success: function (response, status, xhr) {
                    if (history.scrollRestoration) {
                        history.scrollRestoration = 'manual';
                    }
                    location.reload();
                    return;

                    toastr.success(
                        "Preview has been updated with current configured layout.",
                        "Preview updated!", {
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
                    toastr.error(
                        "Please try it again later.",
                        "Something went wrong!", {
                            timeOut: 0,
                            extendedTimeOut: 0,
                            closeButton: true,
                            closeDuration: 0
                        }
                    );
                },
                complete: function () {
                    previewButton.removeAttribute("data-kt-indicator");
                }
            });
        });
    };

    const handleExport = function () {
        exportButton.addEventListener("click", function (e) {
            e.preventDefault();

            toastr.success(
                "Process has been started and it may take a while.",
                "Generating HTML!", {
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
            const data = $(form).serialize();

            $.ajax({
                type: "POST",
                dataType: "html",
                url: url,
                data: data,
                success: function (response, status, xhr) {
                    const timer = setInterval(function () {
                        $("<iframe/>").attr({
                            src: url + "?layout-builder[action]=export&download=1&output=" + response,
                            style: "visibility:hidden;display:none",
                        }).ready(function () {
                            // Stop the timer
                            clearInterval(timer);

                            exportButton.removeAttribute("data-kt-indicator");
                        }).appendTo("body");
                    }, 3000);
                },
                error: function (response) {
                    toastr.error(
                        "Please try it again later.",
                        "Something went wrong!", {
                            timeOut: 0,
                            extendedTimeOut: 0,
                            closeButton: true,
                            closeDuration: 0
                        }
                    );

                    exportButton.removeAttribute("data-kt-indicator");
                },
            });
        });
    };

    const handleReset = function () {
        resetButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Show progress
            resetButton.setAttribute("data-kt-indicator", "on");

            // Set form action value
            actionInput.value = "reset";

            // Prepare form data
            const data = $(form).serialize();

            $.ajax({
                type: "POST",
                dataType: "html",
                url: url,
                data: data,
                success: function (response, status, xhr) {
                    if (history.scrollRestoration) {
                        history.scrollRestoration = 'manual';
                    }

                    location.reload();
                    return;

                    toastr.success(
                        "Preview has been successfully reset and the page will be reloaded.",
                        "Reset Preview!", {
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
                    toastr.error(
                        "Please try it again later.",
                        "Something went wrong!", {
                            timeOut: 0,
                            extendedTimeOut: 0,
                            closeButton: true,
                            closeDuration: 0
                        }
                    );
                },
                complete: function () {
                    resetButton.removeAttribute("data-kt-indicator");
                },
            });
        });
    };

    const handleThemeMode = function () {
        const checkLight = document.querySelector('#kt_layout_builder_theme_mode_light');
        const checkDark = document.querySelector('#kt_layout_builder_theme_mode_dark');
        const check = document.querySelector('#kt_layout_builder_theme_mode_' + KTThemeMode.getMode());

        if (checkLight) {
            checkLight.addEventListener("click", function () {
                this.checked = true;
                this.closest('[data-kt-buttons="true"]').querySelector('.form-check-image.active').classList.remove('active');
                this.closest('.form-check-image').classList.add('active');
                KTThemeMode.setMode('light');
            });
        }

        if (checkDark) {
            checkDark.addEventListener("click", function () {
                this.checked = true;
                this.closest('[data-kt-buttons="true"]').querySelector('.form-check-image.active').classList.remove('active');
                this.closest('.form-check-image').classList.add('active');
                KTThemeMode.setMode('dark');
            });
        }

        if (check) {
            check.closest('.form-check-image').classList.add('active');
            check.checked = true;
        }
    };

    return {
        // Public functions
        init: function () {
            engage = document.querySelector('#kt_app_engage');
            engageToggleOn = document.querySelector('#kt_app_engage_toggle_on');
            engageToggleOff = document.querySelector('#kt_app_engage_toggle_off');
            engagePrebuiltsModal = document.querySelector('#kt_app_engage_prebuilts_modal');

            if (engage && engagePrebuiltsModal) {
                handleEngagePrebuilts();
                handleEngagePrebuiltsViewMenu();
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
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppLayoutBuilder.init();
});
"use strict";

// Class definition
const KTLayoutSearch = function () {
    // Private variables
    let element;
    let formElement;
    let mainElement;
    let resultsElement;
    let wrapperElement;
    let emptyElement;

    let preferencesElement;
    let preferencesShowElement;
    let preferencesDismissElement;

    let advancedOptionsFormElement;
    let advancedOptionsFormShowElement;
    let advancedOptionsFormCancelElement;
    let advancedOptionsFormSearchElement;

    let searchObject;

    // Private functions
    let processs = function (search) {
        let timeout = setTimeout(function () {
            let number = KTUtil.getRandomInt(1, 3);

            // Hide recently viewed
            mainElement.classList.add('d-none');

            if (number === 3) {
                // Hide results
                resultsElement.classList.add('d-none');
                // Show empty message
                emptyElement.classList.remove('d-none');
            } else {
                // Show results
                resultsElement.classList.remove('d-none');
                // Hide empty message
                emptyElement.classList.add('d-none');
            }

            // Complete search
            search.complete();
        }, 1500);
    }

    let processsAjax = function (search) {
        // Hide recently viewed
        mainElement.classList.add('d-none');

        axios.post('/search', {
            query: searchObject.getQuery()
        })
            .then(function (response) {
                // Populate results
                resultsElement.innerHTML = response;
                // Show results
                resultsElement.classList.remove('d-none');
                // Hide empty message
                emptyElement.classList.add('d-none');

                // Complete search
                search.complete();
            })
            .catch(function (error) {
                // Hide results
                resultsElement.classList.add('d-none');
                // Show empty message
                emptyElement.classList.remove('d-none');

                // Complete search
                search.complete();
            });
    }

    const clear = function (search) {
        // Show recently viewed
        mainElement.classList.remove('d-none');
        // Hide results
        resultsElement.classList.add('d-none');
        // Hide empty message
        emptyElement.classList.add('d-none');
    };

    const handlePreferences = function () {
        // Preference show handler
        preferencesShowElement.addEventListener('click', function () {
            wrapperElement.classList.add('d-none');
            preferencesElement.classList.remove('d-none');
        });

        // Preference dismiss handler
        preferencesDismissElement.addEventListener('click', function () {
            wrapperElement.classList.remove('d-none');
            preferencesElement.classList.add('d-none');
        });
    };

    const handleAdvancedOptionsForm = function () {
        // Show
        advancedOptionsFormShowElement.addEventListener('click', function () {
            wrapperElement.classList.add('d-none');
            advancedOptionsFormElement.classList.remove('d-none');
        });

        // Cancel
        advancedOptionsFormCancelElement.addEventListener('click', function () {
            wrapperElement.classList.remove('d-none');
            advancedOptionsFormElement.classList.add('d-none');
        });

        // Search
        advancedOptionsFormSearchElement.addEventListener('click', function () {

        });
    };

    // Public methods
    return {
        init: function () {
            // Elements
            element = document.querySelector('#kt_header_search');

            if (!element) {
                return;
            }

            wrapperElement = element.querySelector('[data-kt-search-element="wrapper"]');
            formElement = element.querySelector('[data-kt-search-element="form"]');
            mainElement = element.querySelector('[data-kt-search-element="main"]');
            resultsElement = element.querySelector('[data-kt-search-element="results"]');
            emptyElement = element.querySelector('[data-kt-search-element="empty"]');

            preferencesElement = element.querySelector('[data-kt-search-element="preferences"]');
            preferencesShowElement = element.querySelector('[data-kt-search-element="preferences-show"]');
            preferencesDismissElement = element.querySelector('[data-kt-search-element="preferences-dismiss"]');

            advancedOptionsFormElement = element.querySelector('[data-kt-search-element="advanced-options-form"]');
            advancedOptionsFormShowElement = element.querySelector('[data-kt-search-element="advanced-options-form-show"]');
            advancedOptionsFormCancelElement = element.querySelector('[data-kt-search-element="advanced-options-form-cancel"]');
            advancedOptionsFormSearchElement = element.querySelector('[data-kt-search-element="advanced-options-form-search"]');

            // Initialize search handler
            searchObject = new KTSearch(element);

            // Demo search handler
            searchObject.on('kt.search.process', processs);

            // Ajax search handler
            //searchObject.on('kt.search.process', processsAjax);

            // Clear handler
            searchObject.on('kt.search.clear', clear);

            // Custom handlers
            handlePreferences();
            handleAdvancedOptionsForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTLayoutSearch.init();
});
"use strict";

// Class definition
const KTThemeModeUser = function () {

    const handleSubmit = function () {
        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function () {
            const menuMode = KTThemeMode.getMenuMode();
            const mode = KTThemeMode.getMode();
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
}();

// Initialize app on document ready
KTUtil.onDOMContentLoaded(function () {
    KTThemeModeUser.init();
});

// Declare KTThemeModeUser for Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTThemeModeUser;
}
"use strict";

// Class definition
var KTThemeMode = function () {
    let menu;
    const callbacks = [];
    const the = this;

    const getMode = function () {
        let mode;

        if (document.documentElement.hasAttribute("data-bs-theme")) {
            return document.documentElement.getAttribute("data-bs-theme");
        } else if (localStorage.getItem("data-bs-theme") !== null) {
            return localStorage.getItem("data-bs-theme");
        } else if (getMenuMode() === "system") {
            return getSystemMode();
        }

        return "light";
    };

    const setMode = function (mode, menuMode) {
        const currentMode = getMode();

        // Reset mode if system mode was changed
        if (menuMode === 'system') {
            if (getSystemMode() !== mode) {
                mode = getSystemMode();
            }
        } else if (mode !== menuMode) {
            menuMode = mode;
        }

        // Read active menu mode value
        const activeMenuItem = menu ? menu.querySelector('[data-kt-element="mode"][data-kt-value="' + menuMode + '"]') : null;

        // Enable switching state
        document.documentElement.setAttribute("data-kt-theme-mode-switching", "true");

        // Set mode to the target document.documentElement
        document.documentElement.setAttribute("data-bs-theme", mode);

        // Disable switching state
        setTimeout(function () {
            document.documentElement.removeAttribute("data-kt-theme-mode-switching");
        }, 300);

        // Store mode value in storage
        localStorage.setItem("data-bs-theme", mode);

        // Set active menu item
        if (activeMenuItem) {
            localStorage.setItem("data-bs-theme-mode", menuMode);
            setActiveMenuItem(activeMenuItem);
        }

        if (mode !== currentMode) {
            KTEventHandler.trigger(document.documentElement, 'kt.thememode.change', the);
        }
    };

    var getMenuMode = function () {
        if (!menu) {
            return null;
        }

        const menuItem = menu ? menu.querySelector('.active[data-kt-element="mode"]') : null;

        if (menuItem && menuItem.getAttribute('data-kt-value')) {
            return menuItem.getAttribute('data-kt-value');
        } else if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
            return document.documentElement.getAttribute("data-bs-theme-mode")
        } else if (localStorage.getItem("data-bs-theme-mode") !== null) {
            return localStorage.getItem("data-bs-theme-mode");
        } else {
            return typeof defaultThemeMode !== "undefined" ? defaultThemeMode : "light";
        }
    }

    var getSystemMode = function () {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light";
    }

    const initMode = function () {
        setMode(getMode(), getMenuMode());
        KTEventHandler.trigger(document.documentElement, 'kt.thememode.init', the);
    };

    const getActiveMenuItem = function () {
        return menu.querySelector('[data-kt-element="mode"][data-kt-value="' + getMenuMode() + '"]');
    };

    var setActiveMenuItem = function (item) {
        const menuMode = item.getAttribute("data-kt-value");

        const activeItem = menu.querySelector('.active[data-kt-element="mode"]');

        if (activeItem) {
            activeItem.classList.remove("active");
        }

        item.classList.add("active");
        localStorage.setItem("data-bs-theme-mode", menuMode);
    }

    const handleMenu = function () {
        const items = [].slice.call(menu.querySelectorAll('[data-kt-element="mode"]'));

        items.map(function (item) {
            item.addEventListener("click", function (e) {
                e.preventDefault();

                const menuMode = item.getAttribute("data-kt-value");
                let mode = menuMode;

                if (menuMode === "system") {
                    mode = getSystemMode();
                }

                setMode(mode, menuMode);
            });
        });
    };

    return {
        init: function () {
            menu = document.querySelector('[data-kt-element="theme-mode-menu"]');

            initMode();

            if (menu) {
                handleMenu();
            }
        },

        getMode: function () {
            return getMode();
        },

        getMenuMode: function () {
            return getMenuMode();
        },

        getSystemMode: function () {
            return getSystemMode();
        },

        setMode: function (mode) {
            return setMode(mode)
        },

        on: function (name, handler) {
            return KTEventHandler.on(document.documentElement, name, handler);
        },

        off: function (name, handlerId) {
            return KTEventHandler.off(document.documentElement, name, handlerId);
        }
    };
}();

// Initialize app on document ready
KTUtil.onDOMContentLoaded(function () {
    KTThemeMode.init();
});

// Declare KTThemeMode for Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTThemeMode;
}
"use strict";

// Class definition
const KTAppSidebar = function () {
    // Private variables
    var toggle;
    let sidebar;
    let headerMenu;
    let menuDashboardsCollapse;
    let menuWrapper;
    var toggle;

    // Private functions
    // Handle sidebar minimize mode toggle
    const handleToggle = function () {
        const toggleObj = KTToggle.getInstance(toggle);
        const headerMenuObj = KTMenu.getInstance(headerMenu);

        if (toggleObj === null) {
            return;
        }

        // Add a class to prevent sidebar hover effect after toggle click
        toggleObj.on('kt.toggle.change', function () {
            // Set animation state
            sidebar.classList.add('animating');

            // Wait till animation finishes
            setTimeout(function () {
                // Remove animation state
                sidebar.classList.remove('animating');
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
        toggleObj.on('kt.toggle.changed', function () {
            // In server side check sidebar_minimize_state cookie
            // value and add data-kt-app-sidebar-minimize="on"
            // attribute to Body tag and "active" class to the toggle button
            const date = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000); // 30 days from now

            KTCookie.set("sidebar_minimize_state", toggleObj.isEnabled() ? "on" : "off", {
                expires: date
            });
        });
    };

    // Handle dashboards menu items collapse mode
    const handleShowMore = function () {
        menuDashboardsCollapse.addEventListener('hide.bs.collapse', event => {
            menuWrapper.scrollTo({
                top: 0,
                behavior: 'instant'
            });
        });
    };

    const handleMenuScroll = function () {
        const menuActiveItem = menuWrapper.querySelector(".menu-link.active");

        if (!menuActiveItem) {
            return;
        }

        if (KTUtil.isVisibleInContainer(menuActiveItem, menuWrapper) === true) {
            return;
        }

        menuWrapper.scroll({
            top: KTUtil.getRelativeTopPosition(menuActiveItem, menuWrapper),
            behavior: 'smooth'
        });
    };

    // Public methods
    return {
        init: function () {
            // Elements
            sidebar = document.querySelector('#kt_app_sidebar');
            toggle = document.querySelector('#kt_app_sidebar_toggle');
            headerMenu = document.querySelector('#kt_app_header_menu');
            menuDashboardsCollapse = document.querySelector('#kt_app_sidebar_menu_dashboards_collapse');
            menuWrapper = document.querySelector('#kt_app_sidebar_menu_wrapper');

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
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppSidebar.init();
});
"use strict";

// Class definition
const KTLayoutToolbar = function () {
    // Private variables
    let toolbar;

    // Private functions
    const initForm = function () {
        const rangeSlider = document.querySelector("#kt_app_toolbar_slider");
        const rangeSliderValueElement = document.querySelector("#kt_app_toolbar_slider_value");

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
            const value = Number(rangeSlider.noUiSlider.get());

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
            toolbar = document.querySelector('#kt_app_toolbar');

            if (!toolbar) {
                return;
            }

            initForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTLayoutToolbar.init();
});
