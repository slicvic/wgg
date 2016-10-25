'use strict';
var Logger = (function() {
    function info() {
        log(arguments, 'info');
    }

    function error() {
        log(arguments, 'error');
    }

    function log(message, type) {
        if (!window.console) {
            throw new Error('Logger: console is undefined');
        }
        if (!window.console[type]) {
            throw new Error('Logger: invalid log type');
        }

        console[type].apply(console, Array.prototype.slice.call(message));
    }

    return {
        info: info,
        error: error
    }
})();
