@extends('Theme::layouts_2.to-do-master')
@section('page-css')
<style type="text/css">   
    button#patientdetailsicd {
        margin-left: 20px;
        float: right;
    }
    .checkdiv {
        position: relative;
        padding: 4px 8px;
        border-radius:40px;
        margin-bottom:4px;
        min-height:30px;
        padding-left:40px;
        display: flex;
        align-items: center;
    }
    .checkdiv:last-child {
        margin-bottom:0px;
    }
    .checkdiv span {
        position: relative;
        vertical-align: middle;
        line-height: normal;
    }
    .invalid {
        appearance: none;
        position: absolute;
        top: 43%;
        left: 4px;
        transform: translateY(-50%);
        background-color: white;
        width: 12px;
        height: 12px;
        border: 1px #000000c7 solid;
        border-radius: 3px;
    }
    .invalid:checked:before {
        content:'';
        position: absolute;
        top:50%;
        left:50%;
        transform:translate(-50%,-50%) rotate(45deg);
        background-color:red;
        width:10px;
        height:2px;
        border-radius:10px;
        transition:all .5s;
    }

    .invalid:checked:after {
        content:'';
        position: absolute;
        top:50%;
        left:50%;
        transform:translate(-50%,-50%) rotate(-45deg);
        background-color:red;
        width:10px;
        height:2px;
        border-radius:10px;
        transition:all .5s;
    }
    table {
            /* border-spacing: 0px; */
            table-layout: fixed;
            /* margin-left: auto; */
            /* margin-right: auto; */
        }
        
        td {
            word-wrap: break-word;
        }
</style>
@endsection
@section('page-title')
    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">ICD10 Code Report</h4>
        </div>
    </div>            
</div>
<div class="separator-breadcrumb border-top"></div> 
<div id="app"></div>
<div class="row mb-4" id="call-sub-steps"> 
    <div class="col-md-12">
        <div class="card12"> 
            <div class="row ">
                <div class="col-md-12 ">  
                    <div class="card text-left">
                        <div class="card-body" >
                        <div id="success-set" style="display: none;">  
                            <div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Verified Successfully!</strong>
                            </div>
                        </div>
                        <div id="success-unset" style="display: none;">  
                            <div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Verified Successfully!</strong>
                            </div>
                        </div>
                           <!-- <h4 class="card-title mb-3">Practices</h4> -->
                            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="invalid-icon-tab" data-toggle="tab" href="#invalid" role="tab" aria-controls="invalid" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i>Non Verified Codes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="valid-icon-tab" data-toggle="tab" href="#valid" role="tab" aria-controls="valid" aria-selected="true"><i class="nav-icon color-icon i-Telephone mr-1"></i>Verified Codes</a>
                                </li>
                            </ul> 
                            <div class="tab-content" id="myIconTabContent">
                                <div class="tab-pane show active" id="invalid" role="tabpanel" aria-labelledby="invalid-icon-tab">
                                    @include('Reports::verify_icd10_codes_reports.invalidicdcode')
                                </div>
                                <div class="tab-pane" id="valid" role="tabpanel" aria-labelledby="valid-icon-tab">
                                    @include('Reports::verify_icd10_codes_reports.validicdcode')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
   
    <script type="text/javascript">
        
        var getflyout = function (){
            util.getToDoListData(0, {{ getPageModuleName() }});
        }

        var getvalidCTTListReport = function(practices=null,diagnosis=null) {
          //  $.fn.dataTable.ext.errMode = 'throw';
          if(diagnosis=='')
            {
                diagnosis=null;
            }
            if(practices=='')
            {
                practices=null;
            }  
          var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null, 
                    mRender: function(data, type, full, meta){
                        code1 = full['code1'];
                        if(full['code1'] == null){
                            code1 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['code1'] == null){
                                code1 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return code1;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null,
                    mRender: function(data, type, full, meta){
                        condition = full['condition'];

                        if(full['condition'] == null){
                            condition = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['condition'] == null){
                                condition = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return condition;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null,
                    mRender: function(data, type, full, meta){
                        patientcount = full['patientcount'];

                        if(full['patientcount'] == null){
                            patientcount = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['patientcount'] == null){
                                patientcount = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){  
                                var newcode = full['newcode'];
                                var newcondition = full['condition'];
                                var diag = full['diagnosis_id'];
             
                                // return patientcount + ' '+'<button type="button" id="patientdetailsicd" onclick=patientdetailsformodal("'+newcode+'","'+newcondition+'") class="btn btn-primary" >Details</button>';
                                return patientcount + '   '+'<button type="button" id="patientdetailsicd" onclick=patientdetailsformodal("'+newcode+'","'+diag+'","'+practices+'") class="btn btn-primary" >Details</button>';
                            
                            } 
                            
                
                        }
                    },
                    orderable: true
                },
                {data: 'valid_chk', name: 'valid_chk', orderable: false, searchable: false},
                {data: 'invalid_chk', name: 'invalid_chk', orderable: false, searchable: false},
                {data: 'verify_yes_no', name: 'verify_yes_no', orderable: false, searchable: false},
                {data: 'f_name', name: 'f_name',render: 
                    function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.l_name;
                        } else { 
                            return '';
                        }
                    }
                },
                {data: null,
                    mRender: function(data, type, full, meta){
                        verify_on = full['verify_on'];

                        if(full['verify_on'] == null){
                            verify_on = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['verify_on'] == null){
                                verify_on = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return verify_on;
                            }               
                        }
                    },
                    orderable: true
                }
                ];
            
            var url = "/reports/verify-code/search"+'/'+practices+'/'+diagnosis;
            console.log(url);
            util.renderDataTable('verify_report', url, columns, "{{ asset('') }}");
              
        }

        var getinvalidCTTListReport = function(practices=null,diagnosis=null) {
          //  $.fn.dataTable.ext.errMode = 'throw';
          if(diagnosis=='')
            {
                diagnosis=null;
            }
            if(practices=='')
            {
                practices=null;
            }  
          var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null, 
                    mRender: function(data, type, full, meta){
                        code1 = full['code1'];
                        if(full['code1'] == null){
                            code1 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['code1'] == null){
                                code1 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return code1;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null,
                    mRender: function(data, type, full, meta){
                        condition = full['condition'];

                        if(full['condition'] == null){
                            condition = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['condition'] == null){
                                condition = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return condition;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null,
                    mRender: function(data, type, full, meta){
                        patientcount = full['patientcount'];

                        if(full['patientcount'] == null){
                            patientcount = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['patientcount'] == null){
                                patientcount = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){  
                                var newcode = full['code'];
                                var newcondition = full['condition'];
                                var diag = full['diagnosis_id'];
                                var practicid = full['practicid'];

                                // return patientcount + ' '+'<button type="button" id="patientdetailsicd" onclick=patientdetailsformodal("'+newcode+'","'+newcondition+'") class="btn btn-primary" >Details</button>';
                                return patientcount + '   '+'<button type="button" id="patientdetailsicd" onclick=patientdetailsformodal1("'+newcode+'","'+diag+'","'+practices+'") class="btn btn-primary" >Details</button>';
                            
                            } 
                            
                        }
                    },
                    orderable: true
                },
                {data: 'valid_chk', name: 'valid_chk', orderable: false, searchable: false},
                {data: 'invalid_chk', name: 'invalid_chk', orderable: false, searchable: false},
                // {data: 'verify_yes_no', name: 'verify_yes_no', orderable: false, searchable: false},
                // {data: 'f_name', name: 'f_name',render: 
                //     function(data, type, full, meta){
                //         if(data!='' && data!='NULL' && data!=undefined){
                //             return data + ' ' + full.l_name;
                //         } else { 
                //             return '';
                //         }
                //     }
                // },
                // {data: null,
                //     mRender: function(data, type, full, meta){
                //         verify_on = full['verify_on'];

                //         if(full['verify_on'] == null){
                //             verify_on = '';
                //         }
                //         if(data!='' && data!='NULL' && data!=undefined){
                //             if(full['verify_on'] == null){
                //                 verify_on = '';
                //             }
                //             if(data!='' && data!='NULL' && data!=undefined){
                //                 return verify_on;
                //             }               
                //         }
                //     },
                //     orderable: true
                // }
                ];
            
           // debugger; 
            // if(diagnosis=='')
            // {
            //     diagnosis=null;
            // }
            // if(practices=='')
            // {
            //     practices=null;
            // }
          //  if(activedeactivestatus==''){activedeactivestatus=null;}
                
            var url = "/reports/verify-code1/search"+'/'+practices+'/'+diagnosis;
            console.log(url);
            util.renderDataTable('verify_report1', url, columns, "{{ asset('') }}");
              
        }

        var getCTTChildListReport = function(diagnosis=null,code=null,practicid=null) {
            // $.fn.dataTable.ext.errMode = 'throw';   
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},

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
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                },
                                orderable: true
                },

                {data: null,
                            mRender: function(data, type, full, meta){
                            practicename = full['practicename'];
                            if(full['practicename'] == null){
                                practicename = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return practicename;
                            }
                        },
                        orderable: true
               },

               {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                if (value === null) return "";
                    return moment(value).format('MM-DD-YYYY');
                }
                },  

                {data: null, 
                    mRender: function(data, type, full, meta){
                        code1 = full['code1'];
                        if(full['code1'] == null){
                            code1 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['code1'] == null){
                                code1 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return code1;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null,
                    mRender: function(data, type, full, meta){
                        condition = full['condition'];

                        if(full['condition'] == null){
                            condition = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['condition'] == null){
                                condition = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return condition;
                            }               
                        }
                    },
                    orderable: true
                }
               
                ];
            
            if(diagnosis=='')
            {
                diagnosis=null;
            }

            if(code==''){
                code = null; 
            }else{
                var code1 = code.replace(/__/g, " ");
                code = code1;
            }
            
            var url = "/reports/verify-code-child/search"+'/'+diagnosis+'/'+code+'/'+practicid;
            console.log(url);
            util.renderDataTable('verify_report_child', url, columns, "{{ asset('') }}");
              
        }

        var getCTTChildListReport1 = function(diagnosis=null,code=null,practicid=null) {
            // $.fn.dataTable.ext.errMode = 'throw';   
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},

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
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                },
                                orderable: true
                },

                {data: null,
                            mRender: function(data, type, full, meta){
                            practicename = full['practicename'];
                            if(full['practicename'] == null){
                                practicename = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return practicename;
                            }
                        },
                        orderable: true
               },

               {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                if (value === null) return "";
                    return moment(value).format('MM-DD-YYYY');
                }
                },  

                {data: null, 
                    mRender: function(data, type, full, meta){
                        code1 = full['code1'];
                        if(full['code1'] == null){
                            code1 = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['code1'] == null){
                                code1 = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return code1;
                            }               
                        }
                    },
                    orderable: true
                },
                {data: null,
                    mRender: function(data, type, full, meta){
                        condition = full['condition'];

                        if(full['condition'] == null){
                            condition = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['condition'] == null){
                                condition = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return condition;
                            }               
                        }
                    },
                    orderable: true
                }
               
                ];
            
           
            if(diagnosis=='')
            {
                diagnosis=null;
            }

            if(code==''){
                code = null; 
            }else{
                var code1 = code.replace(/__/g, " ");
                code = code1;
            }
            
        
                
            var url = "/reports/verify-code-child1/search"+'/'+diagnosis+'/'+code+'/'+practicid;
            console.log(url);
            util.renderDataTable('verify_report_child1', url, columns, "{{ asset('') }}");
              
        }
        
    
        var patientdetailsformodal = function(code,diag,practicid){
            getCTTChildListReport(diag,code,practicid);      
            $('#patientdetailsicdmodel').modal('show');
        }

        var patientdetailsformodal1 = function(code,diag,practicid){
            getCTTChildListReport1(diag,code,practicid);      
            $('#patientdetailsicdmodel1').modal('show');
        }
      
    $(document).ready(function(){    
        // $.fn.dataTable.ext.errMode = 'throw';
        util.getToDoListData(0, {{ getPageModuleName() }});
        getvalidCTTListReport();
        getinvalidCTTListReport();   
        $('#valid-icon-tab').click(function(){ //alert("working");
            var element = document.getElementById('invalid-icon-tab');
            element.classList.remove('active');

            var element = document.getElementById('valid-icon-tab');
            element.classList.add('active');

            var element = document.getElementById('invalid');
            element.classList.remove('show' , 'active');

            var element = document.getElementById('valid');
            element.classList.add('show' , 'active');
        });

        $('#invalid-icon-tab').click(function(){ //alert("working");
            var element = document.getElementById('valid-icon-tab');
            element.classList.remove('active');

            var element = document.getElementById('invalid-icon-tab');
            element.classList.add('active');

            var element = document.getElementById('valid');
            element.classList.remove('show' , 'active');

            var element = document.getElementById('invalid');
            element.classList.add('show' , 'active');
        });

        $('#validcttsearchbutton').click(function(){ //alert("working");
            var practices=$('#practices').val();
            var diagnosis=$('#diagnosis_condition').val();
            getvalidCTTListReport(practices,diagnosis);        

        });

        $('#validcttresetbutton').click(function(){    
            $('#practices').val('').trigger('change');   
            $('#diagnosis_condition').val('').trigger('change');   
        });

        $('#invalidcttsearchbutton').click(function(){ //alert("working");
            var practices=$('#practices1').val();
            var diagnosis=$('#diagnosis_condition1').val();
            getinvalidCTTListReport(practices,diagnosis);        
        });

        $('#invalidcttresetbutton').click(function(){    
            $('#practices1').val('').trigger('change');   
            $('#diagnosis_condition1').val('').trigger('change');   
        });

        $('#verify_report tbody').on('click', 'td .chk_valid', function () { //alert("working");
            var arr=$(this).attr('id');    
            var id = arr.split('/');
        //alert(id);
            var valid_invalid="";   
            if($(this).prop('checked') == true){
                valid_invalid="1";
            } else {
                valid_invalid="0";
            }
            //alert(valid_invalid);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/reports/verify-request',
                data: {valid_invalid: valid_invalid, 
                    code:id[0],
                    condition:id[1]
                },
                success: function (data) {
                var practices=$('#practices').val();
                var diagnosis=$('#diagnosis_condition').val();
                // alert(diagnosis);
                //getvalidCTTListReport(diagnosis,practices); 
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

        $('#verify_report tbody').on('click', 'td .chk_invalid', function () { //alert("working");
            var arr=$(this).attr('id');    
            var id = arr.split('/');
           // alert(id);
            var valid_invalid="";   
            if($(this).prop('checked') == true){
                valid_invalid="0";
            } else {
                valid_invalid="1";
            }
            //alert(valid_invalid);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/reports/verify-request',
                data: {valid_invalid: valid_invalid, 
                    code:id[0],
                    condition:id[1]
                },
                success: function (data) {
                var practices=$('#practices').val();
                var diagnosis=$('#diagnosis_condition').val();
                // alert(diagnosis);
                //getvalidCTTListReport(diagnosis,practices); 
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

        $('#verify_report1 tbody').on('click', 'td .chk_valid', function () { //alert("working");
            var arr=$(this).attr('id');    
            var id = arr.split('/');
            //alert(id);
            var valid_invalid="";   
            if($(this).prop('checked') == true){
                valid_invalid="1";
            } else {
                valid_invalid="0";
            }
            //alert(valid_invalid);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/reports/verify-request',
                data: {valid_invalid: valid_invalid, 
                    code:id[0],
                    condition:id[1]
                },
                success: function (data) {
                var practices=$('#practices').val();
                var diagnosis=$('#diagnosis_condition').val();
                // alert(diagnosis);
                //getinvalidCTTListReport(diagnosis,practices); 
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

        $('#verify_report1 tbody').on('click', 'td .chk_invalid', function () { //alert("working");
            var arr=$(this).attr('id');    
            var id = arr.split('/');
          //  alert(id);
            var valid_invalid="";   
            if($(this).prop('checked') == true){
                valid_invalid="0";
            } else {
                valid_invalid="1";
            }
            //alert(valid_invalid);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/reports/verify-request',
                data: {valid_invalid: valid_invalid, 
                    code:id[0],
                    condition:id[1]
                },
                success: function (data) {
                var practices=$('#practices').val();
                var diagnosis=$('#diagnosis_condition').val();
                // alert(diagnosis);
                //getinvalidCTTListReport(diagnosis,practices); 
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

    }); 
    </script>
@endsection