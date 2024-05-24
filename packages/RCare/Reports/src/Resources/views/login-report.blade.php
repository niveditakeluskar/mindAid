@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')

@endsection
@endsection 
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Login Logs</h4>
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
                    
                   
                    <div class="col-md-3 form-group mb-3">
                        <label for="date">Date</label>
                        @date('date',["id" => "fromdate"])                           
                    </div>

                    <div class="col-md-3 form-group mb-3">
                        <label for="users">Users</label>
                        @selectUsers('users',["id" => "users","class"=>"select2"])                                
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
             <!-- <button type="button" id="billupdate" class="btn btn-primary mt-4" style="margin-left: 1110px;margin-bottom: 9px;">Add Bill</button> -->
                <div class="table-responsive">
                    <table id="login-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="97px">User Name</th>
                            <th width="205px">User Email</th>
                            <th width="97px">IP Address</th>
                            <th width="97px">Login Time</th>
                            <th width="97px">Logout Time</th>                         
                       
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
        var getLoginList = function(fromdate=null,users=null) {     
            var columns =  [   
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},


                                {data: null, mRender: function(data, type, full, meta){
                                    // m_Name = full['pmname'];
                                    // if(full['pmname'] == null){
                                    //     m_Name = '';
                                    // }
                                    m_Name = '';  
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        return full['f_name']+' '+m_Name+' '+full['l_name'];
                                        // if(full['profile_img']=='' || full['profile_img']==null) {
                                        //     return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['f_name']+' '+m_Name+' '+full['l_name'];
                                        // } else {
                                        //     return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['f_name']+' '+m_Name+' '+full['l_name'];
                                        // }
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                    // return data[0]['loginusers']['f_name'];
                                }, 
                                orderable: true
                                },

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        user_email = full['user_email'];
                                        if(full['user_email'] == null){
                                            user_email = '';   
                                        }
                                        return user_email;  
                                    },
                                    orderable: true  
                                },

                               
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['ip_address'];
                                        if(full['ip_address'] == null){
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
                                        logintotaltime = full['login_time'];
                                        if(full['login_time'] == null){
                                            logintotaltime = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return logintotaltime;
                                        }
                                    },
                                    orderable: true
                                },


                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        logouttotaltime = full['logout_time'];
                                        if(full['logout_time'] == null){
                                            logintotaltime = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return logouttotaltime;
                                        }
                                    },
                                    orderable: true
                                },  



                          
                            ];  
            
         
             if(fromdate==''){ fromdate=null; } 
             if(users==''){ users=null;}   
            
            var url = "/reports/login-logs-report/"+fromdate+'/'+users;   
                console.log(url);   
		    table1 = util.renderDataTable('login-list', url, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {

            

            function convert(str) {
            var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");     
            }
 
            // var date = new Date(), y = date.getFullYear(), m = date.getMonth();
            // var firstDay = new Date(y, m, 1);
            // var lastDay = new Date();

            var d = new Date();
            var strDate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();


           $("#fromdate").attr("value", (convert(strDate)));
           var fromdate1=$('#fromdate').val();
           var users1 = "null";    
            getLoginList(fromdate1,users1); 
 
        }); 

        $('#resetbutton').click(function(){ 
            
            function convert(str) {
            var date = new Date(str), 
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            }

            var d = new Date();
            var strDate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();  
             
            var mydate = convert(strDate); 
            $('#users').val('').trigger('change'); 
            $('#fromdate').val(mydate);  
            var fromdate2 =$('#fromdate').val();
            var users1 = "null";   
            
            getLoginList(fromdate2,users1);      
            
        });


   
$('#searchbutton').click(function(){ 
  //debugger
   
    var fromdate1=$('#fromdate').val();
    var users=$('#users').val();   
    
    getLoginList(fromdate1,users);          
});


 
    </script>
@endsection