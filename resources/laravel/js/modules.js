        /**
 * 
 */
//const URL_POPULATE = "/org/ajax/populateForm";


/**
 * Populate the form of the given patient
 *
 * 
 */
var populateForm = function (data, url) {
    $.get(
        url, 
        data,
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

$('#addModule').click(function (){
    
    $('#modelHeading1').html("Add Module");
    $(".is-invalid").removeClass("is-invalid");
    $('.invalid-feedback').html("");
    $('#add_module_modal').modal('show');
});

$('body').on('click', '.editroles', function () {
    var id = $(this).data('id');
    $.get("ajax/editModule" +'/' +id+'/edit', function (data) {
        $('#edit_module_heading').html("Edit Module");
        $('#edit_module_modal').modal('show');
        $('#id').val(id);
        $('#module').val(data.module);
            
    }) 
});

$('#addcompModule').click(function () {
    $('#modelHeading1').html("Add Sub Module");
    $('#add_comp_module_modal').modal('show');
});

$('body').on('click', '.edit_comp', function () {
    var id = $(this).data('id');
    $.get("ajax/editComponent" +'/' +id+'/edit', function (data) {
        $('#modelHeading').html("Edit Sub Module");
        $('#edit_comp_module_modal').modal('show');
        $('#compid').val(data.id);
        $('#edit_comp_module_id').val(data.module_id);
        $('#comp').val(data.components);  
    })
});

$('body').on('click', '.changeModuleStatus_active', function () {
     var id = $(this).data('id');
      if(confirm("Are you sure you want to Deactivate this Module")){
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              type   : 'post',
              url    : '/org/changeModuleStatus/'+id,
             // data: {"_token": "{{ csrf_token() }}","id": id},
              data   :  {"id": id},
              success: function(response) {
                renderModulesTable();
                $("#msgActive").show();
                var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Module Deactivated Successfully!</strong></div>';
              $("#msgActive").html(txt);
              var scrollPos = $(".main-content").offset().top;
          $(window).scrollTop(scrollPos);
          //goToNextStep("call_step_1_id");
          setTimeout(function () { $("#msgActive").hide(); 
          }, 3000);
        }
          });
      }else{ return false;}
});
$('body').on('click', '.changeModuleStatus_deactive', function () {
      var id = $(this).data('id');
      if(confirm("Are you sure you want to Activate this Module")){
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              type   : 'post',
              url    : '/org/changeModuleStatus/'+id,
             // data: {"_token": "{{ csrf_token() }}","id": id},
              data   :  {"id": id},
              success: function(response) {
                renderModulesTable();
                $("#msg").show();
                var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Module Activated Successfully!</strong></div>';
              $("#msg").html(txt);
              var scrollPos = $(".main-content").offset().top;
          $(window).scrollTop(scrollPos);
          //goToNextStep("call_step_1_id");
          setTimeout(function () { $("#msg").hide(); 
          }, 3000);
              }
          });
      }else{ return false;}
});

$('body').on('click', '.changeComponentStatus_active', function () {
    var id = $(this).data('id');
        if(confirm("Are you sure you want to Deactivate this Sub Module")){
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              type   : 'post',
              url    : '/org/changeComponentStatus/'+id,
             // data: {"_token": "{{ csrf_token() }}","id": id},
              data   :  {"id": id},
              success: function(response) {
                renderSubModulesTable();
                $("#msgActive").show();
                var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Sub Module Deactivated Successfully!</strong></div>';
              $("#msgActive").html(txt);
              var scrollPos = $(".main-content").offset().top;
          $(window).scrollTop(scrollPos);
          //goToNextStep("call_step_1_id");
          setTimeout(function () { $("#msgActive").hide(); 
          }, 3000);
        }
        });
    }else{ return false;}
});

$('body').on('click', '.changeComponentStatus_deactive', function () {
  var id = $(this).data('id');
  if(confirm("Are you sure you want to Activate this Sub Module")){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
          type   : 'post',
          url    : '/org/changeComponentStatus/'+id,
         // data: {"_token": "{{ csrf_token() }}","id": id},
          data   :  {"id": id},
          success: function(response) {
            renderSubModulesTable();
            $("#msg").show();
            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Sub Module Activated Successfully!</strong></div>';
          $("#msg").html(txt);
          var scrollPos = $(".main-content").offset().top;
      $(window).scrollTop(scrollPos);
      //goToNextStep("call_step_1_id");
      setTimeout(function () { $("#msg").hide(); 
      }, 3000);
          }
      });
  }else{ return false;}
}); 

 
var onAddmodule = function (formObj, fields, response) {
        // console.log(response);
    if (response.status == 200) {
        $("#add_module_form")[0].reset(); 
        $("#add_module_modal").modal('hide');
        $("#success-alert").show();
        var scrollPos = $(".main-content").offset().top;
        renderModulesTable();
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $('.alert').fadeOut('fast'); 
        }, 3000);
    }
};
 
var onEditmodule = function (formObj, fields, response) {
    // console.log(response);
    if (response.status == 200) {
        $("#edit_module_form")[0].reset(); 
        $('#edit_module_modal').modal('hide');
        $("#success-alert").show();
        var scrollPos = $(".main-content").offset().top;
        renderModulesTable();
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $('.alert').fadeOut('fast'); 
        }, 3000);
    }
};

var onAddCompmodule = function (formObj, fields, response) {
        // console.log(response);
    if (response.status == 200) {
        $("#add_comp_module_form")[0].reset(); 
        $("#add_comp_module_modal").modal('hide');
        $("#success-alert").show();
        var scrollPos = $(".main-content").offset().top;
        renderSubModulesTable();
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $('.alert').fadeOut('fast'); 
        }, 3000);
    }
};
 
var onEditCompmodule = function (formObj, fields, response) {
    // console.log(response);
    if (response.status == 200) {
        $("#edit_comp_module_form")[0].reset(); 
        $('#edit_comp_module_modal').modal('hide');
        $("#success-alert").show();
        var scrollPos = $(".main-content").offset().top;
        renderSubModulesTable();
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $('.alert').fadeOut('fast'); 
        }, 3000);
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
    // renderModulesTable();
    // renderSubModulesTable();
    form.ajaxForm("add_module_form", onAddmodule, function () {
        return true;
    });
    form.ajaxForm("edit_module_form", onEditmodule, function () {
     return true;
    });
    form.ajaxForm("add_comp_module_form", onAddCompmodule, function () {
        return true;
    });
    form.ajaxForm("edit_comp_module_form", onEditCompmodule, function () {
     return true;
    });

};

window.modules = {
    init: init,
    onResult: onResult
};