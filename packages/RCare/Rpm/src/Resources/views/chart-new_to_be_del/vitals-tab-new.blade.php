<ul class="nav nav-tabs" id="patientvitalstab" role="tablist">
<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 $actual_link2 = "$_SERVER[REQUEST_URI]"; 
//      echo  $actual_link2 ; 
$patientid =  $patient[0]->id;
    $a = explode("/",$actual_link);
    // $vital =  $a[6] ;
    // if($vital=='observationsbp' || $vital=='observationsheartrate' ){
    //     $deviceid = 2;
    // }else if($vital=='observationsoxymeter'){
    //     $deviceid = 1;
    // }
    // else {
    //     $deviceid = 0;
    // } 
    ?> 
    <?php 
    // $select = "select * from rpm_core.devices order by device_name";
    // $sel = mysql_query($select);
    // //ksort($devices);
    // echo($sel);
    //echo($devices[0]->device_name);
    for($i=0;$i<count($devices);$i++){
        if($i =="0"){
            $active="active";
        } 
        else
        {
            $active="";
        }
    ?>
    <li class="nav-item">
        <a class="nav-link {{$active}} tabclass" id="device-icon-tab_{{$devices[$i]->id}}" data-toggle="tab" href="#deviceid_{{$devices[$i]->id}}" role="tab"  aria-selected="false" onclick="getUrl(this);"><i class="nav-icon color-icon i-Control-2 mr-1"></i><?php echo $devices[$i]->device_name;?></a>

<!-- onclick="getUrl(this); return false;" -->
      <!--   <a class="nav-link {{$active}} tabclass" id="dev_id" data-toggle="tab" href="#deviceid_{{$devices[$i]->id}}" role="tab" aria-controls="ccm-call" aria-selected="false"><i class="nav-icon color-icon i-Control-2 mr-1"></i><?php echo $devices[$i]->device_name;?></a> -->
    </li>
    <!-- <input type="hidden" name="dev_id" id="dev_id" value=" {{$devices[$i]->id}}"> -->
    <?php } ?>                             
</ul>
<div class="tab-content" id="myIconTabContent">
  
<?php  
                                if(!empty($devices)){
                                for($i=0;$i<count($devices);$i++){
                                    //*active-tab basis of device-id from url
                                       
                                    $devicename=str_replace(' ', '-', $devices[$i]->device_name);
                                    $path="Rpm::chart-new.newchartfile_2";
                                    ?>
                                
                                <div id="success_{{$devices[$i]->id}}"></div>
                                   @include($path)
                                </div>
                               <?php }} ?>

</div>  


<script type="text/javascript">

function getUrl(e) {
  //$('#dev_id').click(function(e) {
   var href = e.getAttribute("href");
   var a = href.split("_");
   var link = "<?php echo $actual_link2; ?>";
   var p = "<?php echo $patientid; ?>";
   //var dev = $('#dev_id').val();
   //alert(dev);
   var a1 = a[1];

   alert(link+'/'+a1);
   var url ='/rpm/chart-new/updatediv/'+p+'/'+a1;

   $.ajax({
            type: 'get',
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url:url,
            success:function(data){
                alert("success");
            }
   });
}



// $(document).ready(function() {
//     $('.nav-item').click(function(e) {
//         // var href1 = e.getAttribute("href");
//         // var a1 = href1.split("_");
//         // var a11 = a1[1];
//         var p1 = "<?php echo $patientid; ?>";
//         // alert("In function");

//         $.ajax({
//             type: 'GET',
//             headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//             url: '/rpm/updatediv',
//             data: {
//                 'p1' : p1
                
//             },
//             success: function(data) {
//                 alert("Hi");
//             }
//         });
//     });
// });
</script>     
<!-- <script src="{{ asset('assets/js/timer.js') }}"></script> 
<script src="{{URL::asset('asset/js/bootstrap.min.js')}}"></script>        -->
