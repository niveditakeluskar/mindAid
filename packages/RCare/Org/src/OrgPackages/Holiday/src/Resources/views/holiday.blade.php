@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
    <style>
    th, td {
        padding-left: 10px;
    } 
    </style>
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
       <h4 class="card-title mb-3">Holidays</h4>
    </div>
     <div class="col-md-1">
     <a class="" href="javascript:void(0)" id="addholidays"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Holiday"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Holiday"></i></a>  
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
                <div id="msg"></div>
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="holiday-list" class="display datatable table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Last Modifed By</th>
                            <th>Last Modifed On</th> 
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

<div class="modal fade" id="add_holiday_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add Holiday</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <div class="modal-body">
                <form action="{{ route("ajax.save.holiday")}}" method="post" name ="holiday_form"  id="holiday_form">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    @include('Holiday::holiday-add')
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
     $('#addholidays').click(function () {
        //alert("working");
		$('#appendcode').html('');
		$("form[name='holiday_form'] #event").removeClass('is-invalid');
		$("form[name='holiday_form'] #event").next('.invalid-feedback').html('');
		$("form[name='holiday_form'] #date").removeClass('is-invalid');
		$("form[name='holiday_form'] #date").next('.invalid-feedback').html('');
		$("#modelHeading1").text('Add Holiday');
		codecount = 0;
		$("#holiday_form")[0].reset();
		$('#add_holiday_modal').modal('show');
		$('#id').val("");
	}); 

    var renderHolidayTable =  function() {
            var columns = [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'event',name: 'event'},
                // {data: 'date1',type: 'date', name: 'date',"render":function (value) {
                //    if (value === null) return "";
                //         return util.viewsDateFormatWithTime(value);
                //     }
                // },
                {data: 'date1', name: 'date'},      
                {data: 'day', name: 'day'},            
                {data: 'f_name', name: 'f_name',render: 
                    function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.l_name;
                        } else { 
                            return '';
                        }
                    }
                },     
                {data: 'updated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at',"render":function (value) {
                   if (value === null) return "";
                        return util.viewsDateFormatWithTime(value);
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
            var table = util.renderDataTable('holiday-list', "{{ route('holidays_list') }}", columns, "{{ asset('') }}"); 
    }

    $(document).ready(function() {
        holiday.init();
        renderHolidayTable();
        // util.getToDoListData(0, {{getPageModuleName()}});
    });

    </script>
@endsection