var init = function () {

$.ajax({
    url: "/ajax/rCare/fetchServices",
    type: 'GET',
    dataType: 'json', // added data type
    success: function(res) {
        console.log(res);
        alert(res);
    }
});

}

window.services = {
    init: init
};