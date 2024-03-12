<style>
.tableFixHead   { overflow-y: auto; height: 400px; } 
.tableFixHead thead th { position: sticky; top: 0; }
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }  
th     { background:#eee; }
</style>
<div class="table-responsive mt-4 tableFixHead"  >
    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029; ">
        <thead>
            <tr class="tablehead">
                <th>Sr</th>
                <th>Topic</th>
                <th>CareManager Notes</th>
            </tr> 
        </thead>
            <tbody>


            <?php 
            
            $k =0;
            //if(!empty($prev_topics) || isset($prev_topics) || $prev_topics!=''){ $i=1; 
                if(sizeof($prev_topics)==0){ ?>
                <tr><td></td>
                    <td style='text-align:center'>No Data Found</td>
                    <td></td>
                </tr>
            <?php }else{$i=1;
                foreach($prev_topics as $key => $value){?>
                <tr>
                <td><?php  echo $i++; $k=$i;?></td>
                <td><?php  echo $value->topic;?></td>
                <td><?php  echo $value->notes;?></td>
                </tr> 
            <?php } }?>
        
                  
        
     


                


         

           





        </tbody>
    </table>
   
</div>
<br>
<br>
<br>  

<script type="text/javascript">

</script>

<!-- </div> -->
<!-- ============ End Customizer =============]]
