        <!--============ Customizer =============  to rotate text use spin in class  (click)="isOpen = !isOpen" -->
        <div class="previous_month_notes" id="previous_month_id" style="display:none;">
            <div class="handle" id="previous_month_id" data-toggle="tooltip" data-placement="left" title="Previous Month Notes" tabindex="0"> 
                <i class="i-Calendar-2 spin"><!--  <span> Previous Month Notes </span>  --></i> 
            </div> 
            <div class="card">
                <div class="card-header" id="headingOne">
                    <div class="mb-0"> 
                       Monthly Notes 
                        @hidden('regi_mnth',['id'=>'regi_mnth'])
                        <div style="float:right">
                        
                        <!-- <button class="btn btn-primary" id="prev-sidebar-month" onclick="prev_month_Fun()"> -->
                                <i class="btn btn-primary i-Left mr-2"  id="prev-sidebar-month" ></i> <!-- onclick="prev_month_Fun()"></i> -->
                            <!-- </button> -->
                            <label id="display_month_year"></label> 
                            <!-- <button class="btn btn-primary" id="next-sidebar-month" onclick="curr_month_Fun()" style='display:none'> -->
                                <i class="btn btn-primary i-Right mr-2" id="next-sidebar-month" style='display:none' ></i> <!-- onclick="curr_month_Fun()"></i> -->
                            <!-- </button> -->
                        </div>
                    </div> 
                </div>
                <div class="previous_month-body" data-perfect-scrollbar data-suppress-scroll-x="true">
                    <div class="accordion" id="accordionCustomizer">
                        <div id="previous-month" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionCustomizer">
                            <div class="container-fluid">
                                <div class="col-12"> 
                                    <div id="previousMonthData"></div> 
                                </div> 
                            </div> 
                        </div>
                    </div>
                </div> 
                <div id="temp" style="display:none;"> Patient Caretool Data</div>                
            </div>
        </div>
	
        @section('bottom-js')
        <script type="text/javascript">
            
            var a ;
           


            // $(document).ready(function() {  
            //     // alert("document");
            //     var patient_id = $("#patient_id").val(); 
            //     var module_id = $("input[name='module_id']").val();
            //     util.getPatientPreviousMonthCalender(patient_id,module_id);

            //     var regis =  $("input[name='regi_mnth']").val();
				

            //     var months = new Array( "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            //     var d = new Date();
               
            //     var currentMonth= moment().format("MM");
            //     var currentYear = moment().format("YYYY"); 
            //     $("#display_month_year").html(moment().format("MMM YYYY"));
            //     newPreviousMonth = moment(d, 'YYYY/MM/DD');
            //     console.log("newPreviousMonth"+newPreviousMonth);
                
            //     $("#prev-sidebar-month").click(function(){
            //         alert("hello prev-sidebar-month");
            //         var registeredcalender = $("#regi_mnth").val(); 
            //         var dateObj1 = moment(registeredcalender, 'DD-MM-YYYY');
            //         var registeredmonth = dateObj1.format('MM');
            //         var registeredyear = dateObj1.format('YYYY');
            //         console.log("dateObj1"+dateObj1);
            //         var patient_id = $("#hidden_id").val(); 
            //         var module_id = $("input[name='module_id']").val();
            //         newPreviousMonth = moment(newPreviousMonth).subtract(1, 'months').format('MM-YYYY');
            //         // console.log("newPreviousMonth"+newPreviousMonth);
            //         var month = moment(newPreviousMonth, 'MM').format('MM');
            //         var month_name = moment(newPreviousMonth, 'MM').format('MMMM');
            //         var currentMonthName = moment(currentMonth, 'MM').format('MMMM');
            //         console.log("month",month);
            //         console.log("month_name",month_name);
            //         // // var day = dateObj.getDate();
            //         var year = moment(newPreviousMonth, 'YYYY').format('YYYY'); //dateObj1.format("YYYY");// dateObj.getFullYear();
            //         console.log("year",year);

            //         if(registeredcalender.length>0){ 
            //             $("#display_month_year").html(moment().format("MMM YYYY"));
            //             $("#display_month_year").html(month_name+' '+year);
            //             // if((month < currentMonth) && (year == currentYear)){
            //             if (new Date(year, month) < new Date(currentYear, currentMonth)){        
            //                 $("#next-sidebar-month").show(); 
            //             } else{
            //                 $("#next-sidebar-month").hide(); 
            //             } 
            //             // if((month >= registeredmonth) && (year== registeredyear)){
            //             if (new Date(year, month) >= new Date(registeredyear, registeredmonth)){
            //                 $("#prev-sidebar-month").show(); 
            //             } else {
            //                 var displaydata = "Patient CareTools"
            //                 $("#display_month_year").html(displaydata);
            //                 $("#prev-sidebar-month").hide(); 
            //                 $(".previous_month-body").hide();
            //                 $("#temp").show();
            //                 $("#temp").html('');
            //                 $('#temp').append($(".patientCaretool-body").html());
            //             }
            //         } else {
            //             var displaymonth = months[currentMonth-2];
            //             var displaydata = "Patient CareTools"
            //             $("#display_month_year").html(displaydata); 
            //             $("#next-sidebar-month").show(); 
            //             $("#prev-sidebar-month").hide();
            //             $(".previous_month-body").hide(); 
            //             $("#temp").show();
            //             $('#temp').html('');
            //             $('#temp').append($(".patientCaretool-body").html());
            //         }
            //         util.getPatientPreviousMonthNotes(patient_id,module_id,month,year);
                   
            //     });

            //     // function curr_month_Fun(){
            //     $("#next-sidebar-month").click(function(){
            //         var registeredcalender = $("#regi_mnth").val(); 
            //         var dateObj1 = new Date(registeredcalender);
            //         var month_name = dateObj1.getMonth();
            //         var registeredmonth = dateObj1.getMonth()+1; //months from 1-12 =>Because getmonth() start from 0.
            //         var registeredday = dateObj1.getDate();
            //         var registeredyear = dateObj1.getFullYear(); 

            //         // var currentMonth= (new Date).getMonth()+ 1;
            //         // var currentYear = (new Date).getFullYear(); 
            //         d.setMonth(d.getMonth() + 1); 
            //         var patient_id = $("#hidden_id").val();  
            //         var module_id = $("input[name='module_id']").val();
            //         var dateObj = new Date(d.toLocaleDateString());
            //         var month_name = dateObj.getMonth(); 
            //         var month = dateObj.getMonth()+1; //months from 1-12 =>Because getmonth() start from 0.
            //         var day = dateObj.getDate();
            //         var year = dateObj.getFullYear();

            //         // console.log("month",month);
            //         $("#temp").hide(); 
            //         $(".previous_month-body").show(); 
            //         if(registeredcalender.length>0){
            //             $("#display_month_year").html(months[month_name]+' '+year);
            //             // if((month < currentMonth) && (year == currentYear)){
            //             if (new Date(year, month) < new Date(currentYear, currentMonth)){    
            //                 $("#next-sidebar-month").show();     
            //             } else{
            //                 $("#next-sidebar-month").hide();  
            //             } 
            //             // if((month >= registeredmonth) & (year== registeredyear)){
            //             if (new Date(year, month) >= new Date(registeredyear, registeredmonth)){
            //                 $("#prev-sidebar-month").show(); 
            //             } else {
            //                 $("#prev-sidebar-month").hide();
            //                 $(".previous_month-body").hide(); 
            //                 $("#temp").show();
            //                 $('#temp').html('');
            //                 $('#temp').append($(".patientCaretool-body").html());
            //             } 
            //         } else{
            //             var displaymonth = months[currentMonth-1];
            //             $("#display_month_year").html(displaymonth+' '+year);
            //             $("#next-sidebar-month").hide();
            //             $("#prev-sidebar-month").show(); 
            //         }
            //         util.getPatientPreviousMonthNotes(patient_id,module_id,month,year); 
                    
            //     });
            // });
        </script>
        <!-- ============ End Customizer ============= -->
		@endsection