@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-11">
                <h4 class="card-title mb-3">Practices</h4>
            </div>
            <div class="col-md-1">
                <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addPractice"> Add Practice</a>  
            </div>
        </div>
        <!-- ss -->             
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div id="success"></div>
    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                    @include('Theme::layouts.flash-message')
                    <div class="table-responsive">
                        <table id="practiceList" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Practice</th>      
                                    <th>Location</th>
                                    <th>Practice Number</th>
                                    <th>Address</th>
                                    <th>Phone Number</th>
                                    <th>Key Contact</th> 
                                    <th>Outgoing Phone Number</th>  
                                    <th>Last Modified By</th>
                                    <th>Last Modified On</th>                
                                    <th>Action</th>
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
    <div class="modal fade" id="add_practice_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 635px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading1">Add Practice</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('create_org_practice') }}" method="POST" name="AddPracticeForm" id="AddPracticeForm">
                    {{ csrf_field() }}
                    <input type="hidden" id="checkcounter" value="">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Practice  <span style="color:red">*</span></label>
                                    @text("name", ["placeholder" => "Enter Practice Name"])
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Practice Number <span style="color:red">*</span></label>
                                    @text("number",["placeholder" => "Enter Practice Number"])
                                </div>
                                <div class="col-md-4 form-group mb-3 ">
                                    <label for="practicename">Location <span style="color:red">*</span></label>
                                    @text("location",["placeholder" => "Enter Location"])
                                </div>
                                <div class="col-md-4 form-group mb-3 ">
                                    <label for="practicename">Phone Number <span style="color:red">*</span></label>
                                    @phone("phone",["placeholder" => "Enter Phone Number"])
                                </div>
                                <div class="col-md-4 form-group mb-3 "> 
                                    <label for="practicename">Key Contact <span style="color:red">*</span></label>
                                    @text("key_contact",["placeholder" => "Enter Key Contacts"])
                                </div>
                                <div class="col-md-6 form-group mb-3 ">
                                    <label for="practicename">Outgoing Phone Number <span style="color:red">*</span></label>
                                    @phone("outgoing_phone_number",["placeholder" => "Enter Outgoing Phone Number"])
                                </div>
                                <div class="col-md-12  form-group mb-3 ">
                                    <label for="practicename">Address <span style="color:red">*</span></label>
                                    <textarea class="form-control forms-element" name="address" placeholder = "Enter Address"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>                   
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file"><span class="error"></span>Upload logo</label>
                                        @file("file", ["id" => "logo", "class" => "form-control",'onchange'=>"uploadfile()"])
                                        <input type="hidden" name="image_path" id="image_path">
                                        <br/>
                                        <div id="viewlogo"> </div>
                                        <!-- <input type="hidden" name="profile_img" id="profile_img"> -->
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="uploading_img_loader" style="display:none;" class="loader-bubble loader-bubble-primary m-5"></div>
                                </div>
                            </div>
                            <div style="display: none">
                                @selectprovidertypes("provider_type_id[]",["id"=>"providertype", "onchange" => "providerSubtype(this)"])
                                @selectspecialpractices("provider_subtype_id[]",["id"=>"provider_subtype"])
                            </div>                
                            <div class="form-group" id="appendprovider"></div> 
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"  class="btn  btn-primary m-1 save_practice">Save</button>
                                    <!-- <button type="button" class="btn btn-info float-left additionalProvider" id="additionalProvider">Add Provider</button> -->
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script type="text/javascript">
        var renderPracticeTable =  function() {
            var columns= [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'location',name: 'location'},
                {data: 'number', name: 'number'},
                {data: 'address', data:'address'},
                {data: 'phone', name: 'phone'},
                {data: 'key_contact', name: 'key_contact'},
                {data: 'outgoing_phone_number', name: 'outgoing_phone_number'},
                {data: 'users',
                    mRender: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            l_name = data['l_name'];
                            if(data['l_name'] == null && data['f_name'] == null){
                              l_name = '';
                              return '';
                            } else {
                              return data['f_name'] + ' ' + l_name;
                            }
                        } else { 
                            return ''; 
                        }    
                    },orderable: false
                }, 
                {data: 'updated_at',name:'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
            var table = util.renderDataTable('practiceList', "{{ route('org_practices_list') }}", columns, "{{ asset('') }}");   
        };

        $(document).ready(function() {
            practices.init();
            renderPracticeTable();
            // $("[name='provider_type_id']").on("change", function () {
            //   util.updateProviderSubtype(parseInt($(this).val()), $("#provider_subtype"));
            // });
            util.getToDoListData(0, {{getPageModuleName()}});
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function providerSubtype(val) {
            var provider_type_id = val.value;
            var id_count = (val.id).substring((val.id).indexOf('_') + 1);
            $.ajax({
                type: 'post',
                url: '/org/subtypeProviders', 
                data: 'provider_type_id=' + provider_type_id,
                success: function(response) {
                    $("#provider_subtype_"+id_count).html(response);
                },
            });
        }

        function uploadfile() {   
            var file_data = $("#logo").prop("files")[0];   
            var form_data = new FormData();    
            form_data.append("file", file_data);
            $("#uploading_img_loader").show();
            $(".save_practice").prop('disabled', true);
            $.ajax({
                url: '/org/ajax/uploadImage',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                enctype: 'multipart/form-data',
                success: function(data) {          
                    $('#image_path').val($.trim(data));
                    $("#uploading_img_loader").hide();
                    $(".save_practice").prop('disabled', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    </script>
@endsection