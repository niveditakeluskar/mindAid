<div class="breadcrusmb"> 
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="form-row">
</div>
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-header">
            Patient Alert History
            </div>  
            <div class="card-body">
                <div>
                    @include('Rpm::alert-history.vitals-tab')
                </div> 
                <div class="form-row"> 
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-2 form-group mb-3">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
                    </div> 
                    <div>
                    <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>                   
                    <button type="button" id="resetbutton" class="btn btn-primary mt-4">Reset</button>
                    </div>
                </div>         
                @include('Theme::layouts.flash-message')
               <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
         
                 $a = explode("/",$actual_link);
                 $vital =  $a[6] ;
                 $deviceid=$a[7];
                 ?>  
                  <input type="hidden" name="deviceid" id="deviceid" value="{{$deviceid}}">
                  <div id="tablediv"></div>
                  <div class="table-responsive" >
                  </div>
            </div>
        </div>
    </div>
</div>


    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/rpmlist.js'))}}"></script>
    <script type="text/javascript">  

 var getPatientAlertHistoryList =  function(patient = null,fromdate1=null,todate1=null,deviceid=null) {
       
                var sPageURL = window.location.pathname;
                var arr = sPageURL.split('/');
                

                if(patient==''|| patient==null ){
                    var newpatient = arr[3];
                    
                }
                else{
                    var newpatient = patient;
                }
                
                if(fromdate1==''){ fromdate1=null; }
                if(todate1=='')  { todate1=null; } 
                if(deviceid=='')  { deviceid=null; } 
             
    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var assetBaseUrl = "{{ asset('') }}";    
    $('.table-responsive').html("");
 //  $('.table-responsive').html('<table id="alert_history_details_table" class="display table table-striped table-bordered"></table>');   
     var url ="/rpm/patient-alert-history-list/"+newpatient+"/"+fromdate1+"/"+todate1+"/"+deviceid;  
     console.log(url+"testing");               
    $.ajax({
        type: 'GET',
        url: url,
        //data: data,
        success: function (datatest) {
         
          var dataObject = eval('[' +datatest+']');
                 var columns = [];
          var tableHeaders;
         // tableHeaders+='<th colspan="3">test</th>';

                    $.each(dataObject[0].columns, function(i, val){
                        tableHeaders += "<th>" + val.title + "</th>";
                    });
          $('.table-responsive').html('<table id="alert_history_details_table" class="display table table-striped table-bordered"><thead>'+tableHeaders+'</thead></table>');        
          
     $('#alert_history_details_table').dataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                titleAttr: 'PDF' 
            }
        ],
        "processing": true,
        "Language": { 
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...',
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found",
        },
         "destroy": true,
        "data": dataObject[0].DATA,
        "columns": dataObject[0].COLUMNS,
       
        
     });
  //   $('#load-monthly-billing-tbl').hide();

        }
    });
}

    </script>
    <script>

        function formatDate() {
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year,month, day].join('-');
        }

            var currentdate = formatDate();   
            var date = new Date(); 

           var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
           var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
           var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);

       





     $(document).ready(function() {
       
        $('#fromdate').val(firstDayWithSlashes);                      
        $('#todate').val(currentdate);
        var fromdate1 = $('#fromdate').val();
        var todate1 = $('#todate').val();
       
        rpmlist.init();
         var deviceidarray=[];
         var uniquedeviceid=[];
        var deviceid=$('#deviceid').val();
       // alert(fromdate1+"test"+todate1+"test1"+deviceid);
        getPatientAlertHistoryList(null,fromdate1,todate1,deviceid);         
        deviceidarray.push(deviceid); 

     $('.alerttabclass').click(function(){       
            var tabid=this.id;     
            var res = tabid.split("_");
            $('#deviceid').val(res[1]);             
             deviceidarray.push(res[1]); 
              getPatientAlertHistoryList(null,fromdate1,todate1,res[1]);    
             
             });

        util.getToDoListData(0, {{getPageModuleName()}});

        });





        $('#searchbutton').click(function(){
       
        var ref_this = $("ul#patientvitalstab li a.active").attr('id');
        var res = ref_this.split("_");
        var fromdate1=$('#fromdate').val();
        var todate1=$('#todate').val(); 
          getPatientAlertHistoryList(null,fromdate1,todate1,res[1]);
       
      
       });

       $('#resetbutton').click(function(){
        var ref_this = $("ul#patientvitalstab li a.active").attr('id');
        var res = ref_this.split("_");
        $('#fromdate').val('');
        $('#todate').val('');
        $('#fromdate').val(firstDayWithSlashes);  
        $('#todate').val(currentdate);
        var fromdate1=$('#fromdate').val();
        var todate1=$('#todate').val(); 
        getPatientAlertHistoryList(null,fromdate1,todate1,res[1]);
       });


  
    </script>
