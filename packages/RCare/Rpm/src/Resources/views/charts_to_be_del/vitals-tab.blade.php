
<ul class="nav nav-tabs" id="patientvitalstab" role="tablist">
<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
    // echo  $actual_link ; 
    // $a = explode("/",$actual_link);
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
    <?php for($i=0;$i<count($devices);$i++){
        if($i =="0"){
            $active="active";
        } 
        else
        {
            $active="";
        }
    ?>
    <li class="nav-item">
        <a class="nav-link {{$active}} tabclass" id="device-icon-tab_{{$devices[$i]->id}}" data-toggle="tab" href="#deviceid_{{$devices[$i]->id}}" role="tab" aria-controls="ccm-call" aria-selected="false"><i class="nav-icon color-icon i-Control-2 mr-1"></i><?php echo $devices[$i]->device_name;?></a>
    </li>
    <?php } ?>                             
</ul>
<div class="tab-content" id="myIconTabContent">
    

</div>  
                      