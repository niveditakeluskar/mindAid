@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Physicians</h4>
                </div>
                 <div class="col-md-1">
                 <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addPhysicican"> Add physician</a>  
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
                    <table id="physicianList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="45px">Sr No.</th>
                            <th>physician Name</th> 
                            <th>Physician UID</th>
                            <th>physician Email</th>     
                            <th width="80px">physician Contact</th>                   
                            <th width="65px">Action</th>
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
    

<div class="modal fade" id="edit_physician_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Edit physician</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
      <div class="modal-body">
        <form action="{{ route('update_org_physician') }}" method="POST" id="physicianForm" name="physicianForm" class="form-horizontal">
          {{ csrf_field() }}
          <input type="hidden" name="id" id="id">
          <div class="form-group">
          
                        <div class="row">
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianname">physician Name</label>
                                  <input id="physician_name" name ="physician_name" class="form-control  " type="text" required> 
                            </div>
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianname">physician Email</label>
                                    <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianname">physician UID</label>
                                    <input type="text" name="physicians_uid" class="form-control" min="0" id="physicians_uid" required>
                            </div> 
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianname">physician Contact Number</label>
                                    <input type="Number" name="contact" min="0" class="form-control" id="contact" required>
                            </div>  
                        </div>
                      
          </div>
        </div>

                            
         
      <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="submit"  class="btn  btn-primary m-1">Save Changes</button>
                            <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
    
   </div>
 </div>
</div>


<div class="modal fade" id="add_physician_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add physician</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
          <div class="modal-body">
            <form action="{{ route('create_org_physician') }}" method="post">
                      {{ csrf_field() }}

                      <div class="form-group">
                        <div class="row">
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianame">physician Name</label>
                               @text("physician_name", ["id" => "physician_name", "placeholder" => "Enter physician", "required" ])
                            </div>
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianname">physician Email</label>
                                    <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianname">physician Contact Number</label>
                                    <input type="text" name="contact" class="form-control"  id="contact" required>
                            </div> 
                            <div class="col-md-12  form-group mb-3 ">
                               <label for="physicianname">physician UID</label>
                                    <input type="text" name="physicians_uid" class="form-control" id="physicians_uid" required>
                            </div> 
                                 
                        </div>
                      </div> 
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"  class="btn  btn-primary m-1">Add physician</button>
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
          </div>
    </div>
  </div> 
</div>


@endsection
@section('page-js')

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script type="text/javascript">
        
        var columns= [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'physicians_uid', name: 'physicians_uid'},
                    {data: 'phone', name: 'phone'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];
        var table = util.renderDataTable('physicianList', "{{ route('org_physicians_list') }}", columns, "{{ asset('') }}");   

        $('#addPhysicican').click(function () {
            //$('#modelHeading1').html("Add Role");
        //     $('#saveBtn').val("create-product");
        //     $('#product_id').val('');
        //     $('#productForm').trigger("reset");
        // // $('#modelHeading').html("Add Role");
            $('#add_physician_modal').modal('show');
        });

        $('body').on('click', '.editPhysicians', function () {
            var id = $(this).data('id');
            $.get("ajax/editPhysicians" +'/' +id+'/edit', function (data) {
                //$('#modelHeading').html("Edit Roles");
                $('#saveBtn').val("edit-user");
                $('#edit_physician_modal').modal('show');
                $('#id').val(data.id);
                $('#physician_name').val(data.name);
                $('#contact').val(data.phone);
                $('#email').val(data.email);
                $('#physicians_uid').val(data.physicians_uid);


            })
        });
        $(document).ready(function() {
            util.getToDoListData(0, {{getPageModuleName()}});
        });
    </script>
    
    <!-- <script type="text/javascript">
  $(function () {
      var table = $('#usersRolesList').DataTable({
        processing: true,
        serverSide: true,

        ajax: "{{ route('users_roles_list') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'physician_name', name: 'physician_name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

      

      $('#addUser').click(function () {
        $('#modelHeading1').html("Add Role");
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
       // $('#modelHeading').html("Add Role");
        $('#ajaxModel1').modal('show');
    });


    /*  $('body').on('click', '.deleteRoles', function () {
     
     var id = $(this).data("id");
     confirm("Are You sure want to delete !");
   
     $.ajax({
         type: "POST",
         url: "/ajax/rCare/deleteRole"+'/'+ id +'/delete',
         data: {
        "_token": "{{ csrf_token() }}"
        },
         success: function (data) {
             table.draw();
         },
         error: function (data) {
             console.log('Error:', data);
         }
     });
 });
    */
 


  });

</script>  -->

@endsection

 