
<style>
.tableFixHead   { overflow-y: auto; height: 400px; } 
.tableFixHead thead th { position: sticky; top: 0; }
table  { border-collapse: collapse; width: 100%; } 
th, td { padding: 8px 16px; }
th     { background:#eee; }
</style>
<div class="col-lg-12 mb-1">
    <label class=" "><strong>Patient Condition:</strong></label>
    <?php if(!empty($Condition)|| isset($Condition)|| $Condition!=''){   $i=0;
                foreach($Condition as $ckey => $cvalue){    if($i!=0){echo ",  ";}?>
     <span><?php echo $cvalue->conditionName; ?></span>
                <?php   $i++; }} ?>
</div>

<div class="table-responsive mt-4 tableFixHead"> 
    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
        <thead>
            <tr>
                <th>Sr</th>
                <th style="width: 94px;">Date</th> 
                <th>General Notes</th> 
                <!--th>CareManager Notes</th-->
            </tr>
        </thead>
            <tbody>
            <?php  //if(!empty($caretool)|| isset($caretool)|| $caretool!=''){ $i=1;
                 if(sizeof($caretool)>0){$i=1;
                foreach($caretool as $key => $value){?>
                <tr>
                <td><?php  echo $i++;?></td>
                <td><?php echo date("m-d-Y", strtotime($value->monthyear));?></td>
                <td><?php  echo $value->generalnotes;?></td>
                
                </tr>
            <?php }?> 
                  
            <?php } else {?>
                <tr><td></td>
                <td style='text-align:center'>No Data Found</td>
                <td></td>
                </tr>
            <?php }?>

        </tbody>
    </table>
</div> 
<br>
<br>
<?php// print_r($Condition); ?>
