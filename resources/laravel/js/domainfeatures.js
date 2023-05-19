const URL_POPULATE = "editDomainFeatures";

var populateForm = function (id, url) {
    $.get(
        url,
        id,
        function (result) { 
            console.log(result);
            for (var key in result) {
                form.dynamicFormPopulate(key, result[key]);
            }
        }
 
    ).fail(function (result) {
        console.error("Population Error:", result);
    }); 

};

var onSaveDomainFeatures = function (formObj, fields, response) {
    if (response.status == 200) {
        $("#success").show();
        domainfeatureslistData();
        if($("#modelHeading1").text()=='Add Domain Features'){
            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>domain Features Added Successfully!</strong></div>';
        }else{
            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>domain Features Updated Successfully!</strong></div>';
        }
        $("#domain_features_form")[0].reset();
        $('#myModal1').modal('hide');
        
        $("#success").html(txt); 
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos); 
        setTimeout(function () {
            $("#success").hide(); 
        }, 3000); 

    } 
    // else{
    //     var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill mandatory fields!</strong></div>';
    //     $("#success").html(txt);
    //     $("#success").show();
    //     setTimeout(function () {
    //         $("#success").hide();
    //     }, 3000);
    // }
};
 
$('#add-btn').click(function () {
    // debugger;
    $("input").removeClass("is-invalid");
    $("select").removeClass("is-invalid");
    $('.invalid-feedback').html('');
    $("#modelHeading1").text('Add Domain Features');
    $("#domain_features_form")[0].reset();
    $('#myModal1').modal('show');
    $('#edit_hid_id').val("");
}); 

var init = function () {
    form.ajaxForm("domain_features_form", onSaveDomainFeatures, function () {
        return true;
    }); 

    $('body').on('click', '.editDomainFeatures', function () {
        $("input").removeClass("is-invalid");
        $("select").removeClass("is-invalid");
        $('.invalid-feedback').html('');
        $("#modelHeading1").text('Edit Domain Features');
        $('#myModal1').modal('show');
        var id = $(this).data('id'); 
        var data = ""; 
        // var reasons_id = $('#reasons_id').val(id);
        var formpopulateurl = URL_POPULATE+'/'+ id +'/edit';
        populateForm(data, formpopulateurl);
    });


    $('body').on('click', '.change_Domainstatus_active', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Deactivate the Status")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: 'ajax/rCare/changeDomainFeaturesStatus/' + id + '/update',
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    domainfeatureslistData(); 
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Reason Deactivated Successfully!</strong></div>';
                    $("#success").html(txt);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { return false; } 
    });
    $('body').on('click', '.change_Domainstatus_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Activate the Status")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: 'ajax/rCare/changeDomainFeaturesStatus/' + id + '/update',
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) { 
                    domainfeatureslistData();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Reason Activated Successfully!</strong></div>';
                    $("#success").html(txt);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { return false; }
    }); 

};



window.domainfeatures= {
    init: init
    //onResult: onResult
};




                // if(result[key].static['url'] != null){
                //     var url = result[key].static['url']; 
                //     $('#domain_features_form input[name="url"]').val(url);
                // }

                // if(result[key].static['features'] != null){ 
                //     var features = result[key].static['features']; 
                //     $('#domain_features_form input[name="features"]').val(features);
                // }

                // if(result[key].static['password_attempts'] != null){ 
                //     var password_attempts = result[key].static['password_attempts']; 
                //     $('#domain_features_form input[name="password_attempts"]').val(password_attempts);
                // }

                // if(result[key].static['digit_in_otp'] != null){ 
                //     var digit_in_otp = result[key].static['digit_in_otp']; 
                //     $('#domain_features_form input[name="digit_in_otp"]').val(digit_in_otp);
                // }

                // if(result[key].static['otp_max_attempts'] != null){ 
                //     var otp_max_attempts = result[key].static['otp_max_attempts']; 
                //     $('#domain_features_form input[name="otp_max_attempts"]').val(otp_max_attempts);
                // }

                // if(result[key].static['otp_text']==1){ 
                //     var otp_text = result[key].static['otp_text']; 
                //     $('#domain_features_form input[name="otp_text"]').val(otp_text);
                // }

                // if (result[key].static['otp_text'] != null) {
                //     var otp_text = result[key].static['otp_text'];
                //     if (otp_text == 1) {
                //         $('#otp_text').prop('checked', true);// Checks it
                //     } else {
                //         $('#otp_text').prop('checked', false); // Unchecks it
                //     }
                // }

                // if (result[key].static['otp_email'] != null) {
                //     var otp_email = result[key].static['otp_email'];
                //     if (otp_email == 1) {
                //         $('#otp_email').prop('checked', true);// Checks it
                //     } else {
                //         $('#otp_email').prop('checked', false); // Unchecks it
                //     }
                // }