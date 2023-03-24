//import KtUtil from "@/assets/js/base-theme/util/kt-util";
//import KTEventHandler from "@/assets/js/base-theme/event-handler/KTEventHandler";
// Class definition
let KTStepper = function(element, options) {
  //////////////////////////////
  // ** Private variables  ** //
  //////////////////////////////
  let the = this;

  if (typeof element === "undefined" || element === null) {
    return;
  }

  // Default Options
  let defaultOptions = {
    startIndex: 1,
    animation: false,
    animationSpeed: "0.3s",
    animationNextClass: "animate__animated animate__slideInRight animate__fast",
    animationPreviousClass: "animate__animated animate__slideInLeft animate__fast"
  };

  ////////////////////////////
  // ** Private methods  ** //
  ////////////////////////////

  let _construct = function() {
    if (KtUtil.data(element).has("stepper") === true) {
      the = KtUtil.data(element).get("stepper");
    } else {
      _init();
    }
  };

  let _init = function() {
    the.options = KtUtil.deepExtend({}, defaultOptions, options);
    the.uid = KtUtil.getUniqueId("stepper");

    the.element = element;

    // Set initialized
    the.element.setAttribute("data-kt-stepper", "true");

    // Elements
    the.steps = KtUtil.findAll(the.element, "[data-kt-stepper-element=\"nav\"]");
    the.btnNext = KtUtil.find(the.element, "[data-kt-stepper-action=\"next\"]");
    the.btnPrevious = KtUtil.find(the.element, "[data-kt-stepper-action=\"previous\"]");
    the.btnSubmit = KtUtil.find(the.element, "[data-kt-stepper-action=\"submit\"]");

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
    the.nextListener = function(e) {
      e.preventDefault();

      KTEventHandler.trigger(the.element, "kt.stepper.next", the);
    };

    the.previousListener = function(e) {
      e.preventDefault();

      KTEventHandler.trigger(the.element, "kt.stepper.previous", the);
    };

    the.stepListener = function(e) {
      e.preventDefault();

      if (the.steps && the.steps.length > 0) {
        let i = 0;
        const len = the.steps.length;
        for (; i < len; i++) {
          if (the.steps[i] === this) {
            the.clickedStepIndex = i + 1;

            KTEventHandler.trigger(the.element, "kt.stepper.click", the);

            return;
          }
        }
      }
    };

    // Event Handlers
    KtUtil.addEvent(the.btnNext, "click", the.nextListener);

    KtUtil.addEvent(the.btnPrevious, "click", the.previousListener);

    the.stepListenerId = KtUtil.on(
      the.element,
      "[data-kt-stepper-action=\"step\"]",
      "click",
      the.stepListener
    );

    // Bind Instance
    KtUtil.data(the.element).set("stepper", the);
  };

  var _goTo = function(index) {
    // Trigger "change" event
    KTEventHandler.trigger(the.element, "kt.stepper.change", the);

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
    KTEventHandler.trigger(the.element, "kt.stepper.changed", the);

    return the;
  };

  const _goNext = function() {
    return _goTo(_getNextStepIndex());
  };

  const _goPrevious = function() {
    return _goTo(_getPreviousStepIndex());
  };

  const _goLast = function() {
    return _goTo(_getLastStepIndex());
  };

  const _goFirst = function() {
    return _goTo(_getFirstStepIndex());
  };

  var _refreshUI = function() {
    let state = "";

    if (_isLastStep()) {
      state = "last";
    } else if (_isFirstStep()) {
      state = "first";
    } else {
      state = "between";
    }

    // Set state class
    KtUtil.removeClass(the.element, "last");
    KtUtil.removeClass(the.element, "first");
    KtUtil.removeClass(the.element, "between");

    KtUtil.addClass(the.element, state);

    // Step Items
    const elements = KtUtil.findAll(
      the.element,
      "[data-kt-stepper-element=\"nav\"], [data-kt-stepper-element=\"content\"], [data-kt-stepper-element=\"info\"]"
    );

    if (elements && elements.length > 0) {
      let i = 0;
      const len = elements.length;
      for (; i < len; i++) {
        const element = elements[i];
        const index = KtUtil.index(element) + 1;

        KtUtil.removeClass(element, "current");
        KtUtil.removeClass(element, "completed");
        KtUtil.removeClass(element, "pending");

        if (index === the.currentStepIndex) {
          KtUtil.addClass(element, "current");

          if (
            the.options.animation !== false &&
            element.getAttribute("data-kt-stepper-element") === "content"
          ) {
            KtUtil.css(element, "animationDuration", the.options.animationSpeed);

            const animation =
              _getStepDirection(the.passedStepIndex) === "previous"
                ? the.options.animationPreviousClass
                : the.options.animationNextClass;
            KtUtil.animateClass(element, animation);
          }
        } else {
          if (index < the.currentStepIndex) {
            KtUtil.addClass(element, "completed");
          } else {
            KtUtil.addClass(element, "pending");
          }
        }
      }
    }
  };

  let _isLastStep = function() {
    return the.currentStepIndex === the.totalStepsNumber;
  };

  var _isFirstStep = function() {
    return the.currentStepIndex === 1;
  };

  // eslint-disable-next-line no-unused-vars
  const _isBetweenStep = function() {
    return _isLastStep() === false && _isFirstStep() === false;
  };

  let _getNextStepIndex = function() {
    if (the.totalStepsNumber >= the.currentStepIndex + 1) {
      return the.currentStepIndex + 1;
    } else {
      return the.totalStepsNumber;
    }
  };

  let _getPreviousStepIndex = function() {
    if (the.currentStepIndex - 1 > 1) {
      return the.currentStepIndex - 1;
    } else {
      return 1;
    }
  };

  let _getFirstStepIndex = function() {
    return 1;
  };

  var _getLastStepIndex = function() {
    return the.totalStepsNumber;
  };

  const _getTotalStepsNumber = function() {
    return the.totalStepsNumber;
  };

  var _getStepDirection = function(index) {
    if (index > the.currentStepIndex) {
      return "next";
    } else {
      return "previous";
    }
  };

  const _getStepContent = function(index) {
    const content = KtUtil.findAll(the.element, "[data-kt-stepper-element=\"content\"]");

    if (content[index - 1]) {
      return content[index - 1];
    } else {
      return false;
    }
  };

  const _destroy = function() {
    // Event Handlers
    KtUtil.removeEvent(the.btnNext, "click", the.nextListener);

    KtUtil.removeEvent(the.btnPrevious, "click", the.previousListener);

    KtUtil.off(the.element, "click", the.stepListenerId);

    KtUtil.data(the.element).remove("stepper");
  };

  // Construct Class
  _construct();

  ///////////////////////
  // ** Public API  ** //
  ///////////////////////

  // Plugin API
  the.getElement = function(index) {
    return the.element;
  };

  the.goTo = function(index) {
    return _goTo(index);
  };

  the.goPrevious = function() {
    return _goPrevious();
  };

  the.goNext = function() {
    return _goNext();
  };

  the.goFirst = function() {
    return _goFirst();
  };

  the.goLast = function() {
    return _goLast();
  };

  the.getCurrentStepIndex = function() {
    return the.currentStepIndex;
  };

  the.getNextStepIndex = function() {
    return _getNextStepIndex();
  };

  the.getPassedStepIndex = function() {
    return the.passedStepIndex;
  };

  the.getClickedStepIndex = function() {
    return the.clickedStepIndex;
  };

  the.getPreviousStepIndex = function() {
    return _getPreviousStepIndex();
  };

  the.destroy = function() {
    return _destroy();
  };

  // Event API
  the.on = function(name, handler) {
    return KTEventHandler.on(the.element, name, handler);
  };

  the.one = function(name, handler) {
    return KTEventHandler.one(the.element, name, handler);
  };

  the.off = function(name, handlerId) {
    return KTEventHandler.off(the.element, name, handlerId);
  };

  the.trigger = function(name, event) {
    return KTEventHandler.trigger(the.element, name, event, the, event);
  };
};

// Static methods
KTStepper.getInstance = function(element) {
  if (element !== null && KtUtil.data(element).has("stepper")) {
    return KtUtil.data(element).get("stepper");
  } else {
    return null;
  }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
  module.exports = KTStepper;
}

