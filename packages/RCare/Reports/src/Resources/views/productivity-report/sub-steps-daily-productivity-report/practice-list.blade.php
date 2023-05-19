@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Practices List</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="practice_form" name="practice_form"  action ="">
                @csrf
                <div class="form-row">
                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label> 
                        @selectpracticeswithAll("practices", ["id" => "practices_id","class" => "select2"])
                    </div>

<!--                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Caremanager Name</label>
                        @selectpractices("practices", ["id" => "practicesid","class" => "select2"])
                    </div -->

                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="search-practice" class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="Productivity-Practice-List" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                              <th width="35px">Sr No.</th>        
                              <th width="205px">Practice</th>
                              <th width="20px">Location</th>
                              <th width="20px">Practice Number</th> 
                              <th width="20px">Address</th>  
                              <th width="20px">Phone Number</th>
                            </tr>
                        </thead>
                      <tbody>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="app"></div>
@endsection
@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
// Productivity practice billable patient
var getProductivityPracticeList = function(practice=null) {
    var columns =  [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'pname', name: 'pname'},
        {data: 'plocation',name: 'plocation'},
        {data: 'pnumber', name: 'pnumber'},
        {data: 'paddress', data:'paddress'},
        {data: 'pphone', name: 'pphone'},
    ];
    if(practice=='')
    {
        practice=null;
    }
    var url ="/reports/productivity-practice/"+practice;
    util.renderDataTable('Productivity-Practice-List', url, columns, "{{ asset('') }}");
}

$(document).ready(function() {
    //getProductivitycounts();
    getProductivityPracticeList();
    util.getToDoListData(0, {{getPageModuleName()}});
}); 


$('#search-practice').click(function(){
    var practice=$('#practices_id').val();
    getProductivityPracticeList(practice);
});

</script>

@endsection


