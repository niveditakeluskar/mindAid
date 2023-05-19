<div>
   <!-- <form action="" method="post" name="" id=""> -->
      {{-- <!-- {{ csrf_field() }} --> --}}
      <!-- Review Dropdown -->
      @include('Rpm::monthlyService.review-data-dropdown')
      <!-- Review Dropdown -->

      <!-- Call Section-->
      
      <!-- Call Section-->      

     

      <!-- Scripts Modal -->
      @include('Rpm::monthlyService.call-script-modal')
      <!-- Scripts Modal -->

      <!-- Within Guidelines -->
      @include('Rpm::monthlyService.within_guidelines')
      <!-- Within Guidelines -->

      <!-- In Call PCP Office Range Modal-->
      @include('Rpm::monthlyService.in-call-pcp-office-range')
      <!-- In Call PCP Office Range Modal-->

      <!-- In Call PCP Emergency Range Modal-->
      @include('Rpm::monthlyService.in-call-pcp-emergency-range')
      <!-- In Call PCP Emergency Range Modal-->
   <!-- </form> -->
</div>
@section('page-js')
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<script>
   CKEDITOR.replace( 'editor');
</script>
<script>
    $(function() {
        $("#review_data").change(function() {
           if($("#review_data").val() == 1 ){
                $("#buttons").show();
                $("#within_guidelines").hide();
                $("#question").hide();
                $("#questionnaireButtons").hide();
                $("#record_details").hide();
           }
           if($("#review_data").val() == 2 ){
                $("#within_guidelines").show();
                $("#buttons").hide();
                $("#content-template").hide();
                $("#call_section").hide(); 
                $("#question").hide();
                $("#questionnaireButtons").hide();
                $("#record_details").hide();
           }
           if($("#review_data").val() == 3 ){
                // alert("Out of guidelines");
                $.ajax({
                    type: 'get',
                    url: '/rpm/getQuestionnaire',
                    success: function (response) {
                    }
                });
                $("#questionnaireButtons").show();
                $("#question").show();
                $("#buttons").hide();
                $("#content-template").hide();
                $("#call_section").hide(); 
                $("#within_guidelines").hide();
                $("#record_details").hide();
            }
        });
    });
    $("#text").click(function(){
        var step ='step-1';
        nextStep2(step);
        $("#content-template").show();
        $("#template_type_id").val(2);
        $("#call_section").hide();
        var template_type_id = 2;
        $.ajax({
            type: 'post',
            url: '/rpm/fetchEmailContentForMonthlyMonitoring',
            data: {template_type_id:template_type_id },
            success: function (response) {
                document.getElementById("content_title").innerHTML=response; 
            }
        }); 
    });
   
    $("#calling").click(function(){
        var step ='step-1';
        nextStep2(step);
        $("#call_section").show();
        $("#content-template").hide();
    });
   
   $("#not_answered").click(function(){
       $("#answer").show();
       $("#call_scripts").hide();
       $('#script').html('');
       $("#content_name").val('');
   });    
   
   // $("#answered").click(function(){
   //     $("#call_scripts").show();
   // }); 
   
//    $("#answered").click(function(){
//     //    $.ajax({
//     //                type: 'get',
//     //                url: '/rpm/getCallScripts',
//     //                success: function (response) {
//     //                }
//     //            });
//        $("#answer").hide();
//        $("#call_scripts").show();
//    });  
   
    $("#content_name").change(function() {  
        var id = $("#content_name").val();
        // alert("temp_id"+id);
        $("#temp_id").val(id);
        $.ajax({
            type: 'get',
            url: '/rpm/getCallScriptsById',
            data : {
                'id': id,
            },
            success: function (response) {
                // alert(JSON.stringify(response[0].content).message);
                // $('#exampleModalCenterTitle').html(jQuery.parseJSON(response[0].content_title);
                $('#script').html(jQuery.parseJSON(response[0].content).message);
                $modal = $('.script_modal');
                $modal.modal('show');
            },
            dataType: 'JSON',
        });
    });  
   
   $("#content_title").change(function() {
        var content_title =$("#content_title").val();
        // alert("temp_id"+content_title);
        $("#temp_id").val(content_title);
        $.ajax({
            type: 'post',
            url: '/rpm/fetchContentForMonthlyMonitoring',
            data: {
                _token: '{!! csrf_token() !!}', content_title:content_title
            },
            success: function (response) { 
                // alert(jQuery.parseJSON(response[0].content).message); 
                $("#subject").val(jQuery.parseJSON(response[0].content).subject);
                $("#sender_from").val(jQuery.parseJSON(response[0].content).from); 
                var text = jQuery.parseJSON(response[0].content).message;
                $('#content_area').html($(text).text());
                CKEDITOR.instances['editor'].setData(jQuery.parseJSON(response[0].content).message);
            }
        });
    });     
   
    $(document).ready(function(){  
        $(content_title).change(function(){ 
            var selected = $('#content_title').val();
            // alert(selected);
            if(selected != 0){
                $('#content_title').show();
                $('#content_title_text').hide();
            };
            if(selected == 0){
                $('#content_title_text').show();
                $('#content_title').show();
            }
        });     
        //timer
        $("#start").hide();
        $("#pause").show();
        $("#time-container").val(AppStopwatch.startClock);   
    });
   
   $("#office_range").click(function(){
       $modal = $('.office_modal');
       $modal.modal('show');
   });  
   
   $("#emergency_range").click(function(){
       $modal = $('.emergency_modal');
       $modal.modal('show');
   });  

   $("#save_office_pcp").click(function(){
       $('#record_details').show();
   });  
   
   $("#save_emergency_pcp").click(function(){
       $('#record_details').show();
   });  

//    $("#next_step").click(function(){
//         var step ='step-1';
//         nextStep2(step);
//     });

    function nextStep2(step){
    // alert(step);
    // $('#script_modal').modal('hide');
            var pageURL = $(location).attr("href");
             var str = pageURL.substr(0,pageURL.indexOf('#'));
             // alert(str);
            //var str2 = parseInt(str)+1;
            window.location=str+""+'#step-2';
}

</script>
@endsection