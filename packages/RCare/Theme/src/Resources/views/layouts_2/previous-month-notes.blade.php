<!--============ Customizer =============  to rotate text use spin in class  (click)="isOpen = !isOpen" -->
<div class="previous_month_notes" id="previous_month_id" style="display:none;">
    <div class="handle" id="previous_month_id" data-toggle="tooltip" data-placement="left" title="Previous Month Notes" tabindex="0">
        <i class="i-Calendar-2 spin"><!--  <span> Previous Month Notes </span>  --></i>
    </div>
    <div class="card">
        <div class="card-header" id="headingOne">
            <div class="mb-0">
                Monthly Notes
                @hidden('regi_mnth',['id'=>'regi_mnth'])
                <div style="float:right">
                    <i class="btn btn-primary i-Left mr-2" id="prev-sidebar-month" onclick="prevMomths()"></i>
                    <span id="display_month_year"></span>
                    <i class="btn btn-primary i-Right mr-2" id="next-sidebar-month"  onclick="nextMonths()" style='display:none'></i>
                </div>
            </div>
        </div>
        <div class="previous_month-body" data-perfect-scrollbar data-suppress-scroll-x="true">
            <div class="accordion" id="accordionCustomizer">
                <div id="previous-month" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionCustomizer">
                    <div class="container-fluid">
                        <div class="col-12">
                            <div id="previousMonthData"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="temp" style="display:none;"> Patient Caretool Data</div>
    </div>
</div>

