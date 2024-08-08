@extends('Theme::layouts_2.to-do-master')
@section('page-css')
 @section('page-title')
  Care Manager Logged Minute Productivity Report
@endsection 
@endsection
@section('main-content') 
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3"> Care Manager Logged Minute Productivity Report</h4>
        </div>
    </div> 
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div> 
@include('Reports::monthly-account-performance-report.summary-filter')
 <div class="row mb-4"> 
    <div class="col-md-12 mb-4">  
        <div class="card text-rightleft"> 
            <div class="card-body">
                <div class="table-responsive">
                    <table id="caremanager-listing" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="10">Minutes Logged in Rcare</th>
                            <th colspan="2">Average</th>
                        </tr>
                        
                        <tr> 
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                            <th colspan="2">5 Days Ago</th>
                            <th colspan="2">4 Days Ago</th>
                            <th colspan="2">3 Days Ago</th>
                            <th colspan="2">2 Days Ago</th>
                            <th colspan="2">Yesterday</th> 
                            <th colspan="2">Avg</th>
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
<style type="text/css">

</style>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
    </script>
@endsection