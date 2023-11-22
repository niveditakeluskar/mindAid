<?php 
   $toDoList = $to_do_arr; //getToDoList();
?>
@if(!empty($toDoList))
   @foreach($toDoList as $toDoListItem)
      <?php 
            $task_notes = $toDoListItem['task_date'];
            if(isset($task_notes) && $task_notes != "") {
               $task_notes = " - ". Carbon\Carbon::parse($task_notes)->format('d-m-Y');
            }
            if($toDoListItem['module_id'] == '3' && $toDoListItem['component_id'] == '19'){
               $url = "/ccm/monthly-monitoring/".$toDoListItem['patient_id'];
            } else {
               $url = "#";
            }
      ?>
      <a href="{{ $url }}" class="list-group-item list-group-item-action"><b>{{ $toDoListItem['fname'].' '.$toDoListItem['lname'] }}</b>- {{ $toDoListItem['task_notes'].$task_notes}}</a>
   @endforeach
@else 
<a href="#" class="list-group-item list-group-item-action">To do list is empty.</a>
@endif