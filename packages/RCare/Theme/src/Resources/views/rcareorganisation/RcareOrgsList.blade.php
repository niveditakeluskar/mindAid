@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Orgs</h4>
                </div>
                 <div class="col-md-1">
                 <a class="btn btn-success btn-sm " href="{{ route('addorgs') }}" id=""> Add Org</a>  
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
                    <table id="orgsList" class="display table table-striped table-bordered" style="width:100%">
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
                            <th width="150px">Action</th>        

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

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    
    <script type="text/javascript">


  $(function () {

      var table = $('#orgsList').DataTable({
        processing: true,
        serverSide: true,
        hover:true,
 
        ajax: "{{ route('orgsList') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'contact_person', name: 'contact_person'},
            {data: 'contact_person_email', name: 'contact_person_email'},
            {data: 'city', name: 'city'},
                
            {data: 'action', name: 'action', orderable: false, searchable: false},
            
        ]



    });

      $('#orgsList').on( 'mouseenter', 'tbody tr', function () {
  var rowData = table.row( this ).data();
   // ... show tool tip

} );
      
      });

  



</script> 

@endsection

 