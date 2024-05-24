<div class="row mb-4">
    <div class="col-md-12">
        <div class="card" >
            <div class="card-header" >
                <div class="form-row">
                    <span class="col-md-8">
                        <h4>Device Training</h4>
                    </span>
                </div>  
            </div>
            <div class="card-body"> 
                <div class="row"> 
                    <div class="col-md-12 steps_section" id="" style=""> 
                       @include('Rpm::deviceTraning.device-traning-sub-steps.step')
                    </div>                   
                    <div class="col-md-6 steps_section" id="select-activity-2" style=""></div>              
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