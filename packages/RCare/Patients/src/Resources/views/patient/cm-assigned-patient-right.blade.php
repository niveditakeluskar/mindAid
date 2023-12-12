<style>
.tableFixHead   { overflow-y: auto; height: 400px; } 
.tableFixHead thead th { position: sticky; top: 0; }
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; } 
th     { background:#eee; }
</style>
<div class="row">
    <div class="col-lg-12 mb-3">
        <!-- table -->
            <div class="col-md-12">
                <div class="table-responsive">
                  <table id="task-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029; ">
                    <thead>
                        <tr>
                            <th>Sr No.</th>                        
                            <th>Practice</th> 
                            <th>Patient</th>
                            <th>DOB</th>
                        </tr> 
                    </thead>
                    <tbody>
                        <?php //if(!empty($prev_topics) || isset($prev_topics) || $prev_topics!=''){ $i=1; 
                        if($query==0){ ?>
                        <tr><td></td>
                            <td style='text-align:center'> Not Assigned any patient!!!</td>
                            <td></td>
                        </tr>
                    <?php }else{$i=1;
                        foreach($query as $key => $value){?>
                        <tr>
                        <?php
                         isset($value->userfname)?$userfname = $value->userfname:$userfname='';
                         isset($value->userlname)?$userlname = $value->userlname:$userlname='';
                         ?>

                        <td><?php  echo $i++;?></td>
                        <td><?php  echo $value->practice;?></td>
                        <td><?php  echo $value->patient;?></td>
                        <td><?php  $dob= $value->dob;
                                if($dob=='null' ||$dob==null){
                                    echo "";
                                }else{
                                    echo date('m-d-Y',strtotime($value->tt));
                                }?>
                        </td> 
                        <!-- <td><?php //echo $userfname.' '.$userlname;?></td> -->
                        </tr> 
                    <?php } }?>
                    </tbody>
                  </table>
                </div> 
            </div>
        <!-- end table -->
        <input type="hidden" id="count_patient" value="{{$i-1}}">
    </div> 
</div>
<script type="text/javascript">

       

</script>