@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.css')}}">
     <link rel="stylesheet" href="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Manager Billing Status</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                   
                    <div class="col-md-2 form-group mb-2">
                        <label for="care_manager_id">Care Manager</label>
                       @selectcaremanager("care_manager_id", ["id" => "care_manager_id", "placeholder" => "Select Care Manager"])
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpractices("practices", ["id" => "practices"])
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="provider">Provider Name</label>
                        @selectpracticesphysician("provider",["id" => "physician"])
                    </div>
                   <div class="col-md-2 form-group mb-2">
                        <label for="module">Module Name</label>
                        @selectOrgModule("modules",["id" => "modules"])
                    </div>
                    <div class="col-md-1 form-group mb-1">
                        <!-- <label for="month">Time</label>
                        @time('time',["id" => "time"]) -->
                          <label for="time">Time</label>
                               @text("time", ["id" => "time", "placeholder" => "hh:mm:ss"])                       
                    </div>
                     <div class="col-md-3 form-group mb-1">
                        <label for="date">From</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-3 form-group mb-1">
                        <label for="date">To</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
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
             <button type="button" id="billupdate" class="btn btn-primary mt-4" style="margin-left: 1110px;margin-bottom: 9px;">Add Bill</button>
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="10px">EMR/EHR ID</th>
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">CM</th>
                         <th width="">Provider</th>                         
                            <th >Last Contact</th>
                            <th width="75px">Minutes Spent</th>
                             <th width="75px">Billing</th>
                              <th width="75px"><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                            
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
    <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.full.min.js')}}"></script>
      <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.js')}}"></script>
        <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.min.js')}}"></script>
          <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.full.js')}}"></script>
    <script type="text/javascript">
        var table1='';
       var getCareManagerList = function(practice = null,provider=null,modules=null,time=null,fromdate=null,todate=null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null,
                                     mRender: function(data, type, full, meta){
                                        practice_emr = full['practice_emr'];
                                        if(full['practice_emr'] == null){
                                            practice_emr = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_emr;
                                        }
                                    },
                                    orderable: false
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['mname'];
                                    if(full['mname'] == null){
                                        m_Name = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_img']=='' || full['profile_img']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        } else {
                                            return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        }
                                       
                                    }
                                },
                                orderable: false
                                },
                                {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },
                                {data: null, mRender: function(data, type, full, meta){                                    
                                   
                                    if(data!='' && data!='NULL' && data!=undefined){
                                           if(full['f_name']==null || full['l_name']==null) {
                                            return '';
                                        }
                                        else
                                        {
                                             return  full['f_name']+' '+full['l_name']; 
                                        }
                                    }
                                   
                                },
                                orderable: false
                                },
                               
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        provider_name = full['provider_name'];
                                        if(full['provider_name'] == null){
                                            provider_name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return provider_name;
                                        }
                                    },
                                    orderable: false
                                },

                                {data: 'rec_date',type: 'date-dd-mm-yyyy', name: 'rec_date', "render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },                                
                                
                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['totaltime'];
                                        if(full['totaltime'] == null){
                                            totaltime = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return totaltime;
                                        }
                                    },
                                    orderable: false
                                },
                                  {data:null,
                                    mRender: function(data, type, full, meta){
                                        status = '';
                                        
                                        if(data!='' && data!='NULL' && data!=undefined){
                                             if(full['status'] == null || full['status']==0){
                                                return status='Pending';
                                             }
                                             else
                                             {                                                
                                                return status='Done';
                                               
                                             }
                                            
                                        }
                                    },
                                    orderable: false
                                },
                                {data: null, 'render': function (data, type, full, meta){
                                     return '<input type="checkbox" class="test" name="patientbill" value="'+full['id']+'">';
                                 },
                                 orderable: false
                               
                            }
                            ];
            
           // debugger;
            if(practice==''){practice=null;} 
            if(provider==''){provider=null;}            
            if(time==''){ time=null;}
             if(fromdate==''){ fromdate=null; }
             if(todate=='')  { todate=null; }

				var url = "/ccm/care-manager-report/search/"+practice+'/'+provider+'/'+modules+'/'+time+'/'+fromdate+'/'+todate;
                console.log(url);
				 table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
			getCareManagerList();
            $("[name='practices']").on("change", function () {
                 util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });
                $("[name='modules']").val(3).attr("selected", "selected").change();          
            util.getToDoListData(0, {{getPageModuleName()}});
          //  $(".patient-div").hide(); // to hide patient search select
        

             
                
           
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
            url: '/ccm/billupdate',
            data: data,
            success: function (data) {
                console.log("save cuccess");
                getCareManagerList();

            }
        });
      
      });    


$('#searchbutton').click(function(){
    $('#time').removeClass("is-invalid");
    var practice=$('#practices').val();
     var provider=$('#physician').val();
      var modules=$('#modules').val();
       var fromdate=$('#fromdate').val();
         var todate=$('#todate').val();
       var time=$('#time').val();
        var time_regex = new RegExp(/^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9]:?[0-5][0-9]+$))/g);
            if(time!="")
            {
             if(time_regex.test(time)){
                getCareManagerList(practice,provider,modules,time,fromdate,todate);
             }
             else
             {
                $('#time').addClass("is-invalid");
                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS formate.");
             }
         }
         else
         {        getCareManagerList(practice,provider,modules,time,fromdate,todate);
}
  
});


 
    </script>
@endsection