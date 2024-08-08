@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">License</h4>
                </div>
                 <div class="col-md-1">
                
                </div>
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
               @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="LisenseList" class="display table table-striped table-bordered capital" style="width:100%">
                    <thead>
                        <tr class="capital-first">
                            <th>No</th>
                            <th>Organization Name</th>
                            <th>License Model</th>
                            <th>Subscription in Month</th>
                            <th>Module</th>
                            <th>Start Date</th>
                            <th>End date</th>                  
                            <th width="100px">Action</th>
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

@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="https://momentjs.com/downloads/moment.min.js"></script>
    <script type="text/javascript">


  

       var columns = [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'licensfetch.name', name: 'licensfetch.name'},
                    {data: 'license_model', name: 'license_model'},
                    {data: 'subscription_in_months', name: 'subscription_in_months'},
                    {data: 'modules.modules', name: 'modules.modules'},

                    {data: 'start_date', type: 'date-dd-mmm-yyyy', name: 'start_date',
                     "render":function (value) {
                    if (value === null) return "";
                     return moment(value).format('MM-DD-YYYY');
                    }
                    },
          
                    {data: 'end_date', name: 'end_date',
                   "render":function (value) {
                    if (value === null) return "";
                    return moment(value).format('MM-DD-YYYY');
                  }
                  },
      
                   {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
             var table = util.renderDataTable('LisenseList', "{{ route('licenseList') }}", columns, "{{ asset('') }}");  
          

              $("document").ready(function(){
                setTimeout(function(){
                   $("div.alert").remove();
                }, 5000 ); // 5 secs
                util.getToDoListData(0, {{getPageModuleName()}});
            });
</script> 

@endsection

 