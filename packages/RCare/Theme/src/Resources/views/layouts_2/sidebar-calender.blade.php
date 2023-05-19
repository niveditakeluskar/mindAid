<!--============ Customizer =============  to rotate text use spin in class  (click)="isOpen = !isOpen" -->
<div class="sidebarcalender" id="sidebarcalender_id" style="display:none; ">
    <div class="handle" id="sidebarcalender_id"  data-toggle="tooltip" data-placement="left" title="Todo List Calender" tabindex="0"> 
        <i class="i-Calendar-2 spin">
    </div>
    <div class="card">
    <div class="card-header" id="headingOne"> 
        <p class="mb-0">To Do List Calender</p> 
    </div>
    <div class="sidebarcalender-body" data-perfect-scrollbar data-suppress-scroll-x="true"style="overflow-y:scroll!important">
        <div class="accordion" id="accordionsidebarcalender">
            <div id="collapseOne" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionsidebarcalender">
                <div class="container-fluid">
                    <div class="list-group mt-3 mb-4">
                        <div id="todo-calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============ End sidebarCalender ============= -->