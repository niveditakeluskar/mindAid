<div class="modal fade office_modal" tabindex="-1" role="dialog" aria-labelledby="officeRangeModal" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="officeRangeModal">Patient Condition - In Call PCP Office Range</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="card mb-4">
               <!--begin::form-->                                
               <div class="card-body">
                  <?php
                     // $questionnaire = json_decode($data,true);  
                     
                     $num = 1;
                     $queData = json_decode($questionSet1['question']);
                     $questionnaire = $queData->question->q;     
                     // var_dump($questionnaire);
                     // foreach($questionnaire as $value)         
                     ?>
                  @foreach($questionnaire as $value)
                  <div class="form-row">
                     <div class="form-group col-md-6" id="from">
                        <strong class="mr-1">{{ $num }} :</strong>	                           
                        {{$value->questionTitle}}
                        <p class="text-muted m-0"></p>
                     </div>
                     <div class="form-group col-md-6" id="sub">
                        <div class="row">
                            @foreach($value->label as $labels)
                            <!-- <span class="col-md-12"> -->
                            <!-- <label>{{$labels}}</label> -->
                            @if($value->answerFormat == 1)
                            <select>
                                <option value="{{$labels}}">{{$labels}}</option>
                            </select>
                            @elseif($value->answerFormat == 2)<input type="text" value="{{$labels}}">
                            @elseif($value->answerFormat == 3)
                            <label class="radio radio-primary col-md-3">
                                <input type="radio" name="radio{{ $num }}" value="{{$labels}}" formControlName="radio">
                                <span>{{$labels}}</span>
                                <span class="checkmark"></span>
                            </label>
                            @elseif($value->answerFormat == 4)<input type="checkbox" value="{{$labels}}" >
                            @elseif($value->answerFormat == 2)
                            <textarea type="text" value="{{$labels}}"></textarea>
                            @endif
                            </span>
                            @endforeach
                            <p class="text-muted m-0"></p>
                            <p class="text-muted m-0"></p>
                        </div>
                     </div>
                     <div class="invalid-feedback"></div>
                  </div>
                  <div class="form-row">
                     <div class="form-group col-md-12">
                        <!-- <strong class="mr-1">Label</strong>	 -->
                     </div>
                  </div>
                  <?php $num++ ?>
                  @endforeach
               </div>
               <!-- end::form -->
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="save_office_pcp">Save</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
         </div>
      </div>
   </div>
</div>