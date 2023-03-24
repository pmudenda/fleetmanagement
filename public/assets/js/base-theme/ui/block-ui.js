
// Class definition
//import KtUtil from "@/assets/js/base-theme/util/kt-util";
//import KTEventHandler from "@/assets/js/base-theme/event-handler/KTEventHandler";

let KTBlockUI = function(element, options) {
  //////////////////////////////
  // ** Private variables  ** //
  //////////////////////////////
  let the = this;

  if (typeof element === "undefined" || element === null) {
    return;
  }

  // Default options
  let defaultOptions = {
    zIndex: false,
    overlayClass: "",
    overflow: "hidden",
    message: "<span class=\"spinner-border text-primary\"></span>"
  };

  ////////////////////////////
  // ** Private methods  ** //
  ////////////////////////////

  let _construct = function() {
    if (KtUtil.data(element).has("blockui")) {
      the = KtUtil.data(element).get("blockui");
    } else {
      _init();
    }
  };

  let _init = function() {
    // Variables
    the.options = KtUtil.deepExtend({}, defaultOptions, options);
    the.element = element;
    the.overlayElement = null;
    the.blocked = false;
    the.positionChanged = false;
    the.overflowChanged = false;

    // Bind Instance
    KtUtil.data(the.element).set("blockui", the);
  };

  let _block = function() {
    if (KTEventHandler.trigger(the.element, "kt.blockui.block", the) === false) {
      return;
    }

    let isPage = the.element.tagName === "BODY";

    let position = KtUtil.css(the.element, "position");
    let overflow = KtUtil.css(the.element, "overflow");
    let zIndex = isPage ? 10000 : 1;

    if (the.options.zIndex > 0) {
      zIndex = the.options.zIndex;
    } else {
      if (KtUtil.css(the.element, "z-index") != "auto") {
        zIndex = KtUtil.css(the.element, "z-index");
      }
    }

    the.element.classList.add("blockui");

    if (position === "absolute" || position === "relative" || position === "fixed") {
      KtUtil.css(the.element, "position", "relative");
      the.positionChanged = true;
    }

    if (the.options.overflow === "hidden" && overflow === "visible") {
      KtUtil.css(the.element, "overflow", "hidden");
      the.overflowChanged = true;
    }

    the.overlayElement = document.createElement("DIV");
    the.overlayElement.setAttribute(
      "class",
      "blockui-overlay " + the.options.overlayClass
    );

    the.overlayElement.innerHTML = the.options.message;

    KtUtil.css(the.overlayElement, "z-index", zIndex);

    the.element.append(the.overlayElement);
    the.blocked = true;

    KTEventHandler.trigger(the.element, "kt.blockui.after.blocked", the);
  };

  let _release = function() {
    if (KTEventHandler.trigger(the.element, "kt.blockui.release", the) === false) {
      return;
    }

    the.element.classList.add("blockui");

    if (the.positionChanged) {
      KtUtil.css(the.element, "position", "");
    }

    if (the.overflowChanged) {
      KtUtil.css(the.element, "overflow", "");
    }

    if (the.overlayElement) {
      KtUtil.remove(the.overlayElement);
    }

    the.blocked = false;

    KTEventHandler.trigger(the.element, "kt.blockui.released", the);
  };

  let _isBlocked = function() {
    return the.blocked;
  };

  let _destroy = function() {
    KtUtil.data(the.element).remove("blockui");
  };

  // Construct class
  _construct();

  ///////////////////////
  // ** Public API  ** //
  ///////////////////////

  // Plugin API
  the.block = function() {
    _block();
  };

  the.release = function() {
    _release();
  };

  the.isBlocked = function() {
    return _isBlocked();
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
KTBlockUI.getInstance = function(element) {
  if (element !== null && KtUtil.data(element).has("blockui")) {
    return KtUtil.data(element).get("blockui");
  } else {
    return null;
  }
};

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
  module.exports = KTBlockUI;
}
