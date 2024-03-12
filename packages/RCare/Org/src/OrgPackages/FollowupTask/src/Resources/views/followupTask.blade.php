@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
       <h4 class="card-title mb-3">Follow up Task</h4>
    </div>
     <div class="col-md-1">
     <a class="" href="javascript:void(0)" id="addFollowupTask"><i class="add-icons i-Split-Horizontal-2-Window" data-toggle="tooltip" data-placement="top" title="Add Followup Task"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Followup Task"></i></a>  
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
                    <table id="followupTask-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th width="80px">Task</th>
                            <th width="30px">Last Modifed By</th>
                            <th width="30px">Last Modifed On</th> 
                            <th width="40px">Action</th>
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

<!-- Add medication -->
<div class="modal fade" id="add_followuptask_modal" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <form action="{{ route("add-task")}}" method="post" name ="followuptask_form"  id="followuptask_form">
            <div class="modal-body">
                @csrf
                <label for="time">Task</label>
                <input type="hidden" name="id" id="id">
                @text('task',['id'=>'task'])
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary float-right" id="task_submit">Submit</button>
                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div> 

@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
        var renderFollowupTaskTable =  function() {
            var columns = [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'task',name: 'task'},
                {data:'users',
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
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
            var table = util.renderDataTable('followupTask-list', "{{ route('list-task') }}", columns, "{{ asset('') }}"); 
        } 
        $(document).ready(function() {
            followuptask.init();
            renderFollowupTaskTable();
            util.getToDoListData(0, {{getPageModuleName()}});
        });

</script>
@endsection