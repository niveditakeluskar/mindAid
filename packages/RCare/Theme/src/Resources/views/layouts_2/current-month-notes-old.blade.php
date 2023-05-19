<!--============ Customizer =============  to rotate text use spin in class  (click)="isOpen = !isOpen" -->
<div class="current_month" id="current_month_id">
    <div class="handle" id="current_month_id">
        <i class="i-Calendar spin"> <!-- <span> Current Month Notes </span> --> </i> 
       
    </div>
    <div class="current_month-body" data-perfect-scrollbar data-suppress-scroll-x="true">
        <div class="accordion" id="accordionCustomizer">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <p class="mb-0">
                        Current Month Notes
                    </p>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionCustomizer">
                    <div class="">
                   
                    <div class="container-fluid">
                        <div class="row"></div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                <?php if($current_month->count() > 0){?>
                                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Topic</th>
                                                <th>CareManager Notes</th>
                                            </tr>
                                        </thead>
 
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Health-related follow-up from last month</td>
                                                <td></td>
                                                
                                                
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>personal-realted follow-up from last month</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>New Hospitalization/ER visit/Urgent Care</td>
                                                <td>{{ $current_month->condition_requirnment_notes ? $current_month->condition_requirnment_notes : "NO"}}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>New Office Visits(any doctor)</td>
                                                <td>{{ $current_month->nov_notes ? $current_month->nov_notes : "NO"}}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>New diagnosis</td>
                                                <td>{{ $current_month->nd_notes ? $current_month->nd_notes : "NO"}}</td>
                                                
                                                
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Medication Changes</td>
                                                <td>{{ $current_month->med_added_or_discon_notes ? $current_month->med_added_or_discon_notes : "NO"}}</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>New Labs or Diagnostic Imaging</td>
                                                <td>{{ $current_month->report_requirnment_notes ? $current_month->report_requirnment_notes : "NO"}}</td>
                                               
                                            </tr>
                                             <tr>
                                                <td>8</td>
                                                <td>New/Changes to DME</td>
                                                <td>{{ $current_month->dme_notes ? $current_month->dme_notes : "NO" }}
                                                <p>{{ $current_month->ctd_notes ?  $current_month->ctd_notes :" " }}</p></td>
                                               
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Anything else of interest</td>
                                                <td>{{ $current_month->anything_else ? $current_month->anything_else : "NO"}}</td>
                                            </tr>
                                          
                                        </tbody>
                                    </table>
                                    
                                <?php }else{?>
                                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Topic</th>
                                                <th>CareManager Notes</th>
                                            </tr>
                                        </thead>
 
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Health-related follow-up from last month</td>
                                                <td></td>
                                                
                                                
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>personal-realted follow-up from last month</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>New Hospitalization/ER visit/Urgent Care</td>
                                                <td></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>New Office Visits(any doctor)</td>
                                                <td></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>New diagnosis</td>
                                                <td></td>
                                                
                                                
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Medication Changes</td>
                                                <td></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>New Labs or Diagnostic Imaging</td>
                                                <td></td>
                                               
                                            </tr>
                                             <tr>
                                                <td>8</td>
                                                <td>New/Changes to DME</td>
                                                <td></td>
                                               
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Anything else of interest</td>
                                                <td></td>
                                            </tr>
                                          
                                        </tbody>
                                    </table>

                                <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============ End Customizer =============