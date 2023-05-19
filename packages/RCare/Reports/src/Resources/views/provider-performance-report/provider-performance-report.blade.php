@extends('Theme::layouts_2.to-do-master')
@section('page-css')
 @section('page-title')
  Provider Performance Report
@endsection 
@endsection
@section('main-content')
<div class="breadcrusmb"> 
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Provider Performance Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>


<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form" method="post"  action ="">
                @csrf
                <div class="form-row">
                   
                   <div class="col-md-3 form-group mb-2">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>

                     <div class="col-md-3 form-group mb-3">
                        <label for="practices">Practice</label>    
                        @selectGroupedPractices("Practices",["id" => "practicesid", "class" => "form-control"])                    
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["class" => "select2","id" => "physician"])
                    </div>   
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>                   
                       <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
                    </div>
                  
                </div>
                </form>
            </div>
        </div>

    </div>
</div>


 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">

            <div class="card-body">
                @include('Theme::layouts.flash-message')                
           
                <div class="table-responsive">
                    <table id="provider-performance-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>                          
                            <th width="205px">Practice</th>
                            <th width="97px">Provider</th>                                    
                            <th  width="50px">Total No. of Patients In CCM</th>
                             <th  width="50px">Total No. of Patients In RPM</th>                             
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>


 

<div id="app">
</div>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
   
    <script type="text/javascript">
        var table1='';
       var getproviderPerformanceList = function(practice = null,providerid=null,practicegrp1=null,activedeactivestatus=null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},                                
                               
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['practice'];
                                        if(full['practice'] == null){
                                            name = ''; 
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true
                                },
                                 {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['provider'];
                                        if(full['provider'] == null){
                                            name = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true
                                },
                              
                               
                                  {data: 'ccmview', name: 'ccmview', mRender: function(data, type, full, meta){
                                        ccmcount = full['ccmcount'];
                                        if(full['ccmcount'] == null){
                                            ccmcount = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return ccmcount+ " "+full['ccmview'];
                                        }
                                    },
                                    orderable: true} ,
                                
                                {data: 'rpmview', name: 'rpmview', mRender: function(data, type, full, meta){
                                        rpmcount = full['rpmcount'];
                                        if(full['rpmcount'] == null){
                                            rpmcount = '';
                                        }
                                          
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return rpmcount+ " "+full['rpmview'];
                                        }
                                    },
                                    orderable: true}   
                              
                            ];
               
             if(practicegrp1==''){practicegrp1=null;}               
             if(practice==''){practice=null;} 
             if(providerid==''){providerid=null;} 
             if(activedeactivestatus==''){activedeactivestatus=null;}
         
         var url = "/reports/provider-performance-report-search/"+practicegrp1+'/'+practice+'/'+providerid+'/'+activedeactivestatus;
        
         table1 = util.renderDataTable('provider-performance-list', url, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
          $('#time').val('00:20:00');
          getproviderPerformanceList();   
          util.getToDoListData(0, {{getPageModuleName()}});

        
         
         $("#practicesid").on("change", function () {
         
                if($(this).val() == '' || $(this).val() == 0){

                    util.updatePhysicianListWithoutOther(001, $("#physician"))
                }else{
                    util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
                }                 
            });             
        }); 

         $("[name='practicesgrp']").on("change", function () { 

                var practicegrp_id = $(this).val(); 

                if(practicegrp_id==0){
                   // getPatientData();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practicesid")); 
                }
                else{
                     util.updatePracticeListWithoutOther(001, $("#practicesid"));
                }   
            });

   
        $('#example-select-all').on('click', function(){                     
            var rows = table1.rows().nodes();                   
            $('input[type="checkbox"]',rows).prop('checked', this.checked);                     
        });

               
    $('#billupdate').click(function(){  
   var checkbox_value=[];
              var rowcollection =  table1.$("input[type='checkbox']:checked", {"page": "all"});
            rowcollection.each(function(index,elem){
                checkbox_value.push($(elem).val());              
                
            }); 
           // var data=
            var modules=$('#modules').val();
          //  console.log(checkbox_value);
             var data = {
            patient_id: checkbox_value,
            module_id: modules         

        }
             $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/reports/billupdate',
            data: data,
            success: function (data) {
                console.log("save cuccess");
                getEnrollmentList();

            }
        });
      
      });    


var getPatientData=function()
{
  $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/reports/patient-summary',
            //data: data,
            success: function (data) {
              var TotalEnreolledPatient=data.TotalEnreolledPatient[0]['count'];
               var Totalpatient=data.Totalpatient[0]['count'];
                var TotalUnEnrolledPatient=data.TotalUnEnrolledPatient[0]['count'];
                 var TotalEnrolledInCCM=data.TotalEnrolledInCCM[0]['count'];
                  var TotalEnrolledInRPM=data.TotalEnrolledInRPM[0]['count'];
                 
                 $('#totalpatient').html(Totalpatient);
                   $('#totalenrolledpatient').html(TotalEnreolledPatient);
                     $('#totalenrolledpatientCCM').html(TotalEnrolledInCCM);
                       $('#totalenrolledpatientRPM').html(TotalEnrolledInRPM);
                         $('#totalunenrolledpatient').html(TotalUnEnrolledPatient);

               // console.log("save cuccess"+data.TotalEnreolledPatient[0]['count']);
               

            }
        });
}


$('#resetbutton').click(function(){
  
 $('#practicegrp').val('').trigger('change');
 $('#practicesid').val('').trigger('change');
 $('#physician').val('').trigger('change');      
 $('#activedeactivestatus').val('3').trigger('change');    
  });  

$('#searchbutton').click(function(){
  // debugger;
    $('#time').removeClass("is-invalid");
     var practicesgrp1=$("#practicesgrp").val();
     var practice=$('#practicesid').val();  
     var provider=$('#physician').val(); 
     var activedeactivestatus=$('#activedeactivestatus').val(); 

      // alert(practice);
      getproviderPerformanceList(practice,provider,practicesgrp1,activedeactivestatus); 
});

    //  jQuery(function(){
            jQuery('.click_id').click(function(){
                //alert($(this).attr('target'));               
                jQuery('#'+$(this).attr('target')).show();
                if($(this).attr('target')!='Enrolled')
                {
                         jQuery('#Enrolled').hide();
                }
                else
                {
                    jQuery('#Enrolled').show();
                }
               // if($(this).attr('target')!='Non-Enrolled')
               //  { 
               //        jQuery('#Non-Enrolled').hide();
               //  }
               //   else if($(this).attr('target')!='total-patients')
               //  {
               //        jQuery('#total-patients').hide();
               //  }
            });
           
      //  });
 
    </script>
@endsection