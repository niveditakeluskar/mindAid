<div class="row mb-4">
    <div class="col-md-12">
        <div class="card" >
            <div class="card-header" >
                <div class="form-row">
                    <span class="col-md-8">
                        <h4>Patient Status</h4>
                    </span>
                </div>  
            </div>
            <div class="card-body"> 
                <div class="row">
                    <div class="" id="status_block"  style="display: none">
						<div id="status_blockheader">
						<h4>Status</h4>
						</div>
						<div id="status_blockcontent">
                        <label for="name"><b>Total Time Elapsed: </b></label> 00:14:00 Hours<br/>
                        <div class="mb-1 mt-1">
                            <label class=" mb-1"><strong>Chronic Condition(s):</strong></label>
                            <span>Hypertension (I10), COPD (J44.9), Diabetes (E11.9), Depression (F32.9)</span>
                        </div>
                        <div class="mb-1">
                            <label class=" mb-1"><strong>RPM Device(s):</strong></label>
                            <span>Blood Pressure Cuff</span>
                        </div>
                        <div class="mb-1">
                            <label class=" mb-1"><strong>Medications / Vaccines:</strong></label>
                            <span>Enduron (5 mg 1x daily), Lopressor (100 mg 2x daily), Zoloft (25 mg 2x daily)</span>
                        </div>
                        <div class="mb-1">
                            <label class=" "><strong>Date of Last Contact:</strong></label>
                            <span>November 23, 2019</span>
                        </div>
                        <div class="mb-1">
                            <label class=" "><strong>Personal Notes:</strong></label>
                            <span>Wife is deceased (2015).  Daughter (Julie) is his primary care giver.  Dog lover; has 3 golden retrievers (Charlie, Thor, Rex); Rex is old and sick.  Grandson goes to UT, in his third year and is studying to be an architect.  Likes to play bingo at the Catholic Church on Wednesdays.  Plays golf on Saturday morning with regular group.</span>
                        </div>
                        <div class="mb-1">
                            <label class=" "><strong>Part of Research Study:</strong></label>
                            <span>Self-Management Grant (G1C)</span>
                        </div>
                    </div>  
                    </div>  
                    <div class="col-md-12 steps_section" id="" style=""> 
                       @include('Rpm::deviceTraning.device-traning-sub-steps.step')
                    </div>                   
                    <div class="col-md-6 steps_section" id="select-activity-2" style=""> 
                      
                    </div>              
       
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="current_month_notes" tabindex="-1" role="dialog" aria-labelledby="current_month_notes-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content"> 
            <form action="" method="post" name="practice_add">
                <div class="modal-header">
                    <h5 class="modal-title mx-auto" id="current_month_notes-title"> <span class="text-muted">{{$checklist->fname}} {{$checklist->lname}}'s </span>Current Monthly Call Note( Dec 2019 ) </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">  
                </div>
            </form>
        </div>
    </div>
</div> 