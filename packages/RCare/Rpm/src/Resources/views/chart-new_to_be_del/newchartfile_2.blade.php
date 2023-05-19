<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-header">
            Data Reading Chart
            </div>  
            <div class="card-body">
                <div>
                   
                    @include('Rpm::chart-new.vitals-tab-new')
                </div> 
                   
                @include('Theme::layouts.flash-message')
                <input type="hidden" name="dev_id" id="dev_id">
                <?php $patientid =  $patient[0]->id; ?>
            </div>
        </div>
    </div>
</div>


	<div id="container"></div>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src=""></script>

<script type="text/javascript">
    // function checkreviewclick(data,deviceid)
    //      {
    //         var deviceid=deviceid;
    //         var reviewstatus;
            
    //         var patient_id = "<?php echo $patientid; ?>";
            
          
    //         var checkid=data.id;
    //         var res = checkid.replace("review_", "");
    //         // var serialid=$('#'+res).val();
    //         var serialid=$('#id_'+deviceid+"_"+res).val();
    //          // if(serialid != "" || serialid != 0 || serialid != undefined )
    //         if(serialid == "" || serialid == 0 || serialid == undefined )
    //         {
    //             alert("No Reading Found!");
    //         }
    //         else
    //         {
    //         var reviewdata = {
    //         patient_id: patient_id,
    //         deviceid: deviceid,           
    //         serialid : serialid
    //         } 

    //            $.ajaxSetup({    
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //        });
    //        $.ajax({
    //             type: 'POST',
    //             url: '/rpm/chart-new/updatediv',    
    //             data: reviewdata,
    //             success: function (data) {
                       
    //                    $("#success_"+deviceid).show();
    //                 // var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Review Status Updated Successfully!</strong></div>';
    //                 // $("#success_"+deviceid).html(txt);
    //                 var scrollPos = $(".main-content").offset().top;
    //                 $(window).scrollTop(scrollPos);
    //                 //goToNextStep("call_step_1_id");
    //                 setTimeout(function () {
    //                     $("#success_"+deviceid).hide();
    //                 }, 3000);
    //             }
    //        });

    //        }
    //      }


var arraydtetime2 = '<?php echo json_encode($uniArray) ?>';

//alert(arraydtetime2);
var patientarraydatetime = JSON.parse(arraydtetime2);

var readingOxy = '<?php echo json_encode($arrayreading1) ?>';
var patientreading1 = JSON.parse(readingOxy);

var readingHrt = '<?php echo json_encode($arrayreading2) ?>';
var patientreading2 = JSON.parse(readingHrt);

var reading3 = '<?php echo json_encode($arrayreading3) ?>';
var patientreading3 = JSON.parse(reading3);

var label1 = '<?php echo json_encode($label1) ?>';
var label11 = JSON.parse(label1);

var label2 = '<?php echo json_encode($label2) ?>';
var label22 = JSON.parse(label2);

var label3 = '<?php echo json_encode($label3) ?>';
var label33 = JSON.parse(label3);

// if(patientreadingOxy){
//     var patientarraydatetime = JSON.parse(arraydtetime1);
// }else if(patientreadingHrt){
//     var patientarraydatetime = JSON.parse(arraydtetime2);
// }
console.log(patientarraydatetime);

//console.log(patientreading);



// var a = arraydtetime[0];
// console.log(a);
// for(var i = 0; i < arraydtetime.length; i++){
//     console.log(arraydtetime[i]); 
// }

var labels = [10,20,30,40,50,60,70,80,90,100];

var m_names = ['January', 'February', 'March', 
               'April', 'May', 'June', 'July', 
               'August', 'September', 'October', 'November', 'December'];

const d = new Date();
var month = m_names[d.getMonth()];
var labels123 = ['0', '54', '70', '80', '140', '180', '200', '240','300'];

document.addEventListener('DOMContentLoaded', function () {
        const chart = Highcharts.chart('container', {

            chart: {
                scrollablePlotArea: {
      			minWidth: 700
    			}
            },
            title: {
                text: 'Reading Chart'
            },
            xAxis: {
            	title: {
			      text: 'Date-Time'
			    },
			    // min:Date.UTC(2021, 0, 0),
			    // max:Date.UTC(2021, 12, 1),
			    // type: 'datetime',
			    // tickInterval: 24 * 3600 * 1000*30,
			    categories: patientarraydatetime,
                // categories: ['2021-06-18 12:00:00','2021-06-18 15:00:00','2021-06-18 18:00:00','2021-06-18 21:00:00','2021-06-18 23:00:00'],//7*24*3600*1000,//24 * 3600 * 1000*30,
                labels: {
      				align: 'left',
				      x: 3,
				      y: -3
    			}
            },
            yAxis: {
                title: {
                    text: 'Readings'
                }
            },
             series: [{
                name: label11,
                data: patientreading1
             }, 
            {
                name: label22,
                data: patientreading2
            }, 
            {
                name: label33,
                data: patientreading3
            }
            ]
        });
    });
</script>