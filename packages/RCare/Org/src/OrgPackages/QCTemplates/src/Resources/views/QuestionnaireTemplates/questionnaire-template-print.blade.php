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
.label{
    margin-top: -14px;
}
/*Now the CSS Created by R.S*/
//* {margin: 0; padding: 0;}

</style>
    <title>Questionnaire Template</title>
</head>
<body>
<div class="card">
   <div class="card-body" style="margin: -45px -45px -0px -45px"> 
        <div class="form-row" style="background-color:#27a8de;height: 100px;">
             <div class="form-row" margin-top="20px">
                <img src="http://rcareproto2.d-insights.global/assets/images/logo.png" alt="" height="40px" width="150px" style="padding-top: 30px;padding-left: 17px;">
             </div>
            <label id="cpid">Questionnaire Template</label>
        </div>
        <div class="form-row" style="background-color:#1474a0;height: 10px;"></div>
    </div>
        <div class="form-row" style="padding-top:20px;">
            <table width=100%>
                <tr>
                    <td><b>Content Name:</b> {{$data->content_title }}</td>
                    <td><b>Content Type:</b> {{ $type[0]->template_type }}</td>
                </tr>
                <tr>
                    <td><b>Modules:</b> {{$module[0]->module }}</td>
                    <td><b>Step:</b> @if(isset($stageCode[0]->description) && $stageCode[0]->description != "") {{ $stageCode[0]->description }} @endif</td>
                </tr>
                <tr>
                    <td><b>Sub Modules:</b> {{ $components[0]->components }}</td>
                    <td><b>Stage:</b> @if(isset($stage[0]->description) && $stage[0]->description != "") {{ $stage[0]->description }} @endif</td>
                </tr>
            </table>
            <hr>
            <div class="separator-breadcrumb border-top"></div>
                <?php
                    $number = 1;
                    $queData = json_decode($data['question']);
                    ?>
                    @if($number==1)@endif
                    <?php
                    $questionnaire = $queData->question->q;
                    ?>
                    @foreach($questionnaire as $value)
                    <div class="question_div" id="question_div{{$number}}">
                        <div class="form-group" id="question">
                            <div class="row">
                                <label class="col-md-2"><strong class="mr-1">Question {{$number}} :</strong><?php (isset($value->questionTitle) && ($value->questionTitle != '')) ? print($value->questionTitle) : ''; ?> </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6" id="sub">
                                <strong class="mr-1">Answer Type:</strong>   
                                @if(!empty($value->answerFormat)){{config("form.answer_format")[$value->answerFormat]}}@endif
                            <p class="text-muted m-0"></p>        
                        </div>
                        @if(isset($value->label))
                        <div class="form-group col-md-6 label">
                            <strong class="mr-1">Label:</strong> 
                            @php $i = 97 @endphp
                            @foreach($value->label as $labels)
                               @php echo '<span><strong>'.chr($i) .".".'</strong>'.$labels."   ".'</span>'  @endphp
                               @php $i++ @endphp
                            @endforeach 
                            <p class="text-muted m-0"></p>
                        </div>
                        @endif
                        <?php $number++; ?>
                        <div class="separator-breadcrumb border-top"></div>
                    </div>
                    @endforeach
        </div>
            <br><br><br>
 <!--     </div> -->
    <div class="card-footer"><hr></div>
</div>
<div style="margin: -45px -45px -0px -45px">
    <div class="form-row" style="background-color:#1474a0;height: 10px;"></div>
    <div class="form-row" style="background-color:#27a8de;height: 80px;"></div>
</div>

</body>
</html>

                                            