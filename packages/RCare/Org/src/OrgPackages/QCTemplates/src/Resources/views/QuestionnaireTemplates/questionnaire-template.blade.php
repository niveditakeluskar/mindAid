@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-10">
            <h4 class="card-title mb-3">Questionnaire Template</h4>
        </div>
        <div class="col-md-1">
            <!-- <a class="btn btn-success btn-sm " href="addTemplate" > Add Questionnaire Template</a>   -->
            <a class="" href="add-questionnaire-template"><i class="add-icons i-Blinklist" data-toggle="tooltip" data-placement="top" title="Add Questionnaire Template" style="margin-right:-7px"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Questionnaire Template"></i></a> 
        </div> 
        <div class="col-md-1">
            <!-- <a class="btn btn-success btn-sm " href="addTemplate" > Add Questionnaire Template</a>   -->
            <a class="" href="copy-questionnaire-template"><i class="add-icons i-Data-Copy" data-toggle="tooltip" data-placement="top" title="Copy questionnaire Template" style="margin-right: -7px"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Copy questionnaire Template"></i></a> 
        </div> 
    </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="mailList" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Questionnaire Name</th>
                                <th>Type</th> 
                                <th>Module</th>
                                <th>Sub Module</th>
                                <th>Stage</th>
                                <th>Step</th> 
                                <th>Sequence</th>
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
                    {data: 'template.template_type', name: 'template.template_type'},
                    {data: 'module.module', name: 'module.module'},
                    {data: 'components.components', name: 'components.components'},
                    {data: 'stage.description', name: 'stage.description',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data;
                        }else{return '';}
                        }
                    },
                    {data: 'step.description', name: 'step.description',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data;
                        }else{return '';}
                        }
                    },
                    {data: 'sequence', name: 'sequence'},
                    {data: 'users.f_name', name: 'users.f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.users.l_name;
                        }else{return '';}
                        }
                    },
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];
        var url = $(location).attr('href');
        var secondLevelLocation = url.split('/').reverse()[1];
        if(secondLevelLocation == 'rpm') {
            var table = util.renderDataTable('mailList', "{{ route('rpm-questionnaire-template') }}", columns, "{{ asset('') }}");  
        } else if(secondLevelLocation == 'ccm'){
            var table = util.renderDataTable('mailList', "{{ route('ccm-questionnaire-template') }}", columns, "{{ asset('') }}");  
        } else if(secondLevelLocation == 'patients'){
            var table = util.renderDataTable('mailList', "{{ route('patients-questionnaire-template') }}", columns, "{{ asset('') }}");  
        } 
        $(document).ready(function() {
            util.getToDoListData(0, {{getPageModuleName()}});
        });
         
          $('table').on('draw.dt', function()
         {
    $('[data-toggle="tooltip"]').tooltip();

});
    </script>
@endsection