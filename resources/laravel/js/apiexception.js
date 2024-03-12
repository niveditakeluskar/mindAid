


const URL_POPULATE = "/ajax/populateApiExceptionForm";


// var populateForm = function (data, url) {

// 	$.get(
// 		url, 
// 		data,
// 		function (result) {
// 			console.log(result);
// 			for (var key in result) {
// 				form.dynamicFormPopulate(key, result[key]);
// 				// updateBmi();

// 			}
// 		}
// 	).fail(function (result) {
// 		console.error("Population Error:", result);
// 	});

// };

// function  Webhookdetail(id){
//     alert("1");
//     alert(id);
  
//     $("#rpm_webhook_modal").modal('show');
//     var formpopulateurl =  "/ajax/populateApiExceptionForm/" +  id;
//     populateForm(data, formpopulateurl);  

// }  





var test = function()
{
    alert("hello");
}

var init = function () {
  
    alert("init");
    // $('.webhookdetail').click(function() {
    //     alert("1");

    // }); 
}

window.apiexception = {
	init: init
	
};
