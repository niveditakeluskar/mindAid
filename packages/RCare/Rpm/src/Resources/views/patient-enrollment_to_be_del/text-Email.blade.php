<div class="row" id="content-template" style="display: none;">
    <div class="col-md-12 ">
        <div class="card">
            <div class="card-header"><h4 id="header_text"></h4>
            </div>
            <form id="template_fetch" method="post"> 
                @csrf
            <div class="card-body">
                <!-- use Temlate Content -->
                    <div id="template_content">
                        <div class="row">
                            <div class="col-md-12 form-row" id="contactlist">
                                <label for="contactNumber" class="action-bar-horizontal-label col-md-4 col-form-label ">Contact No.:</label>
                                <div class="col-md-8 mb-3">
                                    <select class="custom-select" name="contact_number" id="contact_number">
                                        <option value="">Choose Contact Number</option>
                                        @foreach($contact_number as $value)
                                            <option value="{{ $value->phone_primary }}">{{ $value->phone_primary }}</option>
                                            <option value="{{ $value->phone_secondary }}">{{ $value->phone_secondary }}</option>
                                            <option value="{{ $value->other_contact_phone }}">{{ $value->other_contact_phone }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 form-row" id="emaillist">
                                <label for="emailTo" class="action-bar-horizontal-label col-md-4 col-form-label ">To:</label>
                                <div class="col-md-8 mb-3">
                                    <select class="custom-select" name="contact_email" id="contact_email">
                                        <option value="">Choose Contact Email</option>
                                        @foreach($contact_email as $value)
                                            <option value="{{ $value->email }}">{{ $value->email }}</option>
                                            <option value="{{ $value->poa_email }}">{{ $value->poa_email }}</option>
                                            <option value="{{ $value->other_contact_email }}">{{ $value->other_contact_email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 form-row">
                                <label for="Template" class="action-bar-horizontal-label col-md-4 col-form-label ">Template:</label>
                                <div class="col-md-8">
                                    <select class="custom-select" name="content_title" id="content_title">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id = "textarea">
							<div class="col-md-12 form-row">
                            <label for="Message" class="action-bar-horizontal-label col-md-4 col-form-label ">Message:</label>
                            <div class="col-md-8">
                                <textarea name="content_area" class="form-control" id="content_area"></textarea> 
                            </div>
							</div>
                        </div>
                        <div class="row" style="display: none" id="email-div">
                            <label for="Subject" class="action-bar-horizontal-label col-md-2 col-form-label ">Subject:</label>
                            <div class="col-md-10">
                                @text("subject", ["id" => "subject"]) 
                            </div>
                            <label for="Message" class="action-bar-horizontal-label col-md-2 col-form-label ">Message:</label>
                            <div class="col-md-10">
                                <p name="content" class="mail_content" id="editor" cols="30" rows="10"></p>
                            </div>
                        </div>
                    </div>
            </div>
                <div class="card-footer text-right">
                    <div class="">
                        <button type="button" class="btn btn-primary btn-icon btn-lg m-1" id="send">
                            <!-- <span class="ul-btn__icon"><i class="i-"></i></span> -->
                            <span class="ul-btn__text">Send</span> 
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script> 
    $("#send").click(function(){
        var hidden_id =$('#hidden_id').val();
        var title = $('#header_text').html();
        var practice_id   =  $('#practice_id').val();
       var contact_number =  $('#contact_number').val();
       var content_title  =  $('#content_title').val();
       var content_area   =  $('#content_area').val();
       var contact_email  =  $('#contact_email').val();
       var subject        =  $('#subject').val();
       var content        =  $('#content').val();

        if (title=='Text'){
            var template_type_id ='2'
                 // alert(template_type_id);
            if (content_title=='') {
                alert('please Choose Title')
            }
        }
        else if(title =='Email'){
                  var template_type_id ='1'
                  // alert(template_type_id);
                  if (content_title=='') {
                alert('please Choose Title')
            }
        }
       
        $.ajax({
         type:'post', 
         url:"/rpm/patient-enrollment-save",
         data:{hidden_id:hidden_id,
            practice_id:practice_id,
            title:title,
         contact_number:contact_number,
         content_title:content_title,
         title:title,
         contact_email:contact_email,
         subject:subject,
         content:content,
         content_area:content_area,
         template_type_id:template_type_id},  // multiple data sent using ajax
        success: function (response) {
            // alert(response)
        }
      });
      return false;
    })
</script>
