@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/header_fixed.css')}}">

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
	<div class="alert alert-success" id="acces-success-alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Role assigned successfully! </strong><span id="text"></span>
    </div>	
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">

            <div class="card-body">
				<!--h4 class="card-title mb-3">Role</h4-->
                @include('Theme::layouts.flash-message')
               <form method="post" action="{{ route('AssignRolesComponent') }}">
		            @csrf
                <div class="table-responsive">
                    <table id="usersRolesAssign" class="display table table-striped table-bordered fixed_header" style="width:100%">
                    <thead>
                        <tr>
                        <th>Components / Roles</th>
                        @foreach($data as $dt)
                            
                            <th>{{$dt->role_name}}</th>
			            @endforeach
                        </tr>
                    </thead>
                    <tbody>
			
			@foreach($moduledata as $service)
					
					<tr> 
						<td>{{ $service->module}} <br><br><br><?php foreach($sdata as $services){ if($service->id==$services->module_id){ echo $services->components."<br><br><br><br><br><br><br><br><br>";} } ?></td> 
						@foreach($data as $dt)
						<td>
						<div style="display:flex">
                            <label class="checkbox checkbox-primary col-md-2 float-left">
                                <input type="button" id="c{{$dt->id.$service->id}}" onclick="multicheck(this.id)">
                                <span>C</span>
                                <span class="checkmark"></span>
                            </label>
                            <label class="checkbox checkbox-primary col-md-2 float-left">
                                <input type="button" id="r{{$dt->id.$service->id}}" onclick="multicheck(this.id)" >
                                <span>R</span>
                                <span class="checkmark"></span>
                            </label>
                            <label class="checkbox checkbox-primary col-md-2 float-left">
                                <input type="button" id="u{{$dt->id.$service->id}}" onclick="multicheck(this.id)">
                                <span>U</span>
                                <span class="checkmark"></span>
							</label>
							<label class="checkbox checkbox-primary col-md-2 float-left">	
                                <input type="button" id="d{{$dt->id.$service->id}}" onclick="multicheck(this.id)">
                                <span>D</span>
                                <span class="checkmark"></span>
                            </label>
                        </div>
						
						@foreach($sdata as $services)
						<?php if($service->id==$services->module_id){ ?> 
												<input type="hidden" name="role_id[]" value="{{$dt->id}}">
												<input type="hidden" name="module_id[]" value="{{$service->id}}">
												
												<input type="hidden" name="components_id[]" value="{{$services->id}}"> 
											
												<ul class="list-group list-group-flush">
																<li class="list-group-item">C
													  <label class="switch ">
													<input type="checkbox" class="c{{$dt->id.$service->id}}" id="c{{$dt->id.$services->id}}" value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$services->id && strpos($crud,'c')!==false) {echo 'checked'; }} ?> >
													<span class="slider round"></span>
													</label>
													<input name="create[]" id="vc{{$dt->id.$services->id}}" class="vc{{$dt->id.$service->id}}"  type="hidden" value="">
													</li>
																<li class="list-group-item">R
													<label class="switch">
													<input type="checkbox" class="r{{$dt->id.$service->id}}" id="r{{$dt->id.$services->id}}"  value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$services->id && strpos($crud,'r')!==false) {echo 'checked'; }} ?> >
													<span class="slider round"></span></label>
													<input name="read[]" id="vr{{$dt->id.$services->id}}"  class="vr{{$dt->id.$service->id}}" type="hidden" value="">
													</li>
																<li class="list-group-item">U
													<label class="switch"><input type="checkbox" class="u{{$dt->id.$service->id}}" id="u{{$dt->id.$services->id}}"  value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$services->id && strpos($crud,'u')!==false) {echo 'checked'; }} ?> >
													<span class="slider round"></span>
													</label>
													<input name="update[]" id="vu{{$dt->id.$services->id}}" class="vu{{$dt->id.$service->id}}"  type="hidden" value="">
													</li>
																<li class="list-group-item">D		
													<label class="switch"><input type="checkbox" class="d{{$dt->id.$service->id}}" id="d{{$dt->id.$services->id}}"  value="" <?php foreach($adata as $adt){ $r=$adt->role_id;$s=$adt->components_id;$crud=$adt->crud; if($r==$dt->id && $s==$services->id && strpos($crud,'d')!==false) {echo 'checked'; }} ?> >
													<span class="slider round"></span>
													</label>
													<input name="delete[]" id="vd{{$dt->id.$services->id}}" class="vd{{$dt->id.$service->id}}" type="hidden" value="">
													</li>
												</ul>
								<?php } ?>				
							@endforeach		
							</td>	
						
						@endforeach
					</tr>
			@endforeach
			
                    </tbody>
                </table>
                </div>
		<button type="button" id="save_access_matrix" class="btn btn-info btn-lg btn-block">Assign</button>
		</form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')
     
<script>
	
$(document).ready(function() {
var value = 1;

$('input[type="checkbox"]').change(function() { 
		var id = $(this).attr('id');
		//alert('1');
			//$(":checkbox[class='" + id + "']").prop("checked", this.checked);
		
    if ($(this).is(':checked')) {
	var vid = id.charAt(0); 
        $('#v'+id).val(vid);
		
    } else {
        $('#v'+id).val('');
		

    }
}).change();
util.getToDoListData(0, {{getPageModuleName()}});
});
var clicked = false;
function multicheck(cid){
	
	$(":checkbox[class='" + cid + "']").prop("checked", !clicked);
	
	clicked=!clicked;
	if($("."+cid).is(':checked')){
		$('#'+cid).nextAll().eq(1).css('background', '#2cb7e9');
		var vid = cid.charAt(0); 
		$('.v'+cid).val(vid);
	}else{
		$('#'+cid).nextAll().eq(1).css('background', '#dee2e6');
		$('.v'+cid).val('');
	}
	}
	$('#save_access_matrix').click(function() { 
		var role_id = $( "input[name='role_id[]']" ).serializeArray();
		var module_id = $( "input[name='module_id[]']" ).serializeArray();
		var components_id = $( "input[name='components_id[]']" ).serializeArray();
		var create = $( "input[name='create[]']" ).serializeArray();
		var read = $( "input[name='read[]']" ).serializeArray();
		var update = $( "input[name='update[]']" ).serializeArray();
		var delete_array = $( "input[name='delete[]']" ).serializeArray();
		//alert(create.length);
		var token = "{{ csrf_token() }}";
		var datastring = '_token=' + token + '&role_id=' + role_id + '&module_id='+module_id + '&components_id='+components_id + '&create='+create + '&read='+read + '&update='+update + '&delete='+delete_array;
		var json_role = JSON.stringify(role_id);
		var json_module = JSON.stringify(module_id);
		var json_components = JSON.stringify(components_id);
		var json_create = JSON.stringify(create);
		var json_read = JSON.stringify(read);
		var json_update = JSON.stringify(update);
		var json_delete_array = JSON.stringify(delete_array);
		$("#preloader").show();
		$.ajax({
                    type: 'post',
					url: '/org/AssignRolesComponent',
					//data: JSON.stringify(datastring),
					data: {
						"_token": "{{ csrf_token() }}",
						'role_id': json_role,
						'module_id' :json_module,
						'components_id' : json_components ,
						'create' : json_create ,
						'read' : json_read ,
						'update' : json_update ,
						'delete' : json_delete_array
					},
					//data: "_token": "{{ csrf_token() }}",'role_id=' + role_id + '&module_id='+module_id + '&components_id='+components_id + '&create='+create + '&read='+read + '&update='+update + '&delete='+delete_array,
                    success: function(response) {
						$("#preloader").hide();
						$('#acces-success-alert').show();
						
                    },
                });

		//alert(users.length);
	});
</script>
@endsection

 

 