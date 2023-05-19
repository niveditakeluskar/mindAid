@extends('Theme::layouts.master')  
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
           <h4 class="card-title mb-3">Api Exception Report</h4>  
        </div>
         <div class="col-md-1">
        </div>
    </div>           
</div>
<div class="separator-breadcrumb border-top"></div>



<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="api_exception_form" name="api_exception_form"  action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-3 form-group mb-3">
                            <label for="practicename">Practice</label>                  
                            @selectrpmpractices("practices",["id" => "practices", "class" => "form-control show-tick select2"])   
                    </div>
                    <div class="col-md-3 form-group mb-3 patientdiv" id="patientdiv" style="display: none">
                        <label for="patient">Patient</label>
                        @selectpatient("patient",["id" => "patient","class"=>"custom-select show-tick select2"])
                    </div>
                     <div class="col-md-3 form-group mb-2 patientdiv" style="display: none">
                        <label for="practicegrp">Patient's EMR</label>
                        @selectemrpractice("patient_emr", ["id" => "patient_emr","class" => "select2"])
                    </div>
                     <div class="col-md-3 form-group mb-2">
                        <label for="exception_emr">Exception's EMR</label>
                        @selectExceptionEMR("exception_emr", ["id" => "exception_emr","class" => "select2"])
                    </div>
                     <div class="col-md-3 form-group mb-3">  
                          <label for="exception_type">Exception Type</label> 
                          <select id="exception_type" name="exception_type" class="custom-select show-tick" >
                            <option value="" selected>All</option> 
                            <option value="alert">Alert</option>
                            <option value="info">Info</option>                           
                          </select>                          
                    </div>
                    <div class="col-md-3 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-3 form-group mb-3">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
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
            <div id="success-set" style="display: none;">  
                <div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Re-process set Successfully!</strong>
                </div>
                </div>
                <div id="success-unset" style="display: none;">  
                <div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Re-process unset Successfully!</strong>
                </div>
                </div>          
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="api-exception-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="15px">Sr No.</th>
                            <th width="15px">Patient Id</th>
                            <th width="20px">Api</th>
                            <th width="25px">Parameter</th>
                            <th width="20px">Exception Type</th>
                            <th width="20px">Incident</th>
                            <th width="20px">Action Taken</th>
                            <th width="20px">EMR</th> 
                            <th width="20px">Device Code</th>
                            <th width="20px">Observation Id</th> 
                            <th width="20px">Timestamp</th> 
                            <th width="20px">Webhook Details</th>
                            <th width="20px">Re-process</th>                            
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

<div class="modal fade" id="rpm_webhook_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="
    width: 800px!important;
    margin-left: 280px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Webhook Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form action="" name="rpm_cm_form" id="rpm_cm_form" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <!-- <div> -->
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">

                                <div class="row">
                                <label class="col-md-2 ml-3" for="timestamp" id="timestamp">Timestamp :</label><div class="col-md-6" id="timestampdiv"></div>
                                </div>
                                <div class="row">
                                <label for="xmitid" class="col-md-2 ml-3" id="xmitid">Device Code :</label><div class="col-md-6" id="xmitiddiv"></div>
                                </div>
                                <div class="row">
                                <label for="careplanid" class="col-md-2 ml-3" id="careplanid">CarePlan Id :</label><div class="col-md-6" id="careplaniddiv"></div>
                                </div>
                                <div class="row">
                                <label for="sourceid"  class="col-md-2 ml-3" id="sourceid">Source ID :</label><div class="col-md-6" id="sourceiddiv"></div>
                                </div>
                                <div class="row">
                                <label for="mrn"  class="col-md-2 ml-3" id="mrn">MRN :</label><div class="col-md-6" id="mrndiv"></div>
                                </div>
                                <div class="row">  
                                <label for="observationid"  class="col-md-2 ml-3" id="observationid">Observation Id :</label><div class="col-md-6" id="observationiddiv"></div>
                                </div>
                                <!-- <input type="hidden"  name="rpm_observation_id" id="rpm_observation_id" value=""/>
                                <input type="hidden" name="patient_id" id="patient_id" value=""/>
                                <input type="hidden" name="vital" id="vital" value=""/>   -->
                                <!-- <input type="text" name="rpm" id="rpm" value=""/>   -->
                                <div id="rpmdiv"></div>
                                <div></div>
                               
                               
                            </div>    
                         </div>  
                    <div class="card-footer">  
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <!-- <button type="submit" class="btn  btn-primary m-1">Submit</button> -->
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




@endsection
@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>  
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/apiexception.js'))}}"></script>

    <script type="text/javascript">

        var getApiExceptionList = function(fromdate=null,todate=null,exception_type=null,patientid=null,patientemr=null,exceptionemr=null) {
            var columns = [
                               { data: 'DT_RowIndex', name: 'DT_RowIndex'},

                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        patient_id = full['patient_id'];  
                                        if(full['patient_id'] == null){
                                            patient_id = '';
                                        }
                                        if(full['patient_id']!='' && full['patient_id']!='NULL' && full['patient_id']!=undefined){
                                            return patient_id;
                                        }
                                    },
                                    orderable: true   
                                },

                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        api = full['api'];  
                                        if(full['api'] == null){
                                            api = '';
                                        }
                                        if(full['api']!='' && full['api']!='NULL' && full['api']!=undefined){
                                            return api;
                                        }
                                    },
                                    orderable: true   
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        parameter = full['parameter'];  
                                        if(full['parameter'] == null){
                                            parameter = '';
                                        }
                                        if(full['parameter']!='' && full['parameter']!='NULL' && full['parameter']!=undefined){
                                            return parameter;
                                        }
                                    },
                                    orderable: true   
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        exception_type = full['exception_type'];  
                                        if(full['exception_type'] == null){
                                            exception_type = '';
                                        }
                                        if(full['exception_type']!='' && full['exception_type']!='NULL' && full['exception_type']!=undefined){
                                            return exception_type;
                                        }
                                    },
                                    orderable: true   
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        incident = full['incident'];  
                                        if(full['incident'] == null){
                                            incident = '';
                                        }
                                        if(full['incident']!='' && full['incident']!='NULL' && full['incident']!=undefined){
                                            return incident;
                                        }
                                    },
                                    orderable: true   
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        action_taken = full['action_taken'];  
                                        if(full['action_taken'] == null){
                                            action_taken = '';
                                        }
                                        if(full['action_taken']!='' && full['action_taken']!='NULL' && full['action_taken']!=undefined){
                                            return action_taken;
                                        }
                                    },
                                    orderable: true   
                                },
                                 {data:null,
                                    mRender: function(data, type, full, meta){
                                        mrn = full['mrn'];  
                                        if(full['mrn'] == null){
                                            mrn = '';
                                        }
                                        if(full['mrn']!='' && full['mrn']!='NULL' && full['mrn']!=undefined){
                                            return mrn;
                                        }
                                    },
                                    orderable: true     
                                },

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        device_code = full['device_code'];  
                                        if(full['device_code'] == null){
                                            device_code = '';
                                        }
                                        if(full['device_code']!='' && full['device_code']!='NULL' && full['device_code']!=undefined){
                                            return device_code;
                                        }
                                    },
                                    orderable: true     
                                },

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        observation_id = full['observation_id'];  
                                        if(full['observation_id'] == null){
                                            observation_id = '';
                                        }
                                        if(full['observation_id']!='' && full['observation_id']!='NULL' && full['observation_id']!=undefined){
                                            return observation_id;
                                        }
                                    },
                                    orderable: true     
                                },
                                  
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        updated_at = full['updated_at'];  
                                        if(full['updated_at'] == null){
                                            updated_at = '';
                                        }
                                        if(full['updated_at']!='' && full['updated_at']!='NULL' && full['updated_at']!=undefined){
                                            return updated_at;
                                        }
                                    },
                                    orderable: true     
                                },
                                {data: 'action', name: 'action'},
                                {data: 'reprocess', name: 'reprocess'}

                              
                          ];  

                        if(fromdate==''){fromdate=null;}
                        if(todate==''){todate=null;} 
                        if(exception_type==''){exception_type=null;}            
                        if(patientid==''){ patientid=null;}
                        if(patientemr==''){ patientemr=null;}
                        if(exceptionemr==''){ exceptionemr=null;}
                      
               
                var url ="/rpm/api-exception/search/"+fromdate+"/"+todate+"/"+exception_type+"/"+patientid+"/"+patientemr+"/"+exceptionemr;  
                console.log(url);
                var table1 = util.renderDataTable('api-exception-list', url, columns, "{{ asset('') }}"); 
            
        } 
    </script>
    <script>
     $(document).ready(function() {

     	    function convert(str) {
		        var date = new Date(str),
		        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
		        day = ("0" + date.getDate()).slice(-2);
		        return [date.getFullYear(), mnth, day].join("-");
		    }
  
			  var date = new Date(), y = date.getFullYear(), m = date.getMonth();
			  var firstDay = new Date(y, m, 1);
			  var lastDay = new Date();
			  var fromdate = $("#fromdate").attr("value", (convert(firstDay)));
			  var todate   = $("#todate").attr("value", (convert(lastDay)));      
			  var currentdate = todate.val();
			  var startdate = fromdate.val();
			  $('#fromdate').val(startdate);
			  $('#todate').val(currentdate); 
       
        //apiexception.init(); 
        getApiExceptionList(startdate,currentdate);

          $("[name='practices']").on("change", function () {
            var practice_id = $(this).val(); 
            if(practice_id==""){practice_id=null;} 
            $('.patientdiv').show();
             util.updatePatientList(parseInt($(this).val()),'2', $("#patient"));  
             util.getEmrOnPractice(parseInt($(this).val()), $("#patient_emr"));      
         });

           $("[name='patient']").on("change", function () {
            var practice_id = $("[name='practices']").val(); 
             var patient_id = $(this).val();  
             if(patient_id==""){patient_id=null;}   
              if(practice_id==""){practice_id=null;} 
             util.getEmrOnPractice(parseInt(practice_id), $("#patient_emr"),patient_id);      
         });
       
        util.getToDoListData(0, {{getPageModuleName()}});  

    });

     $('#searchbutton').click(function(){
     	  var fromdate=$('#fromdate').val();
          var todate=$('#todate').val();
          var exception_type=$('#exception_type').val();
          var patient_emr=$('#patient_emr').val();
          var exception_emr=$('#exception_emr').val();
          var patient=$('#patient').val();

            getApiExceptionList(fromdate,todate,exception_type,patient,patient_emr,exception_emr);

     });

     $('#resetbutton').click(function(){
     	 function convert(str) {
			  var date = new Date(str), 
			    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
			    day = ("0" + date.getDate()).slice(-2);
			    return [date.getFullYear(), mnth, day].join("-"); 
			  }

			 var date = new Date(),
			 y = date.getFullYear(), m = date.getMonth(), d=date.getDate();

			var firstDay = new Date(y, m, 1);
			var lastDay = new Date(y, m, d); 
			  var fromdate =$("#fromdate").val(convert(firstDay));
			  var todate =$("#todate").val(convert(lastDay));
              $('.patientdiv').hide();
			$('#patient_emr').val('').trigger('change');
			$('#exception_type').val('').trigger('change');
            $('#patient').val('').trigger('change');
            $('#exception_emr').val('').trigger('change');
             $('#practices').val('').trigger('change');
     });

  $('#api-exception-list tbody').on('click', 'td .chk_reproceess', function () { 
        var webhookid=$(this).val();
        var reprocess="";
        var arr=$(this).attr('id');    
        var id = arr.split('/');
       //alert(webhookid);   
        if($(this).prop('checked') == true){
            reprocess="1";
        } 
        else
        {
            reprocess="0";
        }
         $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
         });
         $.ajax({
        type: 'POST',
        url: '/rpm/api-reprocess-request',
        data: {webhookid: webhookid, 
            reprocess: reprocess,id:id[0],
            parameter:id[1],
            device_code:id[2],
            partner_id:id[3]
        },
        success: function (data) {
            console.log(data+"test");
          if($.trim(data)=="1")
          {
            $("#success-set").show();
             $("#success-unset").hide();
          }
          else
          {
            $("#success-unset").show();
              $("#success-set").hide();
          }
           setTimeout(function () {
             $("#success-set").hide();
              $("#success-unset").hide();
        }, 3000);
        }
     })
});

var populateForm = function (data, url) {

$.get(
    url, 
    data,
    function (result) {
        // console.log(result);
        // console.log(result.rpm_cm_form.static.content);
        // const myJSON = JSON.parse(result.rpm_cm_form.static.content);  
        // console.log(myJSON.timestamp);
      
        // $("#rpm").val(result.rpm_cm_form.static.content);
        const mjson = JSON.parse(result.rpm_cm_form.static.content);


        $("#timestampdiv").empty();
        $("#timestampdiv").append(mjson.timestamp);

        $("#xmitiddiv").empty();
        $("#xmitiddiv").append(mjson.xmit_id);

        $("#careplaniddiv").empty();
        $("#careplaniddiv").append(mjson.carePlanId);  

        $("#sourceiddiv").empty();
        $("#sourceiddiv").append(mjson.sourceID);

        $("#mrndiv").empty();
        $("#mrndiv").append(mjson.mrn);  

        $("#observationiddiv").empty();
        $("#observationiddiv").append(mjson.observation_id);  

        // $("#careplaniddiv").empty();
        // $("#careplaniddiv").append(mjson.carePlanId);  


        // $("#rpmdiv").empty();
        // $('#rpmdiv').append(result.rpm_cm_form.static.content);  


        for (var key in result) {
            form.dynamicFormPopulate(key, result[key]);
            // updateBmi();
            

        }
    }
).fail(function (result) {
    console.error("Population Error:", result);
});

};

    var Webhookdetail = function(id){
    // alert("1");
    // alert(id);
  
    $("#rpm_webhook_modal").modal('show');
    var formpopulateurl =  "ajax/populateApiExceptionForm/" +  id;
    var data = "";
    populateForm(data, formpopulateurl);  
}

       
  
    </script>
@endsection