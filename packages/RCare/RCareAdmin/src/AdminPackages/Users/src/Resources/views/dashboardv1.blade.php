@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
    <div class="breadcrumb">
        <h1>Dashboard</h1>
        <!--ul>
            <li><a href="">Dashboard</a></li>
            <li>Version 1</li>
        </ul-->
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div class="row">
        <!-- ICON BG -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Hotel"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">Organization</p>
                        <p class="text-primary text-24 line-height-1 mb-2" id="orgs"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Receipt-3"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">License</p>
                        <p class="text-primary text-24 line-height-1 mb-2" id="license"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Professor"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">Roles</p>
                        <p class="text-primary text-24 line-height-1 mb-2" id="roles"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Business-ManWoman"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">Users</p>
                        <p class="text-primary text-24 line-height-1 mb-2" id="users"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>     
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card o-hidden mb-4">
                <div class="card-header d-flex align-items-center border-0">
                    <h3 class="w-50 float-left card-title m-0">Organization</h3>
                </div>
                <div class="card-body">
                    <!--div class="card-title">Organization</div-->
                    <div class="table-responsive">
                        <table id="orgs-list" class="display table table-striped table-bordered" style="width:100%">
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
    <script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
    <script src="{{asset('assets/js/es5/echart.options.min.js')}}"></script>
    <script src="{{asset('assets/js/es5/dashboard.v1.script.js')}}"></script>
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script type="text/javascript">
        // $(function () {
        //     var table = $('#orgs-list').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         hover:true,
        //         ajax: "{{ route('org_list') }}",
        //         columns: [
        //             {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        //             {data: 'name', name: 'name'},
        //             {data: 'email', name: 'email'},
        //             {data: 'phone', name: 'phone'},
        //             {data: 'contact_person', name: 'contact_person'},
        //             {data: 'contact_person_email', name: 'contact_person_email'},
        //             {data: 'city', name: 'city'},
        //             {data: 'state', name: 'state'},      
        //             {data: 'action', name: 'action', orderable: false, searchable: false},
        //         ]
        //     });
        //     $('#orgs-list').on( 'mouseenter', 'tbody tr', function () {
        //         var rowData = table.row( this ).data();
        //         // ... show tool tip
        //     });
        // });

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
        var table = util.renderDataTable('orgs-list', "{{ route('org_list') }}", columns, "{{ asset('') }}"); 


        $(document).ready(function () {
            var val = "counts";
            var org_url = "{{ route('getOrg') }}";
            $.ajax({
                type: 'post',
                url: org_url,
                data: {
                    _token: '{!! csrf_token() !!}',
                    service:val
                },
                success: function (response) {
                    //alert(response);
                    document.getElementById("orgs").innerHTML=response; 
                }
            });

            var lic_url = "{{ route('getLic') }}";
            $.ajax({
                type: 'post',
                url: lic_url,
                data: {
                    _token: '{!! csrf_token() !!}',
                    service:val
                },
                success: function (response) {
                    //alert(response);
                    document.getElementById("license").innerHTML=response; 
                }
            });

            var roles_url = "{{ route('getRoles') }}";
            $.ajax({
                type: 'post',
                url: roles_url,
                data: {
                    _token: '{!! csrf_token() !!}',
                    service:val
                },
                success: function (response) {
                    //alert(response);
                    document.getElementById("roles").innerHTML=response; 
                }
            });

            var users_url = "{{ route('getUsers') }}";
            $.ajax({
                type: 'post',
                url: users_url,
                data: {
                    _token: '{!! csrf_token() !!}',
                    service:val
                },
                success: function (response) {
                    //alert(response);
                    document.getElementById("users").innerHTML=response; 
                }
            });
        });
    </script>
@endsection