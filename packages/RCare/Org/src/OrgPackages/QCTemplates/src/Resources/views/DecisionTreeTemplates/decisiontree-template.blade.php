 @extends('Theme::layouts.master')
@section('page-css')

    <style> 
        .disabled { 
            pointer-events: none; 
            cursor: default; 
        } 
		
		.table-responsive {
			display: block;
			width: 95%;
			overflow-x: auto;
			margin: auto;
		}
		button.dt-button {
			border-style: none !important;
			background: none;
			cursor:pointer;
		}
		.paginate_button{
			cursor:pointer;
		}
    </style>
    <!--Andy22Nov21 (Not working link after move public folder)-->
     
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> 
		<style>
		table.dataTable{
			border-bottom:1px solid #ced4da;
		}
		table.dataTable thead th {
			vertical-align: bottom;
			border-bottom: 2px solid #9E9E9E !important;
		}
		.dataTables_filter input{
			outline: initial !important;
			background: #f8f9fa;
			border: 1px solid #ced4da;
			color: #fff;
		}
		.dataTables_filter input {
			background-image: url(https://img.icons8.com/search);
			background-position-y: center;
			background-position-x: 95%;
			background-size: contain;
			background-repeat: no-repeat;
			background-size: 18px 17px !important;
			margin-right:4px;
            color:black!important;
		}
		.dataTables_filter label{
			color:#fff;
		}
		button.dt-button.buttons-copy, button.dt-button.buttons-pdf, button.dt-button.buttons-excel, button.dt-button.buttons-csv{
			background:none;
			background-image:none;
			margin:0px;
			padding:0px 2px;
		}
		</style>
             <link rel="stylesheet" href="{{asset('assets/styles/external-css/buttons.dataTables.min.css')}}"> 

 	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css"> 
	<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables/editor.dataTables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-10">
            <h4 class="card-title mb-3">Decision Tree Template</h4>
        </div>
        <div class="col-md-1">
            <!-- <a class="btn btn-success btn-sm " href="addTemplate" > Add Questionnaire Template</a>   -->
            <a class="" href="add-decisiontree-template"><i class="add-icons i-Blinklist" data-toggle="tooltip" data-placement="top" title="Add DecisionTree Template" style="margin-right: -7px"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add DecisionTree Template"></i></a> 
        </div> 
        <div class="col-md-1">
            <!-- <a class="btn btn-success btn-sm " href="addTemplate" > Add Questionnaire Template</a>   -->
            <a class="" href="copy-decisiontree-template"><i class="add-icons i-Data-Copy" data-toggle="tooltip" data-placement="top" title="Copy DecisionTree Template" style="margin-right: -7px"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Copy DecisionTree Template"></i></a> 
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
                <div class="table-responsive">
					@csrf
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
                                <th>Last Modifed By</th>
                                <th>Last Modifed On</th>           
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
      <!-- <script src='https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js'></script>		 -->
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script> 

    <script src="{{asset(mix('assets/js/jquery.validate.min.js'))}}"></script>  

    <!--  <script src='https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js'></script> -->
    <script src="{{asset('assets/js/external-js/dataTables.buttons.min.js')}}"></script>
   <!--  <script src="{{asset('assets/js/external-js/vfs_fonts.js')}}"></script> -->

    <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js'></script>
    
    <script src="{{asset('assets/js/vendor/dataTables.select.min.js')}}"></script>
    <script src="{{asset('assets/js/vendor/datatables/dataTables.editor.min.js')}}"></script>
	
    <script type="text/javascript">
     $(document).ready(function(){
		var csrf = $("input[name='_token']").val();
		
        editor = new $.fn.dataTable.Editor( {
                ajax: { url: "{{ route('update.decision.tree.inline')}}", 
                        type:'get',
					},
                
                table: "#mailList",
			//	idSrc:  0,
                fields: [
                    { 
                        label: "Sequence:",
                        name: "sequence"
                    }, 
                ],
				

            });
            
			//$('#callwrap-list').on( 'click', 'tbody td:not(:first-child)', function (e) {
			$('#mailList').on( 'click', 'tbody td:not(:first-child)', function (e) {
				 editor.inline( this, {
					buttons: { label: '&gt;', fn: function () { this.submit(); } }
					//onBlur: 'submit'
				} );
            } );

           // var table =  $('#mailList');
            //table.DataTable().ajax.reload();

    $('body').on('click', '.change_status_decision_template_active', function () {
        var id = $(this).data('id');
        if(confirm("Are you sure you want to Deactivate this Decision Tree Template")){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(location).attr('href');
            var secondLevelLocation = url.split('/').reverse()[1];
                
                if(secondLevelLocation == 'rpm') {
                    var url = '/rpm/delete-questionnaire-template/'+id;  
                } else if(secondLevelLocation == 'ccm'){
                    var url = '/ccm/delete-questionnaire-template/'+id;  
                } else if(secondLevelLocation == 'patients'){
                    var url = '/patients/delete-questionnaire-template/'+id;  
                }
            $.ajax({
                type   : 'get',
                url    : url,
               // data: {"_token": "{{ csrf_token() }}","id": id},
                data   :  {"id": id},
                success: function(response) {
                    getDecisionTreeTable();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Decision Tree Template Deactivated Successfully!</strong></div>';
                    $("#success").html(txt);
                }
            });
        }else{ return false;}
    });
    $('body').on('click', '.change_status_decision_template_deactive', function () {
        var id = $(this).data('id');

        if(confirm("Are you sure you want to Activate this Decision Tree Template")){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(location).attr('href');
            var secondLevelLocation = url.split('/').reverse()[1];
                
                if(secondLevelLocation == 'rpm') {
                    var url = '/rpm/delete-questionnaire-template/'+id;  
                } else if(secondLevelLocation == 'ccm'){
                    var url = '/ccm/delete-questionnaire-template/'+id;  
                } else if(secondLevelLocation == 'patients'){
                    var url = '/patients/delete-questionnaire-template/'+id;  
                }
            $.ajax({
                type   : 'get',
                url    : url,
               // data: {"_token": "{{ csrf_token() }}","id": id},
                data   :  {"id": id},
                success: function(response) {
                    getDecisionTreeTable();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Decision Tree Template Activated Successfully!</strong></div>';
                    $("#success").html(txt);
                }
            });
        }else{ return false;}
    });

        });    
    var getDecisionTreeTable =  function() {
        var columns= [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'content_title', name: 'content_title', render: function(data, type, full, meta){
                            var name = "<span class='capital-first'>" + data + "</span>";
                            return name
                        }
                    },
                    {data: 'template_type', name: 'template_type'},
                    {data: 'module', name: 'module'},
                    {data: 'components', name: 'components'},
                    {data: 'stage', name: 'stage'},
                    {data: 'step', name: 'step'},
                    {data: 'sequence', name: 'sequence'},
                    {data: 'f_name', name: 'f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.l_name;
                        }else{ return '';}
                        }
                    },
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];
        var url = $(location).attr('href');
        var secondLevelLocation = url.split('/').reverse()[1];
		
        if(secondLevelLocation == 'rpm') {
            var table = util.renderDataTable('mailList', "{{ route('rpm-decisiontree-template') }}", columns, "{{ asset('') }}");  
        } else if(secondLevelLocation == 'ccm'){
            var table = util.renderDataTable('mailList', "{{ route('ccm-decisiontree-template') }}", columns, "{{ asset('') }}");  
        } else if(secondLevelLocation == 'patients'){
            var table = util.renderDataTable('mailList', "{{ route('patients-decisiontree-template') }}", columns, "{{ asset('') }}");  
        }
    };
 
        $(document).ready(function() {
            getDecisionTreeTable();
            util.getToDoListData(0, {{getPageModuleName()}});
        });
    </script>
@endsection