
/**
 * Display a notification of the given type with the given message
 *
 * @param {String} type
 * @param {String} message
 */
var notify = function(type, message) {
    $.notify({
        // Options
        message: message
    }, {
        // Settings
        animate: {
            enter: "animated fadeInRightBig animated-fast",
            exit: "animated fadeOutRightBig animated-slow"
        },
        delay: 3000,
        placement: {
            from: "bottom",
            align: "right"
        },
        type: type,
        z_index: 10000
    });
}

/**
 * Display a danger-themed notification with the given message
 *
 * @param {String} message
 */
var danger = function(message) {
    notify("danger", message);
};

/**
 * Display an info themed-notification with the given message
 *
 * @param {String} message
 */
var info = function(message) {
    notify("info", message);
};

/**
 * Display a success-themed notification with the given message
 *
 * @param {String} message
 */
var success = function(message) {
	alert("notify success called");
    notify("success", message);
};

/**
 * Display a warning-themed notification with the given message
 *
 * @param {String} message
 */
var warning = function(message) {
    notify("warning", message);
};

// Export the module
window.notify = {
    danger:  danger,
    info:    info,
    success: success,
    warning: warning
};
