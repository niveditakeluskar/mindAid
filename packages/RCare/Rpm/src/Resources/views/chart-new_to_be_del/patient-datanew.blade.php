

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
                $vital =  $a[6] ; ?>  
                <div class="table-responsive">
                    <table id="patient-alert-history-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead id="vitaltableheader">
                        <tr>
                            <th width="50px">TimeStamp</th>
                            <th colspan="3">Reading</th>
                        </tr>
                        @if($vital=='observationsoxymeter' || $vital=='observationsheartrate' )
                            <tr>
                                <th  width="50px"></th>
                                <th  width="50px">Sp2</th>
                                <th  width="50px">Perfusion Index</th>
                                <th  width="50px">Heartrate</th>  
                            </tr>
                        @else 
                            <tr>
                                <th  width="50px"></th>
                                <th  width="50px">Systolic</th>
                                <th  width="50px">Diastolic</th>
                                <th  width="50px">Heartrate</th>  
                            </tr>
                        @endif  
                    </thead>
                    <tbody id="vitalstablebody">
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>


    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">  

        var getPatientAlertHistoryList = function(patient = null,unit = null,fromdate1=null,todate1=null) {
            var columns = [
                            
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['csseffdate'];  
                                        if(full['csseffdate'] == null){
                                            totaltime = '';
                                        }
                                        if(full['csseffdate']!='' && full['csseffdate']!='NULL' && full['csseffdate']!=undefined){
                                            return totaltime;
                                        }
                                    },
                                    orderable: true   
                                },        
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        readingone = full['readingone'];  
                                        if(full['readingone'] == null){
                                            readingone = '';
                                        }
                                        if(full['readingone']!='' && full['readingone']!='NULL' && full['readingone']!=undefined){
                                            return readingone;
                                        }
                                    },
                                    orderable: true   
                                },

                                { data: null,
                                    mRender: function(data, type, full, meta){
                                        readingtwo = full['readingtwo'];
                                            if(full['readingtwo'] == null){
                                                readingtwo = '';
                                        }
                                        if(full['readingtwo']!='' && full['readingtwo']!='NULL' && full['readingtwo']!=undefined){
                                            return full['readingtwo'];
                                            // return ["<a href='/rpm/device-traning/"+full['id']+"'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname']+'</a>';
                                        }
                                    },
                                    orderable: false
                                },
                                // { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob',
                                //     "render":function (value) {
                                //     if (value === null) return "";
                                //         return moment(value).format('MM-DD-YYYY');
                                //     }
                                // },
                         
                                { data: null,
                                    mRender: function(data, type, full, meta){
                                        heartratereading = full['heartratereading'];
                                            if(full['heartratereading'] == null){
                                                heartratereading = '';
                                        }
                                        else{
                                            return heartratereading;
                                        }
                                         
                                    },
                                    orderable: false
                                }
                       
  
                        // { data: 'action', name: 'action', orderable: false, searchable: false}
                        ];  

                        // if(patient==''){patient=null;}
                        // if(unit==''){unit=null;} 
                        
                        
            
                var sPageURL = window.location.pathname;
                var arr = sPageURL.split('/');
                

                if(patient==''|| patient==null ){
                    var newpatient = arr[3];
                }
                else{
                    var newpatient = patient;
                }
                if(unit=='' || unit==null){
                    var newunit = arr[4];
                }
                else{
                    var newunit = unit;
                } 
                if(fromdate1==''){ fromdate1=null; }
                if(todate1=='')  { todate1=null; } 
               
                var url ="/rpm/patient-alert-history-list/"+newpatient+"/"+newunit+"/"+fromdate1+"/"+todate1;   
               
                console.log(url,sPageURL);
                var table1 = util.renderDataTable('patient-alert-history-list', url, columns, "{{ asset('') }}"); 
            
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

           function vitaltable(value,fromdatevalue,todatevalue) {
            if(value==2){             
            $('#vitaltableheader').html('');
            $('#vitaltableheader').append('<tr><th width="50px">TimeStamp</th><th colspan="3">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">Sp2</th><th  width="50px">Perfusion Index</th><th  width="50px">Heartrate</th></tr>');
            getPatientAlertHistoryList(null,'observationsoxymeter',fromdatevalue,todatevalue);    
            }
            else if(value==3){
            $('#vitaltableheader').html('');
            $('#vitaltableheader').append('<tr><th width="50px">TimeStamp</th><th colspan="3">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">Systolic</th><th  width="50px">Diastolic</th><th  width="50px">Heartrate</th></tr>');
            getPatientAlertHistoryList(null,'observationsbp',fromdatevalue,todatevalue);   
            }
            else{
            $('#vitaltableheader').html('');
            $('#vitaltableheader').append('<tr><th width="50px">TimeStamp</th><th colspan="3">Reading</th></tr><tr><th  width="50px"></th><th  width="50px">NA</th><th  width="50px">NA</th><th  width="50px">NA</th></tr>'); 
            getPatientAlertHistoryList('000000000','observationsbp',fromdatevalue,todatevalue);   
            
            } 
           }


     $(document).ready(function() {
       
        $('#fromdate').val(firstDayWithSlashes);                      
        $('#todate').val(currentdate);
        var fromdate1 = $('#fromdate').val();
        var todate1 = $('#todate').val();
        getPatientAlertHistoryList(null,null,fromdate1,todate1);
        rpmlist.init();



        $('.tabclass').click(function(){       
            var tabid=this.id;     
            var res = tabid.split("_");
            vitaltable(res[1],fromdate1,todate1);          
             });

        

        util.getToDoListData(0, {{getPageModuleName()}});

        });



        $('#searchbutton').click(function(){
       
        var ref_this = $("ul#patientvitalstab li a.active").attr('id');
        var res = ref_this.split("_");
        var fromdate1=$('#fromdate').val();
        var todate1=$('#todate').val(); 
       
        vitaltable(res[1],fromdate1,todate1);
      
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
        vitaltable(res[1],fromdate1,todate1); 
       });



       
  
    </script>
