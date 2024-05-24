<!DOCTYPE html>
<html lang="en">
<style type="text/css">
  
   .error {
      color: red;     
   }
</style>
<head>
  <title>Renova Healthcare</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- <script src="https://www.google.com/recaptcha/api.js"></script> -->
{!! NoCaptcha::renderJs() !!}
</head>
<body> 
<div class="container">
  <h5>Opt Out of Texting</h5> 
  {!! NoCaptcha::renderJs() !!}
        <form id="unsubscribe_form" name="unsubscribe_form" > 
            @csrf
                <div class="card">
                 <div class="card-body">
                  <div id="msg"></div>
                    <div id="unsubscribe-group" class="form-group">
                 	 <input type="checkbox" id="unsubscribe" name="unsubscribe" value="0">
                     <label for="unsubscribe"> Opt Out of Texting</label> 
                   </div>
                     <input type="hidden" value="{{ request()->get('contact_trackid') }}" name="contact_no">
                     <input type="hidden" value="{{ request()->get('t') }}" name="patientid">
                     <!-- <div class="g-recaptcha" data-sitekey="INSERT_YOUR_SITE_KEY"></div> -->
                      <div id="recaptcha-group" class="form-group">
                     {!! NoCaptcha::display() !!}
                   
                      </div>
                 	</div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row" style="float: right">   
                                <button type="button" id="save-text" class="btn  btn-primary m-1" >Submit</button>
                                 <!-- <button type="button" id="save-text" class="btn btn-outline-secondary m-1 cancel">Cancel</button> -->
                        </div>
                    </div>
                </div>
                
            </div>            
        </form>   
  </div>

</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

     $("button").click(function(){     
         if($('#unsubscribe').is(":not(:checked)")){           
             $("#unsubscribe-group").append(
            '<div class="error" id="error">Please select checkbox to unsubscribe.</div>'
             );
          }
          else{
             $('#error').html('');
          }         
            var data = $('form').serialize();            
          $.ajax({type: "POST",url: "text-msg-unsubscribe",data:data, error: function (request, error) {
          console.log(arguments);
           $("#recaptcha-group").append(
            '<div class="error" id="error1">Please verify that you are not a robot.</div>'
             );
      
         }     , success: function(result){
            console.log(result+"testing");
            $('#error1').html('');
            var msg ='';
             if($.trim(result)=='1')
             {
              msg = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Text Message Unsubscribed Successfully!</strong></div>';
             }
             else
             {
               msg = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>'+result+'</strong></div>';
             }               
              $('#msg').html(msg);
          }});     
      });

</script>
