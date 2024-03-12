@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Assign Modules</h4>
                </div>
                 <div class="col-md-1">
                 <!--a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"></a-->  
                </div>
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">

            <div class="card-body">
				<!--h4 class="card-title mb-3">Role</h4-->
                @include('Theme::layouts.flash-message')
               <form method="post" action="{{ route('AssignRolesComponent') }}">
		            @csrf
                <div class="table-responsive">
                    <table id="usersRolesAssign" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                        <th>Components / Roles</th>
                        @foreach($data as $dt)
                            
                            <th>{{$dt->role_name}}</th>
			            @endforeach
                        </tr>
                    </thead>
                    <tbody>
			
			@foreach($sdata as $service)
					
					<tr>
						<td>{{ $service->components}}</td>
						@foreach($data as $dt)
					
						<td>
								                
                                <input type="hidden" name="role_id[]" value="{{$dt->id}}">
                                <input type="hidden" name="module_id[]" value="{{$service->module_id}}">
								<input type="hidden" name="components_id[]" value="{{$service->id}}">
								<ul class="list-group list-group-flush">
                    							<li class="list-group-item">C
  									<label class="switch ">
									<input type="checkbox" class="default" id="c{{$dt->id.$service->id}}" value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$service->id && strpos($crud,'c')!==false) {echo 'checked'; }} ?> >
									<span class="slider round"></span>
									</label>
									<input name="create[]" id="vc{{$dt->id.$service->id}}"  type="hidden" value="">
									</li>
                    							<li class="list-group-item">R
									<label class="switch">
									<input type="checkbox" class="default" id="r{{$dt->id.$service->id}}"  value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$service->id && strpos($crud,'r')!==false) {echo 'checked'; }} ?> >
									<span class="slider round"></span></label>
									<input name="read[]" id="vr{{$dt->id.$service->id}}"  type="hidden" value="">
									</li>
                    							<li class="list-group-item">U
									<label class="switch"><input type="checkbox" class="default" id="u{{$dt->id.$service->id}}"  value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$service->id && strpos($crud,'u')!==false) {echo 'checked'; }} ?> >
									<span class="slider round"></span>
									</label>
									<input name="update[]" id="vu{{$dt->id.$service->id}}"   type="hidden" value="">
									</li>
                    							<li class="list-group-item">D		
									<label class="switch"><input type="checkbox" class="default" id="d{{$dt->id.$service->id}}"  value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$service->id && strpos($crud,'d')!==false) {echo 'checked'; }} ?> >
									<span class="slider round"></span>
									</label>
									<input name="delete[]" id="vd{{$dt->id.$service->id}}"  type="hidden" value="">
									</li>
								</ul>

						</td>	
						@endforeach
					</tr>
			@endforeach
			
                    </tbody>
                </table>
                </div>
		<button type="submit" class="btn btn-info btn-lg btn-block">Assign</button>
		</form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')
     
<script>
$(document).ready(function() {

$('input[type="checkbox"]').change(function() { 
		var id = $(this).attr('id');
    if ($(this).is(':checked')) {
	var vid = id.charAt(0); 
        $('#v'+id).val(vid);
    } else {
        $('#v'+id).val('');
    }
}).change();
util.getToDoListData(0, {{getPageModuleName()}});
});
</script>
@endsection

 

 