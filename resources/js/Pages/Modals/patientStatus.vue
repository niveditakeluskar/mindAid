<template>
    <div>

    </div>
</template>
<script>
import axios from 'axios';

export default {
  setup() {
    const callExternalFunctionWithParams = (param1, param2) => {
      const activeDeactiveModal = document.getElementById('active-deactive');
      if (activeDeactiveModal) {
        $(activeDeactiveModal).modal('show');
      } else {
        console.error('Modal element not found or jQuery/Bootstrap not properly loaded');
      }
     
      if ($.isNumeric(param1) == true) {
        //patient list
      /*   var module = $("input[name='module_id']").val();
        alert(module);
        $('#enrolledservice_modules').val(module).trigger('change');
        $('#enrolledservice_modules').change();
      } else { */
       let patientId = param1;
        //worklist
        var selmoduleId = $("#modules").val();
        axios({
          method: "GET",
          url: `/patients/patient-module/${patientId}/patient-module`,
        }).then(function (response) {
          $('.enrolledservice_modules').html('');
          const enr = response.data;
          var count_enroll = enr.length;
          for (var i = 0; i < count_enroll; i++) {
            $('.enrolledservice_modules').append(`<option value="${response.data[i].module_id}">${response.data[i].module.module}</option>`);
          }
          $("#enrolledservice_modules").val(selmoduleId).trigger('change');
        }).catch(function (error) {
          console.error(error, error.response);
        });
        var status = param2;
        $("form[name='active_deactive_form'] #worklistclick").val("1");
        $("form[name='active_deactive_form'] #patientid").val(patientId);
        $("form[name='active_deactive_form'] #date_value").hide();
        $("form[name='active_deactive_form'] #fromdate").hide();
        $("form[name='active_deactive_form'] #todate").hide();
        if (status == 0) {
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").hide();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 1) {
          $("form[name='active_deactive_form'] #role1").hide();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 2) {
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").hide();
          $("form[name='active_deactive_form'] #role3").show();
        }
        if (status == 3) {
          $("form[name='active_deactive_form'] #role1").show();
          $("form[name='active_deactive_form'] #role0").show();
          $("form[name='active_deactive_form'] #role2").show();
          $("form[name='active_deactive_form'] #role3").hide();
        }
      }else{
        $(activeDeactiveModal).modal('hide');
      }
    };
  // When the Submit button is clicked within the modal
      $('.submit-active-deactive').on('click', function () {
        // Serialize the form data
        const formData = $('#active_deactive_form').serialize();

        // Make an AJAX POST request to the specified route
        $.ajax({
          type: 'POST',
          url: '/patients/patient-active-deactive',
          data: formData,
          success: function (response) {
            // Display the response message within the modal
            $('#patientalertdiv').html('<div class="alert alert-success">' + response.message + '</div>');

            // Optionally, close the modal after a certain delay
            setTimeout(function () {
              $('#active-deactive').modal('hide');
            }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
          },
          error: function (xhr, status, error) {
            // Display error messages in case of failure
            $('#patientalertdiv').html('<div class="alert alert-danger">Error: ' + error + '</div>');
          }
        });
      });
    return { callExternalFunctionWithParams };
  },
};

</script>