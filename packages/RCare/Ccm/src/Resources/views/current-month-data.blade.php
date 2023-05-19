<div class="table-responsive mt-4">
    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
        <thead>
            <tr>
                <th>Sr</th>
                <th>Topic</th> 
                <th>CareManager Notes</th>
            </tr>
        </thead>
            <tbody>
            <?php 
                if(sizeof($curr_topics)==0){?> 
            <tr>
                    <td>1</td>
                    <td>Health-related follow-up from last month</td>
                    <td></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>personal-realted follow-up from last month</td>
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>New Hospitalization/ER visit/Urgent Care</td>
                    <td></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>New Office Visits(any doctor)</td>
                    <td></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>New diagnosis</td>
                    <td></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Medication Changes</td>
                    <td></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>New Labs or Diagnostic Imaging</td>
                    <td></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>New/Changes to DME</td>
                    <td></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Patient Relationship Building</td>
                    <td></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>Last Month’s Review</td>
                    <td></td>
                </tr>
            <?php }else{ $i=1;
                foreach($curr_topics as $key => $value){?>
                <tr>
                <td><?php  echo $i++;?></td>
                <td><?php  echo $value->topic;?></td>
                <td><?php  echo $value->notes;?></td>
                </tr>
            <?php } }?>
        </tbody>
    </table>
</div>