@extends('Theme::layouts_2.to-do-master')
@section('main-content')

    @foreach($patient as $checklist)
    @csrf
    <div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        <div class="col-md-12">
            {{ csrf_field() }}
            <!--Add view Patient Overview -->
            @include('Ccm::careplandevelopment.patient-overview')
        </div>
    </div>
    @endforeach
    <script type="text/javascript">
     $('.click_id').click(function(){
        // debugger;
        evID = $(this).closest('td').prevAll('.patient_data').text();
        // alert(evID);
            if(evID=='Diagnosis Codes'){
                $('.smartwizard').removeAttr('id');
                $('#family .smartwizard').attr('id','smartwizard');
                $('#diagnosis-codes').show();
                $('#allergy-information').hide();
                $('#provider-information').hide();
                $('#family').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();
            }
            if(evID=='Allergies'){
                // debugger;
                $('.smartwizard').removeAttr('id');
                $('#allergy-information .smartwizard').attr('id','smartwizard');
                $('#allergy-information').show();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#family').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();
                id = $("#patient_id").val(); 
                // var sectionId = $("#food-icon-pill").attr('href');
                // sectionId = sectionId.substring(1, sectionId.length);
                renderAllergiesTable(id, 'emr_food');
                // renderAllergiesTable(id, 'food');

            }
            if(evID=='Family'){
          
                $('.smartwizard').removeAttr('id');
                $('#family .smartwizard').attr('id','smartwizard');
                $('#family').show();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();  
            
            }
            if(evID=='Provider'){
                $('.smartwizard').removeAttr('id');
                $('#provider-information .smartwizard').attr('id','smartwizard');
                $('#provider-information').show();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#family').hide();
                $('#healthcare-services').hide();
                $('#medications').hide();
                $('#numbers-tracking').hide();  
            }
            if(evID=='Medications'){
                $('.smartwizard').removeAttr('id');
                $('#medications .smartwizard').attr('id','smartwizard');
                $('#medications').show();
                $('#family').hide();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').hide();
                $('#numbers-tracking').hide();
            }

            if(evID=='Services'){
                debugger;
                $('.smartwizard').removeAttr('id');
                $('#healthcare-services .smartwizard').attr('id','smartwizard');
                $('#medications').hide();
                $('#family').hide();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').show();
                $('#numbers-tracking').hide();
                id = $("#patient_id").val(); 
                // var sectionId = $("#dme-services-icon-pill").attr('href');
                // sectionId = sectionId.substring(1, sectionId.length);
                renderAllergiesTable(id, 'emr_dme');
                // renderAllergiesTable(id, 'dme');
            }

            if(evID=='Numbers Tracking'){
                $('.smartwizard').removeAttr('id');
                $('#numbers-tracking .smartwizard').attr('id','smartwizard');
                $('#numbers-tracking').show();
                $('#medications').hide();
                $('#family').hide();
                $('#allergy-information').hide();
                $('#diagnosis-codes').hide();
                $('#provider-information').hide();
                $('#healthcare-services').hide();
            }
           // $('#smartwizard').smartWizard();

        });

        $('.patient_data-save').on('click', function() {

modal_name = $("#myIconTab-care-plan-development li a.active").attr('href').slice(1);
tabid = $(this).closest('.tab-pane').attr('id');
alert(modal_name);
alert(tabid);
// $("#"+tabid+" form").attr('id', tabid+'_family_data-form');
if (tabid == 'patient') {
    $("#" + tabid + " form").attr('id', modal_name + "_" + tabid + '_patient_data-form');
    var form_id = modal_name + "_" + tabid + '_patient_data-form';
    alert(form_id);
    var data = $("#" + form_id).serialize();
    alert(data);
    URL = '/ccm/savePatientData';
    DATA = "tab_name" + '=' + modal_name + "&" + data;
    alert(DATA);
    ajax_careplandata(URL, DATA);
} else if (tabid == 'spouse' || tabid == 'care-giver' || tabid == 'emergency-contact') {
    $("#" + tabid + " form").attr('id', tabid + '_family_data-form');
    var form_id = tabid + '_family_data-form';
    // alert(form_id);
    var data = $("#" + form_id).serialize();
    alert(data);
    URL = '/ccm/savePatientfamilyData';
    DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
    // alert();
    ajax_careplandata(URL, DATA);
} else if (tabid == 'pcp' || tabid == 'dentist' || tabid == 'vision') {
    $("#" + tabid + " form").attr('id', tabid + '_provider-form');
    var form_id = tabid + '_provider-form';
    // alert(form_id);
    var data = $("#" + form_id).serialize();
    URL = '/ccm/savePatientprovidersData';
    DATA = data;
    ajax_careplandata(URL, DATA);
} else if (tabid == 'specialists') {
    // alert('specialists');
    $("#" + tabid + " form").attr('id', tabid + '_specialistprovider-form');
    var form_id = tabid + '_specialistprovider-form';
    // alert(form_id);
    var data = $("#" + form_id).serialize();
    URL = '/ccm/savePatientprovidersData';
    DATA = data;
    ajax_careplandata(URL, DATA);
} else if (tabid == 'medication') {
    // ("#"+tabid+" form").attr('id', tabid+'_med-form');
    // var form_id =tabid+'_med-form';
    form_id = 'med-form';
    alert(form_id);
    var data = $("#" + form_id).serialize();
    URL = '/ccm/savePatientmedicationData';
    DATA = data;
    ajax_careplandata(URL, DATA);
} else if (tabid == 'vitals') {
    // ("#"+tabid+" form").attr('id', tabid+'_med-form');
    // var form_id =tabid+'_med-form';
    form_id = 'vital-form';
    alert(form_id);
    var data = $("#" + form_id).serialize();
    URL = '/ccm/savePatientvitalData';
    DATA = data;
    ajax_careplandata(URL, DATA);
} else if (tabid == 'sibiling-sibling') {
    // ("#"+tabid+"form").attr('id', tabid+'_siblings-form');
    // var form_id = tabid+'_siblings-form';
    // alert(form_id);
    var data = $("#sibiling-form").serialize();
    URL = '/ccm/savePatientfamilyData';
    DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
    ajax_careplandata(URL, DATA);
} else if (tabid == 'children-sibling') {
    // ("#"+tabid+"form").attr('id', tabid+'_siblings-form');
    // var form_id = tabid+'_siblings-form';
    // alert(form_id);
    var data = $("#children-form").serialize();
    URL = '/ccm/savePatientfamilyData';
    DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
    ajax_careplandata(URL, DATA);
} else if (tabid == 'grand_children-sibling') {
    var data = $("#grand_children-form").serialize();
    URL = '/ccm/savePatientfamilyData';
    DATA = "relationship" + '=' + tabid + "&" + "tab_name" + '=' + modal_name + "&" + data;
    ajax_careplandata(URL, DATA);
} else if (tabid == 'care-plan-development-reviewdata-pcp') {
    $("#" + tabid + " form").attr('id', tabid + '_doctor-info-form');
    var form_id = tabid + '_doctor-info-form';
    alert(form_id);
    var data = $("#" + form_id).serialize();
    URL = '/ccm/savePatientprovidersData';
    DATA = data;
    ajax_careplandata(URL, DATA);
}

});


$('#open_health_services').click(function(){
            debugger;
            $('.smartwizard').removeAttr('id');
            // $('#healthcare-services .smartwizard').attr('id','smartwizard');
            $("[name='healthcare_services']").show();
            // $('#healthcare-services').show();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            $('#travel').hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
            id = $("#patient_id").val(); 
            // var sectionId = $("#dme-services-icon-pill").attr('href');
            // sectionId = sectionId.substring(1, sectionId.length);
            renderAllergiesTable(id, 'review_dme');
        });
        $('#open_allergies').click(function(){
            debugger;
            $('.smartwizard').removeAttr('id');
            // $('#allergy-information .smartwizard').attr('id','smartwizard');
            // $('#allergy-information').show();
            $("[name='allergy_information']").show();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#travel').hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
            id = $("#patient_id").val(); 
            // var sectionId = $("#food-icon-pill").attr('href');
            // sectionId = sectionId.substring(1, sectionId.length);
            renderAllergiesTable(id, 'review_food');
        });
        $('#open_medications').click(function(){
            // debugger;
            $('.smartwizard').removeAttr('id');
            $('#allergy-information .smartwizard').attr('id','smartwizard');
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").show();
            // $('#medications').show();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#travel').hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_travel').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#travel .smartwizard').attr('id','smartwizard');
            $('#travel').show();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#hobbies').hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_hobbies').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#hobbies .smartwizard').attr('id','smartwizard');
            $('#hobbies').show();
            $('#travel').hide();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#pets').hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_pets').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#pets .smartwizard').attr('id','smartwizard');
            $('#pets').show();
            $('#hobbies').hide();
            $('#travel').hide();
            $("[name='allergy_information']").hide();
            // $('#allergy-information').hide();
            $("[name='medications']").hide();
            // $('#medications').hide();
            // $('#healthcare-services').hide();
            $("[name='healthcare_services']").hide();
            $('#codes-info-for-medical').hide();
            $('#doctors-information').hide();
            $('#live-info-for-anyone').hide();
            $("[name='family_info']").hide();
            $("[name='grandchildren_info']").hide();
            $('#smartwizard').smartWizard();
        });
        $('#open_family_info').click(function(){
            // debugger;
            // alert('hey baby');
            $('.smartwizard').removeAttr('id');
            // $('#family-info .smartwizard').attr('id','smartwizard');
            $("[name='family_info']").show();
            // $('#family-info').show();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
         $('#open_live_info').click(function(){
            $('.smartwizard').removeAttr('id');
            // $('#live-info-for-anyone .smartwizard').attr('id','smartwizard');
            $("[name='live_info_for_anyone']").show();
            // $('#live-info-for-anyone').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
         $('#open_sibiling_info').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#sibiling-info .smartwizard').attr('id','smartwizard');
            $('#sibiling-info').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
         $('#open_children_info').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#children-info .smartwizard').attr('id','smartwizard');
            $('#children-info').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
          $('#open_grandchildren_info').click(function(){
            // debugger;
            $('.smartwizard').removeAttr('id');
            // $('#grandchildren .smartwizard').attr('id','smartwizard');
            $("[name='grandchildren_info']").show();
            // $('#grandchildren-info').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#doctors-information').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
          $('#open_doctors_information').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#doctors-information .smartwizard').attr('id','smartwizard');
            $('#doctors-information').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#codes-info-for-medical').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
          $('#open_codes_info_for_medical').click(function(){
            $('.smartwizard').removeAttr('id');
            $('#codes-info-for-medical .smartwizard').attr('id','smartwizard');
            $('#codes-info-for-medical').show();
            // $('#family-info').hide();
            $("[name='family_info']").hide();
            $('#live-info-for-anyone').hide();
            $('#sibiling-info').hide();
            $('#children-info').hide();
            $('#grandchildren-info').hide();
            $('#doctors-information').hide();
            $("[name='medications']").hide();
            $("[name='allergy_information']").hide();
            $('#pets').hide();
            $('#travel').hide();
            $("[name='healthcare_services']").hide();
            $("[name='grandchildren_info']").hide();
            $('#hobbies').hide();
            $('#smartwizard').smartWizard();
        });
           

        $("#additional_hobby").click(function(){
            $("#appendHobbyDiv").append($("#hobby").html());
        });
        $("#additional_pet").click(function(){
            $("#appendPetDiv").append($("#pet").html());
        });
</script>
@endsection