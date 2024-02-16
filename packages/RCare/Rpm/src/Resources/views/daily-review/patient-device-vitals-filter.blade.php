                   <?php $monthlyid="monthly_".$devices[$i]->id;
                         $monthlyto="monthlyto_".$devices[$i]->id;

                   ?>
                   <div class="row">
                         <div class="col-md-2 form-group mb-3">
                        <label for="month">From Month & Year</label>
                        @month('monthly',["id" => $monthlyid]) 
                        </div>
                        <div class="col-md-2 form-group mb-3">
                            <label for="month">To Month & Year</label>
                            @month('monthlyto',["id" => $monthlyto])
                        </div>
                        <div class="row col-md-2 mb-2">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4 month-search" id="month-search_{{$devices[$i]->id}}" >Search</button>
                        </div>
                        <div class="col-md-8">
                            <button type="button" class="btn btn-primary mt-4 month-reset" id="month-reset_{{$devices[$i]->id}}">Reset</button>
                        </div>
                        
                    </div>
                     <div class="col-md-5 text-right" id="rpmfilename_{{$devices[$i]->id}}"></div>
                     
                     <div class="col-md-1 text-right" ><button type="button" class="btn btn-primary mt-4 month-reset" id="Addressed_{{$devices[$i]->id}}">Addressed</button></div>
                        
                        </div>