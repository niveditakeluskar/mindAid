<!DOCTYPE html>
<html>
<head>
<style type="text/css">

body{
    font-family:verdana;    
}
table, th {
 /* border: 0px solid black; */
 text-align:justify ;
 /*text-align:left;*/
 /*padding-left:3px;*/ 
}
table,td{
vertical-align: middle;
 
text-align:justify;
}

/*table, tr{ 
    margin-right: 10px; 
}
*/
//* {margin: 0; padding: 0;}

.header
{
  margin-left: -25px;
}

#cpecialisttbl {
  /*font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;*/
  border-collapse: collapse;
  width: 100%;
}

#cpecialisttbl td, #cpecialisttbl th {
  border: 1px solid #ddd;
   text-align: center;
  /*padding: 8px;*/
}

#cpecialisttbl tr:nth-child(even){background-color: #f2f2f2;}

#cpecialisttbl tr:hover {background-color: #ddd;}

#cpecialisttbl th {
 padding-top:2px;
  padding-bottom: 2px;
  text-align: center;
  background-color: #1474a0;
  color: white;

}
b{
    color:#000033;

}
h4
{
   background-color:#cdebf9;
   color:#000033;
   padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 7px;
    /*width: 330px;*/
}
h3
{
   padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 7px;
    color:white;
}
#cpid
{
    float: right;
    color: white;
    font-size: 36px;
    font-family: -webkit-pictograph;   
    margin-top: -11px;
    margin-right: 10px;
}
</style>
    <title>CARE PLAN</title>

</head>
<body>

<div class="" style="margin: -45px -45px -0px -45px">       
    <div class="card-body"> 
        <div class="form-row" style="background-color:#27a8de;height: 100px;">
                 <div class="form-row" margin-top="20px">
                    <img src="http://rcareproto2.d-insights.global/assets/images/logo.png" alt="" height="40px" width="150px" style="padding-top: 30px;padding-left: 17px;">
                 </div>
                <label id="cpid">Care Plan Template</label>
             </div>
              <div class="form-row" style="background-color:#1474a0;height: 10px;">
              </div>

   
    </div>
</div> 

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <!--  <h3 class="card-title mb-3" id="ID">Care Plan</h3> -->
                    <div style="background-color: #1474a0;color: white;margin-top:10px;">
                             <h3>{{empty($diagnosis_data->condition) ? '' : $diagnosis_data->condition}}</h3>                    
                     </div>                             
                      <h4>Diagnosis Codes:</h4> 
                      {{empty($diagnosis_data->condition) ? '' : $diagnosis_data->condition}} ({{empty($diagnosis_data->code) ? '' : $diagnosis_data->code}})                    
                   <br>
                    <hr>
                  
                    <div class="row">
                        <label class="col-md-2" id="ID5"><h4>Treatment Goals:</h4></label>
                        <div class="col-md-10" id="ID6">
                           <?php  
                              if(!empty($diagnosis_data->goals))
                              {
                           $data = json_decode($diagnosis_data->goals, true); ?> 
                           <ol>  
                           @foreach ($data as $goal)                                                       
                              <li>{{$goal}}</li>                            
                                 @endforeach   
                               </ol>
                             <?php } ?>
                         
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID3"><h4>Symptoms:</h4></label>
                        <div class="col-md-10" id="ID4">
                              <?php  
                               if(!empty($diagnosis_data->symptoms)){
                              $datasym = json_decode($diagnosis_data->symptoms, true);?> 
                           <ol>  
                           @foreach ($datasym as $sym)
                            <li>{{$sym}}</li>
                                 @endforeach   
                               </ol>
                             <?php } ?>
                        </div>
                    </div>
                  
                   
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID9"><h4>Tasks:</h4></label>
                        <div class="col-md-10" id="ID10">
                            <?php  
                            if(!empty($diagnosis_data->tasks)){
                            $datatask = json_decode($diagnosis_data->tasks, true);?> 
                           <ol>  
                           @foreach ($datatask as $task)
                           <li>{{$task}}</li>
                                 @endforeach   
                               </ol>
                             <?php } ?>
                        </div>
                    </div>
                    
                   <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID7"><h4>Medication:</h4></label>
                        <div class="col-md-10" id="ID8">                        
                           
                           <?php if(!empty($medications)){ ?>
                           <ol>                              
                           @foreach ($medications as $med)
                            <li>{{$med->description}}</li>
                                 @endforeach   
                               </ol>
                             <?php } ?>
                        </div>
                    </div> 
                   
                    <div class="row">
                        <label class="col-md-2" id="ID11"><h4>Allergies:</h4></label>
                        <div class="col-md-10"id="ID12">
                           <?php  $dataallergy = json_decode($diagnosis_data->allergies, true);?> 
                           <ol>  
                            <?php if(!empty($dataallergy)){ ?>
                           @foreach ($dataallergy as $allergy)
                           <li>{{$allergy}}</li>
                                 @endforeach   
                               <?php } ?>
                               </ol>
                            <label id="Allergies"></label>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID13"><h4>Supports:</h4></label>
                        <div class="col-md-10" id="ID14" style="margin-left: 20px"> 
                          {{empty($diagnosis_data->support) ? '' : $diagnosis_data->support}}                            
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
    <br>
<hr>
<br>
   
<div style="float: right;">
    Electronic Care Manager Signature
    </div>
    <br>
    <br>
    <br>
    <br><br><br><br>
    
   <div style="margin: -45px -45px -0px -45px">
 <div class="form-row" style="background-color:#1474a0;height: 10px;">
              </div>
     <div class="form-row" style="background-color:#27a8de;height: 80px;"> 
     </div>               
             </div>
</body>
</html>
