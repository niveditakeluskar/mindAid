<!-- Vatran Service -->
<div id="vateran-service" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Military Service</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" name ="vateran_service_form"  id="vateran_service_form">
                        @csrf
                        <div class="col-md-8 form-group">
							<label>Have you ever served in the Military?</label>
								@select("Military served status", "military_status", [
								0 => "Yes",
								1 => "No",
								2 => "Unknown"
								], [ "id" => "military" ])
						</div>
                        
                        <?php 
								$last_key = 0;
                                if(isset($stepWiseVateran)){
								foreach($stepWiseVateran as $skey => $stepvalue){ ?>
                    <div class="row" id="veteran-question" style="display:none">
                            <div class="col-lg-12 mb-3">
                                <div class="card">
                                    <div class="card-body">		
                                        <?php
                                        foreach ($stepvalue as $key => $value) {
                                            $queData = json_decode($value['question']);
                                            ?>
                                            <div class="mb-4 radioVal" id="{{$last_key}}general_question11">
                                                <?php $que_val = trim(preg_replace('/\s+/', ' ', $queData->question->qs->q)); ?>
                                                <label for="are-you-in-pain" class="col-md-12"><input type="hidden" name="DT{{$last_key}}[qs][q]" value="<?php echo $que_val; ?>"><?php echo $queData->question->qs->q; ?></label>
                                                <div class="d-inline-flex mb-2 col-md-12">
                                                    <?php
                                                    if(property_exists($queData->question->qs, 'opt')){ 
                                                        renderTree($queData->question->qs->opt, 'DT' . $last_key . '[qs][q]', '1', $last_key, $queData->question->qs->AF); 
                                                    }/*else if($queData->question->qs->AF == '2' || $queData->question->qs->AF == '5'){
                                                        $label_str = str_replace("[q]", "", 'DT' . $last_key . '[qs][q]');
                                                        $i = 1;
                                                        $label = $label_str . "[opt][" . $i . "][val]"; ?>
                                                        echo "<input type='text' class='form-control col-md-5' name='$label' id='11' value=''><label class='radio radio-primary mr-3'><input type='hidden' id='' value='$label'></label><input type='radio' style='display:none' checked>";
                                                    <?php
                                                    }*/
                                                    ?>
                                                </div>
                                                <p class="message" style="color:red"></p>
                                            </div>
                                            <div id="question{{$last_key}}"></div>

                                            <hr>
                                            <?php 
                                            $last_key++;

                                        } $last_key = $last_key;  ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div> 


                        <?php }} ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary float-right submit-vateran-service">Submit</button>
                            <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Vatran Service --> 