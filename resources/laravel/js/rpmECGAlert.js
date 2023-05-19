  var baseURL = window.location.origin+'/';
  var getrpmalertlisting =  function(fromdate=null,todate=null) {
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},                      
                        {data: 'fname',
                            mRender: function(data, type, full, meta){
                            if(data!='' && data!='NULL' && data!=undefined){
                              l_name = full['lname'];
                            if(full['lname'] == null && full['fname'] == null){
                              l_name = '';
                              return '';
                            }
                            else
                            {

                              return full['fname'] + ' ' + l_name;
                            }
                            } else { 
                                return ''; 
                            }    
                            },orderable: true
                            }, 
                         {data: 'device_code',name:'device_code'},
                         {data: 'name',name:'name'},     
                         {data: 'type',name:'type'},
                         {data: 'threshold',name:'threshold'},                       
                         {data: 'flag',name:'flag'},
                         {data: 'addressedby',name:'addressedby'},
                         {data: 'addressedtime',name:'addressedtime'},                                             
                         {data: 'readingtimestamp',type: 'date-dd-mm-yyyy h:i:s', name: 'readingtimestamp',"render":function (value) {
                             if (value === null) return "";
                                  return util.viewsDateFormatWithTime(value);
                              }
                          },
                           {data: 'webhook_observation_id',name:'webhook_observation_id'},      
                            {data: null, 
                            mRender: function(data, type, full, meta){
                            if(data!='' && data!='NULL' && data!=undefined){
                              match_status = full['match_status'];
                            if(match_status == null){                              
                              return '';
                            }
                            else
                            {
                              if(match_status=='1')
                              {
                                return "Yes";
                              }
                              else
                              {
                                 return "No";
                              }

                            }
                            } else { 
                                return ''; 
                            }    
                            },orderable: true
                            },     
                             //{data: 'alert_status',name:'alert_status'}, 
                             {data: null, 
                            mRender: function(data, type, full, meta){
                            if(data!='' && data!='NULL' && data!=undefined){
                              alert_status = full['alert_status'];
                            if(alert_status == null){                              
                              return '';
                            }
                            else
                            {
                              if(alert_status=='1')
                              {
                                return "Yes";
                              }
                              else
                              {

                                 return "No";
                              }

                            }
                            } else { 
                                return ''; 
                            }    
                            },orderable: true
                            },         
                         {data: 'action',name:'action'},
                        
                    ]
                    if(fromdate==''){fromdate=null;}
                        if(todate==''){todate=null;} 
                     var url="/org/rpm-alert-list/"+fromdate+"/"+todate;

            var table = util.renderDataTable('rpm-alert-list', url, columns, baseURL); 
    }; 

var init = function () {

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
          getrpmalertlisting(startdate,currentdate);

          $('#searchbutton').click(function(){
          var fromdate=$('#fromdate').val();
          var todate=$('#todate').val();
          var exception_type=$('#exception_type').val();
          var emr=$('#patient_emr').val();

            getrpmalertlisting(fromdate,todate);
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
            });
    
};

  $('#rpm-alert-list tbody').on('click', 'td .rpmalertnotes', function () { 
        $('#rpm_alert_modal').modal('show');     
         $('#notes').html($(this).val());
      })

window.rpmECGAlert={  
    init: init
}