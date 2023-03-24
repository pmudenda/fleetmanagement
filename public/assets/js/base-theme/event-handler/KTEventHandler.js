//import * as KtUtil from '@/assets/js/base-theme/util/kt-util';

// Class definition
let KTEventHandler = (function() {
  ////////////////////////////
  // ** Private Variables  ** //
  ////////////////////////////
  let _handlers = {};

  ////////////////////////////
  // ** Private Methods  ** //
  ////////////////////////////
  const _triggerEvent = function(element, name, target) {
    let returnValue = true;
    let eventValue;

    if (KtUtil.data(element).has(name) === true) {
      const handlerIds = KtUtil.data(element).get(name);
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

  const _addEvent = function(element, name, callback, one) {
    const handlerId = KtUtil.getUniqueId("event");
    let handlerIds = KtUtil.data(element).get(name);

    if (!handlerIds) {
      handlerIds = [];
    }

    handlerIds.push(handlerId);

    KtUtil.data(element).set(name, handlerIds);

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

  const _removeEvent = function(element, name, handlerId) {
    const handlerIds = KtUtil.data(element).get(name);
    const index = handlerIds && handlerIds.indexOf(handlerId);

    if (index !== -1) {
      handlerIds.splice(index, 1);
      KtUtil.data(element).set(name, handlerIds);
    }

    if (_handlers[name] && _handlers[name][handlerId]) {
      delete _handlers[name][handlerId];
    }
  };

  ////////////////////////////
  // ** Public Methods  ** //
  ////////////////////////////
  return {
    trigger: function(element, name, target) {
      return _triggerEvent(element, name, target);
    },

    on: function(element, name, handler) {
      return _addEvent(element, name, handler);
    },

    one: function(element, name, handler) {
      return _addEvent(element, name, handler, true);
    },

    off: function(element, name, handlerId) {
      return _removeEvent(element, name, handlerId);
    },

    debug: function() {
      for (let b in _handlers) {
        // eslint-disable-next-line no-prototype-builtins
        if (_handlers.hasOwnProperty(b)) console.log(b);
      }
    }
  };
})();

// Webpack support
if (typeof module !== "undefined" && typeof module.exports !== "undefined") {
  module.exports = KTEventHandler;
}


