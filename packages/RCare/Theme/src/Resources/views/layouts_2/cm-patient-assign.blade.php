<!--============ Customizekar =============  to rotate text use spin in class  (click)="isOpen = !isOpen" assingpatient assignpatient-body-->
<div class="assingpatient" id="customizer_id2" style="display:block;">
    <div class="handle" id="customizer_id2"  data-toggle="tooltip" data-placement="left" title="Search Patient">
        <i class="i-Search-People"> <!-- <span> Current Month Notes </span> --> </i> 
        <!-- <span class="badge">0</span> -->
    </div>
    <div class="card">
        <div class="card-header" id="headingOne">
            <p class="mb-0">Search Patient</p>
        </div>
        
        <div class="assignpatient-body" data-perfect-scrollbar data-suppress-scroll-x="true"style="overflow-y:scroll!important">
            <div class="accordion" id="accordionCustomizer">
                <div id="collapseOne" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionCustomizer">
                    <div class="container-fluid">
                        <div class="list-group mt-3 mb-4" id="patientassignlist"></div> <!-- mb-4  class added by pranali on 30Oct2020  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- ============ End Customizer ============= -->
</div>