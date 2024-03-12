@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Scheduler</h4>       
           
        </div>
        <div class="col-md-1">
            <a class="" href="javascript:void(0)" id="addScheduler"><i class="add-icons i-Administrator"
                    data-toggle="tooltip" data-placement="top" title="Schedule Event"></i><i class="plus-icons i-Add"
                    data-toggle="tooltip" data-placement="top" title="Schedule Event"></i></a>
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
                    <table id="scheduler-list" class="display table table-striped table-bordered capital"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th width="50px">Sr No.</th>
                                <th>Activity</th>
                                <th>{{config('global.practice_group')}}</th>
                                <th>Services</th>
                                <th>Start Date</th> 
                                <th>Day of Execution</th>
                                <th>Time of Execution</th>
                                <th>Operation</th> 
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
    <div class="modal-dialog modal-lg" style="
    width: 800px!important;
    margin-left: 280px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Schedule Event</h4>
                <button type="button" class="close" data-dismiss="modal" onClick="form_clear()">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{route('save.scheduler')}}" name="add_scheduler_form" id="add_scheduler_form"
                    method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <div>
                        <div class="row">
                            <div class="col-md-4  form-group mb-3">
                                <label for="activity">Activity<span style="color: red">*</span></label>
                                <div class="forms-element">
                                    @selectactivitytimertype("activity_id", ["id" => "activity","class" => "select2"])
                                </div>
                                <div class="invalid-feedback"></div>
                                <!-- @if ($errors->has('activity_id'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('activity_id') }}</strong>
                            </span>
                        @endif -->

                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label for="Services">Services<span style="color: red">*</span></label><br>
                                <div class="forms-element">
                                @foreach($modules as $m)
                                <input type="checkbox" name="module_id[{{$m->id}}]"  
                                    id="services_{{$m->module}}" value="{{$m->id}}">{{' '.$m->module}} &nbsp;
                                @endforeach
                                </div>      
                                <div class="invalid-feedback" id="services-error" style="display:none;color:red"></div>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="operation">Operation<span style="color: red">*</span></label>
                                <select id="operation" name="operation" class="custom-select show-tick select2">
                                    <option value="1" selected>Add</option>
                                    <option value="2">Subtract</option>
                                </select>
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Start Date<span style="color: red">*</span></label>
                                @date("date_of_execution", ["id" => "date_of_execution1"])
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>Day of Execution <span style="color: red">*</span></label>
                                <div class="forms-element">
                                <select name="day_of_execution" id="day_of_execution" class="select2">
                                    @for($i=1; $i < 32 ; $i++) <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                </select> 
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 form-group mb-3">  
                                <label for="time_of_execution">Time of Execution (24 hours format)<span style="color: red">*</span></label>
                                @timetext("time_of_execution",["id" => "time_of_execution"])
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="comments">Comments</label><br>
                                <textarea name="comments" class="form-control" id="comments"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group" style="margin-top: 15px;">
                                <p id ="selected_activity_details2"></p>
                            </div>
                            <div class="col-md-4 form-group" style="margin-top: 15px;"> 
                                <span id ="selected_activity_details1"></span>
                            </div>
                            <div class="col-md-4 form-group" style="margin-top: 15px;">
                                <p id ="selected_activity_details3"></p>
                            </div> 
                        </div>

                        <div class="row"> 
                            <div class="col-md-4 form-group" id ="dynamic_practice_grp" style="margin-top:-9px;">  
                            </div>
                            <div class="col-md-4 form-group" id ="dynamic_timerequired" style="margin-top:-9px;">        
                            </div>
                            <div class="col-md-4 form-group" id ="dynamic_practices" style="margin-top:-9px;">                                    
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 form-group" style="margin-top:-9px;">
                                <p id ="selected_activity_details4"></p>
                            </div> 
                        </div>
                       
                       
                    </div>  
                    <div class="card-footer">  
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="scheduleevent" class="btn  btn-primary m-1">Schedule Event</button>
                                    <button type="button" class="btn btn-outline-secondary m-1"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_scheduler_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Edit Scheduler</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{route('update.scheduler')}}" name="edit_scheduler_form" id="edit_scheduler_form"
                    method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6  form-group mb-3">
                                <input type="hidden" name="scheduler_id" id="scheduler_id" />
                                <label for="activity">Activity</label>
                                @selectactivitytimertype("editactivity", ["id" => "editactivity","class" => "select2"])
                                <!-- <span id="lblError" style="color: red"></!--> -->
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="Services">Services</label><br>
                                @foreach($modules as $m)
                                <input type="checkbox" name="modules[{{$m->id}}]" id="modules_{{$m->id}}"
                                    value={{$m->id}}>{{$m->module}} &nbsp;
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

                            <div class="col-md-4 form-group mb-3">
                                <label for="operation">Operation</label>
                                <select id="operation" name="operation" class="custom-select show-tick select2">
                                    <option value="1">Add</option>
                                    <option value="2">Subtracts</option>
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
                                    <button type="submit" class="btn  btn-primary m-1">Update Scheduler</button>
                                    <button type="button" class="btn btn-outline-secondary m-1"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>