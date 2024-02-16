@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Insurance Report</h4>
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
                        <!-- <label for="insurancee">Insurance </label>
                        <input id="insurance" name="insurance" type="text" value="" autocomplete="off" class="form-control capitalize"> -->
 <label for="insurance">Care Manager</label>
                       @selectinsurance("insurance", ["id" => "insurance", "class" => "select2"])
                        
                    </div>

                    
                    <div class="col-md-2 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>                   
                       <!-- <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button> -->
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
                    <table id="Activities-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>  
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">Practice</th>
                            <th width="">Primary Insurance</th> 
                            <th width="">Secondary Provider</th> 
                            <th width="">Status</th> 
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
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables_themeroller.css">
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
   
    <script type="text/javascript">  
    var table1='';   
   var getInsuranceReport = function(insurance=null) {
            var columns =  [   
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},  
                                 {data: 'patient_name', name: 'patient_name'},
                                 {data: 'dob', name: 'dob'},                              
                                 {data: 'practice', name: 'practice'},
                                 {data: 'primary_insurance', name: 'primary_insurance'},
                                 {data: 'secondary_provider', name: 'secondary_provider'},
                                 {data: 'status', name: 'status'},
                            ];
            
             // debugger;
            //   var datas=function(data){
            //     data.insurance = $('#insurance').val();
            // };
             if(insurance==''){insurance=null;}
             

         var url = "/reports/insurance-report/search/"+insurance;
         table1 = util.renderDataTable('Activities-list', url, columns, "{{ asset('') }}");
              
        }


    //Run On HTML Build
    $(document).ready(function () {

    });
           
// $('#resetbutton').click(function(){
            
//             $('#insurance').val();
           
            
//             var insurance =$('#insurance').val();
           
//   });

$('#searchbutton').click(function(){

    // $('#time').removeClass("is-invalid");
    var insurance =$('#insurance').val();
  
            getInsuranceReport(insurance);
            

  
});
 
    </script>
@endsection