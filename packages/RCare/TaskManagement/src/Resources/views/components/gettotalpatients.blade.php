@extends('Theme::layouts_2.to-do-master')
@section('page-title')
    Patients - 
@endsection
@section('page-css')
@endsection 
@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-11">
                <h4 class="card-title mb-3">Patients</h4>
            </div>
        </div>
        <!-- ss -->             
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row">
        <div class="card mb-4 col-md-12">  
            <div class="form-group" id="allpatient" name="allpatient" > 
                <div class="card-body">
                    @include('Theme::layouts.flash-message')
                    <div class="form-row">
                        <div class="col-md-6 form-group">  
                            <label for="practicename">Practice</label>                        
                            @selectpracticespcp("practices", ["class" => "select2","id" => "practicesfour"])
                            <!-- selectpracticeswithoutNone -->
                        </div>
                        <div class="col-md-1 form-group mb-3">
                            <button type="button" id="searchbuttonfour" class="btn btn-primary mt-4">Search</button>
                        </div>
                    </div>
                </div>
            </div>                                         
        </div>        
    </div>

    <div class="row">
        <div class="card col-md-12">  
            <div class="form-group" id="allpatient" name="allpatient" >
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="all-patient-list" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="35px">Sr No.</th>                        
                                    <th width="50px">Practice EMR</th>     
                                    <th width="205px">Patient</th>
                                    <th width="97px">DOB</th>
                                    <th width="97px">Practice</th>
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
        var getallpatientlist =function(practice = null) {
            var columns =  [ 
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},

                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['practice_emr'];
                        if(full['practice_emr'] == null){
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
            ];
            // debugger;
            if(practice==''){practice=null;}
            // var url = "/patients/patients-assignment/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption;
            //var url = "/patients/patients-assignment/nonassignedpatients/"+practice;
            var url ="/task-management/patients-assignment/allpatients/"+practice;
            // console.log(url);
            util.renderDataTable('all-patient-list', url, columns, "{{ asset('') }}");
        }

        $(document).ready(function() {
            getallpatientlist();
        });


        $('#searchbuttonfour').click(function(){
            var practice=$('#practicesfour').val();
            // alert(practice); 
            // console.log(practice);
            getallpatientlist(practice);
        });

    </script>
@endsection 