@extends('Theme::layouts.master')
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
	<div id="success">
	</div>	
	<div class="card-body">
		<div class="row mb-4">
			<div class="col-md-12  mb-4">
				<div class="card mb-4">
					<div class="card-header mb-3">Edit Devices </div>
						<div class="card-body" id="hobby">
							<form action="{{ route("ajax.save.devices")}}" method="post" name ="main_edit_devices_form"  id="main_edit_devices_form">
								@csrf
								<div class="alert alert-success" id="success-alert" style="display: none;">
									<button type="button" class="close" data-dismiss="alert">x</button>
									<strong> Devices  Updated successfully! </strong><span id="text"></span>
								</div>
								<input type="hidden" name="id" value="">
								@include('Devices::devices')
							</form>
						</div>	
					</div>	
				</div>	
			</div>	
		</div>	
	</div>	
	<div id="app"></div>
@endsection
@section('page-js')
	<script type="text/javascript">
		$(document).ready(function() {
			devices.init();
			util.getToDoListData(0, {{getPageModuleName()}});
		});
	</script>
@endsection