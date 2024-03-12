@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-10">
                   <h4 class="card-title mb-3">Content Template</h4>
                </div>
                <div class="col-md-1">
                 <!-- <a class="btn btn-success btn-sm " href="addTemplate" > Add Content Template</a>   -->
                 <a class="" href="addTemplate"><i class="add-icons i-Split-Horizontal-2-Window" data-toggle="tooltip" data-placement="top" title="Add Content Template"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Content Template"></i></a> 
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
                
                <div class="table-responsive">
                    <table id="mailList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Content Name</th>
                            <th>Content Type</th> 
                            <!-- <th>Services</th> -->
                            <th>Sub Services</th>                 
                            <!-- <th>Content</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php //$i=1; ?>
                        {{-- @foreach($data as $value) --}}
                        <!-- <tr>
                            <td><?php //echo $i++; ?></td>
                            <td>{{-- $value->content_title --}}</td>
                            <td>{{-- $value->template->template_type --}}</td>
                           
                            <td>{{-- $value->subservice['sub_services'] --}}</td>
                            <td>{{-- $value->content --}}</td>
                            <td>
                                <a href="updatemailtemplate/{{-- $value->id --}}" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>
                                <a href="viewTemplateDetails/{{-- $value->id --}}"><i class="text-15 i-Eye" style="color: green;"></i></a>
                                <a href="deletemailtemplate/{{-- $value->id --}}"><i class=" i-Close" style="color: #2cb8ea;"></i></a>
                            </td>
                        </tr> -->
                        {{-- @endforeach --}}
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
    <script type="text/javascript">
        
        var columns= [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'content_title', name: 'content_title', render: function(data, type, full, meta){
                            var name = "<span class='capital-first'>" + data + "</span>";
                            return name
                        }
                    },
                    {data: 'service.module', name: 'module'},
                    {data: 'subservice.sub_services', name: 'sub_services'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];
        var table = util.renderDataTable('mailList', "{{ route('listmailtemplate') }}", columns, "{{ asset('') }}");   

    </script>
@endsection

 