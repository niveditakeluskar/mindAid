<div class="row">
    <div class="col-md-12 mb-4"> 
        <div class="card text-left">   
            <div class="card-body">
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf 
                <div class="form-row">
                    <div class="col-md-2 form-group mb-2">
                        <label for="caremanagerid">Practice grp</label>
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">Date</label>
                        @date('date',["id" => "fromdate"])                  
                    </div> 
                    <div class="row col-md-3 mb-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary mt-4" id="searchbutton">Search</button>
                        </div>
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-primary mt-4" id="resetbutton">Reset</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
