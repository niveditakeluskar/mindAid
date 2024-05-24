@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-11">
                <h4 class="card-title mb-3">Roles</h4>
            </div>
                <div class="col-md-1">
                <a class=" " href="javascript:void(0)" id="addrole"> <i class="add-icons i-Business-Mens" data-toggle="tooltip" data-placement="top" title=" Add Role"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title=" Add Role"></i></a> 
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
                        <table id="usersRolesList" class="display table table-striped table-bordered capital" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="55px">Sr No.</th>
                                    <th>Roles Name</th>                  
                                    <th width="55px">Action</th>
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
    
    <div class="modal fade" id="role_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="role_modal_heading"></h4>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" name="role_form" id="role_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="role_name">Role name</label>
                                    @text("role_name", ["id" => "role_name", "class" => "form-control capital-first"])
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="Status">Status</label>
                                    @selectactivestatus("status",["id" => "status", "class" => "form-control form-control"])
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                            <div class="col-lg-12 text-right">
                                    <span id="button_div"></span>
                                        <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>

<!-- 
                         <div class="card-footer">
                              <div class="mc-footer">
                                  <div class="row">
                                    
                                        

                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-3" id="button_div">
                                        </div>
                                        <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                        </div>
                                        <div class="col-md-1" id="button_div">
                                        </div>
                                          
                                        
                                   
                                </div> 
                            </div>
                         </div> -->
                        <div class="form-group">
                            <div class="row">
                                <!-- <div class="col-md-6" id="button_div">
                                    
                                </div> -->
                                <!--  <div class="col-md-6">
                                    <button class="btn btn-primary btn-block btn-rounded mt-3">Cancel</button>
                                </div> -->
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
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
        
         $("document").ready(function(){
    setTimeout(function(){
       $("div.alert").remove();
    }, 5000 ); // 5 secs

});
    </script>
    <script type="text/javascript">
    
        var columns= [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'role_name', name: 'role_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];
        var table = util.renderDataTable('usersRolesList', "{{ route('users_roles_list') }}", columns, "{{ asset('') }}");

    </script>
    
    <script>
        $(document).ready(function() {
            roles.init();
            form.ajaxForm(
                "role_form",
                roles.onResult,
                roles.onSubmit,
                roles.onErrors
            );
            form.evaluateRules("UserRoleAddRequest");
        });
    </script>

@endsection