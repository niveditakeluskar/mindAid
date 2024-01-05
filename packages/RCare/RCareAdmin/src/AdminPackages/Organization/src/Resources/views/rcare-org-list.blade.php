@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-10">
                   <h4 class="card-title mb-3">Organization</h4>
                </div>
                 <div class="col-md-2">
                 <a class="float-right" href="{{ route('addorg') }}" id=""><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Organization"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Organization"></i></a>   
                </div>
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              <!--     -->

                <div class="table-responsive">
                    <table id="orgs-list" class="display table table-striped table-bordered capital1" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th> 
                            <th>Email</th>
                            <th>Phone</th> 
                           <th>Contact Person</th>
                           <th>Contact Person Email</th>
                            <th>City</th>
                            <th>State</th>     
                            <th width="60px">Action</th>        

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

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">License</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

@endsection

@section('page-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>


    <script type="text/javascript">
    
        var columns= [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'contact_person', name: 'contact_person'},
                    {data: 'contact_person_email', name: 'contact_person_email'},
                    {data: 'city', name: 'city'},
                    {data: 'state', name: 'state'},      
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];
        var table = util.renderDataTable('orgs-list', "{{ route('org_list_data') }}", columns, "{{ asset('') }}");

         $("document").ready(function(){
    setTimeout(function(){
       $("div.alert").remove();
    }, 5000 ); // 5 secs

});

    </script>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
 
@endsection



 