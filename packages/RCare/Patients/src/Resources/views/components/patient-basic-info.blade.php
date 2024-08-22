<div id='success'></div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card grey-bg">
            <div class="card-body">             
                <div class="form-row">
                    <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient[0]->id}}">
                    <div class="col-md-12">
                        <div class="form-row"> 

                            <div class="col-md-4 right-divider">
                                <label data-toggle="tooltip" data-toggle="tooltip" data-placement="top" title="Name">
                                <i class="text-muted i-Doctor"></i> :  {{$patient[0]->fname}} {{$patient[0]->lname}}</label><br/>
                                <label data-toggle="tooltip" title="DOB" data-original-title="Patient DOB" for="dob">{{ $patient[0]->dob }}</label>  |
                                <label data-toggle="tooltip" title="Gender" data-original-title="Patient Gender" for="gender"> 
                                <?php 
                                    if($patient[0]->gender == 0) { 
                                        echo "Female";
                                    } else { 
                                        echo "Male";
                                    }
                                ?>
                                </label>
                            </div>

                            <div class="col-md-4 right-divider">
                                <label data-toggle="tooltip" data-placement="right" title="Contact Number" data-original-title="Patient Phone No." for="Phone No">
                                    <i class="text-muted i-Old-Telephone"></i> : <b>{{ $patient[0]->country_code}} {{$patient[0]->mob}}</b></label> |
                                    
                                <label for="CM" data-toggle="tooltip" data-placement="top" title="Email" data-original-title="Email">
                                    <b><i class="text-muted i-Talk-Man"></i></b>
                                     : {{ $patient[0]->email }}
                                </label><br>
                                <label data-toggle="tooltip" data-placement="right" id="basix-info-address" title="Address" data-original-title="Patient Address" for="Address" style="padding-right:2px;">
                                    <i class="text-muted i-Post-Sign"></i> : {{ $patient[0]->city}} {{ $patient[0]->state}} {{ $patient[0]->address}} {{ $patient[0]->zipcode}}</label> 
                            </div>

                            <div class="col-md-4 right-divider">  
                                <label for="Practice" data-toggle="tooltip" data-placement="top" title="Practice" data-original-title="Patient Practice">
                                    <i class="text-muted i-Hospital"></i> : 
                                    {{empty($patient[0]->practices['name']) ? '' :  $patient[0]->practices['name']}}
                                </label><br>
                                <label for="EMR" data-toggle="tooltip" data-placement="top" title="EMR" data-original-title="Patient EMRrrr">
                                    <i class="text-muted i-ID-Card"></i>
                                     : {{ empty($patient[0]->surgery_date) ? '' : $patient[0]->surgery_date }}
                                </label>
                            </div>

                        </div>
                        
                    </div>
            </div> 
        </div>
    </div>
</div>
                                </div>

        