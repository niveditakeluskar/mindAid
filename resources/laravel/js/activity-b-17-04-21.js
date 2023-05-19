/**
 * 
 */

const URL_POPULATE = "/org/ajax/populateActivityForm";
var parameterscnt = 0;
var i =0;
var paramCount = 0;
var formlength =0;  

var populateForm = function (data, url) {
    $.get( url, data, function (result) {
        $("#append_parameter").empty();
        $("#editincrementdiv").empty();
        if ($('#practicesnew').hasClass("select2-hidden-accessible")) {
            $('#practicesnew').select2('destroy'); 
        }
        if($('#editpracticesgrp').hasClass("select2-hidden-accessible")){
            $('#editpracticesgrp').select2('destroy');  
        }

        if($('#editappendpracticesnew').hasClass("select2-hidden-accessible")){
            $('#editappendpracticesnew').select2('destroy'); 
        }

        if($('#editcandy').hasClass("select2-hidden-accessible")){
            $('#editcandy').select2('destroy');  
        }
        
        for (var key in result) {
            
            form.dynamicFormPopulate(key, result[key]);

            var parameters = result[key].static['paramdata'];
            // console.log(parameters);
            var timer_type = result[key].static.timer_type; 
            var activity_id = result[key].static.id;
            $("#activity_id").val(result[key].static.id); 

            if(timer_type == 2 || timer_type == 3){
                
                $("#defaulttimingnew").css("display","block");
                
                 paramCount = parameters.length;
                // var formlength = parameter.length;
                // console.log("paramCount",paramCount); 
             
            }
            else{
               
                $("#defaulttimingnew").css("display","none");
            }
            
            for (parameterscnt = 0; parameterscnt < paramCount; parameterscnt++) {
               
                var a =$('#editprac').html();  
                $("#append_parameter").append(a);  
                $("#append_parameter").show();
                $('#p1').attr("id","p1_"+parameterscnt);     
                      
                $('#time_required').attr("id","time_required_"+parameterscnt);
                $("#time_required_"+parameterscnt).val(parameters[parameterscnt]['time_required']); 
                $("#editpracticesgrp").attr("id","editpracticesgrp_"+(parameterscnt+1));

                $("#editpracticesgrp_"+(parameterscnt+1)).attr("name","editActivity["+(parameterscnt+1)+"]"+"[practiceGroup]"); 
                $("#time_required_"+parameterscnt).attr("name","editActivity["+(parameterscnt+1)+"]"+"[time_required]");     
                
                
                $("#editul").attr("id","editul_"+(parameterscnt+1));
                $("#editcandy").attr("id","editcandy_"+(parameterscnt+1));
                $("#editul_"+(parameterscnt+1)+ ">  li").each(function() {
                    // alert(this.text + ' ' + this.value);
                    $("#practicesnew").attr("id","practicesnew_"+(parameterscnt+1));
                    var v = $(this).find("input[type='checkbox']").val();
                    // console.log(v);    
                    $("#practicesnew_"+(parameterscnt+1)).attr("id","practicesnew_"+(parameterscnt+1)+"_"+v);   
                    // $("#practicesnew_"+(parameterscnt+1)+"_"+v).attr("name","activitypractice"+"["+"practicesnew_"+(parameterscnt+1)+"]["+v+"]");
                    $("#practicesnew_"+(parameterscnt+1)+"_"+v).attr("name","editActivity["+(parameterscnt+1)+"]"+"["+"practicesnew_"+(parameterscnt+1)+"]["+v+"]"); 
 
                });  
               
                


                $("#editpracticesgrp_"+(parameterscnt+1)).select2().val(parameters[parameterscnt]['practice_group']).trigger('change');
                // $("#practicesnew_"+(parameterscnt+1)).select2().val(parameters[parameterscnt]['practice_id']).trigger('change'); 

                for(var checkbox in parameters[parameterscnt]['practice_id_array']){  
                      
                    $("#practicesnew_"+(parameterscnt+1)+"_"+parameters[parameterscnt]['practice_id_array'][checkbox]).prop('checked',true);
                    $("#practicesnew_"+(parameterscnt+1)+"_"+parameters[parameterscnt]['practice_id_array'][checkbox]).trigger('change');         
                }  

                var x = $('#editul_'+(parameterscnt+1)+' li input[type="checkbox"]:checked').length;
                if (x != "") {
                    $('#editcandy_'+(parameterscnt+1)).html(x + " Practices" + " " + "selected");
                } else if (x < 1) {
                    $('#editcandy').html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
                }

                $('#editremovepracticetime').attr("id","editremovepracticetime_"+parameterscnt); 
               
                
                var a1 = $("#practicesnew_1").html(); 
                if(parameterscnt==0){ 
                    console.log("if");
                   $("#edit-pracdiv").show(); 
                   $("#editremove-pracdiv").hide();     
                }
                // else{
                //     // $("#edit-pracdiv").hide(); 
                //     $("#editremove-pracdiv").show();  
                // }
                var l = parameterscnt+1; 
                // var m = l-1; 
                
                // alert(m);
                $("#editpracticesgrp_"+l).on("change", function () {   
                    
                    var practicegrp_id = $(this).val(); 
                    var myid = $(this).attr("id");
                    // alert(myid);
                    var prcid = myid.split("_");
                  

                    if(practicegrp_id==0){   
                    }
                    if(practicegrp_id!=''){
                        util.geteditPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#editul_"+prcid[1]),prcid[1]);      
                    }
                    else{     
                    }     
                }); 
                
                
                 
            }


           
        }

        $('.multiDrop').on('click', function (event) {
            // alert("1");
        event.stopPropagation();  
        $(this).next('ul').slideToggle();

        });

      

        $(document).on('click', function () {
            if (!$(event.target).closest('.wrapMulDrop').length) {
                $('.wrapMulDrop ul').slideUp();
            }
        });

        $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
            // alert("2");
            // alert($(this).attr('id'));
            var mycheckboxid = $(this).attr('id');
            var ulid = mycheckboxid.split("_");
            // alert(ulid[1]);  

            var x = $('#editul_'+ulid[1]+' li input[type="checkbox"]:checked').length;
            if (x != "") {
                $('#editcandy_'+ulid[1]).html(x + "Practices" + " " + "selected");
            } else if (x < 1) {
                $('#editcandy_'+ulid[1]).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
            }
        }); 

     

        $('#editpracticetime').on('click', function () {   
           
            addpractice();
            });   

        $('.remove-icons').on('click', function () { 
            // alert("test");
            // alert($(this).id);
            // console.log($(this).attr('id'));  
            removepractice($(this).attr('id'));
            }); 
         
        $("#timer_type").on('change',function(){
                    // alert("timerchange"); 
                    var m =0;       
                    var type = $('option:selected', this).text() ; 
                
                    if(type=='Standard')
                    {
                        
                        $("#append_parameter").html(''); 
                        $("#editincrementdiv").html('');
                        var a =$('#editprac').html(); 
                        $("#append_parameter").append(a);     
                        $("#append_parameter").show();
                        $("#defaulttimingnew").show();
                        $("#edit-pracdiv").show(); 
                        $("#editremove-pracdiv").hide(); 
                        $('#editpracticetime').on('click', function () {   
                            addpractice();
                            
                            
                            });  

                        
                        
                    }
                    else if(type=='Backend'){
                        $("#append_parameter").html(''); 
                        $("#editincrementdiv").html('');
                        var a =$('#editprac').html(); 
                        $("#append_parameter").append(a);     
                        $("#append_parameter").show();
                        $("#defaulttimingnew").show();
                        $("#edit-pracdiv").show(); 
                        $("#editremove-pracdiv").hide(); 
                        $('#editpracticetime').on('click', function () {   
                            addpractice();
                            
                            
                            });
                    }
                    else{
                        $("#append_parameter").html('');
                        $("#editincrementdiv").html(''); 
                        $("#append_parameter").hide();
                        $("#defaulttimingnew").hide(); 
                        $("#edit-pracdiv").hide(); 
                        //$("#editremove-pracdiv").hide();
                        $("#editincrementdiv").hide(); 
                    }

                    $('#editcandy').on('click', function (event) {
                        // alert("1");
                    event.stopPropagation();  
                    $(this).next('ul').slideToggle();
                    });   
            
                    $(document).on('click', function () { 
                            if (!$(event.target).closest('#editul').length) {
                                $('.wrapMulDrop ul').slideUp();
                            }
                        });   
                
                        $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
                            // alert($(this).attr('id'));
                            // var mynewcheckboxid = $(this).attr('id');
                            // var myulid = mynewcheckboxid.split("_"); 
                            var x = $('#editul_0 li input[type="checkbox"]:checked').length;
                            // var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
                            if (x != "") {
                                $('#editcandy').html(x + " Practices" + " " + "selected");   
                            } else if (x < 1) {
                                $('#editcandy').html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
                            }
                        }); 


                        $("#editpracticesgrp").attr("id","editpracticesgrp_"+0);
                        $('#editul').attr("id","editul_"+0);

                        // $('#time_required').attr("id","time_required_"+parameterscnt);
                        // $("#time_required_"+parameterscnt).val(parameters[parameterscnt]['time_required']); 
                        // $("#editpracticesgrp").attr("id","editpracticesgrp_"+(parameterscnt+1));
        
                        $("#editpracticesgrp_"+(m)).attr("name","editActivity["+(m)+"]"+"[practiceGroup]");  
                        $("#time_required").attr("name","editActivity["+(m)+"]"+"[time_required]");  
                        
                        // $("#practicesnew").attr("name","practicesnew_"+(parameterscnt+1)); 
                        // var v = $(this).find("input[type='checkbox']").val();
                     
                        // $("#practicesnew_"+(parameterscnt+1)).attr("id","practicesnew_"+(parameterscnt+1)+"_"+v);   
                    
                        // $("#practicesnew").attr("name","editActivity["+(m+1)+"]"+"["+"practicesnew_"+(m+1)+"]["+v+"]");
                        $("#practicesnew").attr("name","editActivity["+(m)+"]"+"["+"practicesnew_"+(m)+"][]");   
                        // $("#editpracticesgrp_"+(parameterscnt+1)).select2().val(parameters[parameterscnt]['practice_group']).trigger('change');

                        $("#editpracticesgrp_"+0).on("change", function () {   
                            // alert("hello");
                            var practicegrp_id = $(this).val(); 
                            var myid = $(this).attr("id");
                            // alert(myid); 
                            var prcid = myid.split("_");
                          
                        
                            if(practicegrp_id==0){   
                            }
                            if(practicegrp_id!=''){
                                util.geteditPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#editul_"+prcid[1]),prcid[1]);      
                            }
                            else{     
                            }     
                        });      

                
        }); 

      
           
    } 

    

   
       

    ).fail(function (result) {
        console.error("Population Error:", result);
    });
}; 


 

var addpractice = function(){  
//    alert(paramCount);   
    paramCount = paramCount+1;  
    
    // alert(paramCount);
    $('#p2').attr("id","p2_"+i);  
    
    $('#editremovepracticetimenew').attr("id","editremovepracticetimenew_"+i);
    $('#editappendpracticesgrp').attr("id","editappendpracticesgrp_"+i);
    $('#edittime_required').attr("id","edittime_required_"+i);

    $("#editappendpracticesgrp_"+i).attr("name","editActivity["+(paramCount+i)+"]"+"[practiceGroup]"); 
    // $("#editremovepracticetimenew_"+i).attr("name","editActivity["+i+"]"+"[time_required]");
    $("#edittime_required_"+i).attr("name","editActivity["+(paramCount+i)+"]"+"[time_required]");   

    $("#editappendcandy").attr("id","editappendcandy_"+i);
    $("#editappendul").attr("id","editappendul_"+i);
    // $('#editappendpracticesnew').attr("id","editappendpracticesnew_"+i);
                $("#editappendul_"+i+ ">  li").each(function() {
                    var v = $(this).find("input[type='checkbox']").val();
    
                    $("#editappendpracticesnew").attr("id","editappendpracticesnew_"+(i));
                    
                    $("#editappendpracticesnew_"+i).attr("id","editappendpracticesnew_"+(i)+"_"+v);  
                    // $("#editappendpracticesnew_"+i+"_"+v).attr("name","activitypractice"+"["+"editappendpracticesnew_"+i+"]["+v+"]");
                    $("#editappendpracticesnew_"+i+"_"+v).attr("name","editActivity["+(paramCount+i)+"]"+"["+"editappendpracticesnew_"+(paramCount+i)+"]["+v+"]"); 
                  
                    
                }); 
     
        
    var b = $("#e1p1").html();      
   
    $("#editincrementdiv").append(b);   
    $("#editincrementdiv").show(); 
    $('#p2_'+i).attr("id","p2_"+(i+1));  
    $('#editappendpracticesgrp_'+i).attr("id","editappendpracticesgrp_"+(i+1));
    $('#edittime_required_'+i).attr("id","edittime_required_"+(i+1)); 
      //  $('#editappendpracticesnew_'+i).attr("id","editappendpracticesnew_"+(i+1));
      
    $("#editappendpracticesgrp_"+(i+1)).attr("name","editActivity["+(paramCount+i+1)+"]"+"[practiceGroup]"); 
    $("#edittime_required_"+(i+1)).attr("name","editActivity["+(paramCount+i+1)+"]"+"[time_required]"); 
    
    $("#editappendcandy_"+i).attr("id","editappendcandy_"+(i+1));             
    $("#editappendul_"+i).attr("id","editappendul_"+(i+1)); 
    $("#editappendul_"+(i+1)+ ">  li").each(function() { 
      
        var v = $(this).find("input[type='checkbox']").val();    
        $("#editappendpracticesnew_"+i+"_"+v).attr("id","editappendpracticesnew_"+(i+1)+"_"+v);
        // $("#editappendpracticesnew_"+(i+1)+"_"+v).attr("name","activitypractice"+"["+"editappendpracticesnew_"+(i+1)+"]["+v+"]");   
        $("#editappendpracticesnew_"+(i+1)+"_"+v).attr("name","editActivity["+(paramCount+i+1)+"]"+"["+"editappendpracticesnew_"+(paramCount+i+1)+"]["+v+"]");    
        
    });   
     
    
   
    $('#editremovepracticetimenew_'+i).attr("id","editremovepracticetimenew_"+(i+1));    
    
    
    $('.editappendcandy').on('click', function (event) { 
        // alert("1"); 
    event.stopPropagation();  
    $(this).next('ul').slideToggle();
    });    
    
    $(document).on('click', function () {
        if (!$(event.target).closest('.wrapMulDrop').length) {
            $('.wrapMulDrop ul').slideUp();   
        }
    });
    
    $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
        var newcheckboxid = $(this).attr('id');
        
        var uuulid = newcheckboxid.split("_");  
        var x = $('#editappendul_'+uuulid[1]+' li input[type="checkbox"]:checked').length;
        if (x != "") {
            $('#editappendcandy_'+uuulid[1]).html(x + "Practices" + " " + "selected");
        } else if (x < 1) {
            $('#editappendcandy_'+uuulid[1]).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
        }
    });

    $("#editappendpracticesgrp_"+i).on("change", function () { 
         
        var practicegrp_id = $(this).val();
        var myname = $(this).attr("name");
        var formlength = myname.substr(13,1) ;
        var myid = $(this).attr("id");
        var prcid = myid.split("_"); 
        
        if(practicegrp_id==0){   
        }
        if(practicegrp_id!=''){
            // util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#editappendpracticesnew_"+prcid[1])); 
            util.geteditappendPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#editappendul_"+prcid[1]),prcid[1],formlength);    
        }
        else{     
        }     
    }); 
    
       
    i=i+1;    
}

var removepractice = function(id)
{
    //  var button_id = $(this).attr('id'); 
    var button_id = id;
    //  var button_id = $(event.target).attr('id'); 
    //  alert(button_id);
      
     var b = button_id.split("_");  
     $("#p1_"+b[1]).remove();  
}







var onActivityMainForm = function (formObj, fields, response) {
    
    console.log("response" + response.status);  
    if (response.status == 200) {
        $("#add_activity_form")[0].reset();
        getActivitylisting();
        $("#success").show();
        $('#add-activities').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Activity Added Successfully!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    }
    // else{
    // //    $('form[name="add_activity_form"] .invalid-feedback').hide();
    // alert("else");
    // $("#success").hide();   
    // $("#msg").show();
    // // $('#edit-activities-modal').modal('hide');
    // var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please enter time in HH:MM:SS format!</strong></div>';
    // $("#msg").html(txt);     
    // } 
    // else if (response.status == 500 ) { 
    //     console.log( (response) );    
    //     alert( response); 
	// 	// $('form[name="number_tracking_vitals_form"] .invalid-feedback').hide();  
	// 	// $("form[name='number_tracking_vitals_form'] .alert-success").hide();
	// 	// var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill atleast 1 field!</strong></div>';
	// 	// $("#alert-danger").html(txt);
	// 	// $("#alert-danger").show();     
	// } 
};

var onEditActivityMainForm = function (formObj, fields, response) {
    if (response.status == 200) {
        $("#edit_activity_form")[0].reset();
        getActivitylisting();
        $("#success").show();
        $('#edit-activities-modal').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Activity Updated Successfully!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    }
};

var init = function () {

   
    
    form.ajaxForm("edit_activity_form", onEditActivityMainForm, function () {
        return true;
    });

    form.ajaxForm("add_activity_form", onActivityMainForm, function () {


        return true; 
    });

    $('body').on('click', '#activity-list .editActivity', function () {
        $("#edit_activity_form")[0].reset();
        addparameterscnt = 0;
        $('#edit-activities-modal').modal('show');
        var sPageURL = window.location.pathname;
        var parts = sPageURL.split("/"); 
        var id = $(this).data('id');
        var data = "";
        var formpopulateurl = URL_POPULATE + "/" + id;
        populateForm(data, formpopulateurl); 
    });

    $('body').on('click', '.change_activity_status_active', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to deactivate this Activity")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/activityStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    getActivitylisting();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Activity Deactivated Successfully!</strong></div>';
                    $("#success").html(txt);
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    //goToNextStep("call_step_1_id");
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { 
            return false; 
        }
    }); 

    $('body').on('click', '.change_activity_status_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to activate this Activity")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/activityStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    getActivitylisting();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Activity Activated Successfully!</strong></div>';
                    $("#success").html(txt);
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    //goToNextStep("call_step_1_id");
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { 
            return false; 
        }
    });
};

window.activity = { 
    init: init
    //onResult: onResult
}; 