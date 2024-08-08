@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection 

@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Scheduler</h4> 
        </div>
        <div class="col-md-1"> 
            <a class="" href="javascript:void(0)" id="addScheduler"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Scheduler"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Scheduler"></i></a>  
        </div>
    </div>
   <!-- ss -->             
</div>  
<div class="separator-breadcrumb border-top"></div>
<div id="success"></div>
<div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-left">      
      <div class="card-body"> 
                @include('Theme::layouts.flash-message')      
        <div class="table-responsive">
          <table id="scheduler-list" class="display table table-striped table-bordered capital" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50px">Sr No.</th>
                                <th>Activity</th>
                                <th>Services</th>
                                <th>Day of Execution</th>               
                                <th>Time of Execution</th> 
                                <th>Created by</th>
                                <th>Comments</th> 
                                <th>Action</th> 
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

<div class="modal fade" id="add_scheduler_modal" aria-hidden="true">  
    <div class="modal-dialog">  
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Add Scheduler</h4>
            <button type="button" class="close" data-dismiss="modal" onClick="form_clear()">&times;</button>
            </div>
       
            <div class="modal-body">
               <form action="{{route('save.scheduler')}}" name="add_scheduler_form" id="add_scheduler_form"  method="POST" class="form-horizontal">
                {{ csrf_field() }} 
                <div class="form-group">  
                   <div class="row">
                        <div class="col-md-6  form-group mb-3">
                            <label for="activity">Activity</label>   
                            @selectactivitytimertype("activity", ["id" => "activity","class" => "select2"])
                            <span id="lblError" style="color: red"></span>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="Services">Services</label><br> 
                            @foreach($modules as $m)
                            <input type="checkbox" name="{{$m->module}}" id="services" value="{{$m->id}}" >{{$m->module}} &nbsp; 
                            @endforeach  
                        </div>
                   </div> 

                   <div class="row">
                        <div class="col-md-8">
                         <label>Date & Time Execution </label>
                            <div class="row"> 
                                <div class="col-md-6 form-group mb-3">
                                    @date("day_of_execution", ["id" => "day_of_execution"])          
                                </div>
                                <div class="col-md-6 form-group mb-3">      
                                    @timetext("time_of_execution",["id" => "time_of_execution"])  
                                </div>
                            </div>
                        </div>
                       
                       <div  class="col-md-4 form-group mb-3">
                       <label for="operation">Operation</label>
                            <select id="operation" name="operation" class="custom-select show-tick select2">
                                <option value="1" selected>Add</option>
                                <option value="2">Deduct</option>  
                            </select> 
                       </div>
                   </div>

                   <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="comments">Comments</label><br>
                            <textarea name="comments" class="form-control" id="comments"></textarea>    
                        </div>   
                   </div>  
                </div> 
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1">Add Scheduler</button>
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal" >Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<!-- <div class="modal fade" id="edit_scheduler_modal" aria-hidden="true">   
    <div class="modal-dialog">  
        <div class="modal-content">

            <div class="modal-header">
            <h4 class="modal-title">Edit Scheduler</h4>
            <button type="button" class="close" data-dismiss="modal" >&times;</button>
            </div>
                
            <div class="modal-body">
            <form action="" name="edit_scheduler_form" id="edit_scheduler_form"  method="POST" class="form-horizontal">
                {{ csrf_field() }} 
                <div class="form-group">
                   <div class="row">
                        <div class="col-md-6  form-group mb-3">
                            <label for="activity">Activity</label>  
                            @selectactivitytimertype("activity", ["id" => "activity","class" => "select2"])
                            <span id="lblError" style="color: red"></span>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="Services">Services</label><br> 
                            @foreach($modules as $m)
                            <input type="checkbox" name="services" id="services" value={{$m->id}} >{{$m->module}} &nbsp;
                            @endforeach  
                        </div>
                   </div> 

                   <div class="row">
                        <div class="col-md-8">
                         <label>Date & Time Execution </label>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    @date("executiondate", ["id" => "executiondate"])          
                                </div>
                                <div class="col-md-6 form-group mb-3">      
                                    @timetext("executiontime",["id" => "executiontime"])  
                                </div>
                            </div>
                        </div>
                       
                       <div  class="col-md-4 form-group mb-3">
                       <label for="operation">Operation</label>
                            <select id="operation" name="operation" class="custom-select show-tick select2">
                                <option value="1" selected>Add</option>
                                <option value="2">Deduct</option>  
                            </select> 
                       </div>
                   </div>

                   <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="comments">Comments</label><br>
                            <textarea name="comments" class="form-control" id="comments"></textarea>    
                        </div>   
                   </div>  
                </div>
               <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1">Update Scheduler</button>  
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal" >Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            </div>

        </div>
    </div>
</div> -->

@endsection

@section('page-js')
  


<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">

var getSchedulerlisting =  function() {
        var columns = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'activity',name: 'activity',
                mRender: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        activityname = data['activity'];
                        return activityname;
                    } else { 
                        return ''; 
                    }    
                },orderable: false },
            {data: 'module_id',name: 'services', 
             mRender: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        if(data== 2){
                            return 'RPM';
                        } 
                        else if(data== 3){
                            return 'CCM';  
                        }
                        else if(data== 4){
                            return 'AWV';  
                        }
                        else {
                            return 'TCM';
                        }
                    } 
                },orderable: false 
            },
            {data:'day_of_execution', name:'day_of_execution'},
            {data: 'time_of_execution',name:'time_of_execution'},
            {data:'users',
             mRender: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        l_name = data['l_name'];
                        if(data['l_name'] == null && data['f_name'] == null){
                            l_name = '';
                            return '';
                        } else {
                            return data['f_name'] + ' ' + l_name;
                        }
                    } else { 
                        return ''; 
                    }    
                },orderable: false 
            }, 
            // {data: 'updated_at',name:'updated_at'},
            // {data:'updated_at',
            //                         mRender: function(data, type, full, meta){
                                        
            //                             console.log("outsideif"+full['updated_at']);
            //                             if(full['updated_at'] == null || full['updated_at']=='null' || full['updated_at']== ''  ){  
            //                                 last_date = full['created_at'];
            //                                 console.log("if"+full['created_at']);
            //                                 return last_date;
            //                             }
            //                             else{ 
            //                                 console.log("else"+full['updated_at']); 
            //                                 last_date = full['updated_at'];
            //                                 return last_date;
            //                             }
            //                             // if(data!='' && data!='NULL' && data!=undefined){  
            //                             //     return last_date;
            //                             // }
            //                         },
            //                         orderable: true 
            //                     }, 

            // {data: 'updated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at',"render":function (value) {
            //    if (value === null) return "";
            //         return util.viewsDateFormatWithTime(value); 
            //     }
            // },

            {data:'comments', name:'comments'}, 
             
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('scheduler-list', "{{ route('scheduler.list') }}", columns, "{{ asset('') }}");  
    };

$('#addScheduler').click(function () {
    $('#add_scheduler_modal').modal('show');
});


$(document).ready(function(){
    getSchedulerlisting();
    scheduler.init();  
    util.getToDoListData(0, {{getPageModuleName()}});  
});   


 
</script>


@endsection

 
