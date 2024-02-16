@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Manager Patients</h4>
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
                       @selectcaremanagerwithAll("care_manager_id", ["id" => "care_manager_id", "placeholder" => "Select Care Manager","class" => "select2"])
                    </div>
                   
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpractices("practices", ["id" => "practices","class" => "select2"])
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["id" => "physician","class" => "select2"])
                    </div>
                    </div>
                   <div class="form-row">
                     <div class="col-md-2 form-group mb-2"> 
                          <select id="timeoption" class="custom-select show-tick" style="margin-top: 23px;">
                            <!--   <option value="0">All</option> -->
                            <option value=">">Greater than</option>
                             <option value="<" selected>Less than</option>
                              <option value="=">Equal to</option>
                          </select>                         
                    </div>
                     <div class="col-md-2 form-group mb-2">                        
                          <label for="time">Time</label>
                               @text("time", ["id" => "time", "placeholder" => "hh:mm:ss"])                       
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                    </div>
                    <div class="col-md-1 form-group mb-3">
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
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>                        
                            <!-- <th >Practice EMR</th>      -->
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">Practice</th>
                            <th width="97px">CM</th>
                            <th width="">Provider</th>                  
                            <th >Last Contact</th>
                            <th width="75px">Minutes Spent</th>
                            <th width="75px">Enrolled Modules</th>
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
        var getCareManagerList = function(practice = null,provider=null,time=null,care_manager_id=null,timeoption=null) {
            var columns =  [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                // { data: null,
                //     mRender: function(data, type, full, meta){
                //         practice_emr = full['practice_emr'];
                //         if(full['practice_emr'] == null){
                //             practice_emr = '';
                //         }
                //         if(data!='' && data!='NULL' && data!=undefined){
                //             return practice_emr;
                //         }
                //     },
                //     orderable: true
                // },
                { data: null, 
                    mRender: function(data, type, full, meta){
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
                    orderable: true
                },
                { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', 
                    "render":function (value) {
                        if (value === null) return "";
                        return util.viewsDateFormat(value);
                    }
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['cmname'];
                        if(full['cmname'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
                { data: null, 
                    mRender: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['f_name']==null || full['l_name']==null) {
                                return '';
                            } else {
                                return  full['f_name']+' '+full['l_name']; 
                            }
                        }
                    },
                    orderable: true
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['name'];
                        if(full['name'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
                { data: 'last_date',type: 'date-dd-mm-yyyy', name: 'last_date'
                // ,
                //     "render":function (value) {
                //         if (value === null) return "";
                //         return util.viewsDateFormat(value);
                //     }
                },  
                { data:null,
                    mRender: function(data, type, full, meta){
                        totaltime = full['totaltime'];
                        if(full['totaltime'] == null){
                            totaltime = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return totaltime;
                        }
                    },
                    orderable: true
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        module = full['module'];
                        if(full['module'] == null){
                            module = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return module;
                        }
                    },
                    orderable: true
                }
            ];
            // debugger;
            if(practice==''){practice=null;} 
            if(provider==''){provider=null;}            
            if(time==''){ time=null;}
            if(time=='00:00:00'){ time='00:00:00';}
            if(timeoption=='')  { timeoption=null; }
            if(care_manager_id=='')
            {
                care_manager_id=null;
            }
            var url = "/patients/care-manager-patients/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption;
            // console.log(url);
            util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
          $('#time').val('00:20:00');
			//getCareManagerList();
            $("[name='practices']").on("change", function () {
                 util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
            });
                //$("[name='modules']").val(3).attr("selected", "selected").change();          
           // util.getToDoListData(0, {{getPageModuleName()}});
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
            url: '/reports/billupdate',
            data: data,
            success: function (data) {
                console.log("save cuccess");
                getCareManagerList();

            }
        });
      
      });    

$('#resetbutton').click(function(){
 $('#care_manager_id').val('');
   $('#practices').val('');
   $('#physician').val('');
 $('#time').val('00:20:00'); 
$('#timeoption').val("<");
});


$('#timeoption').click(function(){
   $checkvalue=$('#timeoption').val();
   if($checkvalue=='0')
   {
      $('#time').val('00:00:00'); 
      $('#time').prop( "disabled", true);
   }
   else
   {
        $('#time').prop( "disabled", false);
   }
    });

$('#searchbutton').click(function(){
  //debugger
    $('#time').removeClass("is-invalid");
    var practice=$('#practices').val();
     var provider=$('#physician').val();
      //var modules=$('#modules').val();
       //var fromdate1=$('#fromdate').val();
        // var todate1=$('#todate').val();
         var timeoption=$('#timeoption').val();
         //console.log(fromdate1+"   "+todate1);
       var care_manager_id=$('#care_manager_id').val();
       var time=$('#time').val();
         if(time == '')
            {
                time = '00:00:00';
            }
        var time_regex = new RegExp(/(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)/);
      
            if(time!="")
            {
              if(time_regex.test(time)){
                console.log(time.length);
                  if(time.length==8)
                        {
            
                getCareManagerList(practice,provider,time,care_manager_id,timeoption);
               }
               else
               {
                $('#time').addClass("is-invalid");
                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS formate.");
               }
             }
             else
             {
                $('#time').addClass("is-invalid");
                $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS formate.");
             }
         }
         else
         {        getCareManagerList(practice,provider,time,care_manager_id,timeoption);
}
  
});


 
    </script>
@endsection 