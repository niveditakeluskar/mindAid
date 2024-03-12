@extends('Theme::layouts.master')
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div id="app">
	<div id="success">
	</div>	
	<div class="card-body" style=" margin-left: 260px; margin-right: 295px;">
		<div class="row mb-4">
			<div class="col-md-12  mb-4">
				<div class="card mb-4">
					<div class="card-header mb-3">Add Diagnosis Code</div>
						<div class="card-body" id="hobby">
							<form action="{{ route("ajax.save.diagnosis")}}" method="post" name ="main_diagnosis_form"  id="main_diagnosis_form">
								@csrf
								<div class="alert alert-success" id="success-alert" style="display: none;">
									<button type="button" class="close" data-dismiss="alert">x</button>
									<strong> Diagnosis Code Add successfully! </strong><span id="text"></span>
								</div>
								@include('Diagnosis::diagnosis')
							</form>
						</div>	
					</div>	
				</div>	
			</div>	
		</div>	
	</div>	

</div>
@endsection
@section('page-js')
	<script type="text/javascript">
		$(document).ready(function() {
			diagnosisCode.init();
			util.getToDoListData(0, {{getPageModuleName()}});
		});
	</script>
@endsection