<!--============ Customizer =============  to rotate text use spin in class  (click)="isOpen = !isOpen" -->
<div class="current_month_notes" id="current_month_id">
    <div class="handle" id="current_month_id">
        <i class="i-Calendar spin"> <!-- <span> Current Month Notes </span> --> </i> 
    </div>
    <div class="card">
        <div class="card-header" id="headingOne">
            <p class="mb-0">
                Current Month Notes
            </p>
        </div>
        <div class="current_month-body" data-perfect-scrollbar data-suppress-scroll-x="true" style="max-height: 500px !important;">
            <div class="accordion" id="accordionCustomizer">
                <div id="current-month" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionCustomizer">
                    <div class="container-fluid">
                        <div id="currentMonthData"></div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="current_month-body" data-perfect-scrollbar data-suppress-scroll-x="true">
        <div class="accordion" id="accordionCustomizer">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <p class="mb-0">
                        Current Month Notes
                    </p>
                </div>
                <div id="current-month" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionCustomizer">
                    <div class="">
                        <div class="container-fluid">
                            <div class="row"></div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div id="currentMonthData"></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
<!-- ============ End Customizer =============