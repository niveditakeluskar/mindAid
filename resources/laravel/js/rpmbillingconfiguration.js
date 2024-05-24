


const URL_RPM_BILLING_POPULATE = '/org/ajax/rpm_billing_populate/populate';
var populateForm = function (url) {
    //alert("url"+url);
    $.get(
        url,
        function (result) {
            // console.log(result);

            for (var key in result) {
                form.dynamicFormPopulate(key, result[key]);
                // var providerdata = result[key].static.providerdata;
              //  console.log(result);


            }
        }
    ).fail(function (result) {
        console.error("Population Error:", result);
    });

};

var onRPMbilling = function (formObj, fields, response) {
    // alert("ajax");   
    if(response.status == 200 && $.trim(response.data)=='') {
        $("#threshold-success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>RPM Billing Configuration saved successfully!</strong></div>';
        $("#threshold-success").html(txt);
        var scrollPos = $(".main-content").offset().top;
			$(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#threshold-success").hide();
        }, 3000);
    }
    else if(response.status == 200 && ($.trim(response.data) !='' && $.trim(response.data) == 'time is not valid')){
        $("#threshold-success").show();
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Vital Review Time must be between 00:00:00 and 23:59:59 hours!</strong></div>';
        $("#threshold-success").html(txt);
         var scrollPos = $(".main-content").offset().top;
			$(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#threshold-success").hide();
        }, 3000);
    }
    else{
        // if (response.data.errors.activity_id){
        //     $("#activity_div").html('The activity field is required.');
        // } else {
        //      $("#activity_div").html('');
        // }
    }
};

var onResult = function (form, fields, response, error) {
    if (error) {
    }
    else {
        window.location.href = response.data.redirect;
    }
};


var init = function () {
    form.ajaxForm("rpm_billing_form", onRPMbilling, function () {
        return true;
    });
   
        var url = URL_RPM_BILLING_POPULATE;       
        populateForm(url);
  

     $('#headqadd').change(function() {        
        if(this.checked) {      
         $('#headquaters_fname').val($('#billing_fname').val()); 
         $('#headquaters_lname').val($('#billing_lname').val()); 
         $('#headquaters_phone').val($('#billing_phone').val()); 
         $('#headquaters_address').val($('#billing_address').val()); 
         $('#headquaters_email').val($('#billing_email').val()); 
         $('#headquaters_city').val($('#billing_city').val()); 
         $('#headquaters_state').val($("select[name='billing_state']").val());
         $("select[name='headquaters_state']").val($("select[name='billing_state']").val());
         $('#headquaters_zip').val($('#zip').val());               
        }      
        else
        {
          $('#headquaters_fname').val('');
          $('#headquaters_lname').val('');
          $('#headquaters_phone').val('');
          $('#headquaters_address').val('');
          $('#headquaters_email').val('');
          $('#headquaters_city').val('');
          $("select[name=headquaters_state] option[value='']").attr('selected', true);        
          $('#headquaters_zip').val('');
        }
    });
};

 

window.rpmbillingconfiguration = {
    init: init,
    onResult: onResult
};