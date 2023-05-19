@extends('Theme::layouts.master')
@section('page-css')
<!-- <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}"> -->
<style> 
        .disabled {  
            pointer-events: none; 
            cursor: default; 
        } 
		
		.table-responsive {
			display: block;
			width: 95%;
			overflow-x: auto; 
			margin: auto;
		}
		button.dt-button {
			border-style: none !important;
			background: none;
			cursor:pointer;
		}
		.paginate_button{
			cursor:pointer;
		}
    </style>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
		<style>
		table.dataTable{
			border-bottom:1px solid #ced4da;
		}
		table.dataTable thead th {
			vertical-align: bottom;
			border-bottom: 2px solid #9E9E9E !important;
		}
		.dataTables_filter input{
			outline: initial !important;
			background: #f8f9fa;
			border: 1px solid #ced4da;
			color: #fff;
		}
		.dataTables_filter input {
			background-image: url(https://img.icons8.com/search);
			background-position-y: center;
			background-position-x: 95%;
			background-size: contain;
			background-repeat: no-repeat;
			background-size: 18px 17px !important;
			margin-right:4px;
            color:black!important;
		}
		.dataTables_filter label{ 
			color:#fff;
		} 
		button.dt-button.buttons-copy, button.dt-button.buttons-pdf, button.dt-button.buttons-excel, button.dt-button.buttons-csv{
			background:none;
			background-image:none;
			margin:0px;
			padding:0px 2px;
		}
		</style>
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables/editor.dataTables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">Activities</h4>  
 </div>
 <div class="col-md-1">
   <a class="" href="javascript:void(0)" id="addActivity"><i class="add-icons i-File-Pie" data-toggle="tooltip" data-placement="top" title="Add Activity"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Activity"></i></a>  
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
             <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
             @include('Theme::layouts.flash-message')
             <div class="table-responsive">
                <table id="activity-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th width="50px">Activity Type</th>
                            <th width="50px">Timer Type</th>
                            <th width="50px">Default Time</th>
                            <th width="50px">Activity</th>
                            <th width="50px">Last Modified By</th>
                            <th width="50px">Last Modified On</th>
                            <th width="50px">Sequence</th>
                            <th width="40px">Action</th>
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
<?php $org = config('global.practice_group'); ?>   
 

<!--start modal-->
<div id="add-activities" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content--> 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Activity</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <form action="{{route('add-activity')}}" method="post" name ="add_activity_form"  id="add_activity_form">
                            @csrf   
                           

                            <div class="card-body"> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4 form-group">
                                      
                                        <label for="activitytype" class="forms-element">Activity Type<span class='error'>*</span></label>
                                        <input type="text" class="form-control"  size="50" name="activitytype" id="activitytype">
                                        <div class="invalid-feedback"></div> 
                                        <!-- @text('activity_type',['id'=>'activitytype'])  --> 
                                        </div>
                                    </div>  
                                </div>

                                <div class="row">
                                 
                                    <div class="col-md-4 form-group"> 
                                    <label for="activity" class="forms-element">Activity<span class='error'>*</span></label>
                                        <input type="text" class="form-control" class="form-control" name="activity[0][activity]" id="activity">
                                       <div class="invalid-feedback"></div>
                                        <!-- @text('activity[0][activity]',['id'=>'activity']) --> 
                                    </div> 
                                    <div class=" col-md-4 form-group" style="margin-top:27px">
                                        <select id="activitydropdown" name="activity[0][activitydropdown]" class="custom-select show-tick" >
                                        <option value="1" selected>Manual</option> 
                                        <option value="2">Standard</option>
                                        <option value="3">Backend</option>
                                        </select>                                        
                                    </div>
                                     
                                    <div class="col-md-3 form-group" id="defaulttiming" style="display:none;">
                                        <label for="defaulttiming" class="forms-element">Default</label>
                                        <input type="text" class="form-control" id="defaulttime" name="activity[0][defaulttime]" placeholder="hh:mm:ss">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-1" style="margin-top:29px"> 
                                     <i class="plus-icons i-Add" id="add-newactivity" name="add-newactivity"></i>
                                    </div>

                                  </div>

                                 

                                  <div class="row" id="newtask" style="display:none">

                                   

                                  <div class="col-md-12">  
                                    <div class="row">
                                        <div  class="col-md-4 form-group" id="">
                                            <label for="practicegroup">{{config('global.practice_group')}}</label>  
                                            @selectgrppractices("activity[0][dynamicdropdown][]", ["class" => "select2","id" => "practicesgrp"])  
                                        </div>    
                                        <div class="col-md-4 form-group"  id="practice_'+i+'" >
                                            <label for="practicename" >Practice Name</label>
                                            <!-- @selectGroupedPractices("activity[0][practices][]", ["id" => "practices", "class" => "select2"])     -->
                                            <div class="wrapMulDrop">
                                            <button type="button" id="candy_0_0" class="multiDrop form-control col-md-12">Select practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                    
                                            <?php if (isset($prac[0]->id) && $prac[0]!=''){?>
                                            <ul id="utilulpract_0_0" style="height:80px;overflow-y:scroll">
                                                 
                                                <?php foreach ($prac as $key => $value): ?> 
                                                <li id="utilpract" style="display:block"> 
                                                    <label class="forms-element checkbox checkbox-outline-primary"> 
                                                        <input class="form-control" name ="activity[0][practices][0][<?php echo $value->id;?>]"   type="checkbox" value ="<?php echo $value->id;?>"><span class=""><?php echo $value->name?></span><span class="checkmark"></span>              
                                                    </label>
                                                </li>
                                                <?php endforeach ?> 
                                            </ul>
                                            <?php }else{?> 
                                            <?php }?>    
                                                    
                                           </div>

                                        </div>
                                        <div class="col-md-3 form-group" id="practicebasedtiming_'+i+'" >
                                            <label for="practicebasedtiming" >Time spent</label>
                                            <input type="text" class="form-control" id="newactivitypracticebasedtime" name="activity[0][newactivitypracticebasedtime][]" placeholder="hh:mm:ss">
                                            <div class="invalid-feedback"></div>  
                                        </div>
                                        <div class="col-md-1" style="margin-top:28px">
                                            <i class="plus-icons i-Add" id="add-newpracticetime" name="add-newpracticetime"></i>
                                        </div>
                                    </div>
                                  </div>
                                    
                                  </div>

                                  <div class="row">
                                    <!-- <div class="col-md-4" id="defaulttimeincrement"></div> -->
                                    <div class="col-md-12" id="practicetimeincrement"></div>
                                  </div>  
                                  
                                
                                  <hr id="line" style="border-top: 1px solid #2cb8ea; display:none">
                                        
                                <div id="inc"></div> 
                           
                            
                            </div>         
                            <div class="modal-footer">
                                <!-- <button type="submit" class="btn btn-primary float-right submit-active-deactive">Submit</button> -->
                                <button type="submit" class="btn btn-primary float-right" id="activitysubmit">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal content end-->
</div>
<!--end modal-->

<!--start edit modal-->
<div id="edit-activities-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content--> 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Activity</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <form action="{{route('updateactivity')}}" method="post" name ="edit_activity_form"  id="edit_activity_form">
                            @csrf   
                           

                            <div class="card-body"> 

                            <input type="hidden" name="activity_id" id="activity_id"/> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4 form-group">
                                        <label for="activitytype" class="forms-element">Activity Type<span class='error'>*</span></label>
                                        <input type="text" class="form-control"  size="50" name="activity_type" id="activity_type">
                                        <div class="invalid-feedback"></div> 
                                        </div>
                                    </div>  
                                </div>

                                <div class="row">
                                 
                                    <div class="col-md-4 form-group"> 
                                    <label for="activity" class="forms-element">Activity<span class='error'>*</span></label>
                                        <input type="text" class="form-control" class="form-control" name="activity" id="activity">
                                        <div class="invalid-feedback"></div>
                                    </div> 
                                    <div class=" col-md-4 form-group" style="margin-top:27px">
                                        <select id="timer_type" name="timer_type" id="timer_type" class="custom-select show-tick" >
                                        <option value="1">Manual</option> 
                                        <option value="2">Standard</option>
                                        <option value="3">Backend</option>
                                        </select>                                        
                                    </div>
                                     
                                    <div class="col-md-3 form-group" id="defaulttimingnew" style="display:none;">
                                        <label for="defaulttiming" class="forms-element">Default</label>
                                        <input type="text" class="form-control" id="default_time" name="default_time">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <!-- <div class="col-md-1" style="margin-top:29px">
                                     <i class="plus-icons i-Add" id="add-newactivity" name="add-newactivity"></i>
                                    </div> -->

                                  </div> 

                                  <div  id="append_parameter"style="display:none;"><br></div>

                                  <div  id="editprac" style="display:none;">
                                    <div class="row" id="p1">
                                        <div  class="col-md-4 form-group"> 
                                            <label for="practicegroup">{{config('global.practice_group')}}</label> 
                                            @selectgrppractices("editpracticesgrp[]", ["class" => "select2","id" => "editpracticesgrp"])  
                                        </div>     
                                        <div class="col-md-4 form-group"> 
                                            <label for="practicename" >Practice Name</label>
                                            <!-- @selectGroupedPractices("practicesnew[]", ["id" => "practicesnew", "class" => "select2"]) -->
                                            <div class="wrapMulDrop">  
                                            <button type="button" id="editcandy"  class="multiDrop form-control col-md-12 editcandy">Select practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                    
                                            <?php if (isset($prac[0]->id) && $prac[0]!=''){?>
                                            <ul id="editul" style="height:80px;overflow-y:scroll">  
                                                 
                                                <?php foreach ($prac as $key => $value): ?> 
                                                <li  style="display:block">  
                                                    <label class="forms-element checkbox checkbox-outline-primary"> 
                                                        <input class="form-control" name ="practicesnew[<?php echo $value->id;?>]" id="practicesnew"  type="checkbox" value ="<?php echo $value->id;?>"><span class=""><?php echo $value->name?></span><span class="checkmark"></span>              
                                                    </label>
                                                </li>
                                                <?php endforeach ?> 
                                            </ul>
                                            <?php }else{?> 
                                            <?php }?>    
                                                    
                                           </div>
                                        </div> 
                                        <div class="col-md-3 form-group" id="practicebasedtiming_'+i+'" >
                                            <label for="practicebasedtiming" >Time spent</label>
                                            <input type="text" class="form-control" id="time_required" name="time_required[]" >
                                            <div class="invalid-feedback"></div>
                                           
                                        </div> 
                                        
                                        <div class="col-md-1" id="edit-pracdiv" style="margin-top:28px;display:none;">
                                            <i class="plus-icons i-Add" id="editpracticetime" name="editpracticetime"></i>
                                        </div> 
                                        <div class="col-md-1" id="editremove-pracdiv" style="margin-top:28px;"> 
                                            <i class="remove-icons i-Remove" id="editremovepracticetime" name="editremovepracticetime"></i>
                                        </div> 
 
                                    </div>
                                        
                                  </div> 

                                  <div  id="e1p1" style="display:none;">  
                                    <div class="row" id="p2">
                                        <div  class="col-md-4 form-group"> 
                                            <label for="practicegroup">{{config('global.practice_group')}}</label>  
                                            @selectgrppractices("editpracticesgrp[]", ["class" => "editappendpracticesgrp","id" => "editappendpracticesgrp"]) 
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="practicename" >Practice Name</label>
                                            <!-- @selectGroupedPractices("practicesnew[]", ["id" => "editappendpracticesnew", "class" => "editappendpracticesnew"]) -->
                                            <div class="wrapMulDrop">  
                                            <button type="button" id="editappendcandy"  class="multiDrop form-control col-md-12 editappendcandy">Select practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button>
                                    
                                            <?php if (isset($prac[0]->id) && $prac[0]!=''){?>
                                            <ul id="editappendul" style="height:80px;overflow-y:scroll">
                                                 
                                                <?php foreach ($prac as $key => $value): ?> 
                                                <li  style="display:block">  
                                                    <label class="forms-element checkbox checkbox-outline-primary"> 
                                                        <input class="form-control" name ="editappendpracticesnew[<?php echo $value->id;?>]" id="editappendpracticesnew"  type="checkbox" value ="<?php echo $value->id;?>"><span class=""><?php echo $value->name?></span><span class="checkmark"></span>              
                                                    </label>
                                                </li>
                                                <?php endforeach ?> 
                                            </ul>
                                            <?php }else{?> 
                                            <?php }?>    
                                                    
                                           </div>
                                        </div>
                                        <div class="col-md-3 form-group" id="practicebasedtiming_'+i+'" >
                                            <label for="practicebasedtiming" >Time spent</label>
                                            <input type="text" class="form-control" id="edittime_required" name="time_required[]" placeholder="hh:mm:ss">   
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-1" id="editremove-pracdivnew" style="margin-top:28px;">
                                            <i class="remove-icons i-Remove myicons" id="editremovepracticetimenew" name="editremovepracticetimenew" onclick="ppppp(this)"></i>
                                        </div> 
                                    </div>
                                    </div> 
                                  <div id="editincrementdiv"></div>
                                 
                                <hr id="line" style="border-top: 1px solid #2cb8ea; display:none">        
                                <div id="inc"></div>  
                        
                            </div>         
                            <div class="modal-footer">
                                <!-- <button type="submit" class="btn btn-primary float-right submit-active-deactive">Submit</button> -->
                                <button type="submit" class="btn btn-primary float-right" id="activitysubmit">Submit</button>
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal content end-->
</div>
<!--end edit modal-->

@endsection 

@section('page-js')
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    
     <script src='https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js'></script>		
    <script src='https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js'></script>
    <script src="{{asset('assets/js/vendor/dataTables.select.min.js')}}"></script>
    <script src="{{asset('assets/js/vendor/datatables/dataTables.editor.min.js')}}"></script>

<!-- <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>

<script src="{{asset('assets/js/vendor/dataTables.select.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/datatables/dataTables.editor.min.js')}}"></script>  -->
 
<script type="text/javascript">

var csrf = $("input[name='_token']").val();
		
        editor = new $.fn.dataTable.Editor( {
                ajax: { url: "{{ route('update.activity.sequences.inline')}}", 
                        type:'get',
                        success: function (response) {
                            getActivitylisting();
                            $("#success").show();
                            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Activity Sequence Saved Successfully!</strong></div>';
                            $("#success").html(txt);
                            var scrollPos = $(".main-content").offset().top;
                            $(window).scrollTop(scrollPos);
                            //goToNextStep("call_step_1_id");
                            setTimeout(function () {
                            $("#success").hide();
                            }, 3000);
                        },
                        error: function(response){
                            // console.log("error respponse");
                            // console.log(response);    
                            if($.trim(response.responseText)=='not numeric'){        
                                $("#success").show();
                                var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Activity Sequence is not a Numeric Value!</strong></div>';
                                $("#success").html(txt);
                                var scrollPos = $(".main-content").offset().top;
                                $(window).scrollTop(scrollPos);
                                //goToNextStep("call_step_1_id");
                                setTimeout(function () {
                                $("#success").hide();
                                }, 3000);
                            }
                        }
					},
                
                table: "#activity-list",
			//	idSrc:  0,
                fields: [
                    { 
                        label: "Sequence:",
                        name: "sequence"
                    }, 
                ],
                
				

            });
        $('#activity-list').on( 'click', 'tbody td:not(:first-child)', function (e) {
            // alert("hello");
            // console.log($(this));      
   		    editor.inline( this, {
					buttons: { label: '&gt;', fn: function () { this.submit(); } }
					//onBlur: 'submit'
				} );
            } );  
        

    var getActivitylisting =  function() {
        var columns = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'activity_type',name: 'activity_type'},
            {data: 'timer_type',
             mRender: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        if(data== 1){
                            return 'Manual';
                        } 
                        else if(data== 3){
                            return 'Backend';  
                        }
                        else {
                            return 'Standard';
                        }
                    } 
                },orderable: false 
            },
            {data:'default_time', name:'default_time'},
            {data: 'activity',name:'activity'},
            {data:'null',
             mRender: function(data, type, full, meta){
                //  console.log(data);
                //  console.log(full);
                    if(full!='' && full!='NULL' && full!=undefined){
                        l_name = full['l_name'];
                        if(full['l_name'] == null && full['f_name'] == null){
                            l_name = '';
                            return '';
                        } else {
                            return full['f_name'] + ' ' + l_name;
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

            {data: 'aupdated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'aupdated_at',"render":function (value) {
               if (value === null) return "";
                    return util.viewsDateFormatWithTime(value); 
                }
            },

                    {data: 'sequence',name:'sequence'},
             
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('activity-list', "{{ route('activity_list') }}", columns, "{{ asset('') }}");  
    };

    // $("#timer_type").change(function(){ 
    //   var type = $('option:selected', this).text() ; 
    //   if(type==Standard)
    //   {
    //       $("#append_parameter").show();
    //     $("#defaulttiming").show();  
    //   }
    //   else{
    //     $("#append_parameter").hide();
    //     $("#defaulttiming").hide(); 
    //   }
         
    // });

    var ppppp = function(val){
        var button_id = $(val).attr('id');
        var b =button_id.split("_");  
        $("#p2_"+b[1]).remove();  
    }
    var appenadddefaulttiming= function(i,val){
              
                if(val == '2'|| val =='3') {
                    $('#defaulttiming_'+i).show();
                    $('#newtask_'+i).show();
                    //$('$prac_'+i).show();
                }  
                else{
                    $('#defaulttiming_'+i).hide();
                    $('#newtask_'+i).hide(); 
                    $('#prac_'+i).hide();   
                }
            
           } 

    var removenewactivity= function(i){
       
        $("#newrow_"+i).remove();
        $("#line2_"+i).remove(); 

        return false;
        

    }  

    var k = 1;
    var newpracticetimeinappend= function(i)
    {
      
        $("#prac_"+i).append('<div class="controls" id="pracinappend_'+i+'_'+k+'">\
                <div class="row">\
                <div  class="col-md-4 form-group" id="">\<label for="practicegroup"><?php print $org; ?></label>@selectgrppractices("activity['+i+'][dynamicdropdown][]", ["class" => "newactivityappendpracticesgrp","id" => "newactivityappendpracticesgrp_'+k+'"])\</div>\
                <div class="col-md-4">\
                    <label for="practicename">Practice Name</label>\
                    <div class="wrapMulDrop"><button type="button" id="appendnewcandy_'+i+'_'+k+'" class="multiDrop form-control col-md-12">Select practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button><?php if (isset($prac[0]->id) ){?><ul id="appendutilulpract_'+i+'_'+k+'" style="height:80px;overflow-y:scroll"><?php foreach ($prac as $key => $value): ?><li><label class="forms-element checkbox checkbox-outline-primary"><input class="" name ="activity['+i+'][practices]['+i+'][<?php echo $value->id;?>]" id ="practices" type="checkbox" value ="<?php echo $value->id;?>"><span class=""><?php echo $value->name?></span><span class="checkmark"></span></label></li><?php endforeach ?></ul><?php }else{?><?php }?>\</div>\
                </div>\
                <div class="col-md-3 form-group">\
                    <label for="practicebasedtiming">Time spent</label>\
                        <input type="text" class="form-control" id="newactivitypracticebasedtime" name="activity['+i+'][newactivitypracticebasedtime][]" placeholder="hh:mm:ss">\
                        <div class="invalid-feedback"></div>\
                    </div>\
                <div class="col-md-1" style="margin-top:28px;">\
                    <i class="remove-icons i-Remove" id="removepracactivity_'+i+'_'+k+'" onclick="removepracactivityinappend('+i+','+k+')" name="removepracactivity"></i>\
                </div>\
                </div>\
                </div>');  
                $("#newactivityappendpracticesgrp_"+k).on("change", function () { 
                var m = k-1;    
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){  
                }
                if(practicegrp_id!=''){
                    // util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$(".newactivityappendpractices_"+m));
                    util.getappendPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#appendutilulpract_"+i+"_"+m),i,m);       
                }
                else{     
                }     
                }); 

                $('#appendnewcandy_'+i+'_'+k).on('click', function (event) { 
                                // alert("candy");
                                event.stopPropagation();  
                                $(this).next('ul').slideToggle();
                                });

                                $(document).on('click', function () {
                                if (!$(event.target).closest('.wrapMulDrop').length) {
                                    $('.wrapMulDrop ul').slideUp();
                                }
                                });

                                $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
                               
                                    // var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
                                    var x = $('#appendutilulpract_'+i+'_'+(k-1)+' li input[type="checkbox"]:checked').length;
                                    if (x != "") {
                                        $('#appendnewcandy_'+i+'_'+(k-1)).html(x + "Practices" + " " + "selected");
                                    } else if (x < 1) {
                                        $('#appendnewcandy_'+i+'_'+(k-1)).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
                                    }
                                });

                k++; 
        // return false;           
    }  

    var removepracactivityinappend=  function(i,k)//appendform
    {
        $("#pracinappend_"+i+"_"+k).remove();    
    } 

    var minusprac= function(j)//mainform
    {
        $("#minuspracdiv_"+j).remove();
    }

    
    

    $(document).ready(function() { 
        getActivitylisting();
        activity.init(); 
        util.getToDoListData(0, {{getPageModuleName()}});

      

        $('#candy_0_0').on('click', function (event) {
            // alert("1");
        event.stopPropagation();  
        $(this).next('ul').slideToggle();
        });

      

        $(document).on('click', function () { 
            if (!$(event.target).closest('#utilulpract_0_0').length) {
                $('.wrapMulDrop ul').slideUp();
            }
        });

        $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
          
            // var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
            var x = $('#utilulpract_0_0 li input[type="checkbox"]:checked').length; 
               
            if (x != "") {
                $('#candy_0_0').html(x + " Practices" + " " + "selected");
            } else if (x < 1) {
                $('#candy_0_0').html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
            }
        });    

//---------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------        
      
        
      
        
        var i =1; var k=1;
        $("#add-newactivity").click( function(e) {
            // var p,q;
            var k = i-1;
            // var p = i-1;
            // var q = k-1;
              e.preventDefault(); 
              $("#line").show(); 
              $("#inc").append('<div class="controls" id="newrow_'+i+'">\
                                    <div class="row">\
                                    <div class="col-md-4 form-group" id="newactivity_'+i+'"><label for="activity" class="forms-element">Activity</label>\
                                    <input type="text" class="form-control" name="activity['+i+'][activity]" id="activity_'+i+'">\
                                    <div class="invalid-feedback"></div>\</div>\
                                    <div class="col-md-4 form-group" style="margin-top:27px;">\
                                        <select id="activitydropdown_'+i+'" name="activity['+i+'][activitydropdown]" onchange="appenadddefaulttiming('+i+',this.value)" class="custom-select show-tick" >\
                                                <option value="1" selected>Manual</option> \
                                                <option value="2">Standard</option>\
                                                <option value="3">Backend</option>\
                                        </select> \
                                    </div>\
                                    <div class="col-md-3 form-group" id="defaulttiming_'+i+'" style="display:none;">\
                                        <label for="defaulttiming" class="forms-element">Default</label>\
                                        <input type="text" class="form-control" id="appenddefaulttime_'+i+'" name="activity['+i+'][defaulttime]" placeholder="hh:mm:ss">\
                                        <div class="invalid-feedback"></div>\
                                    </div>\
                                    <div class="col-md-1" style="margin-top:28px;">\
                                       <i class="remove-icons i-Remove" id="remove-newactivity_'+i+'" onclick="removenewactivity('+i+')" name="remove-newactivity"></i>\
                                    </div>\
                                    </div>\
                                  <div class="row" id="newtask_'+i+'" style="display:none">\<div  class="col-md-4 form-group" id="">\<label for="practicegroup"><?php print $org; ?></label>@selectgrppractices("activity['+i+'][dynamicdropdown][]", ["class" => "editpracticesgrp","id" => "newactivitypracticesgrp_'+i+'"])\</div>\
                                    <div class="col-md-4" id="practice_'+i+'" >\
                                        <label for="practicename">Practice Name </label>\
                                        <div class="wrapMulDrop"><button type="button" id="candy_'+i+'_'+k+'" class="multiDrop form-control col-md-12">Select practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button><?php if (isset($prac[0]->id) ){?><ul id="utilulpract_'+i+'_'+k+'"  style="height:80px;overflow-y:scroll"><?php foreach ($prac as $key => $value): ?><li><label class="forms-element checkbox checkbox-outline-primary"><input class="newactivitypractices_'+i+'" name ="activity['+i+'][practices]['+i+'][<?php echo $value->id;?>]" id ="practices" type="checkbox" value ="<?php echo $value->id;?>"><span class=""><?php echo $value->name?></span><span class="checkmark"></span></label></li><?php endforeach ?></ul><?php }else{?><?php }?>\</div>\
                                    </div>\
                                    <div class="col-md-3 form-group" id="practicebasedtiming_'+i+'" >\
                                      <label for="practicebasedtiming">Time spent</label>\
                                        <input type="text" class="form-control" id="appendpracticebasedtime_'+i+'" name="activity['+i+'][newactivitypracticebasedtime][]" placeholder="hh:mm:ss">\
                                        <div class="invalid-feedback"></div>\
                                        </div>\
                                    <div class="col-md-1" style="margin-top:28px">\
                                            <i class="plus-icons i-Add" id="appendnewpracticetime_'+i+'" name="appendpracticetime" onclick="newpracticetimeinappend('+i+')"></i>\
                                    </div>\
                                  </div>\
                                  <div class="row">\
                                    <div class="col-md-12" id="prac_'+i+'"></div>\
                                  </div>\
                                  </div>\
                                  <hr id="line2_'+i+'"" style="border-top: 1px solid #2cb8ea;">\
                                    </div>');   
                                    $("#newactivitypracticesgrp_"+i).on("change",function(){
                                    var k = i-1;
                                
                                    var practicegrpid = $(this).val();     
                                    if(practicegrpid==0){ 
                                    
                                    }
                                    if(practicegrpid!=''){  
                                        // util.getPracticelistaccordingtopracticegrp(parseInt(practicegrpid), $(".newactivitypractices_"+k)); 
                                        util.getnewactivityPracticelistaccordingtopracticegrp(parseInt(practicegrpid),$("#utilulpract_"+i+"_"+k),i,k);            
                                    }
                                    else{     
                                        
                                    } 
                                });  

                                $('#candy_'+i+'_'+k).on('click', function (event) { 
                                alert("candy");
                                event.stopPropagation();  
                                $(this).next('ul').slideToggle();
                                });

                                $(document).on('click', function () {
                                if (!$(event.target).closest('.wrapMulDrop').length) {
                                    $('.wrapMulDrop ul').slideUp();
                                }
                                });

                                $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
                                    // alert("2");
                                    // var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;

                                    
                                    var x = $('#utilulpract_'+(i-1)+'_'+k+' li input[type="checkbox"]:checked').length;
                                       
                                    if (x != "") {
                                        $('#candy_'+(i-1)+'_'+k).html(x + " Practices" + " " + "selected");  
                                       
                                       
                                    } else if (x < 1) {
                                        $('#candy_'+i+'_'+k).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
                                    }
                                });  

                    i++;
                    // k++; 
                
            return false;  
            });

            
            // var dynamicpracticegroupname = config('global.practice_group');
            
            var j=1; 
            $("#add-newpracticetime").click(function(e){       
                // alert(dynamicpracticegroupname);  
               e.preventDefault(); 
               $("#practicetimeincrement").append('<div class="controls" id="minuspracdiv_'+j+'"><div class="row"><div class="col-md-4"><label for="practicename"><?php print $org; ?></label>@selectgrppractices("activity[0][dynamicdropdown][]", ["class" => "select2","id" => "dynamicdropdown_'+j+'"])</div>\<div class="col-md-4" id="pr1div"><label for="practicename">Practice Name</label>\<div class="wrapMulDrop"><button type="button" id="appendnewcandy_0_'+j+'" class="multiDrop form-control col-md-12">Select practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button><?php if (isset($prac[0]->id) ){?><ul id="appendutilulpract_0_'+j+'" style="height:80px; overflow-y:scroll"><?php foreach ($prac as $key => $value): ?><li><label class="forms-element checkbox checkbox-outline-primary"><input class = "form-control onepract_'+j+'" name ="activity['+j+'][practices][0][<?php echo $value->id;?>]" id="practices" type="checkbox" value ="<?php echo $value->id;?>"><span class=""><?php echo $value->name?></span><span class="checkmark"></span></label></li><?php endforeach ?></ul><?php }else{?><?php }?></div>\
               </div>\
               <div class="col-md-3 form-group" ><label for="practicebasedtiming" class="forms-element">Time spent</label><input type="text" class="form-control" id="newactivitypracticebasedtime_'+j+'" name="activity[0][newactivitypracticebasedtime][]" placeholder="hh:mm:ss">\
               <div class="invalid-feedback"></div>\</div>\
               <div class="col-md-1" style="margin-top:28px;">\<i class="remove-icons i-Remove" id="minuspracactivity_'+j+'" onclick="minusprac('+j+')"  name="removepracactivity1">\</i>\</div>\
               </div>\</div>'); 
               $("#dynamicdropdown_"+j).on("change",function(){
                var k = j-1; 
               
                var practicegrpid = $(this).val();   
                if(practicegrpid==0){ 
                    
                }
                if(practicegrpid!=''){  
                    // util.getPracticelistaccordingtopracticegrp(parseInt(practicegrpid), $(".onepract_"+k));
                    // util.getnewactivityPracticelistaccordingtopracticegrp(parseInt(practicegrpid),($("#utilulpract_"+0+'_'+k)),0,k); 
                    util.getappendPracticelistaccordingtopracticegrp(parseInt(practicegrpid),($("#appendutilulpract_"+0+'_'+k)),0,k) ;          
                }
                else{     
                     
                } 
               });  
                
                // alert(j);
                $('#appendnewcandy_0_'+j).on('click', function (event) { 
                // alert(k);
                event.stopPropagation();  
                $(this).next('ul').slideToggle();
                });

                $(document).on('click', function () {
                if (!$(event.target).closest('.wrapMulDrop').length) {
                    $('.wrapMulDrop ul').slideUp();
                }
                });

                $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () { 
                    
                    // var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;  
                    var x = $('#appendutilulpract_0_'+(j-1)+' li input[type="checkbox"]:checked').length; 
                    
                    if (x != "") {                       
                        $('#appendnewcandy_0_'+(j-1)).html(x + " Practices" + " " + "selected");
                    } else if (x < 1) {
                        $('#appendnewcandy_0_'+(j-1)).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
                    }
                });
 
                j++;
              
           });    
               
            

             $('#activitydropdown').on('change', function() {
                if(this.value == '2' || this.value == '3') {
                    $("#newtask").show();
                    $('#defaulttiming').show();
                    $("#practicetimeincrement").show();
                    
                }
                else{
                    $("#newtask").hide();
                    $('#defaulttiming').hide(); 
                    $("#practicetimeincrement").hide();   
                }
            });

    
    

         
    }); //document close

    $('#addActivity').click(function() {
        $("#line").hide(); 
        $("#defaulttimeincrement").empty(); 
        $("#inc").empty(); 
        $("#practicetimeincrement").empty(); 
        $("#add_activity_form")[0].reset();
        $("#activitydropdown").val("1").trigger('change');
        $("#practices").val("").trigger('change'); 
        $('#add-activities').modal('show');    
    });

   
    
    $("#practicesgrp").on("change", function () {
        // alert("hi");   
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){  
                }
                if(practicegrp_id!=''){
                    
                    util.getnewactivityPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#utilpract_0_0"),0,0);   
                  

                }
                else{   
                }     
            });

  
     
        
      
   

</script> 
@endsection    