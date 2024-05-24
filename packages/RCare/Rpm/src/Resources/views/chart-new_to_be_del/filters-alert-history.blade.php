<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">     
            <div class="card-body">
                <form>
                @csrf
                <div class="form-row">
                        <div class="col-md-2 form-group mb-3">  
                            <label for="practicegrp">{{config('global.practice_group')}}</label>
                            @selectrpmpracticesgroup("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="practicename">Practice</label>                  
                            @selectrpmpractices("practices",["id" => "practices", "class" => "form-control show-tick select2"])   
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="provider">Provider</label>
                            @selectpracticesphysician("provider",["id" => "physician","class"=>"custom-select show-tick select2"])
                        </div>

                        <div class="col-md-2 form-group mb-3">
                            <label for="caremanager">Care Manager</label>
                            @selectRpmcaremanagerNone("caremanagerid", ["class" => "select2","id" => "caremanagerid"]) 
                            <!-- selectcaremanagerNone -->
                        </div>
                        <div class="col-md-2 form-group mb-3">
                            <label for="patient">Time Frame</label>
                            <select id="timeframe" class="custom-select show-tick" name="timeframe">
                                <option value ="" selected>All</option>
                                <option value="1">30</option>
                                <option value="2">60</option>
                                <option value="3">90</option>                             
                            </select>
                        </div>
                    </div>
                    <div class="form-row">   
                        <div class="col-md-1">
                            <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary mt-4" id="resetbutton">Reset</button>
                        </div>
                    </div>
                
                </form>
            </div>
        </div>
    </div>
</div>

