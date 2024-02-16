<style>
.tableFixHead   { overflow-y: auto; height: 400px; } 
.tableFixHead thead th { position: sticky; top: 0; }
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; } 
th     { background:#eee; }
</style>
<?php if($patient_id!=0){?>
<div class="row">
    <div class="col-lg-12 mb-3">
        <!-- table -->
            <div class="col-md-12">
                <div class="table-responsive">
                  <table id="task-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029; ">
                    <thead>
                        <tr>
                            <th>Sr No.</th>                        
                            <th>Task</th> 
                            <th>Date Scheduled</th>
                            <th>Task Time</th>
                            <th>Assigned By</th> 
                        </tr> 
                    </thead>
                    <tbody>
                        <?php //if(!empty($prev_topics) || isset($prev_topics) || $prev_topics!=''){ $i=1; 
                        if($query==0){ ?>
                        <tr><td></td>
                            <td style='text-align:center'>To do List is Empty!!!</td>
                            <td></td>
                        </tr>
                    <?php }else{$i=1;
                        foreach($query as $key => $value){?>
                        <tr>
                        <td><?php  echo $i++;?></td>
                        <td><?php  echo $value->task_notes;?></td>
                        <td><?php  $scheduled_date= $value->tt;
                                if($scheduled_date=='null' ||$scheduled_date==null){
                                    echo "";
                                }else{
                                    echo date('m-d-Y',strtotime($value->tt));
                                }?>
                        </td>
                        <td><?php  echo $value->task_time;?></td> 
                        <?php
                         isset($value->userfname)?$userfname = $value->userfname:$userfname='';
                         isset($value->userlname)?$userlname = $value->userlname:$userlname='';
                         ?>
                        <td><?php echo $userfname.' '.$userlname;?></td>
                        </tr> 
                    <?php } }?>
                    </tbody>
                  </table>
                </div> 
            </div>
        <!-- end table -->
        <input type="hidden" id="count_todo" value="{{$i-1}}">
    </div> 
</div>
<?php }else{?>
<?php //if(isset($patient_id) && $patient_id==0){?>
<div class="row">
    <div class="col-lg-12 mb-3">
        <!-- table --> 
            <div class="col-md-12">
                <div class="table-responsive">
                  <table id="task-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029; ">
                    <thead>
                        <tr>
                            <th>Sr No.</th>  
                            <th>Patient Name</th>                      
                            <th>Task</th>     
                            <th>Date Scheduled</th>
                            <th>Task Time</th>
                            <th>Assigned By</th> 
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php //if(!empty($prev_topics) || isset($prev_topics) || $prev_topics!=''){ $i=1; 
                        if($query==0){ ?>
                        <tr><td></td>
                            <td style='text-align:center'>To do List is Empty!!!</td>
                            <td></td>
                        </tr>
                    <?php }else{
                        $i=1; $url='';
                        foreach($query as $key => $value){?>
                        <tr>
                        <td><?php  echo $i++;?></td> 
                        <?php
                            //dd($value);
                             $module_name     = strtolower(str_replace(' ', '-', $value->module));
                             $components_name = strtolower(str_replace(' ', '-', $value->components));
                             $enrolled_service_id = strtolower(str_replace(' ', '-', $value->enrolled_service_id));
                             $patient_id = $value->patient_id;
                             if($components_name =='patient-enrollment'){
                                $url = "/".$module_name."/".$components_name."/".$patient_id."/".$enrolled_service_id;
                             }else{
                                $url = "/".$module_name."/".$components_name."/".$patient_id;
                             } 
                        ?> 
                        <td><a href="{{ $url }}" ><?php  echo $value->fname.' '.$value->lname;?></a></td>
                        <td><?php  echo $value->task_notes;?></td>
                        <td><?php  $scheduled_date= $value->tt;
                                if($scheduled_date=='null' ||$scheduled_date==null){
                                    echo "";
                                }else{
                                    echo date('m-d-Y',strtotime($value->tt)); 
                                }?>
                        </td>
                        <td><?php  echo $value->task_time;?></td>
                        <!-- <?php //dd($value);?> -->
                        <?php
                         isset($value->userfname)?$userfname = $value->userfname:$userfname='';
                         isset($value->userlname)?$userlname = $value->userlname:$userlname='';
                         ?>
                        <td><?php echo $userfname.' '.$userlname;?></td>
                        </tr> 
                    <?php } }?>
                    </tbody>
                  </table>
                </div>  
            </div>
        <!-- end table -->
        <input type="hidden" id="count_todo" value="{{$i-1}}">
    </div> 
</div>
<?php }?>

<script type="text/javascript">

       

</script>