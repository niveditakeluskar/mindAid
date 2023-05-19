/**
 * display a nice label (Yes/No or Y/N) for a boolean value
 *
 * @param  {Mix}     value
 * @param  {Boolean} short Use the shortened Y/N instead of Yes/No
 * @return {String}
 */
var yesNo = function (value, short = false) {
    if (short) {
        return value ? "Y" : "N";
    }
    return value ? "Yes" : "No";
};

/**
 * Display a nice label (active/inactive) for a boolean value
 *
 * @param  {Mix}    value
 * @return {String}
 */
var activeInactive = function (value) {
    return value ? "Active" : "Inactive";
};

// Export the module
window.locale = {
    yesNo: yesNo,
    activeInactive: activeInactive
};
