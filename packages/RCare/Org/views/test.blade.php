@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Test </h4>
                </div>
                 <div class="col-md-1">
                 <!-- <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>   -->
                 <a class="btn btn-success btn-sm " href="{{ route('test-add') }}" id="addUser"> Add Test User</a>
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
                    <table id="testList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>							
                            <th width="200px">Action</th>
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

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}" defer></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('#testList').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('list_test') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'f_name', name: 'f_name'},
                    {data: 'l_name', name: 'l_name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script> 

@endsection

 